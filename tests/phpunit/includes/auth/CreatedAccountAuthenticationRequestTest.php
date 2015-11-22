<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\CreatedAccountAuthenticationRequest
 */
class CreatedAccountAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new CreatedAccountAuthenticationRequest( 42, 'Test' );
	}

	public function testConstructor() {
		$ret = new CreatedAccountAuthenticationRequest( 42, 'Test' );
		$this->assertSame( 42, $ret->id );
		$this->assertSame( 'Test', $ret->username );
	}

	public function provideLoadFromSubmission() {
		return array(
			'Empty request' => array(
				array(),
				array(),
				false
			),
		);
	}
}
