<?php

namespace MediaWiki\Auth;

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use Psr\Container\ContainerInterface;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider
 */
class TemporaryPasswordPrimaryAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {

	private $manager = null;
	private $config = null;
	private $validity = null;

	/**
	 * Get an instance of the provider
	 *
	 * $provider->checkPasswordValidity is mocked to return $this->validity,
	 * because we don't need to test that here.
	 *
	 * @param array $params
	 * @return TemporaryPasswordPrimaryAuthenticationProvider
	 */
	protected function getProvider( $params = [] ) {
		if ( !$this->config ) {
			$this->config = new \HashConfig( [
				'EmailEnabled' => true,
			] );
		}
		$config = new \MultiConfig( [
			$this->config,
			MediaWikiServices::getInstance()->getMainConfig()
		] );
		$hookContainer = $this->createHookContainer();

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

		$mockedMethods[] = 'checkPasswordValidity';
		$provider = $this->getMockBuilder( TemporaryPasswordPrimaryAuthenticationProvider::class )
			->setMethods( $mockedMethods )
			->setConstructorArgs( [ $params ] )
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

	protected function hookMailer( $func = null ) {
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		if ( $func ) {
			$reset = $hookContainer->scopedRegister( 'AlternateUserMailer', $func, true );
		} else {
			$reset = $hookContainer->scopedRegister( 'AlternateUserMailer', function () {
				$this->fail( 'AlternateUserMailer hook called unexpectedly' );
				return false;
			}, true );
		}
		return $reset;
	}

	public function testBasics() {
		$provider = new TemporaryPasswordPrimaryAuthenticationProvider();

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_CREATE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( 'UTSysop' ) );
		$this->assertTrue( $provider->testUserExists( 'uTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
		$this->assertFalse( $provider->testUserExists( '<invalid>' ) );

		$req = new PasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = '<invalid>';
		$provider->providerChangeAuthenticationData( $req );
	}

	public function testConfig() {
		$config = new \HashConfig( [
			'EnableEmail' => false,
			'NewPasswordExpiry' => 100,
			'PasswordReminderResendTime' => 101,
			'AllowRequiringEmailForResets' => false,
		] );

		$p = TestingAccessWrapper::newFromObject( new TemporaryPasswordPrimaryAuthenticationProvider() );
		$p->setConfig( $config );
		$this->assertSame( false, $p->emailEnabled );
		$this->assertSame( 100, $p->newPasswordExpiry );
		$this->assertSame( 101, $p->passwordReminderResendTime );

		$p = TestingAccessWrapper::newFromObject( new TemporaryPasswordPrimaryAuthenticationProvider( [
			'emailEnabled' => true,
			'newPasswordExpiry' => 42,
			'passwordReminderResendTime' => 43,
			'allowRequiringEmailForResets' => true,
		] ) );
		$p->setConfig( $config );
		$this->assertSame( true, $p->emailEnabled );
		$this->assertSame( 42, $p->newPasswordExpiry );
		$this->assertSame( 43, $p->passwordReminderResendTime );
		$this->assertSame( true, $p->allowRequiringEmail );
	}

	public function testTestUserCanAuthenticate() {
		$user = self::getMutableTestUser()->getUser();

		$dbw = wfGetDB( DB_MASTER );
		$config = MediaWikiServices::getInstance()->getMainConfig();
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordFactory = new \PasswordFactory( $config->get( 'PasswordConfig' ), 'A' );

		$pwhash = $passwordFactory->newFromPlaintext( 'password' )->toString();

		$provider = $this->getProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$this->assertFalse( $provider->testUserCanAuthenticate( '<invalid>' ) );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'DoesNotExist' ) );

		$dbw->update(
			'user',
			[
				'user_newpassword' => \PasswordFactory::newInvalidPassword()->toString(),
				'user_newpass_time' => null,
			],
			[ 'user_id' => $user->getId() ]
		);
		$this->assertFalse( $provider->testUserCanAuthenticate( $user->getName() ) );

		$dbw->update(
			'user',
			[
				'user_newpassword' => $pwhash,
				'user_newpass_time' => null,
			],
			[ 'user_id' => $user->getId() ]
		);
		$this->assertTrue( $provider->testUserCanAuthenticate( $user->getName() ) );
		$this->assertTrue( $provider->testUserCanAuthenticate( lcfirst( $user->getName() ) ) );

		$dbw->update(
			'user',
			[
				'user_newpassword' => $pwhash,
				'user_newpass_time' => $dbw->timestamp( time() - 10 ),
			],
			[ 'user_id' => $user->getId() ]
		);
		$providerPriv->newPasswordExpiry = 100;
		$this->assertTrue( $provider->testUserCanAuthenticate( $user->getName() ) );
		$providerPriv->newPasswordExpiry = 1;
		$this->assertFalse( $provider->testUserCanAuthenticate( $user->getName() ) );

		$dbw->update(
			'user',
			[
				'user_newpassword' => \PasswordFactory::newInvalidPassword()->toString(),
				'user_newpass_time' => null,
			],
			[ 'user_id' => $user->getId() ]
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $options
	 * @param array $expected
	 */
	public function testGetAuthenticationRequests( $action, $options, $expected ) {
		$actual = $this->getProvider( [ 'emailEnabled' => true ] )
			->getAuthenticationRequests( $action, $options );
		foreach ( $actual as $req ) {
			if ( $req instanceof TemporaryPasswordAuthenticationRequest && $req->password !== null ) {
				$req->password = 'random';
			}
		}
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetAuthenticationRequests() {
		$anon = [ 'username' => null ];
		$loggedIn = [ 'username' => 'UTSysop' ];

		return [
			[ AuthManager::ACTION_LOGIN, $anon, [
				new PasswordAuthenticationRequest
			] ],
			[ AuthManager::ACTION_LOGIN, $loggedIn, [
				new PasswordAuthenticationRequest
			] ],
			[ AuthManager::ACTION_CREATE, $anon, [] ],
			[ AuthManager::ACTION_CREATE, $loggedIn, [
				new TemporaryPasswordAuthenticationRequest( 'random' )
			] ],
			[ AuthManager::ACTION_LINK, $anon, [] ],
			[ AuthManager::ACTION_LINK, $loggedIn, [] ],
			[ AuthManager::ACTION_CHANGE, $anon, [
				new TemporaryPasswordAuthenticationRequest( 'random' )
			] ],
			[ AuthManager::ACTION_CHANGE, $loggedIn, [
				new TemporaryPasswordAuthenticationRequest( 'random' )
			] ],
			[ AuthManager::ACTION_REMOVE, $anon, [
				new TemporaryPasswordAuthenticationRequest
			] ],
			[ AuthManager::ACTION_REMOVE, $loggedIn, [
				new TemporaryPasswordAuthenticationRequest
			] ],
		];
	}

	public function testAuthentication() {
		$user = self::getMutableTestUser()->getUser();

		$password = 'TemporaryPassword';
		$hash = ':A:' . md5( $password );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			[ 'user_newpassword' => $hash, 'user_newpass_time' => $dbw->timestamp( time() - 10 ) ],
			[ 'user_id' => $user->getId() ]
		);

		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_LOGIN;
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$providerPriv->newPasswordExpiry = 100;

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
		$req->username = $user->getName();
		$req->password = $password;
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
			AuthenticationResponse::newPass( $user->getName() ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = \Status::newGood();
		$req->username = lcfirst( $user->getName() );
		$this->assertEquals(
			AuthenticationResponse::newPass( $user->getName() ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );
		$req->username = $user->getName();

		// Expired password
		$providerPriv->newPasswordExpiry = 1;
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

		// Bad password
		$providerPriv->newPasswordExpiry = 100;
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
		if ( $type === PasswordAuthenticationRequest::class ||
			$type === TemporaryPasswordAuthenticationRequest::class
		) {
			$req = new $type();
		} else {
			$req = $this->createMock( $type );
		}
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = $user;
		$req->password = 'NewPassword';

		$provider = $this->getProvider();
		$this->validity = $validity;
		$this->assertEquals( $expect1, $provider->providerAllowsAuthenticationDataChange( $req, false ) );
		$this->assertEquals( $expect2, $provider->providerAllowsAuthenticationDataChange( $req, true ) );
	}

	public static function provideProviderAllowsAuthenticationDataChange() {
		$err = \StatusValue::newGood();
		$err->error( 'arbitrary-warning' );

		return [
			[ AuthenticationRequest::class, 'UTSysop', \Status::newGood(),
				\StatusValue::newGood( 'ignored' ), \StatusValue::newGood( 'ignored' ) ],
			[ PasswordAuthenticationRequest::class, 'UTSysop', \Status::newGood(),
				\StatusValue::newGood( 'ignored' ), \StatusValue::newGood( 'ignored' ) ],
			[ TemporaryPasswordAuthenticationRequest::class, 'UTSysop', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood() ],
			[ TemporaryPasswordAuthenticationRequest::class, 'uTSysop', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood() ],
			[ TemporaryPasswordAuthenticationRequest::class, 'UTSysop', \Status::wrap( $err ),
				\StatusValue::newGood(), $err ],
			[ TemporaryPasswordAuthenticationRequest::class, 'UTSysop',
				\Status::newFatal( 'arbitrary-error' ), \StatusValue::newGood(),
				\StatusValue::newFatal( 'arbitrary-error' ) ],
			[ TemporaryPasswordAuthenticationRequest::class, 'DoesNotExist', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood( 'ignored' ) ],
			[ TemporaryPasswordAuthenticationRequest::class, '<invalid>', \Status::newGood(),
				\StatusValue::newGood(), \StatusValue::newGood( 'ignored' ) ],
		];
	}

	/**
	 * @dataProvider provideProviderChangeAuthenticationData
	 * @param string $user
	 * @param string $type
	 * @param bool $changed
	 */
	public function testProviderChangeAuthenticationData( $user, $type, $changed ) {
		$cuser = ucfirst( $user );
		$oldpass = 'OldTempPassword';
		$newpass = 'NewTempPassword';

		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_newpassword', [ 'user_name' => $cuser ] );
		$cb = new ScopedCallback( function () use ( $dbw, $cuser, $oldHash ) {
			$dbw->update( 'user', [ 'user_newpassword' => $oldHash ], [ 'user_name' => $cuser ] );
		} );

		$hash = ':A:' . md5( $oldpass );
		$dbw->update(
			'user',
			[ 'user_newpassword' => $hash, 'user_newpass_time' => $dbw->timestamp( time() + 10 ) ],
			[ 'user_name' => $cuser ]
		);

		$provider = $this->getProvider();

		// Sanity check
		$loginReq = new PasswordAuthenticationRequest();
		$loginReq->action = AuthManager::ACTION_CHANGE;
		$loginReq->username = $user;
		$loginReq->password = $oldpass;
		$loginReqs = [ PasswordAuthenticationRequest::class => $loginReq ];
		$this->assertEquals(
			AuthenticationResponse::newPass( $cuser ),
			$provider->beginPrimaryAuthentication( $loginReqs ),
			'Sanity check'
		);

		if ( $type === PasswordAuthenticationRequest::class ||
			$type === TemporaryPasswordAuthenticationRequest::class
		) {
			$changeReq = new $type();
		} else {
			$changeReq = $this->createMock( $type );
		}
		$changeReq->action = AuthManager::ACTION_CHANGE;
		$changeReq->username = $user;
		$changeReq->password = $newpass;
		$resetMailer = $this->hookMailer();
		$provider->providerChangeAuthenticationData( $changeReq );
		ScopedCallback::consume( $resetMailer );

		$loginReq->password = $oldpass;
		$ret = $provider->beginPrimaryAuthentication( $loginReqs );
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

		$loginReq->password = $newpass;
		$ret = $provider->beginPrimaryAuthentication( $loginReqs );
		if ( $changed ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $cuser ),
				$ret,
				'new password should pass'
			);
			$this->assertNotNull(
				$dbw->selectField( 'user', 'user_newpass_time', [ 'user_name' => $cuser ] )
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
			$this->assertNull(
				$dbw->selectField( 'user', 'user_newpass_time', [ 'user_name' => $cuser ] )
			);
		}
	}

	public static function provideProviderChangeAuthenticationData() {
		return [
			[ 'UTSysop', AuthenticationRequest::class, false ],
			[ 'UTSysop', PasswordAuthenticationRequest::class, false ],
			[ 'UTSysop', TemporaryPasswordAuthenticationRequest::class, true ],
		];
	}

	public function testProviderChangeAuthenticationDataEmail() {
		$user = self::getMutableTestUser()->getUser();

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			[ 'user_newpass_time' => $dbw->timestamp( time() - 5 * 3600 ) ],
			[ 'user_id' => $user->getId() ]
		);

		$req = TemporaryPasswordAuthenticationRequest::newRandom();
		$req->username = $user->getName();
		$req->mailpassword = true;

		$provider = $this->getProvider( [ 'emailEnabled' => false ] );
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newFatal( 'passwordreset-emaildisabled' ), $status );

		$provider = $this->getProvider( [
			'emailEnabled' => true, 'passwordReminderResendTime' => 10
		] );
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newFatal( 'throttled-mailpassword', 10 ), $status );

		$provider = $this->getProvider( [
			'emailEnabled' => true, 'passwordReminderResendTime' => 3
		] );
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertFalse( $status->hasMessage( 'throttled-mailpassword' ) );

		$dbw->update(
			'user',
			[ 'user_newpass_time' => $dbw->timestamp( time() + 5 * 3600 ) ],
			[ 'user_id' => $user->getId() ]
		);
		$provider = $this->getProvider( [
			'emailEnabled' => true, 'passwordReminderResendTime' => 0
		] );
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertFalse( $status->hasMessage( 'throttled-mailpassword' ) );

		$req->caller = null;
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newFatal( 'passwordreset-nocaller' ), $status );

