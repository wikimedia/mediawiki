<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AbstractPrimaryAuthenticationProvider
 */
class AbstractPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testAbstractPrimaryAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPrimaryAuthenticationProvider'
		);

		try {
			$provider->continuePrimaryAuthentication( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

		try {
			$provider->continuePrimaryAccountCreation( null, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( null, null, array() )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( null, true )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( null, false )
		);

		$provider->finishAccountCreation( null, AuthenticationResponse::newPass() );
		$provider->autoCreatedAccount( null );

		$provider->expects( $this->once() )
			->method( 'testUserExists' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( true ) );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'foo' ) );
	}

	public function testProviderRevokeAccessForUser() {
		$reqs = array();
		for ( $i = 0; $i < 3; $i++ ) {
			$reqs[$i] = $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' );
			$reqs[$i]->done = false;
		}

		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPrimaryAuthenticationProvider'
		);
		$provider->expects( $this->once() )->method( 'getAuthenticationRequests' )
			->with(
				$this->identicalTo( AuthManager::ACTION_REMOVE ),
				$this->identicalTo( array( 'username' => 'UTSysop' ) )
			)
			->will( $this->returnValue( $reqs ) );
		$provider->expects( $this->exactly( 3 ) )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) {
				$this->assertSame( 'UTSysop', $req->username );
				$this->assertFalse( $req->done );
				$req->done = true;
			} ) );

		$provider->providerRevokeAccessForUser( 'UTSysop' );

		foreach ( $reqs as $i => $req ) {
			$this->assertTrue( $req->done, "#$i" );
		}
	}

	/**
	 * @dataProvider providePrimaryAccountLink
	 * @param string $type PrimaryAuthenticationProvider::TYPE_* constant
	 * @param string $msg Error message from beginPrimaryAccountLink
	 */
	public function testPrimaryAccountLink( $type, $msg ) {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPrimaryAuthenticationProvider'
		);
		$provider->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( $type ) );

		$class = 'MediaWiki\\Auth\\AbstractPrimaryAuthenticationProvider';
		$msg1 = "{$class}::beginPrimaryAccountLink $msg";
		$msg2 = "{$class}::continuePrimaryAccountLink is not implemented.";

		$user = \User::newFromName( 'Whatever' );

		try {
			$provider->beginPrimaryAccountLink( $user, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame( $msg1, $ex->getMessage() );
		}
		try {
			$provider->continuePrimaryAccountLink( $user, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame( $msg2, $ex->getMessage() );
		}
	}

	public static function providePrimaryAccountLink() {
		return array(
			array(
				PrimaryAuthenticationProvider::TYPE_NONE,
				'should not be called on a non-link provider.',
			),
			array(
				PrimaryAuthenticationProvider::TYPE_CREATE,
				'should not be called on a non-link provider.',
			),
			array(
				PrimaryAuthenticationProvider::TYPE_LINK,
				'is not implemented.',
			),
		);
	}

}
