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
			LocalPasswordPrimaryAuthenticationProvider::class,
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
		$this->assertTrue( $provider->testUserExists( 'uTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
		$this->assertFalse( $provider->testUserExists( '<invalid>' ) );

		$provider = new LocalPasswordPrimaryAuthenticationProvider( [ 'loginOnly' => true ] );

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_NONE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( 'UTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );

		$req = new PasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = '<invalid>';
		$provider->providerChangeAuthenticationData( $req );
	}

	public function testTestUserCanAuthenticate() {
		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', [ 'user_name' => 'UTSysop' ] );
		$cb = new \ScopedCallback( function () use ( $dbw, $oldHash ) {
			$dbw->update( 'user', [ 'user_password' => $oldHash ], [ 'user_name' => 'UTSysop' ] );
		} );
		$id = \User::idFromName( 'UTSysop' );

		$provider = $this->getProvider();

		$this->assertFalse( $provider->testUserCanAuthenticate( '<invalid>' ) );

		$this->assertFalse( $provider->testUserCanAuthenticate( 'DoesNotExist' ) );

		$this->assertTrue( $provider->testUserCanAuthenticate( 'UTSysop' ) );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'uTSysop' ) );

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
		$req->action = AuthManager::ACTION_LOGIN;
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

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

		$req->username = '<invalid>';
		$req->password = 'WhoCares';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
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

		// Successful auth after normalizing name
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = \Status::newGood();
		$req->username = 'uTSysop';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop' ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );
		$req->username = 'UTSysop';

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
	 * @param \Status $validity Result of the password validity check
	 * @param \StatusValue $expect1 Expected result with $checkData = false
	 * @param \StatusValue $expect2 Expected result with $checkData = true
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, $user, \Status $validity,
		\StatusValue $expect1, \StatusValue $expect2
	) {
		if ( $type === PasswordAuthenticationRequest::class ) {
			$req = new $type();
		} elseif ( $type === PasswordDomainAuthenticationRequest::class ) {
			$req = new $type( [] );
		} else {
			$req = $this->getMock( $type );
		}
		$req->action = AuthManager::ACTION_CHANGE;
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
			$expect2->getValue() === 'ignored' ? $expect2 : \StatusValue::newFatal( 'badretype' ),
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
		$err = \StatusValue::newGood();
		$err->error( 'arbitrary-warning' );

		return [
			[ AuthenticationRequest::class, 'UTSysop', \Status::newGood(),
				\StatusValue::newGood( 'ignored' ), \StatusValue::newGood( 'ignored' ) ],
			[ PasswordAuthenticationRequest::class, 'UTSysop', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood() ],
			[ PasswordAuthenticationRequest::class, 'uTSysop', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood() ],
			[ PasswordAuthenticationRequest::class, 'UTSysop', \Status::wrap( $err ),
				\StatusValue::newGood(), $err ],
			[ PasswordAuthenticationRequest::class, 'UTSysop', \Status::newFatal( 'arbitrary-error' ),
				\StatusValue::newGood(), \StatusValue::newFatal( 'arbitrary-error' ) ],
			[ PasswordAuthenticationRequest::class, 'DoesNotExist', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood( 'ignored' ) ],
			[ PasswordDomainAuthenticationRequest::class, 'UTSysop', \Status::newGood(),
				\StatusValue::newGood( 'ignored' ), \StatusValue::newGood( 'ignored' ) ],
		];
	}

	/**
	 * @dataProvider provideProviderChangeAuthenticationData
	 * @param string $user
	 * @param string $type
	 * @param bool $loginOnly
	 * @param bool $changed
	 */
	public function testProviderChangeAuthenticationData( $user, $type, $loginOnly, $changed ) {
		$cuser = ucfirst( $user );
		$oldpass = 'UTSysopPassword';
		$newpass = 'NewPassword';

		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', [ 'user_name' => $cuser ] );
		$oldExpiry = $dbw->selectField( 'user', 'user_password_expires', [ 'user_name' => $cuser ] );
		$cb = new \ScopedCallback( function () use ( $dbw, $cuser, $oldHash, $oldExpiry ) {
			$dbw->update(
				'user',
				[
					'user_password' => $oldHash,
					'user_password_expires' => $oldExpiry,
				],
				[ 'user_name' => $cuser ]
			);
		} );

		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'ResetPasswordExpiration' => [ function ( $user, &$expires ) {
				$expires = '30001231235959';
			} ]
		] );

		$provider = $this->getProvider( $loginOnly );

		// Sanity check
		$loginReq = new PasswordAuthenticationRequest();
		$loginReq->action = AuthManager::ACTION_LOGIN;
		$loginReq->username = $user;
		$loginReq->password = $oldpass;
		$loginReqs = [ PasswordAuthenticationRequest::class => $loginReq ];
		$this->assertEquals(
			AuthenticationResponse::newPass( $cuser ),
			$provider->beginPrimaryAuthentication( $loginReqs ),
			'Sanity check'
		);

		if ( $type === PasswordAuthenticationRequest::class ) {
			$changeReq = new $type();
		} else {
			$changeReq = $this->getMock( $type );
		}
		$changeReq->action = AuthManager::ACTION_CHANGE;
		$changeReq->username = $user;
		$changeReq->password = $newpass;
		$provider->providerChangeAuthenticationData( $changeReq );

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

		$loginReq->password = $oldpass;
		$ret = $provider->beginPrimaryAuthentication( $loginReqs );
		if ( $old === 'pass' ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $cuser ),
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

		$loginReq->password = $newpass;
		$ret = $provider->beginPrimaryAuthentication( $loginReqs );
		if ( $new === 'pass' ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $cuser ),
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
			$dbw->selectField( 'user', 'user_password_expires', [ 'user_name' => $cuser ] )
		);
	}

	public static function provideProviderChangeAuthenticationData() {
		return [
			[ 'UTSysop', AuthenticationRequest::class, false, false ],
			[ 'UTSysop', PasswordAuthenticationRequest::class, false, true ],
			[ 'UTSysop', AuthenticationRequest::class, true, false ],
			[ 'UTSysop', PasswordAuthenticationRequest::class, true, true ],
			[ 'uTSysop', PasswordAuthenticationRequest::class, false, true ],
			[ 'uTSysop', PasswordAuthenticationRequest::class, true, true ],
		];
	}

	public function testTestForAccountCreation() {
		$user = \User::newFromName( 'foo' );
		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_CREATE;
		$req->username = 'Foo';
		$req->password = 'Bar';
		$req->retype = 'Bar';
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

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
		$req->action = AuthManager::ACTION_CREATE;
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider( true );
		try {
			$provider->beginPrimaryAccountCreation( $user, $user, [] );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		try {
			$provider->finishAccountCreation( $user, $user, AuthenticationResponse::newPass() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		$provider = $this->getProvider( false );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $user, [] )
		);

		$req->username = 'foo';
		$req->password = null;
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $user, $reqs )
		);

		$req->username = null;
		$req->password = 'bar';
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $user, $reqs )
		);

		$req->username = 'foo';
		$req->password = 'bar';

		$expect = AuthenticationResponse::newPass( 'Foo' );
		$expect->createRequest = clone( $req );
		$expect->createRequest->username = 'Foo';
		$this->assertEquals( $expect, $provider->beginPrimaryAccountCreation( $user, $user, $reqs ) );

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

		$res2 = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );
		$this->assertEquals( $expect, $res2, 'Sanity check' );

		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status, 'sanity check' );

		$this->assertNull( $provider->finishAccountCreation( $user, $user, $res2 ) );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::PASS, $ret->status, 'new password is set' );

	}

}
