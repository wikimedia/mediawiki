<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers SoftResetPasswordAuthenticationRequest
 * @uses HardResetPasswordAuthenticationRequest
 * @uses PasswordAuthenticationRequest
 */
class SoftResetPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'SoftResetPasswordAuthenticationRequest';

	public function provideNewFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				null
			),
			array(
				'Username + password',
				array( 'username' => 'User', 'password' => 'Bar' ),
				null
			),
			array(
				'Username + password + retype',
				$data = array( 'username' => 'User', 'password' => 'Bar', 'retype' => 'Bar' ),
				$data
			),
			array(
				'Username empty',
				array( 'username' => '', 'password' => 'Bar', 'retype' => 'Bar' ),
				null
			),
			array(
				'Password empty',
				array( 'username' => 'User', 'password' => '', 'retype' => 'Bar' ),
				null
			),
			array(
				'Retype empty',
				array( 'username' => 'User', 'password' => 'Bar', 'retype' => '' ),
				null
			),
			array(
				'Username + password + retype + skip',
				array( 'username' => 'User', 'password' => 'Bar', 'retype' => 'Bar', 'skip' => '' ),
				array( 'username' => 'User', 'password' => 'Bar', 'retype' => 'Bar', 'skip' => true ),
			),
			array(
				'Everything except skip empty',
				array( 'username' => '', 'password' => '', 'retype' => '', 'skip' => '' ),
				array( 'username' => '', 'password' => '', 'retype' => '', 'skip' => true ),
			),
		);
	}
}
