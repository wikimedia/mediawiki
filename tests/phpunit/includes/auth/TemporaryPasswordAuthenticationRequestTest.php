<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\TemporaryPasswordAuthenticationRequest
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
		unset( $policy['policies'] );
		$policy['policies']['default'] = [
			'MinimalPasswordLength' => 1,
			'MinimumPasswordLengthToLogin' => 1,
		];

		$this->setMwGlobals( [
			'wgMinimalPasswordLength' => 10,
			'wgPasswordPolicy' => $policy,
		] );

		$ret1 = TemporaryPasswordAuthenticationRequest::newRandom();
		$ret2 = TemporaryPasswordAuthenticationRequest::newRandom();
		$this->assertEquals( 10, strlen( $ret1->password ) );
		$this->assertEquals( 10, strlen( $ret2->password ) );
		$this->assertNotSame( $ret1->password, $ret2->password );

		$policy['policies']['default']['MinimalPasswordLength'] = 15;
		$this->setMwGlobals( 'wgPasswordPolicy', $policy );
		$ret = TemporaryPasswordAuthenticationRequest::newRandom();
		$this->assertEquals( 15, strlen( $ret->password ) );

		$policy['policies']['default']['MinimalPasswordLength'] = [ 'value' => 20 ];
		$this->setMwGlobals( 'wgPasswordPolicy', $policy );
		$ret = TemporaryPasswordAuthenticationRequest::newRandom();
		$this->assertEquals( 20, strlen( $ret->password ) );
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
		$this->assertIsArray( $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( \Message::class, $ret['provider'] );
		$this->assertSame( 'authmanager-provider-temporarypassword', $ret['provider']->getKey() );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( \Message::class, $ret['account'] );
		$this->assertSame( [ 'UTSysop' ], $ret['account']->getParams() );
	}
}
