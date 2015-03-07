<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\HardResetPasswordAuthenticationRequest
 */
class HardResetPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'MediaWiki\\Auth\\HardResetPasswordAuthenticationRequest';

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
		);
	}
}
