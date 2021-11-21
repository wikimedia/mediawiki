<?php

namespace MediaWiki\Tests\Rest\Handler;

use BagOStuff;
use DeferredUpdates;
use EmptyBagOStuff;
use Exception;
use ExtensionRegistry;
use HashBagOStuff;
use HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Rest\Handler\RevisionHTMLHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\RevisionRecord;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @covers \MediaWiki\Rest\Handler\RevisionHTMLHandler
 * @group Database
 */
class RevisionHTMLHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '<p>Hello <b>World</b></p>';

	/** @var int */
	private static $uuidCounter = 0;

	protected function setUp(): void {
		parent::setUp();

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
	 * Checks whether Parsoid extension is installed and skips the test if it's not.
	 */
	private function checkParsoidInstalled() {
		if ( !ExtensionRegistry::getInstance()->isLoaded( 'Parsoid' ) ) {
			$this->markTestSkipped( 'Skip test, since parsoid is not configured' );
		}
	}

	/**
	 * @param BagOStuff|null $cache
	 * @param Parsoid|MockObject|null $parsoid
	 * @return RevisionHTMLHandler
	 * @throws Exception
	 */
	private function newHandler( BagOStuff $cache = null, Parsoid $parsoid = null ): RevisionHTMLHandler {
		$parserCacheFactoryOptions = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, [
			'ParserCacheUseJson' => true,
			'CacheEpoch' => '20200202112233',
			'OldRevisionParserCacheExpireTime' => 60 * 60,
		] );

		$parserCacheFactory = new ParserCacheFactory(
			$cache ?: new EmptyBagOStuff(),
			new WANObjectCache( [ 'cache' => $cache ?: new EmptyBagOStuff() ] ),
			$this->createHookContainer(),
			new JsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$parserCacheFactoryOptions,
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory()
		);

		/** @var GlobalIdGenerator|MockObject $idGenerator */
		$idGenerator = $this->createNoOpMock( GlobalIdGenerator::class, [ 'newUUIDv1' ] );

		$idGenerator->method( 'newUUIDv1' )->willReturnCallback(
			static function () {
				return 'uuid' . ++self::$uuidCounter;
			}
		);

		$handler = new RevisionHTMLHandler(
			new HashConfig( [
				'RightsUrl' => 'https://example.com/rights',
				'RightsText' => 'some rights',
			] ),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getTitleFormatter(),
			$parserCacheFactory,
			$idGenerator,
			$this->getServiceContainer()->getPageStore()
		);

		if ( $parsoid !== null ) {
			$handlerWrapper = TestingAccessWrapper::newFromObject( $handler );
			$helperWrapper = TestingAccessWrapper::newFromObject( $handlerWrapper->htmlHelper );
			$helperWrapper->parsoid = $parsoid;
		}

		return $handler;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		$this->editPage( $page, self::WIKITEXT );
		$revisions['first'] = $page->getRevisionRecord();

		$this->editPage( $page, 'DEAD BEEF' );
		$revisions['latest'] = $page->getRevisionRecord();

		return [ $page, $revisions ];
	}

	public function testExecuteWithHtml() {
		$this->checkParsoidInstalled();
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [
			'format' => 'with_html'
		] );

		$this->assertResponseData( $revisions['first'], $data );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
	}

	public function testExecuteHtmlOnly() {
		$this->checkParsoidInstalled();
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );

		$htmlResponse = (string)$response->getBody();
		$this->assertStringContainsString( '<!DOCTYPE html>', $htmlResponse );
		$this->assertStringContainsString( '<html', $htmlResponse );
		$this->assertStringContainsString( self::HTML, $htmlResponse );
	}

	public function testHtmlIsCached() {
		$this->checkParsoidInstalled();

		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$cache = new HashBagOStuff();
		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willReturn( new PageBundle( 'mocked HTML', null, null, '1.0' ) );

		$handler = $this->newHandler( $cache, $parsoid );
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$htmlResponse = (string)$response->getBody();
		$this->assertStringContainsString( 'mocked HTML', $htmlResponse );

		// check that we can run the test again and ensure that the parse is only run once
		$handler = $this->newHandler( $cache, $parsoid );
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$htmlResponse = (string)$response->getBody();
		$this->assertStringContainsString( 'mocked HTML', $htmlResponse );
	}

	public function testEtagLastModified() {
		$this->checkParsoidInstalled();

		$time = time();
		MWTimestamp::setFakeTime( $time );

		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$cache = new HashBagOStuff();

		// First, test it works if nothing was cached yet.
		// Make some time pass since page was created:
		MWTimestamp::setFakeTime( $time + 10 );
		$handler = $this->newHandler( $cache );
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time + 10 ),
			$response->getHeaderLine( 'Last-Modified' ) );

		$etag = $response->getHeaderLine( 'ETag' );

		// Now, test that headers work when getting from cache too.
		MWTimestamp::setFakeTime( $time + 20 );
		$handler = $this->newHandler( $cache );
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertSame( $etag, $response->getHeaderLine( 'ETag' ) );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time + 10 ),
			$response->getHeaderLine( 'Last-Modified' ) );

		// Now, expire the cache, and assert we are getting a new timestamp back
		MWTimestamp::setFakeTime( $time + 10000 );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $time ) ),
			'Can invalidate cache'
		);
		DeferredUpdates::doUpdates();

		$handler = $this->newHandler( $cache );
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertNotSame( $etag, $response->getHeaderLine( 'ETag' ) );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time + 10000 ),
			$response->getHeaderLine( 'Last-Modified' ) );
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
		$this->checkParsoidInstalled();

		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$handler = $this->newHandler( null, $parsoid );
		$this->expectExceptionObject( $expectedException );
		$this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
	}

	public function testExecute_missingparam() {
		$request = new RequestData();

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "paramvalidator-missingparam", [ 'revision' ] ),
				400
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_error() {
		$request = new RequestData( [ 'pathParams' => [ 'id' => '2076419894' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-revision", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	/**
	 * @param RevisionRecord $rev
	 * @param array $data
	 */
	private function assertResponseData( RevisionRecord $rev, array $data ): void {
		$title = $rev->getPageAsLinkTarget();

		$this->assertSame( $rev->getId(), $data['id'] );
		$this->assertSame( $rev->getSize(), $data['size'] );
		$this->assertSame( $rev->isMinor(), $data['minor'] );
		$this->assertSame(
			wfTimestampOrNull( TS_ISO_8601, $rev->getTimestamp() ),
			$data['timestamp']
		);
		$this->assertSame( $title->getArticleID(), $data['page']['id'] );
		$this->assertSame( $title->getDBkey(), $data['page']['key'] ); // assume main namespace
		$this->assertSame( $title->getText(), $data['page']['title'] ); // assume main namespace
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $data['content_model'] );
		$this->assertSame( 'https://example.com/rights', $data['license']['url'] );
		$this->assertSame( 'some rights', $data['license']['title'] );
		$this->assertSame( $rev->getComment()->text, $data['comment'] );
		$this->assertSame( $rev->getUser()->getId(), $data['user']['id'] );
		$this->assertSame( $rev->getUser()->getName(), $data['user']['name'] );
	}

}
