<?php
include("SeleniumTestConstants.php");

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


	/**
	 * Create a test fixture page if one does not exist
	 * @param $pageName The fixture page name. If none is supplied, it uses SeleniumTestConstants::WIKI_INTERNAL_LINK
	 */
	function createTestPageIfMissing( $pageName = null ) {
		if ( $pageName == null ) {
			$pageName = SeleniumTestConstants::WIKI_INTERNAL_LINK;		
		}
		$this->type( SeleniumTestConstants::INPUT_SEARCH_BOX, $pageName  );
		$this->click( SeleniumTestConstants::BUTTON_SEARCH );
		$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
		$this->click( SeleniumTestConstants::LINK_START . $pageName );
		$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
		$location =  $this->getLocation() . "\n";
		if ( strpos( $location, '&redlink=1') !== false  ) {
			$this->type( SeleniumTestConstants::TEXT_EDITOR,  "Test fixture page. No real content here" );
			$this->click( SeleniumTestConstants::BUTTON_SAVE );
			$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
			$this->assertTrue( $this->isTextPresent( $pageName ),
			$this->getText( SeleniumTestConstants::TEXT_PAGE_HEADING ) );
		}
	}
	
	/**
	 * Create a test page using date as part of the name so that it is unique
	 * @param $pagePrefix The prefix to use for the page name. The current date will be appended to this to make it unique
	 * @param $watchThis Whether to add the page to my watchlist. Defaults to false.
	 */
	function createNewTestPage( $pagePrefix, $watchThis = false ) {
		$pageName = $pagePrefix . date("Ymd-His");
		$this->type( SeleniumTestConstants::INPUT_SEARCH_BOX, $pageName  );
		$this->click( SeleniumTestConstants::BUTTON_SEARCH );
		$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
		$this->click( SeleniumTestConstants::LINK_START . $pageName );
		$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
		$location =  $this->getLocation() . "\n";
		$this->assertContains( '&redlink=1', $location ).
		$this->type( SeleniumTestConstants::TEXT_EDITOR,  "Test fixture page. No real content here" );
		if ( $watchThis ) {
			$this->click( "wpWatchthis" );
		}
		$this->click( SeleniumTestConstants::BUTTON_SAVE );
		$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
		$this->assertTrue( $this->isTextPresent( $pageName ),
		$this->getText( SeleniumTestConstants::TEXT_PAGE_HEADING ) );
		return $pageName;
	}

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

}
