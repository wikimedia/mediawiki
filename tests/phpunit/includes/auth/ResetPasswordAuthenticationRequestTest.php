<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\HardResetPasswordAuthenticationRequest
 */
class ResetPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {
	public function provideGetFieldInfo() {
		return array(
			array( new ResetPasswordAuthenticationRequest( true ) ),
			array( new ResetPasswordAuthenticationRequest( false ) ),
		);
	}

	public function provideLoadFromSubmission() {
		return array(
			// hard reset tests
			array(
				'Empty request',
				new ResetPasswordAuthenticationRequest( true ),
				array(),
				null,
			),
			array(
				'Password',
				new ResetPasswordAuthenticationRequest( true ),
				array( 'password' => 'Bar' ),
				null,
			),
			array(
				'Password + retype',
				new ResetPasswordAuthenticationRequest( true ),
				array( 'password' => 'Bar', 'retype' => 'Bar' ),
				array( 'hard' => true, 'username' => null, 'password' => 'Bar', 'retype' => 'Bar' ),
			),
			array(
				'Password empty',
				new ResetPasswordAuthenticationRequest( true ),
				array( 'password' => '', 'retype' => 'Bar' ),
				null,
			),
			array(
				'Retype empty',
				new ResetPasswordAuthenticationRequest( true ),
				array( 'password' => 'Bar', 'retype' => '' ),
				null,
			),

			// soft reset tests
			array(
				'Empty request',
				new ResetPasswordAuthenticationRequest( false ),
				array(),
				null,
			),
			array(
				'Password',
				new ResetPasswordAuthenticationRequest( false ),
				array( 'password' => 'Bar' ),
				null,
			),
			array(
				'Password + retype',
				new ResetPasswordAuthenticationRequest( false ),
				array( 'password' => 'Bar', 'retype' => 'Bar' ),
				array( 'hard' => false, 'username' => null, 'password' => 'Bar', 'retype' => 'Bar' ),
			),
			array(
				'Password empty',
				new ResetPasswordAuthenticationRequest( false ),
				array( 'password' => '', 'retype' => 'Bar' ),
				null,
			),
			array(
				'Retype empty',
				new ResetPasswordAuthenticationRequest( false ),
				array( 'password' => 'Bar', 'retype' => '' ),
				null,
			),
			array(
				'Password + retype + skip',
				new ResetPasswordAuthenticationRequest( false ),
				array( 'password' => 'Bar', 'retype' => 'Bar', 'skip' => '' ),
				array( 'hard' => false, 'username' => null, 'password' => 'Bar', 'retype' => 'Bar',
					'skip' => true ),
			),
			array(
				'Everything except skip empty',
				new ResetPasswordAuthenticationRequest( false ),
				array( 'password' => '', 'retype' => '', 'skip' => '' ),
				array( 'hard' => false, 'username' => null, 'password' => '', 'retype' => '',
					'skip' => true ),
			),
		);
	}
}
