<?php 

if (PHP_SAPI != 'cli') {
	header('Status: 404 not found');
	exit();
}
define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');

if (strpos('/usr/bin/php', '@php_bin') === 0) {
    require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPUnit' . DIRECTORY_SEPARATOR . 'Autoload.php';
} else {
    require '/usr/share/php' . DIRECTORY_SEPARATOR . 'PHPUnit' . DIRECTORY_SEPARATOR . 'Autoload.php';
}

PHPUnit_TextUI_Command::main();

