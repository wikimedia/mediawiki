<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiLogout
 */
class ApiLogoutTest extends ApiTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function testUserLogoutBadToken() {
		try {
			$token = 'invalid token';
			$retLogout = $this->doUserLogout( $token );
		}
		catch ( ApiUsageException $e ) {
			$exceptionMsg = $e->getMessage();
		}

		$this->assertSame( "Invalid CSRF token.", $exceptionMsg );
	}

	public function testUserLogout() {
		// TODO: there has to be a cleaner way to make User::doLogout happy
		global $wgUser;
		$wgUser = User::newFromId( '127.0.0.1' );

		$token = $this->getUserCsrfTokenFromApi();
		$retLogout = $this->doUserLogout( $token );
		$this->assertFalse( $wgUser->isLoggedIn() );
	}

	public function getUserCsrfTokenFromApi() {
		$retToken = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => 'csrf'
		] );

		$this->assertArrayNotHasKey( 'warnings', $retToken );

		return $retToken[0]['query']['tokens']['csrftoken'];
	}

	public function doUserLogout( $logoutToken ) {
		return $this->doApiRequest( [
			'action' => 'logout',
			'token' => $logoutToken
		] );
	}
}
