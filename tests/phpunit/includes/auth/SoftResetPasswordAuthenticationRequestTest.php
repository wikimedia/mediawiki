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
				'Password',
				array( 'password' => 'Bar' ),
				null
			),
			array(
				'Password + retype',
				array( 'password' => 'Bar', 'retype' => 'Bar' ),
				array( 'username' => null, 'password' => 'Bar', 'retype' => 'Bar' ),
			),
			array(
				'Password empty',
				array( 'password' => '', 'retype' => 'Bar' ),
				null
			),
			array(
				'Retype empty',
				array( 'password' => 'Bar', 'retype' => '' ),
				null
			),
			array(
				'Password + retype + skip',
				array( 'password' => 'Bar', 'retype' => 'Bar', 'skip' => '' ),
				array( 'username' => null, 'password' => 'Bar', 'retype' => 'Bar', 'skip' => true ),
			),
			array(
				'Everything except skip empty',
				array( 'password' => '', 'retype' => '', 'skip' => '' ),
				array( 'username' => null, 'password' => '', 'retype' => '', 'skip' => true ),
			),
		);
	}
}
