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
			'Empty request' => array(
				array(),
				array(),
				false
			),
			'Username' => array(
				array(),
				$data = array( 'username' => 'User' ),
				$data,
			),
			'Username empty' => array(
				array(),
				array( 'username' => '' ),
				false
			),
		);
	}
}
