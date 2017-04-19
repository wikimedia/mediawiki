<?php

namespace MediaWiki\Auth;

use Wikimedia\TestingAccessWrapper;

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
		$user2 = new \User;
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mb = $this->getMockBuilder( ResetPasswordSecondaryAuthenticationProvider::class )
			->setMethods( [ 'tryReset' ] );

		$methods = [
			'beginSecondaryAuthentication' => [ $user, $reqs ],
			'continueSecondaryAuthentication' => [ $user, $reqs ],
			'beginSecondaryAccountCreation' => [ $user, $user2, $reqs ],
			'continueSecondaryAccountCreation' => [ $user, $user2, $reqs ],
		];
		foreach ( $methods as $method => $args ) {
			$mock = $mb->getMock();
			$mock->expects( $this->once() )->method( 'tryReset' )
				->with( $this->identicalTo( $user ), $this->identicalTo( $reqs ) )
				->will( $this->returnValue( $obj ) );
			$this->assertSame( $obj, call_user_func_array( [ $mock, $method ], $args ) );
		}
	}

	public function testTryReset() {
		$user = \User::newFromName( 'UTSysop' );

		$provider = $this->getMockBuilder(
			ResetPasswordSecondaryAuthenticationProvider::class
		)
			->setMethods( [
				'providerAllowsAuthenticationDataChange', 'providerChangeAuthenticationData'
			] )
			->getMock();
		$provider->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->will( $this->returnCallback( function ( $req ) {
				$this->assertSame( 'UTSysop', $req->username );
				return $req->allow;
			} ) );
		$provider->expects( $this->any() )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) {
				$this->assertSame( 'UTSysop', $req->username );
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
		$provider = TestingAccessWrapper::newFromObject( $provider );

		$msg = wfMessage( 'foo' );
		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);
		$passReq = new PasswordAuthenticationRequest();
		$passReq->action = AuthManager::ACTION_CHANGE;
		$passReq->password = 'Foo';
		$passReq->retype = 'Bar';
		$passReq->allow = \StatusValue::newGood();
		$passReq->done = false;

		$passReq2 = $this->getMockBuilder( PasswordAuthenticationRequest::class )
			->enableProxyingToOriginalMethods()
			->getMock();
		$passReq2->action = AuthManager::ACTION_CHANGE;
		$passReq2->password = 'Foo';
		$passReq2->retype = 'Foo';
		$passReq2->allow = \StatusValue::newGood();
		$passReq2->done = false;

		$passReq3 = new PasswordAuthenticationRequest();
		$passReq3->action = AuthManager::ACTION_LOGIN;
		$passReq3->password = 'Foo';
		$passReq3->retype = 'Foo';
		$passReq3->allow = \StatusValue::newGood();
		$passReq3->done = false;

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->tryReset( $user, [] )
		);

		$manager->setAuthenticationSessionData( 'reset-pass', 'foo' );
		try {
			$provider->tryReset( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', (object)[] );
		try {
			$provider->tryReset( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass msg is missing', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => 'foo',
		] );
		try {
			$provider->tryReset( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass msg is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
		] );
		try {
			$provider->tryReset( $user, [] );
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
			$provider->tryReset( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass req is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq3,
		] );
		try {
			$provider->tryReset( $user, [ $passReq ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'reset-pass req is not valid', $ex->getMessage() );
		}

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( $user, [] );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq,
		] );
		$res = $provider->tryReset( $user, [] );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 2, $res->neededRequests );
		$expectedPassReq = clone $passReq;
		$expectedPassReq->required = AuthenticationRequest::OPTIONAL;
		$this->assertEquals( $expectedPassReq, $res->neededRequests[0] );
		$this->assertEquals( $skipReq, $res->neededRequests[1] );
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->retype = 'Bad';
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq,
		] );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->retype = 'Bad';
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'badretype', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq3 ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->retype = $passReq->password;
		$passReq->allow = \StatusValue::newFatal( 'arbitrary-fail' );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'arbitrary-fail', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );

		$passReq->allow = \StatusValue::newGood();
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertTrue( $passReq->done );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq2,
		] );
		$res = $provider->tryReset( $user, [ $passReq2 ] );
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
		$res = $provider->tryReset( $user, [ $passReq ] );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 2, $res->neededRequests );
		$expectedPassReq = clone $passReq2;
		$expectedPassReq->required = AuthenticationRequest::OPTIONAL;
		$this->assertEquals( $expectedPassReq, $res->neededRequests[0] );
		$this->assertEquals( $skipReq, $res->neededRequests[1] );
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( $passReq->done );
		$this->assertFalse( $passReq2->done );
	}
}
