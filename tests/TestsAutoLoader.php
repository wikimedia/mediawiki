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
$testDir = __DIR__;

$wgAutoloadClasses += array(

	# tests
	'DbTestPreviewer' => "$testDir/testHelpers.inc",
	'DbTestRecorder' => "$testDir/testHelpers.inc",
	'DelayedParserTest' => "$testDir/testHelpers.inc",
	'TestFileIterator' => "$testDir/testHelpers.inc",
	'TestRecorder' => "$testDir/testHelpers.inc",

	# tests/phpunit
	'MediaWikiTestCase' => "$testDir/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testDir/phpunit/MediaWikiPHPUnitCommand.php",
	'MediaWikiLangTestCase' => "$testDir/phpunit/MediaWikiLangTestCase.php",
	'MediaWikiProvide' => "$testDir/phpunit/includes/Providers.php",
	'TestUser' => "$testDir/phpunit/includes/TestUser.php",

	# tests/phpunit/includes
	'BlockTest' => "$testDir/phpunit/includes/BlockTest.php",
	'RevisionStorageTest' => "$testDir/phpunit/includes/RevisionStorageTest.php",
	'WikiPageTest' => "$testDir/phpunit/includes/WikiPageTest.php",

	//db
	'ORMTableTest' => "$testDir/phpunit/includes/db/ORMTableTest.php",
	'PageORMTableForTesting' => "$testDir/phpunit/includes/db/ORMTableTest.php",

	//Selenium
	'SeleniumTestConstants' => "$testDir/selenium/SeleniumTestConstants.php",

	# tests/phpunit/includes/api
	'ApiFormatTestBase' => "$testDir/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'MockApi' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",
	'UserWrapper' => "$testDir/phpunit/includes/api/ApiTestCase.php",

	# tests/phpunit/includes/content
	'DummyContentHandlerForTesting' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'DummyContentForTesting' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'ContentHandlerTest' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'JavaScriptContentTest' => "$testDir/phpunit/includes/content/JavaScriptContentTest.php",
	'TextContentTest' => "$testDir/phpunit/includes/content/TextContentTest.php",
	'WikitextContentTest' => "$testDir/phpunit/includes/content/WikitextContentTest.php",

	# tests/phpunit/includes/db
	'ORMRowTest' => "$testDir/phpunit/includes/db/ORMRowTest.php",

	# tests/phpunit/includes/parser
	'NewParserTest' => "$testDir/phpunit/includes/parser/NewParserTest.php",

	# tests/phpunit/includes/libs
	'GenericArrayObjectTest' => "$testDir/phpunit/includes/libs/GenericArrayObjectTest.php",

	# tests/phpunit/includes/site
	'SiteTest' => "$testDir/phpunit/includes/site/SiteTest.php",
	'TestSites' => "$testDir/phpunit/includes/site/TestSites.php",

	# tests/phpunit/languages
	'LanguageClassesTestCase' => "$testDir/phpunit/languages/LanguageClassesTestCase.php",

	# tests/phpunit/maintenance
	'DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",

	# tests/parser
	'ParserTest' => "$testDir/parser/parserTest.inc",
	'ParserTestParserHook' => "$testDir/parser/parserTestsParserHook.php",

	# tests/selenium
	'Selenium' => "$testDir/selenium/Selenium.php",
	'SeleniumLoader' => "$testDir/selenium/SeleniumLoader.php",
	'SeleniumTestCase' => "$testDir/selenium/SeleniumTestCase.php",
	'SeleniumTestConsoleLogger' => "$testDir/selenium/SeleniumTestConsoleLogger.php",
	'SeleniumTestConstants' => "$testDir/selenium/SeleniumTestConstants.php",
	'SeleniumTestHTMLLogger' => "$testDir/selenium/SeleniumTestHTMLLogger.php",
	'SeleniumTestListener' => "$testDir/selenium/SeleniumTestListener.php",
	'SeleniumTestSuite' => "$testDir/selenium/SeleniumTestSuite.php",
	'SeleniumConfig' => "$testDir/selenium/SeleniumConfig.php",
);
