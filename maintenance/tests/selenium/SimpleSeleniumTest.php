<?php

class SimpleSeleniumTest extends SeleniumTestCase
{
	public $name = "Basic selenium test";

	public function runTest()
	{
		$this->open( Selenium::getBaseUrl() . '/index.php?title=Selenium&action=edit' );
		$this->type( "wpTextbox1", "This is a basic test" );
		$this->click( "wpPreview" );
		$this->waitForPageToLoad( 10000 );

		// check result
		$source = $this->getText( "//div[@id='wikiPreview']/p" );
		$correct = strstr( $source, "This is a basic test" );
		$this->assertEquals( $correct, true );

	}

}
