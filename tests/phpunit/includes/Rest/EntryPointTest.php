<?php

namespace MediaWiki\Tests\Rest;

use EmptyBagOStuff;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Stream;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Psr\Container\ContainerInterface;
use RequestContext;
use WebResponse;
use Wikimedia\ObjectFactory;
use User;

/**
 * @covers \MediaWiki\Rest\EntryPoint
 * @covers \MediaWiki\Rest\Router
 */
class EntryPointTest extends \MediaWikiTestCase {
	private static $mockHandler;

	private function createRouter( RequestInterface $request ) {
		global $IP;

		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$permissionManager = $this->createMock( PermissionManager::class );

		return new Router(
			[ "$IP/tests/phpunit/unit/includes/Rest/testRoutes.json" ],
			[],
			'/rest',
			new EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new StaticBasicAuthorizer(),
			$objectFactory,
			new Validator( $objectFactory, $permissionManager, $request, new User )
		);
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

		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/EntryPoint/header' ) ] );
		$entryPoint = new EntryPoint(
			RequestContext::getMain(),
			$request,
			$webResponse,
			$this->createRouter( $request ) );
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
			$this->createRouter( $request ) );
		ob_start();
		$entryPoint->execute();
		$this->assertSame( 'hello', ob_get_clean() );
	}

}
