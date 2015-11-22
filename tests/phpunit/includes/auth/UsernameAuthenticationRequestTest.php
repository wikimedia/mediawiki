<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\UsernameAuthenticationRequest
 */
class UsernameAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new UsernameAuthenticationRequest();
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				array(),
				false
			),
			array(
				'Username',
				array(),
				$data = array( 'username' => 'User' ),
				$data,
			),
			array(
				'Username empty',
				array(),
				array( 'username' => '' ),
				false
			),
		);
	}
}
