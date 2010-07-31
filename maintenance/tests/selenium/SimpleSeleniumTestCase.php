<?php

class SimpleSeleniumTestCase extends SeleniumTestCase
{
	public function __construct( $name = 'Basic selenium test') {
		parent::__construct( $name );
	}

	public function runTest()
	{
		$this->open( Selenium::getBaseUrl() . 
			'/index.php?title=Selenium&action=edit' );
		$this->type( "wpTextbox1", "This is a basic test" );
		$this->click( "wpPreview" );
		$this->waitForPageToLoad( 10000 );

		// check result
		$source = $this->getText( "//div[@id='wikiPreview']/p" );
		$correct = strstr( $source, "This is a basic test" );
		$this->assertEquals( $correct, true );

	}

}
