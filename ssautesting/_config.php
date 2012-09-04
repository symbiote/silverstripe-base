<?php
if (file_exists(dirname(__FILE__).'/testing.conf.php')) {
	include_once dirname(__FILE__).'/testing.conf.php';
	
	Director::addRules(100, array(
		'dev' => 'ParameterisedDevelopmentController',
	));
}
