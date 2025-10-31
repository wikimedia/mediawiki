<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\RememberMeAuthenticationRequest;
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
		$this->assertSame( [], $req->getFieldInfo() );
	}

	public function testNoChoice() {
		$req = new RememberMeAuthenticationRequest(
			RememberMeAuthenticationRequest::ALWAYS_REMEMBER
		);
		$reqWrapper = TestingAccessWrapper::newFromObject( $req );
		$this->assertSame( [], $req->getFieldInfo() );
		$this->assertNotNull( $reqWrapper->expiration );

		$req = new RememberMeAuthenticationRequest(
			RememberMeAuthenticationRequest::NEVER_REMEMBER
		);
		$reqWrapper = TestingAccessWrapper::newFromObject( $req );
		$this->assertSame( [], $req->getFieldInfo() );
		$this->assertNull( $reqWrapper->expiration );
	}

	public function testInvalid() {
		$this->expectException( '\UnexpectedValueException' );
		new RememberMeAuthenticationRequest( 'invalid value' );
	}

	protected function getInstance( array $args = [] ) {
		if ( isset( $args[1] ) ) {
			$req = new RememberMeAuthenticationRequest( $args[1] );
		} else {
			$req = new RememberMeAuthenticationRequest();
		}
		$reqWrapper = TestingAccessWrapper::newFromObject( $req );
		$reqWrapper->expiration = $args[0];
		return $req;
	}

	public static function provideLoadFromSubmission() {
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
			'Empty request (CHOOSE_REMEMBER)' => [
				[ 30 * 24 * 3600, RememberMeAuthenticationRequest::CHOOSE_REMEMBER ],
				[],
				[ 'expiration' => 30 * 24 * 3600, 'rememberMe' => false ]
			],
			'RememberMe present (CHOOSE_REMEMBER)' => [
				[ 30 * 24 * 3600, RememberMeAuthenticationRequest::CHOOSE_REMEMBER ],
				[ 'rememberMe' => '' ],
				[ 'expiration' => 30 * 24 * 3600, 'rememberMe' => true ]
			],
			'RememberMe present but session provider cannot remember (CHOOSE_REMEMBER)' => [
				[ null, RememberMeAuthenticationRequest::CHOOSE_REMEMBER ],
				[ 'rememberMe' => '' ],
				false
			],
			'Empty request (FORCE_CHOOSE_REMEMBER)' => [
				[ 30 * 24 * 3600, RememberMeAuthenticationRequest::FORCE_CHOOSE_REMEMBER ],
				[],
				[ 'expiration' => 30 * 24 * 3600, 'rememberMe' => false, 'skippable' => false ]
			],
			'RememberMe present (FORCE_CHOOSE_REMEMBER)' => [
				[ 30 * 24 * 3600, RememberMeAuthenticationRequest::FORCE_CHOOSE_REMEMBER ],
				[ 'rememberMe' => '' ],
				[ 'expiration' => 30 * 24 * 3600, 'rememberMe' => true, 'skippable' => false ]
			],
			'RememberMe present but session provider cannot remember (FORCE_CHOOSE_REMEMBER)' => [
				[ null, RememberMeAuthenticationRequest::FORCE_CHOOSE_REMEMBER ],
				[ 'rememberMe' => '', 'skippable' => false ],
				false
			],
		];
	}
}
