<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

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
