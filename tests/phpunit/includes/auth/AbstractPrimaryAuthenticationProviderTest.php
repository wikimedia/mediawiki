<?php

namespace MediaWiki\Tests\Auth;

use BadMethodCallException;
use MediaWiki\Auth\AbstractPrimaryAuthenticationProvider;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PrimaryAuthenticationProvider;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use StatusValue;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractPrimaryAuthenticationProvider
 */
class AbstractPrimaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use AuthenticationProviderTestTrait;

	public function testAbstractPrimaryAuthenticationProvider() {
		$user = $this->createMock( User::class );

		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );

		try {
			$provider->continuePrimaryAuthentication( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException ) {
		}

		try {
			$provider->continuePrimaryAccountCreation( $user, $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException ) {
		}

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'foo' ) );
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
			->with( 'foo' )
			->willReturn( true );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'foo' ) );
	}

	public function testProviderRevokeAccessForUser() {
		$reqs = [];
		for ( $i = 0; $i < 3; $i++ ) {
			$reqs[$i] = $this->createMock( AuthenticationRequest::class );
		}
		$username = 'TestProviderRevokeAccessForUser';

		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );
		$provider->expects( $this->once() )->method( 'getAuthenticationRequests' )
			->with(
				$this->identicalTo( AuthManager::ACTION_REMOVE ),
				$this->identicalTo( [ 'username' => $username ] )
			)
			->willReturn( $reqs );
		$provider->expects( $this->exactly( 3 ) )->method( 'providerChangeAuthenticationData' )
			->willReturnCallback( function ( $req ) use ( $username ) {
				$this->assertSame( $username, $req->username );
			} );

		$provider->providerRevokeAccessForUser( $username );

		foreach ( $reqs as $i => $req ) {
			$this->assertNotNull( $req->username, "#$i" );
		}
	}

	/**
	 * @dataProvider providePrimaryAccountLink
	 * @param string $type PrimaryAuthenticationProvider::TYPE_* constant
	 * @param string $msg Error message from beginPrimaryAccountLink
	 */
	public function testPrimaryAccountLink( $type, $msg ) {
		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );
		$provider->method( 'accountCreationType' )
			->willReturn( $type );

		$class = AbstractPrimaryAuthenticationProvider::class;
		$msg1 = "{$class}::beginPrimaryAccountLink $msg";
		$msg2 = "{$class}::continuePrimaryAccountLink is not implemented.";

		$user = User::newFromName( 'Whatever' );

		try {
			$provider->beginPrimaryAccountLink( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame( $msg1, $ex->getMessage() );
		}
		try {
			$provider->continuePrimaryAccountLink( $user, [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
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

	/**
	 * @dataProvider provideProviderNormalizeUsername
	 */
	public function testProviderNormalizeUsername( $name, $expect ) {
		// fake interwiki map for the 'Interwiki prefix' testcase
		$interwikiLookup = $this->getDummyInterwikiLookup( [ 'interwiki' ] );
		$this->setService( 'InterwikiLookup', $interwikiLookup );

		$provider = $this->getMockForAbstractClass( AbstractPrimaryAuthenticationProvider::class );
		$this->initProvider( $provider, null, null, null, null, $this->getServiceContainer()->getUserNameUtils() );
		$this->assertSame( $expect, $provider->providerNormalizeUsername( $name ) );
	}

	public static function provideProviderNormalizeUsername() {
		return [
			'Leading space' => [ ' Leading space', 'Leading space' ],
			'Trailing space ' => [ 'Trailing space ', 'Trailing space' ],
			'Namespace prefix' => [ 'Talk:Username', null ],
			'Interwiki prefix' => [ 'interwiki:Username', null ],
			'With hash' => [ 'name with # hash', null ],
			'Multi spaces' => [ 'Multi  spaces', 'Multi spaces' ],
			'Lowercase' => [ 'lowercase', 'Lowercase' ],
			'Invalid character' => [ 'in[]valid', null ],
			'With slash' => [ 'with / slash', null ],
			'Underscores' => [ '___under__scores___', 'Under scores' ],
		];
	}

}
