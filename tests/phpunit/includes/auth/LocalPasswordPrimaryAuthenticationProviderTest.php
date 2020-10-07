<?php

namespace MediaWiki\Auth;

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use Psr\Container\ContainerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider
 */
class LocalPasswordPrimaryAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {

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
			MediaWikiServices::getInstance()->getMainConfig()
		] );

		// We need a real HookContainer since testProviderChangeAuthenticationData()
		// modifies $wgHooks
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();

		if ( !$this->manager ) {
			$services = $this->createNoOpAbstractMock( ContainerInterface::class );
			$objectFactory = new \Wikimedia\ObjectFactory( $services );
			$permManager = $this->createNoOpMock( PermissionManager::class );

			$this->manager = new AuthManager(
				new \FauxRequest(),
				$config,
				$objectFactory,
				$permManager,
				$hookContainer
			);
		}
		$this->validity = \Status::newGood();
		$provider = $this->getMockBuilder( LocalPasswordPrimaryAuthenticationProvider::class )
			->setMethods( [ 'checkPasswordValidity' ] )
			->setConstructorArgs( [ [ 'loginOnly' => $loginOnly ] ] )
			->getMock();

		$provider->expects( $this->any() )->method( 'checkPasswordValidity' )
			->will( $this->returnCallback( function () {
				return $this->validity;
			} ) );
		$provider->setConfig( $config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );
		$provider->setHookContainer( $hookContainer );

		return $provider;
	}

	public function testBasics() {
		$user = $this->getMutableTestUser()->getUser();
		$userName = $user->getName();
		$lowerInitialUserName = mb_strtolower( $userName[0] ) . substr( $userName, 1 );

		$provider = new LocalPasswordPrimaryAuthenticationProvider();

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_CREATE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( $userName ) );
		$this->assertTrue( $provider->testUserExists( $lowerInitialUserName ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
		$this->assertFalse( $provider->testUserExists( '<invalid>' ) );

		$provider = new LocalPasswordPrimaryAuthenticationProvider( [ 'loginOnly' => true ] );

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_NONE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( $userName ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );

		$req = new PasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = '<invalid>';
		$provider->providerChangeAuthenticationData( $req );
	}

	public function testTestUserCanAuthenticate() {
		$user = $this->getMutableTestUser()->getUser();
		$userName = $user->getName();
		$dbw = wfGetDB( DB_MASTER );

		$provider = $this->getProvider();

		$this->assertFalse( $provider->testUserCanAuthenticate( '<invalid>' ) );

		$this->assertFalse( $provider->testUserCanAuthenticate( 'DoesNotExist' ) );

		$this->assertTrue( $provider->testUserCanAuthenticate( $userName ) );
		$lowerInitialUserName = mb_strtolower( $userName[0] ) . substr( $userName, 1 );
		$this->assertTrue( $provider->testUserCanAuthenticate( $lowerInitialUserName ) );

		$dbw->update(
			'user',
			[ 'user_password' => \PasswordFactory::newInvalidPassword()->toString() ],
			[ 'user_name' => $userName ]
		);
		$this->assertFalse( $provider->testUserCanAuthenticate( $userName ) );

		// Really old format
		$dbw->update(
			'user',
			[ 'user_password' => '0123456789abcdef0123456789abcdef' ],
			[ 'user_name' => $userName ]
		);
		$this->assertTrue( $provider->testUserCanAuthenticate( $userName ) );
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
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$user = $this->getMutableTestUser()->getUser();
		$userName = $user->getName();
		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			'*',
			[ 'user_name' => $userName ],
			__METHOD__
		);

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() + 200 );
		$providerPriv->setPasswordResetFlag( $userName, \Status::newGood(), $row );
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 200 );
		$providerPriv->setPasswordResetFlag( $userName, \Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 1 );
		$providerPriv->setPasswordResetFlag( $userName, \Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = \Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $userName, $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = \Status::newGood( [ 'forceChange' => true ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $userName, $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = \Status::newGood( [ 'suggestChangeOnLogin' => false, ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $userName, $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNull( $ret );
	}

	public function testAuthentication() {
		$testUser = $this->getMutableTestUser();
		$userName = $testUser->getUser()->getName();

		$dbw = wfGetDB( DB_MASTER );
		$id = \User::idFromName( $userName );

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
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

		// Validation failure
		$req->username = $userName;
		$req->password = $testUser->getPassword();
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
			AuthenticationResponse::newPass( $userName ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		// Successful auth after normalizing name
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = \Status::newGood();
		$req->username = mb_strtolower( $userName[0] ) . substr( $userName, 1 );
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );
		$req->username = $userName;

		// Successful auth with reset
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = \Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$this->validity->error( 'arbitrary-warning' );
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
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
		$dbw->update( 'user', [ 'user_password' => $password ], [ 'user_name' => $userName ] );
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
			AuthenticationResponse::newPass( $userName ),
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
		$this->config->set( 'PasswordSalt', true );
		$password = md5( "$id-" . md5( 'FooBar' ) );
		$dbw->update( 'user', [ 'user_password' => $password ], [ 'user_name' => $userName ] );
		$req->password = 'FooBar';
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
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
			$req = $this->createMock( $type );
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
	 * @param callable|bool $usernameTransform
	 * @param string $type
	 * @param bool $loginOnly
	 * @param bool $changed
	 */
	public function testProviderChangeAuthenticationData(
			$usernameTransform, $type, $loginOnly, $changed ) {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser()->getName();
		if ( is_callable( $usernameTransform ) ) {
			$user = $usernameTransform( $user );
		}
		$cuser = ucfirst( $user );
		$oldpass = $testUser->getPassword();
		$newpass = 'NewPassword';

		$dbw = wfGetDB( DB_MASTER );
		$oldExpiry = $dbw->selectField( 'user', 'user_password_expires', [ 'user_name' => $cuser ] );

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
			$changeReq = $this->createMock( $type );
		}
		$changeReq->action = AuthManager::ACTION_CHANGE;
		$changeReq->username = $user;
		$changeReq->password = $newpass;
		$provider->providerChangeAuthenticationData( $changeReq );

		if ( $loginOnly && $changed ) {
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
			wfTimestampOrNull(
				TS_MW,
				$dbw->selectField( 'user', 'user_password_expires', [ 'user_name' => $cuser ] )
			)
		);
	}

	public static function provideProviderChangeAuthenticationData() {
		return [
			[ false, AuthenticationRequest::class, false, false ],
			[ false, PasswordAuthenticationRequest::class, false, true ],
			[ false, AuthenticationRequest::class, true, false ],
			[ false, PasswordAuthenticationRequest::class, true, true ],
			[ 'ucfirst', PasswordAuthenticationRequest::class, false, true ],
			[ 'ucfirst', PasswordAuthenticationRequest::class, true, true ],
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
		$expect->createRequest = clone $req;
		$expect->createRequest->username = 'Foo';
		$this->assertEquals( $expect, $provider->beginPrimaryAccountCreation( $user, $user, $reqs ) );

		// We have to cheat a bit to avoid having to add a new user to
		// the database to test the actual setting of the password works right
		$dbw = wfGetDB( DB_MASTER );

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
