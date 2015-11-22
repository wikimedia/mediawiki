<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\RememberMeAuthenticationRequest
 */
class RememberMeAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new RememberMeAuthenticationRequest;
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				array(),
				array( 'rememberMe' => false )
			),
			array(
				'RememberMe present',
				array(),
				array( 'rememberMe' => '' ),
				array( 'rememberMe' => true )
			),
		);
	}
}
