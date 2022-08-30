<?php

namespace MediaWiki\Tests\Rest\Helper;

use BagOStuff;
use DeferredUpdates;
use EmptyBagOStuff;
use Exception;
use ExtensionRegistry;
use HashBagOStuff;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\Handler\ParsoidHTMLHelper;
use MediaWiki\Rest\LocalizedHttpException;
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
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use WikitextContent;

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

	private const MOCK_HTML = '<!DOCTYPE html><html>mocked HTML</html>';

	private function exactlyOrAny( ?int $count ): InvocationOrder {
		return $count === null ? $this->any() : $this->exactly( $count );
	}

	/**
	 * @param array<string,int> $expectedCalls
	 *
	 * @return MockObject|ParsoidOutputAccess
	 */
	public function newMockParsoidOutputAccess( $expectedCalls = [] ): ParsoidOutputAccess {
		$expectedCalls += [
			'getParserOutput' => 1,
			'getParsoidRenderID' => null,
			'getParsoidPageBundle' => null,
		];

		$parsoid = $this->createNoOpMock( ParsoidOutputAccess::class, array_keys( $expectedCalls ) );

		$parsoid->expects( $this->exactlyOrAny( $expectedCalls[ 'getParserOutput' ] ) )
			->method( 'getParserOutput' )
			->willReturnCallback( static function (
				PageRecord $page,
				ParserOptions $parserOpts,
				?RevisionRecord $rev = null,
				int $options = 0
			) {
				$pout = new ParserOutput( self::MOCK_HTML );
				$pout->setCacheRevisionId( $rev ? $rev->getId() : $page->getLatest() );
				$pout->setCacheTime( wfTimestampNow() ); // will use fake time
				return Status::newGood( $pout );
			} );

		$parsoid->expects( $this->exactlyOrAny( $expectedCalls[ 'getParsoidRenderID' ] ) )
			->method( 'getParsoidRenderID' )
			->willReturnCallback( static function ( ParserOutput $pout ) {
				return new ParsoidRenderID( $pout->getCacheRevisionId(), $pout->getCacheTime() );
			} );

		$parsoid->expects( $this->exactlyOrAny( $expectedCalls[ 'getParsoidPageBundle' ] ) )
			->method( 'getParsoidPageBundle' )
			->willReturnCallback( static function ( ParserOutput $pout ) {
				return new PageBundle( $pout->getRawText() );
			} );

		return $parsoid;
	}

	protected function setUp(): void {
		parent::setUp();

		if ( !ExtensionRegistry::getInstance()->isLoaded( 'Parsoid' ) ) {
			$this->markTestSkipped( 'Parsoid is not configured' );
		}

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
	 * @return ParsoidHTMLHelper
	 * @throws Exception
	 */
	private function newHelper( BagOStuff $cache = null, ?ParsoidOutputAccess $access = null ): ParsoidHTMLHelper {
		$cache = $cache ?: new EmptyBagOStuff();
		$stash = new SimpleParsoidOutputStash( $cache, 1 );

		$helper = new ParsoidHTMLHelper(
			$stash,
			new NullStatsdDataFactory(),
			$access ?? $this->newMockParsoidOutputAccess()
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

		$content = new WikitextContent( 'test' );
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
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$helper = $this->newHelper();
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser(), $rev );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertSame( self::MOCK_HTML, $htmlresult );
	}

	public function testHtmlIsStashed() {
		[ $page, ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$cache = new HashBagOStuff();

		$helper = $this->newHelper( $cache );

		$helper->init( $page, [ 'stash' => true ] + self::PARAM_DEFAULTS, $this->newUser() );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertSame( self::MOCK_HTML, $htmlresult );

		$eTag = $helper->getETag();
		$parsoidStashKey = ParsoidRenderID::newFromETag( $eTag );

		$stash = new SimpleParsoidOutputStash( $cache, 1 );
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
	 * @covers ParsoidHTMLHelper::init
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidOutputAccess::parse
	 */
	public function testEtagLastModifiedWithPageIdentity() {
		[ $fakePage, $fakeRevision ] = $this->getNonExistingPageWithFakeRevision( __METHOD__ );
		$poa = $this->createMock( ParsoidOutputAccess::class );
		$poa->expects( $this->once() )
			->method( 'parse' )
			->willReturnCallback( static function (
				PageRecord $page,
				ParserOptions $parserOpts,
				?RevisionRecord $rev = null
			) use ( $fakePage, $fakeRevision ) {
				self::assertSame( $page, $fakePage, '$page and $fakePage should be the same' );
				self::assertSame( $rev, $fakeRevision, '$rev and $fakeRevision should be the same' );

				$pout = new ParserOutput( self::MOCK_HTML );
				$pout->setCacheRevisionId( $rev ? $rev->getId() : $page->getLatest() );
				$pout->setCacheTime( wfTimestampNow() ); // will use fake time
				return Status::newGood( $pout );
			} );
		$poa->method( 'getParsoidRenderID' )
			->willReturnCallback( static function ( ParserOutput $pout ) {
				return new ParsoidRenderID( 1, $pout->getCacheTime() );
			} );

		$helper = $this->newHelper( null, $poa );
		$helper->init( $fakePage, self::PARAM_DEFAULTS, $this->newUser(), $fakeRevision );
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

		/** @var ParsoidOutputAccess|MockObject $access */
		$access = $this->createNoOpMock( ParsoidOutputAccess::class, [ 'getParserOutput' ] );
		$access->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$helper = $this->newHelper( null, $access );
		$helper->init( $page, self::PARAM_DEFAULTS, $this->newUser() );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
	}

}
