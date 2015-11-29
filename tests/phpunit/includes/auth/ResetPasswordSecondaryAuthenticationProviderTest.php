<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider
 */
class ResetPasswordSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {

	private $manager = null;
	private $config = null;
	private $primary = null;

	/**
	 * Get an instance of the provider
	 * @return ResetPasswordSecondaryAuthenticationProvider
	 */
	protected function getProvider() {
		$that = $this;

		$this->resetPrimary();

		if ( !$this->config ) {
			$this->config = new \HashConfig( array(
				'AuthManagerConfig' => array(
					'preauth' => array(),
					'primaryauth' => array(
						array( 'factory' => function () use ( $that ) {
							return $that->primary;
						} ),
					),
					'secondaryauth' => array(),
				),
			) );
		}
		$config = new \MultiConfig( array(
			$this->config,
			\ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		) );

		if ( !$this->manager ) {
			$this->manager = new AuthManager( new \FauxRequest(), $config );
		}
		$this->validity = \Status::newGood();

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$provider->setConfig( $config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );

		return $provider;
	}

	/**
	 * Reset $this->primary
	 */
	protected function resetPrimary() {
		$this->primary = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\PrimaryAuthenticationProvider'
		);
		$this->primary->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary' ) );

		if ( $this->manager ) {
			$priv = \TestingAccessWrapper::newFromObject( $this->manager );
			$priv->primaryAuthenticationProviders = null;
			$tmp = $priv->allAuthenticationProviders;
			unset( $tmp['primary'] );
			$priv->allAuthenticationProviders = $tmp;
		}
	}

	public function testBasics() {
		$provider = new ResetPasswordSecondaryAuthenticationProvider();

		$user = new \User();
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAccountCreation( $user, array() )
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param bool|null $hard
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $hard, $response ) {
		$provider = $this->getProvider();
		if ( $hard === null ) {
			$this->manager->removeAuthenticationData( 'reset-pass' );
		} else {
			$this->manager->setAuthenticationData( 'reset-pass', (object)array(
				'msg' => wfMessage( 'mainpage' ),
				'hard' => $hard,
			) );
		}
		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action ) );
	}

	public static function provideGetAuthenticationRequests() {
		return array(
			array( AuthManager::ACTION_LOGIN, true, array() ),
			array( AuthManager::ACTION_LOGIN, false, array() ),
			array( AuthManager::ACTION_LOGIN, null, array() ),
			array( AuthManager::ACTION_CREATE, true, array() ),
			array( AuthManager::ACTION_CREATE, false, array() ),
			array( AuthManager::ACTION_CREATE, null, array() ),
			array( AuthManager::ACTION_CHANGE, true, array() ),
			array( AuthManager::ACTION_CHANGE, false, array() ),
			array( AuthManager::ACTION_CHANGE, null, array() ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, true, array(
				new HardResetPasswordAuthenticationRequest()
			) ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, false, array(
				new SoftResetPasswordAuthenticationRequest()
			) ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, null, array() ),
			array( AuthManager::ACTION_CREATE_CONTINUE, true, array() ),
			array( AuthManager::ACTION_CREATE_CONTINUE, false, array() ),
			array( AuthManager::ACTION_CREATE_CONTINUE, null, array() ),
		);
	}

	public function testAuthentication() {
		$that = $this;
		$user = \User::newFromName( 'TestUser' );
		$msg = wfMessage( 'foobar' );

		$provider = $this->getProvider();
		$this->manager->removeAuthenticationData( 'reset-pass' );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $user, array() )
		);

		foreach ( array(
			'MediaWiki\\Auth\\HardResetPasswordAuthenticationRequest' => true,
			'MediaWiki\\Auth\\SoftResetPasswordAuthenticationRequest' => false,
		) as $reqType => $hard ) {
			$this->resetPrimary();
			$this->primary->expects( $this->never() )
				->method( 'providerAllowsAuthenticationDataChange' );
			$this->primary->expects( $this->never() )
				->method( 'providerChangeAuthenticationData' );

			$data = (object)array(
				'msg' => $msg,
				'hard' => $hard,
			);
			$this->manager->setAuthenticationData( 'reset-pass', $data );

			$this->assertEquals(
				AuthenticationResponse::newUI( array( new $reqType ), $msg ),
				$provider->beginSecondaryAuthentication( $user, array() )
			);
			$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$req = new $reqType;
			$req->username = 'TestUser';
			$req->password = 'TestPassword';
			$req->retype = 'BadRetype';
			$reqs = array( $req );

			$req->skip = true;
			if ( $hard ) {
				$this->assertEquals(
					AuthenticationResponse::newUI( array( new $reqType ), $msg ),
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
			$this->assertEquals( array( new $reqType ), $res->neededRequests );
			$this->assertEquals( 'badretype', $res->message->getKey() );
			$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$req->retype = $req->password;

			$this->resetPrimary();
			$this->primary->expects( $this->once() )
				->method( 'providerAllowsAuthenticationDataChange' )
				->will( $this->returnCallback( function ( $req2 ) use ( $that ) {
					$that->assertInstanceOf( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					return \StatusValue::newFatal( 'random-failure' );
				} ) );
			$this->primary->expects( $this->never() )
				->method( 'providerChangeAuthenticationData' );
			$res = $provider->continueSecondaryAuthentication( $user, $reqs );
			$this->assertEquals( AuthenticationResponse::UI, $res->status );
			$this->assertEquals( array( new $reqType ), $res->neededRequests );
			$this->assertEquals( 'random-failure', $res->message->getKey() );
			$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$this->resetPrimary();
			$this->primary->expects( $this->once() )
				->method( 'providerAllowsAuthenticationDataChange' )
				->will( $this->returnCallback( function ( $req2 ) use ( $that ) {
					$that->assertInstanceOf( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					return \StatusValue::newGood();
				} ) );
			$this->primary->expects( $this->once() )
				->method( 'providerChangeAuthenticationData' )
				->with( $this->callback( function ( $req2 ) use ( $that ) {
					$that->assertInstanceOf( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					return true;
				} ) );
			$res = $provider->continueSecondaryAuthentication( $user, $reqs );
			$this->assertEquals( AuthenticationResponse::newPass(), $res );
			$this->assertNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

			$class = get_class( $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' ) );
			$data->reqType = $class;
			$data->reqData = array(
				'foo' => 42,
				'bar' => 'baz',
			);
			$this->manager->setAuthenticationData( 'reset-pass', $data );
			$this->resetPrimary();
			$this->primary->expects( $this->once() )
				->method( 'providerAllowsAuthenticationDataChange' )
				->will( $this->returnCallback( function ( $req2 ) use ( $that, $class ) {
					$that->assertInstanceOf( $class, $req2 );
					$that->assertSame( 'TestUser', $req2->username );
					$that->assertSame( 'TestPassword', $req2->password );
					$that->assertSame( 42, $req2->foo );
					$that->assertSame( 'baz', $req2->bar );
					return \StatusValue::newGood();
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
