<?php

/*
 * Include any local instance specific configuration here - typically
 * this includes any database settings, email settings, etc that change
 * between installations. 
 */

global $databaseConfig;
$databaseConfig = array(
        "type" => "${db.type}",
        "server" => "${db.host}",
        "username" => "${db.user}",
        "password" => "${db.pass}",
        "database" => "${db.name}",
);


Security::setDefaultAdmin('${admin.username}', '${admin.password}');
// Email::setAdminEmail('admin@example.org');
define('SS_LOG_FILE', dirname(__FILE__).'/'.basename(dirname(dirname(__FILE__))).'.log');
