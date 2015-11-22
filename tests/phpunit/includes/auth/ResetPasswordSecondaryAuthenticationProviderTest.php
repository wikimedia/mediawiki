<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider
 */
class ResetPasswordSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$provider = new ResetPasswordSecondaryAuthenticationProvider();

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [] ],
			[ AuthManager::ACTION_CREATE, [] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [] ],
			[ AuthManager::ACTION_REMOVE, [] ],
		];
	}

	public function testBasics() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mb = $this->getMockBuilder( 'MediaWiki\\Auth\\ResetPasswordSecondaryAuthenticationProvider' )
			->setMethods( [ 'tryReset' ] );

		$methods = [
			'beginSecondaryAuthentication',
			'continueSecondaryAuthentication',
			'beginSecondaryAccountCreation',
			'continueSecondaryAccountCreation',
		];
		foreach ( $methods as $method ) {
			$mock = $mb->getMock();
			$mock->expects( $this->once() )->method( 'tryReset' )
				->with( $this->identicalTo( $reqs ) )
				->will( $this->returnValue( $obj ) );
			$this->assertSame( $obj, call_user_func( [ $mock, $method ], $user, $reqs ) );
		}
	}

	public function testTryReset() {
		$user = \User::newFromName( 'UTSysop' );

		$provider = $this->getMockBuilder(
			'MediaWiki\\Auth\\ResetPasswordSecondaryAuthenticationProvider'
		)
			->setMethods( [
				'providerAllowsAuthenticationDataChange', 'providerChangeAuthenticationData'
			] )
			->getMock();
		$provider->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->will( $this->returnCallback( function ( $req ) {
				return $req->allow;
			} ) );
		$provider->expects( $this->any() )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) {
				$req->done = true;
			} ) );
		$config = new \HashConfig( [
			'AuthManagerConfig' => [
				'preauth' => [],
				'primaryauth' => [],
				'secondaryauth' => [
					[ 'factory' => function () use ( $provider ) {
						return $provider;
					} ],
				],
			],
		] );
		$manager = new AuthManager( new \FauxRequest, $config );
		$provider->setManager( $manager );
		$provider = \TestingAccessWrapper::newFromObject( $provider );

		$msg = wfMessage( 'foo' );
		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);
		$passReq = new PasswordAuthenticationRequest( true );
		$passReq->username = 'UTSysop';
		$passReq->password = 'Foo';
		$passReq->retype = 'Bar';
		$passReq->allow = \StatusValue::newGood();
		$passReq->done = false;
		$passReq2 = $this->getMock(
			'MediaWiki\\Auth\\PasswordAuthenticationRequest', [], [ true ]
		);
		$passReq2->username = 'UTSysop';
		$passReq2->password = 'Foo';
		$passReq2->retype = 'Foo';
		$passReq2->allow = \StatusValue::newGood();
		$passReq2->done = false;

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->tryReset( [] )
		);

		$manager->setAuthenticationSessionData( 'reset-pass', 'foo' );
		try {
			$provider->tryReset( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', (object)[] );
		try {
			$provider->tryReset( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass msg is missing', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => 'foo',
		] );
		try {
			$provider->tryReset( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass msg is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
		] );
		try {
			$provider->tryReset( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass hard is missing', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
			'req' => 'foo',
		] );
		try {
			$provider->tryReset( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass req is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( [] );
		$this->assertInstanceOf( 'MediaWiki\\Auth\\AuthenticationResponse', $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( $msg, $res->message );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			'MediaWiki\\Auth\\PasswordAuthenticationRequest',
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq,
		] );
		$res = $provider->tryReset( [] );
		$this->assertInstanceOf( 'MediaWiki\\Auth\\AuthenticationResponse', $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( $msg, $res->message );
		$this->assertCount( 2, $res->neededRequests );
		$this->assertSame( $passReq, $res->neededRequests[0] );
		$this->assertEquals( $skipReq, $res->neededRequests[1] );
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->retype = 'Bad';
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq,
		] );
		$res = $provider->tryReset( [ $skipReq, $passReq ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->retype = 'Bad';
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( [ $skipReq, $passReq ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'badretype', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			'MediaWiki\\Auth\\PasswordAuthenticationRequest',
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->retype = $passReq->password;
		$passReq->allow = \StatusValue::newFatal( 'arbitrary-fail' );
		$res = $provider->tryReset( [ $skipReq, $passReq ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'arbitrary-fail', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			'MediaWiki\\Auth\\PasswordAuthenticationRequest',
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->allow = \StatusValue::newGood();
		$res = $provider->tryReset( [ $skipReq, $passReq ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertTrue( $passReq->done );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq2,
		] );
		$res = $provider->tryReset( [ $passReq2 ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertTrue( $passReq2->done );

		$passReq->done = false;
		$passReq2->done = false;
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq2,
		] );
		$res = $provider->tryReset( [ $passReq ] );
		$this->assertInstanceOf( 'MediaWiki\\Auth\\AuthenticationResponse', $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( $msg, $res->message );
		$this->assertCount( 2, $res->neededRequests );
		$this->assertSame( $passReq2, $res->neededRequests[0] );
		$this->assertEquals( $skipReq, $res->neededRequests[1] );
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );
		$this->assertFalse( $passReq2->done );
	}
}
