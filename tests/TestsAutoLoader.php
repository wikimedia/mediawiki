<?php

global $wgAutoloadClasses;
$testFolder = dirname( __FILE__ );

$wgAutoloadClasses += array(
	'MediaWikiTestCase' => "$testFolder/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testFolder/phpunit/MediaWikiPHPUnitCommand.php",
	'ApiTestSetup' => "$testFolder/phpunit/includes/api/ApiSetup.php",
	'RandomImageGenerator' => "$testFolder/phpunit/includes/api/RandomImageGenerator.php",
	'SeleniumTestConstants' => "$testFolder/selenium/SeleniumTestConstants.php",
);

