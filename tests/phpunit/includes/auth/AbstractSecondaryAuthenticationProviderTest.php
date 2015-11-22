<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AbstractSecondaryAuthenticationProvider
 */
class AbstractSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testAbstractSecondaryAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractSecondaryAuthenticationProvider'
		);

		try {
			$provider->continueSecondaryAuthentication( null, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

		try {
			$provider->continueSecondaryAccountCreation( null, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
		$this->assertEquals(
			\StatusValue::newGood( 'ignored' ),
			$provider->providerAllowsAuthenticationDataChange( $req )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( null, null, array() )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAutoCreation( null )
		);

		$provider->autoCreatedAccount( null );
	}
}
