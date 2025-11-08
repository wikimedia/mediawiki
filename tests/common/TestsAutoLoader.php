<?php

/**
 * AutoLoader for the testing suite.
 *
 * @license GPL-2.0-or-later
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

	# tests/common/Parser
	'DbTestPreviewer' => "$testDir/common/Parser/DbTestPreviewer.php",
	'DbTestRecorder' => "$testDir/common/Parser/DbTestRecorder.php",
	'DjVuSupport' => "$testDir/common/Parser/DjVuSupport.php",
	'MediaWiki\\Tests\\AnsiTermColorer' => "$testDir/common/Parser/AnsiTermColorer.php",
	'MediaWiki\\Tests\\DummyTermColorer' => "$testDir/common/Parser/DummyTermColorer.php",
	'MultiTestRecorder' => "$testDir/common/Parser/MultiTestRecorder.php",
	'ParserTestMockParser' => "$testDir/common/Parser/ParserTestMockParser.php",
	'ParserTestParserHook' => "$testDir/common/Parser/ParserTestParserHook.php",
	'ParserTestPrinter' => "$testDir/common/Parser/ParserTestPrinter.php",
	'ParserTestResult' => "$testDir/common/Parser/ParserTestResult.php",
	'ParserTestResultNormalizer' => "$testDir/common/Parser/ParserTestResultNormalizer.php",
	'ParserTestRunner' => "$testDir/common/Parser/ParserTestRunner.php",
	'PhpunitTestRecorder' => "$testDir/common/Parser/PhpunitTestRecorder.php",
	'TestFileEditor' => "$testDir/common/Parser/TestFileEditor.php",
	'TestRecorder' => "$testDir/common/Parser/TestRecorder.php",

	# tests/phpunit
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

	# tests/phpunit/includes/Api
	'ApiQueryTestBase' => "$testDir/phpunit/includes/Api/Query/ApiQueryTestBase.php",
	'ApiTestCase' => "$testDir/phpunit/includes/Api/ApiTestCase.php",
	'ApiTestContext' => "$testDir/phpunit/includes/Api/ApiTestContext.php",
	'ApiUploadTestCase' => "$testDir/phpunit/includes/Api/ApiUploadTestCase.php",
	'RandomImageGenerator' => "$testDir/phpunit/includes/Api/RandomImageGenerator.php",
	'MediaWiki\\Tests\\Api\\Format\\ApiFormatTestBase' => "$testDir/phpunit/includes/Api/Format/ApiFormatTestBase.php",
	'MediaWiki\\Tests\\Api\\Query\\ApiQueryTestBase' => "$testDir/phpunit/includes/Api/Query/ApiQueryTestBase.php",
	'MediaWiki\\Tests\\Api\\Query\\ApiQueryContinueTestBase' => "$testDir/phpunit/includes/Api/Query/ApiQueryContinueTestBase.php",
	'MediaWiki\\Tests\\Api\\ApiTestCase' => "$testDir/phpunit/includes/Api/ApiTestCase.php",
	'MediaWiki\\Tests\\Api\\ApiTestContext' => "$testDir/phpunit/includes/Api/ApiTestContext.php",
	'MediaWiki\\Tests\\Api\\ApiUploadTestCase' => "$testDir/phpunit/includes/Api/ApiUploadTestCase.php",
	'MediaWiki\\Tests\\Api\\MockApi' => "$testDir/phpunit/includes/Api/MockApi.php",
	'MediaWiki\\Tests\\Api\\MockApiQueryBase' => "$testDir/phpunit/includes/Api/MockApiQueryBase.php",
	'MediaWiki\\Tests\\Api\\RandomImageGenerator' => "$testDir/phpunit/includes/Api/RandomImageGenerator.php",

	# tests/phpunit/includes/Auth
	'MediaWiki\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/Auth/AuthenticationRequestTestCase.php",
	'MediaWiki\\Tests\\Auth\\AuthenticationRequestTestCase' =>
		"$testDir/phpunit/includes/Auth/AuthenticationRequestTestCase.php",

	# tests/phpunit/includes/Block
	'MediaWiki\\Tests\\Block\\Restriction\\RestrictionTestCase' => "$testDir/phpunit/includes/Block/Restriction/RestrictionTestCase.php",

	# tests/phpunit/includes/RecentChanges
	'TestRecentChangesHelper' => "$testDir/phpunit/includes/RecentChanges/TestRecentChangesHelper.php",
	'MediaWiki\Tests\Recentchanges\ChangeTrackingUpdateSpyTrait' => "$testDir/phpunit/includes/RecentChanges/ChangeTrackingUpdateSpyTrait.php",

	# tests/phpunit/includes/Config
	'TestAllServiceOptionsUsed' => "$testDir/phpunit/includes/Config/TestAllServiceOptionsUsed.php",
	'LoggedServiceOptions' => "$testDir/phpunit/includes/Config/LoggedServiceOptions.php",

	# tests/phpunit/includes/Content
	'DummyNonTextContent' => "$testDir/phpunit/mocks/Content/DummyNonTextContent.php",
	'DummyContentForTesting' => "$testDir/phpunit/mocks/Content/DummyContentForTesting.php",
	'MediaWiki\\Tests\\Content\\CssContentTest' => "$testDir/phpunit/includes/Content/CssContentTest.php",
	'MediaWiki\\Tests\\Mocks\\Content\\DummyContentHandlerForTesting' =>
		"$testDir/phpunit/mocks/Content/DummyContentHandlerForTesting.php",
	'MediaWiki\\Tests\\Mocks\\Content\\DummyContentForTesting' => "$testDir/phpunit/mocks/Content/DummyContentForTesting.php",
	'MediaWiki\\Tests\\Mocks\\Content\\DummyNonTextContentHandler' => "$testDir/phpunit/mocks/Content/DummyNonTextContentHandler.php",
	'MediaWiki\\Tests\\Mocks\\Content\\DummyNonTextContent' => "$testDir/phpunit/mocks/Content/DummyNonTextContent.php",
	'MediaWiki\\Tests\\Mocks\\Content\\DummySerializeErrorContentHandler' =>
		"$testDir/phpunit/mocks/Content/DummySerializeErrorContentHandler.php",
	'MediaWiki\\Tests\\Content\\JavaScriptContentTest' => "$testDir/phpunit/includes/Content/JavaScriptContentTest.php",
	'MediaWiki\\Tests\\Content\\TextContentTest' => "$testDir/phpunit/includes/Content/TextContentTest.php",
	'MediaWiki\\Tests\\Content\\TextContentHandlerIntegrationTest' => "$testDir/phpunit/includes/Content/TextContentHandlerIntegrationTest.php",
	'MediaWiki\\Tests\\Content\\WikitextContentTest' => "$testDir/phpunit/includes/Content/WikitextContentTest.php",
	'MediaWiki\\Tests\\Content\\JavaScriptContentHandlerTest' => "$testDir/phpunit/includes/Content/JavaScriptContentHandlerTest.php",
	'MediaWiki\\Tests\\Content\\ContentSerializationTestTrait' => "$testDir/phpunit/includes/Content/ContentSerializationTestTrait.php",

	# tests/phpunit/includes/DB
	'DatabaseTestHelper' => "$testDir/phpunit/includes/DB/DatabaseTestHelper.php",

	# tests/phpunit/includes/Debug
	'TestDeprecatedClass' => "$testDir/phpunit/includes/Debug/TestDeprecatedClass.php",
	'TestDeprecatedSubclass' => "$testDir/phpunit/includes/Debug/TestDeprecatedSubclass.php",

	# tests/phpunit/includes/Diff
	'CustomDifferenceEngine' => "$testDir/phpunit/includes/Diff/CustomDifferenceEngine.php",
	'MediaWiki\\Tests\\Diff\\TextDiffer\\TextDifferData' => "$testDir/phpunit/includes/Diff/TextDiffer/TextDifferData.php",

	# tests/phpunit/includes/ExternalStore
	'ExternalStoreForTesting' => "$testDir/phpunit/includes/ExternalStore/ExternalStoreForTesting.php",
	'MediaWiki\\Tests\\ExternalStore\\ExternalStoreForTesting' => "$testDir/phpunit/includes/ExternalStore/ExternalStoreForTesting.php",

	# tests/phpunit/includes/Logging
	'LogFormatterTestCase' => "$testDir/phpunit/includes/Logging/LogFormatterTestCase.php",
	'MediaWiki\\Tests\\Logging\\LogFormatterTestCase' => "$testDir/phpunit/includes/Logging/LogFormatterTestCase.php",

	# tests/phpunit/includes/OutputTransform
	'MediaWiki\\Tests\\OutputTransform\\DummyDOMTransformStage' => "$testDir/phpunit/includes/OutputTransform/DummyDOMTransformStage.php",
	'MediaWiki\\Tests\\OutputTransform\\TestUtils' => "$testDir/phpunit/includes/OutputTransform/TestUtils.php",
	'MediaWiki\\Tests\\OutputTransform\\OutputTransformStageTestBase' => "$testDir/phpunit/includes/OutputTransform/OutputTransformStageTestBase.php",
	'MediaWiki\\Tests\\OutputTransform\\Stages\\HandleTOCMarkersTestCommon' => "$testDir/phpunit/includes/OutputTransform/Stages/HandleTOCMarkersTestCommon.php",

	# tests/phpunit/includes/Page
	'LinkCacheTestTrait' => "$testDir/phpunit/includes/Page/LinkCacheTestTrait.php",

	# tests/phpunit/includes/Parser
	'MediaWiki\\Tests\\Parser\\CacheTimeTest' => "$testDir/phpunit/includes/Parser/CacheTimeTest.php",
	'MediaWiki\\Tests\\Parser\\ParserOutputTest' => "$testDir/phpunit/includes/Parser/ParserOutputTest.php",
	'ParserIntegrationTest' => "$testDir/phpunit/suites/ParserIntegrationTest.php",
	'MediaWiki\\Tests\\Parser\\ParserCacheSerializationTestCases' =>
		"$testDir/phpunit/includes/Parser/ParserCacheSerializationTestCases.php",
	'Wikimedia\\Tests\\SerializationTestTrait' =>
		"$testDir/phpunit/includes/libs/Serialization/SerializationTestTrait.php",
	'Wikimedia\\Tests\\SerializationTestUtils' =>
		"$testDir/phpunit/includes/libs/Serialization/SerializationTestUtils.php",

	# tests/phpunit/includes/ResourceLoader
	'MediaWiki\\Tests\\ResourceLoader\\ImageModuleTest' =>
		"$testDir/phpunit/includes/ResourceLoader/ImageModuleTest.php",
	'MediaWiki\\Tests\\ResourceLoader\\ImageModuleTestable' =>
		"$testDir/phpunit/includes/ResourceLoader/ImageModuleTest.php",
	'MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait' =>
		"$testDir/phpunit/includes/ResourceLoader/ResourceLoaderUpdateSpyTrait.php",

	# tests/phpunit/includes/Session
	'MediaWiki\\Session\\TestBagOStuff' => "$testDir/phpunit/includes/Session/TestBagOStuff.php",
	'MediaWiki\\Tests\\Session\\TestBagOStuff' => "$testDir/phpunit/includes/Session/TestBagOStuff.php",
	'MediaWiki\\Tests\\Session\\TestUtils' => "$testDir/phpunit/includes/Session/TestUtils.php",

	# tests/phpunit/includes/Site
	'TestSites' => "$testDir/phpunit/includes/Site/TestSites.php",
	'MediaWiki\\Tests\\Site\\SiteTest' => "$testDir/phpunit/includes/Site/SiteTest.php",
	'MediaWiki\\Tests\\Site\\TestSites' => "$testDir/phpunit/includes/Site/TestSites.php",

	# tests/phpunit/includes/SpecialPage
	'MediaWiki\\Tests\\SpecialPage\\SpecialPageTestHelper' => "$testDir/phpunit/includes/SpecialPage/SpecialPageTestHelper.php",
	'MediaWiki\\Tests\\SpecialPage\\AbstractChangesListSpecialPageTestCase' => "$testDir/phpunit/includes/SpecialPage/AbstractChangesListSpecialPageTestCase.php",
	'MediaWiki\\Tests\\SpecialPage\\FormSpecialPageTestCase' => "$testDir/phpunit/includes/SpecialPage/FormSpecialPageTestCase.php",

	# tests/phpunit/includes/Specials
	'SpecialPageTestBase' => "$testDir/phpunit/includes/Specials/SpecialPageTestBase.php",
	'MediaWiki\\Tests\\Specials\\SpecialPageTestBase' => "$testDir/phpunit/includes/Specials/SpecialPageTestBase.php",
	'SpecialPageExecutor' => "$testDir/phpunit/includes/Specials/SpecialPageExecutor.php",
	'MediaWiki\\Tests\\Specials\\SpecialPageExecutor' => "$testDir/phpunit/includes/Specials/SpecialPageExecutor.php",
	'MediaWiki\\Tests\\Specials\\SpecialSearchTestMockResultSet' => "$testDir/phpunit/includes/Specials/SpecialSearchTestMockResultSet.php",

	# tests/phpunit/includes/Storage
	'MediaWiki\\Tests\\Storage\\PageEditStashContentsTest' => "$testDir/phpunit/includes/Storage/PageEditStashContentsTest.php",
	'MediaWiki\\Tests\\Storage\\TestMutableRevisionRecord' => "$testDir/phpunit/unit/includes/Storage/TestMutableRevisionRecord.php",

	# tests/phpunit/includes/Title
	'TitleCodecTestBase' => "$testDir/phpunit/includes/Title/TitleCodecTestBase.php",

	# test/phpunit/includes/User
	'UserOptionsLookupTestBase' => "$testDir/phpunit/integration/includes/User/Options/UserOptionsLookupTestBase.php",

	# tests/phpunit/includes/Language
	'MediaWiki\\Tests\\Mocks\\Language\\DummyConverter' => "$testDir/phpunit/mocks/Language/DummyConverter.php",
	'MediaWiki\\Tests\\Language\\LanguageClassesTestCase' => "$testDir/phpunit/includes/Language/LanguageClassesTestCase.php",
	'MediaWiki\\Tests\\Language\\LanguageConverterTestTrait' => "$testDir/phpunit/includes/Language/LanguageConverterTestTrait.php",
	'MediaWiki\\Tests\\Language\\LocalizationUpdateSpyTrait' => "$testDir/phpunit/includes/Language/LocalizationUpdateSpyTrait.php",
	'MediaWiki\\Tests\\Language\\MessageTest' => "$testDir/phpunit/includes/Language/MessageTest.php",

	# tests/phpunit/includes/libs
	'BagOStuffTestBase' => "$testDir/phpunit/includes/libs/ObjectCache/BagOStuffTestBase.php",
	'Wikimedia\\Tests\\Diff\FakeDiffOp' => "$testDir/phpunit/unit/includes/libs/Diff/FakeDiffOp.php",
	'Wikimedia\\Tests\\ParamValidator\\TypeDef\\TypeDefTestCase' => "$testDir/phpunit/unit/includes/libs/ParamValidator/TypeDef/TypeDefTestCase.php",
	'Wikimedia\\Tests\\ParamValidator\\TypeDef\\TypeDefTestCaseTrait' => "$testDir/phpunit/unit/includes/libs/ParamValidator/TypeDef/TypeDefTestCaseTrait.php",

	# tests/phpunit/includes/ParamValidator
	'MediaWiki\\Tests\\ParamValidator\\TypeDef\\TypeDefIntegrationTestCase' => "$testDir/phpunit/includes/ParamValidator/TypeDef/TypeDefIntegrationTestCase.php",

	# tests/phpunit/unit/includes/ParamValidator
	'MediaWiki\\Tests\\ParamValidator\\TypeDef\\TypeDefUnitTestCase' => "$testDir/phpunit/unit/includes/ParamValidator/TypeDef/TypeDefUnitTestCase.php",

	# tests/phpunit/unit/includes/Search
	'MediaWiki\Tests\Search\SearchUpdateSpyTrait' => "$testDir/phpunit/includes/Search/SearchUpdateSpyTrait.php",

	# tests/phpunit/maintenance
	'MediaWiki\\Tests\\Maintenance\\DumpAsserter' => "$testDir/phpunit/maintenance/DumpAsserter.php",
	'MediaWiki\\Tests\\Maintenance\\DumpTestCase' => "$testDir/phpunit/maintenance/DumpTestCase.php",
	'MediaWiki\\Tests\\Maintenance\\MaintenanceBaseTestCase' => "$testDir/phpunit/maintenance/MaintenanceBaseTestCase.php",
	'MediaWiki\\Tests\\Maintenance\\PageDumpTestDataTrait' => "$testDir/phpunit/maintenance/PageDumpTestDataTrait.php",

	# tests/phpunit/includes/Media
	'FakeDimensionFile' => "$testDir/phpunit/includes/Media/FakeDimensionFile.php",
	'MediaWikiMediaTestCase' => "$testDir/phpunit/includes/Media/MediaWikiMediaTestCase.php",

	# tests/phpunit/mocks
	'DummySessionProvider' => "$testDir/phpunit/mocks/Session/DummySessionProvider.php",
	'MediaWiki\\Tests\\Session\\DummySessionBackend' => "$testDir/phpunit/mocks/Session/DummySessionBackend.php",
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
	'MediaWiki\\Tests\\Mocks\\Language\\MockLocalisationCacheTrait' => "$testDir/phpunit/mocks/Language/MockLocalisationCacheTrait.php",
	'MockBitmapHandler' => "$testDir/phpunit/mocks/Media/MockBitmapHandler.php",
	'MockChangesListFilter' => "$testDir/phpunit/mocks/MockChangesListFilter.php",
	'MockChangesListFilterGroup' => "$testDir/phpunit/mocks/MockChangesListFilterGroup.php",
	'MockCompletionSearchEngine' => "$testDir/phpunit/mocks/Search/MockCompletionSearchEngine.php",
	'MockDjVuHandler' => "$testDir/phpunit/mocks/Media/MockDjVuHandler.php",
	'MockFSFile' => "$testDir/phpunit/mocks/FileBackend/MockFSFile.php",
	'MockFileBackend' => "$testDir/phpunit/mocks/FileBackend/MockFileBackend.php",
	'MockHttpTrait' => "$testDir/phpunit/mocks/MockHttpTrait.php",
	'MockImageHandler' => "$testDir/phpunit/mocks/Media/MockImageHandler.php",
	'MockLocalRepo' => "$testDir/phpunit/mocks/FileRepo/MockLocalRepo.php",
	'MockMessageLocalizer' => "$testDir/phpunit/mocks/MockMessageLocalizer.php",
	'MediaWiki\\Tests\\Mocks\\PoolCounter\\MockPoolCounterFailing' => "$testDir/phpunit/mocks/PoolCounter/MockPoolCounterFailing.php",
	'MockSearchEngine' => "$testDir/phpunit/mocks/Search/MockSearchEngine.php",
	'MockSearchResult' => "$testDir/phpunit/mocks/Search/MockSearchResult.php",
	'MockSearchResultSet' => "$testDir/phpunit/mocks/Search/MockSearchResultSet.php",
	'MockSvgHandler' => "$testDir/phpunit/mocks/Media/MockSvgHandler.php",
	'MockTitleTrait' => "$testDir/phpunit/mocks/MockTitleTrait.php",
	'NullGuzzleClient' => "$testDir/phpunit/mocks/NullGuzzleClient.php",
	'NullHttpRequestFactory' => "$testDir/phpunit/mocks/NullHttpRequestFactory.php",
	'NullMultiHttpClient' => "$testDir/phpunit/mocks/NullMultiHttpClient.php",
	'MediaWiki\Tests\FileRepo\TestRepoTrait' => "$testDir/phpunit/mocks/FileRepo/TestRepoTrait.php",
	'MediaWiki\\Tests\\MockEnvironment' => "$testDir/phpunit/mocks/MockEnvironment.php",

	# tests/phpunit/unit/includes/Auth
	'MediaWiki\\Tests\\Unit\\Auth\\AuthenticationProviderTestTrait' => "$testDir/phpunit/unit/includes/Auth/AuthenticationProviderTestTrait.php",

	# tests/phpunit/unit/includes/CommentFormatter
	'MediaWiki\\Tests\\Unit\\CommentFormatter\\CommentFormatterTestUtils' => "$testDir/phpunit/unit/includes/CommentFormatter/CommentFormatterTestUtils.php",

	# tests/phpunit/unit/includes/EditPage/Constraint and tests/phpunit/integration/includes/EditPage/Constraint
	'EditConstraintTestTrait' => "$testDir/phpunit/unit/includes/EditPage/Constraint/EditConstraintTestTrait.php",

	# tests/phpunit/unit/includes/FileBackend
	'FileBackendGroupTestTrait' => "$testDir/phpunit/unit/includes/FileBackend/FileBackendGroupTestTrait.php",
	'FileBackendIntegrationTestBase' => "$testDir/phpunit/integration/includes/libs/FileBackend/FileBackendIntegrationTestBase.php",

	# tests/phpunit/unit/includes/HookContainer
	'MediaWiki\\Tests\\HookContainer\\HookRunnerTestBase' => "$testDir/phpunit/unit/includes/HookContainer/HookRunnerTestBase.php",

	# tests/phpunit/unit/includes/Json
	'MediaWiki\\Tests\\Json\\JsonDeserializableSubClass' => "$testDir/phpunit/mocks/Json/JsonDeserializableSubClass.php",
	'MediaWiki\\Tests\\Mocks\\Json\\JsonDeserializableSubClass' => "$testDir/phpunit/mocks/Json/JsonDeserializableSubClass.php",
	'MediaWiki\\Tests\\Mocks\\Json\\JsonDeserializableSubClassAlias' => "$testDir/phpunit/mocks/Json/JsonDeserializableSubClass.php",
	'MediaWiki\\Tests\\Mocks\\Json\\JsonDeserializableSuperClass' => "$testDir/phpunit/mocks/Json/JsonDeserializableSuperClass.php",
	'MediaWiki\\Tests\\Mocks\\Json\\ManagedObject' => "$testDir/phpunit/mocks/Json/ManagedObject.php",
	'MediaWiki\\Tests\\Mocks\\Json\\ManagedObjectFactory' => "$testDir/phpunit/mocks/Json/ManagedObjectFactory.php",
	'MediaWiki\\Tests\\Mocks\\Json\\PlainJsonJwtCodec' => "$testDir/phpunit/mocks/Json/PlainJsonJwtCodec.php",
	'MediaWiki\\Tests\\Mocks\\Json\\SampleContainerObject' => "$testDir/phpunit/mocks/Json/SampleContainerObject.php",
	'MediaWiki\\Tests\\Mocks\\Json\\SampleObject' => "$testDir/phpunit/mocks/Json/SampleObject.php",
	'MediaWiki\\Tests\\Mocks\\Json\\SampleObjectAlias' => "$testDir/phpunit/mocks/Json/SampleObject.php",

	# tests/phpunit/unit/includes/Language
	'MediaWiki\\Tests\\Unit\\Language\\LanguageCodeTest' => "$testDir/phpunit/unit/includes/Language/LanguageCodeTest.php",
	'MediaWiki\\Tests\\Unit\\Language\\LanguageFallbackTestTrait' => "$testDir/phpunit/unit/includes/Language/LanguageFallbackTestTrait.php",
	'MediaWiki\\Tests\\Unit\\Language\\LanguageNameUtilsTestTrait' => "$testDir/phpunit/unit/includes/Language/LanguageNameUtilsTestTrait.php",

	# tests/phpunit/unit/includes/libs/FileBackend/FSFile
	'Wikimedia\\Tests\\FileBackend\\FSFile\\TempFSFileTestTrait' => "$testDir/phpunit/unit/includes/libs/FileBackend/FSFile/TempFSFileTestTrait.php",

	# tests/phpunit/unit/includes/libs/Rdbms
	'MediaWiki\\Tests\\Unit\\Libs\\Rdbms\\AddQuoterMock' => "$testDir/phpunit/unit/includes/libs/Rdbms/AddQuoterMock.php",
	'MediaWiki\\Tests\\Unit\\Libs\\Rdbms\\SQLPlatformTestHelper' => "$testDir/phpunit/unit/includes/libs/Rdbms/SQLPlatformTestHelper.php",

	# tests/phpunit/unit/includes/libs/Message
	'Wikimedia\\Tests\\Message\\DataMessageValueTest' => "$testDir/phpunit/unit/includes/libs/Message/DataMessageValueTest.php",
	'Wikimedia\\Tests\\Message\\ListParamTest' => "$testDir/phpunit/unit/includes/libs/Message/ListParamTest.php",
	'Wikimedia\\Tests\\Message\\MessageParamTest' => "$testDir/phpunit/unit/includes/libs/Message/MessageParamTest.php",
	'Wikimedia\\Tests\\Message\\MessageSerializationTestTrait' => "$testDir/phpunit/unit/includes/libs/Message/MessageSerializationTestTrait.php",
	'Wikimedia\\Tests\\Message\\MessageValueTest' => "$testDir/phpunit/unit/includes/libs/Message/MessageValueTest.php",
	'Wikimedia\\Tests\\Message\\ScalarParamTest' => "$testDir/phpunit/unit/includes/libs/Message/ScalarParamTest.php",
	'Wikimedia\\Tests\\Message\\T377912TestCase' => "$testDir/phpunit/unit/includes/libs/Message/T377912TestCase.php",

	# tests/phpunit/unit/includes/Utils
	'MediaWiki\\Tests\\Unit\\Utils\\UrlUtilsProviders' => "$testDir/phpunit/unit/includes/Utils/UrlUtilsProviders.php",

	# tests/phpunit/unit/includes/Password
	'MediaWiki\\Tests\\Unit\\Password\\PasswordTestCase' => "$testDir/phpunit/unit/includes/Password/PasswordTestCase.php",
	'MediaWiki\\Tests\\Unit\\Password\\Pbkdf2PasswordTestCase' => "$testDir/phpunit/unit/includes/Password/Pbkdf2PasswordTestCase.php",

	# tests/phpunit/integration/includes
	'MediaWiki\\Tests\\ExtensionJsonTestBase' => "$testDir/phpunit/integration/includes/ExtensionJsonTestBase.php",
	'MediaWiki\\Tests\\ExtensionServicesTestBase' => "$testDir/phpunit/integration/includes/ExtensionServicesTestBase.php",

	# tests/phpunit/integration/includes/Edit
	'MediaWiki\\Tests\\Integration\\Edit\\SimpleParsoidOutputStashSerializationTest' => "$testDir/phpunit/integration/includes/Edit/SimpleParsoidOutputStashSerializationTest.php",

	# tests/phpunit/integration/includes/HTMLForm
	'MediaWiki\\Tests\\Integration\\HTMLForm\\HTMLFormFieldTestCase' => "$testDir/phpunit/integration/includes/HTMLForm/HTMLFormFieldTestCase.php",

	# tests/phpunit/integration/includes/libs
	'LockManagerIntegrationTestBase' => "$testDir/phpunit/integration/includes/libs/LockManager/LockManagerIntegrationTestBase.php",

	# tests/phpunit/integration/includes/Rest/Handler
	'MediaWiki\\Tests\\Rest\\Handler\\HandlerIntegrationTestTrait' => "$testDir/phpunit/integration/includes/Rest/Handler/HandlerIntegrationTestTrait.php",

	# tests/phpunit/integration/includes/User
	'MediaWiki\\Tests\\User\\ActorStoreTestBase' => "$testDir/phpunit/integration/includes/User/ActorStoreTestBase.php",

	# tests/phpunit/integration/includes/User/Options
	'MediaWiki\\Tests\\User\\Options\\MockUserOptionsStore' => "$testDir/phpunit/integration/includes/User/Options/MockUserOptionsStore.php",

	# tests/phpunit/integration/includes/User/TempUser
	'MediaWiki\\Tests\\User\\TempUser\\TempUserTestTrait' => "$testDir/phpunit/integration/includes/User/TempUser/TempUserTestTrait.php",

	# tests/phpunit/structure
	'MediaWiki\\Tests\\Structure\\AbstractSchemaTestBase' => "$testDir/phpunit/structure/AbstractSchemaTestBase.php",
	'MediaWiki\\Tests\\Structure\\BundleSizeTestBase' => "$testDir/phpunit/structure/BundleSizeTestBase.php",

	# tests/phpunit/unit/includes/Rest
	'MediaWiki\Tests\Rest\MockHandlerFactory' => "$testDir/phpunit/unit/includes/Rest/MockHandlerFactory.php",
	'MediaWiki\\Tests\\Rest\\RestTestTrait' => "$testDir/phpunit/unit/includes/Rest/RestTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\SessionHelperTestTrait' => "$testDir/phpunit/unit/includes/Rest/SessionHelperTestTrait.php",

	# tests/phpunit/unit/includes/Rest/Handler
	'MediaWiki\\Tests\\Unit\\Permissions\\MockAuthorityTrait' => "$testDir/phpunit/mocks/Permissions/MockAuthorityTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\ActionModuleBasedHandlerTestTrait' => "$testDir/phpunit/unit/includes/Rest/Handler/ActionModuleBasedHandlerTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\HTMLHandlerTestTrait' => "$testDir/phpunit/integration/includes/Rest/Handler/HTMLHandlerTestTrait.php",
	'MediaWiki\\Tests\\Rest\\Handler\\LintHandlerTestTrait' => "$testDir/phpunit/integration/includes/Rest/Handler/LintHandlerTestTrait.php",
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

	# tests/phpunit/unit/includes/Session
	'MediaWiki\\Tests\\Session\\SessionProviderTestTrait' => "$testDir/phpunit/unit/includes/Session/SessionProviderTestTrait.php",
	'MediaWiki\\Tests\\Session\\SessionStoreTestTrait' => "$testDir/phpunit/unit/includes/Session/SessionStoreTestTrait.php",

	# tests/suites
	'ParserTestFileSuite' => "$testDir/phpunit/suites/ParserTestFileSuite.php",
	'ParsoidTestFileSuite' => "$testDir/phpunit/suites/ParsoidTestFileSuite.php",
	'ParserTestTopLevelSuite' => "$testDir/phpunit/suites/ParserTestTopLevelSuite.php",
	'SuiteEventsTrait' => "$testDir/phpunit/suites/SuiteEventsTrait.php",
];
