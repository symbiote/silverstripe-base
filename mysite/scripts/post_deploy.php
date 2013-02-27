<?php


if (PHP_SAPI != 'cli') {
	header("HTTP/1.0 404 Not Found");
	exit();
}

$cwd = getcwd();

$MYSITE_BASE = realpath(dirname(dirname(__FILE__)));
$SITE_BASE = dirname($MYSITE_BASE);

$_SERVER['SCRIPT_FILENAME'] = __FILE__;
chdir($SITE_BASE.'/framework');
require_once 'core/Core.php';

$releases = dirname($SITE_BASE);
$releases = glob($releases . '/*');
if (count($releases)) {
	$realReleases = array();
	foreach ($releases as $releaseName) {
		if (preg_match('/\d{14}$/', $releaseName)) {
			$realReleases[] = $releaseName;
		} 
	}
	$releases = $realReleases;
	// unset the 'last' one which is the current new release
	unset($releases[count($releases) - 1]);
}

// reorder in newest first, to oldest last
$releases = array_reverse($releases);
$oldPath = null;

$cmds = array();

// find the last good DEPLOYED release
foreach ($releases as $release) {
	if (file_exists($release . '/DEPLOYED')) {
		$oldPath = $release;
		break;
	}
}

// Check for xhprof config if available
$xhprof_dir = $MYSITE_BASE . '/thirdparty/xhprof';

if (is_dir($xhprof_dir)) {
	chdir($xhprof_dir . '/xhprof_lib/utils');
	if (!file_exists('xhprof_runs.php')) {
		$path_to_link = 'xhprof_runs_mysql.php';
		$cmd = "ln -s $path_to_link xhprof_runs.php";
		$cmds[] = $cmd;
		`$cmd`;
	}
	chdir($cwd);

	$xhprof_config = $oldPath .'/mysite/thirdparty/xhprof/xhprof_lib/config.php';
        if (file_exists($xhprof_config)) {
                $cmds[] = "copy $xhprof_config $xhprof_dir";
                copy($xhprof_config, $xhprof_dir .'/xhprof_lib/config.php');
        }

}


// clean up combined files - may need to have this configurable
$combined_base = $SITE_BASE . '/assets/_combinedfiles';
if (is_dir($combined_base)) {
	$files = glob($combined_base.'/*.js');
	foreach ($files as $j) {
		$cmds[] = "unlink $j";
		unlink($j);
	}
	$files = glob($combined_base.'/*.css');
	foreach ($files as $c) {
		$cmds[] = "unlink $c";
		unlink($c);
	}
}

global $SYNC_SCRIPT;

// run the rsync of code changes immediately
// NOTE: This is a hardcoded path; it may need to be updated for stage rsync??
if ($SYNC_SCRIPT && file_exists($SYNC_SCRIPT)) {
	$cmds[] = $SYNC_SCRIPT;
	`$SYNC_SCRIPT`;
}

// now apc clear - relies on apache .htaccess denial for .php scripts except those
// triggered from allowed hosts - in this case localhost
if (file_exists($MYSITE_BASE . '/apc_clear.php')) {
	$url = 'http://localhost/' . Director::baseURL() . '/mysite/apc_clear.php';
	$cmds[] = "GET $url: " . file_get_contents($url);
}

// now do things if we have 'remote' servers configured
global $REMOTE_SERVERS;

if (isset($REMOTE_SERVERS) && count($REMOTE_SERVERS)) {
	foreach ($REMOTE_SERVERS as $server => $config) {
		$user = isset($config['user']) ? $config['user'] : 'deploy';
		$port = isset($config['port']) ? $config['port'] : 22;
		$host = $config['host'];
		$commands = isset($config['cmds']) ? $config['cmds'] : array();
		foreach ($commands as $command) {
			$cmd = "ssh -p $port $user@$host '$command'";
			$cmds[] = $cmd;
			`$cmd`;
		}
	}
}

file_put_contents(dirname(__FILE__).'/deploy.log', implode("\n", $cmds));

