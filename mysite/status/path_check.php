<?php 

$root = dirname(dirname(__DIR__));
include_once $root . '/_ss_environment.php';

global $_FILE_TO_URL_MAPPING;

if (!isset($_FILE_TO_URL_MAPPING[$root])) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	throw new Exception("Root value not set");
}

if ($_FILE_TO_URL_MAPPING[$root] == 'http://localhost') {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	throw new Exception("Root mapping is incorrect");
}

echo "OK";


