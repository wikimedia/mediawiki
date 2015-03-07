<?php

/**
 * @group AuthManager
 * @covers ResetPasswordSecondaryAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 * @uses AuthManager
 * @uses NullAuthnSession
 * @uses AuthnSession
 */
class ResetPasswordSecondaryAuthenticationProviderTest extends MediaWikiTestCase {

	private $manager = null;
	private $config = null;
	private $primary = null;

	/**
	 * Get an instance of the provider
	 * @return ResetPasswordSecondaryAuthenticationProvider
	 */
	protected function getProvider() {
		$that = $this;

		$sessionProvider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$sessionProvider->method( 'provideAuthnSession' )->willReturn( new NullAuthnSession() );

		$this->resetPrimary();

		if ( !$this->config ) {
			$this->config = new HashConfig( array(
				'AuthManagerConfig' => array(
					'sessionstore' => array(),
					'session' => array(
						array(
							'factory' => function () use ( $sessionProvider ) { return $sessionProvider; },
						),
					),
					'preauth' => array(),
					'primaryauth' => array(
						array(
							'factory' => function () use ( $that ) { return $that->primary; },
						),
					),
					'secondaryauth' => array(),
					'logger' => null,
				),
			) );
		}
		$config = new MultiConfig( array(
			$this->config,
			ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		) );

		if ( !$this->manager ) {
			$this->manager = new AuthManager( new FauxRequest(), $config );
		}
		$this->validity = Status::newGood();

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$provider->setConfig( $config );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );

