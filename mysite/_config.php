<?php

global $project;
$project = 'mysite';

include_once dirname(__FILE__).'/local.conf.php';

if (!defined('SS_LOG_FILE')) {
	define('SS_LOG_FILE', '/var/log/silverstripe/'.basename(dirname(dirname(__FILE__))).'.log');
}

SS_Log::add_writer(new SS_LogFileWriter(SS_LOG_FILE), SS_Log::NOTICE, '<=');


// Sets up relevant cache settings to prevent permission errors
SS_Cache::add_backend('default', 'File', array(
	'cache_dir' => TEMP_FOLDER . DIRECTORY_SEPARATOR . 'cache',
	'hashed_directory_umask' => 2775,
	'cache_file_umask' => 0660
));

require_once 'Zend/Cache.php';
require_once 'Zend/Date.php';
$coreCache = Zend_Cache::factory(
	'Core',
	'File',
	array(),
	array('hashed_directory_umask' => 2775, 'cache_file_umask' => 0660, 'cache_dir' => TEMP_FOLDER)
);

Zend_Date::setOptions(array('cache' => $coreCache));
