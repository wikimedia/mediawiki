<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\SkipResetPasswordAuthenticationRequest
 */
class SkipResetPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new SkipResetPasswordAuthenticationRequest;
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
				'skipped',
				array(),
				array( 'skip' => 'Foobar' ),
				array( 'skip' => true )
			),
		);
	}
}
