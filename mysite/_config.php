<?php

global $project;
$project = 'mysite';


include_once dirname(__FILE__).'/local.conf.php';

// Sites running on the following servers will be
// run in development mode. See
// http://doc.silverstripe.com/doku.php?id=devmode
// for a description of what dev mode does.
Director::set_dev_servers(array(
	'127.0.0.1',
));

if (!defined('SS_LOG_FILE')) {
	define('SS_LOG_FILE', '/var/log/silverstripe/'.basename(dirname(dirname(__FILE__))).'.log');
}

SS_Log::add_writer(new SS_LogFileWriter(SS_LOG_FILE), SS_Log::NOTICE, '<=');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.com/themes/
SSViewer::set_theme('dew');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

MySQLDatabase::set_connection_charset('utf8');

// necessary for now
SQLite3Database::$vacuum = false;
