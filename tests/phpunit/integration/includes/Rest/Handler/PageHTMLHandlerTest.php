<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Hook\ParserLogLinterDataHook;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\PageHTMLHandler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\StreamInterface;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;
use WikiPage;

/**
 * @covers \MediaWiki\Rest\Handler\PageHTMLHandler
 * @group Database
 */
class PageHTMLHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use PageHandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '>World</';

	private HashBagOStuff $parserCacheBagOStuff;

	protected function setUp(): void {
		parent::setUp();

		$this->parserCacheBagOStuff = new HashBagOStuff();
		// Protect the ObjectCacheFactory from the service container reset,
		// so the emulated parser cache persists between calls to executeHandler().
		$objectCacheFactory = $this->getServiceContainer()->getObjectCacheFactory();
		$this->setService( 'ObjectCacheFactory', $objectCacheFactory );
	}

	/**
	 * @param Parsoid|MockObject|null $parsoid
	 * @param RevisionRenderer|MockObject|null $renderer
	 *
	 * @return PageHTMLHandler
	 */
	private function newHandler(
		?Parsoid $parsoid = null, ?RevisionRenderer $renderer = null
	): PageHTMLHandler {
		if ( $parsoid ) {
			$this->resetServicesWithMockedParsoid( $parsoid );
		} else {
			// ParserOutputAccess has a localCache which can return stale content.
			// Resetting ensures that ParsoidCachePrewarmJob gets a fresh copy
			// of ParserOutputAccess without these problems!
			$this->resetServices();
		}

		if ( $renderer ) {
			$this->setService( 'RevisionRenderer', $renderer );
		}

		return $this->newPageHtmlHandler();
	}

	public function testExecuteWithHtml() {
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ),
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

	public function testExecuteWillLint() {
		$this->overrideConfigValue( MainConfigNames::ParsoidSettings, [
			'linting' => true
		] );

		$mockHandler = $this->createMock( ParserLogLinterDataHook::class );
		$mockHandler->expects( $this->once() ) // this is the critical assertion in this test case!
		->method( 'onParserLogLinterData' );

		$this->setTemporaryHook(
			'ParserLogLinterData',
			$mockHandler
		);

		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$this->executeHandlerAndGetBodyData( $handler, $request, [
			'format' => 'with_html'
		] );
	}

	public function testExecuteWithHtmlForSystemMessagePage() {
		$title = Title::newFromText( 'MediaWiki:Logouttext' );
		$page = $this->getNonexistingTestPage( $title );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [
			'format' => 'with_html'
		] );

		// Let's create and test on a full HTML document since system message pages
		// will not return a full HTML document by default.
		$data['html'] = ContentUtils::toXML( DOMUtils::parseHTML( $data['html'] ) );

		$this->assertSame( $title->getPrefixedDBkey(), $data['key'] );
		$this->assertSame( $title->getPrefixedText(), $data['title'] );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( '<meta http-equiv', $data['html'] );
		$this->assertStringContainsString( 'content="en"', $data['html'] );

		$msg = wfMessage( 'logouttext' )->inLanguage( 'en' )->useDatabase( false );
		$this->assertStringContainsString( $msg->parse(), $data['html'] );
	}

	public function testExecuteHtmlOnly() {
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ),
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

	public static function provideWikiRedirect() {
		yield 'follow wiki redirects per default' => [ [], 307, null, false ];
		yield 'bad redirect param' => [ [ 'redirect' => 'wrong' ], 400, null, false ];
		yield 'redirect=no' => [ [ 'redirect' => 'no' ], 200, 'Footer' ];
		yield 'redirect=false' => [ [ 'redirect' => 'false' ], 200, 'Footer' ];
		yield 'redirect=true' => [ [ 'redirect' => 'true' ], 307, null, false ];
	}

	/**
	 * @dataProvider provideWikiRedirect
	 */
	public function testWikiRedirect(
		$params, $expectedStatus, $expectedText = null, $allowRenders = true
	) {
		$redirect = $this->getExistingTestPage( 'HtmlEndpointTestPage/redirect' );
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/target' );

		$this->editPage(
			$redirect,
			"#REDIRECT [[{$page->getTitle()->getPrefixedDBkey()}]]\n" .
			"Redirect Footer"
		);

		$request = new RequestData(
			[
				'pathParams' => [ 'title' => $redirect->getTitle()->getPrefixedText() ],
				'queryParams' => $params
			]
		);

		try {
			// If renders should not occur, use a renderer that will throw if called
			$renderer = $allowRenders ? null : $this->createNoOpMock( RevisionRenderer::class );
			$handler = $this->newHandler( null, $renderer );
			$response = $this->executeHandler( $handler, $request, [
				'format' => 'html'
			] );

			$this->assertSame( $expectedStatus, $response->getStatusCode() );

			if ( $expectedText !== null ) {
				$this->assertStringContainsString(
					$expectedText,
					(string)$response->getBody()
				);
			}
		} catch ( HttpException $ex ) {
			$this->assertSame( $expectedStatus, $ex->getCode() );
		}
	}

	public function testExecuteHtmlOnlyForSystemMessagePage() {
		$title = Title::newFromText( 'MediaWiki:Logouttext/de' );
		$page = $this->getNonexistingTestPage( $title );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );

		$htmlResponse = (string)$response->getBody();
		// Let's create and test on a full HTML document since system message pages
		// will not return a full HTML document by default.
		$htmlResponse = ContentUtils::toXML( DOMUtils::parseHTML( $htmlResponse ) );

		$this->assertStringContainsString( '<!DOCTYPE html>', $htmlResponse );
		$this->assertStringContainsString( '<html', $htmlResponse );
		$this->assertStringContainsString( '<meta http-equiv', $htmlResponse );
		$this->assertStringContainsString( 'content="de"', $htmlResponse );

		$msg = wfMessage( 'logouttext' )->inLanguage( 'de' )->useDatabase( false );
		$this->assertStringContainsString( $msg->parse(), $htmlResponse );
	}

	/**
	 * Assert that we return a 404 even if an associated remote file description
	 * page exists (T353688).
	 */
	public function testRemoteDescriptionWithNonexistentFilePage() {
		$name = 'JustSomeSillyFile.png';

		$this->installMockFileRepo( $name );

		$page = $this->getNonexistingTestPage( "File:$name" );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedDBkey() ] ]
		);
		$handler = $this->newHandler();
		$exception = $this->executeHandlerAndGetHttpException( $handler, $request, [
			'format' => 'with_html'
		] );

		$this->assertSame( 404, $exception->getCode() );
	}

	/**
	 * Assert that we return the local page content even if an associated remote
	 * file description page exists (T353688).
	 */
	public function testRemoteDescriptionWithExistingFilePage() {
		$name = 'JustSomeSillyFile.png';

		$this->installMockFileRepo( $name );

		$pageName = "File:$name";
		$this->editPage( $pageName, 'Local content' );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $pageName ] ]
		);
		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [
			'format' => 'with_html'
		] );

		$this->assertSame( $pageName, $data['key'] );
		$this->assertSame( $pageName, $data['title'] );

		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( 'Local content', $data['html'] );
	}

	/**
	 * @dataProvider provideExecuteWithVariant
	 */
	public function testExecuteWithVariant(
		string $format,
		callable $bodyHtmlHandler,
		string $expectedContentLanguage,
		string $expectedVaryHeader
	) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$page = $this->getExistingTestPage( 'HtmlVariantConversion' );
		$this->assertStatusGood( $this->editPage( $page, '<p>test language conversion</p>' ),
			'Edited a page'
		);

		$acceptLanguage = 'en-x-piglatin';
		$request = new RequestData(
			[
				'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ],
				'headers' => [
					'Accept-Language' => $acceptLanguage
				]
			]
		);

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format
		] );

		$htmlBody = $bodyHtmlHandler( $response->getBody() );
		$contentLanguageHeader = $response->getHeaderLine( 'Content-Language' );
		$varyHeader = $response->getHeaderLine( 'Vary' );

		$this->assertStringContainsString( '>esttay anguagelay onversioncay<', $htmlBody );
		$this->assertEquals( $expectedContentLanguage, $contentLanguageHeader );
		$this->assertStringContainsStringIgnoringCase( $expectedVaryHeader, $varyHeader );
		$this->assertStringContainsString( $acceptLanguage, $response->getHeaderLine( 'ETag' ) );
	}

	public static function provideExecuteWithVariant() {
		yield 'with_html request should contain accept language but not content language' => [
			'with_html',
			static function ( StreamInterface $response ) {
				return json_decode( $response->getContents(), true )['html'];
			},
			'',
			'accept-language'
		];

		yield 'html request should contain accept and content language' => [
			'html',
			static function ( StreamInterface $response ) {
				return $response->getContents();
			},
			'en-x-piglatin',
			'accept-language'
		];
	}

	public function testEtagLastModified() {
		$time = time();
		MWTimestamp::setFakeTime( $time );

		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		// First, test it works if nothing was cached yet.
		// Make some time pass since page was created:
		$time += 10;

		// Force the touch time, since it's used for Last-Modified
		// when fetching the current version of a page.
		$page->getTitle()->invalidateCache( $time );

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
	}

	/**
	 * @dataProvider provideHandlesParsoidError
	 */
	public function testHandlesParsoidError(
		Exception $parsoidException,
		Exception $expectedException
	) {
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
		$this->executeHandler( $handler, $request, [ 'format' => 'html' ] );
	}

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
			MainConfigNames::RateLimits, [
				'stashbasehtml' => [
					'&can-bypass' => false,
					'ip' => [ 1, 60 ],
					'newbie' => [ 1, 60 ]
				]
			]
		);

		$page = $this->getExistingTestPage();
		$authority = $this->getAuthority();

		$this->executePageHTMLRequest( $page, [ 'stash' => true ], [], $authority );
		// In this request, the rate limit has been exceeded, so it should throw.
		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 429 );
		$this->executePageHTMLRequest( $page, [ 'stash' => true ], [], $authority );
	}

}
