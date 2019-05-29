<?php

if (PHP_SAPI != 'cli') {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$_SERVER['SCRIPT_FILENAME'] = __FILE__;

$project_base = dirname(dirname(dirname(__FILE__)));

if (!is_dir($project_base.'/framework') && file_exists(__DIR__ . '/backup_database_ss4.php')) {
	// let's assume SS4 and relieve ourself of duty
	return include __DIR__ . '/backup_database_ss4.php';
}

chdir(dirname(dirname(dirname(__FILE__))).'/framework');
require_once 'core/Core.php';

$outfile = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : $outfile = Director::baseFolder().'/mysite/scripts/backup-'.date('Y-m-d-H-i-s').'.sql.gz';

global $databaseConfig;

switch ($databaseConfig['type']) {
	case 'MySQLDatabase':
		$u = $databaseConfig['username'];
		$p = $databaseConfig['password'];
		$h = $databaseConfig['server'];
		$d = $databaseConfig['database'];

		$cmd = "mysqldump --user=".escapeshellarg($u)." --password=".escapeshellarg($p)." --ignore-table=$d.details --host=".escapeshellarg($h)." ".escapeshellarg($d)." --max_allowed_packet=512M | gzip > ".escapeshellarg($outfile);
		exec($cmd);
		break;
	case 'SQLiteDatabase':
	case 'SQLite3Database':
		$d = $databaseConfig['database'];
		$path = realpath(dirname(__FILE__).'/../../assets/.sqlitedb/'.$d);
		$cmd = "sqlite3 ".escapeshellarg($path)." .dump | gzip > ".escapeshellarg($outfile);
		exec($cmd);
		break;
	default: break;
}
