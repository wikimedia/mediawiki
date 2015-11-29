<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\CreatedAccountAuthenticationRequest
 */
class CreatedAccountAuthenticationRequestTest extends AuthenticationRequestTestCase {
	public function testConstructor() {
		$ret = new CreatedAccountAuthenticationRequest( 42, 'Test' );
		$this->assertSame( 42, $ret->id );
		$this->assertSame( 'Test', $ret->username );
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				null
			),
		);
	}

	protected function getInstance() {
		return new CreatedAccountAuthenticationRequest( null, null );
	}
}
