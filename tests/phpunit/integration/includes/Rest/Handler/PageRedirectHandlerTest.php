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
		$this->overrideConfigValue( 'UsePigLatinVariant', true );

		$pageSourceHandler = $this->newPageSourceHandler();
		$pageHtmlHandler = $this->newPageHtmlHandler();
		$this->handlers = [
			'source' => $pageSourceHandler,
			'bare' => $pageSourceHandler,
			'html' => $pageHtmlHandler,
			'with_html' => $pageHtmlHandler,
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

		$this->assertEquals( $expectedStatus, $response->getStatusCode() );
		if ( $hasBodyRedirectTarget && $expectedStatus === 200 ) {
			$body = json_decode( $response->getBody()->getContents() );
			$this->assertStringContainsString( $targetPageTitle, $body->redirect_target );
		}
		if ( $expectedStatus !== 200 ) {
			$this->assertStringContainsString( $targetPageTitle, $response->getHeaderLine( 'location' ) );
		}
	}

	public function temporaryRedirectProvider() {
		yield [
			'source',
			'/page/{title}',
			[],
			307
		];

		yield [
			'source',
			'/page/{title}',
			[ 'redirect' => 'no' ],
			200
		];

		yield [
			'bare',
			'/page/{title}/bare',
			[],
			307
		];

		yield [
			'bare',
			'/page/{title}/bare',
			[ 'redirect' => 'no' ],
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
			[ 'redirect' => 'no' ],
			200
		];
	}

	/**
	 * @dataProvider permanentRedirectProvider
	 */
	public function testPermanentRedirect( $format, $path ) {
		$this->markTestSkippedIfExtensionNotLoaded( 'Parsoid' );
		$page = $this->getExistingTestPage( 'SourceEndpointTestPage with spaces' );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
		);

		$handler = $this->handlers[$format];
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format,
			'path' => $path
		] );

		$this->assertEquals( 301, $response->getStatusCode() );
		$this->assertStringContainsString( $page->getTitle()->getPrefixedDBkey(), $response->getHeaderLine( 'location' ) );
	}

	public function permanentRedirectProvider() {
		yield [ 'source', '/page/{title}' ];
		yield [ 'bare', '/page/{title}/bare' ];
		yield [ 'html', '/page/{title}/html' ];
		yield [ 'with_html', '/page/{title}/with_html' ];
	}
}
