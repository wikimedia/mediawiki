<?php

class MediaWikiEditorTestSuite extends SeleniumTestSuite {
    public function setUp() {
        $this->setLoginBeforeTests( true );
        parent::setUp();
    }
    public function addTests() {
        $testFiles = array(
                'maintenance/tests/selenium/suites/AddNewPageTestCase.php',
                'maintenance/tests/selenium/suites/AddContentToNewPageTestCase.php',
                'maintenance/tests/selenium/suites/PreviewPageTestCase.php',
                'maintenance/tests/selenium/suites/SavePageTestCase.php',
        );
        parent::addTestFiles( $testFiles );
    }
}

