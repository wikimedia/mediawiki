<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\PasswordDomainAuthenticationRequest
 */
class PasswordDomainAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		$ret = new PasswordDomainAuthenticationRequest( [ 'd1', 'd2' ] );
		$ret->action = $args[0];
		return $ret;
	}

	public static function provideGetFieldInfo() {
		return [
			[ [ AuthManager::ACTION_LOGIN ] ],
			[ [ AuthManager::ACTION_CREATE ] ],
			[ [ AuthManager::ACTION_CHANGE ] ],
			[ [ AuthManager::ACTION_REMOVE ] ],
		];
	}

	public function testGetFieldInfo2() {
		$info = [];
		foreach ( [
			AuthManager::ACTION_LOGIN,
			AuthManager::ACTION_CREATE,
			AuthManager::ACTION_CHANGE,
			AuthManager::ACTION_REMOVE,
		] as $action ) {
			$req = new PasswordDomainAuthenticationRequest( [ 'd1', 'd2' ] );
			$req->action = $action;
			$info[$action] = $req->getFieldInfo();
		}

		$this->assertSame( [], $info[AuthManager::ACTION_REMOVE], 'No data needed to remove' );

		$this->assertArrayNotHasKey( 'retype', $info[AuthManager::ACTION_LOGIN],
			'No need to retype password on login' );
		$this->assertArrayHasKey( 'domain', $info[AuthManager::ACTION_LOGIN],
			'Domain needed on login' );
		$this->assertArrayHasKey( 'retype', $info[AuthManager::ACTION_CREATE],
			'Need to retype when creating new password' );
		$this->assertArrayHasKey( 'domain', $info[AuthManager::ACTION_CREATE],
			'Domain needed on account creation' );
		$this->assertArrayHasKey( 'retype', $info[AuthManager::ACTION_CHANGE],
			'Need to retype when changing password' );
		$this->assertArrayNotHasKey( 'domain', $info[AuthManager::ACTION_CHANGE],
			'Domain not needed on account creation' );

		$this->assertNotEquals(
			$info[AuthManager::ACTION_LOGIN]['password']['label'],
			$info[AuthManager::ACTION_CHANGE]['password']['label'],
			'Password field for change is differentiated from login'
		);
		$this->assertNotEquals(
			$info[AuthManager::ACTION_CREATE]['password']['label'],
			$info[AuthManager::ACTION_CHANGE]['password']['label'],
			'Password field for change is differentiated from create'
		);
		$this->assertNotEquals(
			$info[AuthManager::ACTION_CREATE]['retype']['label'],
			$info[AuthManager::ACTION_CHANGE]['retype']['label'],
			'Retype field for change is differentiated from create'
		);
	}

	public function provideLoadFromSubmission() {
		$domainList = [ 'domainList' => [ 'd1', 'd2' ] ];
		return [
			'Empty request, login' => [
				[ AuthManager::ACTION_LOGIN ],
				[],
				false,
			],
			'Empty request, change' => [
				[ AuthManager::ACTION_CHANGE ],
				[],
				false,
			],
			'Empty request, remove' => [
				[ AuthManager::ACTION_REMOVE ],
				[],
				false,
			],
			'Username + password, login' => [
				[ AuthManager::ACTION_LOGIN ],
				$data = [ 'username' => 'User', 'password' => 'Bar' ],
				false,
			],
			'Username + password + domain, login' => [
				[ AuthManager::ACTION_LOGIN ],
				$data = [ 'username' => 'User', 'password' => 'Bar', 'domain' => 'd1' ],
				$data + [ 'action' => AuthManager::ACTION_LOGIN ] + $domainList,
			],
			'Username + password + bad domain, login' => [
				[ AuthManager::ACTION_LOGIN ],
				$data = [ 'username' => 'User', 'password' => 'Bar', 'domain' => 'd5' ],
				false,
			],
			'Username + password + domain, change' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'domain' => 'd1' ],
				false,
			],
			'Username + password + domain + retype' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz', 'domain' => 'd1' ],
				[ 'password' => 'Bar', 'retype' => 'baz', 'action' => AuthManager::ACTION_CHANGE ] +
					$domainList,
			],
			'Username empty, login' => [
				[ AuthManager::ACTION_LOGIN ],
				[ 'username' => '', 'password' => 'Bar', 'domain' => 'd1' ],
				false,
			],
			'Username empty, change' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => '', 'password' => 'Bar', 'retype' => 'baz', 'domain' => 'd1' ],
				[ 'password' => 'Bar', 'retype' => 'baz', 'action' => AuthManager::ACTION_CHANGE ] +
					$domainList,
			],
			'Password empty, login' => [
				[ AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '', 'domain' => 'd1' ],
				false,
			],
			'Password empty, login, with retype' => [
				[ AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '', 'retype' => 'baz', 'domain' => 'd1' ],
				false,
			],
			'Retype empty' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => '', 'domain' => 'd1' ],
				false,
			],
		];
	}

	public function testDescribeCredentials() {
		$req = new PasswordDomainAuthenticationRequest( [ 'd1', 'd2' ] );
		$req->action = AuthManager::ACTION_LOGIN;
		$req->username = 'UTSysop';
		$req->domain = 'd2';
		$ret = $req->describeCredentials();
		$this->assertIsArray( $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( \Message::class, $ret['provider'] );
		$this->assertSame( 'authmanager-provider-password-domain', $ret['provider']->getKey() );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( \Message::class, $ret['account'] );
		$this->assertSame( 'authmanager-account-password-domain', $ret['account']->getKey() );
		$this->assertSame( [ 'UTSysop', 'd2' ], $ret['account']->getParams() );
	}
}
