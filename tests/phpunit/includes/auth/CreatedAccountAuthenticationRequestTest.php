<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers CreatedAccountAuthenticationRequest
 * @uses AuthenticationRequest
 */
class CreatedAccountAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'CreatedAccountAuthenticationRequest';

	public function testConstructor() {
		$req = $this->getMock( 'AuthenticationRequest' );
		$ret = new CreatedAccountAuthenticationRequest( 42, 'Test', $req );
		$this->assertSame( 42, $ret->id );
		$this->assertSame( 'Test', $ret->username );
		$this->assertSame( $req, $ret->sessionReq );
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
