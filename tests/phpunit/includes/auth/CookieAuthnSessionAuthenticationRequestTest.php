<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers CookieAuthnSessionAuthenticationRequest
 */
class CookieAuthnSessionAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'CookieAuthnSessionAuthenticationRequest';

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
