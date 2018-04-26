<?php

/**
 * Temporary workaround updated backup script for SS4 environments
 */

use SilverStripe\Control\Director;
use SilverStripe\ORM\Connect\MySQLDatabase;
use SilverStripe\SQLite\SQLite3Database;
use SilverStripe\Control\HTTPApplication;
use SilverStripe\Control\HTTPRequestBuilder;
use SilverStripe\Core\CoreKernel;
use SilverStripe\Core\Startup\ErrorControlChainMiddleware;
use SilverStripe\Core\EnvironmentLoader;
use SilverStripe\Core\Environment;

if (PHP_SAPI != 'cli') {
        header("HTTP/1.0 404 Not Found");
        exit;
}

function mysite_base() {
        $MYSITE_BASE = realpath(dirname(dirname(__FILE__)));
        return $MYSITE_BASE;
}

function site_base() {
        return dirname(mysite_base());
}

$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require site_base().'/vendor/silverstripe/framework/src/includes/autoload.php';

// Build request and detect flush
//$request = HTTPRequestBuilder::createFromEnvironment();
$kernel = new CoreKernel(BASE_PATH);
$kernel->boot(true);

$outfile = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : $outfile = Director::baseFolder().'/mysite/scripts/backup-'.date('Y-m-d-H-i-s').'.sql.gz';

EnvironmentLoader::loadFile(site_base().'/.env');

$vars = Environment::getVariables();
$envVars = $vars['env'];

switch ($envVars['SS_DATABASE_CLASS']) {
        case 'MySQLDatabase':
        case 'MySQLPDODatabase':
                $u = $envVars['SS_DATABASE_USERNAME'];
                $p = $envVars['SS_DATABASE_PASSWORD'];
                $h = $envVars['SS_DATABASE_SERVER'];
                $d = $envVars['SS_DATABASE_NAME'];

                $cmd = "mysqldump --user=".escapeshellarg($u)." --password=".escapeshellarg($p)." --ignore-table=$d.details --host=".escapeshellarg($h)." ".escapeshellarg($d)." | gzip > ".escapeshellarg($outfile);
                exec($cmd);
                break;
        case 'SQLiteDatabase':
        case 'SQList3Database':
                $d = $envVars['SS_DATABASE_NAME'];
                $path = realpath(dirname(__FILE__).'/../../assets/.sqlitedb/'.$d);
                $cmd = "sqlite3 ".escapeshellarg($path)." .dump | gzip > ".escapeshellarg($outfile);
                exec($cmd);
                break;
        default: break;
}
