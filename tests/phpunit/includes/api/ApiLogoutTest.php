<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiLogout
 */
class ApiLogoutTest extends ApiTestCase {

	protected function setUp() : void {
		global $wgRequest, $wgUser;

		parent::setUp();

		// Link the user to the Session properly so User::doLogout() doesn't complain.
		$wgRequest->getSession()->setUser( $wgUser );
		$wgUser = User::newFromSession( $wgRequest );
		$this->apiContext->setUser( $wgUser );
	}

	public function testUserLogoutBadToken() {
		global $wgUser;

		$this->setExpectedApiException( 'apierror-badtoken' );

		try {
			$token = 'invalid token';
			$this->doUserLogout( $token );
		} finally {
			$this->assertTrue( $wgUser->isLoggedIn(), 'not logged out' );
		}
	}

	public function testUserLogout() {
		global $wgUser;

		$this->assertTrue( $wgUser->isLoggedIn(), 'sanity check' );
		$token = $this->getUserCsrfTokenFromApi();
		$this->doUserLogout( $token );
		$this->assertFalse( $wgUser->isLoggedIn() );
	}

	public function testUserLogoutWithWebToken() {
		global $wgUser, $wgRequest;

		$this->assertTrue( $wgUser->isLoggedIn(), 'sanity check' );

		// Logic copied from SkinTemplate.
		$token = $wgUser->getEditToken( 'logoutToken', $wgRequest );

		$this->doUserLogout( $token );
		$this->assertFalse( $wgUser->isLoggedIn() );
	}

	private function getUserCsrfTokenFromApi() {
		$retToken = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => 'csrf'
		] );

		$this->assertArrayNotHasKey( 'warnings', $retToken );

		return $retToken[0]['query']['tokens']['csrftoken'];
	}

	private function doUserLogout( $logoutToken ) {
		return $this->doApiRequest( [
			'action' => 'logout',
			'token' => $logoutToken
		] );
	}
}
