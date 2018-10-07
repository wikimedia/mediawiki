<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\TemporaryPasswordAuthenticationRequest
 */
class TemporaryPasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		$ret = new TemporaryPasswordAuthenticationRequest;
		$ret->action = $args[0];
		return $ret;
	}

	public static function provideGetFieldInfo() {
		return [
			[ [ AuthManager::ACTION_CREATE ] ],
			[ [ AuthManager::ACTION_CHANGE ] ],
			[ [ AuthManager::ACTION_REMOVE ] ],
		];
	}

	public function testNewRandom() {
		global $wgPasswordPolicy;

		$policy = $wgPasswordPolicy;
		$policy['policies']['default'] += [
			'MinimalPasswordLength' => 1,
			'MinimalPasswordLengthToLogin' => 1,
		];

		$this->setMwGlobals( 'wgPasswordPolicy', $policy );

		$ret1 = TemporaryPasswordAuthenticationRequest::newRandom();
		$ret2 = TemporaryPasswordAuthenticationRequest::newRandom();
		$this->assertNotSame( '', $ret1->password );
		$this->assertNotSame( '', $ret2->password );
		$this->assertNotSame( $ret1->password, $ret2->password );
	}

	public function testNewInvalid() {
		$ret = TemporaryPasswordAuthenticationRequest::newInvalid();
		$this->assertNull( $ret->password );
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[ AuthManager::ACTION_REMOVE ],
				[],
				false,
			],
			'Create, empty request' => [
				[ AuthManager::ACTION_CREATE ],
				[],
				false,
			],
			'Create, mailpassword set' => [
				[ AuthManager::ACTION_CREATE ],
				[ 'mailpassword' => 1 ],
				[ 'mailpassword' => true, 'action' => AuthManager::ACTION_CREATE ],
			],
		];
	}

	public function testDescribeCredentials() {
		$req = new TemporaryPasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_LOGIN;
		$req->username = 'UTSysop';
		$ret = $req->describeCredentials();
		$this->assertInternalType( 'array', $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( \Message::class, $ret['provider'] );
		$this->assertSame( 'authmanager-provider-temporarypassword', $ret['provider']->getKey() );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( \Message::class, $ret['account'] );
		$this->assertSame( [ 'UTSysop' ], $ret['account']->getParams() );
	}
}
