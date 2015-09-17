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
		$uppCalled = 0;
		$uppStatus = Status::newGood();
		$this->setMwGlobals( array(
			'wgPasswordPolicy' => array(
				'policies' => array(
					'default' => array(
						'Check' => true,
					),
				),
				'checks' => array(
					'Check' => function () use ( &$uppCalled, &$uppStatus ) {
						$uppCalled++;
						return $uppStatus;
					},
				),
			)
		) );

		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->setConfig( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( 'foo', 'bar' ) );

		$uppStatus->fatal( 'arbitrary-warning' );
		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( 'foo', 'bar' ) );
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

		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->setConfig( $config );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setManager( $manager );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$manager->removeAuthenticationData( null );
		$status = Status::newGood();
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$this->assertNull( $manager->getAuthenticationData( 'reset-pass' ) );

		$manager->removeAuthenticationData( null );
		$status = Status::newGood();
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$ret = $manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$config->set( 'InvalidPasswordReset', false );
		$manager->removeAuthenticationData( null );
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
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

	public function testProviderRevokeAccessForUser() {
		$req = new PasswordAuthenticationRequest;
		$req->username = 'foo';
		$req->password = null;

		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->expects( $this->once() )
			->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );

		$provider->providerRevokeAccessForUser( 'foo' );
	}

}
