<?php

class SimpleSeleniumTestCase extends SeleniumTestCase
{
	public function testBasic()
	{
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
	
	/*
	 * Needs the following in your LocalConfig or an alternative method of configuration (coming soon)
	 * require_once( "$IP/extensions/UsabilityInitiative/Vector/Vector.php" );
	 * $wgDefaultSkin='vector';
	 */
	public function testGlobalVariable1()
	{
		$this->open( $this->getUrl() . '/index.php?&action=purge' );
		$bodyClass = $this->getAttribute( "//body/@class" );
		$this-> assertContains('skin-vector', $bodyClass, 'Vector skin not set');
	}

}
