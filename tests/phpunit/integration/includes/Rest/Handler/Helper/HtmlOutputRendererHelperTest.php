<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use Exception;
use MediaWiki\Content\CssContent;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Hook\ParserLogLinterDataHook;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\LanguageVariantConverter;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper
 * @group Database
 */
class HtmlOutputRendererHelperTest extends MediaWikiIntegrationTestCase {
	private const CACHE_EPOCH = '20001111010101';

	private const TIMESTAMP_OLD = '20200101112233';
	private const TIMESTAMP = '20200101223344';
	private const TIMESTAMP_LATER = '20200101234200';

	private const WIKITEXT_OLD = 'Hello \'\'\'Goat\'\'\'';
	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML_OLD = '>Goat<';
	private const HTML = '>World<';

	private const PARAM_DEFAULTS = [
		'stash' => false,
		'flavor' => 'view',
	];

	private const MOCK_HTML = 'mocked HTML';
	private const MOCK_HTML_VARIANT = 'ockedmay HTML';

	private function exactlyOrAny( ?int $count ): InvocationOrder {
		return $count === null ? $this->any() : $this->exactly( $count );
	}

	/**
	 * @param LoggerInterface|null $logger
	 *
	 * @return LoggerSpi
	 */
	private function getLoggerSpi( $logger = null ) {
		$spi = $this->createNoOpMock( LoggerSpi::class, [ 'getLogger' ] );
		$spi->method( 'getLogger' )->willReturn( $logger ?? new NullLogger() );
		return $spi;
	}

	private function setFakeTime( string $time, ?BagOStuff $cache = null ): void {
		MWTimestamp::setFakeTime( $time );
		if ( $cache ) {
			$cache->setMockTime( $time );
		}
	}

	/**
	 * @return MockObject|ParserOutputAccess
	 */
	public function newMockParserOutputAccess( ?string $expectedHtml ): ParserOutputAccess {
		$expectedCalls = [
			'getParserOutput' => null,
		];
		$access = $this->createNoOpMock( ParserOutputAccess::class, array_keys( $expectedCalls ) );
		$access->expects( $this->exactlyOrAny( $expectedCalls[ 'getParserOutput' ] ) )
			->method( 'getParserOutput' )
			->willReturnCallback( function (
				PageRecord $page,
				ParserOptions $parserOpts,
				?RevisionRecord $rev = null,
				int $options = 0
			) use ( $expectedHtml ) {
				// Note that HtmlOutputRendererHelper only passes
				// non-null RevisionRecords here, so getMockHtml() will
				// always return <p>-wrapped main slot content.
				$pout = $this->makeParserOutput(
					$parserOpts,
					$expectedHtml ?? $this->getMockHtml( $rev ),
					$rev,
					$page
				); // will use fake time
				return Status::newGood( $pout );
			} );
		return $access;
	}

	private function getMockHtml( $rev ) {
		if ( $rev instanceof RevisionRecord ) {
			$html = '<p>' . $rev->getContent( SlotRecord::MAIN )->getText() . '</p>';
		} elseif ( is_int( $rev ) ) {
			$html = '<p>rev:' . $rev . '</p>';
		} else {
			$html = self::MOCK_HTML;
		}

		return $html;
	}

	/**
	 * @param ParserOptions $parserOpts
	 * @param string $html
	 * @param RevisionRecord|int|null $rev
	 * @param PageIdentity $page
	 * @param string|null $version
	 *
	 * @return ParserOutput
	 */
	private function makeParserOutput(
		ParserOptions $parserOpts,
		string $html,
		$rev,
		PageIdentity $page,
		?string $version = null
	): ParserOutput {
		static $counter = 0;
		$lang = $parserOpts->getTargetLanguage();
		$lang = $lang ? $lang->getCode() : 'en';
		$version ??= Parsoid::defaultHTMLVersion();

		$html = "<!DOCTYPE html><html lang=\"$lang\"><body><div id='t3s7'>$html</div></body></html>";

		$revTimestamp = null;
		if ( $rev instanceof RevisionRecord ) {
			$revTimestamp = $rev->getTimestamp();
			$rev = $rev->getId() ?? 0;
		}

		$pout = new ParserOutput( $html );
		$pout->setCacheRevisionId( $rev ?? $page->getLatest() );
		$pout->setCacheTime( wfTimestampNow() ); // will use fake time
		if ( $revTimestamp ) {
			$pout->setRevisionTimestamp( $revTimestamp );
		}
		// We test that UUIDs are unique, so make a cheap unique UUID
		$pout->setRenderId( 'bogus-uuid-' . strval( $counter++ ) );
		$pout->setExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY, [
			'parsoid' => [ 'ids' => [
				't3s7' => [ 'dsr' => [ 0, 0, 0, 0 ] ],
			] ],
			'mw' => [ 'ids' => [] ],
			'version' => $version,
			'headers' => [
				'content-language' => $lang
			]
		] );

