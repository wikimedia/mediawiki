<?php

/**
 * @group AuthManager
 * @covers AbstractPasswordPrimaryAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 */
class AbstractPasswordPrimaryAuthenticationProviderTest extends MediaWikiTestCase {
	public function testConstructor() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->authoritative );

		$provider = $this->getMockForAbstractClass(
			'AbstractPasswordPrimaryAuthenticationProvider', array( array( 'authoritative' => false ) )
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertFalse( $providerPriv->authoritative );
	}

	public function testGetPasswordFactory() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->setConfig( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPasswordFactory();
		$this->assertInstanceOf( 'PasswordFactory', $obj );
		$this->assertSame( $obj, $providerPriv->getPasswordFactory() );
	}

	public function testGetPassword() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->setConfig( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPassword( null );
		$this->assertInstanceOf( 'Password', $obj );

		$obj = $providerPriv->getPassword( 'invalid' );
		$this->assertInstanceOf( 'Password', $obj );
	}

	public function testCheckPasswordValidity() {
		$user = $this->getMockBuilder( 'User' )
			->setMethods( array( 'checkPasswordValidity' ) )
			->getMock();

		$status = Status::newGood();
		$user->expects( $this->once() )
			->method( 'checkPasswordValidity' )
			->with( $this->equalTo( 'passWord!' ) )
			->willReturn( $status );

		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->setConfig( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$this->assertSame( $status, $providerPriv->checkPasswordValidity( $user, 'passWord!' ) );
	}

	/**
	 * @uses AuthManager
	 */
	public function testSetPasswordResetFlag() {
		$config = new HashConfig( array(
			'InvalidPasswordReset' => true,
		) );

		$manager = new AuthManager(
			new FauxRequest(), ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		);

		$user = $this->getMockBuilder( 'User' )
			->setMethods( array( 'getPasswordExpired' ) )
			->getMock();

		$status = Status::newGood();
		$user->method( 'getPasswordExpired' )
			->will( $this->onConsecutiveCalls(
				false,
				'hard',
				'soft',
				false,
				false
			) );

		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->setConfig( $config );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setManager( $manager );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$manager->removeAuthenticationData( null );
		$providerPriv->setPasswordResetFlag( $user, Status::newGood() );
		$this->assertNull( $manager->getAuthenticationData( 'reset-pass' ) );

		$manager->removeAuthenticationData( null );
		$providerPriv->setPasswordResetFlag( $user, Status::newGood() );
		$ret = $manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$manager->removeAuthenticationData( null );
		$providerPriv->setPasswordResetFlag( $user, Status::newGood() );
		$ret = $manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$manager->removeAuthenticationData( null );
		$status = Status::newGood();
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $user, $status );
		$ret = $manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$config->set( 'InvalidPasswordReset', false );
		$manager->removeAuthenticationData( null );
		$providerPriv->setPasswordResetFlag( $user, $status );
		$ret = $manager->getAuthenticationData( 'reset-pass' );
		$this->assertNull( $ret );
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testFailResponse() {
		$provider = $this->getMockForAbstractClass(
			'AbstractPasswordPrimaryAuthenticationProvider', array( array( 'authoritative' => false ) )
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$req = new PasswordAuthenticationRequest;

		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::ABSTAIN, $ret->status );

		$provider = $this->getMockForAbstractClass(
			'AbstractPasswordPrimaryAuthenticationProvider', array( array( 'authoritative' => true ) )
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$req->password = '';
		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpasswordempty', $ret->message->getKey() );

		$req->password = 'X';
		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $which
	 * @param array $response
	 */
	public function testGetAuthenticationRequestTypes( $which, $response ) {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );

		$this->assertSame( $response, $provider->getAuthenticationRequestTypes( $which ) );
	}

	public static function provideGetAuthenticationRequestTypes() {
		return array(
			array( 'login', array( 'PasswordAuthenticationRequest' ) ),
			array( 'create', array( 'PasswordAuthenticationRequest' ) ),
			array( 'change', array( 'PasswordAuthenticationRequest' ) ),
			array( 'all', array( 'PasswordAuthenticationRequest' ) ),
			array( 'login-continue', array() ),
			array( 'create-continue', array() ),
		);
	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testContinuePrimaryAuthentication() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->continuePrimaryAuthentication( array() );
	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testContinuePrimaryAccountCreation() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->continuePrimaryAccountCreation( User::newFromName( 'UTSysop' ), array() );
	}

	public function testProviderAllowPropertyChange() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$this->assertTrue( $provider->providerAllowPropertyChange( 'foo' ) );
	}

	public function testProviderAllowChangingAuthenticationType() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$this->assertTrue( $provider->providerAllowChangingAuthenticationType( 'PasswordAuthenticationRequest' ) );
		$this->assertTrue( $provider->providerAllowChangingAuthenticationType( 'foo' ) );
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testFinishAccountCreation() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->finishAccountCreation(
			User::newFromName( 'UTSysop' ),
			AuthenticationResponse::newPass( 'UTSysop' )
		);
		$this->assertTrue( true ); // Dummy
	}

	public function testTestForAutoCreation() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( User::newFromName( 'UTSysop' ) )
		);
	}

	public function testAutoCreatedAccount() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->autoCreatedAccount(
			User::newFromName( 'UTSysop' )
		);
		$this->assertTrue( true ); // Dummy
	}
}
