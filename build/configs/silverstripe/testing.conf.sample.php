<?php

global $TESTING_CONFIG;
$TESTING_CONFIG = array(
        "database" => "${testing.db.name}",
	"reporter" => "${testing.reporter}",
	"logfile" => "${testing.logdir}/${testing.logfile}",
// If testing with SQLiteDatabase but running with MySQL, use the following
// 	'type'		=> 'SQLite3Database',
//	'path'		=> ASSETS_PATH . '/.sqlitedb/',
//	'key'		=> 'SQLite3DatabaseKey',
//	'memory'	=> false
);
