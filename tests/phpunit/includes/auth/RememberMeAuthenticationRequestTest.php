<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\RememberMeAuthenticationRequest
 */
class RememberMeAuthenticationRequestTest extends AuthenticationRequestTestCase {

	public function testGetFieldInfo_2() {
		$req = new RememberMeAuthenticationRequest;

		$req->expiration = 3600 * 24 * 30;
		$this->assertNotEmpty( $req->getFieldInfo() );

		$req->expiration = 0;
		$this->assertEmpty( $req->getFieldInfo() );
	}

	protected function getInstance( array $args = [] ) {
		// FIXME ensure expiration is non-zero onces SessionManager gets DI
		return new RememberMeAuthenticationRequest;
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[],
				[],
				[ 'rememberMe' => false ]
			],
			'RememberMe present' => [
				[],
				[ 'rememberMe' => '' ],
				[ 'rememberMe' => true ]
			],
		];
	}
}
