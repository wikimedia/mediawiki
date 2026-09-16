<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Content\TextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\Hook\ParserLogLinterDataHook;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\PageHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @covers \MediaWiki\Rest\Handler\PageHandler
 * @group Database
 */
class PageHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use PageHandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '>World</';

	private HashBagOStuff $parserCacheBagOStuff;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
		] );

		$this->parserCacheBagOStuff = new HashBagOStuff();
		// Protect the ObjectCacheFactory from the service container reset,
		// so the emulated parser cache persists between calls to executeHandler().
		$objectCacheFactory = $this->getServiceContainer()->getObjectCacheFactory();
		$this->setService( 'ObjectCacheFactory', $objectCacheFactory );
	}

	private function newHandler(): PageHandler {
		// ParserOutputAccess has a localCache which can return stale content.
		// Resetting ensures that ParsoidCachePrewarmJob gets a fresh copy
		// of ParserOutputAccess without these problems!
		$this->resetServices();

		return $this->newPageHandler();
	}

	public function testExecuteBare() {
		$page = $this->getExistingTestPage( 'Talk:SourceEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$htmlUrl = 'https://wiki.example.com/rest/mock/page/Talk%3ASourceEndpointTestPage%2Fwith%2Fslashes/html';

		$handler = $this->newHandler();
		$config = [ 'prop' => [] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertResponseData( $page, $data );
		$this->assertSame( $htmlUrl, $data['html_url'] );
		$this->assertArrayNotHasKey( 'source', $data );
		$this->assertArrayNotHasKey( 'html', $data );
	}

	public function testExecuteSource() {
		$page = $this->getExistingTestPage( 'Talk:SourceEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [ 'source' ] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		/** @var TextContent $content */
		$content = $page->getRevisionRecord()->getContent( SlotRecord::MAIN );

		$this->assertResponseData( $page, $data );
		$this->assertSame( $content->getText(), $data['source'] );
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
		$config = [ 'prop' => [ 'html' ] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertResponseData( $page, $data );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
		// When HTML is embedded, no html_url link is emitted.
		$this->assertArrayNotHasKey( 'html_url', $data );
	}

	public function testExecuteSourceAndHtml() {
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [ 'source', 'html' ] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		/** @var TextContent $content */
		$content = $page->getRevisionRecord()->getContent( SlotRecord::MAIN );

		$this->assertResponseData( $page, $data );
		// Both source and rendered HTML are present in a single response.
		$this->assertSame( $content->getText(), $data['source'] );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
	}

	public function testETagVariesByOutputProp() {
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ), 'Edited a page' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$etags = [];
		foreach ( [ [], [ 'source' ], [ 'html' ], [ 'source', 'html' ] ] as $prop ) {
			$key = implode( '|', $prop );
			$etags[ $key ] = $this->executeHandler( $this->newHandler(), $request, [ 'prop' => $prop ] )
				->getHeaderLine( 'ETag' );

			$this->assertNotSame( '', $etags[ $key ], "ETag for prop=[$key]" );
		}

		// Each prop combination is a different representation of the page, so no
		// two of them may share an ETag. This covers both sources of the tag: the
		// combinations including html come from the HTML output helper, the ones
		// without it from the content helper.
		$this->assertSameSize(
			array_unique( $etags ),
			$etags,
			'Each prop combination must have its own ETag, got: '
				. json_encode( $etags, JSON_PRETTY_PRINT )
		);
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
			'prop' => [ 'html' ]
		] );
	}

	public function testExecuteRestbaseCompat() {
		$page = $this->getExistingTestPage( 'Talk:SourceEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[
				'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ],
				'headers' => [ 'x-restbase-compat' => 'true' ]
			]
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [] ];
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, $config );

		$this->assertRestbaseCompatibleResponseData( $page, $data );
	}

	/**
	 * A "known" but non-existing page such as a system message page returns a
	 * 404 unless the route opts into the shadow-HTML quirk (allowShadowHtml).
	 */
	public function testExecuteWithHtmlForSystemMessagePage() {
		$title = Title::newFromText( 'MediaWiki:Logouttext' );
		$page = $this->getNonexistingTestPage( $title );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-title", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request, [ 'prop' => [ 'html' ] ] );
	}

	/**
	 * With the allowShadowHtml quirk enabled, a system message page is rendered
	 * as HTML instead of returning a 404 (backwards compatibility for the
	 * /page/{title}/with_html route).
	 */
	public function testExecuteShadowHtmlForSystemMessagePage() {
		$title = Title::newFromText( 'MediaWiki:Logouttext' );
		$this->getNonexistingTestPage( $title );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $title->getPrefixedText() ] ]
		);

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [
			'prop' => [ 'html' ],
			'allowShadowHtml' => true,
		] );

		$this->assertSame( $title->getPrefixedDBkey(), $data['key'] );
		// The shadow page's message content is rendered into the HTML.
		$msg = wfMessage( 'logouttext' )->inLanguage( 'en' )->useDatabase( false );
		$this->assertStringContainsString( $msg->parse(), $data['html'] );
	}

	/**
	 * Assert that we return a 404 even if an associated remote file description
	 * page exists (T353688).
	 */
	public function testRemoteDescriptionWithNonexistentFilePage() {
		$name = 'JustSomeSillyFile.png';

		// A matching remote file exists, but PageHandler 404s a page with no local
		// content before it ever consults the file repo, so don't require findFile().
		$this->installMockFileRepo( $name, expectFindFile: false );

		$page = $this->getNonexistingTestPage( "File:$name" );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedDBkey() ] ]
		);
		$handler = $this->newHandler();
		$exception = $this->executeHandlerAndGetHttpException( $handler, $request, [
			'prop' => [ 'html' ]
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
			'prop' => [ 'html' ]
		] );

		$this->assertSame( $pageName, $data['key'] );
		$this->assertSame( $pageName, $data['title'] );

		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( 'Local content', $data['html'] );
	}

	/**
	 * A remote file description page reports the file it belongs to as its redirect
	 * target. When that is the page being requested, there is no redirect to make,
	 * and reporting one would send the client back to the same URL (T353688).
	 */
	public function testRemoteDescriptionDoesNotRedirectToItself() {
		$name = 'JustSomeSillyFile.png';

		// getRedirected() differs from getName(), so RedirectStore does report a
		// redirect target; it just happens to be the requested page itself.
		$this->installMockFileRepo( $name, redirectedFrom: 'SomeOtherFile.png' );

		$pageName = "File:$name";
		$this->editPage( $pageName, 'Local content' );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $pageName ] ]
		);

		// prop=html follows wiki redirects, so without the guard this would be a 307
		// pointing at $pageName. executeHandlerAndGetBodyData() asserts the 2xx.
		$data = $this->executeHandlerAndGetBodyData( $this->newHandler(), $request, [
			'prop' => [ 'html' ]
		] );

		$this->assertSame( $pageName, $data['key'] );
		$this->assertArrayNotHasKey( 'redirect_target', $data );
	}

	public static function provideWikiRedirectFollowing() {
		// prop config, query params, expected status, whether the body reports redirect_target
		yield 'source: reports target in body, does not follow' => [ [ 'source' ], [], 200, true ];
		yield 'bare: reports target in body, does not follow' => [ [], [], 200, true ];
		yield 'with_html: follows the wiki redirect (307)' => [ [ 'html' ], [], 307, false ];
		yield 'with_html redirect=no: does not follow, reports target' =>
			[ [ 'html' ], [ 'redirect' => 'no' ], 200, true ];
		// "redirect" is a boolean parameter, so it accepts more than just "no".
		yield 'with_html redirect=false: does not follow, reports target' =>
			[ [ 'html' ], [ 'redirect' => 'false' ], 200, true ];
		yield 'with_html redirect=true: follows the wiki redirect (307)' =>
			[ [ 'html' ], [ 'redirect' => 'true' ], 307, false ];
	}

	/**
	 * @dataProvider provideWikiRedirectFollowing
	 *
	 * Wiki redirects are only followed (307) when HTML is served; the metadata
	 * output (source/bare) instead reports the target via redirect_target.
	 */
	public function testWikiRedirectFollowing(
		array $prop, array $queryParams, int $expectedStatus, bool $expectRedirectTarget
	) {
		$target = $this->getExistingTestPage( 'PageHandlerTest/redirect-target' );
		$redirect = $this->getExistingTestPage( 'PageHandlerTest/redirect-source' );
		$this->editPage( $redirect, "#REDIRECT [[{$target->getTitle()->getPrefixedDBkey()}]]" );

		$request = new RequestData( [
			'pathParams' => [ 'title' => $redirect->getTitle()->getPrefixedText() ],
			'queryParams' => $queryParams,
		] );

		$response = $this->executeHandler( $this->newHandler(), $request, [ 'prop' => $prop ] );

		$this->assertSame( $expectedStatus, $response->getStatusCode(), (string)$response->getBody() );
		if ( $expectRedirectTarget ) {
			$data = json_decode( (string)$response->getBody(), true );
			$this->assertArrayHasKey( 'redirect_target', $data );
		}
	}

	public function testPropIsNotAQueryParamWhenPinnedInConfig() {
		// When the route pins "prop", it must not also be exposed as a query param.
		$pinned = $this->newHandler();
		$this->initHandler( $pinned, new RequestData( [] ), [ 'prop' => [ 'source' ] ] );
		$this->assertArrayNotHasKey( 'prop', $pinned->getParamSettings() );

		// When it is not pinned, it is offered as a multi-valued query param.
		$free = $this->newHandler();
		$this->initHandler( $free, new RequestData( [] ), [] );
		$settings = $free->getParamSettings();
		$this->assertArrayHasKey( 'prop', $settings );
		$this->assertSame( 'query', $settings['prop'][ Handler::PARAM_SOURCE ] );
		$this->assertTrue( $settings['prop'][ ParamValidator::PARAM_ISMULTI ] );
		$this->assertSame( [ 'source', 'html' ], $settings['prop'][ ParamValidator::PARAM_TYPE ] );
	}

	public function testPropReadFromQueryParamWhenNotPinned() {
		$page = $this->getExistingTestPage( 'PageHandlerTest/prop-query' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ), 'Edited a page' );

		$request = new RequestData( [
			'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ],
			'queryParams' => [ 'prop' => 'source' ],
		] );

		// No prop pinned in the route config, so it is taken from the query param.
		$data = $this->executeHandlerAndGetBodyData( $this->newHandler(), $request, [] );

		$this->assertArrayHasKey( 'source', $data );
		$this->assertArrayNotHasKey( 'html', $data );
	}

	public static function provideRedirectFollowingByPropQueryParam() {
		// Same URL, different prop: the metadata output reports the redirect,
		// while asking for HTML follows it.
		yield 'prop=source reports the target' => [ 'source', 200 ];
		yield 'prop=html follows the redirect' => [ 'html', 307 ];
		yield 'prop=source|html follows the redirect' => [ 'source|html', 307 ];
	}

	/**
	 * When "prop" is not pinned by the route, it is the query parameter that decides
	 * whether a wiki redirect is followed, because that is tied to serving HTML.
	 *
	 * @dataProvider provideRedirectFollowingByPropQueryParam
	 */
	public function testRedirectFollowingByPropQueryParam( string $prop, int $expectedStatus ) {
		$target = $this->getExistingTestPage( 'PageHandlerTest/prop-redirect-target' );
		$redirect = $this->getExistingTestPage( 'PageHandlerTest/prop-redirect-source' );
		$this->editPage( $redirect, "#REDIRECT [[{$target->getTitle()->getPrefixedDBkey()}]]" );

		$request = new RequestData( [
			'pathParams' => [ 'title' => $redirect->getTitle()->getPrefixedText() ],
			'queryParams' => [ 'prop' => $prop ],
		] );

		// The route supplies a path but pins no prop, so prop comes from the query.
		$response = $this->executeHandler(
			$this->newHandler(), $request, [ 'path' => '/page/{title}' ]
		);

		$this->assertSame( $expectedStatus, $response->getStatusCode(), (string)$response->getBody() );

		if ( $expectedStatus === 200 ) {
			$data = json_decode( (string)$response->getBody(), true );
			$this->assertArrayHasKey( 'redirect_target', $data );
			return;
		}

		// The redirect keeps the requested prop, so following it yields the same
		// representation of the target that was asked for of the redirect.
		$this->assertSame(
			'/rest/mock/page/' . urlencode( $target->getTitle()->getPrefixedDBkey() )
				. '?prop=' . urlencode( $prop ) . '&redirect=no',
			$response->getHeaderLine( 'location' )
		);
	}

	public function testSourceResponseHasConditionalHeaders() {
		$page = $this->getExistingTestPage( 'PageHandlerTest/response-headers' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ), 'Edited a page' );
		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		// A source/bare (non-HTML) response must still carry ETag and
		// Last-Modified validators for conditional requests. This guards against
		// getETag()/getLastModified() reading from an uninitialized content helper.
		$response = $this->executeHandler( $this->newHandler(), $request, [ 'prop' => [ 'source' ] ] );

		$this->assertNotSame( '', $response->getHeaderLine( 'ETag' ) );
		$this->assertNotSame( '', $response->getHeaderLine( 'Last-Modified' ) );
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
		$config = [ 'prop' => [] ];
		$this->executeHandler( $handler, $request, $config );
	}

	public function testExecute_message() {
		$request = new RequestData( [ 'pathParams' => [ 'title' => 'MediaWiki:Ok' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-title", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$config = [ 'prop' => [] ];
		$this->executeHandler( $handler, $request, $config );
	}

	public static function provideUnusableTitle() {
		// Titles that do not address a wiki page at all, as opposed to addressing
		// one that does not exist.
		yield 'unparseable' => [ '::X::' ];
		yield 'underscore only' => [ '_' ];
		yield 'special page' => [ 'Special:Blankpage' ];
	}

	/**
	 * A title that addresses no page is reported as missing, not as forbidden:
	 * there is no page for the permission check to deny access to. This is the
	 * behavior /v1/page/{title} shipped with, see also the equivalent cases in
	 * tests/api-testing/REST/Page.js.
	 *
	 * @dataProvider provideUnusableTitle
	 */
	public function testExecute_unusableTitle( string $title ) {
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$exception = $this->executeHandlerAndGetHttpException(
			$this->newHandler(),
			$request,
			[ 'prop' => [ 'source' ] ]
		);

		$this->assertSame( 404, $exception->getCode(), $exception->getMessage() );
	}

	private function assertResponseData( WikiPage $page, array $data ): void {
		$this->assertSame( $page->getId(), $data['id'] );
		$this->assertSame( $page->getTitle()->getPrefixedDBkey(), $data['key'] );
		$this->assertSame( $page->getTitle()->getPrefixedText(), $data['title'] );
		$this->assertSame( $page->getLatest(), $data['latest']['id'] );
		$this->assertSame(
			wfTimestampOrNull( TS::ISO_8601, $page->getTimestamp() ),
			$data['latest']['timestamp']
		);
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $data['content_model'] );
		$this->assertSame( 'https://example.com/rights', $data['license']['url'] );
		$this->assertSame( 'some rights', $data['license']['title'] );
	}

	private function assertRestbaseCompatibleResponseData( WikiPage $page, array $data ): void {
		$this->assertArrayHasKey( 'items', $data );
		$this->assertSame( $page->getTitle()->getPrefixedDBkey(), $data['items'][0]['title'] );
		$this->assertSame( $page->getId(), $data['items'][0]['page_id'] );
		$this->assertSame( $page->getLatest(), $data['items'][0]['rev'] );
		$this->assertSame( $page->getNamespace(), $data['items'][0]['namespace'] );
		$this->assertSame( $page->getUser(), $data['items'][0]['user_id'] );
		$this->assertSame( $page->getUserText(), $data['items'][0]['user_text'] );
		$this->assertSame(
			wfTimestampOrNull( TS::ISO_8601, $page->getTimestamp() ),
			$data['items'][0]['timestamp']
		);
		$this->assertSame( $page->getComment(), $data['items'][0]['comment'] );
		$this->assertSame( [], $data['items'][0]['tags'] );
		$this->assertSame( [], $data['items'][0]['restrictions'] );
		$this->assertSame(
			$page->getTitle()->getPageLanguage()->getCode(),
			$data['items'][0]['page_language']
		);
		$this->assertSame( $page->isRedirect(), $data['items'][0]['redirect'] );
	}

}
