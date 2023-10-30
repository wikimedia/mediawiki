<?php

namespace MediaWiki\Tests\Unit\Rest\BasicAccess;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Tests\Rest\RestTestTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Rest\BasicAccess\BasicAuthorizerBase
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\BasicRequestAuthorizer
 * @covers \MediaWiki\Rest\BasicAccess\MWBasicRequestAuthorizer
 */
class MWBasicRequestAuthorizerTest extends MediaWikiUnitTestCase {
	use RestTestTrait;

	private function createRouter( $userRights, $request ) {
		$authority = new SimpleAuthority( new UserIdentityValue( 0, 'Test user' ), $userRights );

		return $this->newRouter( [
			'basicAuth' => new MWBasicAuthorizer( $authority ),
			'authority' => $authority,
			'request' => $request
		] );
	}

	public function testReadDenied() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' ) ] );
		$router = $this->createRouter( [], $request );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'rest-read-denied', $data['error'] );
	}

	public function testReadAllowed() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' ) ] );
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
			'uri' => new Uri( '/rest/mock/v1/MWBasicRequestAuthorizerTest/write' )
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
			'uri' => new Uri( '/rest/mock/v1/MWBasicRequestAuthorizerTest/write' )
		] );
		$router = $this->createRouter( [ 'read', 'writeapi' ], $request );
		$response = $router->execute( $request );

		$this->assertSame( 200, $response->getStatusCode() );
	}
}
