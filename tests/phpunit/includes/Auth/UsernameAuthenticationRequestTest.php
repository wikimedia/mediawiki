<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\UsernameAuthenticationRequest;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\UsernameAuthenticationRequest
 */
class UsernameAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new UsernameAuthenticationRequest();
	}

	public static function provideLoadFromSubmission() {
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
