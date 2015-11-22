<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AbstractPrimaryAuthenticationProvider
 */
class AbstractPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {
	protected function setUp() {
		global $wgDisableAuthManager;

		parent::setUp();
		if ( $wgDisableAuthManager ) {
			$this->markTestSkipped( '$wgDisableAuthManager is set' );
		}
	}

	public function testAbstractPrimaryAuthenticationProvider() {
		$user = \User::newFromName( 'UTSysop' );

		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );

		try {
			$provider->continuePrimaryAuthentication( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

		try {
			$provider->continuePrimaryAccountCreation( $user, $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

		$req = $this->getMockForAbstractClass( AuthenticationRequest::class );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( $user, AuthManager::AUTOCREATE_SOURCE_SESSION )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( $user, false )
		);

		$this->assertNull(
			$provider->finishAccountCreation( $user, $user, AuthenticationResponse::newPass() )
		);
		$provider->autoCreatedAccount( $user, AuthManager::AUTOCREATE_SOURCE_SESSION );

		$res = AuthenticationResponse::newPass();
		$provider->postAuthentication( $user, $res );
		$provider->postAccountCreation( $user, $user, $res );
		$provider->postAccountLink( $user, $res );

		$provider->expects( $this->once() )
			->method( 'testUserExists' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( true ) );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'foo' ) );
	}

	public function testProviderRevokeAccessForUser() {
		$reqs = [];
		for ( $i = 0; $i < 3; $i++ ) {
			$reqs[$i] = $this->getMock( AuthenticationRequest::class );
			$reqs[$i]->done = false;
		}

		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );
		$provider->expects( $this->once() )->method( 'getAuthenticationRequests' )
			->with(
				$this->identicalTo( AuthManager::ACTION_REMOVE ),
				$this->identicalTo( [ 'username' => 'UTSysop' ] )
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
		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );
		$provider->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( $type ) );

		$class = AbstractPrimaryAuthenticationProvider::class;
		$msg1 = "{$class}::beginPrimaryAccountLink $msg";
		$msg2 = "{$class}::continuePrimaryAccountLink is not implemented.";

		$user = \User::newFromName( 'Whatever' );

		try {
			$provider->beginPrimaryAccountLink( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame( $msg1, $ex->getMessage() );
		}
		try {
			$provider->continuePrimaryAccountLink( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame( $msg2, $ex->getMessage() );
		}
	}

	public static function providePrimaryAccountLink() {
		return [
			[
				PrimaryAuthenticationProvider::TYPE_NONE,
				'should not be called on a non-link provider.',
			],
			[
				PrimaryAuthenticationProvider::TYPE_CREATE,
				'should not be called on a non-link provider.',
			],
			[
				PrimaryAuthenticationProvider::TYPE_LINK,
				'is not implemented.',
			],
		];
	}

}
