<?php

namespace MediaWiki\Tests\Unit\Rest\BasicAccess;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Reporter\PHPErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @covers \MediaWiki\Rest\BasicAccess\BasicAuthorizerBase
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\BasicRequestAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicRequestAuthorizer
 */
class MWBasicRequestAuthorizerTest extends MediaWikiUnitTestCase {
	private function createRouter( $userRights, $request ) {
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$authority = new SimpleAuthority( new UserIdentityValue( 0, 'Test user' ), $userRights );

		global $IP;

		return new Router(
			[ "$IP/tests/phpunit/unit/includes/Rest/testRoutes.json" ],
			[],
			'http://wiki.example.com',
			'/rest',
			new \EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new MWBasicAuthorizer( $authority ),
			$authority,
			$objectFactory,
			new Validator( $objectFactory, $request, $authority ),
			new PHPErrorReporter(),
			$this->createHookContainer()
		);
	}

	public function testReadDenied() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/hello' ) ] );
		$router = $this->createRouter( [], $request );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'rest-read-denied', $data['error'] );
	}

	public function testReadAllowed() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/hello' ) ] );
		$router = $this->createRouter( [ 'read' ], $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public static function writeHandlerFactory() {
		return new class extends Handler {
			public function needsWriteAccess() {
				return true;
			}

			public function execute() {
				return '';
			}
		};
	}

	public function testWriteDenied() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/MWBasicRequestAuthorizerTest/write' )
		] );
		$router = $this->createRouter( [ 'read' ], $request );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'rest-write-denied', $data['error'] );
	}

	public function testWriteAllowed() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/MWBasicRequestAuthorizerTest/write' )
		] );
		$router = $this->createRouter( [ 'read', 'writeapi' ], $request );
		$response = $router->execute( $request );

		$this->assertSame( 200, $response->getStatusCode() );
	}
}
