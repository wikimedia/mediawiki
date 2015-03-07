<?php

/**
 * @group AuthManager
 * @covers AbstractPrimaryAuthenticationProvider
 */
class AbstractPrimaryAuthenticationProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 * @uses AuthenticationResponse
	 */
	public function testAbstractPrimaryAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( 'AbstractPrimaryAuthenticationProvider' );

		try {
			$provider->continuePrimaryAuthentication( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
		}

		try {
			$provider->continuePrimaryAccountCreation( null, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
		}

		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
		$this->assertTrue( $provider->providerAllowsAuthenticationDataChangeType( 'Foo' ) );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( null, null, array() )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( null )
		);

		$provider->finishAccountCreation( null, AuthenticationResponse::newPass() );
		$provider->autoCreatedAccount( null );

		$provider->expects( $this->once() )
			->method( 'testUserExists' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( true );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'foo' ) );
	}
}
