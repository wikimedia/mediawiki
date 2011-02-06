<?php

global $wgAutoloadClasses;
$testFolder = dirname( __FILE__ );

$wgAutoloadClasses += array(

	//PHPUnit
	'MediaWikiTestCase' => "$testFolder/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testFolder/phpunit/MediaWikiPHPUnitCommand.php",
		
		//API
		'ApiTestSetup' => "$testFolder/phpunit/includes/api/ApiSetup.php",
		'RandomImageGenerator' => "$testFolder/phpunit/includes/api/RandomImageGenerator.php",
	
		//Parser
		'ParserTestFileIterator' => "$testFolder/phpunit/includes/parser/NewParserHelpers.php",
		
		
	//Selenium
	'SeleniumTestConstants' => "$testFolder/selenium/SeleniumTestConstants.php",

	//Generic providers
	'MediaWikiProvide' => "$testFolder/phpunit/includes/Providers.php",
);

