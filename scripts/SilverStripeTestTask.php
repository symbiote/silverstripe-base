<?php

/**
 * Execute the SilverStripe test suite ala the phpunit task
 *
 * @author marcus
 *
 */
class SilverStripeTestTask extends Task
{
	private $module = '';
	private $testcase = '';
	
	public function setModule($v)
	{
		$this->module = $v;
	}
	
	public function setTestCase($v)
	{
		$this->testcase = $v;
	}

	/**
	 * The main entry point
	 *
	 * @throws BuildException
	 */
	function main()
	{
		$envDir = dirname(dirname(dirname(__FILE__)));
		// fake the _ss_environment.php file for the moment
		$ssEnv = <<<TEXT
<?php
// Set the \$_FILE_MAPPING for running the test cases, it's basically a fake but useful
define('SS_ENVIRONMENT_TYPE', 'dev');
global \$_FILE_TO_URL_MAPPING;
\$_FILE_TO_URL_MAPPING['$envDir'] = 'http://localhost';
?>
TEXT;

		$envFile = dirname(dirname(__FILE__)).'/_ss_environment.php';
		$cleanup = false;
		if (!file_exists($envFile)) {
			file_put_contents($envFile, $ssEnv);
			$cleanup = true;
		}
		
		$testCmd = '/dev/tests/all';
		
		if ($this->module != '') {
			$testCmd = '/dev/tests/module/'.$this->module;
		}
		
		if ($this->testcase != '') {
			$testCmd = '/dev/tests/'.$this->testcase;
		} 

		echo "Exec $testCmd\n";
		
		// simply call the php ss-cli-script.php dev/tests/all and parse the output
		exec('php sapphire/cli-script.php '.$testCmd, $output, $result);

		$output = implode("\n", $output);

		echo "============\n$output\n============";
		
		if ($cleanup) {
			unlink($envFile);
		}
		/*if (preg_match("/(\d+) tests run: (\d+) passes, (\d+) fails, and (\d+) exceptions/i", $output, $matches)) {
			print_r($matches);
		}*/
	}
}

?>