<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\CreateFromLoginAuthenticationRequest
 */
class CreateFromLoginAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new CreateFromLoginAuthenticationRequest(
			null, []
		);
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[],
				[],
				[],
			],
		];
	}
}
