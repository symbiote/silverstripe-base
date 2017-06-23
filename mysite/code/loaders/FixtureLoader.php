<?php

/**
 * A utility class used to load fixtures into the system
 * 
 * To prepare fixtures for loading, add the following
 * to your _config.php. Note that 'type' and 'filter' are used to
 * search for an existing record so that the fixture isn't bootstrapped multiple
 * times
 * 
 * FixtureLoader::$preload_fixtures[] = array(
 *		'file' => 'path/to/yaml.yml',
 *		'type' => 'ClassType',				// used to look for an existing object 
 *		'filter' => '"Field" = \'Value\'',	// used to find an existing object
 *		'subsite' => 'Subsite Title',		// the title of a subsite this item should be created in
 * );
 * 
 * To make use of it, add something like the following to your
 * Page->requireDefaultRecords method
 * 
 * <code>
 * if (Director::isDev()) {
 *		$loader = new FixtureLoader();
 *		$loader->loadFixtures();
 * }
 * </code>
 *
 * 
 *  
 * @author marcus@symbiote.com.au
 * @license http://silverstripe.org/bsd-license/
 */
class FixtureLoader {
	public static $preload_fixtures = array(
	);

	public function loadFixtures() {
		if (ClassInfo::exists('Subsite')) {
			$currentSubsite = Subsite::currentSubsiteID();
		}

		foreach (self::$preload_fixtures as $desc) {
			$fixtureFile = $desc['file'];
			if (file_exists(Director::baseFolder().'/'.$fixtureFile)) {
				$siteID = null;
				if (isset($desc['subsite'])) {
					$site = DataObject::get_one('Subsite', '"Title" = \''.Convert::raw2sql($desc['subsite']).'\'');
					if ($site && $site->ID) {
						$siteID = $site->ID;
					}

					if (!$siteID) {
						// no site, so just skip this file load
						continue;
					}
				}

				// need to disable the filter when running dev/build so that it actually searches
				// within the relevant subsite, not the 'current' one.
				if (ClassInfo::exists('Subsite')) {
					Subsite::$disable_subsite_filter = true;
				}

				$filter = $desc['filter'] . ($siteID ? ' AND "SubsiteID"='.$siteID : '');
				$existing = DataObject::get_one($desc['type'], $filter);

				if (ClassInfo::exists('Subsite')) {
					Subsite::$disable_subsite_filter = false;
				}

				if (!$existing) {
					if ($siteID) {
						Subsite::changeSubsite($siteID);
					}

					$fixture = new YamlFixture($fixtureFile);
					$fixture->saveIntoDatabase();
					DB::alteration_message('YAML bootstrap loaded from '.$fixtureFile, 'created');
				}
			}
		}

		if (ClassInfo::exists('Subsite')) {
			Subsite::changeSubsite($currentSubsite);
		}
	}
}