<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Tests\MockEnvironment;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\EntryPoint
 */
class EntryPointTest extends MediaWikiIntegrationTestCase {
	use RestTestTrait;

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::RestPath, '/rest' );
	}

	private function createRouter( RequestInterface $request ) {
		return $this->newRouter( [
			'request' => $request
		] );
	}

	/**
	 * @param RequestData $request
	 * @param MockEnvironment $env
	 *
	 * @return EntryPoint
	 */
	private function getEntryPoint( RequestData $request, MockEnvironment $env ): EntryPoint {
		$entryPoint = new EntryPoint(
			$request,
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);

		$entryPoint->setRouter( $this->createRouter( $request ) );
		return $entryPoint;
	}

	public static function mockHandlerHeader() {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$response->setHeader( 'Foo', 'Bar' );
				return $response;
			}
		};
	}

	public function testHeader() {
		$uri = '/rest/mock/v1/EntryPoint/header';
		$request = new RequestData( [ 'uri' => new Uri( $uri ) ] );

		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		$entryPoint = $this->getEntryPoint(
			$request,
			$env
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();
		$env->assertHeaderValue( 'Bar', 'Foo' );
		$env->assertStatusCode( 200 );
	}

	public static function mockHandlerBodyRewind() {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$stream = new Stream( fopen( 'php://memory', 'w+' ) );
				$stream->write( 'hello' );
				$response->setBody( $stream );
				return $response;
			}
		};
	}

	/**
	 * Make sure EntryPoint rewinds a seekable body stream before reading.
	 */
	public function testBodyRewind() {
		$uri = '/rest/mock/v1/EntryPoint/bodyRewind';
		$request = new RequestData( [ 'uri' => new Uri( $uri ) ] );

		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		$entryPoint = $this->getEntryPoint(
			$request,
			$env
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		// NOTE: MediaWikiEntryPoint::doPostOutputShutdown flushes all output buffers
		$this->assertStringContainsString( 'hello', $entryPoint->getCapturedOutput() );
	}

	public static function provideUnsupportedContentTypeRoutes() {
		return [
			'page creation' => [ '/rest/v1/page' ],
			'wikitext transform' => [ '/rest/v1/transform/wikitext/to/html' ],
		];
	}

	/**
	 * The Content-Type is checked before the read permission. Its value must
	 * be shown literally in the error, not expanded as wikitext, or a reader
	 * of a private wiki could transclude pages into the error.
	 *
	 * @dataProvider provideUnsupportedContentTypeRoutes
	 */
	public function testUnsupportedContentTypeIsNotExpanded( string $uri ) {
		$this->setGroupPermissions( '*', 'read', false );
		$this->overrideConfigValue( MainConfigNames::WhitelistRead, [] );

		$request = new RequestData( [
			'uri' => new Uri( $uri ),
			'method' => 'POST',
			'headers' => [ 'Content-Type' => 'x/{{uc:marker}}' ],
			'bodyContents' => 'a',
		] );
		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		// Use the real router and the core routes.
		$entryPoint = new EntryPoint(
			$request,
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);
		$entryPoint->enableOutputCapture();
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 415 );
		$this->assertStringContainsString( 'x/{{uc:marker}}', $output );
		$this->assertStringNotContainsString( 'MARKER', $output );
	}

	/**
	 * The request method is shown literally in the 405 error, not expanded
	 * as wikitext. This error is also raised before the read permission check.
	 */
	public function testWrongMethodIsNotExpanded() {
		$this->setGroupPermissions( '*', 'read', false );
		$uri = '/rest/v1/page';
		$request = new RequestData( [
			'uri' => new Uri( $uri ),
			'method' => '{{uc:marker}}',
		] );
		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		$entryPoint = new EntryPoint(
			$request,
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);
		$entryPoint->enableOutputCapture();
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		$env->assertStatusCode( 405 );
		$this->assertStringNotContainsString( 'MARKER', $output );
	}

}
