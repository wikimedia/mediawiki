<?php
/**
 * AutoLoader for the testing suite.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Testing
 */

global $wgAutoloadClasses;
$testFolder = __DIR__;

$wgAutoloadClasses += array(

	//PHPUnit
	'MediaWikiTestCase' => "$testFolder/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testFolder/phpunit/MediaWikiPHPUnitCommand.php",
	'MediaWikiLangTestCase' => "$testFolder/phpunit/MediaWikiLangTestCase.php",
	'NewParserTest' => "$testFolder/phpunit/includes/parser/NewParserTest.php",

	//includes
	'BlockTest' => "$testFolder/phpunit/includes/BlockTest.php",

	//API
	'ApiFormatTestBase' => "$testFolder/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiTestCase' => "$testFolder/phpunit/includes/api/ApiTestCase.php",
	'TestUser' => "$testFolder/phpunit/includes/TestUser.php",
	'MockApi' => "$testFolder/phpunit/includes/api/ApiTestCase.php",
	'RandomImageGenerator' => "$testFolder/phpunit/includes/api/RandomImageGenerator.php",
	'UserWrapper' => "$testFolder/phpunit/includes/api/ApiTestCase.php",

	//db
	'ORMTableTest' => "$testFolder/phpunit/includes/db/ORMTableTest.php";

	//Selenium
	'SeleniumTestConstants' => "$testFolder/selenium/SeleniumTestConstants.php",

	//maintenance
	'DumpTestCase' => "$testFolder/phpunit/maintenance/DumpTestCase.php",
	'BackupDumper' => "$testFolder/../maintenance/backup.inc",

	//language
	'LanguageClassesTestCase' => "$testFolder/phpunit/languages/LanguageClassesTestCase.php",

	//Generic providers
	'MediaWikiProvide' => "$testFolder/phpunit/includes/Providers.php",
);

