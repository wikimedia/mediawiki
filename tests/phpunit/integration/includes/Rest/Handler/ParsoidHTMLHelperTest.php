<?php

namespace MediaWiki\Tests\Rest\Helper;

use BagOStuff;
use DeferredUpdates;
use EmptyBagOStuff;
use Exception;
use ExtensionRegistry;
use HashBagOStuff;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Rest\Handler\ParsoidHTMLHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use ParserCache;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use User;
use WANObjectCache;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Handler\ParsoidHTMLHelper
 * @group Database
 */
class ParsoidHTMLHelperTest extends MediaWikiIntegrationTestCase {
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
	];

	/**
	 * @param int $expectedCalls
	 *
	 * @return MockObject
	 */
	public function newMockParsoid( $expectedCalls = 1 ): MockObject {
		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->exactly( $expectedCalls ) )->method( 'wikitext2html' )->willReturn(
			new PageBundle( 'mocked HTML', [ 'parsoid-data' ], [ 'mw-data' ], '1.0' )
		);

		return $parsoid;
	}

	protected function setUp(): void {
		parent::setUp();

		if ( !ExtensionRegistry::getInstance()->isLoaded( 'Parsoid' ) ) {
			$this->markTestSkipped( 'Parsoid is not configured' );
		}

		$this->setMwGlobals( 'wgCacheEpoch', self::CACHE_EPOCH );

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
	 * @return MockObject
	 */
	private function newUser( array $returns = [] ): MockObject {
		$user = $this->createNoOpMock( User::class, [ 'pingLimiter' ] );
		$user->method( 'pingLimiter' )->willReturn( $returns['pingLimiter'] ?? false );
		return $user;
	}

	/**
	 * @param BagOStuff|null $cache
	 * @param Parsoid|MockObject|null $parsoid
	 * @param array $config
	 *
	 * @return ParsoidHTMLHelper
	 * @throws Exception
	 */
	private function newHelper(
		BagOStuff $cache = null,
		Parsoid $parsoid = null,
		array $config = []
	): ParsoidHTMLHelper {
		$cache = $cache ?: new EmptyBagOStuff();
		$config += MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig );

		$parserCache = new ParserCache(
			'TestPCache',
			$cache,
			self::CACHE_EPOCH,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getJsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory()
		);

		$revisionOutputCache = new RevisionOutputCache(
			'TestRCache',
			new WANObjectCache( [ 'cache' => $cache ] ),
			60 * 60,
			self::CACHE_EPOCH,
			$this->getServiceContainer()->getJsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger()
		);

		$stash = new SimpleParsoidOutputStash( $cache );

		$helper = new ParsoidHTMLHelper(
			$parserCache,
			$revisionOutputCache,
			$this->getServiceContainer()->getGlobalIdGenerator(),
			$stash,
			new NullStatsdDataFactory(),
			new ServiceOptions(
				ParsoidHTMLHelper::CONSTRUCTOR_OPTIONS,
				[ 'ParsoidCacheConfig' => $config ]
			)
		);

		if ( $parsoid !== null ) {
			$wrapper = TestingAccessWrapper::newFromObject( $helper );
			$wrapper->parsoid = $parsoid;
		}

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

	public function provideRevisionReferences() {
		return [
			'current' => [ null, [ 'html' => self::HTML, 'timestamp' => self::TIMESTAMP ] ],
			'old' => [ 'first', [ 'html' => self::HTML_OLD, 'timestamp' => self::TIMESTAMP_OLD ] ],
		];
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testGetHtml( $revRef, $revInfo ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( '<!DOCTYPE html>', $htmlresult );
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( $revInfo['html'], $htmlresult );

		// Test that data-parsoid has been added to ParserOutput
		$pageBundle = $helper->getHtml()->getExtensionData( ParsoidHTMLHelper::PARSOID_PAGE_BUNDLE_KEY );

		$this->assertIsArray( $pageBundle );
		$this->assertArrayHasKey( 'parsoid', $pageBundle );
		$this->assertArrayHasKey( 'mw', $pageBundle );
		$this->assertIsArray( $pageBundle['parsoid'] );
		$this->assertIsArray( $pageBundle['mw'] );

		// check that we actually got data-parsoid mappings.
		$this->assertNotEmpty( $pageBundle['parsoid']['ids'] );
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testHtmlIsCached( $revRef ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$cache = new HashBagOStuff();
		$parsoid = $this->newMockParsoid();

		$helper = $this->newHelper( $cache, $parsoid );

		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );

		// check that we can run the test again and ensure that the parse is only run once
		$helper = $this->newHelper( $cache, $parsoid );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertNotNull( $helper->getHtml()->getExtensionData( ParsoidHTMLHelper::PARSOID_PAGE_BUNDLE_KEY ) );
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );
	}

	public function testHtmlIsStashed() {
		[ $page, ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$cache = new HashBagOStuff();
		$parsoid = $this->newMockParsoid();

		$helper = $this->newHelper( $cache, $parsoid );

		$helper->init( $page, [ 'stash' => true ] + self::PARAM_DEFAULTS, $this->newUser() );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );

		$eTag = $helper->getETag();
		$parsoidStashKey = ParsoidRenderID::newFromETag( $eTag );

		$stash = new SimpleParsoidOutputStash( $cache );
		$this->assertNotNull( $stash->get( $parsoidStashKey ) );
	}

	public function testStashRateLimit() {
		$page = $this->getExistingTestPage( __METHOD__ );

		$helper = $this->newHelper();

		$user = $this->newUser( [ 'pingLimiter' => true ] );
		$helper->init( $page, [ 'stash' => true ] + self::PARAM_DEFAULTS, $user );

		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 429 );
		$helper->getHtml();
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testEtagLastModified( $revRef, $revInfo ) {
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

		// Advance the time, but not so much that caches would expire.
		// The time in the header should remain as before.
		$now = MWTimestamp::convert( TS_UNIX, self::TIMESTAMP_LATER ) + 100;
		MWTimestamp::setFakeTime( $now );
		$helper = $this->newHelper( $cache );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );

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
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$helper = $this->newHelper( null, $parsoid );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
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
		$config = [ 'CacheThresholdTime' => $cacheThresholdTime ];

		$cache = new HashBagOStuff();
		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		// With CacheThresholdTime set to 1, the page will be parsed multiple times
		// in this case, at least 2 times since it was fast to parse.
		$parsoid->expects( $this->exactly( $expectedCalls ) )
			->method( 'wikitext2html' )
			->willReturn( new PageBundle( 'mocked HTML', null, null, '1.0' ) );

		$helper = $this->newHelper( $cache, $parsoid, $config );

		$helper->init( $page, self::PARAM_DEFAULTS );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );

		$helper = $this->newHelper( $cache, $parsoid, $config );
		$helper->init( $page, self::PARAM_DEFAULTS );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertNotNull( $helper->getHtml()->getExtensionData( ParsoidHTMLHelper::PARSOID_PAGE_BUNDLE_KEY ) );
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );
	}

}
