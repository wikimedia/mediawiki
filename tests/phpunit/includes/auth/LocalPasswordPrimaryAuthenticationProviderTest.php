<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider
 */
class LocalPasswordPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {

	private $manager = null;
	private $config = null;
	private $validity = null;

	protected function setUp() {
		global $wgDisableAuthManager;

		parent::setUp();
		if ( $wgDisableAuthManager ) {
			$this->markTestSkipped( '$wgDisableAuthManager is set' );
		}
	}

	/**
	 * Get an instance of the provider
	 *
	 * $provider->checkPasswordValidity is mocked to return $this->validity,
	 * because we don't need to test that here.
	 *
	 * @param bool $loginOnly
	 * @return LocalPasswordPrimaryAuthenticationProvider
	 */
	protected function getProvider( $loginOnly = false ) {
		if ( !$this->config ) {
			$this->config = new \HashConfig();
		}
		$config = new \MultiConfig( [
			$this->config,
			\ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		] );

		if ( !$this->manager ) {
			$this->manager = new AuthManager( new \FauxRequest(), $config );
		}
		$this->validity = \Status::newGood();

		$provider = $this->getMock(
			'MediaWiki\\Auth\\LocalPasswordPrimaryAuthenticationProvider',
			[ 'checkPasswordValidity' ],
			[ [ 'loginOnly' => $loginOnly ] ]
		);
		$provider->expects( $this->any() )->method( 'checkPasswordValidity' )
			->will( $this->returnCallback( function () {
				return $this->validity;
			} ) );
		$provider->setConfig( $config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );

		return $provider;
	}

	public function testBasics() {
		$provider = new LocalPasswordPrimaryAuthenticationProvider();

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_CREATE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( 'UTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );

		$provider = new LocalPasswordPrimaryAuthenticationProvider( [ 'loginOnly' => true ] );

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_NONE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( 'UTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
	}

	public function testTestUserCanAuthenticate() {
		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', [ 'user_name' => 'UTSysop' ] );
		$cb = new \ScopedCallback( function () use ( $dbw, $oldHash ) {
			$dbw->update( 'user', [ 'user_password' => $oldHash ], [ 'user_name' => 'UTSysop' ] );
		} );
		$id = \User::idFromName( 'UTSysop' );

		$provider = $this->getProvider();

		$this->assertFalse( $provider->testUserCanAuthenticate( 'DoesNotExist' ) );

		$this->assertTrue( $provider->testUserCanAuthenticate( 'UTSysop' ) );

		$dbw->update(
			'user',
			[ 'user_password' => \PasswordFactory::newInvalidPassword()->toString() ],
			[ 'user_name' => 'UTSysop' ]
		);
		$this->assertFalse( $provider->testUserCanAuthenticate( 'UTSysop' ) );

		// Really old format
		$dbw->update(
			'user',
			[ 'user_password' => '0123456789abcdef0123456789abcdef' ],
			[ 'user_name' => 'UTSysop' ]
		);
		$this->assertTrue( $provider->testUserCanAuthenticate( 'UTSysop' ) );
	}

	public function testSetPasswordResetFlag() {
		// Set instance vars
		$this->getProvider();

		/// @todo: Because we're currently using User, which uses the global config...
		$this->setMwGlobals( [ 'wgPasswordExpireGrace' => 100 ] );

		$this->config->set( 'PasswordExpireGrace', 100 );
		$this->config->set( 'InvalidPasswordReset', true );

		$provider = new LocalPasswordPrimaryAuthenticationProvider();
		$provider->setConfig( $this->config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			'*',
			[ 'user_name' => 'UTSysop' ],
			__METHOD__
		);

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() + 200 );
		$providerPriv->setPasswordResetFlag( 'UTSysop', \Status::newGood(), $row );
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 200 );
		$providerPriv->setPasswordResetFlag( 'UTSysop', \Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 1 );
		$providerPriv->setPasswordResetFlag( 'UTSysop', \Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = \Status::newGood();
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( 'UTSysop', $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );
	}

	public function testAuthentication() {
		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', [ 'user_name' => 'UTSysop' ] );
		$cb = new \ScopedCallback( function () use ( $dbw, $oldHash ) {
			$dbw->update( 'user', [ 'user_password' => $oldHash ], [ 'user_name' => 'UTSysop' ] );
		} );
		$id = \User::idFromName( 'UTSysop' );

		$req = new PasswordAuthenticationRequest();
		$reqs = [ 'MediaWiki\\Auth\\PasswordAuthenticationRequest' => $req ];

		$provider = $this->getProvider();

		// General failures
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( [] )
		);

		$req->username = 'foo';
		$req->password = null;
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$req->username = null;
		$req->password = 'bar';
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$req->username = 'DoesNotExist';
		$req->password = 'DoesNotExist';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		// Validation failure
		$req->username = 'UTSysop';
		$req->password = 'UTSysopPassword';
		$this->validity = \Status::newFatal( 'arbitrary-failure' );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'arbitrary-failure',
			$ret->message->getKey()
		);

