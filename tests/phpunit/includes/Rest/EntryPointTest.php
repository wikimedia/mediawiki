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

}
