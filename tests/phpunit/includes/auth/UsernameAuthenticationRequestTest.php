<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\UsernameAuthenticationRequest
 */
class UsernameAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new UsernameAuthenticationRequest();
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[],
				[],
				false
			],
			'Username' => [
				[],
				$data = [ 'username' => 'User' ],
				$data,
			],
			'Username empty' => [
				[],
				[ 'username' => '' ],
				false
			],
		];
	}
}
