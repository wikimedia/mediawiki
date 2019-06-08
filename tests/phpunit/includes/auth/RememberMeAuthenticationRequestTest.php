<?php

namespace MediaWiki\Auth;

use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\RememberMeAuthenticationRequest
 */
class RememberMeAuthenticationRequestTest extends AuthenticationRequestTestCase {

	public static function provideGetFieldInfo() {
		return [
			[ [ 1 ] ],
			[ [ null ] ],
		];
	}

	public function testGetFieldInfo_2() {
		$req = new RememberMeAuthenticationRequest();
		$reqWrapper = TestingAccessWrapper::newFromObject( $req );

		$reqWrapper->expiration = 30 * 24 * 3600;
		$this->assertNotEmpty( $req->getFieldInfo() );

		$reqWrapper->expiration = null;
		$this->assertEmpty( $req->getFieldInfo() );
	}

	protected function getInstance( array $args = [] ) {
		$req = new RememberMeAuthenticationRequest();
		$reqWrapper = TestingAccessWrapper::newFromObject( $req );
		$reqWrapper->expiration = $args[0];
		return $req;
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[ 30 * 24 * 3600 ],
				[],
				[ 'expiration' => 30 * 24 * 3600, 'rememberMe' => false ]
			],
			'RememberMe present' => [
				[ 30 * 24 * 3600 ],
				[ 'rememberMe' => '' ],
				[ 'expiration' => 30 * 24 * 3600, 'rememberMe' => true ]
			],
			'RememberMe present but session provider cannot remember' => [
				[ null ],
				[ 'rememberMe' => '' ],
				false
			],
		];
	}
}
