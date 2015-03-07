<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\CreatedAccountAuthenticationRequest
 */
class CreatedAccountAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'MediaWiki\\Auth\\CreatedAccountAuthenticationRequest';

	public function testConstructor() {
		$ret = new CreatedAccountAuthenticationRequest( 42, 'Test' );
		$this->assertSame( 42, $ret->id );
		$this->assertSame( 'Test', $ret->username );
	}

	public function provideNewFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				null
			),
		);
	}
}
