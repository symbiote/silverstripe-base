<?php
include_once dirname(__FILE__).'/deploy_helpers.php';

$cwd = getcwd();

$SITE_BASE = site_base();
$MYSITE_BASE = mysite_base();

$cmds = array();

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

// now prune the list of releases to the number we want
// @TODO Re-enable command execution once a staging env is in place!!!
$releases = all_releases();

rsort($releases);

$count = count($releases);
$index = $count - 1;
while ($count > 5 && $index > 0) {
	if (!isset($releases[$index])) {
		break;
	}

	$old_release = $releases[$index];

	if (!file_exists("$old_release/KEEP_DEPLOYMENT")) {
		unset($releases[$index]);
		$count = count($releases);
		$cmd = "rm -rf $old_release";
		$cmds[] = $cmd;
		`$cmd`;	
	}
	--$index;
}

file_put_contents(dirname(__FILE__).'/deploy.log', implode("\n", $cmds));