		$req->caller = '127.0.0.256';
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newFatal( 'passwordreset-nosuchcaller', '127.0.0.256' ),
			$status );

		$req->caller = '<Invalid>';
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newFatal( 'passwordreset-nosuchcaller', '<Invalid>' ),
			$status );

		$req->caller = '127.0.0.1';
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newGood(), $status );

		$req->caller = $user->getName();
		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( \StatusValue::newGood(), $status );

		$mailed = false;
		$resetMailer = $this->hookMailer( function ( $headers, $to, $from, $subject, $body )
			use ( &$mailed, $req, $user )
		{
			$mailed = true;
			$this->assertSame( $user->getEmail(), $to[0]->address );
			$this->assertStringContainsString( $req->password, $body );
			return false;
		} );
		$provider->providerChangeAuthenticationData( $req );
		ScopedCallback::consume( $resetMailer );
		$this->assertTrue( $mailed );

		$priv = TestingAccessWrapper::newFromObject( $provider );
		$req->username = '<invalid>';
		$status = $priv->sendPasswordResetEmail( $req );
		$this->assertEquals( \Status::newFatal( 'noname' ), $status );
	}

	public function testTestForAccountCreation() {
		$user = \User::newFromName( 'foo' );
		$req = new TemporaryPasswordAuthenticationRequest();
		$req->username = 'Foo';
		$req->password = 'Bar';
		$reqs = [ TemporaryPasswordAuthenticationRequest::class => $req ];

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

		$this->validity->error( 'arbitrary warning' );
		$expect = \StatusValue::newGood();
		$expect->error( 'arbitrary warning' );
		$this->assertEquals(
			$expect,
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, not validated'
		);
	}

	public function testAccountCreation() {
		$resetMailer = $this->hookMailer();

		$user = \User::newFromName( 'Foo' );

		$req = new TemporaryPasswordAuthenticationRequest();
		$reqs = [ TemporaryPasswordAuthenticationRequest::class => $req ];

		$authreq = new PasswordAuthenticationRequest();
		$authreq->action = AuthManager::ACTION_CREATE;
		$authreqs = [ PasswordAuthenticationRequest::class => $authreq ];

		$provider = $this->getProvider();

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
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'no-email' ) );

		$user = self::getMutableTestUser()->getUser();
		$req->username = $authreq->username = $user->getName();
		$req->password = $authreq->password = 'NewPassword';
		$expect = AuthenticationResponse::newPass( $user->getName() );
		$expect->createRequest = $req;

		$res2 = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );
		$this->assertEquals( $expect, $res2, 'Sanity check' );

		$ret = $provider->beginPrimaryAuthentication( $authreqs );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status, 'sanity check' );

		$this->assertSame( null, $provider->finishAccountCreation( $user, $user, $res2 ) );

		$ret = $provider->beginPrimaryAuthentication( $authreqs );
		$this->assertEquals( AuthenticationResponse::PASS, $ret->status, 'new password is set' );
	}

	public function testAccountCreationEmail() {
		$creator = \User::newFromName( 'Foo' );

		$user = self::getMutableTestUser()->getUser();
		$user->setEmail( null );

		$req = TemporaryPasswordAuthenticationRequest::newRandom();
		$req->username = $user->getName();
		$req->mailpassword = true;

		$provider = $this->getProvider( [ 'emailEnabled' => false ] );
		$status = $provider->testForAccountCreation( $user, $creator, [ $req ] );
		$this->assertEquals( \StatusValue::newFatal( 'emaildisabled' ), $status );

		$provider = $this->getProvider( [ 'emailEnabled' => true ] );
		$status = $provider->testForAccountCreation( $user, $creator, [ $req ] );
		$this->assertEquals( \StatusValue::newFatal( 'noemailcreate' ), $status );

		$user->setEmail( 'test@localhost.localdomain' );
		$status = $provider->testForAccountCreation( $user, $creator, [ $req ] );
		$this->assertEquals( \StatusValue::newGood(), $status );

		$mailed = false;
		$resetMailer = $this->hookMailer( function ( $headers, $to, $from, $subject, $body )
			use ( &$mailed, $req )
		{
			$mailed = true;
			$this->assertSame( 'test@localhost.localdomain', $to[0]->address );
			$this->assertStringContainsString( $req->password, $body );
			return false;
		} );

		$expect = AuthenticationResponse::newPass( $user->getName() );
		$expect->createRequest = clone $req;
		$expect->createRequest->username = $user->getName();
		$res = $provider->beginPrimaryAccountCreation( $user, $creator, [ $req ] );
		$this->assertEquals( $expect, $res );
		$this->assertTrue( $this->manager->getAuthenticationSessionData( 'no-email' ) );
		$this->assertFalse( $mailed );

		$this->assertSame( 'byemail', $provider->finishAccountCreation( $user, $creator, $res ) );
		$this->assertTrue( $mailed );

		ScopedCallback::consume( $resetMailer );
		$this->assertTrue( $mailed );
	}

}
