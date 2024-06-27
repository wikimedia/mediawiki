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

	# tests/exception
	'TestThrowerDummy' => "$testDir/phpunit/data/exception/TestThrowerDummy.php",

	# tests/parser
	'DbTestPreviewer' => "$testDir/parser/DbTestPreviewer.php",
	'DbTestRecorder' => "$testDir/parser/DbTestRecorder.php",
	'DjVuSupport' => "$testDir/parser/DjVuSupport.php",
	'MediaWiki\\Tests\\AnsiTermColorer' => "$testDir/parser/AnsiTermColorer.php",
	'MediaWiki\\Tests\\DummyTermColorer' => "$testDir/parser/DummyTermColorer.php",
	'MultiTestRecorder' => "$testDir/parser/MultiTestRecorder.php",
	'ParserTestMockParser' => "$testDir/parser/ParserTestMockParser.php",
	'ParserTestParserHook' => "$testDir/parser/ParserTestParserHook.php",
	'ParserTestPrinter' => "$testDir/parser/ParserTestPrinter.php",
	'ParserTestResult' => "$testDir/parser/ParserTestResult.php",
	'ParserTestResultNormalizer' => "$testDir/parser/ParserTestResultNormalizer.php",
	'ParserTestRunner' => "$testDir/parser/ParserTestRunner.php",
	'PhpunitTestRecorder' => "$testDir/parser/PhpunitTestRecorder.php",
	'TestFileEditor' => "$testDir/parser/TestFileEditor.php",
	'TestRecorder' => "$testDir/parser/TestRecorder.php",

	# tests/phpunit
	'DynamicPropertyTestHelper' => "$testDir/phpunit/DynamicPropertyTestHelper.php",
	'EmptyResourceLoader' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'HamcrestPHPUnitIntegration' => "$testDir/phpunit/HamcrestPHPUnitIntegration.php",
	'LessFileCompilationTest' => "$testDir/phpunit/LessFileCompilationTest.php",
	'MediaWikiCliOptions' => "$testDir/phpunit/MediaWikiCliOptions.php",
	'MediaWikiCoversValidator' => "$testDir/phpunit/MediaWikiCoversValidator.php",
	'MediaWikiGroupValidator' => "$testDir/phpunit/MediaWikiGroupValidator.php",
	'MediaWikiLangTestCase' => "$testDir/phpunit/MediaWikiLangTestCase.php",
	'MediaWikiLoggerPHPUnitExtension' => "$testDir/phpunit/MediaWikiLoggerPHPUnitExtension.php",
	'MediaWikiPHPUnitResultPrinter' => "$testDir/phpunit/MediaWikiPHPUnitResultPrinter.php",
	'MediaWikiTestCaseTrait' => "$testDir/phpunit/MediaWikiTestCaseTrait.php",
	'MediaWikiUnitTestCase' => "$testDir/phpunit/MediaWikiUnitTestCase.php",
	'MediaWikiIntegrationTestCase' => "$testDir/phpunit/MediaWikiIntegrationTestCase.php",
	'ResourceLoaderFileModuleTestingSubclass' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderFileTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestCase' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'TestLocalisationCache' => "$testDir/phpunit/mocks/TestLocalisationCache.php",
	'TestUser' => "$testDir/phpunit/includes/TestUser.php",
	'TestUserRegistry' => "$testDir/phpunit/includes/TestUserRegistry.php",
	'MWTestDox' => "$testDir/phpunit/MWTestDox.php",

	# tests/phpunit/includes
	'FactoryArgTestTrait' => "$testDir/phpunit/unit/includes/FactoryArgTestTrait.php",
	'TestLogger' => "$testDir/phpunit/mocks/TestLogger.php",

	# tests/phpunit/includes/api
	'ApiFormatTestBase' => "$testDir/phpunit/includes/api/format/ApiFormatTestBase.php",
	'ApiQueryTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryTestBase.php",
	'ApiQueryContinueTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryContinueTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestContext.php",
	'ApiUploadTestCase' => "$testDir/phpunit/includes/api/ApiUploadTestCase.php",
	'MockApi' => "$testDir/phpunit/includes/api/MockApi.php",
	'MockApiQueryBase' => "$testDir/phpunit/includes/api/MockApiQueryBase.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",

	# tests/phpunit/includes/auth
	'MediaWiki\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/auth/AuthenticationRequestTestCase.php",

	# tests/phpunit/includes/block
	'MediaWiki\\Tests\\Block\\Restriction\\RestrictionTestCase' => "$testDir/phpunit/includes/block/Restriction/RestrictionTestCase.php",

	# tests/phpunit/includes/cache
	'LinkCacheTestTrait' => "$testDir/phpunit/includes/cache/LinkCacheTestTrait.php",

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
	'TextContentTest' => "$testDir/phpunit/includes/content/TextContentTest.php",
	'TextContentHandlerIntegrationTest' => "$testDir/phpunit/includes/content/TextContentHandlerIntegrationTest.php",
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
	'MediaWiki\Tests\Parser\ParserCacheSerializationTestCases' =>
		"$testDir/phpunit/includes/parser/ParserCacheSerializationTestCases.php",
	'Wikimedia\Tests\SerializationTestTrait' =>
		"$testDir/phpunit/includes/libs/serialization/SerializationTestTrait.php",
	'Wikimedia\Tests\SerializationTestUtils' =>
		"$testDir/phpunit/includes/libs/serialization/SerializationTestUtils.php",

	# tests/phpunit/includes/poolcounter
	'PoolWorkArticleViewTest' =>
		"$testDir/phpunit/includes/poolcounter/PoolWorkArticleViewTest.php",

	# tests/phpunit/includes/ResourceLoader
	'MediaWiki\\Tests\\ResourceLoader\\ImageModuleTest' =>
		"$testDir/phpunit/includes/ResourceLoader/ImageModuleTest.php",
	'MediaWiki\\Tests\\ResourceLoader\\ImageModuleTestable' =>
		"$testDir/phpunit/includes/ResourceLoader/ImageModuleTest.php",

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

	# test/phpunit/includes/user
	'UserOptionsLookupTest' => "$testDir/phpunit/includes/user/UserOptionsLookupTest.php",

	# tests/phpunit/languages
	'DummyConverter' => "$testDir/phpunit/mocks/languages/DummyConverter.php",
	'LanguageClassesTestCase' => "$testDir/phpunit/languages/LanguageClassesTestCase.php",
	'LanguageConverterTestTrait' => "$testDir/phpunit/languages/LanguageConverterTestTrait.php",

	# tests/phpunit/includes/libs
	'BagOStuffTestBase' => "$testDir/phpunit/includes/libs/objectcache/BagOStuffTestBase.php",
	'GenericArrayObjectTest' => "$testDir/phpunit/includes/libs/GenericArrayObjectTest.php",
	'Wikimedia\ParamValidator\TypeDef\TypeDefTestCase' => "$testDir/phpunit/unit/includes/libs/ParamValidator/TypeDef/TypeDefTestCase.php",
	'Wikimedia\ParamValidator\TypeDef\TypeDefTestCaseTrait' => "$testDir/phpunit/unit/includes/libs/ParamValidator/TypeDef/TypeDefTestCaseTrait.php",

	# tests/phpunit/includes/ParamValidator
	'MediaWiki\ParamValidator\TypeDef\TypeDefIntegrationTestCase' => "$testDir/phpunit/includes/ParamValidator/TypeDef/TypeDefIntegrationTestCase.php",

	# tests/phpunit/maintenance
	'MediaWiki\Tests\Maintenance\DumpAsserter' => "$testDir/phpunit/maintenance/DumpAsserter.php",
	'MediaWiki\Tests\Maintenance\DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",
	'MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase' => "$testDir/phpunit/maintenance/MaintenanceBaseTestCase.php",
	'MediaWiki\Tests\Maintenance\PageDumpTestDataTrait' => "$testDir/phpunit/maintenance/PageDumpTestDataTrait.php",

	# tests/phpunit/media
	'FakeDimensionFile' => "$testDir/phpunit/includes/media/FakeDimensionFile.php",
	'MediaWikiMediaTestCase' => "$testDir/phpunit/includes/media/MediaWikiMediaTestCase.php",

	# tests/phpunit/mocks
	'MockHttpTrait' => "$testDir/phpunit/mocks/MockHttpTrait.php",
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
	'MockTitleTrait' => "$testDir/phpunit/mocks/MockTitleTrait.php",
	'NullGuzzleClient' => "$testDir/phpunit/mocks/NullGuzzleClient.php",
	'NullHttpRequestFactory' => "$testDir/phpunit/mocks/NullHttpRequestFactory.php",
	'NullMultiHttpClient' => "$testDir/phpunit/mocks/NullMultiHttpClient.php",
	'MediaWiki\\Tests\\Unit\\MockServiceDependenciesTrait'
		=> "$testDir/phpunit/mocks/MockServiceDependenciesTrait.php",
	'MediaWiki\\Tests\\Unit\\DummyServicesTrait'
		=> "$testDir/phpunit/mocks/DummyServicesTrait.php",
	'MediaWiki\\Tests\\Unit\\FakeQqxMessageLocalizer'
		=> "$testDir/phpunit/mocks/FakeQqxMessageLocalizer.php",
	'MediaWiki\\Session\\DummySessionBackend'
		=> "$testDir/phpunit/mocks/session/DummySessionBackend.php",
	'DummySessionProvider' => "$testDir/phpunit/mocks/session/DummySessionProvider.php",
	'MockMessageLocalizer' => "$testDir/phpunit/mocks/MockMessageLocalizer.php",
	'MockCompletionSearchEngine' => "$testDir/phpunit/mocks/search/MockCompletionSearchEngine.php",
	'MockSearchEngine' => "$testDir/phpunit/mocks/search/MockSearchEngine.php",
	'MockSearchResultSet' => "$testDir/phpunit/mocks/search/MockSearchResultSet.php",
	'MockSearchResult' => "$testDir/phpunit/mocks/search/MockSearchResult.php",

	# tests/phpunit/unit/includes
	'Wikimedia\\Reflection\\GhostFieldTestClass' => "$testDir/phpunit/mocks/GhostFieldTestClass.php",

	# tests/phpunit/unit/includes/auth
	'MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait' => "$testDir/phpunit/unit/includes/auth/AuthenticationProviderTestTrait.php",

	# tests/phpunit/unit/includes/CommentFormatter
	'MediaWiki\Tests\Unit\CommentFormatter\CommentFormatterTestUtils' => "$testDir/phpunit/unit/includes/CommentFormatter/CommentFormatterTestUtils.php",

	# tests/phpunit/unit/includes/editpage/Constraint and tests/phpunit/integration/includes/editpage/Constraint
	'EditConstraintTestTrait' => "$testDir/phpunit/unit/includes/editpage/Constraint/EditConstraintTestTrait.php",

	# tests/phpunit/unit/includes/filebackend
	'FileBackendGroupTestTrait' => "$testDir/phpunit/unit/includes/filebackend/FileBackendGroupTestTrait.php",

	# tests/phpunit/unit/includes/HookContainer
	'MediaWiki\Tests\HookContainer\HookRunnerTestBase' => "$testDir/phpunit/unit/includes/HookContainer/HookRunnerTestBase.php",

	# tests/phpunit/unit/includes/json
	'MediaWiki\\Tests\\Json\\JsonUnserializableSuperClass' => "$testDir/phpunit/mocks/json/JsonUnserializableSuperClass.php",
	'MediaWiki\\Tests\\Json\\JsonUnserializableSubClass' => "$testDir/phpunit/mocks/json/JsonUnserializableSubClass.php",

	# tests/phpunit/unit/includes/language
	'LanguageFallbackTestTrait' => "$testDir/phpunit/unit/includes/language/LanguageFallbackTestTrait.php",
	'LanguageNameUtilsTestTrait' => "$testDir/phpunit/unit/includes/language/LanguageNameUtilsTestTrait.php",

	# tests/phpunit/unit/includes/libs/filebackend/fsfile
	'TempFSFileTestTrait' => "$testDir/phpunit/unit/includes/libs/filebackend/fsfile/TempFSFileTestTrait.php",

	# tests/phpunit/unit/includes/libs/filebackend/fsfile
	'MediaWiki\\Tests\\Unit\\Libs\\Rdbms\\AddQuoterMock' => "$testDir/phpunit/unit/includes/libs/rdbms/AddQuoterMock.php",
	'MediaWiki\\Tests\\Unit\\Libs\\Rdbms\\SQLPlatformTestHelper' => "$testDir/phpunit/unit/includes/libs/rdbms/SQLPlatformTestHelper.php",

	# tests/phpunit/unit/includes/utils
	'UrlUtilsProviders' => "$testDir/phpunit/unit/includes/utils/UrlUtilsProviders.php",

	# tests/phpunit/includes/unit/password
	'PasswordTestCase' => "$testDir/phpunit/unit/includes/password/PasswordTestCase.php",

	# tests/phpunit/integration/includes/user
	'MediaWiki\Tests\User\ActorStoreTestBase' => "$testDir/phpunit/integration/includes/user/ActorStoreTestBase.php",

	# tests/phpunit/structure
	'MediaWiki\Tests\Structure\BundleSizeTest' => "$testDir/phpunit/structure/BundleSizeTest.php",

	# tests/phpunit/unit/includes/Rest
	'MediaWiki\Tests\Rest\RestTestTrait' => "$testDir/phpunit/unit/includes/Rest/RestTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\SessionHelperTestTrait' => "$testDir/phpunit/unit/includes/Rest/SessionHelperTestTrait.php",

	# tests/phpunit/unit/includes/Rest/Handler
	'MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait' => "$testDir/phpunit/mocks/permissions/MockAuthorityTrait.php",
	'MediaWiki\Tests\Rest\Handler\ActionModuleBasedHandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/ActionModuleBasedHandlerTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\HTMLHandlerTestTrait' => "$testDir/phpunit/integration/includes/Rest/Handler/HTMLHandlerTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\HandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/HandlerTestTrait.php",
	'MediaWiki\Tests\Rest\Handler\HelloHandler' => "$testDir/phpunit/unit/includes/Rest/Handler/HelloHandler.php",
	'MediaWiki\Tests\Rest\Handler\MediaTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/MediaTestTrait.php",

	# tests/phpunit/unit/includes/Revision
	'MediaWiki\Tests\Unit\Revision\RevisionRecordTests' => "$testDir/phpunit/unit/includes/Revision/RevisionRecordTests.php",
	'MediaWiki\Tests\Unit\Revision\RevisionSlotsTest' => "$testDir/phpunit/unit/includes/Revision/RevisionSlotsTest.php",
	'MediaWiki\Tests\Unit\Revision\RevisionStoreRecordTest' => "$testDir/phpunit/unit/includes/Revision/RevisionStoreRecordTest.php",

	# tests/phpunit/unit/includes/Settings/Config
	'MediaWiki\Tests\Unit\Settings\Config\ConfigSinkTestTrait' => "$testDir/phpunit/unit/includes/Settings/Config/ConfigSinkTestTrait.php",

	# tests/phpunit/unit/includes/session
	'MediaWiki\Session\SessionProviderTestTrait' => "$testDir/phpunit/unit/includes/session/SessionProviderTestTrait.php",

	# tests/suites
	'ParserTestFileSuite' => "$testDir/phpunit/suites/ParserTestFileSuite.php",
	'ParsoidTestFileSuite' => "$testDir/phpunit/suites/ParsoidTestFileSuite.php",
	'ParserTestTopLevelSuite' => "$testDir/phpunit/suites/ParserTestTopLevelSuite.php",
	'SuiteEventsTrait' => "$testDir/phpunit/suites/SuiteEventsTrait.php",
];
// phpcs:enable

/**
 * Alias any PHPUnit 4 era PHPUnit_... class
 * to its PHPUnit 6 replacement. For most classes
 * this is a direct _ -> \ replacement, but for
 * some others we might need to maintain a manual
 * mapping. Once we drop support for PHPUnit 4 this
 * should be considered deprecated and eventually removed.
 */
spl_autoload_register( static function ( $class ) {
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
