<?php
/* 
 * Stub of tests be need as part of the hack-a-ton
 */
class MediawikiCoreSmokeTestCase extends SeleniumTestCase {
	public function testUserLogin() {
		
	}
	
	public function testChangeUserPreference() {
		
	}
	
	/*
	 * TODO: generalize this test to be reusable for different skins
	 */
	public function testCreateNewPageVector() {
		
	}
	
	/*
	 * TODO: generalize this test to be reusable for different skins
	 */
	public function testEditExistingPageVector() {
		
	}
	
	/*
	 * TODO: generalize this test to be reusable for different skins
	 */
	public function testCreateNewPageMonobook() {
		
	}
	
	/*
	 * TODO: generalize this test to be reusable for different skins
	 */
	public function testEditExistingPageMonobook() {
		
	}
	
	public function testImageUpload() {
		$this->login();
		$this->open( $this->getUrl() .
			'/index.php?title=Special:Upload' );
		$this->type( 'wpUploadFile', dirname( __FILE__ ) .
			"\\..\\data\\Wikipedia-logo-v2-de.png" );
		$this->check( 'wpIgnoreWarning' );
		$this->click( 'wpUpload' );
		$this->waitForPageToLoad( 30000 );
		
		$this->assertSeleniumHTMLContains(
				'//h1[@class="firstHeading"]', "Wikipedia-logo-v2-de.png" );
		
		/*
		$this->open( $this->getUrl() . '/index.php?title=Image:'
			. ucfirst( $this->filename ) . '&action=delete' );
		$this->type( 'wpReason', 'Remove test file' );
		$this->click( 'mw-filedelete-submit' );
		$this->waitForPageToLoad( 10000 );

		// Todo: This message is localized
		$this->assertSeleniumHTMLContains( '//div[@id="bodyContent"]/p',
			ucfirst( $this->filename ) . '.*has been deleted.' );
		 */
	}
	

}
