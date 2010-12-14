<?php

class MediaWikExtraTestSuite extends SeleniumTestSuite {
    public function setUp() {
        $this->setLoginBeforeTests( true );
        parent::setUp();
    }
    public function addTests() {
        $testFiles = array(
                'maintenance/tests/selenium/suites/MyContributionsTestCase.php',
                'maintenance/tests/selenium/suites/MyWatchListTestCase.php',
                'maintenance/tests/selenium/suites/UserPreferencesTestCase.php',
                'maintenance/tests/selenium/suites/MovePageTestCase.php',
                'maintenance/tests/selenium/suites/PageSearchTestCase.php',
                'maintenance/tests/selenium/suites/EmailPasswordTestCase.php',
                'maintenance/tests/selenium/suites/CreateAccountTestCase.php'
        );
        parent::addTestFiles( $testFiles );
    }
}
