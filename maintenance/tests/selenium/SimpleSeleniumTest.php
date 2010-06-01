<?php

if (!defined('MEDIAWIKI') || !defined('SELENIUMTEST')) {
	echo "This script cannot be run standalone";
	exit(1);
}

// create test suite
$wgSeleniumTestSuites['SimpleSeleniumTest'] = new SeleniumTestSuite('Simple Selenium Test');
$wgSeleniumTestSuites['SimpleSeleniumTest']->addTest(new SimpleSeleniumTest());

class SimpleSeleniumTest extends SeleniumTestCase
{
	public $name = "Basic selenium test";

	public function runTest()
	{
		global $wgSeleniumTestsWikiUrl;
		$this->open($wgSeleniumTestsWikiUrl.'/index.php?title=Selenium&action=edit');
		$this->type("wpTextbox1", "This is a basic test");
		$this->click("wpPreview");
		$this->waitForPageToLoad(10000);

		// check result
		$source = $this->getText("//div[@id='wikiPreview']/p");
		$correct = strstr($source, "This is a basic test");
		$this->assertEquals($correct, true);

	}

}