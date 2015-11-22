<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\CreateFromLoginAuthenticationRequest
 */
class CreateFromLoginAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new CreateFromLoginAuthenticationRequest(
			null, array()
		);
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				array(),
				array(),
			),
		);
	}
}
