<?php

class SimpleSeleniumTestSuite extends SeleniumTestSuite
{
	public function addTests() {
		$testFiles = array(
			'maintenance/tests/selenium/SimpleSeleniumTestCase.php'
		);
		parent::addTestFiles( $testFiles );
	}


}
