<?php

include_once dirname(__FILE__).'/SilverStripeBuildTask.php';

/**
 * Execute the SilverStripe test suite ala the phpunit task
 *
 * @author marcus
 *
 */
class SilverStripeTestTask extends SilverStripeBuildTask
{
	private $module = '';
	private $testcase = '';
	
	public function setModule($v) {
		if (strpos($v, '$') === false) {
			$this->module = $v;
		}
	}
	
	public function setTestCase($v) {
		if (!strpos($v, '$') === 0) {
			$this->testcase = $v;
		}
	}

	/**
	 * The main entry point
	 *
	 * @throws BuildException
	 */
	function main()
	{
		$this->configureEnvFile();
		
		$testCmd = 'dev/tests/all';
		
		if ($this->module != '') {
			$testCmd = 'dev/tests/module/'.$this->module;
		}
		
		if ($this->testcase != '') {
			$testCmd = 'dev/tests/'.$this->testcase;
		} 

		$testCmd .= ' disable_perms=1 flush=1';

		echo "Exec $testCmd\n";
		
		// simply call the php ss-cli-script.php dev/tests/all. We ignore the errors because
		// the test report script picks them up later on. 
		$this->exec('php framework/cli-script.php '.$testCmd, false, true);

		$this->cleanEnv();
		
		/*if (preg_match("/(\d+) tests run: (\d+) passes, (\d+) fails, and (\d+) exceptions/i", $output, $matches)) {
			print_r($matches);
		}*/
	}
}
