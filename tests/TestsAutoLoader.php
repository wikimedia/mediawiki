<?php

global $wgAutoloadClasses;
$testFolder = dirname( __FILE__ );

$wgAutoloadClasses += array(

	//PHPUnit
	'MediaWikiTestCase' => "$testFolder/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testFolder/phpunit/MediaWikiPHPUnitCommand.php",
	'MediaWikiLangTestCase' => "$testFolder/phpunit/MediaWikiLangTestCase.php",
	'NewParserTest' => "$testFolder/phpunit/includes/parser/NewParserTest.php",

	//includes
	'BlockTest' => "$testFolder/phpunit/includes/BlockTest.php",
	'MockOutputPage' => "$testFolder/phpunit/includes/MockOutputPage.php",

	//API
	'ApiFormatTestBase' => "$testFolder/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiTestCase' => "$testFolder/phpunit/includes/api/ApiTestCase.php",
	'ApiTestUser' => "$testFolder/phpunit/includes/api/ApiTestUser.php",
	'MockApi' => "$testFolder/phpunit/includes/api/ApiTestCase.php",
	'RandomImageGenerator' => "$testFolder/phpunit/includes/api/RandomImageGenerator.php",
	'UserWrapper' => "$testFolder/phpunit/includes/api/ApiTestCase.php",

	//Selenium
	'SeleniumTestConstants' => "$testFolder/selenium/SeleniumTestConstants.php",

	//maintenance
	'DumpTestCase' => "$testFolder/phpunit/maintenance/DumpTestCase.php",
	'BackupDumper' => "$testFolder/../maintenance/backup.inc",

	//Generic providers
	'MediaWikiProvide' => "$testFolder/phpunit/includes/Providers.php",
);

