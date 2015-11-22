<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\PasswordAuthenticationRequest
 */
class PasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		$ret = new PasswordAuthenticationRequest( $args[0] );
		$ret->action = $args[1];
		return $ret;
	}

	public static function provideGetFieldInfo() {
		return array(
			array( array( false, AuthManager::ACTION_LOGIN ) ),
			array( array( true, AuthManager::ACTION_CHANGE ) ),
		);
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array( false, AuthManager::ACTION_LOGIN ),
				array(),
				false
			),
			array(
				'Empty request',
				array( true, AuthManager::ACTION_CHANGE ),
				array(),
				false
			),
			array(
				'Username + password',
				array( false, AuthManager::ACTION_LOGIN ),
				$data = array( 'username' => 'User', 'password' => 'Bar' ),
				$data + array( 'needsRetype' => false, 'action' => AuthManager::ACTION_LOGIN ),
			),
			array(
				'Username + password',
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => 'User', 'password' => 'Bar' ),
				false
			),
			array(
				'Username + password + retype',
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz' ),
				array( 'password' => 'Bar', 'retype' => 'baz',
					'needsRetype' => true, 'action' => AuthManager::ACTION_CHANGE )
			),
			array(
				'Username empty',
				array( false, AuthManager::ACTION_LOGIN ),
				array( 'username' => '', 'password' => 'Bar' ),
				false
			),
			array(
				'Username empty',
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => '', 'password' => 'Bar', 'retype' => 'baz' ),
				array( 'password' => 'Bar', 'retype' => 'baz',
					'needsRetype' => true, 'action' => AuthManager::ACTION_CHANGE )
			),
			array(
				'Password empty',
				array( false, AuthManager::ACTION_LOGIN ),
				array( 'username' => 'User', 'password' => '' ),
				false
			),
			array(
				'Password empty',
				array( false, AuthManager::ACTION_LOGIN ),
				array( 'username' => 'User', 'password' => '', 'retype' => 'baz' ),
				false
			),
			array(
				'Retype empty',
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => 'User', 'password' => 'Bar', 'retype' => '' ),
				false
			),
		);
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
		$this->assertSame( array( 'UTSysop' ), $ret['account']->getParams() );
	}
}
