<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\PasswordAuthenticationRequest
 */
class PasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {
	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				null
			),
			array(
				'Username + password',
				$data = array( 'username' => 'User', 'password' => 'Bar' ),
				$data
			),
			array(
				'Username empty',
				array( 'username' => '', 'password' => 'Bar' ),
				null
			),
			array(
				'Password empty',
				array( 'username' => 'User', 'password' => '' ),
				null
			),
		);
	}

	protected function getInstance() {
		return new PasswordAuthenticationRequest();
	}
}
