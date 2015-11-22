<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\RememberMeAuthenticationRequest
 */
class RememberMeAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
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
