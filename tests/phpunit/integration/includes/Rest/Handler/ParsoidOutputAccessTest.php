<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\JsonCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\SlotRecord;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;

/**
 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess
 * @group Database
 */
class ParsoidOutputAccessTest extends MediaWikiIntegrationTestCase {
	private const WIKITEXT = 'Hello \'\'\'Parsoid\'\'\'!';
	private const MOCKED_HTML = 'mocked HTML';
	private const ENV_OPTS = [ 'pageBundle' => true ];

	/**
	 * @param int $expectedCalls
	 *
	 * @return MockObject|Parsoid
	 */
	private function newMockParsoid( $expectedCalls = 1 ) {
		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->exactly( $expectedCalls ) )->method( 'wikitext2html' )->willReturnCallback(
			static function ( PageConfig $pageConfig ) {
				$wikitext = $pageConfig->getRevisionContent()->getContent( SlotRecord::MAIN );

				return new PageBundle(
					self::MOCKED_HTML . ' of ' . $wikitext,
					[ 'parsoid-data' ],
					[ 'mw-data' ],
					Parsoid::defaultHTMLVersion(),
					[ 'content-language' => 'en' ],
					$pageConfig->getContentModel()
				);
			}
		);

		return $parsoid;
	}

	/**
	 * @param int $expectedParses
	 * @param array $parsoidCacheConfig
	 *
	 * @return ParsoidOutputAccess
	 * @throws Exception
	 */
	private function getParsoidOutputAccessWithCache( $expectedParses, $parsoidCacheConfig = [] ) {
		$stats = new NullStatsdDataFactory();
		$services = $this->getServiceContainer();

		$parsoidCacheConfig += [
			'CacheThresholdTime' => 0,
		];

		$parserCacheFactoryOptions = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, [
			'CacheEpoch' => '20200202112233',
			'OldRevisionParserCacheExpireTime' => 60 * 60,
		] );

		$parserCacheFactory = new ParserCacheFactory(
			new HashBagOStuff(),
			new WANObjectCache( [ 'cache' => new HashBagOStuff(), ] ),
			$this->createHookContainer(),
			new JsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$parserCacheFactoryOptions,
			$services->getTitleFactory(),
			$services->getWikiPageFactory()
		);

		return new ParsoidOutputAccess(
			new ServiceOptions(
				ParsoidOutputAccess::CONSTRUCTOR_OPTIONS,
				[
					'ParsoidCacheConfig' => $parsoidCacheConfig,
					'ParsoidSettings' => MainConfigSchema::getDefaultValue(
						MainConfigNames::ParsoidSettings
					),
					'ParsoidWikiID' => 'MyWiki'
				]
			),
			$parserCacheFactory,
			$services->getPageStore(),
			$services->getRevisionLookup(),
			$services->getGlobalIdGenerator(),
			$stats,
			$this->newMockParsoid( $expectedParses ),
			$services->getParsoidSiteConfig(),
			$services->getParsoidPageConfigFactory()
		);
	}

	/**
	 * @return ParserOptions
	 */
	private function getParserOptions() {
		return ParserOptions::newFromAnon();
	}

	private function getHtml( $value ) {
		if ( $value instanceof StatusValue ) {
			$value = $value->getValue();
		}

		if ( $value instanceof ParserOutput ) {
			$value = $value->getRawText();
		}

		$html = preg_replace( '/<!--.*?-->/s', '', $value );
		$html = trim( preg_replace( '/[\r\n]{2,}/s', "\n", $html ) );
		$html = trim( preg_replace( '/\s{2,}/s', ' ', $html ) );
		return $html;
	}

	private function assertContainsHtml( $needle, $actual, $msg = '' ) {
		$this->assertNotNull( $actual );

		if ( $actual instanceof StatusValue ) {
			$this->assertStatusOK( $actual, 'isOK' );
		}

		$this->assertStringContainsString( $needle, $this->getHtml( $actual ), $msg );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParserOutput
	 */
	public function testGetParserOutputThrowsIfRevisionNotFound() {
		$access = $this->getParsoidOutputAccessWithCache( 0 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );

		$this->expectException( RevisionAccessException::class );
		$access->getParserOutput( $page, $parserOptions );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParserOutput
	 */
	public function testGetParserOutputThrowsIfNotWikitext() {
		$this->markTestSkipped( 'Broken by fix for T324711. Restore once we have T311728.' );

		$access = $this->getParsoidOutputAccessWithCache( 0 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$updater = $page->newPageUpdater( $this->getTestUser()->getUserIdentity() );
		$updater->setContent( SlotRecord::MAIN, new JavaScriptContent( '{}' ) );
		$updater->saveRevision( CommentStoreComment::newUnsavedComment( 'testing' ) );

		$this->expectException( UnexpectedValueException::class );
		$access->getParserOutput( $page, $parserOptions );
	}

	/**
	 * Tests that getParserOutput() will return output.
	 *
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParserOutput
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParsoidRenderID
	 */
	public function testGetParserOutput() {
		$access = $this->getParsoidOutputAccessWithCache( 1 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, self::WIKITEXT );

		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		$output = $status->getValue();

		// check that getParsoidRenderID() doesn't throw
		$this->assertNotNull( $access->getParsoidRenderID( $output ) );

		// Ensure that we can still create a valid instance of PageBundle from the ParserOutput
		$pageBundle = PageBundleParserOutputConverter::pageBundleFromParserOutput( $output );
		$this->assertSame( $output->getRawText(), $pageBundle->html );

		// Ensure that the expected mw and parsoid fields are set in the PageBundle
		$this->assertNotEmpty( $pageBundle->mw );
		$this->assertNotEmpty( $pageBundle->parsoid );
		$this->assertNotEmpty( $pageBundle->headers );
		$this->assertNotEmpty( $pageBundle->version );
	}

	/**
	 * Tests that getParserOutput() will place the generated output for the latest revision
	 * in the parsoid parser cache.
	 *
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParserOutput
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getCachedParserOutputInternal
	 */
	public function testLatestRevisionIsCached() {
		$access = $this->getParsoidOutputAccessWithCache( 1 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, self::WIKITEXT );

		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput from cache
		$status = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput from cache, without supplying a PageRecord
		$status = $access->getCachedParserOutput( $page->getTitle(), $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput again, this should not trigger a new parse.
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );
	}

	/**
	 * Tests that getParserOutput() will force a parse since we know that
	 * the revision is not in the cache.
	 *
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParserOutput
	 */
	public function testLatestRevisionWithForceParse() {
		$access = $this->getParsoidOutputAccessWithCache( 2 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, self::WIKITEXT );

		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput again, this should trigger a new parse
		// since we're forcing it to.
		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParsoidOutputAccess::OPT_FORCE_PARSE
		);
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );
	}

	/**
	 * Tests that getParserOutput() will force a parse since we know that
	 * the revision is not in the cache.
	 *
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::getParserOutput
	 */
	public function testLatestRevisionWithNoUpdateCache() {
		$access = $this->getParsoidOutputAccessWithCache( 2 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, self::WIKITEXT );

		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParsoidOutputAccess::OPT_NO_UPDATE_CACHE
		);
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput again, this should trigger a new parse
		// since we suppressed caching above.
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );
	}

	public function provideCacheThresholdData() {
		return [
			yield "fast parse" => [ 1, 2 ], // high threshold, no caching
			yield "slow parse" => [ 0, 1 ], // low threshold, caching
		];
	}

	/**
	 * @dataProvider provideCacheThresholdData()
	 */
	public function testHtmlWithCacheThreshold(
		$cacheThresholdTime,
		$expectedCalls
	) {
		$page = $this->getExistingTestPage( __METHOD__ );
		$parsoidCacheConfig = [
			'CacheThresholdTime' => $cacheThresholdTime
		];
		$parserOptions = $this->getParserOptions();

		$access = $this->getParsoidOutputAccessWithCache( $expectedCalls, $parsoidCacheConfig );
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML, $status );

		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML, $status );
	}

	public function testOldRevisionIsCached() {
		$access = $this->getParsoidOutputAccessWithCache( 1 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$status1 = $this->editPage( $page, self::WIKITEXT );
		$rev = $status1->getValue()['revision-record'];

		// Make an edit so that the revision we're getting output
		// for below is not the current revision.
		$this->editPage( $page, 'Second revision' );

		$access->getParserOutput( $page, $parserOptions, $rev );

		// Get the ParserOutput from cache, using revision object
		$status = $access->getCachedParserOutput( $page, $parserOptions, $rev );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput from cache, using revision ID
		$status = $access->getCachedParserOutput( $page->getTitle(), $parserOptions, $rev->getId() );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );

		// Get the ParserOutput again, this should not trigger a new parse.
		$status = $access->getParserOutput( $page, $parserOptions, $rev );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status );
	}

	public function testGetParserOutputWithOldRevision() {
		$access = $this->getParsoidOutputAccessWithCache( 2 );
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$status1 = $this->editPage( $page, self::WIKITEXT );
		$rev1 = $status1->getValue()['revision-record'];

		$this->editPage( $page, 'Second revision' );

		$status2 = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of Second revision', $status2 );

		$status1 = $access->getParserOutput( $page, $parserOptions, $rev1 );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status1 );

		// Again, using just the revision ID
		$status1 = $access->getParserOutput( $page, $parserOptions, $rev1->getId() );
		$this->assertContainsHtml( self::MOCKED_HTML . ' of ' . self::WIKITEXT, $status1 );

		// check that getParsoidRenderID() doesn't throw
		$output1 = $status1->getValue();
		$this->assertNotNull( $access->getParsoidRenderID( $output1 ) );
	}

	public function provideSupportsContentModels() {
		yield [ CONTENT_MODEL_WIKITEXT, true ];
		yield [ CONTENT_MODEL_JSON, true ];
		yield [ CONTENT_MODEL_JAVASCRIPT, false ];
		yield [ 'xyzzy', false ];
	}

	/**
	 * @dataProvider provideSupportsContentModels
	 */
	public function testSupportsContentModel( $model, $expected ) {
		$this->markTestSkipped( 'Broken by fix for T324711. Restore once we have T311728.' );

		$access = $this->getParsoidOutputAccessWithCache( 0 );
		$this->assertSame( $expected, $access->supportsContentModel( $model ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parse
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parseInternal
	 */
	public function testParseWithPageRecordAndNoRevision() {
		$pageRecord = $this->getExistingTestPage( __METHOD__ )->toPageRecord();
		$pOpts = ParserOptions::newFromAnon();

		$parsoidOutputAccess = $this->getServiceContainer()->getParsoidOutputAccess();
		$status = $parsoidOutputAccess->parse( $pageRecord, $pOpts, self::ENV_OPTS, null );

		$this->assertInstanceOf( Status::class, $status );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );

		/** @var ParserOutput $parserOutput */
		$parserOutput = $status->getValue();
		$this->assertStringContainsString( __METHOD__, $parserOutput->getText() );
		$this->assertNotEmpty( $parserOutput->getExtensionData( 'parsoid-render-id' ) );
		$this->assertNotEmpty( $parserOutput->getCacheRevisionId() );
		$this->assertNotEmpty( $parserOutput->getCacheTime() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parse
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parseInternal
	 */
	public function testParseWithPageRecordAndRevision() {
		$page = $this->getExistingTestPage( __METHOD__ );
		$pageRecord = $page->toPageRecord();
		$pOpts = ParserOptions::newFromAnon();
		$revRecord = $page->getRevisionRecord();

		$parsoidOutputAccess = $this->getServiceContainer()->getParsoidOutputAccess();
		$status = $parsoidOutputAccess->parse( $pageRecord, $pOpts, self::ENV_OPTS, $revRecord );

		$this->assertInstanceOf( Status::class, $status );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );

		/** @var ParserOutput $parserOutput */
		$parserOutput = $status->getValue();
		$this->assertStringContainsString( __METHOD__, $parserOutput->getText() );
		$this->assertNotEmpty( $parserOutput->getExtensionData( 'parsoid-render-id' ) );
		$this->assertNotEmpty( $parserOutput->getCacheRevisionId() );
		$this->assertNotEmpty( $parserOutput->getCacheTime() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parse
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parseInternal
	 */
	public function testParseWithPageIdentityAndRevisionId() {
		$page = $this->getExistingTestPage( __METHOD__ );
		$pOpts = ParserOptions::newFromAnon();
		$revId = $page->getLatest();

		$parsoidOutputAccess = $this->getServiceContainer()->getParsoidOutputAccess();
		$status = $parsoidOutputAccess->parse( $page->getTitle(), $pOpts, self::ENV_OPTS, $revId );

		$this->assertInstanceOf( Status::class, $status );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );

		/** @var ParserOutput $parserOutput */
		$parserOutput = $status->getValue();
		$this->assertStringContainsString( __METHOD__, $parserOutput->getText() );
		$this->assertNotEmpty( $parserOutput->getExtensionData( 'parsoid-render-id' ) );
		$this->assertNotEmpty( $parserOutput->getCacheRevisionId() );
		$this->assertNotEmpty( $parserOutput->getCacheTime() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parse
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parseInternal
	 */
	public function testParseWithNonExistingPageAndFakeRevision() {
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$pOpts = ParserOptions::newFromAnon();

		// Create a fake revision record
		$revRecord = new MutableRevisionRecord( $page->getTitle() );
		$revRecord->setId( 0 );
		$revRecord->setPageId( $page->getId() );
		$revRecord->setContent(
			SlotRecord::MAIN,
			new WikitextContent( 'test' )
		);

		$parsoidOutputAccess = $this->getServiceContainer()->getParsoidOutputAccess();
		$status = $parsoidOutputAccess->parse( $page->getTitle(), $pOpts, self::ENV_OPTS, $revRecord );

		$this->assertInstanceOf( Status::class, $status );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );

		/** @var ParserOutput $parserOutput */
		$parserOutput = $status->getValue();
		$this->assertStringContainsString( __METHOD__, $parserOutput->getText() );
		$this->assertNotEmpty( $parserOutput->getExtensionData( 'parsoid-render-id' ) );
		// The revision ID is set to 0, so that's what is in the cache.
		$this->assertSame( 0, $parserOutput->getCacheRevisionId() );
		$this->assertNotEmpty( $parserOutput->getCacheTime() );
	}

	/**
	 * Mock the language class based on a language code.
	 *
	 * @param string $langCode
	 *
	 * @return Language|Language&MockObject|MockObject
	 */
	private function getLanguageMock( string $langCode ) {
		$language = $this->createMock( Language::class );
		$language->method( 'getCode' )->willReturn( $langCode );

		return $language;
	}

	/** @return Generator */
	public function provideParserOptionsWithLanguageOverride() {
		$parserOptions = $this->createMock( ParserOptions::class );
		$parserOptions->method( 'getTargetLanguage' )
			->willReturn( null );
		yield 'ParserOptions with no language' => [ $parserOptions, 'en' ];

		$langCode = 'de';
		$parserOptions = $this->createMock( ParserOptions::class );
		$parserOptions->method( 'getTargetLanguage' )
			->willReturn( $this->getLanguageMock( $langCode ) );
		yield 'ParserOptions for "de" language' => [ $parserOptions, $langCode ];

		$langCode = 'ar';
		$parserOptions = $this->createMock( ParserOptions::class );
		$parserOptions->method( 'getTargetLanguage' )
			->willReturn( $this->getLanguageMock( $langCode ) );
		yield 'ParserOptions for "ar" language' => [ $parserOptions, $langCode ];
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\HtmlOutputRendererHelper::getParserOutput
	 * @dataProvider provideParserOptionsWithLanguageOverride
	 */
	public function testGetParserOutputWithLanguageOverride( $parserOptions, $expectedLangCode ) {
		$services = $this->getServiceContainer();
		$parserOutputAccess = $services->getParsoidOutputAccess();

		$page = $this->getExistingTestPage();

		$status = $parserOutputAccess->getParserOutput( $page, $parserOptions );

		$this->assertTrue( $status->isOK() );

		// assert dummy content in parsoid output HTML
		$html = $status->getValue()->getText();
		$this->assertStringContainsString( 'UTContent', $html );

		if ( $parserOptions->getTargetLanguage() !== null ) {
			$targetLanguage = $parserOptions->getTargetLanguage()->getCode();
			$this->assertSame( $expectedLangCode, $targetLanguage );
			$this->assertInstanceOf( Language::class, $parserOptions->getTargetLanguage() );
		} else {
			$this->assertNull( $parserOptions->getTargetLanguage() );
		}

		// assert the page language in parsoid output HTML
		$this->assertStringContainsString( 'lang="' . $expectedLangCode . '"', $html );
		$this->assertStringContainsString( 'content="' . $expectedLangCode . '"', $html );
	}

}
