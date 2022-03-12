<?php

namespace MediaWiki\Tests\Rest;

use EmptyBagOStuff;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Reporter\PHPErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\User\UserIdentityValue;
use Psr\Container\ContainerInterface;
use RequestContext;
use WebResponse;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @covers \MediaWiki\Rest\EntryPoint
 * @covers \MediaWiki\Rest\Router
 */
class EntryPointTest extends \MediaWikiIntegrationTestCase {

	private function createRouter( RequestInterface $request ) {
		global $IP;

		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$user = new UserIdentityValue( 0, __CLASS__ );
		$authority = new UltimateAuthority( $user );

		return new Router(
			[ "$IP/tests/phpunit/unit/includes/Rest/testRoutes.json" ],
			[],
			'http://wiki.example.com',
			'/rest',
			new EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new StaticBasicAuthorizer(),
			$authority,
			$objectFactory,
			new Validator( $objectFactory, $request, $authority ),
			new PHPErrorReporter(),
			$this->createHookContainer()
		);
	}

	private function createWebResponse() {
		return $this->getMockBuilder( WebResponse::class )
			->onlyMethods( [ 'header' ] )
			->getMock();
	}

	private function createCorsUtils() {
		$cors = $this->createMock( CorsUtils::class );
		$cors->method( 'modifyResponse' )
			->will( $this->returnArgument( 1 ) );

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
