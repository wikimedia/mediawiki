<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\CreatedAccountAuthenticationRequest
 */
class CreatedAccountAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new CreatedAccountAuthenticationRequest( 42, 'Test' );
	}

	public function testConstructor() {
		$ret = new CreatedAccountAuthenticationRequest( 42, 'Test' );
		$this->assertSame( 42, $ret->id );
		$this->assertSame( 'Test', $ret->username );
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[],
				[],
				false
			],
		];
	}
}
