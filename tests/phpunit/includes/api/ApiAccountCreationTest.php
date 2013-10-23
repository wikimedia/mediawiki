<?php

/**
 * @group Database
 * @group API
 * @group medium
 */
class ApiCreateAccountTest extends ApiTestCase {
	function setUp() {
		parent::setUp();
		LoginForm::setCreateaccountToken();
		$this->setMwGlobals( array( 'wgEnableEmail' => true ) );
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

		$password = User::randomPassword();

		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'name' => 'Apitestnew',
			'password' => $password,
			'email' => 'test@domain.test',
			'realname' => 'Test Name'
		) );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['createaccount'] );

		// Should first ask for token.
		$a = $result['createaccount'];
		$this->assertEquals( 'needtoken', $a['result'] );
		$token = $a['token'];

		// Finally create the account
		$ret = $this->doApiRequest(
			array(
				'action' => 'createaccount',
				'name' => 'Apitestnew',
				'password' => $password,
				'token' => $token,
				'email' => 'test@domain.test',
				'realname' => 'Test Name'
			),
			$ret[2]
		);

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertEquals( 'success', $result['createaccount']['result'] );

		// Try logging in with the new user.
		$ret = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => 'Apitestnew',
			'lgpassword' => $password,
		) );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['login'] );

		$a = $result['login']['result'];
		$this->assertEquals( 'NeedToken', $a );
		$token = $result['login']['token'];

		$ret = $this->doApiRequest(
			array(
				'action' => 'login',
				'lgtoken' => $token,
				'lgname' => 'Apitestnew',
				'lgpassword' => $password,
			),
			$ret[2]
		);

		$result = $ret[0];

		$this->assertNotInternalType( 'bool', $result );
		$a = $result['login']['result'];

		$this->assertEquals( 'Success', $a );

		// log out to destroy the session
		$ret = $this->doApiRequest(
			array(
				'action' => 'logout',
			),
			$ret[2]
		);
		$this->assertEquals( array(), $ret[0] );
	}

	/**
	 * Make sure requests with no names are invalid.
	 * @expectedException UsageException
	 */
	public function testNoName() {
		$this->doApiRequest( array(
			'action' => 'createaccount',
			'token' => LoginForm::getCreateaccountToken(),
			'password' => 'password',
		) );
	}

	/**
	 * Make sure requests with no password are invalid.
	 * @expectedException UsageException
	 */
	public function testNoPassword() {
		$this->doApiRequest( array(
			'action' => 'createaccount',
			'name' => 'testName',
			'token' => LoginForm::getCreateaccountToken(),
		) );
	}

	/**
	 * Make sure requests with existing users are invalid.
	 * @expectedException UsageException
	 */
	public function testExistingUser() {
		$this->doApiRequest( array(
			'action' => 'createaccount',
			'name' => 'Apitestsysop',
			'token' => LoginForm::getCreateaccountToken(),
			'password' => 'password',
			'email' => 'test@domain.test',
		) );
	}

	/**
	 * Make sure requests with invalid emails are invalid.
	 * @expectedException UsageException
	 */
	public function testInvalidEmail() {
		$this->doApiRequest( array(
			'action' => 'createaccount',
			'name' => 'Test User',
			'token' => LoginForm::getCreateaccountToken(),
			'password' => 'password',
			'email' => 'invalid',
		) );
	}
}
