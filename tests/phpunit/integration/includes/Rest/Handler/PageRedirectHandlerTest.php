<?php

namespace MediaWiki\Tests\Rest\Handler;

use HashBagOStuff;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\PageSourceHandler
 * @covers \MediaWiki\Rest\Handler\PageHTMLHandler
 * @covers \MediaWiki\Rest\Handler\PageRedirectHandlerTrait
 * @group Database
 */
class PageRedirectHandlerTest extends MediaWikiIntegrationTestCase {
	use PageHandlerTestTrait;
	use HandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '<p>Hello <b>World</b></p>';

	/** @var HashBagOStuff */
	private $parserCacheBagOStuff;

	private $handlers;

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

		$pageSourceHandler = $this->newPageSourceHandler();
		$pageHtmlHandler = $this->newPageHtmlHandler();
		$pageHistoryHandler = $this->newPageHistoryHandler();
		$pageHistoryCountHandler = $this->newPageHistoryCountHandler();
		$languageLinksHandler = $this->newLanguageLinksHandler();
		$this->handlers = [
			'source' => $pageSourceHandler,
			'bare' => $pageSourceHandler,
			'html' => $pageHtmlHandler,
			'with_html' => $pageHtmlHandler,
			'history' => $pageHistoryHandler,
			'history_count' => $pageHistoryCountHandler,
			'links_language' => $languageLinksHandler,
		];
	}

	/**
	 * @dataProvider temporaryRedirectProvider
	 */
	public function testTemporaryRedirect(
		$format, $path, $queryParams, $expectedStatus, $hasBodyRedirectTarget = true
	) {
		$this->markTestSkippedIfExtensionNotLoaded( 'Parsoid' );

		$targetPageTitle = 'PageEndpointTestPage';
		$redirectPageTitle = 'RedirectPage';
		$this->getExistingTestPage( $targetPageTitle );
		$status = $this->editPage( $redirectPageTitle, "#REDIRECT [[$targetPageTitle]]" );
		$this->assertStatusOK( $status );

		$request = new RequestData(
			[
				'pathParams' => [ 'title' => $redirectPageTitle ],
				'queryParams' => $queryParams
			]
		);
		$handler = $this->handlers[$format];
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format,
			'path' => $path,
		] );
		$headerLocation = $response->getHeaderLine( 'location' );

		$this->assertEquals( $expectedStatus, $response->getStatusCode() );
		if ( $hasBodyRedirectTarget && $expectedStatus === 200 ) {
			$body = json_decode( $response->getBody()->getContents() );
			$this->assertStringContainsString( $targetPageTitle, $body->redirect_target );
			$this->assertUrlQueryParameters( $body->redirect_target, $queryParams );
		}
		if ( $expectedStatus !== 200 ) {
			$this->assertStringContainsString( $targetPageTitle, $headerLocation );
			if ( $headerLocation ) {
				$this->assertUrlQueryParameters( $headerLocation, $queryParams );
			}
		}
	}

	public function temporaryRedirectProvider() {
		yield [
			'source',
			'/page/{title}',
			[],
			200
		];

		yield [
			'bare',
			'/page/{title}/bare',
			[],
			200
		];

		yield [
			'html',
			'/page/{title}/html',
			[],
			307,
			false
		];

		yield [
			'html',
			'/page/{title}/html',
			[ 'flavor' => 'edit', 'dummy' => 'test' ],
			307,
			false
		];

		yield [
			'html',
			'/page/{title}/html',
			[ 'redirect' => 'no' ],
			200,
			false
		];

		yield [
			'with_html',
			'/page/{title}/with_html',
			[],
			307,
		];

		yield [
			'with_html',
			'/page/{title}/with_html',
			[ 'flavor' => 'edit', 'dummy' => 'test', 'redirect' => 'no' ],
			200
		];
	}

	/**
	 * @dataProvider permanentRedirectProvider
	 */
	public function testPermanentRedirect( $format, $path, $extraPathParams = [], $queryParams = [] ) {
		$this->markTestSkippedIfExtensionNotLoaded( 'Parsoid' );
		$page = $this->getExistingTestPage( 'SourceEndpointTestPage with spaces' );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Edited a page'
		);

		$pathParams = [ 'title' => $page->getTitle()->getPrefixedText() ] + $extraPathParams;
		$request = new RequestData(
			[
				'pathParams' => $pathParams,
				'queryParams' => $queryParams
			]
		);

		$handler = $this->handlers[$format];
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format,
			'path' => $path
		] );
		$headerLocation = $response->getHeaderLine( 'location' );
		$this->assertEquals( 301, $response->getStatusCode() );
		$this->assertStringContainsString( $page->getTitle()->getPrefixedDBkey(), $headerLocation );
		$this->assertUrlQueryParameters( $headerLocation, $queryParams );
	}

	public function permanentRedirectProvider() {
		yield [ 'source', '/page/{title}', [], [ 'flavor' => 'edit', 'dummy' => 'test' ] ];
		yield [ 'bare', '/page/{title}/bare' ];
		yield [ 'html', '/page/{title}/html' ];
		yield [ 'with_html', '/page/{title}/with_html' ];
		yield [ 'history', '/page/{title}/history' ];
		yield [ 'history_count', '/page/{title}/history/counts/{type}', [ 'type' => 'edits' ] ];
		yield [ 'links_language', '/page/{title}/links/language' ];
	}

	/**
	 * @param string $url
	 * @param array $queryParams
	 * @return void
	 */
	private function assertUrlQueryParameters( string $url, array $queryParams ): void {
		$parsedUrl = $this->getServiceContainer()->getUrlUtils()->parse( $url );
		$urlParameters = [];

		if ( is_array( $parsedUrl ) ) {
			if ( array_key_exists( 'query', $parsedUrl ) ) {
				$urlParameters = wfCgiToArray(
					$parsedUrl['query']
				);
			}
		}
		$this->assertArrayEquals( $queryParams, $urlParameters );
	}
}
