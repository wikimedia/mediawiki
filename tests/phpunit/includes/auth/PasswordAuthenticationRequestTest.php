<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\PasswordAuthenticationRequest
 */
class PasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		$ret = new PasswordAuthenticationRequest();
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
			$req = new PasswordAuthenticationRequest();
			$req->action = $action;
			$info[$action] = $req->getFieldInfo();
		}

		$this->assertSame( [], $info[AuthManager::ACTION_REMOVE], 'No data needed to remove' );

		$this->assertArrayNotHasKey( 'retype', $info[AuthManager::ACTION_LOGIN],
			'No need to retype password on login' );
		$this->assertArrayHasKey( 'retype', $info[AuthManager::ACTION_CREATE],
			'Need to retype when creating new password' );
		$this->assertArrayHasKey( 'retype', $info[AuthManager::ACTION_CHANGE],
			'Need to retype when changing password' );

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
				$data + [ 'action' => AuthManager::ACTION_LOGIN ],
			],
			'Username + password, change' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar' ],
				false,
			],
			'Username + password + retype' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz' ],
				[ 'password' => 'Bar', 'retype' => 'baz', 'action' => AuthManager::ACTION_CHANGE ],
			],
			'Username empty, login' => [
				[ AuthManager::ACTION_LOGIN ],
				[ 'username' => '', 'password' => 'Bar' ],
				false,
			],
			'Username empty, change' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => '', 'password' => 'Bar', 'retype' => 'baz' ],
				[ 'password' => 'Bar', 'retype' => 'baz', 'action' => AuthManager::ACTION_CHANGE ],
			],
			'Password empty, login' => [
				[ AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '' ],
				false,
			],
			'Password empty, login, with retype' => [
				[ AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '', 'retype' => 'baz' ],
				false,
			],
			'Retype empty' => [
				[ AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => '' ],
				false,
			],
		];
	}

	public function testDescribeCredentials() {
		$req = new PasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_LOGIN;
		$req->username = 'UTSysop';
		$ret = $req->describeCredentials();
		$this->assertIsArray( $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( \Message::class, $ret['provider'] );
		$this->assertSame( 'authmanager-provider-password', $ret['provider']->getKey() );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( \Message::class, $ret['account'] );
		$this->assertSame( [ 'UTSysop' ], $ret['account']->getParams() );
	}
}
