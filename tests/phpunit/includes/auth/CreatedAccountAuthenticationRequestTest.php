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

	public function provideGetFieldInfo() {
		return array( array( new CreatedAccountAuthenticationRequest( null, null ) ) );
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				new CreatedAccountAuthenticationRequest( null, null ),
				array(),
				null
			),
		);
	}
}
