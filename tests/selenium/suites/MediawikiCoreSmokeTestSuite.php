<?php
/**
 * Stubs for now. We're going to start populating this test.
 */
class MediawikiCoreSmokeTestSuite extends SeleniumTestSuite
{
	public function setUp() {
		$this->setLoginBeforeTests( false );
		parent::setUp();
	} 
	public function addTests() {
		$testFiles = array(
			'tests/selenium/suites/MediawikiCoreSmokeTestCase.php'
		);
		parent::addTestFiles( $testFiles );
	}


}
