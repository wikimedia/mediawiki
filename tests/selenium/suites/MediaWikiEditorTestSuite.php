<?php

class MediaWikiEditorTestSuite extends SeleniumTestSuite {
    public function setUp() {
        $this->setLoginBeforeTests( true );
        parent::setUp();
    }
    public function addTests() {
        $testFiles = array(
                'tests/selenium/suites/AddNewPageTestCase.php',
                'tests/selenium/suites/AddContentToNewPageTestCase.php',
                'tests/selenium/suites/PreviewPageTestCase.php',
                'tests/selenium/suites/SavePageTestCase.php',
        );
        parent::addTestFiles( $testFiles );
    }
}

