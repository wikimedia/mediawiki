<?php

namespace MediaWiki\Tests\Rest;

use EmptyBagOStuff;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Stream;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use WebResponse;

/**
 * @covers \MediaWiki\Rest\EntryPoint
 * @covers \MediaWiki\Rest\Router
 */
class EntryPointTest extends \MediaWikiUnitTestCase {
	private static $mockHandler;

	private function createRouter() {
		return new Router(
			[ __DIR__ . '/testRoutes.json' ],
			[],
			'/rest',
			new EmptyBagOStuff(),
			new ResponseFactory() );
	}

	private function createWebResponse() {
		return $this->getMockBuilder( WebResponse::class )
			->setMethods( [ 'header' ] )
			->getMock();
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
		$webResponse->expects( $this->any() )
			->method( 'header' )
			->withConsecutive(
				[ 'HTTP/1.1 200 OK', true, null ],
				[ 'Foo: Bar', true, null ]
			);

		$entryPoint = new EntryPoint(
			new RequestData( [ 'uri' => new Uri( '/rest/mock/EntryPoint/header' ) ] ),
			$webResponse,
			$this->createRouter() );
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
		$entryPoint = new EntryPoint(
			new RequestData( [ 'uri' => new Uri( '/rest/mock/EntryPoint/bodyRewind' ) ] ),
			$this->createWebResponse(),
			$this->createRouter() );
		ob_start();
		$entryPoint->execute();
		$this->assertSame( 'hello', ob_get_clean() );
	}

}
