<?php

namespace MediaWiki\Tests\Rest\BasicAccess;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
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
		// Don't allow the rights to everybody so that user rights kick in.
		$this->mergeMwGlobalArrayValue( 'wgGroupPermissions', [ '*' => $userRights ] );
		$this->overrideUserPermissions(
			$user,
			array_keys( array_filter( $userRights ), function ( $value ) {
				return $value === true;
			} )
		);

		global $IP;

		return new Router(
			[ "$IP/tests/phpunit/unit/includes/Rest/testRoutes.json" ],
			[],
			'/rest',
			new \EmptyBagOStuff(),
			new ResponseFactory(),
			new MWBasicAuthorizer( $user, MediaWikiServices::getInstance()->getPermissionManager() ) );
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
		$router = $this->createRouter( [ 'read' => true, 'writeapi' => false ] );
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/MWBasicRequestAuthorizerTest/write' )
		] );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'rest-write-denied', $data['error'] );
	}

	public function testWriteAllowed() {
		$router = $this->createRouter( [ 'read' => true, 'writeapi' => true ] );
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/MWBasicRequestAuthorizerTest/write' )
		] );
		$response = $router->execute( $request );

		$this->assertSame( 200, $response->getStatusCode() );
	}
}
