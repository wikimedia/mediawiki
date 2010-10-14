<?php
/* 
 * This test case is part of the SimpleSeleniumTestSuite.
 * Configuration for these tests are dosumented as part of SimpleSeleniumTestSuite.php
 */
class SimpleSeleniumTestCase extends SeleniumTestCase {
	public function testBasic() {
		$this->open( $this->getUrl() . 
			'/index.php?title=Selenium&action=edit' );
		$this->type( "wpTextbox1", "This is a basic test" );
		$this->click( "wpPreview" );
		$this->waitForPageToLoad( 10000 );

		// check result
		$source = $this->getText( "//div[@id='wikiPreview']/p" );
		$correct = strstr( $source, "This is a basic test" );
		$this->assertEquals( $correct, true );
	}
	
	public function testGlobalVariableForDefaultSkin() {
		$this->open( $this->getUrl() . '/index.php?&action=purge' );
		$bodyClass = $this->getAttribute( "//body/@class" );
		$this-> assertContains('skin-chick', $bodyClass, 'Chick skin not set');
	}

}
