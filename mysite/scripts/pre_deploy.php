<?php

include_once dirname(__FILE__).'/deploy_helpers.php';

$oldPath = old_path();
$newPath = site_base();
$MYSITE_BASE = mysite_base();

if (is_ss4()) {
    // .env file
    $env = $oldPath . '/.env';
    if (file_exists($env)) {
        $cmd = "cp $oldPath/.env $newPath/.env";
        $cmds[] = $cmd;
        `$cmd`;
    } else {
        $cmds[] = "Generating env file";
        include __DIR__ . '/generate_env.php';
    }
    if (file_exists("$newPath/_ss_environment.php")) {
        $cmd = "rm $newPath/_ss_environment.php";
        `$cmd`;
    }
}
