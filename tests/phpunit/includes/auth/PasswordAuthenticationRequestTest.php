<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\PasswordAuthenticationRequest
 */
class PasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {
	public function provideGetFieldInfo() {
		return array( array( new PasswordAuthenticationRequest() ) );
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				new PasswordAuthenticationRequest(),
				array(),
				null
			),
			array(
				'Username + password',
				new PasswordAuthenticationRequest(),
				$data = array( 'username' => 'User', 'password' => 'Bar' ),
				$data
			),
			array(
				'Username empty',
				new PasswordAuthenticationRequest(),
				array( 'username' => '', 'password' => 'Bar' ),
				null
			),
			array(
				'Password empty',
				new PasswordAuthenticationRequest(),
				array( 'username' => 'User', 'password' => '' ),
				null
			),
		);
	}
}
