<?php

namespace MediaWiki\Tests\Rest\Helper;

use BagOStuff;
use DeferredUpdates;
use EmptyBagOStuff;
use Exception;
use HashBagOStuff;
use Language;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\Handler\HtmlOutputRendererHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use ParserOptions;
use ParserOutput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use Status;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\TestingAccessWrapper;
use WikitextContent;

/**
 * @covers \MediaWiki\Rest\Handler\HtmlOutputRendererHelper
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
	 * @return MockObject|ParsoidOutputAccess
	 */
	public function newMockParsoidOutputAccess(): ParsoidOutputAccess {
		$expectedCalls = [
			'getParserOutput' => null,
			'parse' => null,
			'getParsoidRenderID' => null
		];

		$parsoid = $this->createNoOpMock( ParsoidOutputAccess::class, array_keys( $expectedCalls ) );

		$parsoid->expects( $this->exactlyOrAny( $expectedCalls[ 'getParserOutput' ] ) )
			->method( 'getParserOutput' )
			->willReturnCallback( function (
				PageRecord $page,
				ParserOptions $parserOpts,
				$rev = null,
				int $options = 0
			) {
				$pout = $this->makeParserOutput(
					$parserOpts,
					$this->getMockHtml( $rev ),
					$rev,
					$page
				); // will use fake time
				return Status::newGood( $pout );
			} );

		$parsoid->expects( $this->exactlyOrAny( $expectedCalls[ 'parse' ] ) )
			->method( 'parse' )
			->willReturnCallback( function (
				PageIdentity $page,
				ParserOptions $parserOpts,
				array $envOptions,
				$rev
			) {
				$html = $this->getMockHtml( $rev, $envOptions );

				$pout = $this->makeParserOutput(
					$parserOpts,
					$html,
					$rev,
					$page
				);

				return Status::newGood( $pout );
			} );

		$parsoid->expects( $this->exactlyOrAny( $expectedCalls[ 'getParsoidRenderID' ] ) )
			->method( 'getParsoidRenderID' )
			->willReturnCallback( static function ( ParserOutput $pout ) {
				return new ParsoidRenderID( $pout->getCacheRevisionId(), $pout->getCacheTime() );
			} );

		return $parsoid;
	}

	private function getMockHtml( $rev, array $envOptions = null ) {
		if ( $rev instanceof RevisionRecord ) {
			$html = '<p>' . $rev->getContent( SlotRecord::MAIN )->getText() . '</p>';
		} elseif ( is_int( $rev ) ) {
			$html = '<p>rev:' . $rev . '</p>';
		} else {
			$html = self::MOCK_HTML;
		}

		if ( $envOptions ) {
			$html .= "\n<!--" . json_encode( $envOptions ) . "\n-->";
		}

		return $html;
	}

	/**
	 * @param ParserOptions $parserOpts
	 * @param string $html
	 * @param RevisionRecord|int|null $rev
	 * @param PageIdentity $page
	 *
	 * @return ParserOutput
	 */
	private function makeParserOutput(
		ParserOptions $parserOpts,
		string $html,
		$rev,
		PageIdentity $page
	): ParserOutput {
		$lang = $parserOpts->getTargetLanguage();
		$lang = $lang ? $lang->getCode() : 'en';

		$html = "<!DOCTYPE html><html lang=\"$lang\">$html</html>";

		if ( $rev instanceof RevisionRecord ) {
			$rev = $rev->getId();
		}

		$pout = new ParserOutput( $html );
		$pout->setCacheRevisionId( $rev ?: $page->getLatest() );
		$pout->setCacheTime( wfTimestampNow() ); // will use fake time
		$pout->setExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY, [
			'parsoid' => [ 'ids' => [] ],
			'mw' => [ 'ids' => [] ],
			'version' => '08-15',
			'headers' => [
				'content-language' => $lang
			]
		] );

		return $pout;
	}

	protected function setUp(): void {
		parent::setUp();

		$this->markTestSkippedIfExtensionNotLoaded( 'Parsoid' );

		$this->overrideConfigValue( MainConfigNames::CacheEpoch, self::CACHE_EPOCH );

		// Clean up these tables after each test
		$this->tablesUsed = [
			'page',
			'revision',
			'comment',
			'text',
			'content'
		];
	}

	/**
	 * @param array $returns
	 *
	 * @return MockObject|User
	 */
	private function newUser( array $returns = [] ): MockObject {
		$user = $this->createNoOpMock( User::class, [ 'pingLimiter' ] );
		$user->method( 'pingLimiter' )->willReturn( $returns['pingLimiter'] ?? false );
		return $user;
	}

	/**
	 * @param BagOStuff|null $cache
	 * @param ?ParsoidOutputAccess $access
	 *
	 * @return HtmlOutputRendererHelper
	 * @throws Exception
	 */
	private function newHelper(
		BagOStuff $cache = null,
		?ParsoidOutputAccess $access = null
	): HtmlOutputRendererHelper {
		$chFactory = $this->getServiceContainer()->getContentHandlerFactory();
		$cache = $cache ?: new EmptyBagOStuff();
		$stash = new SimpleParsoidOutputStash( $chFactory, $cache, 1 );

		$helper = new HtmlOutputRendererHelper(
			$stash,
			new NullStatsdDataFactory(),
			$access ?? $this->newMockParsoidOutputAccess(),
			$this->getServiceContainer()->getHtmlTransformFactory()
		);

		return $helper;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		MWTimestamp::setFakeTime( self::TIMESTAMP_OLD );
		$this->editPage( $page, self::WIKITEXT_OLD );
		$revisions['first'] = $page->getRevisionRecord();

		MWTimestamp::setFakeTime( self::TIMESTAMP );
		$this->editPage( $page, self::WIKITEXT );
		$revisions['latest'] = $page->getRevisionRecord();

		MWTimestamp::setFakeTime( self::TIMESTAMP_LATER );
		return [ $page, $revisions ];
	}

	private function getNonExistingPageWithFakeRevision( $name ) {
		$page = $this->getNonexistingTestPage( $name );
		MWTimestamp::setFakeTime( self::TIMESTAMP_OLD );

		$content = new WikitextContent( self::WIKITEXT_OLD );
		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setPageId( $page->getId() );
		$rev->setContent( SlotRecord::MAIN, $content );

		return [ $page, $rev ];
	}

	public function provideRevisionReferences() {
		return [
			'current' => [ null, [ 'html' => self::HTML, 'timestamp' => self::TIMESTAMP ] ],
			'old' => [ 'first', [ 'html' => self::HTML_OLD, 'timestamp' => self::TIMESTAMP_OLD ] ],
		];
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testGetHtml( $revRef ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		// Test with just the revision ID, not the object! We do that elsewhere.
		$rev = $revRef ? $revisions[ $revRef ]->getId() : null;

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );

		if ( $rev ) {
			$helper->setRevision( $rev );
		}

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( $this->getMockHtml( $rev ), $htmlresult );
	}

	public function testGetHtmlWithVariant() {
		$page = $this->getExistingTestPage( __METHOD__ );

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );
		$helper->setVariantConversionLanguage( 'en-x-piglatin' );

		$htmlResult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( self::MOCK_HTML_VARIANT, $htmlResult );
		$this->assertStringContainsString( 'en-x-piglatin', $helper->getETag() );
	}

	public function testGetPreviewHtml() {
		$page = $this->getNonexistingTestPage();

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );
		$helper->setContent( new WikitextContent( 'text to preview' ) );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'text to preview', $htmlresult );
	}

	public function testHtmlIsStashedForExistingPage() {
		[ $page, ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$cache = new HashBagOStuff();

		$helper = $this->newHelper( $cache );

		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );
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
		$helper = $this->newHelper( $cache );

		$text = 'just some wikitext';

		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );
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

		$helper = $this->newHelper();

		$user = $this->newUser( [ 'pingLimiter' => true ] );
		$helper->init( $page, self::PARAM_DEFAULTS, $user );
		$helper->setStashingEnabled( true );

		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 429 );
		$helper->getHtml();
	}

	public function testGetHtmlFragment() {
		$page = $this->getExistingTestPage();

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );
		$helper->setFlavor( 'fragment' );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'fragment', $helper->getETag() );

		$this->assertStringContainsString( self::MOCK_HTML, $htmlresult );
		$this->assertStringContainsString( '"body_only":true', $htmlresult );
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testEtagLastModified( $revRef ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$cache = new HashBagOStuff();

		// First, test it works if nothing was cached yet.
		$helper = $this->newHelper( $cache );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );
		$etag = $helper->getETag();
		$lastModified = $helper->getLastModified();
		$helper->getHtml(); // put HTML into the cache

		// make sure the etag didn't change after getHtml();
		$this->assertSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $lastModified ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);

		// Now, expire the cache. etag and timestamp should change
		$now = MWTimestamp::convert( TS_UNIX, self::TIMESTAMP_LATER ) + 10000;
		MWTimestamp::setFakeTime( $now );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $now ) ),
			'Cannot invalidate cache'
		);
		DeferredUpdates::doUpdates();
		$page->clear();

		$helper = $this->newHelper( $cache );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );

		$this->assertNotSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $now ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\HtmlOutputRendererHelper::init
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parse
	 */
	public function testEtagLastModifiedWithPageIdentity() {
		[ $fakePage, $fakeRevision ] = $this->getNonExistingPageWithFakeRevision( __METHOD__ );
		$poa = $this->createMock( ParsoidOutputAccess::class );
		$poa->expects( $this->once() )
			->method( 'parse' )
			->willReturnCallback( function (
				PageIdentity $page,
				ParserOptions $parserOpts,
				array $envOptions,
				$rev = null
			) use ( $fakePage, $fakeRevision ) {
				self::assertSame( $page, $fakePage, '$page and $fakePage should be the same' );
				self::assertSame( $rev, $fakeRevision, '$rev and $fakeRevision should be the same' );

				$html = $this->getMockHtml( $rev, $envOptions );
				$pout = $this->makeParserOutput( $parserOpts, $html, $rev, $page );
				return Status::newGood( $pout );
			} );
		$poa->method( 'getParsoidRenderID' )
			->willReturnCallback( static function ( ParserOutput $pout ) {
				return new ParsoidRenderID( 1, $pout->getCacheTime() );
			} );

		$helper = $this->newHelper( null, $poa );
		$helper->init( $fakePage, self::PARAM_DEFAULTS, $this->newUser() );
		$helper->setRevision( $fakeRevision );
		$etag = $helper->getETag();
		$lastModified = $helper->getLastModified();

		$this->assertSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $lastModified ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);
	}

	public function provideETagSuffix() {
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
	 * @dataProvider provideETagSuffix()
	 */
	public function testETagSuffix( array $params, string $mode, string $suffix ) {
		$page = $this->getExistingTestPage( __METHOD__ );

		$cache = new HashBagOStuff();

		// First, test it works if nothing was cached yet.
		$helper = $this->newHelper( $cache );
		$helper->init( $page, $params + self::PARAM_DEFAULTS, $this->newUser() );

		$etag = $helper->getETag( $mode );
		$etag = trim( $etag, '"' );
		$this->assertStringEndsWith( $suffix, $etag );
	}

	public function provideHandlesParsoidError() {
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

		/** @var ParsoidOutputAccess|MockObject $access */
		$access = new ParsoidOutputAccess(
			new ServiceOptions(
				ParsoidOutputAccess::CONSTRUCTOR_OPTIONS,
				$this->getServiceContainer()->getMainConfig(),
				[ 'ParsoidWikiID' => 'MyWiki' ]
			),
			$this->getServiceContainer()->getParserCacheFactory(),
			$this->createNoOpMock( PageLookup::class ),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getGlobalIdGenerator(),
			new NullStatsdDataFactory(),
			$parsoid,
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getParsoidPageConfigFactory()
		);

		$helper = $this->newHelper( null, $access );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
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

	public function testGetParserOutputWithLanguageOverride() {
		$language = $this->getLanguageMock( 'ar' );
		$helper = $this->newHelper();

		[ $page, $revision ] = $this->getNonExistingPageWithFakeRevision( __METHOD__ );

		$helper->init( $page, [], $this->newUser(), $revision );
		$helper->setPageLanguage( $language );

		// check nominal content language
		$this->assertSame( 'ar', $helper->getHtmlOutputContentLanguage() );

		// check content language in HTML
		$output = $helper->getHtml();
		$html = $output->getRawText();
		$this->assertStringContainsString( 'lang="ar"', $html );
	}

	public function provideInit() {
		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'Köfte' );
		$user = $this->createNoOpMock( User::class );

		yield 'Minimal' => [
			$page,
			[],
			$user,
			null,
			null,
			[
				'page' => $page,
				'user' => $user,
				'revisionOrId' => null,
				'pageLanguage' => null,
				'stash' => false,
				'flavor' => 'view',
			]
		];

		$rev = $this->createNoOpMock( RevisionRecord::class );
		$lang = $this->createNoOpMock( Language::class );
		yield 'Revision and Language' => [
			$page,
			[],
			$user,
			$rev,
			$lang,
			[
				'revisionOrId' => $rev,
				'pageLanguage' => $lang,
			]
		];

		yield 'revid and stash' => [
			$page,
			[ 'stash' => true ],
			$user,
			8,
			null,
			[
				'stash' => true,
				'flavor' => 'stash',
				'revisionOrId' => 8,
			]
		];

		yield 'flavor' => [
			$page,
			[ 'flavor' => 'fragment' ],
			$user,
			8,
			null,
			[
				'flavor' => 'fragment',
			]
		];

		yield 'stash winds over flavor' => [
			$page,
			[ 'flavor' => 'fragment', 'stash' => true ],
			$user,
			8,
			null,
			[
				'flavor' => 'stash',
			]
		];
	}

	/**
	 * Whitebox test for ensuring that init() sets the correct members.
	 * Testing init() against behavior would mean duplicating all tests that use setters.
	 *
	 * @param PageIdentity $page
	 * @param array $parameters
	 * @param User $user
	 * @param RevisionRecord|int|null $revision
	 * @param Language|null $pageLanguage
	 * @param array $expected
	 *
	 * @dataProvider provideInit
	 */
	public function testInit(
		PageIdentity $page,
		array $parameters,
		User $user,
		$revision,
		?Language $pageLanguage,
		array $expected
	) {
		$helper = $this->newHelper();

		$helper->init( $page, $parameters, $user, $revision, $pageLanguage );

		$wrapper = TestingAccessWrapper::newFromObject( $helper );
		foreach ( $expected as $name => $value ) {
			$this->assertSame( $value, $wrapper->$name );
		}
	}

	/**
	 * @dataProvider providePutHeaders
	 */
	public function testPutHeaders( ?string $targetLanguage, bool $setContentLanguageHeader ) {
		$page = $this->getExistingTestPage( __METHOD__ );
		$expectedCalls = [];

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );

		if ( $targetLanguage ) {
			$helper->setVariantConversionLanguage( $targetLanguage );
			$expectedCalls['addHeader'] = [ [ 'Vary', 'Accept-Language' ] ];

			if ( $setContentLanguageHeader ) {
				$expectedCalls['setHeader'] = [ [ 'Content-Language', $targetLanguage ] ];
			}
		}

		$responseInterface = $this->getResponseInterfaceMock( $expectedCalls );
		$helper->putHeaders( $responseInterface, $setContentLanguageHeader );
	}

	public function providePutHeaders() {
		yield 'no target variant language' => [ null, true ];
		yield 'target language is set but setContentLanguageHeader is false' => [ 'en-x-piglatin', false ];
		yield 'target language and setContentLanguageHeader flag is true' =>
			[ 'en-x-piglatin', true ];
	}

	private function getResponseInterfaceMock( array $expectedCalls ) {
		$responseInterface = $this->createNoOpMock( ResponseInterface::class, array_keys( $expectedCalls ) );
		foreach ( $expectedCalls as $method => $argument ) {
			$responseInterface
				->expects( $this->exactly( count( $argument ) ) )
				->method( $method )
				->withConsecutive( ...$argument );
		}

		return $responseInterface;
	}

}
