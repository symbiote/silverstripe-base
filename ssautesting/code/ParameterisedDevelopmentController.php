<?php

/**
 * Overridden development controller so that we can make use of a 
 * test runner that outputs appropriate XML files that can be used 
 * in a CI tool like hudson or CruiseControl
 * 
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 *
 */
class ParameterisedDevelopmentController extends DevelopmentAdmin
{
	function tests($request) {
		return new ParameterisedTestRunner();
	}
}