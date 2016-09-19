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
$testDir = __DIR__ . "/..";

$wgAutoloadClasses += [

	# tests/common
	'TestSetup' => "$testDir/common/TestSetup.php",

	# tests/parser
	'DbTestPreviewer' => "$testDir/parser/DbTestPreviewer.php",
	'DbTestRecorder' => "$testDir/parser/DbTestRecorder.php",
	'DjVuSupport' => "$testDir/parser/DjVuSupport.php",
	'TestRecorder' => "$testDir/parser/TestRecorder.php",
	'MultiTestRecorder' => "$testDir/parser/MultiTestRecorder.php",
	'ParserTestRunner' => "$testDir/parser/ParserTestRunner.php",
	'ParserTestParserHook' => "$testDir/parser/ParserTestParserHook.php",
	'ParserTestPrinter' => "$testDir/parser/ParserTestPrinter.php",
	'ParserTestResult' => "$testDir/parser/ParserTestResult.php",
	'ParserTestResultNormalizer' => "$testDir/parser/ParserTestResultNormalizer.php",
	'PhpunitTestRecorder' => "$testDir/parser/PhpunitTestRecorder.php",
	'TestFileReader' => "$testDir/parser/TestFileReader.php",
	'TestRecorder' => "$testDir/parser/TestRecorder.php",
	'TidySupport' => "$testDir/parser/TidySupport.php",

	# tests/phpunit
	'MediaWikiTestCase' => "$testDir/phpunit/MediaWikiTestCase.php",
	'MediaWikiPHPUnitTestListener' => "$testDir/phpunit/MediaWikiPHPUnitTestListener.php",
	'MediaWikiLangTestCase' => "$testDir/phpunit/MediaWikiLangTestCase.php",
	'ResourceLoaderTestCase' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderFileModuleTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'EmptyResourceLoader' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'TestUser' => "$testDir/phpunit/includes/TestUser.php",
	'TestUserRegistry' => "$testDir/phpunit/includes/TestUserRegistry.php",
	'LessFileCompilationTest' => "$testDir/phpunit/LessFileCompilationTest.php",

	# tests/phpunit/includes
	'RevisionStorageTest' => "$testDir/phpunit/includes/RevisionStorageTest.php",
	'TestingAccessWrapper' => "$testDir/phpunit/includes/TestingAccessWrapper.php",
	'TestLogger' => "$testDir/phpunit/includes/TestLogger.php",

	# tests/phpunit/includes/api
	'ApiFormatTestBase' => "$testDir/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiQueryTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryTestBase.php",
	'ApiQueryContinueTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryContinueTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'ApiTestCaseUpload' => "$testDir/phpunit/includes/api/ApiTestCaseUpload.php",
	'ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestContext.php",
	'MockApi' => "$testDir/phpunit/includes/api/MockApi.php",
	'MockApiQueryBase' => "$testDir/phpunit/includes/api/MockApiQueryBase.php",
	'UserWrapper' => "$testDir/phpunit/includes/api/UserWrapper.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",

	# tests/phpunit/includes/auth
	'MediaWiki\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/auth/AuthenticationRequestTestCase.php",

	# tests/phpunit/includes/changes
	'TestRecentChangesHelper' => "$testDir/phpunit/includes/changes/TestRecentChangesHelper.php",

	# tests/phpunit/includes/content
	'DummyContentHandlerForTesting' =>
		"$testDir/phpunit/mocks/content/DummyContentHandlerForTesting.php",
	'DummyContentForTesting' => "$testDir/phpunit/mocks/content/DummyContentForTesting.php",
	'DummyNonTextContentHandler' => "$testDir/phpunit/mocks/content/DummyNonTextContentHandler.php",
	'DummyNonTextContent' => "$testDir/phpunit/mocks/content/DummyNonTextContent.php",
	'ContentHandlerTest' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'JavaScriptContentTest' => "$testDir/phpunit/includes/content/JavaScriptContentTest.php",
	'TextContentTest' => "$testDir/phpunit/includes/content/TextContentTest.php",
	'WikitextContentTest' => "$testDir/phpunit/includes/content/WikitextContentTest.php",

	# tests/phpunit/includes/db
	'DatabaseTestHelper' => "$testDir/phpunit/includes/db/DatabaseTestHelper.php",

	# tests/phpunit/includes/diff
	'FakeDiffOp' => "$testDir/phpunit/includes/diff/FakeDiffOp.php",

	# tests/phpunit/includes/logging
	'LogFormatterTestCase' => "$testDir/phpunit/includes/logging/LogFormatterTestCase.php",

	# tests/phpunit/includes/page
	'WikiPageTest' => "$testDir/phpunit/includes/page/WikiPageTest.php",

	# tests/phpunit/includes/parser
	'ParserIntegrationTest' => "$testDir/phpunit/includes/parser/ParserIntegrationTest.php",

	# tests/phpunit/includes/password
	'PasswordTestCase' => "$testDir/phpunit/includes/password/PasswordTestCase.php",

	# tests/phpunit/includes/resourceloader
	'ResourceLoaderImageModuleTest' =>
		"$testDir/phpunit/includes/resourceloader/ResourceLoaderImageModuleTest.php",
	'ResourceLoaderImageModuleTestable' =>
		"$testDir/phpunit/includes/resourceloader/ResourceLoaderImageModuleTest.php",

	# tests/phpunit/includes/session
	'MediaWiki\\Session\\TestBagOStuff' => "$testDir/phpunit/includes/session/TestBagOStuff.php",
	'MediaWiki\\Session\\TestUtils' => "$testDir/phpunit/includes/session/TestUtils.php",

	# tests/phpunit/includes/site
	'SiteTest' => "$testDir/phpunit/includes/site/SiteTest.php",
	'TestSites' => "$testDir/phpunit/includes/site/TestSites.php",

	# tests/phpunit/includes/specialpage
	'SpecialPageTestHelper' => "$testDir/phpunit/includes/specialpage/SpecialPageTestHelper.php",

	# tests/phpunit/includes/specials
	'SpecialPageTestBase' => "$testDir/phpunit/includes/specials/SpecialPageTestBase.php",
	'SpecialPageExecutor' => "$testDir/phpunit/includes/specials/SpecialPageExecutor.php",

	# tests/phpunit/languages
	'LanguageClassesTestCase' => "$testDir/phpunit/languages/LanguageClassesTestCase.php",

	# tests/phpunit/includes/libs
	'GenericArrayObjectTest' => "$testDir/phpunit/includes/libs/GenericArrayObjectTest.php",

	# tests/phpunit/maintenance
	'DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",

	# tests/phpunit/media
	'FakeDimensionFile' => "$testDir/phpunit/includes/media/FakeDimensionFile.php",
	'MediaWikiMediaTestCase' => "$testDir/phpunit/includes/media/MediaWikiMediaTestCase.php",

	# tests/phpunit/mocks
	'MockFSFile' => "$testDir/phpunit/mocks/filebackend/MockFSFile.php",
	'MockFileBackend' => "$testDir/phpunit/mocks/filebackend/MockFileBackend.php",
	'MockLocalRepo' => "$testDir/phpunit/mocks/filerepo/MockLocalRepo.php",
	'MockBitmapHandler' => "$testDir/phpunit/mocks/media/MockBitmapHandler.php",
	'MockImageHandler' => "$testDir/phpunit/mocks/media/MockImageHandler.php",
	'MockSvgHandler' => "$testDir/phpunit/mocks/media/MockSvgHandler.php",
	'MockDjVuHandler' => "$testDir/phpunit/mocks/media/MockDjVuHandler.php",
	'MockOggHandler' => "$testDir/phpunit/mocks/media/MockOggHandler.php",
	'MockMediaHandlerFactory' => "$testDir/phpunit/mocks/media/MockMediaHandlerFactory.php",
	'MockWebRequest' => "$testDir/phpunit/mocks/MockWebRequest.php",
	'MediaWiki\\Session\\DummySessionBackend'
		=> "$testDir/phpunit/mocks/session/DummySessionBackend.php",
	'DummySessionProvider' => "$testDir/phpunit/mocks/session/DummySessionProvider.php",

	# tests/suites
	'ParserTestFileSuite' => "$testDir/phpunit/suites/ParserTestFileSuite.php",
	'ParserTestTopLevelSuite' => "$testDir/phpunit/suites/ParserTestTopLevelSuite.php",
];
