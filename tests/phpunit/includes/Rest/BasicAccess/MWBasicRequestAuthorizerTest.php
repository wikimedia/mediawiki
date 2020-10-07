<?php

namespace MediaWiki\Tests\Rest\BasicAccess;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWikiIntegrationTestCase;
use Psr\Container\ContainerInterface;
use User;
use Wikimedia\ObjectFactory;

/**
 * @group Database
 *
 * @covers \MediaWiki\Rest\BasicAccess\BasicAuthorizerBase
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\BasicRequestAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicRequestAuthorizer
 */
class MWBasicRequestAuthorizerTest extends MediaWikiIntegrationTestCase {
	private function createRouter( $userRights, $request ) {
		$user = User::newFromName( 'Test user' );
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$permissionManager = $this->createMock( PermissionManager::class );
		// Don't allow the rights to everybody so that user rights kick in.
		$permissionManager->method( 'isEveryoneAllowed' )->willReturn( false );
		$permissionManager->method( 'userHasRight' )
			->will( $this->returnCallback( function ( $user, $action ) use ( $userRights ) {
				return isset( $userRights[$action] ) && $userRights[$action];
			} ) );

		global $IP;

		return new Router(
			[ "$IP/tests/phpunit/unit/includes/Rest/testRoutes.json" ],
			[],
			'http://wiki.example.com',
			'/rest',
			new \EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new MWBasicAuthorizer( $user, $permissionManager ),
			$objectFactory,
			new Validator( $objectFactory, $permissionManager, $request, $user ),
			$this->createHookContainer()
		);
	}

	public function testReadDenied() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/hello' ) ] );
		$router = $this->createRouter( [ 'read' => false ], $request );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'rest-read-denied', $data['error'] );
	}

	public function testReadAllowed() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/hello' ) ] );
		$router = $this->createRouter( [ 'read' => true ], $request );
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
		$router = $this->createRouter( [ 'read' => true, 'writeapi' => false ], $request );
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
		$router = $this->createRouter( [ 'read' => true, 'writeapi' => true ], $request );
		$response = $router->execute( $request );

		$this->assertSame( 200, $response->getStatusCode() );
	}
}
