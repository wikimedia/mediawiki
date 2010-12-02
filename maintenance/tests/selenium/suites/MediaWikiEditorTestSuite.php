<?php
/*
 * Sample test suite. 
 * Two ways to configure MW for these tests
 * 1) If you are running multiple test suites, add the following in LocalSettings.php
 * require_once("maintenance/tests/selenium/SimpleSeleniumConfig.php");
 * $wgSeleniumTestConfigs['SimpleSeleniumTestSuite'] = 'SimpleSeleniumConfig::getSettings';
 * OR
 * 2) Add the following to your Localsettings.php
 * $wgDefaultSkin = 'chick';
 */
class MediaWikiEditorTestSuite extends SeleniumTestSuite
{
	public function setUp() {
		$this->setLoginBeforeTests( True );
		parent::setUp();
	} 
	public function addTests() {
		$testFiles = array(
                    'maintenance/tests/selenium/suites/AddNewPageTestCase.php',
                    'maintenance/tests/selenium/suites/AddContentToNewPageTestCase.php',
                    'maintenance/tests/selenium/suites/DeletePageAdminTestCase.php',
                    'maintenance/tests/selenium/suites/PageSearchTestCase.php'

		);
		parent::addTestFiles( $testFiles );
	}


}
