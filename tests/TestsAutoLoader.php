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
	'ParserTestResult' => "$testDir/parser/ParserTestResult.php",
	'TestFileIterator' => "$testDir/testHelpers.inc",
	'TestRecorder' => "$testDir/testHelpers.inc",
	'ITestRecorder' => "$testDir/testHelpers.inc",

	# tests/phpunit
	'MediaWikiTestCase' => "$testDir/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitCommand' => "$testDir/phpunit/MediaWikiPHPUnitCommand.php",
	'MediaWikiPHPUnitTestListener' => "$testDir/phpunit/MediaWikiPHPUnitTestListener.php",
	'MediaWikiLangTestCase' => "$testDir/phpunit/MediaWikiLangTestCase.php",
	'ResourceLoaderTestCase' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderFileModuleTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'TestUser' => "$testDir/phpunit/includes/TestUser.php",
	'LessFileCompilationTest' => "$testDir/phpunit/LessFileCompilationTest.php",

	# tests/phpunit/includes
	'BlockTest' => "$testDir/phpunit/includes/BlockTest.php",
	'RevisionStorageTest' => "$testDir/phpunit/includes/RevisionStorageTest.php",
	'WikiPageTest' => "$testDir/phpunit/includes/WikiPageTest.php",

	# tests/phpunit/includes/api
	'ApiFormatTestBase' => "$testDir/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestContext.php",
	'MockApi' => "$testDir/phpunit/includes/api/MockApi.php",
	'MockApiQueryBase' => "$testDir/phpunit/includes/api/MockApiQueryBase.php",
	'UserWrapper' => "$testDir/phpunit/includes/api/UserWrapper.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",

	# tests/phpunit/includes/content
	'DummyContentHandlerForTesting' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'DummyContentForTesting' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'ContentHandlerTest' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'JavaScriptContentTest' => "$testDir/phpunit/includes/content/JavaScriptContentTest.php",
	'TextContentTest' => "$testDir/phpunit/includes/content/TextContentTest.php",
	'WikitextContentTest' => "$testDir/phpunit/includes/content/WikitextContentTest.php",

	# tests/phpunit/includes/db
	'ORMRowTest' => "$testDir/phpunit/includes/db/ORMRowTest.php",
	'ORMTableTest' => "$testDir/phpunit/includes/db/ORMTableTest.php",
	'PageORMTableForTesting' => "$testDir/phpunit/includes/db/ORMTableTest.php",
	'DatabaseTestHelper' => "$testDir/phpunit/includes/db/DatabaseTestHelper.php",

	# tests/phpunit/languages
	'LanguageClassesTestCase' => "$testDir/phpunit/languages/LanguageClassesTestCase.php",

	# tests/phpunit/includes/libs
	'GenericArrayObjectTest' => "$testDir/phpunit/includes/libs/GenericArrayObjectTest.php",

	# tests/phpunit/maintenance
	'DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",

	# tests/phpunit/media
	'FakeDimensionFile' => "$testDir/phpunit/includes/media/FakeDimensionFile.php",

	# tests/phpunit/mocks
	'MockFSFile' => "$testDir/phpunit/mocks/filebackend/MockFSFile.php",
	'MockFileBackend' => "$testDir/phpunit/mocks/filebackend/MockFileBackend.php",
	'MockBitmapHandler' => "$testDir/phpunit/mocks/media/MockBitmapHandler.php",
	'MockImageHandler' => "$testDir/phpunit/mocks/media/MockImageHandler.php",
	'MockSvgHandler' => "$testDir/phpunit/mocks/media/MockSvgHandler.php",

	# tests/parser
	'NewParserTest' => "$testDir/phpunit/includes/parser/NewParserTest.php",
	'MediaWikiParserTest' => "$testDir/phpunit/includes/parser/MediaWikiParserTest.php",
	'ParserTest' => "$testDir/parser/parserTest.inc",
	'ParserTestParserHook' => "$testDir/parser/parserTestsParserHook.php",

	# tests/phpunit/includes/site
	'SiteTest' => "$testDir/phpunit/includes/site/SiteTest.php",
	'TestSites' => "$testDir/phpunit/includes/site/TestSites.php",
);
