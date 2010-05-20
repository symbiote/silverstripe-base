<?php

global $databaseConfig;
$databaseConfig = array(
        "type" => "${db.type}",
        "server" => "${db.host}",
        "username" => "${db.user}",
        "password" => "${db.pass}",
        "database" => "${db.name}",
);