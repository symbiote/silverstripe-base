<?php

global $project;
$project = 'mysite';


include_once dirname(__FILE__).'/db.conf.php';
include_once dirname(__FILE__).'/helpers.php';

// Sites running on the following servers will be
// run in development mode. See
// http://doc.silverstripe.com/doku.php?id=devmode
// for a description of what dev mode does.
Director::set_dev_servers(array(
	'localhost',
	'127.0.0.1',
));

if (!defined('SS_LOG_PREFIX')) {
	define('SS_LOG_PREFIX', '/var/log/silverstripe/'.basename(dirname(dirname(__FILE__))));
}

SS_Log::add_writer(new SS_LogFileWriter(SS_LOG_PREFIX.'.error.log'), SS_Log::ERR);
SS_Log::add_writer(new SS_LogFileWriter(SS_LOG_PREFIX.'.notice.log'), SS_Log::NOTICE);

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.com/themes/
SSViewer::set_theme('blackcandy');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();
?>