		// Successful auth
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = \Status::newGood();
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop' ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		// Successful auth with reset
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity->error( 'arbitrary-warning' );
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop' ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		// Wrong password
		$this->validity = \Status::newGood();
		$req->password = 'Wrong';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

		// Correct handling of legacy encodings
		$password = ':B:salt:' . md5( 'salt-' . md5( "\xe1\xe9\xed\xf3\xfa" ) );
		$dbw->update( 'user', [ 'user_password' => $password ], [ 'user_name' => 'UTSysop' ] );
		$req->password = 'áéíóú';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

		$this->config->set( 'LegacyEncoding', true );
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop' ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$req->password = 'áéíóú Wrong';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

		// Correct handling of really old password hashes
		$this->config->set( 'PasswordSalt', false );
		$password = md5( 'FooBar' );
		$dbw->update( 'user', [ 'user_password' => $password ], [ 'user_name' => 'UTSysop' ] );
		$req->password = 'FooBar';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop' ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$this->config->set( 'PasswordSalt', true );
		$password = md5( "$id-" . md5( 'FooBar' ) );
		$dbw->update( 'user', [ 'user_password' => $password ], [ 'user_name' => 'UTSysop' ] );
		$req->password = 'FooBar';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop' ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChange
	 * @param string $type
	 * @param string $user
	 * @param Status $validity
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, $user, $validity ) {
		$expect1 = $validity->getValue() === 'ignored'
			? \StatusValue::newGood( 'ignored' )
			: \StatusValue::newGood();
		$expect2 = \StatusValue::newGood();
		$expect2->merge( $validity, true );

		if ( $type === 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ) {
			$req = new $type();
		} else {
			$req = $this->getMock( $type );
		}
		$req->username = $user;
		$req->password = 'NewPassword';
		$req->retype = 'NewPassword';

		$provider = $this->getProvider();
		$this->validity = $validity;
		$this->assertEquals( $expect1, $provider->providerAllowsAuthenticationDataChange( $req, false ) );
		$this->assertEquals( $expect2, $provider->providerAllowsAuthenticationDataChange( $req, true ) );

		$req->retype = 'BadRetype';
		$this->assertEquals(
			$expect1,
			$provider->providerAllowsAuthenticationDataChange( $req, false )
		);
		$this->assertEquals(
			$validity->getValue() === 'ignored' ? $expect2 : \StatusValue::newFatal( 'badretype' ),
			$provider->providerAllowsAuthenticationDataChange( $req, true )
		);

		$provider = $this->getProvider( true );
		$this->assertEquals(
			\StatusValue::newGood( 'ignored' ),
			$provider->providerAllowsAuthenticationDataChange( $req, true ),
			'loginOnly mode should claim to ignore all changes'
		);
	}

	public static function provideProviderAllowsAuthenticationDataChange() {
		$err = \Status::newGood();
		$err->error( 'arbitrary-warning' );

		return [
			[ 'MediaWiki\\Auth\\AuthenticationRequest', 'UTSysop', \Status::newGood( 'ignored' ) ],
			[ 'MediaWiki\\Auth\\PasswordAuthenticationRequest', 'UTSysop', \Status::newGood() ],
			[ 'MediaWiki\\Auth\\PasswordAuthenticationRequest', 'UTSysop', $err ],
			[ 'MediaWiki\\Auth\\PasswordAuthenticationRequest', 'UTSysop',
				\Status::newFatal( 'arbitrary-error' ) ],
			[ 'MediaWiki\\Auth\\PasswordAuthenticationRequest', 'DoesNotExist',
				\Status::newGood( 'ignored' ) ],
		];
	}

	/**
	 * @dataProvider provideProviderChangeAuthenticationData
	 * @param string $type
	 * @param bool $loginOnly
	 * @param bool $changed
	 */
	public function testProviderChangeAuthenticationData( $type, $loginOnly, $changed ) {
		$user = 'UTSysop';
		$oldpass = 'UTSysopPassword';
		$newpass = 'NewPassword';

		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', [ 'user_name' => $user ] );
		$oldExpiry = $dbw->selectField( 'user', 'user_password_expires', [ 'user_name' => $user ] );
		$cb = new \ScopedCallback( function () use ( $dbw, $user, $oldHash, $oldExpiry ) {
			$dbw->update(
				'user',
				[
					'user_password' => $oldHash,
					'user_password_expires' => $oldExpiry,
				],
				[ 'user_name' => $user ]
			);
		} );

		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'ResetPasswordExpiration' => [ function ( $user, &$expires ) {
				$expires = '30001231235959';
			} ]
		] );

		$provider = $this->getProvider( $loginOnly );

		// Sanity check
		$req = new PasswordAuthenticationRequest();
		$req->username = $user;
		$req->password = $oldpass;
		$reqs = [ 'MediaWiki\\Auth\\PasswordAuthenticationRequest' => $req ];
		$this->assertEquals(
			AuthenticationResponse::newPass( $user ),
			$provider->beginPrimaryAuthentication( $reqs ),
			'Sanity check'
		);

		if ( $type === 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ) {
			$req2 = new $type();
		} else {
			$req2 = $this->getMock( $type );
		}
		$req2->username = $user;
		$req2->password = $newpass;
		$provider->providerChangeAuthenticationData( $req2 );

		if ( $loginOnly ) {
			$old = 'fail';
			$new = 'fail';
			$expectExpiry = null;
		} elseif ( $changed ) {
			$old = 'fail';
			$new = 'pass';
			$expectExpiry = '30001231235959';
		} else {
			$old = 'pass';
			$new = 'fail';
			$expectExpiry = $oldExpiry;
		}

		$req->password = $oldpass;
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		if ( $old === 'pass' ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $user ),
				$ret,
				'old password should pass'
			);
		} else {
			$this->assertEquals(
				AuthenticationResponse::FAIL,
				$ret->status,
				'old password should fail'
			);
			$this->assertEquals(
				'wrongpassword',
				$ret->message->getKey(),
				'old password should fail'
			);
		}

		$req->password = $newpass;
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		if ( $new === 'pass' ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $user ),
				$ret,
				'new password should pass'
			);
		} else {
			$this->assertEquals(
				AuthenticationResponse::FAIL,
				$ret->status,
				'new password should fail'
			);
			$this->assertEquals(
				'wrongpassword',
				$ret->message->getKey(),
				'new password should fail'
			);
		}

		$this->assertSame(
			$expectExpiry,
			$dbw->selectField( 'user', 'user_password_expires', [ 'user_name' => $user ] )
		);
	}

	public static function provideProviderChangeAuthenticationData() {
		return [
			[ 'MediaWiki\\Auth\\AuthenticationRequest', false, false ],
			[ 'MediaWiki\\Auth\\PasswordAuthenticationRequest', false, true ],
			[ 'MediaWiki\\Auth\\AuthenticationRequest', true, false ],
			[ 'MediaWiki\\Auth\\PasswordAuthenticationRequest', true, true ],
		];
	}

	public function testTestForAccountCreation() {
		$user = \User::newFromName( 'foo' );
		$req = new PasswordAuthenticationRequest();
		$req->username = 'Foo';
		$req->password = 'Bar';
		$req->retype = 'Bar';
		$reqs = [ 'MediaWiki\\Auth\\PasswordAuthenticationRequest' => $req ];

		$provider = $this->getProvider();
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] ),
			'No password request'
		);

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, validated'
		);

		$req->retype = 'Baz';
		$this->assertEquals(
			\StatusValue::newFatal( 'badretype' ),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, bad retype'
		);
		$req->retype = 'Bar';

		$this->validity->error( 'arbitrary warning' );
		$expect = \StatusValue::newGood();
		$expect->error( 'arbitrary warning' );
		$this->assertEquals(
			$expect,
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, not validated'
		);

		$provider = $this->getProvider( true );
		$this->validity->error( 'arbitrary warning' );
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, not validated, loginOnly'
		);
	}

	public function testAccountCreation() {
		$user = \User::newFromName( 'Foo' );

		$req = new PasswordAuthenticationRequest();
		$reqs = [ 'MediaWiki\\Auth\\PasswordAuthenticationRequest' => $req ];

		$provider = $this->getProvider( true );
		try {
			$provider->beginPrimaryAccountCreation( $user, [] );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		try {
			$provider->finishAccountCreation( $user, AuthenticationResponse::newPass() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		$provider = $this->getProvider( false );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, [] )
		);

		$req->username = 'foo';
		$req->password = null;
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		$req->username = null;
		$req->password = 'bar';
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		$req->username = 'foo';
		$req->password = 'bar';

		$expect = AuthenticationResponse::newPass( 'Foo' );
		$expect->createRequest = clone( $req );
		$expect->createRequest->username = 'Foo';
		$this->assertEquals( $expect, $provider->beginPrimaryAccountCreation( $user, $reqs ) );

		// We have to cheat a bit to avoid having to add a new user to
		// the database to test the actual setting of the password works right
		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', [ 'user_name' => $user ] );
		$cb = new \ScopedCallback( function () use ( $dbw, $user, $oldHash ) {
			$dbw->update( 'user', [ 'user_password' => $oldHash ], [ 'user_name' => $user ] );
		} );

		$user = \User::newFromName( 'UTSysop' );
		$req->username = $user->getName();
		$req->password = 'NewPassword';
		$expect = AuthenticationResponse::newPass( 'UTSysop' );
		$expect->createRequest = $req;

		$res2 = $provider->beginPrimaryAccountCreation( $user, $reqs );
		$this->assertEquals( $expect, $res2, 'Sanity check' );

		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status, 'sanity check' );

		$provider->finishAccountCreation( $user, $res2 );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::PASS, $ret->status, 'new password is set' );

	}

}
