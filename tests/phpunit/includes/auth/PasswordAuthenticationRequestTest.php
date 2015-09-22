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

	public function testGetFieldInfo( $args ) {
		parent::testGetFieldInfo( $args );

		$info = $this->getInstance( $args )->getFieldInfo();
		switch ( $args[0] ) {
			case 'login':
				$this->assertArrayNotHasKey( 'retype', $info, 'No need to retype password on login' );
				break;
			case 'create':
				$this->assertArrayHasKey( 'retype', $info, 'Need to retype when creating new password' );
				break;
			case 'change':
				$this->assertArrayHasKey( 'retype', $info, 'Need to retype when changing password' );
				break;
		}

		$loginInfo = (new PasswordAuthenticationRequest( 'login' ))->getFieldInfo();
		$changeInfo = (new PasswordAuthenticationRequest( 'change' ))->getFieldInfo();
		$this->assertNotEquals( $loginInfo['label'], $changeInfo['label'],
			'Change field is differentiated' );
	}

	public static function provideGetFieldInfo() {
		return [
			[ [ 'login', AuthManager::ACTION_LOGIN ] ],
			[ [ 'create', AuthManager::ACTION_CREATE ] ],
			[ [ 'change', AuthManager::ACTION_CHANGE ] ],
		];
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request, login' => [
				[ 'login', AuthManager::ACTION_LOGIN ],
				[],
				false
			],
			'Empty request, change' => [
				[ 'change', AuthManager::ACTION_CHANGE ],
				[],
				false
			],
			'Username + password, login' => [
				[ 'login', AuthManager::ACTION_LOGIN ],
				$data = [ 'username' => 'User', 'password' => 'Bar' ],
				$data + [ 'type' => 'login', 'action' => AuthManager::ACTION_LOGIN ],
			],
			'Username + password, change' => [
				[ 'change', AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar' ],
				false
			],
			'Username + password + retype' => [
				[ 'change', AuthManager::ACTION_CHANGE ],
				[ 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz' ],
				[ 'password' => 'Bar', 'retype' => 'baz',
					'type' => 'change', 'action' => AuthManager::ACTION_CHANGE ]
			],
			'Username empty, login' => [
				[ 'login', AuthManager::ACTION_LOGIN ],
				[ 'username' => '', 'password' => 'Bar' ],
				false
			],
			'Username empty, change' => [
				[ 'change', AuthManager::ACTION_CHANGE ],
				[ 'username' => '', 'password' => 'Bar', 'retype' => 'baz' ],
				[ 'password' => 'Bar', 'retype' => 'baz',
					'type' => 'change', 'action' => AuthManager::ACTION_CHANGE ]
			],
			'Password empty, login' => [
				[ 'login', AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '' ],
				false
			],
			'Password empty, login, with retype' => [
				[ 'login', AuthManager::ACTION_LOGIN ],
				[ 'username' => 'User', 'password' => '', 'retype' => 'baz' ],
				false
			],
			'Retype empty' => [
				[ 'login', AuthManager::ACTION_CHANGE ],
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
