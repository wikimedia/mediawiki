<?php
/**
 * @group Database
 * @covers SpecialUserlogin
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
	 */
	public function testNoUsername() {
		$ul = new LoginForm();

		$result = $ul->validateUsername( '', 'password', 'test@domain.test' );
		$this->assertEquals( 'noname', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is not possible to create a username that already exists.
	 */
	public function testUserExists() {
		$ul = new LoginForm();

		$result = $ul->validateUsername( 'ThisUserExists', 'password', 'test@domain.test' );
		$this->assertEquals( 'userexists', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is not possible to create a username with a password shorter than the minimum length.
	 */
	public function testPasswordTooShort() {
		$ul = new LoginForm();

		$this->setMwGlobals( 'wgMinimalPasswordLength', 19 );
		$result = $ul->validateUsername( 'Username', 'justthewronglength', 'test@domain.test' );
		$this->assertEquals( 'passwordtooshort', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is not possible to create a username with an invalid email address.
	 */
	public function testInvalidEmail() {
		$ul = new LoginForm();

		$result = $ul->validateUsername( 'Username', 'password', 'invalid' );
		$this->assertEquals( 'invalidemailaddress', $result->getMessage()->getKey() );
	}

	/**
	 * Verifies that it is possible to create an account if everything is in order!
	 */
	public function testSuccess() {
		$ul = new LoginForm();

		$result = $ul->validateUsername( 'Username', 'password', 'test@domain.test');
		$this->assertTrue( $result->isOK() );
	}
}
