<?php

namespace MediaWiki\Tests\Unit\Auth;

use BadMethodCallException;
use MediaWiki\Auth\AbstractSecondaryAuthenticationProvider;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\User\User;
use MediaWikiUnitTestCase;
use StatusValue;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractSecondaryAuthenticationProvider
 */
class AbstractSecondaryAuthenticationProviderTest extends MediaWikiUnitTestCase {
	public function testAbstractSecondaryAuthenticationProvider() {
		$user = $this->createMock( User::class );

		$provider = $this->getMockForAbstractClass( AbstractSecondaryAuthenticationProvider::class );

		try {
			$provider->continueSecondaryAuthentication( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException ) {
		}

		try {
			$provider->continueSecondaryAccountCreation( $user, $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException ) {
		}

		$req = $this->getMockForAbstractClass( AuthenticationRequest::class );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
		$this->assertEquals(
			StatusValue::newGood( 'ignored' ),
			$provider->providerAllowsAuthenticationDataChange( $req )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testUserForCreation( $user, AuthManager::AUTOCREATE_SOURCE_SESSION )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testUserForCreation( $user, false )
		);

		$provider->providerChangeAuthenticationData( $req );
		$provider->autoCreatedAccount( $user, AuthManager::AUTOCREATE_SOURCE_SESSION );

		$res = AuthenticationResponse::newPass();
		$provider->postAuthentication( $user, $res );
		$provider->postAccountCreation( $user, $user, $res );
	}

	public function testProviderRevokeAccessForUser() {
		$reqs = [];
		for ( $i = 0; $i < 3; $i++ ) {
			$reqs[$i] = $this->createMock( AuthenticationRequest::class );
		}
		$userName = 'TestProviderRevokeAccessForUser';

		$provider = $this->getMockBuilder( AbstractSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'providerChangeAuthenticationData' ] )
			->getMockForAbstractClass();
		$provider->expects( $this->once() )->method( 'getAuthenticationRequests' )
			->with(
				$this->identicalTo( AuthManager::ACTION_REMOVE ),
				$this->identicalTo( [ 'username' => $userName ] )
			)
			->willReturn( $reqs );
		$provider->expects( $this->exactly( 3 ) )->method( 'providerChangeAuthenticationData' )
			->willReturnCallback( function ( $req ) use ( $userName ) {
				$this->assertSame( $userName, $req->username );
			} );

		$provider->providerRevokeAccessForUser( $userName );

		foreach ( $reqs as $i => $req ) {
			$this->assertNotNull( $req->username, "#$i" );
		}
	}
}
