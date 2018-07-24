<?php

namespace Symbiote\Base;


use SilverStripe\Core\ClassInfo;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;
use SilverStripe\Dev\YamlFixture;
use SilverStripe\ORM\DB;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Dev\FixtureFactory;
use SilverStripe\Core\Injector\Injector;



/**
 * A utility class used to load fixtures into the system
 * 
 * To prepare fixtures for loading, add the following
 * to your _config.php. Note that 'type' and 'filter' are used to
 * search for an existing record so that the fixture isn't bootstrapped multiple
 * times
 * 
 * Symbiote\Base\FixtureLoader:
 *   preload_fixtures:
 *     key:
 *		 file: path/to/yaml.yml
 *       type: ClassType                // data object type to load for filtering
 *		 filter:                        // used to find an existing object
 *		   Field: Value	
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

    use Configurable;

    private static $preload_fixtures = [];
    
	public function loadFixtures() {
        
		foreach (self::config()->preload_fixtures as $desc) {
			$fixtureFile = $desc['file'];
			if (file_exists(Director::baseFolder().'/'.$fixtureFile)) {
                $fixtureFactory = Injector::inst()->create(FixtureFactory::class);
                $type = isset($desc['type']) ? $desc['type'] : null;
				$filter = isset($desc['filter']) ? $desc['filter'] : null;
                
                $doFixture = true;
                
                if ($type) {
                    $existing = $type::get();
                    $existing = $filter ? $existing->filter($filter) : $existing;
                    $exists = $existing->first();
                    if ($exists) {
                        $doFixture = false;
                    }
                }

				if ($doFixture) {
                    $fixture = YamlFixture::create($fixtureFile);
					$fixture->writeInto($fixtureFactory);
					DB::alteration_message('YAML bootstrap loaded from '.$fixtureFile, 'created');
				}
			}
		}
	}
}