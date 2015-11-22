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
			'Empty request, login' => array(
				array( false, AuthManager::ACTION_LOGIN ),
				array(),
				false
			),
			'Empty request, change' => array(
				array( true, AuthManager::ACTION_CHANGE ),
				array(),
				false
			),
			'Username + password, login' => array(
				array( false, AuthManager::ACTION_LOGIN ),
				$data = array( 'username' => 'User', 'password' => 'Bar' ),
				$data + array( 'needsRetype' => false, 'action' => AuthManager::ACTION_LOGIN ),
			),
			'Username + password, change' => array(
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => 'User', 'password' => 'Bar' ),
				false
			),
			'Username + password + retype' => array(
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz' ),
				array( 'password' => 'Bar', 'retype' => 'baz',
					'needsRetype' => true, 'action' => AuthManager::ACTION_CHANGE )
			),
			'Username empty, login' => array(
				array( false, AuthManager::ACTION_LOGIN ),
				array( 'username' => '', 'password' => 'Bar' ),
				false
			),
			'Username empty, change' => array(
				array( true, AuthManager::ACTION_CHANGE ),
				array( 'username' => '', 'password' => 'Bar', 'retype' => 'baz' ),
				array( 'password' => 'Bar', 'retype' => 'baz',
					'needsRetype' => true, 'action' => AuthManager::ACTION_CHANGE )
			),
			'Password empty, login' => array(
				array( false, AuthManager::ACTION_LOGIN ),
				array( 'username' => 'User', 'password' => '' ),
				false
			),
			'Password empty, login, with retype' => array(
				array( false, AuthManager::ACTION_LOGIN ),
				array( 'username' => 'User', 'password' => '', 'retype' => 'baz' ),
				false
			),
			'Retype empty' => array(
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
