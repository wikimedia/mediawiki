<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers TokenSessionAuthenticationRequest
 */
class TokenSessionAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'TokenSessionAuthenticationRequest';

	public function provideNewFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				array( 'remember' => false )
			),
			array(
				'Remember checked',
				array( 'remember' => '' ),
				array( 'remember' => true )
			),
		);
	}
}
