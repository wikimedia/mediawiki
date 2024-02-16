<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\CreationReasonAuthenticationRequest;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\CreationReasonAuthenticationRequest
 */
class CreationReasonAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new CreationReasonAuthenticationRequest();
	}

	public static function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[],
				[],
				false
			],
			'Reason given' => [
				[],
				$data = [ 'reason' => 'Because' ],
				$data,
			],
			'Reason empty' => [
				[],
				[ 'reason' => '' ],
				false
			],
		];
	}
}
