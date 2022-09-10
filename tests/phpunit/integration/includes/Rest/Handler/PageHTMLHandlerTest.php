<?php

namespace MediaWiki\Tests\Rest\Handler;

use DeferredUpdates;
use Exception;
use ExtensionRegistry;
use HashBagOStuff;
use HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\JsonCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Rest\Handler\PageHTMLHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use WikiPage;

/**
 * @covers \MediaWiki\Rest\Handler\PageHTMLHandler
 * @group Database
 */
class PageHTMLHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '>World</';

	/** @var HashBagOStuff */
	private $parserCacheBagOStuff;

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

		$this->parserCacheBagOStuff = new HashBagOStuff();
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
	 * @param Parsoid|MockObject|null $parsoid
	 *
	 * @return PageHTMLHandler
	 */
	private function newHandler( ?Parsoid $parsoid = null ): PageHTMLHandler {
		$parserCacheFactoryOptions = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, [
			'CacheEpoch' => '20200202112233',
			'OldRevisionParserCacheExpireTime' => 60 * 60,
		] );

		$services = $this->getServiceContainer();
		$parserCacheFactory = new ParserCacheFactory(
			$this->parserCacheBagOStuff,
			new WANObjectCache( [ 'cache' => $this->parserCacheBagOStuff, ] ),
			$this->createHookContainer(),
			new JsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$parserCacheFactoryOptions,
			$services->getTitleFactory(),
			$services->getWikiPageFactory()
		);

		$config = [
			'RightsUrl' => 'https://example.com/rights',
			'RightsText' => 'some rights',
			'ParsoidCacheConfig' =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$parsoidOutputAccess = new ParsoidOutputAccess(
			new ServiceOptions(
				ParsoidOutputAccess::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$parserCacheFactory,
			$services->getRevisionLookup(),
			$services->getGlobalIdGenerator(),
			$services->getStatsdDataFactory(),
			$parsoid ?? new Parsoid(
				$services->get( 'ParsoidSiteConfig' ),
				$services->get( 'ParsoidDataAccess' )
			),
			$services->getParsoidSiteConfig(),
			$services->getParsoidPageConfigFactory()
		);

		$handler = new PageHTMLHandler(
			new HashConfig( $config ),
			$services->getRevisionLookup(),
			$services->getTitleFormatter(),
			$services->getPageStore(),
			$this->getParsoidOutputStash(),
			$services->getStatsdDataFactory(),
			$parsoidOutputAccess
		);

		return $handler;
	}

	public function testExecuteWithHtml() {
		$this->checkParsoidInstalled();
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [
			'format' => 'with_html'
		] );

		$this->assertResponseData( $page, $data );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
	}

	public function testExecuteHtmlOnly() {
		$this->checkParsoidInstalled();
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
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

	public function testEtagLastModified() {
		$this->checkParsoidInstalled();

		$time = time();
		MWTimestamp::setFakeTime( $time );

		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		// First, test it works if nothing was cached yet.
		// Make some time pass since page was created:
		$time += 10;
		MWTimestamp::setFakeTime( $time );
		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$etag = $response->getHeaderLine( 'ETag' );
		$this->assertStringMatchesFormat( '"' . $page->getLatest() . '/%x-%x-%x-%x-%x/%s"', $etag );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time ),
			$response->getHeaderLine( 'Last-Modified' ) );

		// Now, test that headers work when getting from cache too.
		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertSame( $etag, $response->getHeaderLine( 'ETag' ) );
		$etag = $response->getHeaderLine( 'ETag' );
		$this->assertStringMatchesFormat( '"' . $page->getLatest() . '/%x-%x-%x-%x-%x/%s"', $etag );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time ),
			$response->getHeaderLine( 'Last-Modified' ) );

		// Now, expire the cache
		$time += 1000;
		MWTimestamp::setFakeTime( $time );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $time ) ),
			'Can invalidate cache'
		);
		DeferredUpdates::doUpdates();

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertNotSame( $etag, $response->getHeaderLine( 'ETag' ) );
		$etag = $response->getHeaderLine( 'ETag' );
		$this->assertStringMatchesFormat( '"' . $page->getLatest() . '/%x-%x-%x-%x-%x/%s"', $etag );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time ),
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

		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$handler = $this->newHandler( $parsoid );
		$this->expectExceptionObject( $expectedException );
		$this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
	}

	public function testExecute_missingparam() {
		$request = new RequestData();

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "paramvalidator-missingparam", [ 'title' ] ),
				400
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_error() {
		$request = new RequestData( [ 'pathParams' => [ 'title' => 'DoesNotExist8237456assda1234' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-title", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	/**
	 * @param WikiPage $page
	 * @param array $data
	 */
	private function assertResponseData( WikiPage $page, array $data ): void {
		$this->assertSame( $page->getId(), $data['id'] );
		$this->assertSame( $page->getTitle()->getPrefixedDBkey(), $data['key'] );
		$this->assertSame( $page->getTitle()->getPrefixedText(), $data['title'] );
		$this->assertSame( $page->getLatest(), $data['latest']['id'] );
		$this->assertSame(
			wfTimestampOrNull( TS_ISO_8601, $page->getTimestamp() ),
			$data['latest']['timestamp']
		);
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $data['content_model'] );
		$this->assertSame( 'https://example.com/rights', $data['license']['url'] );
		$this->assertSame( 'some rights', $data['license']['title'] );
	}

	/**
	 * Request One:
	 *
	 * When a request is made with no stash entries in the stash and stashing
	 * is set to false, don't stash anything. At this point, the stash is empty.
	 *
	 * Request Two:
	 *
	 * Once a request is made with stashing option set to true, we should have
	 * one entry in parsoid stash. So at this point, the stash is no longer empty
	 * as before.
	 *
	 * Request Three:
	 *
	 * Upon the third request, there is already a stash entry and if the 3rd request's
	 * stashing option is set to false, we're not invalidating the stash entries that
	 * exiting with the UUID. So, if we request a parsoid stashed object from the stash
	 * with a given UUID that exist, we should have a hit.
	 */
	public function testExecuteStashParsoidOutput() {
		$page = $this->getExistingTestPage();
		$outputStash = $this->getParsoidOutputStash();

		[ /* $html1 */, $etag1, $stashKey1 ] = $this->executePageHTMLRequest( $page );
		$this->assertNull( $outputStash->get( $stashKey1 ) );

		[ /* $html2 */, $etag2, $stashKey2 ] = $this->executePageHTMLRequest( $page, [ 'stash' => true ] );
		$this->assertNotNull( $outputStash->get( $stashKey2 ) );

		[ /* $html3 */, $etag3, $stashKey3 ] = $this->executePageHTMLRequest( $page );
		/**
		 * The stash for the previous request should still live at this point.
		 */
		$this->assertNotNull( $outputStash->get( $stashKey2 ) );
		$this->assertNotNull( $outputStash->get( $stashKey3 ) );
		$this->assertSame( $etag1, $etag3 );
		$this->assertNotSame( $etag1, $etag2 );

		// Make sure the output for stashed and unstashed doesn't have the same tag,
		// since it will actually be different!
		// FIXME: implement flavors and write test cases for them.
	}

	public function testETagVariesOnFormat() {
		$page = $this->getExistingTestPage();

		[ /* $html1 */, $etag1 ] =
			$this->executePageHTMLRequest( $page, [], [ 'format' => 'html' ] );

		[ /* $html2 */, $etag2 ] =
			$this->executePageHTMLRequest( $page, [], [ 'format' => 'with_html' ] );

		$this->assertNotSame( $etag1, $etag2 );
	}

	public function testStashingWithRateLimitExceeded() {
		// Set the rate limit to 1 request per minute
		$this->overrideConfigValue(
			MainConfigNames::RateLimits,
			[
				'stashbasehtml' => [
					'&can-bypass' => false,
					'ip' => [ 1, 60 ],
					'newbie' => [ 1, 60 ]
				]
			]
		);

		$page = $this->getExistingTestPage();

		$this->executePageHTMLRequest( $page, [ 'stash' => true ] );
		// In this request, the rate limit has been exceeded, so it should throw.
		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 429 );
		$this->executePageHTMLRequest( $page, [ 'stash' => true ] );
	}

}