		$pout->setLanguage( new Bcp47CodeValue( $lang ) );
		return $pout;
	}

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::CacheEpoch, self::CACHE_EPOCH );
	}

	/**
	 * @return MockObject|Authority
	 */
	private function newAuthority(): MockObject {
		$authority = $this->createNoOpMock( Authority::class, [ 'authorizeWrite' ] );
		$authority->method( 'authorizeWrite' )->willReturn( true );
		return $authority;
	}

	/**
	 * @return MockObject|Authority
	 */
	private function newAuthorityWhoCantStash(): MockObject {
		$authority = $this->createNoOpMock( Authority::class, [ 'authorizeWrite' ] );
		$authority->method( 'authorizeWrite' )->willReturnCallback(
			static function ( $action, $target, PermissionStatus $status ) {
				if ( $action === 'stashbasehtml' ) {
					$status->setRateLimitExceeded();
					$status->setPermission( $action );
					return false;
				}

				return true;
			}
		);
		return $authority;
	}

	private function newHelper(
		array $options,
		PageIdentity $page,
		array $parameters = [],
		?Authority $authority = null,
		$revision = null,
		bool $lenientRevHandling = false
	): HtmlOutputRendererHelper {
		$chFactory = $this->getServiceContainer()->getContentHandlerFactory();
		$cache = $options['cache'] ?? new EmptyBagOStuff();
		$stash = new SimpleParsoidOutputStash( $chFactory, $cache, 1 );

		$services = $this->getServiceContainer();

		if ( isset( $options['ParsoidParserFactory'] ) ) {
			$this->resetServicesWithMockedParsoidParserFactory( $options['ParsoidParserFactory'] );
		}

		return new HtmlOutputRendererHelper(
			$stash,
			StatsFactory::newNull(),
			$options['ParserOutputAccess'] ?? $this->newMockParserOutputAccess(
				$options['expectedHtml'] ?? null
			),
			$services->getPageStore(),
			$services->getRevisionLookup(),
			$services->getRevisionRenderer(),
			$services->getParsoidSiteConfig(),
			$options['HtmlTransformFactory'] ?? $services->getHtmlTransformFactory(),
			$services->getContentHandlerFactory(),
			$services->getLanguageFactory(),
			$page, $parameters, $authority, $revision, $lenientRevHandling
		);
	}

	private function getExistingPageWithRevisions(
		$name, $wikitext = self::WIKITEXT, $wikitextOld = self::WIKITEXT_OLD
	) {
		$page = $this->getNonexistingTestPage( $name );

		$this->setFakeTime( self::TIMESTAMP_OLD );
		$this->editPage( $page, $wikitextOld );
		$revisions['first'] = $page->getRevisionRecord();

		$this->setFakeTime( self::TIMESTAMP );
		$this->editPage( $page, $wikitext );
		$revisions['latest'] = $page->getRevisionRecord();

		$this->setFakeTime( self::TIMESTAMP_LATER );
		return [ $page, $revisions ];
	}

	private function getNonExistingPageWithFakeRevision( $name ) {
		$page = $this->getNonexistingTestPage( $name );
		$this->setFakeTime( self::TIMESTAMP_OLD );

		$content = new WikitextContent( self::WIKITEXT_OLD );
		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setPageId( $page->getId() );
		$rev->setContent( SlotRecord::MAIN, $content );

		return [ $page, $rev ];
	}

	public static function provideRevisionReferences() {
		// Expected values to match the code in getExistingPageWithRevisions()

		return [
			'current' => [ null, [ 'html' => self::HTML, 'timestamp' => self::TIMESTAMP ] ],
			'old' => [ 'first', [ 'html' => self::HTML_OLD, 'timestamp' => self::TIMESTAMP_OLD ] ],
		];
	}

	/**
	 * @dataProvider provideRevisionReferences
	 */
	public function testGetHtml( $revRef ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		// Test with just the revision ID, not the object! We do that elsewhere.
		$revId = $revRef ? $revisions[ $revRef ]->getId() : null;

		$helper = $this->newHelper( [ 'expectedHtml' => $this->getMockHtml( $revId ) ], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		if ( $revId ) {
			$helper->setRevision( $revId );
			$this->assertSame( $revId, $helper->getRevisionId() );
		} else {
			// current revision
			$this->assertSame( 0, $helper->getRevisionId() );
		}

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( $this->getMockHtml( $revId ), $htmlresult );
	}

	public function testGetHtmlWithVariant() {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$page = $this->getExistingTestPage( __METHOD__ );

		$helper = $this->newHelper( [ 'expectedHtml' => self::MOCK_HTML ], $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setVariantConversionLanguage( new Bcp47CodeValue( 'en-x-piglatin' ) );

		$htmlResult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( self::MOCK_HTML_VARIANT, $htmlResult );
		$this->assertStringContainsString( 'en-x-piglatin', $helper->getETag() );

		$pbResult = $helper->getPageBundle();
		$this->assertStringContainsString( self::MOCK_HTML_VARIANT, $pbResult->html );
		$this->assertStringContainsString( 'en-x-piglatin', $pbResult->headers['content-language'] );
	}

	public function testGetHtmlWillLint() {
		$this->overrideConfigValue( MainConfigNames::ParsoidSettings, [
			'linting' => true
		] );

		$page = $this->getExistingTestPage( __METHOD__ );

		$mockHandler = $this->createMock( ParserLogLinterDataHook::class );
		$mockHandler->expects( $this->once() ) // this is the critical assertion in this test case!
			->method( 'onParserLogLinterData' );

		$this->setTemporaryHook(
			'ParserLogLinterData',
			$mockHandler
		);

		// Ensure that the ParserOutputAccess isn't holding cached html.
		$this->resetServices();
		// Use the real ParserOutputAccess, so we use the real hook container.
		$access = $this->getServiceContainer()->getParserOutputAccess();

		$helper = $this->newHelper( [ 'ParserOutputAccess' => $access ], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		// Do it.
		$helper->getHtml();
	}

	public function testGetPageBundleWithOptions() {
		$this->markTestSkipped( 'T347426: Support for non-default output content major version has been disabled.' );
		$page = $this->getExistingTestPage( __METHOD__ );

		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		// Calling setParsoidOptions must disable caching and force the ETag to null
		$helper->setOutputProfileVersion( '999.0.0' );

		$pb = $helper->getPageBundle();

		// NOTE: Check that the options are present in the HTML.
		//       We don't do real parsing, so this is how they are represented in the output.
		$this->assertStringContainsString( '"outputContentVersion":"999.0.0"', $pb->html );
		$this->assertStringContainsString( '"offsetType":"byte"', $pb->html );

		$response = new Response();
		$helper->putHeaders( $response, true );
		$this->assertStringContainsString( 'private', $response->getHeaderLine( 'Cache-Control' ) );
	}

	public function testGetPreviewHtml_setContent() {
		$page = $this->getNonexistingTestPage();

		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setContent( new WikitextContent( 'text to preview' ) );

		// getRevisionId() should return null for fake revisions.
		$this->assertNull( $helper->getRevisionId() );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'text to preview', $htmlresult );
	}

	public function testGetPreviewHtml_setContentSource() {
		$page = $this->getNonexistingTestPage();

		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setContentSource( 'text to preview', CONTENT_MODEL_WIKITEXT );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'text to preview', $htmlresult );
	}

	public function testHtmlIsStashedForExistingPage() {
		[ $page, ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$cache = new HashBagOStuff();
		$this->setFakeTime( self::TIMESTAMP, $cache );

		$helper = $this->newHelper(
			[ 'cache' => $cache, 'expectedHtml' => self::MOCK_HTML ],
			$page,
			self::PARAM_DEFAULTS,
			$this->newAuthority()
		);
		$helper->setStashingEnabled( true );

		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( self::MOCK_HTML, $htmlresult );

		$eTag = $helper->getETag();
		$parsoidStashKey = ParsoidRenderID::newFromETag( $eTag );

		$chFactory = $this->createNoOpMock( IContentHandlerFactory::class );
		$stash = new SimpleParsoidOutputStash( $chFactory, $cache, 1 );
		$this->assertNotNull( $stash->get( $parsoidStashKey ) );
	}

	public function testHtmlIsStashedForFakeRevision() {
		$page = $this->getNonexistingTestPage();

		$cache = new HashBagOStuff();
		$this->setFakeTime( self::TIMESTAMP, $cache );
		$text = 'just some wikitext';

		$helper = $this->newHelper( [ 'cache' => $cache ], $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setContent( new WikitextContent( $text ) );
		$helper->setStashingEnabled( true );

		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( $text, $htmlresult );

		$eTag = $helper->getETag();
		$parsoidStashKey = ParsoidRenderID::newFromETag( $eTag );

		$chFactory = $this->getServiceContainer()->getContentHandlerFactory();
		$stash = new SimpleParsoidOutputStash( $chFactory, $cache, 1 );

		$selserContext = $stash->get( $parsoidStashKey );
		$this->assertNotNull( $selserContext );

		/** @var WikitextContent $stashedContent */
		$stashedContent = $selserContext->getContent();
		$this->assertNotNull( $stashedContent );
		$this->assertInstanceOf( WikitextContent::class, $stashedContent );
		$this->assertSame( $text, $stashedContent->getText() );
	}

	public function testStashRateLimit() {
		$page = $this->getExistingTestPage( __METHOD__ );

		$authority = $this->newAuthorityWhoCantStash();
		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $authority );
		$helper->setStashingEnabled( true );

		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 429 );
		$helper->getHtml();
	}

	public function testInteractionOfStashAndFlavor() {
		$page = $this->getExistingTestPage( __METHOD__ );

		$authority = $this->newAuthorityWhoCantStash();
		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $authority );

		// Assert that the initial flavor is "view"
		$this->assertSame( 'view', $helper->getFlavor() );

		// Assert that we can change the flavor to "edit"
		$helper->setFlavor( 'edit' );
		$this->assertSame( 'edit', $helper->getFlavor() );

		// Assert that enabling stashing will force the flavor to be "stash"
		$helper->setStashingEnabled( true );
		$this->assertSame( 'stash', $helper->getFlavor() );

		// Assert that disabling stashing will reset the flavor to "view"
		$helper->setStashingEnabled( false );
		$this->assertSame( 'view', $helper->getFlavor() );

		// Assert that we cannot change the flavor to "view" when stashing is enabled
		$helper->setStashingEnabled( true );
		$helper->setFlavor( 'view' );
		$this->assertSame( 'stash', $helper->getFlavor() );
	}

	public function testGetHtmlFragment() {
		$page = $this->getExistingTestPage();

		$expectedHtml = '<html><body><section data-mw-section-id=0><p>Contents</p></section></body></html>';
		$helper = $this->newHelper( [
			'ParsoidParserFactory' => $this->newMockParsoidParserFactory( [
				'expectedHtml' => $expectedHtml
			] ),
			'expectedHtml' => $expectedHtml,

		], $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setFlavor( 'fragment' );
		$helper->setContentSource( 'Contents', CONTENT_MODEL_WIKITEXT );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'fragment', $helper->getETag() );
		$this->assertStringContainsString( '<p>Contents</p>', $htmlresult );
		$this->assertStringNotContainsString( "<body", $htmlresult );
		$this->assertStringNotContainsString( "<section", $htmlresult );
	}

	public function testGetHtmlForEdit() {
		$page = $this->getExistingTestPage();

		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setContentSource( 'hello {{world}}', CONTENT_MODEL_WIKITEXT );
		$helper->setFlavor( 'edit' );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'edit', $helper->getETag() );

		$this->assertStringContainsString( 'hello', $htmlresult );
		$this->assertStringContainsString( 'data-parsoid=', $htmlresult );
		$this->assertStringContainsString( '"dsr":', $htmlresult );
	}

	/**
	 * @dataProvider provideRevisionReferences
	 */
	public function testETagLastModified( $revRef ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$cache = new HashBagOStuff();
		$this->setFakeTime( self::TIMESTAMP, $cache );

		// First, test it works if nothing was cached yet.
		$helper = $this->newHelper( [ 'cache' => $cache ], $page, self::PARAM_DEFAULTS, $this->newAuthority(), $rev );

		// put HTML into the cache
		$pout = $helper->getHtml();

		$renderId = ParsoidRenderID::newFromParserOutput( $pout );

		if ( $rev ) {
			$this->assertSame( $rev->getId(), $helper->getRevisionId() );

			// old revision use ParserOutput timestamp
			$lastModified = $pout->getCacheTime();
		} else {
			$this->assertSame( 0, $helper->getRevisionId() );

			// current revision uses the page's touch time
			$lastModified = $page->getTouched();
		}

		// make sure the etag didn't change after getHtml();
		$this->assertStringContainsString( $renderId->getKey(), $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $lastModified ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);

		// Now, expire the cache. etag and timestamp should change
		$now = MWTimestamp::convert( TS_UNIX, self::TIMESTAMP_LATER ) + 10000;
		$this->setFakeTime( $now, $cache );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $now ) ),
			'Cannot invalidate cache'
		);
		DeferredUpdates::doUpdates();
		$page->clear();

		$helper = $this->newHelper( [ 'cache' => $cache ], $page, self::PARAM_DEFAULTS, $this->newAuthority(), $rev );

		$this->assertStringNotContainsString( $renderId->getKey(), $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $now ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);
	}

	/**
	 * Check that getLastModified doesn't load ParserOutput for the latest revision.
	 */
	public function testFastLastModified_no_rev() {
		$page = $this->getExistingTestPage();
		$touchDate = $page->getTouched();

		// First, test it works if nothing was cached yet.
		$helper = $this->newHelper( [
			'ParserOutputAccess' => $this->createNoOpMock( ParserOutputAccess::class ),
		], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		// Try without providing a revision
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $touchDate ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);

		// Provide the latest revision
		$helper = $this->newHelper( [
			'ParserOutputAccess' => $this->createNoOpMock( ParserOutputAccess::class ),
		], $page, self::PARAM_DEFAULTS, $this->newAuthority(), $page->getLatest() );

		$this->assertSame(
			MWTimestamp::convert( TS_MW, $touchDate ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper::init
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper::parseUncacheable
	 */
	public function testETagLastModifiedWithPageIdentity() {
		[ $fakePage, $fakeRevision ] = $this->getNonExistingPageWithFakeRevision( __METHOD__ );
		$pp = $this->createMock( ParsoidParser::class );
		$pp->expects( $this->once() )
			->method( 'parse' )
			->willReturnCallback( function (
				$text,
				PageReference $page,
				ParserOptions $parserOpts,
				bool $linestart = true,
				bool $clearState = true,
				?int $revId = null
			) use ( $fakePage, $fakeRevision ) {
				self::assertTrue( $page->isSamePageAs( $fakePage ), '$page and $fakePage should be the same' );
				self::assertSame( $revId, $fakeRevision->getId(), '$rev and $fakeRevision should be the same' );

				$html = $this->getMockHtml( $fakeRevision );
				$pout = $this->makeParserOutput( $parserOpts, $html, $fakeRevision, $page );
				return $pout;
			} );
		$options['ParsoidParser'] = $pp;
		$options['ParsoidParserFactory'] = $this->newMockParsoidParserFactory(
			$options
		);

		$helper = $this->newHelper( $options, $fakePage, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setRevision( $fakeRevision );

		$this->assertNull( $helper->getRevisionId() );

		$pout = $helper->getHtml();
		$renderId = ParsoidRenderID::newFromParserOutput( $pout );
		$lastModified = $pout->getCacheTime();

		$this->assertStringContainsString( $renderId->getKey(), $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $lastModified ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);
	}

	public static function provideETagSuffix() {
		yield 'stash + html' =>
		[ [ 'stash' => true ], 'html', '/stash/html' ];

		yield 'view html' =>
		[ [], 'html', '/view/html' ];

		yield 'stash + wrapped' =>
		[ [ 'stash' => true ], 'with_html', '/stash/with_html' ];

		yield 'view wrapped' =>
		[ [], 'with_html', '/view/with_html' ];

		yield 'stash' =>
		[ [ 'stash' => true ], '', '/stash' ];

		yield 'flavor = fragment' =>
		[ [ 'flavor' => 'fragment' ], '', '/fragment' ];

		yield 'flavor = fragment + stash = true: stash should take over' =>
		[ [ 'stash' => true, 'flavor' => 'fragment' ], '', '/stash' ];

		yield 'nothing' =>
		[ [], '', '/view' ];
	}

	/**
	 * @dataProvider provideETagSuffix
	 */
	public function testETagSuffix( array $params, string $mode, string $suffix ) {
		$page = $this->getExistingTestPage( __METHOD__ );

		$cache = new HashBagOStuff();
		$this->setFakeTime( self::TIMESTAMP, $cache );

		// First, test it works if nothing was cached yet.
		$helper = $this->newHelper( [
			'cache' => $cache,
		], $page, $params + self::PARAM_DEFAULTS, $this->newAuthority() );
		if ( ( $params['flavor'] ?? null ) === 'fragment' ) {
			$helper->setContentSource( "fragment test", CONTENT_MODEL_WIKITEXT );
		}

		$etag = $helper->getETag( $mode );
		$etag = trim( $etag, '"' );
		$this->assertStringEndsWith( $suffix, $etag );
	}

	public static function provideVariantConversionLanguage() {
		yield 'simple code'
		=> [ 'en', new Bcp47CodeValue( 'en' ) ];

		yield 'code with dashes'
		=> [ 'en-x-piglatin', new Bcp47CodeValue( 'en-x-piglatin' ) ];

		yield 'obsolete alias'
		=> [ 'zh-min-nan', new Bcp47CodeValue( 'nan' ) ];

		yield 'obsolete alias in source language'
		=> [ 'en', new Bcp47CodeValue( 'en' ),
			'zh-min-nan', new Bcp47CodeValue( 'nan' ) ];

		yield 'target and source given as objects'
		=> [ new Bcp47CodeValue( 'x y z' ), new Bcp47CodeValue( 'x y z' ),
			new Bcp47CodeValue( 'a,b,c' ), new Bcp47CodeValue( 'a,b,c' ) ];

		yield 'complex accept-language header (T350852)'
		=> [ 'da, en-gb;q=0.8, en;q=0.7', new Bcp47CodeValue( 'da' ) ];
	}

	/**
	 * @dataProvider provideVariantConversionLanguage
	 */
	public function testSetVariantConversionLanguage(
		$target,
		Bcp47Code $expectedTarget,
		$source = null,
		?Bcp47Code $expectedSource = null
	) {
		$converter = $this->createNoOpMock(
			LanguageVariantConverter::class,
			[ 'convertPageBundleVariant' ]
		);

		// This is the key assertion in this test:
		$converter->expects( $this->once() )
			->method( 'convertPageBundleVariant' )->with(
				$this->anything(),
				$expectedTarget,
				$expectedSource
			);

		$transformFactory = $this->createNoOpMock(
			HtmlTransformFactory::class,
			[ 'getLanguageVariantConverter' ]
		);
		$transformFactory->method( 'getLanguageVariantConverter' )
			->willReturn( $converter );

		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$page = $this->getExistingTestPage( __METHOD__ );

		$helper = $this->newHelper( [ 'HtmlTransformFactory' => $transformFactory ], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		// call method under test
		$helper->setVariantConversionLanguage( $target, $source );

		// Secondary assertion, to ensure that the ETag varies on the right thing.
		$this->assertStringEndsWith( "+lang:$expectedTarget\"", $helper->getETag() );

		$helper->getPageBundle();
	}

	public static function provideHandlesParsoidError() {
		yield 'ClientError' => [
			new ClientError( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-html-backend-error' ),
				400,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
		yield 'ResourceLimitExceededException' => [
			new ResourceLimitExceededException( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-resource-limit-exceeded' ),
				413,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
		yield 'RevisionAccessException' => [
			new RevisionAccessException( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-title' ),
				404,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
	}

	private function newMockParsoidParserFactory( array $options = [] ) {
		if ( isset( $options['Parsoid'] ) ) {
			$mockParsoid = $options['Parsoid'];
		} else {
			$mockParsoid = $this->createNoOpMock( Parsoid::class, [
				'wikitext2html',
			] );
			$mockParsoid
				->method( 'wikitext2html' )
				->willReturn( new PageBundle(
					$options['expectedHtml'] ?? 'This is HTML'
				) );
		}

		// Install it in the ParsoidParser object
		if ( isset( $options['ParsoidParser'] ) ) {
			$parsoidParser = $options['ParsoidParser'];
		} else {
			$services = $this->getServiceContainer();
			$parsoidParser = new ParsoidParser(
				$mockParsoid,
				$services->getParsoidPageConfigFactory(),
				$services->getLanguageConverterFactory(),
				$services->getParsoidDataAccess()
			);
		}

		// Create a mock Parsoid factory that returns the ParsoidParser object
		// with the mocked Parsoid object.
		$mockParsoidParserFactory = $this->createNoOpMock( ParsoidParserFactory::class, [ 'create' ] );
		$mockParsoidParserFactory->method( 'create' )->willReturn( $parsoidParser );
		return $mockParsoidParserFactory;
	}

	private function resetServicesWithMockedParsoid( ?Parsoid $mockParsoid = null ): void {
		$services = $this->getServiceContainer();
		$mockParsoidParserFactory = $this->newMockParsoidParserFactory( [
			'Parsoid' => $mockParsoid,
		] );
		$this->resetServicesWithMockedParsoidParserFactory( $mockParsoidParserFactory );
	}

	private function resetServicesWithMockedParsoidParserFactory( ?ParsoidParserFactory $mockParsoidParserFactory = null ): void {
		$this->setService( 'ParsoidParserFactory', $mockParsoidParserFactory );
	}

	private function newRealParserOutputAccess( $overrides = [] ): array {
		$services = $this->getServiceContainer();

		if ( isset( $overrides['parserCache'] ) ) {
			$parserCache = $overrides['parserCache'];
		} else {
			$parserCache = $this->createNoOpMock(
				ParserCache::class,
				[ 'get', 'save', 'makeParserOutputKey', ]
			);
			$parserCache->method( 'get' )->willReturn( false );
			$parserCache->method( 'save' )->willReturn( null );
			$parserCache->method( 'makeParserOutputKey' )->willReturn( 'test-key' );
		}

		if ( isset( $overrides['revisionCache'] ) ) {
			$revisionCache = $overrides['revisionCache'];
		} else {
			$revisionCache = $this->createNoOpMock( RevisionOutputCache::class, [ 'get', 'save' ] );
			$revisionCache->method( 'get' )->willReturn( false );
			$revisionCache->method( 'save' )->willReturn( null );
		}

		$parserCacheFactory = $this->createNoOpMock(
			ParserCacheFactory::class,
			[ 'getParserCache', 'getRevisionOutputCache' ]
		);
		$parserCacheFactory->method( 'getParserCache' )->willReturn( $parserCache );
		$parserCacheFactory->method( 'getRevisionOutputCache' )->willReturn( $revisionCache );
		$parserOutputAccess = new ParserOutputAccess(
			$parserCacheFactory,
			$services->getRevisionLookup(),
			$services->getRevisionRenderer(),
			StatsFactory::newNull(),
			$services->getChronologyProtector(),
			$this->getLoggerSpi(),
			$services->getWikiPageFactory(),
			$services->getTitleFormatter(),
			$services->getTracer(),
			$services->getPoolCounterFactory()
		);
		return [
			'ParserOutputAccess' => $parserOutputAccess,
		];
	}

	/**
	 * @dataProvider provideHandlesParsoidError
	 */
	public function testHandlesParsoidError(
		Exception $parsoidException,
		Exception $expectedException
	) {
		$page = $this->getExistingTestPage( __METHOD__ );

		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$parserCache = $this->createNoOpMock( ParserCache::class, [ 'get', 'getDirty', 'makeParserOutputKey' ] );
		$parserCache->method( 'get' )->willReturn( false );
		$parserCache->method( 'getDirty' )->willReturn( false );
		$parserCache->expects( $this->atLeastOnce() )->method( 'makeParserOutputKey' );

		$this->resetServicesWithMockedParsoid( $parsoid );
		$access = $this->newRealParserOutputAccess( [ 'parserCache' => $parserCache ] );

		$helper = $this->newHelper( $access, $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
	}

	public static function provideParsoidOutputStatus() {
		yield 'parsoid-client-error' => [
			Status::newFatal( 'parsoid-client-error' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-html-backend-error' ),
				400,
				[
					'reason' => 'parsoid-client-error'
				]
			)
		];
		yield 'parsoid-resource-limit-exceeded' => [
			Status::newFatal( 'parsoid-resource-limit-exceeded' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-resource-limit-exceeded' ),
				413,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
		yield 'missing-revision-permission' => [
			Status::newFatal( 'missing-revision-permission' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-revision' ),
				403,
				[
					'reason' => 'missing-revision-permission'
				]
			)
		];
		yield 'parsoid-revision-access' => [
			Status::newFatal( 'parsoid-revision-access' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-specified-revision-unavailable' ),
				404,
				[
					'reason' => 'parsoid-revision-access'
				]
			)
		];
	}

	/**
	 * @dataProvider provideParsoidOutputStatus
	 */
	public function testParsoidOutputStatus(
		Status $parserOutputStatus,
		Exception $expectedException
	) {
		$page = $this->getExistingTestPage( __METHOD__ );

		$parserAccess = $this->createNoOpMock( ParserOutputAccess::class, [ 'getParserOutput' ] );
		$parserAccess->method( 'getParserOutput' )
			->willReturn( $parserOutputStatus );

		$helper = $this->newHelper( [
			'ParserOutputAccess' => $parserAccess,
		], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
	}

	public function testWillUseParserCache() {
		$page = $this->getExistingTestPage( __METHOD__ );

		// NOTE: Use a simple PageIdentity here, to make sure the relevant PageRecord
		//       will be looked up as needed.
		$page = PageIdentityValue::localIdentity( $page->getId(), $page->getNamespace(), $page->getDBkey() );

		// This is the key assertion in this test case: get() and save() are both called.
		$parserCache = $this->createNoOpMock( ParserCache::class, [ 'get', 'getDirty', 'save', 'makeParserOutputKey' ] );
		$parserCache->expects( $this->once() )->method( 'get' )->willReturn( false );
		$parserCache->method( 'getDirty' )->willReturn( false );
		$parserCache->expects( $this->once() )->method( 'save' );
		$parserCache->expects( $this->atLeastOnce() )->method( 'makeParserOutputKey' );

		$this->resetServicesWithMockedParsoid();
		$access = $this->newRealParserOutputAccess( [
			'parserCache' => $parserCache,
			'revisionCache' => $this->createNoOpMock( RevisionOutputCache::class )
		] );

		$helper = $this->newHelper( $access, $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		$helper->getHtml();
	}

	public function testGetParserOutputWithLanguageOverride() {
		[ $page, [ 'latest' => $revision ] ] = $this->getExistingPageWithRevisions( __METHOD__, '{{PAGELANGUAGE}}' );

		$options = [
			# use real ParserOutputAccess to exercise caching
			'ParserOutputAccess' => $this->getServiceContainer()->getParserOutputAccess(),
		];
		$helper = $this->newHelper( $options, $page, [], $this->newAuthority(), $revision );
		$helper->setPageLanguage( 'ar' );

		// check nominal content language
		$this->assertSame( 'ar', $helper->getHtmlOutputContentLanguage()->toBcp47Code() );

		// check content language in HTML
		$output = $helper->getHtml();
		$html = $output->getRawText();
		$this->assertStringContainsString( 'lang="ar"', $html );
		$this->assertStringContainsString( '>ar<', $html ); # {{PAGELANGUAGE}}

		// Check that cache is properly split on page language (T376783)
		$helper = $this->newHelper( $options, $page, [], $this->newAuthority(), $revision );
		$helper->setPageLanguage( 'en' );
		$this->assertSame( 'en', $helper->getHtmlOutputContentLanguage()->toBcp47Code() );
		$output = $helper->getHtml();
		$html = $output->getRawText();
		$this->assertStringContainsString( 'lang="en"', $html );
		$this->assertStringContainsString( '>en<', $html ); # {{PAGELANGUAGE}}
	}

	public function testGetParserOutputWithRedundantPageLanguage() {
		$poa = $this->createMock( ParserOutputAccess::class );
		$poa->expects( $this->once() )
			->method( 'getParserOutput' )
			->willReturnCallback( function (
				PageIdentity $page,
				ParserOptions $parserOpts,
				$revision = null,
				int $options = 0
			) {
				$usedOptions = [ 'targetLanguage' ];
				self::assertNull( $parserOpts->getTargetLanguage(), 'No target language should be set in ParserOptions' );
				self::assertTrue( $parserOpts->isSafeToCache( $usedOptions ) );

				$html = $this->getMockHtml( $revision );
				$pout = $this->makeParserOutput( $parserOpts, $html, $revision, $page );
				return Status::newGood( $pout );
			} );

		$page = $this->getExistingTestPage();

		$helper = $this->newHelper( [ 'ParserOutputAccess' => $poa ], $page, [], $this->newAuthority() );

		// Explicitly set the page language to the default.
		$pageLanguage = $page->getTitle()->getPageLanguage();
		$helper->setPageLanguage( $pageLanguage );

		// Trigger parsing, so the assertions in the mock are executed.
		$helper->getHtml();
	}

	public static function provideInit() {
		yield 'Minimal' => [
			[],
			null,
			[
				'page' => 'mock',
				'authority' => 'mock',
				'revisionOrId' => null,
				'stash' => false,
				'flavor' => 'view',
			]
		];

		yield 'Revision and Language' => [
			[],
			'mock',
			[
				'revisionOrId' => 'mock',
			]
		];

		yield 'revid and stash' => [
			[ 'stash' => true ],
			8,
			[
				'stash' => true,
				'flavor' => 'stash',
				'revisionOrId' => 8,
			]
		];

		yield 'flavor' => [
			[ 'flavor' => 'fragment' ],
			8,
			[
				'flavor' => 'fragment',
			]
		];

		yield 'stash winds over flavor' => [
			[ 'flavor' => 'fragment', 'stash' => true ],
			8,
			[
				'flavor' => 'stash',
			]
		];
	}

	/**
	 * Whitebox test for ensuring that init() sets the correct members.
	 * Testing init() against behavior would mean duplicating all tests that use setters.
	 *
	 * @dataProvider provideInit
	 */
	public function testInit(
		array $parameters,
		$revision,
		array $expected
	) {
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'Köfte' );
		$authority = $this->createNoOpMock( Authority::class );
		if ( $revision === 'mock' ) {
			$revision = $this->createNoOpMock( RevisionRecord::class, [ 'getId' ] );
			$revision->method( 'getId' )->willReturn( 7 );
		}

		$helper = $this->newHelper( [], $page, $parameters, $authority, $revision );

		$wrapper = TestingAccessWrapper::newFromObject( $helper );
		foreach ( $expected as $name => $value ) {
			if ( $value === 'mock' ) {
				if ( $name === 'page' ) {
					$value = $page;
				} elseif ( $name === 'authority' ) {
					$value = $authority;
				} else {
					$value = $revision;
				}
			}
			$this->assertSame( $value, $wrapper->$name );
		}
	}

	/**
	 * @dataProvider providePutHeaders
	 */
	public function testPutHeaders( ?string $targetLanguage, bool $setContentLanguageHeader ) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$page = $this->getExistingTestPage( __METHOD__ );
		$expectedCalls = [];

		$helper = $this->newHelper( [], $page, self::PARAM_DEFAULTS, $this->newAuthority() );

		if ( $targetLanguage ) {
			$helper->setVariantConversionLanguage( new Bcp47CodeValue( $targetLanguage ) );
			$expectedCalls['addHeader'] = [ [ 'Vary', 'Accept-Language' ] ];
		}

		if ( $setContentLanguageHeader ) {
			$expectedCalls['setHeader'][] = [ 'Content-Language', $targetLanguage ?: 'en' ];

			$version = Parsoid::defaultHTMLVersion();
			$expectedCalls['setHeader'][] = [
				'Content-Type',
				'text/html; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/HTML/' . $version . '"',
			];
		}

		$responseInterface = $this->getResponseInterfaceMock( $expectedCalls );
		$helper->putHeaders( $responseInterface, $setContentLanguageHeader );
	}

	public static function providePutHeaders() {
		yield 'no target variant language' => [ null, true ];
		yield 'target language is set but setContentLanguageHeader is false' => [ 'en-x-piglatin', false ];
		yield 'target language and setContentLanguageHeader flag is true' =>
			[ 'en-x-piglatin', true ];
	}

	private function getResponseInterfaceMock( array $expectedCalls ) {
		$responseInterface = $this->createNoOpMock( ResponseInterface::class, array_keys( $expectedCalls ) );
		foreach ( $expectedCalls as $method => $arguments ) {
			$responseInterface
				->expects( $this->exactly( count( $arguments ) ) )
				->method( $method )
				->willReturnCallback( function ( ...$actualArgs ) use ( $arguments ) {
					static $expectedArgs;
					$expectedArgs ??= $arguments;
					$this->assertContains( $actualArgs, $expectedArgs );
					$argIdx = array_search( $actualArgs, $expectedArgs, true );
					unset( $expectedArgs[$argIdx] );
				} );
		}

		return $responseInterface;
	}

	public static function provideFlavorsForBadModelOutput() {
		yield 'view' => [ 'view' ];
		yield 'edit' => [ 'edit' ];
		// fragment mode is only for posted wikitext fragments not part of a revision
		// and should not be used with real revisions
		//
		// yield 'fragment' => [ 'fragment' ];
	}

	/**
	 * @dataProvider provideFlavorsForBadModelOutput
	 */
	public function testNonParsoidOutput( string $flavor ) {
		$this->resetServicesWithMockedParsoid();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, new CssContent( '"not wikitext"' ) );

		$cache = new HashBagOStuff();
		$this->setFakeTime( self::TIMESTAMP, $cache );
		$helper = $this->newHelper( [
				'cache' => $cache,
			] + $this->newRealParserOutputAccess(), $page, self::PARAM_DEFAULTS, $this->newAuthority() );
		$helper->setFlavor( $flavor );

		$output = $helper->getHtml();
		$this->assertStringContainsString( 'not wikitext', $output->getRawText() );
		$this->assertNotNull( ParsoidRenderID::newFromParserOutput( $output )->getKey() );
	}

}
