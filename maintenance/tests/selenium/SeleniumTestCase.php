<?php
class SeleniumTestCase extends PHPUnit_Framework_TestCase { // PHPUnit_Extensions_SeleniumTestCase
	protected $selenium;

	public function setUp() {
		set_time_limit( 60 );
		$this->selenium = Selenium::getInstance();
	}

	public function tearDown() {

	}

	public function __call( $method, $args ) {
		return call_user_func_array( array( $this->selenium, $method ), $args );
	}

	public function assertSeleniumAttributeEquals( $attribute, $value ) {
		$attr = $this->getAttribute( $attribute );
		$this->assertEquals( $attr, $value );
	}

	public function assertSeleniumHTMLContains( $element, $text ) {
		$innerHTML = $this->getText( $element );
		// or assertContains
		$this->assertRegExp( "/$text/", $innerHTML );
	}

//Common Funtions Added for Selenium Tests

        public function getExistingPage(){
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->type("searchInput", "new" );
                $this->click("searchGoButton");
                $this->waitForPageToLoad("30000");
        }

        public function getNewPage($pageName){

                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->type("searchInput", $pageName );
                $this->click("searchGoButton");
                $this->waitForPageToLoad("30000");
                $this->click("link=".$pageName);
                $this->waitForPageToLoad("600000");


        }
       // Loading the mediawiki editor
        public function loadWikiEditor(){
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
        }

        // Clear the content of the mediawiki editor
        public function clearWikiEditor(){
                $this->type("wpTextbox1", "");
        }

        // Click on the 'Show preview' button of the mediawiki editor
        public function clickShowPreviewBtn(){
                $this->click("wpPreview");
        }

        // Click on the 'Save Page' button of the mediawiki editor
        public function clickSavePageBtn(){
                $this->click("wpSave");
        }

        // Click on the 'Edit' link
        public function clickEditLink(){
                $this->click("link=Edit");
                $this->waitForPageToLoad("30000");
        }

        public function deletePage($pageName){
                $isLinkPresent  = False;
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->click("link=Log out");
                $this->waitForPageToLoad( "30000" );
                $this->click( "link=Log in / create account" );
                $this->waitForPageToLoad( "30000" );
                $this->type( "wpName1", "nadeesha" );
                $this->type( "wpPassword1", "12345" );
                $this->click( "wpLoginAttempt" );
                $this->waitForPageToLoad( "30000" );
                $this->type( "searchInput", $pageName );
                $this->click( "searchGoButton");
                $this->waitForPageToLoad( "30000" );

                $this->click( "link=Delete" );
                $this->waitForPageToLoad( "30000" );
                $this->click( "wpConfirmB" );
                $this->waitForPageToLoad( "30000" );

        }



}
