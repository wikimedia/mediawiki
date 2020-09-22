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

// phpcs:disable Generic.Files.LineLength
$wgAutoloadClasses += [

	# tests/common
	'TestSetup' => "$testDir/common/TestSetup.php",

	# tests/integration
	'MWHttpRequestTestCase' => "$testDir/integration/includes/http/MWHttpRequestTestCase.php",

	# tests/parser
	'DbTestPreviewer' => "$testDir/parser/DbTestPreviewer.php",
	'DbTestRecorder' => "$testDir/parser/DbTestRecorder.php",
	'DjVuSupport' => "$testDir/parser/DjVuSupport.php",
	'MultiTestRecorder' => "$testDir/parser/MultiTestRecorder.php",
	'ParserTestMockParser' => "$testDir/parser/ParserTestMockParser.php",
	'ParserTestRunner' => "$testDir/parser/ParserTestRunner.php",
	'ParserTestParserHook' => "$testDir/parser/ParserTestParserHook.php",
	'ParserTestPrinter' => "$testDir/parser/ParserTestPrinter.php",
	'ParserTestResult' => "$testDir/parser/ParserTestResult.php",
	'ParserTestResultNormalizer' => "$testDir/parser/ParserTestResultNormalizer.php",
	'PhpunitTestRecorder' => "$testDir/parser/PhpunitTestRecorder.php",
	'TestFileEditor' => "$testDir/parser/TestFileEditor.php",
	'TestFileReader' => "$testDir/parser/TestFileReader.php",
	'TestRecorder' => "$testDir/parser/TestRecorder.php",

	# tests/phpunit
	'EmptyResourceLoader' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'HamcrestPHPUnitIntegration' => "$testDir/phpunit/HamcrestPHPUnitIntegration.php",
	'LessFileCompilationTest' => "$testDir/phpunit/LessFileCompilationTest.php",
	'MediaWikiCliOptions' => "$testDir/phpunit/MediaWikiCliOptions.php",
	'MediaWikiCoversValidator' => "$testDir/phpunit/MediaWikiCoversValidator.php",
	'MediaWikiGroupValidator' => "$testDir/phpunit/MediaWikiGroupValidator.php",
	'MediaWikiLangTestCase' => "$testDir/phpunit/MediaWikiLangTestCase.php",
	'MediaWikiLoggerPHPUnitExtension' => "$testDir/phpunit/MediaWikiLoggerPHPUnitExtension.php",
	'MediaWikiPHPUnitCommand' => "$testDir/phpunit/MediaWikiPHPUnitCommand.php",
	'MediaWikiPHPUnitResultPrinter' => "$testDir/phpunit/MediaWikiPHPUnitResultPrinter.php",
	'MediaWikiPHPUnitTestListener' => "$testDir/phpunit/MediaWikiPHPUnitTestListener.php",
	'MediaWikiTestCase' => "$testDir/phpunit/MediaWikiIntegrationTestCase.php",
	'MediaWikiTestCaseTrait' => "$testDir/phpunit/MediaWikiTestCaseTrait.php",
	'MediaWikiUnitTestCase' => "$testDir/phpunit/MediaWikiUnitTestCase.php",
	'MediaWikiIntegrationTestCase' => "$testDir/phpunit/MediaWikiIntegrationTestCase.php",
	'PHPUnit4And6Compat' => "$testDir/phpunit/PHPUnit4And6Compat.php",
	'PHPUnit6And8Compat' => "$testDir/phpunit/PHPUnit6And8Compat.php",
	'ResourceLoaderFileModuleTestingSubclass' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderFileTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestCase' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'TestLocalisationCache' => "$testDir/phpunit/includes/TestLocalisationCache.php",
	'TestUser' => "$testDir/phpunit/includes/TestUser.php",
	'TestUserRegistry' => "$testDir/phpunit/includes/TestUserRegistry.php",

	# tests/phpunit/includes
	'FactoryArgTestTrait' => "$testDir/phpunit/unit/includes/FactoryArgTestTrait.php",
	'RevisionTestModifyableContent' => "$testDir/phpunit/includes/RevisionTestModifyableContent.php",
	'RevisionTestModifyableContentHandler' => "$testDir/phpunit/includes/RevisionTestModifyableContentHandler.php",
	'TestLogger' => "$testDir/phpunit/includes/TestLogger.php",

	# tests/phpunit/includes/api
	'ApiFormatTestBase' => "$testDir/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiQueryTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryTestBase.php",
	'ApiQueryContinueTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryContinueTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestContext.php",
	'ApiUploadTestCase' => "$testDir/phpunit/includes/api/ApiUploadTestCase.php",
	'MockApi' => "$testDir/phpunit/includes/api/MockApi.php",
	'MockApiQueryBase' => "$testDir/phpunit/includes/api/MockApiQueryBase.php",
	'UserWrapper' => "$testDir/phpunit/includes/api/UserWrapper.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",

	# tests/phpunit/includes/auth
	'MediaWiki\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/auth/AuthenticationRequestTestCase.php",

	# tests/phpunit/includes/block
	'MediaWiki\\Tests\\Block\\Restriction\\RestrictionTestCase' => "$testDir/phpunit/includes/block/Restriction/RestrictionTestCase.php",

	# tests/phpunit/includes/changes
	'TestRecentChangesHelper' => "$testDir/phpunit/includes/changes/TestRecentChangesHelper.php",

	# tests/phpunit/includes/config
	'TestAllServiceOptionsUsed' => "$testDir/phpunit/includes/config/TestAllServiceOptionsUsed.php",
	'LoggedServiceOptions' => "$testDir/phpunit/includes/config/LoggedServiceOptions.php",

	# tests/phpunit/includes/content
	'DummyContentHandlerForTesting' =>
		"$testDir/phpunit/mocks/content/DummyContentHandlerForTesting.php",
	'DummyContentForTesting' => "$testDir/phpunit/mocks/content/DummyContentForTesting.php",
	'DummyNonTextContentHandler' => "$testDir/phpunit/mocks/content/DummyNonTextContentHandler.php",
	'DummyNonTextContent' => "$testDir/phpunit/mocks/content/DummyNonTextContent.php",
	'DummySerializeErrorContentHandler' =>
		"$testDir/phpunit/mocks/content/DummySerializeErrorContentHandler.php",
	'ContentHandlerTest' => "$testDir/phpunit/includes/content/ContentHandlerTest.php",
	'JavaScriptContentTest' => "$testDir/phpunit/includes/content/JavaScriptContentTest.php",
	'ObjectFactoryMakeContentHandlerWithSpecsToTest' => "$testDir/phpunit/includes/content/ObjectFactoryMakeContentHandlerWithSpecsToTest.php",
	'TextContentTest' => "$testDir/phpunit/includes/content/TextContentTest.php",
	'WikitextContentTest' => "$testDir/phpunit/includes/content/WikitextContentTest.php",

	# tests/phpunit/includes/db
	'DatabaseTestHelper' => "$testDir/phpunit/includes/db/DatabaseTestHelper.php",

	# tests/phpunit/includes/debug
	'TestDeprecatedClass' => "$testDir/phpunit/includes/debug/TestDeprecatedClass.php",
	'TestDeprecatedSubclass' => "$testDir/phpunit/includes/debug/TestDeprecatedSubclass.php",

	# tests/phpunit/includes/diff
	'CustomDifferenceEngine' => "$testDir/phpunit/includes/diff/CustomDifferenceEngine.php",
	'FakeDiffOp' => "$testDir/phpunit/includes/diff/FakeDiffOp.php",

	# tests/phpunit/includes/externalstore
	'ExternalStoreForTesting' => "$testDir/phpunit/includes/externalstore/ExternalStoreForTesting.php",

	# tests/phpunit/includes/logging
	'LogFormatterTestCase' => "$testDir/phpunit/includes/logging/LogFormatterTestCase.php",

	# tests/phpunit/includes/parser
	'ParserIntegrationTest' => "$testDir/phpunit/suites/ParserIntegrationTest.php",

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
	'AbstractChangesListSpecialPageTestCase' => "$testDir/phpunit/includes/specialpage/AbstractChangesListSpecialPageTestCase.php",
	'FormSpecialPageTestCase' => "$testDir/phpunit/includes/specialpage/FormSpecialPageTestCase.php",

	# tests/phpunit/includes/specials
	'SpecialPageTestBase' => "$testDir/phpunit/includes/specials/SpecialPageTestBase.php",
	'SpecialPageExecutor' => "$testDir/phpunit/includes/specials/SpecialPageExecutor.php",

	# tests/phpunit/includes/Revision
	'MediaWiki\Tests\Revision\RevisionSlotsTest' => "$testDir/phpunit/includes/Revision/RevisionSlotsTest.php",
	'MediaWiki\Tests\Revision\RevisionRecordTests' => "$testDir/phpunit/includes/Revision/RevisionRecordTests.php",
	'MediaWiki\Tests\Revision\RevisionStoreDbTestBase' => "$testDir/phpunit/includes/Revision/RevisionStoreDbTestBase.php",
	'MediaWiki\Tests\Revision\RevisionStoreRecordTest' => "$testDir/phpunit/includes/Revision/RevisionStoreRecordTest.php",

	# test/phpunit/includes/user
	'UserOptionsLookupTest' => "$testDir/phpunit/includes/user/UserOptionsLookupTest.php",

	# tests/phpunit/languages
	'DummyConverter' => "$testDir/phpunit/mocks/languages/DummyConverter.php",
	'LanguageClassesTestCase' => "$testDir/phpunit/languages/LanguageClassesTestCase.php",
	'LanguageConverterTestTrait' => "$testDir/phpunit/languages/LanguageConverterTestTrait.php",

	# tests/phpunit/includes/libs
	'BagOStuffTestBase' => "$testDir/phpunit/includes/libs/objectcache/BagOStuffTestBase.php",
	'GenericArrayObjectTest' => "$testDir/phpunit/includes/libs/GenericArrayObjectTest.php",
	'Wikimedia\ParamValidator\TypeDef\TypeDefTestCase' => "$testDir/phpunit/includes/libs/ParamValidator/TypeDef/TypeDefTestCase.php",

	# tests/phpunit/maintenance
	'MediaWiki\Tests\Maintenance\DumpAsserter' => "$testDir/phpunit/maintenance/DumpAsserter.php",
	'MediaWiki\Tests\Maintenance\DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",
	'MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase' => "$testDir/phpunit/maintenance/MaintenanceBaseTestCase.php",
	'MediaWiki\Tests\Maintenance\PageDumpTestDataTrait' => "$testDir/phpunit/maintenance/PageDumpTestDataTrait.php",

	# tests/phpunit/media
	'FakeDimensionFile' => "$testDir/phpunit/includes/media/FakeDimensionFile.php",
	'MediaWikiMediaTestCase' => "$testDir/phpunit/includes/media/MediaWikiMediaTestCase.php",

	# tests/phpunit/mocks
	'MockFSFile' => "$testDir/phpunit/mocks/filebackend/MockFSFile.php",
	'MockFileBackend' => "$testDir/phpunit/mocks/filebackend/MockFileBackend.php",
	'MockLocalRepo' => "$testDir/phpunit/mocks/filerepo/MockLocalRepo.php",
	'MockBitmapHandler' => "$testDir/phpunit/mocks/media/MockBitmapHandler.php",
	'MockPoolCounterFailing' => "$testDir/phpunit/mocks/poolcounter/MockPoolCounterFailing.php",
	'MockImageHandler' => "$testDir/phpunit/mocks/media/MockImageHandler.php",
	'MockSvgHandler' => "$testDir/phpunit/mocks/media/MockSvgHandler.php",
	'MockDjVuHandler' => "$testDir/phpunit/mocks/media/MockDjVuHandler.php",
	'MockChangesListFilter' => "$testDir/phpunit/mocks/MockChangesListFilter.php",
	'MockChangesListFilterGroup' => "$testDir/phpunit/mocks/MockChangesListFilterGroup.php",
	'MockWebRequest' => "$testDir/phpunit/mocks/MockWebRequest.php",
	'MediaWiki\\Session\\DummySessionBackend'
		=> "$testDir/phpunit/mocks/session/DummySessionBackend.php",
	'DummySessionProvider' => "$testDir/phpunit/mocks/session/DummySessionProvider.php",
	'MockMessageLocalizer' => "$testDir/phpunit/mocks/MockMessageLocalizer.php",
	'MockCompletionSearchEngine' => "$testDir/phpunit/mocks/search/MockCompletionSearchEngine.php",
	'MockSearchEngine' => "$testDir/phpunit/mocks/search/MockSearchEngine.php",
	'MockSearchResultSet' => "$testDir/phpunit/mocks/search/MockSearchResultSet.php",
	'MockSearchResult' => "$testDir/phpunit/mocks/search/MockSearchResult.php",

	# tests/phpunit/unit/includes
	'BadFileLookupTest' => "$testDir/phpunit/unit/includes/BadFileLookupTest.php",

	# tests/phpunit/unit/includes/filebackend
	'FileBackendGroupTestTrait' => "$testDir/phpunit/unit/includes/filebackend/FileBackendGroupTestTrait.php",

	# tests/phpunit/unit/includes/language
	'LanguageFallbackTestTrait' => "$testDir/phpunit/unit/includes/language/LanguageFallbackTestTrait.php",
	'LanguageNameUtilsTestTrait' => "$testDir/phpunit/unit/includes/language/LanguageNameUtilsTestTrait.php",

	# tests/phpunit/unit/includes/libs/filebackend/fsfile
	'TempFSFileTestTrait' => "$testDir/phpunit/unit/includes/libs/filebackend/fsfile/TempFSFileTestTrait.php",

	# tests/phpunit/includes/unit/password
	'PasswordTestCase' => "$testDir/phpunit/unit/includes/password/PasswordTestCase.php",

	# tests/phpunit/unit/includes/Rest/Handler
	'MediaWiki\Tests\Rest\Handler\ActionModuleBasedHandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/ActionModuleBasedHandlerTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\HandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/HandlerTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\HelloHandler' => "$testDir/phpunit/unit/includes/Rest/Handler/HelloHandler.php",
	'MediaWiki\Tests\Rest\Handler\MediaTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/MediaTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\ContributionsTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/ContributionsTestTrait.php",

	# tests/suites
	'ParserTestFileSuite' => "$testDir/phpunit/suites/ParserTestFileSuite.php",
	'ParserTestTopLevelSuite' => "$testDir/phpunit/suites/ParserTestTopLevelSuite.php",
	'SuiteEventsTrait' => "$testDir/phpunit/suites/SuiteEventsTrait.php",
];
// phpcs:enable

/**
 * Alias any PHPUnit 4 era PHPUnit_... class
 * to it's PHPUnit 6 replacement. For most classes
 * this is a direct _ -> \ replacement, but for
 * some others we might need to maintain a manual
 * mapping. Once we drop support for PHPUnit 4 this
 * should be considered deprecated and eventually removed.
 */
spl_autoload_register( function ( $class ) {
	if ( strpos( $class, 'PHPUnit_' ) !== 0 ) {
		// Skip if it doesn't start with the old prefix
		return;
	}

	// Classes that don't map 100%
	$map = [
		'PHPUnit_Framework_TestSuite_DataProvider' => 'PHPUnit\Framework\DataProviderTestSuite',
		'PHPUnit_Framework_Error' => 'PHPUnit\Framework\Error\Error',
	];

	$newForm = $map[$class] ?? str_replace( '_', '\\', $class );

	if ( class_exists( $newForm ) || interface_exists( $newForm ) ) {
		// If the new class name exists, alias
		// the old name to it.
		class_alias( $newForm, $class );
	}
} );
