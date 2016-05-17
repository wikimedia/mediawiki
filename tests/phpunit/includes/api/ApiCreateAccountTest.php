<?php

/**
 * @group Database
 * @group API
 * @group medium
 *
 * @covers ApiCreateAccount
 */
class ApiCreateAccountTest extends ApiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [ 'wgEnableEmail' => true ] );
	}

	/**
	 * Test the account creation API with a valid request. Also
	 * make sure the new account can log in and is valid.
	 *
	 * This test does multiple API requests so it might end up being
	 * a bit slow. Raise the default timeout.
	 * @group medium
	 */
	public function testValid() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}

		$password = PasswordFactory::generateRandomPasswordString();

		$ret = $this->doApiRequest( [
			'action' => 'createaccount',
			'name' => 'Apitestnew',
			'password' => $password,
			'email' => 'test@domain.test',
			'realname' => 'Test Name'
		] );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['createaccount'] );

		// Should first ask for token.
		$a = $result['createaccount'];
		$this->assertEquals( 'NeedToken', $a['result'] );
		$token = $a['token'];

		// Finally create the account
		$ret = $this->doApiRequest(
			[
				'action' => 'createaccount',
				'name' => 'Apitestnew',
				'password' => $password,
				'token' => $token,
				'email' => 'test@domain.test',
				'realname' => 'Test Name'
			],
			$ret[2]
		);

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertEquals( 'Success', $result['createaccount']['result'] );

		// Try logging in with the new user.
		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => 'Apitestnew',
			'lgpassword' => $password,
		] );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['login'] );

		$a = $result['login']['result'];
		$this->assertEquals( 'NeedToken', $a );
		$token = $result['login']['token'];

		$ret = $this->doApiRequest(
			[
				'action' => 'login',
				'lgtoken' => $token,
				'lgname' => 'Apitestnew',
				'lgpassword' => $password,
			],
			$ret[2]
		);

		$result = $ret[0];

		$this->assertNotInternalType( 'bool', $result );
		$a = $result['login']['result'];

		$this->assertEquals( 'Success', $a );

		// log out to destroy the session
		$ret = $this->doApiRequest(
			[
				'action' => 'logout',
			],
			$ret[2]
		);
		$this->assertEquals( [], $ret[0] );
	}

	/**
	 * Make sure requests with no names are invalid.
	 * @expectedException UsageException
	 */
	public function testNoName() {
		$this->doApiRequest( [
			'action' => 'createaccount',
			'token' => LoginForm::getCreateaccountToken()->toString(),
			'password' => 'password',
		] );
	}

	/**
	 * Make sure requests with no password are invalid.
	 * @expectedException UsageException
	 */
	public function testNoPassword() {
		$this->doApiRequest( [
			'action' => 'createaccount',
			'name' => 'testName',
			'token' => LoginForm::getCreateaccountToken()->toString(),
		] );
	}

	/**
	 * Make sure requests with existing users are invalid.
	 * @expectedException UsageException
	 */
	public function testExistingUser() {
		$this->doApiRequest( [
			'action' => 'createaccount',
			'name' => self::$users['sysop']->getUser()->getName(),
			'token' => LoginForm::getCreateaccountToken()->toString(),
			'password' => 'password',
			'email' => 'test@domain.test',
		] );
	}

	/**
	 * Make sure requests with invalid emails are invalid.
	 * @expectedException UsageException
	 */
	public function testInvalidEmail() {
		$this->doApiRequest( [
			'action' => 'createaccount',
			'name' => 'Test User',
			'token' => LoginForm::getCreateaccountToken()->toString(),
			'password' => 'password',
			'email' => 'invalid',
		] );
	}
}
