<?php
/**
 * @group Database
 */
class SpecialUserloginTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		//Create a dummy user to use to test creation attempts where user already exists
		self::$users = array(
			'sysop' => new TestUser(
				'ThisUserExists',
				'Example User',
				'exampleuser@domain.test',
				array( 'sysop' )
			)
		);
	}

	/**
	 * Verifies that it is not possible to create a username with an empty username.
	 * @covers LoginForm::createAndValidateUsername
	 */
	public function testNoUsername() {
		$result = LoginForm::createAndValidateUsername( '', 'password', 'test@domain.test' );
		$this->assertEquals( 'noname', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is not possible to create a username that already exists.
	 * @covers LoginForm::createAndValidateUsername
	 */
	public function testUserExists() {
		$result = LoginForm::createAndValidateUsername( 'ThisUserExists', 'password', 'test@domain.test' );
		$this->assertEquals( 'userexists', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is not possible to create a username with a password
	 * shorter than the minimum length.
	 * @covers LoginForm::createAndValidateUsername
	 */
	public function testPasswordTooShort() {
		$this->setMwGlobals( 'wgMinimalPasswordLength', 19 );
		$result = LoginForm::createAndValidateUsername( 'Username', 'justthewronglength', 'test@domain.test' );
		$this->assertEquals( 'passwordtooshort', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is not possible to create a username with an invalid email address.
	 * @covers LoginForm::createAndValidateUsername
	 */
	public function testInvalidEmail() {
		$result = LoginForm::createAndValidateUsername( 'Username', 'password', 'invalid' );
		$this->assertEquals( 'invalidemailaddress', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is possible to create an account if everything is in order!
	 * @covers LoginForm::createAndValidateUsername
	 */
	public function testSuccess() {
		$username = 'Username';
		$password = 'password';
		$email = 'test@domain.test';

		$result = LoginForm::createAndValidateUsername( $username, $password, $email );

		//Assert that the result was success
		$this->assertTrue( $result->isOK() );

		// Assert that the username and email address are correct
		// Password is not set by createAndValidateUsername, so cannot be checked here
		$this->assertEquals( $username, $result->getValue()->getName() );
		$this->assertEquals( $email, $result->getValue()->getEmail() );
	}
}
