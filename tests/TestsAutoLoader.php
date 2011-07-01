<?php

global $wgAutoloadClasses;
$testFolder = dirname( __FILE__ );

$wgAutoloadClasses += array(

	//PHPUnit
	'MediaWikiTestCase' => "$testFolder/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testFolder/phpunit/MediaWikiPHPUnitCommand.php",
	'MediaWikiLangTestCase' => "$testFolder/phpunit/MediaWikiLangTestCase.php",

	//includes
	'BlockTest' => "$testFolder/phpunit/includes/BlockTest.php",

	//API
	'ApiFormatTestBase' => "$testFolder/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiTestCase' => "$testFolder/phpunit/includes/api/ApiTestCase.php",
	'ApiTestUser' => "$testFolder/phpunit/includes/api/ApiTestUser.php",
	'MockApi' => "$testFolder/phpunit/includes/api/ApiTestCase.php",
	'RandomImageGenerator' => "$testFolder/phpunit/includes/api/RandomImageGenerator.php",
	'UserWrapper' => "$testFolder/phpunit/includes/api/ApiTestCase.php",

	//Parser
	'ParserTestFileIterator' => "$testFolder/phpunit/includes/parser/NewParserHelpers.php",

	//Selenium
	'SeleniumTestConstants' => "$testFolder/selenium/SeleniumTestConstants.php",

	//Generic providers
	'MediaWikiProvide' => "$testFolder/phpunit/includes/Providers.php",
);

