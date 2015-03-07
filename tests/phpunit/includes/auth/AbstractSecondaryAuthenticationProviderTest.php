<?php

/**
 * @group AuthManager
 * @covers AbstractSecondaryAuthenticationProvider
 */
class AbstractSecondaryAuthenticationProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 */
	public function testAbstractSecondaryAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( 'AbstractSecondaryAuthenticationProvider' );

		try {
			$provider->continueSecondaryAuthentication( null, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
		}

		try {
			$provider->continueSecondaryAccountCreation( null, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
		}

		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
		$this->assertEquals(
			StatusValue::newGood( 'ignored' ),
			$provider->providerAllowsAuthenticationDataChange( $req )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( null, null, array() )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( null )
		);

		$provider->autoCreatedAccount( null );
	}
}
