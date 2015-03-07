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
		$this->assertFalse( $providerPriv->authoritative );

		$provider = $this->getMockForAbstractClass(
			'AbstractPasswordPrimaryAuthenticationProvider', array( array( 'authoritative' => true ) )
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->authoritative );
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
	public function testContinueAuthentication() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->continueAuthentication( array() );
	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testContinueAccountCreation() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$provider->continueAccountCreation( User::newFromName( 'UTSysop' ), array() );
	}

	public function testAllowPropertyChange() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$this->assertTrue( $provider->allowPropertyChange( 'foo' ) );
	}

	public function testAllowChangingAuthenticationType() {
		$provider = $this->getMockForAbstractClass( 'AbstractPasswordPrimaryAuthenticationProvider' );
		$this->assertTrue( $provider->allowChangingAuthenticationType( 'PasswordAuthenticationRequest' ) );
		$this->assertTrue( $provider->allowChangingAuthenticationType( 'foo' ) );
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
