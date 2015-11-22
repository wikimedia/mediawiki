<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\PasswordAuthenticationRequest
 */
class PasswordAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new PasswordAuthenticationRequest( $args[0] );
	}

	public static function provideGetFieldInfo() {
		return array(
			array( array( false ) ),
			array( array( true ) ),
		);
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array( false ),
				array(),
				false
			),
			array(
				'Empty request',
				array( true ),
				array(),
				false
			),
			array(
				'Username + password',
				array( false ),
				$data = array( 'username' => 'User', 'password' => 'Bar' ),
				$data + array( 'needsRetype' => false ),
			),
			array(
				'Username + password',
				array( true ),
				array( 'username' => 'User', 'password' => 'Bar' ),
				false
			),
			array(
				'Username + password + retype',
				array( true ),
				$data = array( 'username' => 'User', 'password' => 'Bar', 'retype' => 'baz' ),
				$data + array( 'needsRetype' => true ),
			),
			array(
				'Username empty',
				array( false ),
				array( 'username' => '', 'password' => 'Bar' ),
				false
			),
			array(
				'Username empty',
				array( true ),
				array( 'username' => '', 'password' => 'Bar', 'retype' => 'baz' ),
				false
			),
			array(
				'Password empty',
				array( false ),
				array( 'username' => 'User', 'password' => '' ),
				false
			),
			array(
				'Password empty',
				array( false ),
				array( 'username' => 'User', 'password' => '', 'retype' => 'baz' ),
				false
			),
			array(
				'Retype empty',
				array( true ),
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
