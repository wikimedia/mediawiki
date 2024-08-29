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
	'JsonSchemaAssertionTrait' => "$testDir/phpunit/JsonSchemaAssertionTrait.php",
	'MediaWiki\\Tests\\ResourceLoader\\EmptyResourceLoader' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'HamcrestPHPUnitIntegration' => "$testDir/phpunit/HamcrestPHPUnitIntegration.php",
	'MediaWikiCoversValidator' => "$testDir/phpunit/MediaWikiCoversValidator.php",
	'MediaWikiGroupValidator' => "$testDir/phpunit/MediaWikiGroupValidator.php",
	'MediaWikiLangTestCase' => "$testDir/phpunit/MediaWikiLangTestCase.php",
	'MediaWikiLoggerPHPUnitExtension' => "$testDir/phpunit/MediaWikiLoggerPHPUnitExtension.php",
	'MediaWikiTeardownPHPUnitExtension' => "$testDir/phpunit/MediaWikiTeardownPHPUnitExtension.php",
	'MediaWikiDeprecatedConfigPHPUnitExtension' => "$testDir/phpunit/MediaWikiDeprecatedConfigPHPUnitExtension.php",
	'MediaWikiPHPUnitResultPrinter' => "$testDir/phpunit/MediaWikiPHPUnitResultPrinter.php",
	'MediaWikiTestCaseTrait' => "$testDir/phpunit/MediaWikiTestCaseTrait.php",
	'MediaWikiUnitTestCase' => "$testDir/phpunit/MediaWikiUnitTestCase.php",
	'MediaWikiIntegrationTestCase' => "$testDir/phpunit/MediaWikiIntegrationTestCase.php",
	'ResourceLoaderFileModuleTestingSubclass' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'MediaWiki\\Tests\\ResourceLoader\\ResourceLoaderFileModuleTestingSubclass' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderFileTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'MediaWiki\\Tests\\ResourceLoader\\ResourceLoaderFileTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestCase' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'MediaWiki\\Tests\\ResourceLoader\\ResourceLoaderTestCase' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'ResourceLoaderTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'MediaWiki\\Tests\\ResourceLoader\\ResourceLoaderTestModule' => "$testDir/phpunit/ResourceLoaderTestCase.php",
	'TestSelectQueryBuilder' => "$testDir/phpunit/TestSelectQueryBuilder.php",
	'TestLocalisationCache' => "$testDir/phpunit/mocks/TestLocalisationCache.php",
	'TestUser' => "$testDir/phpunit/includes/TestUser.php",
	'TestUserRegistry' => "$testDir/phpunit/includes/TestUserRegistry.php",
	'MWTestDox' => "$testDir/phpunit/MWTestDox.php",

	# tests/phpunit/includes
	'FactoryArgTestTrait' => "$testDir/phpunit/unit/includes/FactoryArgTestTrait.php",
	'TestLogger' => "$testDir/phpunit/mocks/TestLogger.php",

	# tests/phpunit/includes/api
	'ApiQueryTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestContext.php",
	'ApiUploadTestCase' => "$testDir/phpunit/includes/api/ApiUploadTestCase.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",
	'MediaWiki\\Tests\\Api\\Format\\ApiFormatTestBase' => "$testDir/phpunit/includes/api/format/ApiFormatTestBase.php",
	'MediaWiki\\Tests\\Api\\Query\\ApiQueryTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryTestBase.php",
	'MediaWiki\\Tests\\Api\\Query\\ApiQueryContinueTestBase' => "$testDir/phpunit/includes/api/query/ApiQueryContinueTestBase.php",
	'MediaWiki\\Tests\\Api\\ApiTestCase' => "$testDir/phpunit/includes/api/ApiTestCase.php",
	'MediaWiki\\Tests\\Api\\ApiTestContext' => "$testDir/phpunit/includes/api/ApiTestContext.php",
	'MediaWiki\\Tests\\Api\\ApiUploadTestCase' => "$testDir/phpunit/includes/api/ApiUploadTestCase.php",
	'MediaWiki\\Tests\\Api\\MockApi' => "$testDir/phpunit/includes/api/MockApi.php",
	'MediaWiki\\Tests\\Api\\MockApiQueryBase' => "$testDir/phpunit/includes/api/MockApiQueryBase.php",
	'MediaWiki\\Tests\\Api\\RandomImageGenerator' => "$testDir/phpunit/includes/api/RandomImageGenerator.php",

	# tests/phpunit/includes/auth
	'MediaWiki\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/auth/AuthenticationRequestTestCase.php",
	'MediaWiki\\Tests\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/auth/AuthenticationRequestTestCase.php",

	# tests/phpunit/includes/block
	'MediaWiki\\Tests\\Block\\Restriction\\RestrictionTestCase' => "$testDir/phpunit/includes/block/Restriction/RestrictionTestCase.php",

	# tests/phpunit/includes/cache
	'LinkCacheTestTrait' => "$testDir/phpunit/includes/cache/LinkCacheTestTrait.php",

	# tests/phpunit/includes/recentchanges
	'TestRecentChangesHelper' => "$testDir/phpunit/includes/recentchanges/TestRecentChangesHelper.php",
	'MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait' => "$testDir/phpunit/includes/recentchanges/ChangeTrackingUpdateSpyTrait.php",

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
	'JavaScriptContentHandlerTest' => "$testDir/phpunit/includes/content/JavaScriptContentHandlerTest.php",

	# tests/phpunit/includes/db
	'DatabaseTestHelper' => "$testDir/phpunit/includes/db/DatabaseTestHelper.php",

	# tests/phpunit/includes/debug
	'TestDeprecatedClass' => "$testDir/phpunit/includes/debug/TestDeprecatedClass.php",
	'TestDeprecatedSubclass' => "$testDir/phpunit/includes/debug/TestDeprecatedSubclass.php",

	# tests/phpunit/includes/diff
	'CustomDifferenceEngine' => "$testDir/phpunit/includes/diff/CustomDifferenceEngine.php",
	'MediaWiki\\Tests\\Diff\\TextDiffer\\TextDifferData' => "$testDir/phpunit/includes/diff/TextDiffer/TextDifferData.php",

	# tests/phpunit/includes/externalstore
	'ExternalStoreForTesting' => "$testDir/phpunit/includes/externalstore/ExternalStoreForTesting.php",

	# tests/phpunit/includes/logging
	'LogFormatterTestCase' => "$testDir/phpunit/includes/logging/LogFormatterTestCase.php",

	# tests/phpunit/includes/OutputTransform
	'MediaWiki\\Tests\\OutputTransform\\DummyDOMTransformStage' => "$testDir/phpunit/includes/OutputTransform/DummyDOMTransformStage.php",
	'MediaWiki\\Tests\\OutputTransform\\TestUtils' => "$testDir/phpunit/includes/OutputTransform/TestUtils.php",
	'MediaWiki\\Tests\\OutputTransform\\OutputTransformStageTestBase' => "$testDir/phpunit/includes/OutputTransform/OutputTransformStageTestBase.php",

	# tests/phpunit/includes/parser
	'MediaWiki\\Tests\\Parser\\CacheTimeTest' => "$testDir/phpunit/includes/parser/CacheTimeTest.php",
	'MediaWiki\\Tests\\Parser\\ParserOutputTest' => "$testDir/phpunit/includes/parser/ParserOutputTest.php",
	'ParserIntegrationTest' => "$testDir/phpunit/suites/ParserIntegrationTest.php",
	'MediaWiki\\Tests\\Parser\\ParserCacheSerializationTestCases' =>
		"$testDir/phpunit/includes/parser/ParserCacheSerializationTestCases.php",
	'Wikimedia\\Tests\\SerializationTestTrait' =>
		"$testDir/phpunit/includes/libs/serialization/SerializationTestTrait.php",
	'Wikimedia\\Tests\\SerializationTestUtils' =>
		"$testDir/phpunit/includes/libs/serialization/SerializationTestUtils.php",

	# tests/phpunit/includes/poolcounter
	'PoolWorkArticleViewTest' =>
		"$testDir/phpunit/includes/poolcounter/PoolWorkArticleViewTest.php",

	# tests/phpunit/includes/ResourceLoader
	'MediaWiki\\Tests\\ResourceLoader\\ImageModuleTest' =>
		"$testDir/phpunit/includes/ResourceLoader/ImageModuleTest.php",
	'MediaWiki\\Tests\\ResourceLoader\\ImageModuleTestable' =>
		"$testDir/phpunit/includes/ResourceLoader/ImageModuleTest.php",
	'MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait' =>
		"$testDir/phpunit/includes/ResourceLoader/ResourceLoaderUpdateSpyTrait.php",

	# tests/phpunit/includes/session
	'MediaWiki\\Session\\TestBagOStuff' => "$testDir/phpunit/includes/session/TestBagOStuff.php",
	'MediaWiki\\Tests\\Session\\TestBagOStuff' => "$testDir/phpunit/includes/session/TestBagOStuff.php",
	'MediaWiki\\Tests\\Session\\TestUtils' => "$testDir/phpunit/includes/session/TestUtils.php",

	# tests/phpunit/includes/site
	'TestSites' => "$testDir/phpunit/includes/site/TestSites.php",
	'MediaWiki\\Tests\\Site\\SiteTest' => "$testDir/phpunit/includes/site/SiteTest.php",
	'MediaWiki\\Tests\\Site\\TestSites' => "$testDir/phpunit/includes/site/TestSites.php",

	# tests/phpunit/includes/specialpage
	'MediaWiki\\Tests\\SpecialPage\\SpecialPageTestHelper' => "$testDir/phpunit/includes/specialpage/SpecialPageTestHelper.php",
	'MediaWiki\\Tests\\SpecialPage\\AbstractChangesListSpecialPageTestCase' => "$testDir/phpunit/includes/specialpage/AbstractChangesListSpecialPageTestCase.php",
	'MediaWiki\\Tests\\SpecialPage\\FormSpecialPageTestCase' => "$testDir/phpunit/includes/specialpage/FormSpecialPageTestCase.php",

	# tests/phpunit/includes/specials
	'SpecialPageTestBase' => "$testDir/phpunit/includes/specials/SpecialPageTestBase.php",
	'SpecialPageExecutor' => "$testDir/phpunit/includes/specials/SpecialPageExecutor.php",
	'SpecialSearchTestMockResultSet' => "$testDir/phpunit/includes/specials/SpecialSearchTestMockResultSet.php",

	# tests/phpunit/includes/title
	'TitleCodecTestBase' => "$testDir/phpunit/includes/title/TitleCodecTestBase.php",

	# test/phpunit/includes/user
	'UserOptionsLookupTestBase' => "$testDir/phpunit/integration/includes/user/Options/UserOptionsLookupTestBase.php",

	# tests/phpunit/includes/language
	'DummyConverter' => "$testDir/phpunit/mocks/languages/DummyConverter.php",
	'LanguageClassesTestCase' => "$testDir/phpunit/includes/language/LanguageClassesTestCase.php",
	'LanguageConverterTestTrait' => "$testDir/phpunit/includes/language/LanguageConverterTestTrait.php",
	'MessageTest' => "$testDir/phpunit/includes/language/MessageTest.php",

	# tests/phpunit/includes/libs
	'BagOStuffTestBase' => "$testDir/phpunit/includes/libs/objectcache/BagOStuffTestBase.php",
	'Wikimedia\\Tests\\Diff\FakeDiffOp' => "$testDir/phpunit/unit/includes/libs/Diff/FakeDiffOp.php",
	'Wikimedia\\Tests\\ParamValidator\\TypeDef\\TypeDefTestCase' => "$testDir/phpunit/unit/includes/libs/ParamValidator/TypeDef/TypeDefTestCase.php",
	'Wikimedia\\Tests\\ParamValidator\\TypeDef\\TypeDefTestCaseTrait' => "$testDir/phpunit/unit/includes/libs/ParamValidator/TypeDef/TypeDefTestCaseTrait.php",

	# tests/phpunit/includes/ParamValidator
	'MediaWiki\\Tests\\ParamValidator\\TypeDef\\TypeDefIntegrationTestCase' => "$testDir/phpunit/includes/ParamValidator/TypeDef/TypeDefIntegrationTestCase.php",

	# tests/phpunit/unit/includes/ParamValidator
	'MediaWiki\\Tests\\ParamValidator\\TypeDef\\TypeDefUnitTestCase' => "$testDir/phpunit/unit/includes/ParamValidator/TypeDef/TypeDefUnitTestCase.php",

	# tests/phpunit/unit/includes/Search
	'MediaWiki\Tests\Search\SearchUpdateSpyTrait' => "$testDir/phpunit/includes/search/SearchUpdateSpyTrait.php",

	# tests/phpunit/maintenance
	'MediaWiki\\Tests\\Maintenance\\DumpAsserter' => "$testDir/phpunit/maintenance/DumpAsserter.php",
	'MediaWiki\\Tests\\Maintenance\\DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",
	'MediaWiki\\Tests\\Maintenance\\MaintenanceBaseTestCase' => "$testDir/phpunit/maintenance/MaintenanceBaseTestCase.php",
	'MediaWiki\\Tests\\Maintenance\\PageDumpTestDataTrait' => "$testDir/phpunit/maintenance/PageDumpTestDataTrait.php",

	# tests/phpunit/media
	'FakeDimensionFile' => "$testDir/phpunit/includes/media/FakeDimensionFile.php",
	'MediaWikiMediaTestCase' => "$testDir/phpunit/includes/media/MediaWikiMediaTestCase.php",

	# tests/phpunit/mocks
	'DummySessionProvider' => "$testDir/phpunit/mocks/session/DummySessionProvider.php",
	'MediaWiki\\Tests\\Session\\DummySessionBackend' => "$testDir/phpunit/mocks/session/DummySessionBackend.php",
	'MediaWiki\\Tests\\BrokenClass' => "$testDir/phpunit/mocks/BrokenClass.php",
	'MediaWiki\\Tests\\BrokenClass2' => "$testDir/phpunit/mocks/BrokenClass2.php",
	'MediaWiki\\Tests\\BrokenClass3' => "$testDir/phpunit/mocks/BrokenClass3.php",
	'MediaWiki\\Tests\\MockDatabase' => "$testDir/phpunit/mocks/MockDatabase.php",
	'MediaWiki\\Tests\\ExpectCallbackTrait' => "$testDir/phpunit/mocks/ExpectCallbackTrait.php",
	'MediaWiki\\Tests\\MockWikiMapTrait' => "$testDir/phpunit/mocks/MockWikiMapTrait.php",
	'MediaWiki\\Tests\\Unit\\DummyServicesTrait' => "$testDir/phpunit/mocks/DummyServicesTrait.php",
	'MediaWiki\\Tests\\Unit\\FakeQqxMessageLocalizer' => "$testDir/phpunit/mocks/FakeQqxMessageLocalizer.php",
	'MediaWiki\\Tests\\Unit\\MockBlockTrait' => "$testDir/phpunit/mocks/MockBlockTrait.php",
	'MediaWiki\\Tests\\Unit\\MockServiceDependenciesTrait' => "$testDir/phpunit/mocks/MockServiceDependenciesTrait.php",
	'MediaWiki\\Tests\\Language\\MockLocalisationCacheTrait' => "$testDir/phpunit/mocks/languages/MockLocalisationCacheTrait.php",
	'MockBitmapHandler' => "$testDir/phpunit/mocks/media/MockBitmapHandler.php",
	'MockChangesListFilter' => "$testDir/phpunit/mocks/MockChangesListFilter.php",
	'MockChangesListFilterGroup' => "$testDir/phpunit/mocks/MockChangesListFilterGroup.php",
	'MockCompletionSearchEngine' => "$testDir/phpunit/mocks/search/MockCompletionSearchEngine.php",
	'MockDjVuHandler' => "$testDir/phpunit/mocks/media/MockDjVuHandler.php",
	'MockFSFile' => "$testDir/phpunit/mocks/filebackend/MockFSFile.php",
	'MockFileBackend' => "$testDir/phpunit/mocks/filebackend/MockFileBackend.php",
	'MockHttpTrait' => "$testDir/phpunit/mocks/MockHttpTrait.php",
	'MockImageHandler' => "$testDir/phpunit/mocks/media/MockImageHandler.php",
	'MockLocalRepo' => "$testDir/phpunit/mocks/filerepo/MockLocalRepo.php",
	'MockMessageLocalizer' => "$testDir/phpunit/mocks/MockMessageLocalizer.php",
	'MockPoolCounterFailing' => "$testDir/phpunit/mocks/poolcounter/MockPoolCounterFailing.php",
	'MockSearchEngine' => "$testDir/phpunit/mocks/search/MockSearchEngine.php",
	'MockSearchResult' => "$testDir/phpunit/mocks/search/MockSearchResult.php",
	'MockSearchResultSet' => "$testDir/phpunit/mocks/search/MockSearchResultSet.php",
	'MockSvgHandler' => "$testDir/phpunit/mocks/media/MockSvgHandler.php",
	'MockTitleTrait' => "$testDir/phpunit/mocks/MockTitleTrait.php",
	'NullGuzzleClient' => "$testDir/phpunit/mocks/NullGuzzleClient.php",
	'NullHttpRequestFactory' => "$testDir/phpunit/mocks/NullHttpRequestFactory.php",
	'NullMultiHttpClient' => "$testDir/phpunit/mocks/NullMultiHttpClient.php",
	'MediaWiki\Tests\FileRepo\TestRepoTrait' => "$testDir/phpunit/mocks/filerepo/TestRepoTrait.php",
	'MediaWiki\\Tests\\MockEnvironment' => "$testDir/phpunit/mocks/MockEnvironment.php",

	# tests/phpunit/unit/includes
	'Wikimedia\\Reflection\\GhostFieldTestClass' => "$testDir/phpunit/mocks/GhostFieldTestClass.php",
	'Wikimedia\\Tests\\Reflection\\GhostFieldTestClass' => "$testDir/phpunit/mocks/GhostFieldTestClass.php",

	# tests/phpunit/unit/includes/auth
	'MediaWiki\\Tests\\Unit\\Auth\\AuthenticationProviderTestTrait' => "$testDir/phpunit/unit/includes/auth/AuthenticationProviderTestTrait.php",

	# tests/phpunit/unit/includes/CommentFormatter
	'MediaWiki\\Tests\\Unit\\CommentFormatter\\CommentFormatterTestUtils' => "$testDir/phpunit/unit/includes/CommentFormatter/CommentFormatterTestUtils.php",

	# tests/phpunit/unit/includes/editpage/Constraint and tests/phpunit/integration/includes/editpage/Constraint
	'EditConstraintTestTrait' => "$testDir/phpunit/unit/includes/editpage/Constraint/EditConstraintTestTrait.php",

	# tests/phpunit/unit/includes/filebackend
	'FileBackendGroupTestTrait' => "$testDir/phpunit/unit/includes/filebackend/FileBackendGroupTestTrait.php",
	'FileBackendIntegrationTestBase' => "$testDir/phpunit/integration/includes/libs/filebackend/FileBackendIntegrationTestBase.php",

	# tests/phpunit/unit/includes/HookContainer
	'MediaWiki\\Tests\\HookContainer\\HookRunnerTestBase' => "$testDir/phpunit/unit/includes/HookContainer/HookRunnerTestBase.php",

	# tests/phpunit/unit/includes/json
	'MediaWiki\\Tests\\Json\\JsonDeserializableSubClass' => "$testDir/phpunit/mocks/json/JsonDeserializableSubClass.php",
	'MediaWiki\\Tests\\Json\\JsonDeserializableSubClassAlias' => "$testDir/phpunit/mocks/json/JsonDeserializableSubClass.php",
	'MediaWiki\\Tests\\Json\\JsonDeserializableSuperClass' => "$testDir/phpunit/mocks/json/JsonDeserializableSuperClass.php",
	'MediaWiki\\Tests\\Json\\ManagedObject' => "$testDir/phpunit/mocks/json/ManagedObject.php",
	'MediaWiki\\Tests\\Json\\ManagedObjectFactory' => "$testDir/phpunit/mocks/json/ManagedObjectFactory.php",
	'MediaWiki\\Tests\\Json\\SampleContainerObject' => "$testDir/phpunit/mocks/json/SampleContainerObject.php",
	'MediaWiki\\Tests\\Json\\SampleObject' => "$testDir/phpunit/mocks/json/SampleObject.php",
	'MediaWiki\\Tests\\Json\\SampleObjectAlias' => "$testDir/phpunit/mocks/json/SampleObject.php",

	# tests/phpunit/unit/includes/language
	'LanguageCodeTest' => "$testDir/phpunit/unit/includes/language/LanguageCodeTest.php",
	'LanguageFallbackTestTrait' => "$testDir/phpunit/unit/includes/language/LanguageFallbackTestTrait.php",
	'LanguageNameUtilsTestTrait' => "$testDir/phpunit/unit/includes/language/LanguageNameUtilsTestTrait.php",
	'MediaWiki\Tests\Language\LocalizationUpdateSpyTrait' => "$testDir/phpunit/includes/language/LocalizationUpdateSpyTrait.php",

	# tests/phpunit/unit/includes/libs/filebackend/fsfile
	'Wikimedia\\Tests\\FileBackend\\FSFile\\TempFSFileTestTrait' => "$testDir/phpunit/unit/includes/libs/filebackend/fsfile/TempFSFileTestTrait.php",

	# tests/phpunit/unit/includes/libs/filebackend/fsfile
	'MediaWiki\\Tests\\Unit\\Libs\\Rdbms\\AddQuoterMock' => "$testDir/phpunit/unit/includes/libs/rdbms/AddQuoterMock.php",
	'MediaWiki\\Tests\\Unit\\Libs\\Rdbms\\SQLPlatformTestHelper' => "$testDir/phpunit/unit/includes/libs/rdbms/SQLPlatformTestHelper.php",

	# tests/phpunit/unit/includes/libs/Message
	'Wikimedia\\Tests\\Message\\DataMessageValueTest' => "$testDir/phpunit/unit/includes/libs/Message/DataMessageValueTest.php",
	'Wikimedia\\Tests\\Message\\ListParamTest' => "$testDir/phpunit/unit/includes/libs/Message/ListParamTest.php",
	'Wikimedia\\Tests\\Message\\MessageParamTest' => "$testDir/phpunit/unit/includes/libs/Message/MessageParamTest.php",
	'Wikimedia\\Tests\\Message\\MessageSerializationTestTrait' => "$testDir/phpunit/unit/includes/libs/Message/MessageSerializationTestTrait.php",
	'Wikimedia\\Tests\\Message\\MessageValueTest' => "$testDir/phpunit/unit/includes/libs/Message/MessageValueTest.php",
	'Wikimedia\\Tests\\Message\\ScalarParamTest' => "$testDir/phpunit/unit/includes/libs/Message/ScalarParamTest.php",
	'Wikimedia\\Tests\\Message\\T377912TestCase' => "$testDir/phpunit/unit/includes/libs/Message/T377912TestCase.php",

	# tests/phpunit/unit/includes/utils
	'UrlUtilsProviders' => "$testDir/phpunit/unit/includes/utils/UrlUtilsProviders.php",

	# tests/phpunit/unit/includes/password
	'PasswordTestCase' => "$testDir/phpunit/unit/includes/password/PasswordTestCase.php",
	'Pbkdf2PasswordTestCase' => "$testDir/phpunit/unit/includes/password/Pbkdf2PasswordTestCase.php",

	# tests/phpunit/integration/includes
	'MediaWiki\\Tests\\ExtensionJsonTestBase' => "$testDir/phpunit/integration/includes/ExtensionJsonTestBase.php",
	'MediaWiki\\Tests\\ExtensionServicesTestBase' => "$testDir/phpunit/integration/includes/ExtensionServicesTestBase.php",

	# tests/phpunit/integration/includes/edit
	'MediaWiki\\Tests\\Integration\\Edit\\SimpleParsoidOutputStashSerializationTest' => "$testDir/phpunit/integration/includes/edit/SimpleParsoidOutputStashSerializationTest.php",

	# tests/phpunit/integration/includes/HTMLForm
	'MediaWiki\\Tests\\Integration\\HTMLForm\\HTMLFormFieldTestCase' => "$testDir/phpunit/integration/includes/HTMLForm/HTMLFormFieldTestCase.php",

	# tests/phpunit/integration/includes/libs
	'LockManagerIntegrationTestBase' => "$testDir/phpunit/integration/includes/libs/lockmanager/LockManagerIntegrationTestBase.php",

	# tests/phpunit/integration/includes/user
	'MediaWiki\\Tests\\User\\ActorStoreTestBase' => "$testDir/phpunit/integration/includes/user/ActorStoreTestBase.php",

	# tests/phpunit/integration/includes/user/Options
	'MediaWiki\\Tests\\User\\Options\\MockUserOptionsStore' => "$testDir/phpunit/integration/includes/user/Options/MockUserOptionsStore.php",

	# tests/phpunit/integration/includes/user/TempUser
	'MediaWiki\\Tests\\User\\TempUser\\TempUserTestTrait' => "$testDir/phpunit/integration/includes/user/TempUser/TempUserTestTrait.php",

	# tests/phpunit/structure
	'MediaWiki\\Tests\\Structure\\AbstractSchemaTestBase' => "$testDir/phpunit/structure/AbstractSchemaTestBase.php",
	'MediaWiki\\Tests\\Structure\\BundleSizeTestBase' => "$testDir/phpunit/structure/BundleSizeTestBase.php",

	# tests/phpunit/unit/includes/Rest
	'MediaWiki\Tests\Rest\MockHandlerFactory' => "$testDir/phpunit/unit/includes/Rest/MockHandlerFactory.php",
	'MediaWiki\\Tests\\Rest\\RestTestTrait' => "$testDir/phpunit/unit/includes/Rest/RestTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\SessionHelperTestTrait' => "$testDir/phpunit/unit/includes/Rest/SessionHelperTestTrait.php",

	# tests/phpunit/unit/includes/Rest/Handler
	'MediaWiki\\Tests\\Unit\\Permissions\\MockAuthorityTrait' => "$testDir/phpunit/mocks/permissions/MockAuthorityTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\ActionModuleBasedHandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/ActionModuleBasedHandlerTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\HTMLHandlerTestTrait' => "$testDir/phpunit/integration/includes/Rest/Handler/HTMLHandlerTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\HandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/HandlerTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\PageHandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/PageHandlerTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\HelloHandler' => "$testDir/phpunit/unit/includes/Rest/Handler/HelloHandler.php",
	'MediaWiki\\Tests\\Rest\\Handler\\EchoHandler' => "$testDir/phpunit/unit/includes/Rest/Handler/EchoHandler.php",
	'MediaWiki\\Tests\\Rest\\Handler\\MediaTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/MediaTestTrait.php",

	# tests/phpunit/unit/includes/Revision
	'MediaWiki\\Tests\\Unit\\Revision\\RevisionRecordTests' => "$testDir/phpunit/unit/includes/Revision/RevisionRecordTests.php",
	'MediaWiki\\Tests\\Unit\\Revision\\RevisionSlotsTest' => "$testDir/phpunit/unit/includes/Revision/RevisionSlotsTest.php",
	'MediaWiki\\Tests\\Unit\\Revision\\RevisionStoreRecordTest' => "$testDir/phpunit/unit/includes/Revision/RevisionStoreRecordTest.php",

	# tests/phpunit/unit/includes/Settings/Config
	'MediaWiki\\Tests\\Unit\\Settings\\Config\\ConfigSinkTestTrait' => "$testDir/phpunit/unit/includes/Settings/Config/ConfigSinkTestTrait.php",

	# tests/phpunit/unit/includes/Settings/Source
	'MediaWiki\\Tests\\Unit\\Settings\\Source\\ExampleDefinitionsClass' => "$testDir/phpunit/unit/includes/Settings/Source/ExampleDefinitionsClass.php",

	# tests/phpunit/unit/includes/session
	'MediaWiki\\Tests\\Session\\SessionProviderTestTrait' => "$testDir/phpunit/unit/includes/session/SessionProviderTestTrait.php",

	# tests/suites
	'ParserTestFileSuite' => "$testDir/phpunit/suites/ParserTestFileSuite.php",
	'ParsoidTestFileSuite' => "$testDir/phpunit/suites/ParsoidTestFileSuite.php",
	'ParserTestTopLevelSuite' => "$testDir/phpunit/suites/ParserTestTopLevelSuite.php",
	'SuiteEventsTrait' => "$testDir/phpunit/suites/SuiteEventsTrait.php",
];
// phpcs:enable
