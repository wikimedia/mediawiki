<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\TemporaryPasswordAuthenticationRequest
 */
class TemporaryPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new TemporaryPasswordAuthenticationRequest;
	}

	public function testNewRandom() {
		global $wgPasswordPolicy;

		$this->stashMwGlobals( 'wgPasswordPolicy' );
		$wgPasswordPolicy['policies']['default'] += array(
			'MinimalPasswordLength' => 1,
			'MinimalPasswordLengthToLogin' => 1,
		);

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

	public function provideLoadFromSubmission() {
		return array(
			'Empty request' => array(
				array(),
				array(),
				false
			)
		);
	}
}
