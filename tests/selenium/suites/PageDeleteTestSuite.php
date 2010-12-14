<?php

class PageDeleteTestSuite extends SeleniumTestSuite {
    public function setUp() {
        $this->setLoginBeforeTests( true );
        parent::setUp();
    }
    public function addTests() {
        $testFiles = array(
                'tests/selenium/suites/DeletePageAdminTestCase.php'
        );
        parent::addTestFiles( $testFiles );
    }


}
