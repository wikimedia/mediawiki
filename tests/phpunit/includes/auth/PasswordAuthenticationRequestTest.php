<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\PasswordAuthenticationRequest
 */
class PasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		$ret = new PasswordAuthenticationRequest( $args[0] );
		$ret->action = $args[1];
		return $ret;
	}

	public static function provideGetFieldInfo() {
		return [
			[ [ false, AuthManager::ACTION_LOGIN ] ],
			[ [ true, AuthManager::ACTION_CHANGE ] ],
		];
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request, login' => [
				[ false, AuthManager::ACTION_LOGIN ],
				[],
				false
			],
			'Empty request, change' => [
				[ true, AuthManager::ACTION_CHANGE ],
				[],
				false
			],
			'Username + password, login' => [
				[ false, AuthManager::ACTION_LOGIN ],
				$data = [ 'username' => 'User', 'password' => 'Bar' ],
				$data + [ 'needsRetype' => false, 'action' => AuthManager::ACTION_LOGIN ],
			],
			'Username + password, change' => [
				[ true, AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar' ],
				false
			],
			'Username + password + retype' => [
				[ true, AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz' ],
				[ 'password' => 'Bar', 'retype' => 'baz',
					'needsRetype' => true, 'action' => AuthManager::ACTION_CHANGE ]
			],
			'Username empty, login' => [
				[ false, AuthManager::ACTION_LOGIN ],
				[ 'username' => '', 'password' => 'Bar' ],
				false
			],
			'Username empty, change' => [
				[ true, AuthManager::ACTION_CHANGE ],
				[ 'username' => '', 'password' => 'Bar', 'retype' => 'baz' ],
				[ 'password' => 'Bar', 'retype' => 'baz',
					'needsRetype' => true, 'action' => AuthManager::ACTION_CHANGE ]
			],
			'Password empty, login' => [
				[ false, AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '' ],
				false
			],
			'Password empty, login, with retype' => [
				[ false, AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '', 'retype' => 'baz' ],
				false
			],
			'Retype empty' => [
				[ true, AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => '' ],
				false
			],
		];
	}

	public function testDescribeCredentials() {
		$req = new PasswordAuthenticationRequest;
		$req->username = 'UTSysop';
		$ret = $req->describeCredentials();
		$this->assertInternalType( 'array', $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( 'Message', $ret['provider'] );
		$this->assertSame( 'authmanager-provider-password', $ret['provider']->getKey() );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( 'Message', $ret['account'] );
		$this->assertSame( [ 'UTSysop' ], $ret['account']->getParams() );
	}
}
