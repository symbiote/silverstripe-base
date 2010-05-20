<?php

/**
 * A basic script for installing a barebones SilverStripe system for developers. 
 * 
 * Run the following command from the root of the site
 *
 *	php mysite/install/install.php -c install|package -l /silverstripe/location -u dbusername -p dbpass -d dbname -b /base
 *
 * where
 *	-c = the action to perform; install or package
 *	-l = the location of a silverstripe package that has been unzipped
 * 	-m = A CSV listing of modules to be included in the package. It is assumed that the modules exist in the same directory as silverstripe
 *	-u = the username for the database
 *	-p = the password for the database
 *	-d = the database name
 *	-h = (Optional) the hostname of the database
 *	-b = the rewrite base for the website url (eg http://localhost/base). Leave this blank if it is at the root of a website. 
 *	-n = The name of the TAR file to create (silverstripe.tar.gz if not specified)
 * 
 * Note: If running 'package', then other options do not need to be specified. 
 * 
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 */

define('SITE_ROOT_DIR', dirname(dirname(dirname(__FILE__))));
define('INSTALL_DIR', dirname(__FILE__));

$shortopts = 'c:l:m:u:p:d:h:b:';

$options = getopt($shortopts);

$cmd = ifset($options, 'c', 'install');

$cmds = array('install', 'package');

if (!in_array($cmd, $cmds)) {
	echo "You must specify one of " . implode(',', $cmds) . "\n";
	exit();
}

$cmd($options);

function install($options)
{
	// link to appropriate SS folders
	$ssPath = rtrim(ifset($options, 'l'), '/ ');

	if (!$ssPath) {
		exit("You must specify the path to an existing SilverStripe source package via the -l option\n");
	}

	if (!is_dir($ssPath)) {
		exit("$ssPath doesn't exist\n");
	}
	
	$ssDirs = array('sapphire', 'cms', 'jsparty');

	$modules = ifset($options, 'm');
	if ($modules) {
		$ssDirs = array_merge($ssDirs, explode(',', $modules));
	}

	foreach ($ssDirs as $linkDir) {
		if (!is_dir($ssPath . '/' . $linkDir)) {
			exit("Required SilverStripe directory $ssPath/$linkDir does not exist\n");
		}
	}

	foreach ($ssDirs as $linkDir) {
		if (!symlink($ssPath . '/' . $linkDir, SITE_ROOT_DIR . '/' . $linkDir)) {
			exit("Failed creating symlink to $ssPath/$linkDir at ".SITE_ROOT_DIR);
		}
	}

	// HTACCESS CONFIG
	$rewriteBase = ifset($options, 'b', '/');
	$htaccess = file_get_contents(INSTALL_DIR . '/htaccess.sample');
	$htaccess = str_replace('{rewritebase}', $rewriteBase, $htaccess);
	file_put_contents(SITE_ROOT_DIR.'/.htaccess', $htaccess);


	// DB CONFIG
	$dbuser = ifset($options, 'u');
	$dbpass = ifset($options, 'p');
	$dbname = ifset($options, 'd');
	$dbhost = ifset($options, 'h', 'localhost');

	if (!$dbuser || !$dbpass || !$dbname) {
		exit("Database user, password and name need to be set using -u, -p and -d");
	}

	$dbconf = file_get_contents(INSTALL_DIR . '/db.conf.sample.php');
	$dbconf = str_replace('{username}', $dbuser, $dbconf);
	$dbconf = str_replace('{password}', $dbpass, $dbconf);
	$dbconf = str_replace('{dbname}', $dbname, $dbconf);
	$dbconf = str_replace('{dbhost}', $dbhost, $dbconf);

	file_put_contents(SITE_ROOT_DIR.'/mysite/db.conf.php', $dbconf);

	// RUN THE dev/build to rebuild the DB from scratch, and set an admin user. 
	if ($rewriteBase == "/") {
		$rewriteBase = "";
	}

	$url = "http://localhost$rewriteBase";

	$rebuild = file_get_contents($url.'/dev/build?username=admin@changethis.org&password=admin');

	echo "Installation complete, navigate to $url to login to your system. Default username/pass is admin@changethis.com:admin\n";
}


/**
 * Creates a .tar.gz file that contains a full SilverStripe installation that can be deployed as is
 */
function package($options)
{
	$filename = ifset($options, 'n', 'silverstripe.tar.gz');
	$filename = dirname(SITE_ROOT_DIR).'/'.$filename;

	$buildDir = SITE_ROOT_DIR.'/build';

	// Need PEAR for this :(
	require_once('Archive/Tar.php');

	if (file_exists($filename)) {
		unlink($filename);
	}

	$tar = new Archive_Tar($filename, 'gz');

	$files = listDirectory('.', array('.svn'));


 	$tar->create($files);
}

/** 
 * List contents of a directory, recursively, returning a single array with all filepaths
 */
function listDirectory($dir, $excludes=array())
{
	$files = scandir($dir);
	$keep = array();
	foreach ($files as $fname) {
		if ($fname == '.' || $fname == '..') continue;
		foreach ($excludes as $exclude) {
			if (strpos($fname, $exclude) === 0) {
				// skip this file
				continue 2;
			}
		}

		$fullname = $dir . '/' . $fname;
	
		if (is_dir($fullname)) {
			$keep = array_merge($keep, listDirectory($fullname, $excludes));
		} else {
			$keep[] = $fullname;
		}		
	}

	return $keep;
}

echo "\n";

function ifset($array, $key, $default = null) 
{
	return isset($array[$key]) ? $array[$key] : $default;
}