		return $provider;
	}

	/**
	 * Reset $this->primary
	 */
	protected function resetPrimary() {
		$this->primary = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$this->primary->method( 'getUniqueId' )->willReturn( 'primary' );

		if ( $this->manager ) {
			$priv = TestingAccessWrapper::newFromObject( $this->manager );
			$priv->primaryAuthenticationProviders = null;
			$tmp = $priv->allAuthenticationProviders;
			unset( $tmp['primary'] );
			$priv->allAuthenticationProviders = $tmp;
		}
	}

	/**
	 * @uses PasswordAuthenticationRequest
	 * @uses AuthenticationResponse
	 */
	public function testBasics() {
		$provider = new ResetPasswordSecondaryAuthenticationProvider();

		$this->assertTrue( $provider->providerAllowPropertyChange( 'email' ) );
		$this->assertTrue( $provider->providerAllowPropertyChange( 'realname' ) );
		$this->assertTrue( $provider->providerAllowPropertyChange( 'foo' ) );

		$req = new PasswordAuthenticationRequest();
		$req->username = 'UTSysop';
		$req->password = 'Pass';
		$this->assertEquals(
			StatusValue::newGood( 'ignored' ),
			$provider->providerCanChangeAuthenticationData( $req )
		);

		$user = new User();
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, array() )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAccountCreation( $user, array() )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueSecondaryAccountCreation( $user, array() )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( $user )
		);

		$provider->autoCreatedAccount( $user );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @uses AuthManager
	 * @param string $which
	 * @param bool|null $hard
	 * @param array $response
	 */
	public function testGetAuthenticationRequestTypes( $which, $hard, $response ) {
		$provider = $this->getProvider();
		if ( $hard === null ) {
			$this->manager->removeAuthenticationData( 'reset-pass' );
		} else {
			$this->manager->setAuthenticationData( 'reset-pass', (object)array(
				'msg' => wfMessage( 'mainpage' ),
				'hard' => $hard,
			) );
		}
		$this->assertSame( $response, $provider->getAuthenticationRequestTypes( $which ) );
	}

	public static function provideGetAuthenticationRequestTypes() {
		return array(
			array( 'login', true, array() ),
			array( 'login', false, array() ),
			array( 'login', null, array() ),
			array( 'create', true, array() ),
			array( 'create', false, array() ),
			array( 'create', null, array() ),
			array( 'change', true, array() ),
			array( 'change', false, array() ),
			array( 'change', null, array() ),
			array( 'all', true, array(
				'SoftResetPasswordAuthenticationRequest',
				'HardResetPasswordAuthenticationRequest'
			) ),
			array( 'all', false, array(
				'SoftResetPasswordAuthenticationRequest',
				'HardResetPasswordAuthenticationRequest'
			) ),
			array( 'all', null, array(
				'SoftResetPasswordAuthenticationRequest',
				'HardResetPasswordAuthenticationRequest'
			) ),
			array( 'login-continue', true, array( 'HardResetPasswordAuthenticationRequest' ) ),
			array( 'login-continue', false, array( 'SoftResetPasswordAuthenticationRequest' ) ),
			array( 'login-continue', null, array() ),
			array( 'create-continue', true, array() ),
			array( 'create-continue', false, array() ),
			array( 'create-continue', null, array() ),
		);
	}

	/**
	 * @uses AuthManager
	 * @uses AuthenticationResponse
	 * @uses HardResetPasswordAuthenticationRequest
	 * @uses SoftResetPasswordAuthenticationRequest
	 */
	public function testAuthentication() {
		$that = $this;
		$user = User::newFromName( 'TestUser' );
		$msg = wfMessage( 'foobar' );

		$provider = $this->getProvider();
		$this->manager->removeAuthenticationData( 'reset-pass' );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $user, array() )
		);

		foreach ( array(
			'HardResetPasswordAuthenticationRequest' => true,
			'SoftResetPasswordAuthenticationRequest' => false,
		) as $reqType => $hard ) {
			$this->resetPrimary();
			$this->primary->expects( $this->never() )
				->method( 'providerCanChangeAuthenticationData' );
			$this->primary->expects( $this->never() )
				->method( 'providerChangeAuthenticationData' );

			$data = (object)array(
				'msg' => $msg,
				'hard' => $hard,
			);
			$this->manager->setAuthenticationData( 'reset-pass', $data );

			$this->assertEquals(
				AuthenticationResponse::newUI( array( $reqType ), $msg ),
				$provider->beginSecondaryAuthentication( $user, array() )
			);
			$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$req = new $reqType;
			$req->username = 'TestUser';
			$req->password = 'TestPassword';
			$req->retype = 'BadRetype';
			$reqs = array( $reqType => $req );

			$req->skip = true;
			if ( $hard ) {
				$this->assertEquals(
					AuthenticationResponse::newUI( array( $reqType ), $msg ),
					$provider->continueSecondaryAuthentication( $user, $reqs )
				);
				$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );
			} else {
				$this->assertEquals(
					AuthenticationResponse::newPass(),
					$provider->continueSecondaryAuthentication( $user, $reqs )
				);
				$this->assertNull( $this->manager->getAuthenticationData( 'reset-pass' ) );
				$this->manager->setAuthenticationData( 'reset-pass', $data );
			}

			$req->skip = false;
			$res = $provider->continueSecondaryAuthentication( $user, $reqs );
			$this->assertEquals( AuthenticationResponse::UI, $res->status );
			$this->assertEquals( array( $reqType ), $res->neededRequests );
			$this->assertEquals( 'badretype', $res->message->getKey() );
			$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$req->retype = $req->password;

			$this->resetPrimary();
			$this->primary->expects( $this->once() )
				->method( 'providerCanChangeAuthenticationData' )
				->will( $this->returnCallback( function ( $req2 ) use ( $that ) {
					$that->assertInstanceOf( 'PasswordAuthenticationRequest', $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					return StatusValue::newFatal( 'random-failure' );
				} ) );
			$this->primary->expects( $this->never() )
				->method( 'providerChangeAuthenticationData' );
			$res = $provider->continueSecondaryAuthentication( $user, $reqs );
			$this->assertEquals( AuthenticationResponse::UI, $res->status );
			$this->assertEquals( array( $reqType ), $res->neededRequests );
			$this->assertEquals( 'random-failure', $res->message->getKey() );
			$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$this->resetPrimary();
			$this->primary->expects( $this->once() )
				->method( 'providerCanChangeAuthenticationData' )
				->will( $this->returnCallback( function ( $req2 ) use ( $that ) {
					$that->assertInstanceOf( 'PasswordAuthenticationRequest', $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					return StatusValue::newGood();
				} ) );
			$this->primary->expects( $this->once() )
				->method( 'providerChangeAuthenticationData' )
				->with( $this->callback( function ( $req2 ) use ( $that ) {
					$that->assertInstanceOf( 'PasswordAuthenticationRequest', $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					return true;
				} ) );
			$res = $provider->continueSecondaryAuthentication( $user, $reqs );
			$this->assertEquals( AuthenticationResponse::newPass(), $res );
			$this->assertNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$class = get_class( $this->getMock( 'AuthenticationRequest' ) );
			$data->reqType = $class;
			$data->reqData = array(
				'foo' => 42,
				'bar' => 'baz',
			);
			$this->manager->setAuthenticationData( 'reset-pass', $data );
			$this->resetPrimary();
			$this->primary->expects( $this->once() )
				->method( 'providerCanChangeAuthenticationData' )
				->will( $this->returnCallback( function ( $req2 ) use ( $that, $class ) {
					$that->assertInstanceOf( $class, $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					$that->assertSame( 42, $req2->foo );
					$that->assertSame( 'baz', $req2->bar );
					return StatusValue::newGood();
				} ) );
			$this->primary->expects( $this->once() )
				->method( 'providerChangeAuthenticationData' )
				->with( $this->callback( function ( $req2 ) use ( $that, $class ) {
					$that->assertInstanceOf( $class, $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					$that->assertSame( 42, $req2->foo );
					$that->assertSame( 'baz', $req2->bar );
					return true;
				} ) );
			$res = $provider->continueSecondaryAuthentication( $user, $reqs );
			$this->assertEquals( AuthenticationResponse::newPass(), $res );
			$this->assertNull( $this->manager->getAuthenticationData( 'reset-pass' ) );
		}

	}

}
