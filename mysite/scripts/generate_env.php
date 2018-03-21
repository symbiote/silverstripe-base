<?php

if (!file_exists(mysite_base().'/local.conf.php')) {
    return;
}

$DEFAULT_ADMIN = '';
$DEFAULT_PASS = '';

$SET_AUTH = false;

// dummy files for local.conf to not break
class Email {
    public static function getAdminEmail() {
        return "WAA";
    }
}

class BasicAuth {
    public static function protect_entire_site($group = 'true') {
        global $SET_AUTH;

        $SET_AUTH = $group;
    }
}

class Security {
    public static function setDefaultAdmin($a, $b) {
        global $DEFAULT_ADMIN, $DEFAULT_PASS;

        $DEFAULT_ADMIN = $a;
        $DEFAULT_PASS = $b;
    }
}

class Director {
    public static function set_environment_type($l) {
        
    }
}

include_once mysite_base().'/local.conf.php';

global $databaseConfig;

if (!is_array($databaseConfig)) {
    return;
}

$tmpl = <<<TMPL

## Environment

SS_ENVIRONMENT_TYPE="dev"
SS_DEFAULT_ADMIN_USERNAME="{$DEFAULT_ADMIN}"
SS_DEFAULT_ADMIN_PASSWORD="{$DEFAULT_PASS}"
SS_BASE_URL="//localhost/"

## This is used to determine the cache directory name, where it should match your command line user.

APACHE_RUN_USER="deploy"

## Database

SS_DATABASE_CLASS="{$databaseConfig['type']}"
# SS_DATABASE_CLASS="MySQLPDODatabase"
SS_DATABASE_SERVER="{$databaseConfig['server']}"
SS_DATABASE_USERNAME="{$databaseConfig['username']}"
SS_DATABASE_PASSWORD="{$databaseConfig['password']}"
SS_DATABASE_CHOOSE_NAME="true"
SS_DATABASE_NAME="{$databaseConfig['database']}"

TMPL;

if ($SET_AUTH) {
    $tmpl .= "\nSS_USE_BASIC_AUTH=$SET_AUTH\n";
}

$envfile = site_base().'/.env';

if (!file_exists($envfile)) {
    file_put_contents($envfile, $tmpl);
}
