<?php

include_once dirname(__FILE__).'/deploy_helpers.php';

$oldPath = old_path();
$MYSITE_BASE = mysite_base();

// handles static cache output dir
if ($oldPath && is_dir("$oldPath/cache")) {
	$cache = "$oldPath/cache";
	$to = site_base();
	$cmd = "cp -R $cache $to";
	$cmds[] = $cmd;
	`$cmd`;
}

// handles xhprof config if it exists
$xhprof_dir = $MYSITE_BASE . '/thirdparty/xhprof';
if (is_dir($xhprof_dir)) {
	$cwd = getcwd();
	chdir($xhprof_dir . '/xhprof_lib/utils');
	if (!file_exists('xhprof_runs.php')) {
		$path_to_link = 'xhprof_runs_mysql.php';
		$cmd = "ln -s $path_to_link xhprof_runs.php";
		$cmds[] = $cmd;
		`$cmd`;
	}

	chdir($cwd);
	

	if ($oldPath) {
		$xhprof_config = $oldPath .'/mysite/thirdparty/xhprof/xhprof_lib/config.php';
		if (file_exists($xhprof_config)) {
			$cmds[] = "copy $xhprof_config $xhprof_dir";
			copy($xhprof_config, $xhprof_dir .'/xhprof_lib/config.php');
		}
	}
}

file_put_contents(dirname(__FILE__).'/pre_deploy.log', implode("\n", $cmds));
