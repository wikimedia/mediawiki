<?php

/**
 * @group AuthManager
 * @group Database
 * @covers LocalPasswordPrimaryAuthenticationProvider
 * @uses AbstractPasswordPrimaryAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 */
class LocalPasswordPrimaryAuthenticationProviderTest extends MediaWikiTestCase {

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
			$this->config = new HashConfig();
		}
		$config = new MultiConfig( array(
			$this->config,
			ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		) );

		if ( !$this->manager ) {
			$this->manager = new AuthManager( new FauxRequest(), $config );
		}
		$this->validity = Status::newGood();

		$that = $this;
		$provider = $this->getMock(
			'LocalPasswordPrimaryAuthenticationProvider',
			array( 'checkPasswordValidity' ),
			array( array( 'loginOnly' => $loginOnly ) )
		);
		$provider->method( 'checkPasswordValidity' )
			->will( $this->returnCallback( function () use ( $that ) {
				return $that->validity;
			} ) );
		$provider->setConfig( $config );
		$provider->setLogger( new Psr\Log\NullLogger() );
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

		$provider = new LocalPasswordPrimaryAuthenticationProvider( array( 'loginOnly' => true ) );

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_NONE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( 'UTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
	}

	/**
	 * @uses AuthManager
	 */
	public function testSetPasswordResetFlag() {
		// Set instance vars
		$this->getProvider();

		/// @todo: Because we're currently using User, which uses the global config...
		$this->setMwGlobals( array( 'wgPasswordExpireGrace' => 100 ) );

		$this->config->set( 'PasswordExpireGrace', 100 );
		$this->config->set( 'InvalidPasswordReset', true );

		$provider = new LocalPasswordPrimaryAuthenticationProvider();
		$provider->setConfig( $this->config );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			'*',
			array( 'user_name' => 'UTSysop' ),
			__METHOD__
		);

		$this->manager->removeAuthenticationData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() + 200 );
		$providerPriv->setPasswordResetFlag( 'UTSysop', Status::newGood(), $row );
		$this->assertNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

		$this->manager->removeAuthenticationData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 200 );
		$providerPriv->setPasswordResetFlag( 'UTSysop', Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$this->manager->removeAuthenticationData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 1 );
		$providerPriv->setPasswordResetFlag( 'UTSysop', Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$this->manager->removeAuthenticationData( null );
		$row->user_password_expires = null;
		$status = Status::newGood();
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( 'UTSysop', $status, $row );
		$ret = $this->manager->getAuthenticationData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );
	}

	/**
	 * @uses AuthManager
	 * @uses AuthenticationResponse
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
	 */
	public function testAuthentication() {
		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', array( 'user_name' => 'UTSysop' ) );
		$cb = new ScopedCallback( function () use ( $dbw, $oldHash ) {
			$dbw->update( 'user', array( 'user_password' => $oldHash ), array( 'user_name' => 'UTSysop' ) );
		} );
		$id = User::idFromName( 'UTSysop' );

		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'PasswordAuthenticationRequest' => $req );

		$provider = $this->getProvider();

		// General failures
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( array() )
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
		$this->validity = Status::newFatal( 'arbitrary-failure' );
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
		$this->manager->removeAuthenticationData( null );
		$this->validity = Status::newGood();
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

		// Successful auth with reset
		$this->manager->removeAuthenticationData( null );
		$this->validity->error( 'arbitrary-warning' );
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNotNull( $this->manager->getAuthenticationData( 'reset-pass' ) );

		// Wrong password
		$this->validity = Status::newGood();
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
		$dbw->update( 'user', array( 'user_password' => $password ), array( 'user_name' => 'UTSysop' ) );
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
			AuthenticationResponse::newPass( 'UTSysop', $req ),
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
		$dbw->update( 'user', array( 'user_password' => $password ), array( 'user_name' => 'UTSysop' ) );
		$req->password = 'FooBar';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$this->config->set( 'PasswordSalt', true );
		$password = md5( "$id-" . md5( 'FooBar' ) );
		$dbw->update( 'user', array( 'user_password' => $password ), array( 'user_name' => 'UTSysop' ) );
		$req->password = 'FooBar';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

	}

	/**
	 * @uses AuthManager
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
	 * @dataProvider provideProviderCanChangeAuthenticationData
	 * @param string $type
	 * @param string $user
	 * @param Status $validity
	 */
	public function testProviderCanChangeAuthenticationData( $type, $user, $validity ) {
		$expect = StatusValue::newGood();
		$expect->merge( $validity, true );

		if ( $type === 'PasswordAuthenticationRequest' ) {
			$req = new $type();
		} else {
			$req = $this->getMock( $type );
		}
		$req->username = $user;
		$req->password = 'NewPassword';

		$provider = $this->getProvider();
		$this->validity = $validity;
		$this->assertEquals( $expect, $provider->providerCanChangeAuthenticationData( $req ) );

		$provider = $this->getProvider( true );
		$this->assertEquals(
			StatusValue::newGood( 'ignored' ),
			$provider->providerCanChangeAuthenticationData( $req ),
			'loginOnly mode should claim to ignore all changes'
		);
	}

	public static function provideProviderCanChangeAuthenticationData() {
		$err = Status::newGood();
		$err->error( 'arbitrary-warning' );

		return array(
			array( 'AuthenticationRequest', 'UTSysop', Status::newGood( 'ignored' ) ),
			array( 'PasswordAuthenticationRequest', 'UTSysop', Status::newGood() ),
			array( 'PasswordAuthenticationRequest', 'UTSysop', $err ),
			array( 'PasswordAuthenticationRequest', 'UTSysop', Status::newFatal( 'arbitrary-error' ) ),
			array( 'PasswordAuthenticationRequest', 'DoesNotExist', Status::newGood( 'ignored' ) ),
			array( 'PasswordDomainAuthenticationRequest', 'UTSysop', Status::newGood() ),
		);
	}

	/**
	 * @uses AuthManager
	 * @uses AuthenticationResponse
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
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
		$oldHash = $dbw->selectField( 'user', 'user_password', array( 'user_name' => $user ) );
		$cb = new ScopedCallback( function () use ( $dbw, $user, $oldHash ) {
			$dbw->update( 'user', array( 'user_password' => $oldHash ), array( 'user_name' => $user ) );
		} );

		$provider = $this->getProvider( $loginOnly );

		// Sanity check
		$req = new PasswordAuthenticationRequest();
		$req->username = $user;
		$req->password = $oldpass;
		$reqs = array( 'PasswordAuthenticationRequest' => $req );
		$this->assertEquals(
			AuthenticationResponse::newPass( $user, $req ),
			$provider->beginPrimaryAuthentication( $reqs ),
			'Sanity check'
		);

		if ( $type === 'PasswordAuthenticationRequest' ) {
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
		} elseif ( $changed ) {
			$old = 'fail';
			$new = 'pass';
		} else {
			$old = 'pass';
			$new = 'fail';
		}

		$req->password = $oldpass;
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		if ( $old === 'pass' ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $user, $req ),
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
				AuthenticationResponse::newPass( $user, $req ),
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
	}

	public static function provideProviderChangeAuthenticationData() {
		return array(
			array( 'AuthenticationRequest', false, false ),
			array( 'PasswordAuthenticationRequest', false, true ),
			array( 'PasswordDomainAuthenticationRequest', false, true ),
			array( 'AuthenticationRequest', true, false ),
			array( 'PasswordAuthenticationRequest', true, true ),
			array( 'PasswordDomainAuthenticationRequest', true, true ),
		);
	}

	/**
	 * @uses AuthManager
	 */
	public function testTestForAccountCreation() {
		$user = User::newFromName( 'foo' );
		$req = new PasswordAuthenticationRequest();
		$req->username = 'Foo';
		$req->password = 'Bar';
		$reqs = array( 'PasswordAuthenticationRequest' => $req );

		$provider = $this->getProvider();
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, array() ),
			'No password request'
		);

		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, validated'
		);

		$this->validity->error( 'arbitrary warning' );
		$expect = StatusValue::newGood();
		$expect->error( 'arbitrary warning' );
		$this->assertEquals(
			$expect,
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, not validated'
		);

		$provider = $this->getProvider( true );
		$this->validity->error( 'arbitrary warning' );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, not validated, loginOnly'
		);
	}

	/**
	 * @uses AuthManager
	 * @uses AuthenticationResponse
	 * @uses PasswordAuthenticationRequest
	 */
	public function testAccountCreation() {
		$user = User::newFromName( 'Foo' );

		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'PasswordAuthenticationRequest' => $req );

		$provider = $this->getProvider( true );
		try {
			$provider->beginPrimaryAccountCreation( $user, array() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame( 'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage() );
		}

		try {
			$provider->finishAccountCreation( $user, AuthenticationResponse::newPass() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame( 'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage() );
		}

		$provider = $this->getProvider( false );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, array() )
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

		$req2 = clone( $req );
		$req2->username = 'Foo';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'Foo', $req2 ),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		// We have to cheat a bit to avoid having to add a new user to
		// the database to test the actual setting of the password works right
		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_password', array( 'user_name' => $user ) );
		$cb = new ScopedCallback( function () use ( $dbw, $user, $oldHash ) {
			$dbw->update( 'user', array( 'user_password' => $oldHash ), array( 'user_name' => $user ) );
		} );

		$user = User::newFromName( 'UTSysop' );
		$req->username = $user->getName();
		$req->password = 'NewPassword';
		$expect = AuthenticationResponse::newPass( 'UTSysop', $req );

		$res2 = $provider->beginPrimaryAccountCreation( $user, $reqs );
		$this->assertEquals( $expect, $res2, 'Sanity check' );

		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status, 'sanity check' );

		$provider->finishAccountCreation( $user, $res2 );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::PASS, $ret->status, 'new password is set' );

	}

}
