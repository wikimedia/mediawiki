<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Request\WebResponse;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use RequestContext;

/**
 * @covers \MediaWiki\Rest\EntryPoint
 * @covers \MediaWiki\Rest\Router
 */
class EntryPointTest extends \MediaWikiIntegrationTestCase {
	use RestTestTrait;

	private function createRouter( RequestInterface $request ) {
		return $this->newRouter( [
			'request' => $request
		] );
	}

	private function createWebResponse() {
		return $this->getMockBuilder( WebResponse::class )
			->onlyMethods( [ 'header' ] )
			->getMock();
	}

	private function createCorsUtils() {
		$cors = $this->createMock( CorsUtils::class );
		$cors->method( 'modifyResponse' )
			->willReturnArgument( 1 );

		return $cors;
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
		$webResponse = $this->createWebResponse();
		$webResponse->method( 'header' )
			->withConsecutive(
				[ 'HTTP/1.1 200 OK', true, null ],
				[ 'Foo: Bar', true, null ]
			);

		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/EntryPoint/header' ) ] );

		$entryPoint = new EntryPoint(
			RequestContext::getMain(),
			$request,
			$webResponse,
			$this->createRouter( $request ),
			$this->createCorsUtils()
		);
		$entryPoint->execute();
		$this->assertTrue( true );
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
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/EntryPoint/bodyRewind' ) ] );
		$entryPoint = new EntryPoint(
			RequestContext::getMain(),
			$request,
			$this->createWebResponse(),
			$this->createRouter( $request ),
			$this->createCorsUtils()
		);
		ob_start();
		$entryPoint->execute();
		$this->assertSame( 'hello', ob_get_clean() );
	}

}
