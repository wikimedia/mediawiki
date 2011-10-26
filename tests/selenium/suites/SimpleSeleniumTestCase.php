<?php
/*
 * This test case is part of the SimpleSeleniumTestSuite.
 * Configuration for these tests are documented as part of SimpleSeleniumTestSuite.php
 */
class SimpleSeleniumTestCase extends SeleniumTestCase {
	public function testBasic() {
		$this->open( $this->getUrl() .
			'/index.php?title=Selenium&action=edit' );
		$this->type( "wpTextbox1", "This is a basic test" );
		$this->click( "wpPreview" );
		$this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

		// check result
		$source = $this->getText( "//div[@id='wikiPreview']/p" );
		$correct = strstr( $source, "This is a basic test" );
		$this->assertEquals( $correct, true );
	}

	/**
	 * All this test really does is verify that a global var was set.
	 * It depends on $wgDefaultSkin = 'chick'; being set
	 */
	public function testGlobalVariableForDefaultSkin() {
		$this->open( $this->getUrl() . '/index.php' );
		$bodyClass = $this->getAttribute( "//body/@class" );
		$this-> assertContains('skin-chick', $bodyClass, 'Chick skin not set');
	}

	/**
	 * Just verify that the test db was loaded correctly
	 */
	public function testDatabaseResourceLoadedCorrectly() {
		$this->open( $this->getUrl() . '/index.php/TestResources?action=purge' );
		$testString = $this->gettext( "//body//*[@id='firstHeading']" );
		$this-> assertEquals('TestResources', $testString, 'Article that should be present in the test db was not found.');
	}

}
