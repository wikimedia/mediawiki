<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\User\User;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiLogout
 */
class ApiLogoutTest extends ApiTestCase {

	protected function setUp(): void {
		global $wgRequest;

		parent::setUp();

		$user = $this->getTestSysop()->getUser();
		$wgRequest->getSession()->setUser( $user );
		$this->apiContext->setUser( $user );
	}

	public function testUserLogoutBadToken() {
		$user = $this->getTestSysop()->getUser();

		$this->expectApiErrorCode( 'badtoken' );
		try {
			$token = 'invalid token';
			$this->doUserLogout( $token, $user );
		} finally {
			$this->assertTrue( $user->isRegistered(), 'not logged out' );
		}
	}

	public function testUserLogoutAlreadyLoggedOut() {
		$user = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );

		$this->assertFalse( $user->isRegistered() );
		$token = $this->getUserCsrfTokenFromApi( $user );
		$response = $this->doUserLogout( $token, $user )[0];
		$this->assertFalse( $user->isRegistered() );

		$this->assertArrayEquals(
			[ 'warnings' => [ 'logout' => [ 'warnings' => 'You must be logged in.' ] ] ],
			$response
		);
	}

	public function testUserLogout() {
		$user = $this->getTestSysop()->getUser();

		$this->assertTrue( $user->isRegistered() );
		$token = $this->getUserCsrfTokenFromApi( $user );
		$this->doUserLogout( $token, $user );
		$this->assertFalse( $user->isRegistered() );
	}

	public function testUserLogoutWithWebToken() {
		global $wgRequest;

		$user = $this->getTestSysop()->getUser();
		$this->assertTrue( $user->isRegistered() );

		// Logic copied from SkinTemplate.
		$token = $user->getEditToken( 'logoutToken', $wgRequest );

		$this->doUserLogout( $token, $user );
		$this->assertFalse( $user->isRegistered() );
	}

	private function getUserCsrfTokenFromApi( User $user ) {
		$retToken = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => 'csrf'
		], null, false, $user );

		$this->assertArrayNotHasKey( 'warnings', $retToken );

		return $retToken[0]['query']['tokens']['csrftoken'];
	}

	private function doUserLogout( $logoutToken, User $user ) {
		return $this->doApiRequest( [
			'action' => 'logout',
			'token' => $logoutToken
		], null, false, $user );
	}
}
