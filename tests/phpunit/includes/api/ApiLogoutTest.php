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
		global $wgRequest;

		parent::setUp();

		$user = $this->getTestSysop()->getUser();
		$wgRequest->getSession()->setUser( $user );
		$this->apiContext->setUser( $user );
	}

	public function testUserLogoutBadToken() {
		$user = $this->getTestSysop()->getUser();

		$this->setExpectedApiException( 'apierror-badtoken' );
		try {
			$token = 'invalid token';
			$this->doUserLogout( $token, $user );
		} finally {
			$this->assertTrue( $user->isLoggedIn(), 'not logged out' );
		}
	}

	public function testUserLogout() {
		$user = $this->getTestSysop()->getUser();

		$this->assertTrue( $user->isLoggedIn(), 'sanity check' );
		$token = $this->getUserCsrfTokenFromApi( $user );
		$this->doUserLogout( $token, $user );
		$this->assertFalse( $user->isLoggedIn() );
	}

	public function testUserLogoutWithWebToken() {
		global $wgRequest;

		$user = $this->getTestSysop()->getUser();
		$this->assertTrue( $user->isLoggedIn(), 'sanity check' );

		// Logic copied from SkinTemplate.
		$token = $user->getEditToken( 'logoutToken', $wgRequest );

		$this->doUserLogout( $token, $user );
		$this->assertFalse( $user->isLoggedIn() );
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
