<?php

/**
 * @group Database
 * @group API
 */
class ApiCreateAccounTest extends ApiTestCase {
	function setUp() {
		parent::setUp();
		LoginForm::setCreateaccountToken();
	}

	/**
	 * Test the account creation API with a valid request. Also
	 * make sure the new account can log in and is valid.
	 */
	function testValid() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}

		$password = User::randomPassword();

		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'caname' => 'Apitestnew',
			'capassword' => $password,
			'caemail' => 'test@invalid.domain',
			'carealname' => 'Test Name'
		) );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['createaccount'] );

		// Should first ask for token.
		$a = $result['createaccount'];
		$this->assertEquals( 'needtoken', $a['result'] );
		$token = $a['token'];

		// Finally create the account
		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'caname' => 'Apitestnew',
			'capassword' => $password,
			'catoken' => $token,
			'caemail' => 'test@invalid.domain',
			'carealname' => 'Test Name' ), $ret[2]
		);

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertEquals( 'success', $result['createaccount']['result'] );

		// Try logging in with the new user.
		$ret = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => 'Apitestnew',
			'lgpassword' => $password,
			)
		);

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['login'] );

		$a = $result['login']['result'];
		$this->assertEquals( 'NeedToken', $a );
		$token = $result['login']['token'];

		$ret = $this->doApiRequest( array(
			'action' => 'login',
			'lgtoken' => $token,
			'lgname' => 'Apitestnew',
			'lgpassword' => $password,
			), $ret[2]
		);

		$result = $ret[0];

		$this->assertNotInternalType( 'bool', $result );
		$a = $result['login']['result'];

		$this->assertEquals( 'Success', $a );
	}

	/**
	 * Make sure requests with no names are invalid.
	 * @expectedException UsageException
	 */
	function testNoName() {
		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'catoken' => LoginForm::getCreateaccountToken(),
			'capassword' => 'password',
		) );
	}

	/**
	 * Make sure requests with no password are invalid.
	 * @expectedException UsageException
	 */
	function testNoPassword() {
		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'caname' => 'testName',
			'catoken' => LoginForm::getCreateaccountToken(),
		) );
	}

	/**
	 * Make sure requests with existing users are invalid.
	 * @expectedException UsageException
	 */
	function testExistingUser() {
		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'caname' => 'Apitestsysop',
			'catoken' => LoginForm::getCreateaccountToken(),
			'capassword' => 'password',
			'caemail' => 'test@invalid.domain',
		) );
	}

	/**
	 * Make sure requests with invalid emails are invalid.
	 * @expectedException UsageException
	 */
	function testInvalidEmail() {
		$ret = $this->doApiRequest( array(
			'action' => 'createaccount',
			'caname' => 'Test User',
			'catoken' => LoginForm::getCreateaccountToken(),
			'capassword' => 'password',
			'caemail' => 'sjlfsjklj',
		) );
	}
}
