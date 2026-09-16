<?php

namespace MediaWiki\Tests\Rest\Handler;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Router;
use MediaWikiIntegrationTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\Rest\Handler\PageHandler
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

	/**
	 * @param string $name
	 * @return array{$handler Handler, $config: array}
	 */
	private function getHandler( string $name ): array {
		switch ( $name ) {
			case 'source':
				return [ $this->newPageHandler(), [ 'prop' => [ 'source' ] ] ];
			case 'with_html':
				return [ $this->newPageHandler(), [ 'prop' => [ 'html' ] ] ];
			case 'bare':
				return [ $this->newPageHandler(), [ 'prop' => [] ] ];
			case 'html':
				return [ $this->newPageHtmlHandler(), [] ];
			case 'history':
				return [ $this->newPageHistoryHandler(), [] ];
			case 'history_count':
				return [ $this->newPageHistoryCountHandler(), [] ];
			case 'links_language':
				return [ $this->newLanguageLinksHandler(), [] ];
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
		[ $handler, $config ] = $this->getHandler( $format );
		$response = $this->executeHandler( $handler, $request, [
			'path' => $path,
			...$config
		] );
		$headerLocation = $response->getHeaderLine( 'location' );

		$this->assertEquals(
			$expectedStatus,
			$response->getStatusCode(),
			"Expected status for $path"
		);

		if ( $hasBodyRedirectTarget && $expectedStatus === 200 ) {
			$body = json_decode( (string)$response->getBody() );
			$this->assertRedirectPath( $path, [ 'title' => $targetPageTitle ], $body->redirect_target );
			$this->assertUrlQueryParameters( $expectedQueryParams, $body->redirect_target );
		}
		if ( $expectedStatus !== 200 ) {
			$this->assertRedirectPath( $path, [ 'title' => $targetPageTitle ], $headerLocation );
			$this->assertUrlQueryParameters( $expectedQueryParams, $headerLocation );
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

	public static function permanentRedirectProvider() {
		$extraQuery = [ 'flavor' => 'edit', 'dummy' => 'test' ];

		yield 'source' => [
			'source',
			'/page/{title}',
		];
		yield 'source, query params' => [
			'source',
			'/page/{title}',
			[],
			$extraQuery,
		];
		yield 'bare' => [
			'bare',
			'/page/{title}/bare',
		];
		yield 'html' => [
			'html',
			'/page/{title}/html',
		];
		yield 'html, query params' => [
			'html',
			'/page/{title}/html',
			[],
			$extraQuery,
		];
		yield 'with_html' => [
			'with_html',
			'/page/{title}/with_html',
		];
		yield 'with_html, query params' => [
			'with_html',
			'/page/{title}/with_html',
			[],
			$extraQuery,
		];
		yield 'history' => [
			'history',
			'/page/{title}/history',
		];
		yield 'history_count' => [
			'history_count',
			'/page/{title}/history/counts/{type}',
			[ 'type' => 'edits' ],
		];
		yield 'links_language' => [
			'links_language',
			'/page/{title}/links/language',
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

		[ $handler, $config ] = $this->getHandler( $format );
		$response = $this->executeHandler( $handler, $request, [
			'path' => $path,
			...$config
		] );
		$headerLocation = $response->getHeaderLine( 'location' );
		$this->assertEquals( 301, $response->getStatusCode(), "Expected status for $path" );

		$targetPathParams = [ 'title' => $page->getTitle()->getPrefixedDBkey() ] + $extraPathParams;
		$this->assertRedirectPath(
			$path, $targetPathParams, $headerLocation
		);

		// A normalization redirect carries the request's query parameters over
		// unchanged, and does not add redirect=no the way a wiki redirect does.
		$this->assertUrlQueryParameters( $queryParams, $headerLocation );

		// Every endpoint's normalization redirect is cacheable: it depends only on
		// how titles are normalized, not on page content. The duration is a tuning
		// decision and deliberately not asserted here.
		$this->assertMatchesRegularExpression(
			'/\bmax-age=[1-9]\d*/',
			$response->getHeaderLine( 'Cache-Control' ),
			"Normalization redirect for $path must be cacheable"
		);
	}

	public static function variantRedirectProvider() {
		// Only the endpoints that follow wiki redirects follow a variant title,
		// and only while redirect following is enabled. Everywhere else the
		// requested title is simply missing.
		yield 'html' => [
			'html',
			'/page/{title}/html',
			[],
			307,
		];
		yield 'with_html' => [
			'with_html',
			'/page/{title}/with_html',
			[],
			307,
		];
		yield 'html, redirect=no' => [
			'html',
			'/page/{title}/html',
			[ 'redirect' => 'no' ],
			404,
		];
		yield 'with_html, redirect=no' => [
			'with_html',
			'/page/{title}/with_html',
			[ 'redirect' => 'no' ],
			404,
		];
		yield 'source' => [
			'source',
			'/page/{title}',
			[],
			404,
		];
		yield 'bare' => [
			'bare',
			'/page/{title}/bare',
			[],
			404,
		];
	}

	/**
	 * A page that exists only under a language variant of the requested title is
	 * reached through a temporary redirect. The redirect is generated for the
	 * requested title, which does not exist, so it has to happen before the
	 * missing-page check.
	 *
	 * @dataProvider variantRedirectProvider
	 */
	public function testVariantRedirect(
		string $format, string $path, array $requestQueryParams, int $expectedStatus
	) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		// "Variant" converts to "Ariantvay" in the en-x-piglatin variant, so a
		// request for the former has to find the latter.
		$variantPage = $this->getExistingTestPage( 'Ariantvay' );
		$requestedTitle = 'Variant';
		$this->getNonexistingTestPage( $requestedTitle );

		$request = new RequestData( [
			'pathParams' => [ 'title' => $requestedTitle ],
			'queryParams' => $requestQueryParams,
		] );

		[ $handler, $config ] = $this->getHandler( $format );
		$routeConfig = [ 'path' => $path, ...$config ];

		if ( $expectedStatus === 404 ) {
			$exception = $this->executeHandlerAndGetHttpException( $handler, $request, $routeConfig );
			$this->assertSame( 404, $exception->getCode(), "Expected status for $path" );
			return;
		}

		$response = $this->executeHandler( $handler, $request, $routeConfig );
		$this->assertSame( $expectedStatus, $response->getStatusCode(), "Expected status for $path" );

		$headerLocation = $response->getHeaderLine( 'location' );
		$this->assertRedirectPath(
			$path, [ 'title' => $variantPage->getTitle()->getPrefixedDBkey() ], $headerLocation
		);
		// Variant redirects are limited to one level of redirection, like wiki
		// redirects are (T389588).
		$this->assertUrlQueryParameters( [ 'redirect' => 'no' ], $headerLocation );
	}

	/**
	 * Assert that a redirect stays on the endpoint the request came in on, with
	 * only the title replaced. Checking the target title alone would not notice a
	 * redirect that lands on a different endpoint, or on no route at all.
	 */
	private function assertRedirectPath(
		string $routePath, array $params, string $actualUrl
	) {
		// '/rest' is the router's root path and 'mock' the module path prefix, as
		// set up by HandlerTestTrait. urlencode() matches its router mock.
		$expected = '/rest/mock' . Router::substPathParams( $routePath, $params );

		$this->assertSame(
			$expected,
			explode( '?', $actualUrl )[0],
			"Redirect for $routePath must stay on the same endpoint"
		);
	}

	/**
	 * @param array $queryParams
	 * @param string $actualUrl
	 *
	 * @return void
	 */
	private function assertUrlQueryParameters( array $queryParams, string $actualUrl ): void {
		if ( preg_match( '/\?(.*?)(#.*)?$/',
			$actualUrl, $m ) ) {
			$urlParameters = wfCgiToArray( $m[1] );
		} else {
			$urlParameters = [];
		}
		$this->assertArrayEquals(
			$queryParams,
			$urlParameters,
			false,
			true,
			"Expected parameters for $actualUrl"
		);
	}
}
