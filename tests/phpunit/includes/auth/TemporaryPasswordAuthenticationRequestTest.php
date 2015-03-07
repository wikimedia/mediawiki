<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers TemporaryPasswordAuthenticationRequest
 */
class TemporaryPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'TemporaryPasswordAuthenticationRequest';

	public function testNewRandom() {
		$ret1 = TemporaryPasswordAuthenticationRequest::newRandom();
		$ret2 = TemporaryPasswordAuthenticationRequest::newRandom();
		$this->assertNotSame( '', $ret1->password );
		$this->assertNotSame( '', $ret2->password );
		$this->assertNotSame( $ret1->password, $ret2->password );
	}

	public function testNewInvalid() {
		$ret = TemporaryPasswordAuthenticationRequest::newInvalid();
		$this->assertNull( $ret->password );
	}

	public function provideNewFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				null
			)
		);
	}
}
