<?php

global $databaseConfig;
$databaseConfig = array(
        "type" => "SQLiteDatabase",
        "server" => "localhost",
        "username" => "dbuser",
        "password" => "dbpass",
        "database" => "ssautesting",
);

Security::setDefaultAdmin('admin', 'admin');
?>
