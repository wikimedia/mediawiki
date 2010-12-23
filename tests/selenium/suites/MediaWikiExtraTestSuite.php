<?php

class MediaWikiExtraTestSuite extends SeleniumTestSuite {
	public function setUp() {
		$this->setLoginBeforeTests( true );
		parent::setUp();
	}
	public function addTests() {
		$testFiles = array(
			'tests/selenium/suites/MyContributionsTestCase.php',
			'tests/selenium/suites/MyWatchListTestCase.php',
			'tests/selenium/suites/UserPreferencesTestCase.php',
			'tests/selenium/suites/MovePageTestCase.php',
			'tests/selenium/suites/PageSearchTestCase.php',
			'tests/selenium/suites/EmailPasswordTestCase.php',
			'tests/selenium/suites/CreateAccountTestCase.php'
		);
		parent::addTestFiles( $testFiles );
	}
}
