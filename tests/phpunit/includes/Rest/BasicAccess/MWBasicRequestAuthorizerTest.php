<?php

namespace MediaWiki\Tests\Rest\BasicAccess;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\User\UserIdentity;
use MediaWikiTestCase;
use User;

/**
 * @group Database
 *
 * @covers \MediaWiki\Rest\BasicAccess\BasicAuthorizerBase
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\BasicRequestAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicRequestAuthorizer
 */
class MWBasicRequestAuthorizerTest extends MediaWikiTestCase {
	private function createRouter( $userRights ) {
		$user = User::newFromName( 'Test user' );

		$pm = new class( $user, $userRights ) extends PermissionManager {
			private $testUser;
			private $testUserRights;

			public function __construct( $user, $userRights ) {
				$this->testUser = $user;
				$this->testUserRights = $userRights;
			}

			public function userHasRight( UserIdentity $user, $action = '' ) {
				if ( $user === $this->testUser ) {
					return $this->testUserRights[$action] ?? false;
				}
				return parent::userHasRight( $user, $action );
			}
		};

		global $IP;

		return new Router(
			[ "$IP/tests/phpunit/unit/includes/Rest/testRoutes.json" ],
			[],
			'/rest',
			new \EmptyBagOStuff(),
			new ResponseFactory(),
			new MWBasicAuthorizer( $user, $pm ) );
	}

	public function testReadDenied() {
		$router = $this->createRouter( [ 'read' => false ] );
		$request = new RequestData( [ 'uri' => new Uri( '/rest/user/joe/hello' ) ] );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'rest-read-denied', $data['error'] );
	}

	public function testReadAllowed() {
		$router = $this->createRouter( [ 'read' => true ] );
		$request = new RequestData( [ 'uri' => new Uri( '/rest/user/joe/hello' ) ] );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}
}
