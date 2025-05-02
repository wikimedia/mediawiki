<?php

namespace MediaWiki\Tests\Rest\Handler;

use InvalidArgumentException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWikiIntegrationTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\Rest\Handler\PageSourceHandler
 * @covers \MediaWiki\Rest\Handler\PageHTMLHandler
 * @covers \MediaWiki\Rest\Handler\Helper\PageRedirectHelper
 * @group Database
 */
class PageRedirectHandlerTest extends MediaWikiIntegrationTestCase {
	use PageHandlerTestTrait;
	use HandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private HashBagOStuff $parserCacheBagOStuff;

	protected function setUp(): void {
		parent::setUp();

		$this->parserCacheBagOStuff = new HashBagOStuff();
	}

	private function getHandler( $name, RequestInterface $request ) {
		switch ( $name ) {
			case 'source':
			case 'bare':
				return $this->newPageSourceHandler();
			case 'html':
			case 'with_html':
				return $this->newPageHtmlHandler( $request );
			case 'history':
				return $this->newPageHistoryHandler();
			case 'history_count':
				return $this->newPageHistoryCountHandler();
			case 'links_language':
				return $this->newLanguageLinksHandler();
			default:
				throw new InvalidArgumentException( "Unknown handler: $name" );
		}
	}

	/**
	 * @dataProvider temporaryRedirectProvider
	 */
	public function testTemporaryRedirect(
		$format, $path, $requestQueryParams, $expectedQueryParams, $expectedStatus, $hasBodyRedirectTarget = true
	) {
		$targetPageTitle = 'PageEndpointTestPage';
		$redirectPageTitle = 'RedirectPage';
		$this->getExistingTestPage( $targetPageTitle );
		$status = $this->editPage( $redirectPageTitle, "#REDIRECT [[$targetPageTitle]]" );
		$this->assertStatusOK( $status );

		$request = new RequestData(
			[
				'pathParams' => [ 'title' => $redirectPageTitle ],
				'queryParams' => $requestQueryParams
			]
		);
		$handler = $this->getHandler( $format, $request );
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format,
			'path' => $path,
		] );
		$headerLocation = $response->getHeaderLine( 'location' );

		$this->assertEquals( $expectedStatus, $response->getStatusCode() );
		if ( $hasBodyRedirectTarget && $expectedStatus === 200 ) {
			$body = json_decode( $response->getBody()->getContents() );
			$this->assertStringContainsString( $targetPageTitle, $body->redirect_target );
			$this->assertUrlQueryParameters( $body->redirect_target, $expectedQueryParams );
		}
		if ( $expectedStatus !== 200 ) {
			$this->assertStringContainsString( $targetPageTitle, $headerLocation );
			if ( $headerLocation ) {
				$this->assertUrlQueryParameters( $headerLocation, $expectedQueryParams );
			}
		}
	}

	public static function temporaryRedirectProvider() {
		yield [
			'source',
			'/page/{title}',
			[],
			[ 'redirect' => 'no' ],
			200
		];

		yield [
			'bare',
			'/page/{title}/bare',
			[],
			[ 'redirect' => 'no' ],
			200
		];

		yield [
			'html',
			'/page/{title}/html',
			[],
			[ 'redirect' => 'no' ],
			307,
			false
		];

		yield [
			'html',
			'/page/{title}/html',
			[ 'flavor' => 'edit', 'dummy' => 'test' ],
			[ 'redirect' => 'no', 'flavor' => 'edit', 'dummy' => 'test' ],
			307,
			false
		];

		yield [
			'html',
			'/page/{title}/html',
			[ 'redirect' => 'no' ],
			[ 'redirect' => 'no' ],
			200,
			false
		];

		yield [
			'with_html',
			'/page/{title}/with_html',
			[],
			[ 'redirect' => 'no' ],
			307,
		];

		yield [
			'with_html',
			'/page/{title}/with_html',
			[ 'flavor' => 'edit', 'dummy' => 'test', 'redirect' => 'no' ],
			[ 'flavor' => 'edit', 'dummy' => 'test', 'redirect' => 'no' ],
			200
		];
	}

	/**
	 * @dataProvider permanentRedirectProvider
	 */
	public function testPermanentRedirect( $format, $path, $extraPathParams = [], $queryParams = [] ) {
		$page = $this->getExistingTestPage( 'SourceEndpointTestPage with spaces' );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ),
			'Edited a page'
		);

		$pathParams = [ 'title' => $page->getTitle()->getPrefixedText() ] + $extraPathParams;
		$request = new RequestData(
			[
				'pathParams' => $pathParams,
				'queryParams' => $queryParams
			]
		);

		$handler = $this->getHandler( $format, $request );
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format,
			'path' => $path
		] );
		$headerLocation = $response->getHeaderLine( 'location' );
		$this->assertEquals( 301, $response->getStatusCode() );
		$this->assertStringContainsString( $page->getTitle()->getPrefixedDBkey(), $headerLocation );
		$this->assertUrlQueryParameters( $headerLocation, $queryParams );
	}

	public static function permanentRedirectProvider() {
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
		if ( preg_match( '/\?(.*?)(#.*)?$/', $url, $m ) ) {
			$urlParameters = wfCgiToArray( $m[1] );
		} else {
			$urlParameters = [];
		}
		$this->assertArrayEquals( $queryParams, $urlParameters );
	}
}
