<?php

use SilverStripe\Control\Director;
use SilverStripe\CMS\Model\SiteTree;

class Page extends SiteTree {
	
	private static $db = array(
	);
	
	private static $has_one = array(
	);
	
	public function requireDefaultRecords() {
		if (Director::isDev()) {
			$loader = new FixtureLoader();
			$loader->loadFixtures();
		}
	}
}
