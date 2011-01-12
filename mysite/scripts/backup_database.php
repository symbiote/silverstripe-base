<?php

if (PHP_SAPI != 'cli') {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$outfile = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : $outfile = date('Y-m-d-H-i-s').'.sql';

/**
 * dummy files just so that local.conf.php doesn't complain!
 */
class Security { static function setDefaultAdmin() {} }
class Email { static function setAdminEmail() {} }

include_once dirname(__FILE__).'/../local.conf.php';

$outfile = dirname(__FILE__).'/'.$outfile;

global $databaseConfig;

switch ($databaseConfig['type']) {
	case 'MySQLDatabase':
		$u = $databaseConfig['username'];
		$p = $databaseConfig['password'];
		$h = $databaseConfig['server'];
		$d = $databaseConfig['database'];

		$cmd = "mysqldump --user=$u --password=$p --host=$h $d > ".escapeshellarg($outfile);
		exec($cmd);
		break;
	case 'SQLiteDatabase':
		$d = $databaseConfig['database'];
		$path = realpath(dirname(__FILE__).'/../../assets/.sqlitedb/'.$d);
		$cmd = "sqlite3 ".escapeshellarg($path)." .dump > ".escapeshellarg($outfile);
		exec($cmd);
		break;
	default: break;
}