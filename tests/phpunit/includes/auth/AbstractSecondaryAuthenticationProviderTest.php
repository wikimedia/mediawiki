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

		$provider->providerChangeAuthenticationData( $req );
		$provider->autoCreatedAccount( null );
	}

	public function testProviderRevokeAccessForUser() {
		$that = $this;
		$reqs = array();
		for ( $i = 0; $i < 3; $i++ ) {
			$reqs[$i] = $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' );
			$reqs[$i]->done = false;
		}

		$provider = $this->getMockBuilder( 'MediaWiki\\Auth\\AbstractSecondaryAuthenticationProvider' )
			->setMethods( array( 'providerChangeAuthenticationData' ) )
			->getMockForAbstractClass();
		$provider->expects( $this->once() )->method( 'getAuthenticationRequests' )
			->with(
				$this->identicalTo( AuthManager::ACTION_REMOVE ),
				$this->identicalTo( array( 'username' => 'UTSysop' ) )
			)
			->will( $this->returnValue( $reqs ) );
		$provider->expects( $this->exactly( 3 ) )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) use ( $that ) {
				$that->assertSame( 'UTSysop', $req->username );
				$that->assertFalse( $req->done );
				$req->done = true;
			} ) );

		$provider->providerRevokeAccessForUser( 'UTSysop' );

		foreach ( $reqs as $i => $req ) {
			$this->assertTrue( $req->done, "#$i" );
		}
	}
}
