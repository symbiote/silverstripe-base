<?php

/**
 * A test runnner that accepts several parameters for setting things
 * like the test reporter to use, the 
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 *
 */
class ParameterisedTestRunner extends TestRunner
{
	// overridden on the base due to private declaration in TestRunner
	private static $default_reporter;
	
	/**
	 * Override the default reporter with a custom configured subclass.
	 *
	 * @param string $reporter
	 */
	static function set_reporter($reporter) {
		if (is_string($reporter)) $reporter = new $reporter;
		self::$default_reporter = $reporter;
	}

	function init() {
		parent::init();
		if (!self::$default_reporter) self::set_reporter(Director::is_cli() ? 'CliDebugView' : 'DebugView');
	}
	
	function runTests($classList, $coverage = false) {
		global $TESTING_CONFIG;
		
		$startTime = microtime(true);

		if (isset($TESTING_CONFIG['database']) && $TESTING_CONFIG['database'] != 'silverstripe_testing') {
			global $databaseConfig;
			$newConfig = $databaseConfig;
			$newConfig = array_merge($databaseConfig, $TESTING_CONFIG);
			$newConfig['memory'] = isset($TESTING_CONFIG['memory']) ? $TESTING_CONFIG['memory'] : true;
			$type = isset($newConfig['type']) ? $newConfig['type'] : 'MySQL';
			Debug::message("Connecting to new database $type as defined by testing config");
			DB::connect($newConfig);
			DB::getConn()->selectDatabase($TESTING_CONFIG['database']);
			$dbadmin = new DatabaseAdmin();
			$dbadmin->clearAllData();
			$dbadmin->doBuild(true);
		}

		// XDEBUG seem to cause problems with test execution :-(
		if(function_exists('xdebug_disable')) xdebug_disable();
		
		ini_set('max_execution_time', 0);		
		
		$this->setUp();
		
		// Optionally skip certain tests
		$skipTests = array();
		if($this->request->getVar('SkipTests')) {
			$skipTests = explode(',', $this->request->getVar('SkipTests'));
		}
		$classList = array_diff($classList, $skipTests);
		
		// run tests before outputting anything to the client
		$suite = new PHPUnit_Framework_TestSuite();
		natcasesort($classList);
		foreach($classList as $className) {
			// Ensure that the autoloader pulls in the test class, as PHPUnit won't know how to do this.
			class_exists($className);
			$suite->addTest(new SapphireTestSuite($className));
		}

		// Remove the error handler so that PHPUnit can add its own
		restore_error_handler();

		// CUSTOMISATION
		if (Director::is_cli()) {
			if (isset($TESTING_CONFIG['reporter'])) {
				$clazz = $TESTING_CONFIG['reporter'];
			} else { 
				$clazz = "CliTestReporter";
			}
		} else {
			$clazz = "SapphireTestReporter";
		}
		// END CUSTOMISATION
		
		$reporter = new $clazz;
		$default = self::$default_reporter;

		self::$default_reporter->writeHeader("Sapphire Test Runner");
		if (count($classList) > 1) { 
			self::$default_reporter->writeInfo("All Tests", "Running test cases: " . implode(",", $classList));
		} else {
			self::$default_reporter->writeInfo($classList[0], "");
		}
		
		$results = new PHPUnit_Framework_TestResult();		
		$results->addListener($reporter);

		if($coverage === true) {
			$results->collectCodeCoverageInformation(true);
			$suite->run($results);

			if(!file_exists(ASSETS_PATH . '/coverage-report')) mkdir(ASSETS_PATH . '/coverage-report');
			PHPUnit_Util_Report::render($results, ASSETS_PATH . '/coverage-report/');
			$coverageApp = ASSETS_PATH . '/coverage-report/' . preg_replace('/[^A-Za-z0-9]/','_',preg_replace('/(\/$)|(^\/)/','',Director::baseFolder())) . '.html';
			$coverageTemplates = ASSETS_PATH . '/coverage-report/' . preg_replace('/[^A-Za-z0-9]/','_',preg_replace('/(\/$)|(^\/)/','',realpath(TEMP_FOLDER))) . '.html';
			echo "<p>Coverage reports available here:<ul>
				<li><a href=\"$coverageApp\">Coverage report of the application</a></li>
				<li><a href=\"$coverageTemplates\">Coverage report of the templates</a></li>
			</ul>";
		} else {
			$suite->run($results);
		}
		
		if(!Director::is_cli()) echo '<div class="trace">';
		
		// CUSTOMISATION
		$outputFile = null;
		if ($TESTING_CONFIG['logfile']) {
			$outputFile = BASE_PATH . '/'. $TESTING_CONFIG['logfile'];
		}
		
		$reporter->writeResults($outputFile);
		// END CUSTOMISATION

		$endTime = microtime(true);
		if(Director::is_cli()) echo "\n\nTotal time: " . round($endTime-$startTime,3) . " seconds\n";
		else echo "<p>Total time: " . round($endTime-$startTime,3) . " seconds</p>\n";
		
		if(!Director::is_cli()) echo '</div>';
		
		// Put the error handlers back
		Debug::loadErrorHandlers();
		
		if(!Director::is_cli()) self::$default_reporter->writeFooter();
		
		$this->tearDown();
		
		// Todo: we should figure out how to pass this data back through Director more cleanly
		if(Director::is_cli() && ($results->failureCount() + $results->errorCount()) > 0) exit(2);
	}

	function tearDown() {
		global $TESTING_CONFIG;
		if (!isset($TESTING_CONFIG['database'])) {
			parent::tearDown();
		}
		DB::set_alternative_database_name(null);
	}
}