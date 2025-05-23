<?php

namespace MediaWiki\Tests\Auth;

use BadMethodCallException;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\PasswordDomainAuthenticationRequest;
use MediaWiki\Auth\PrimaryAuthenticationProvider;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserNameUtils;
use MediaWikiIntegrationTestCase;
use StatusValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider
 */
class LocalPasswordPrimaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;
	use DummyServicesTrait;

	/** @var AuthManager|null */
	private $manager = null;
	/** @var HashConfig|null */
	private $config = null;
	/** @var Status|null */
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
		$mwServices = $this->getServiceContainer();
		if ( !$this->config ) {
			$this->config = new HashConfig();
		}
		$config = new MultiConfig( [
			$this->config,
			$mwServices->getMainConfig()
		] );

		// We need a real HookContainer since testProviderChangeAuthenticationData()
		// modifies $wgHooks
		$hookContainer = $mwServices->getHookContainer();

		if ( !$this->manager ) {
			$userNameUtils = $this->createNoOpMock( UserNameUtils::class );

			$this->manager = new AuthManager(
				new FauxRequest(),
				$config,
				$this->getDummyObjectFactory(),
				$hookContainer,
				$mwServices->getReadOnlyMode(),
				$userNameUtils,
				$mwServices->getBlockManager(),
				$mwServices->getWatchlistManager(),
				$mwServices->getDBLoadBalancer(),
				$mwServices->getContentLanguage(),
				$mwServices->getLanguageConverterFactory(),
				$mwServices->getBotPasswordStore(),
				$mwServices->getUserFactory(),
				$mwServices->getUserIdentityLookup(),
				$mwServices->getUserOptionsManager(),
				$mwServices->getNotificationService()
			);
		}
		$this->validity = Status::newGood();
		$provider = $this->getMockBuilder( LocalPasswordPrimaryAuthenticationProvider::class )
			->onlyMethods( [ 'checkPasswordValidity' ] )
			->setConstructorArgs( [
				$mwServices->getConnectionProvider(),
				[ 'loginOnly' => $loginOnly ]
			] )
			->getMock();

		$provider->method( 'checkPasswordValidity' )
			->willReturnCallback( function () {
				return $this->validity;
			} );
		$this->initProvider(
			$provider, $config, null, $this->manager, $hookContainer, $this->getServiceContainer()->getUserNameUtils()
		);

		return $provider;
	}

	public function testBasics() {
		$user = $this->getMutableTestUser()->getUser();
		$userName = $user->getName();
		$lowerInitialUserName = mb_strtolower( $userName[0] ) . substr( $userName, 1 );

		$provider = $this->getProvider();

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_CREATE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( $userName ) );
		$this->assertTrue( $provider->testUserExists( $lowerInitialUserName ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
		$this->assertFalse( $provider->testUserExists( '<invalid>' ) );

		$provider = $this->getProvider( [ 'loginOnly' => true ] );

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
		$dbw = $this->getDb();

		$provider = $this->getProvider();

		$this->assertFalse( $provider->testUserCanAuthenticate( '<invalid>' ) );

		$this->assertFalse( $provider->testUserCanAuthenticate( 'DoesNotExist' ) );

		$this->assertTrue( $provider->testUserCanAuthenticate( $userName ) );
		$lowerInitialUserName = mb_strtolower( $userName[0] ) . substr( $userName, 1 );
		$this->assertTrue( $provider->testUserCanAuthenticate( $lowerInitialUserName ) );

		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_password' => PasswordFactory::newInvalidPassword()->toString() ] )
			->where( [ 'user_name' => $userName ] )
			->caller( __METHOD__ )
			->execute();
		$this->assertFalse( $provider->testUserCanAuthenticate( $userName ) );

		// Really old format
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_password' => '0123456789abcdef0123456789abcdef' ] )
			->where( [ 'user_name' => $userName ] )
			->caller( __METHOD__ )
			->execute();
		$this->assertTrue( $provider->testUserCanAuthenticate( $userName ) );
	}

	public function testSetPasswordResetFlag() {
		// Set instance vars
		$this->getProvider();

		// @todo: Because we're currently using User, which uses the global config...
		$this->overrideConfigValue( MainConfigNames::PasswordExpireGrace, 100 );

		$this->config->set( MainConfigNames::PasswordExpireGrace, 100 );
		$this->config->set( MainConfigNames::InvalidPasswordReset, true );

		$provider = new LocalPasswordPrimaryAuthenticationProvider(
			$this->getServiceContainer()->getConnectionProvider()
		);
		$this->initProvider( $provider, $this->config, null, $this->manager );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$user = $this->getMutableTestUser()->getUser();
		$userName = $user->getName();
		$row = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'user' )
			->where( [ 'user_name' => $userName ] )
			->caller( __METHOD__ )->fetchRow();

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() + 200 );
		$providerPriv->setPasswordResetFlag( $userName, Status::newGood(), $row );
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 200 );
		$providerPriv->setPasswordResetFlag( $userName, Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = wfTimestamp( TS_MW, time() - 1 );
		$providerPriv->setPasswordResetFlag( $userName, Status::newGood(), $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-expired-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $userName, $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = Status::newGood( [ 'forceChange' => true ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $userName, $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity', $ret->msg->getKey() );
		$this->assertTrue( $ret->hard );

		$this->manager->removeAuthenticationSessionData( null );
		$row->user_password_expires = null;
		$status = Status::newGood( [ 'suggestChangeOnLogin' => false, ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( $userName, $status, $row );
		$ret = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNull( $ret );
	}

	public function testAuthentication() {
		$testUser = $this->getMutableTestUser();
		$userName = $testUser->getUser()->getName();

		$dbw = $this->getDb();
		$id = $testUser->getUser()->getId();

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
		$this->validity = Status::newFatal( 'arbitrary-failure' );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		// AbstractPasswordPrimaryAuthenticationProvider::getFatalPasswordErrorResponse() will
		// wrap the original message in 'fatalpassworderror'
		$this->assertEquals(
			'fatalpassworderror',
			$ret->message->getKey()
		);

		// Successful auth
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = Status::newGood();
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		// Successful auth after normalizing name
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = Status::newGood();
		$req->username = mb_strtolower( $userName[0] ) . substr( $userName, 1 );
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );
		$req->username = $userName;

		// Successful auth with reset
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$this->validity->error( 'arbitrary-warning' );
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

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
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_password' => $password ] )
			->where( [ 'user_name' => $userName ] )
			->caller( __METHOD__ )
			->execute();
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

		$this->config->set( MainConfigNames::LegacyEncoding, true );
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
		$password = md5( "$id-" . md5( 'FooBar' ) );
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_password' => $password ] )
			->where( [ 'user_name' => $userName ] )
			->caller( __METHOD__ )
			->execute();
		$req->password = 'FooBar';
		$this->assertEquals(
			AuthenticationResponse::newPass( $userName ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChange
	 *
	 * @param string $type
	 * @param callable $usernameGetter Function that takes the username of a sysop user and returns the username to
	 * use for testing.
	 * @param Status $validity Result of the password validity check
	 * @param StatusValue $expect1 Expected result with $checkData = false
	 * @param StatusValue $expect2 Expected result with $checkData = true
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, callable $usernameGetter, Status $validity,
		StatusValue $expect1,
		StatusValue $expect2
	) {
		$user = $usernameGetter( $this->getTestSysop()->getUserIdentity()->getName() );
		if ( $type === PasswordAuthenticationRequest::class ) {
			$req = new $type();
			$req->password = 'NewPassword';
			$req->retype = 'NewPassword';
		} elseif ( $type === PasswordDomainAuthenticationRequest::class ) {
			$req = new $type( [] );
		} else {
			$req = $this->createMock( $type );
		}
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = $user;

		$provider = $this->getProvider();
		$this->validity = $validity;
		$this->assertEquals( $expect1, $provider->providerAllowsAuthenticationDataChange( $req, false ) );
		$this->assertEquals( $expect2, $provider->providerAllowsAuthenticationDataChange( $req, true ) );

		if ( $req instanceof PasswordAuthenticationRequest ) {
			$req->retype = 'BadRetype';
		}
		$this->assertEquals(
			$expect1,
			$provider->providerAllowsAuthenticationDataChange( $req, false )
		);
		$this->assertEquals(
			$expect2->getValue() === 'ignored' ? $expect2 : StatusValue::newFatal( 'badretype' ),
			$provider->providerAllowsAuthenticationDataChange( $req, true )
		);

		$provider = $this->getProvider( true );
		$this->assertEquals(
			StatusValue::newGood( 'ignored' ),
			$provider->providerAllowsAuthenticationDataChange( $req, true ),
			'loginOnly mode should claim to ignore all changes'
		);
	}

	public static function provideProviderAllowsAuthenticationDataChange() {
		$err = StatusValue::newGood();
		$err->error( 'arbitrary-warning' );

		return [
			[
				AuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newGood(),
				StatusValue::newGood( 'ignored' ),
				StatusValue::newGood( 'ignored' )
			],
			[
				PasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood()
			],
			[
				PasswordAuthenticationRequest::class,
				'lcfirst',
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood()
			],
			[
				PasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::wrap( $err ),
				StatusValue::newGood(),
				$err
			],
			[
				PasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newFatal( 'arbitrary-error' ),
				StatusValue::newGood(),
				StatusValue::newFatal( 'arbitrary-error' )
			],
			[
				PasswordAuthenticationRequest::class,
				static fn () => 'DoesNotExist',
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood( 'ignored' )
			],
			[
				PasswordDomainAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newGood(),
				StatusValue::newGood( 'ignored' ),
				StatusValue::newGood( 'ignored' )
			],
		];
	}

	/**
	 * @dataProvider provideProviderChangeAuthenticationData
	 * @param callable|false $usernameTransform
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

		$dbw = $this->getDb();
		$oldExpiry = $dbw->newSelectQueryBuilder()
			->select( 'user_password_expires' )
			->from( 'user' )
			->where( [ 'user_name' => $cuser ] )
			->fetchField();

		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'ResetPasswordExpiration' => [ static function ( $user, &$expires ) {
				$expires = '30001231235959';
			} ]
		] );

		$provider = $this->getProvider( $loginOnly );

		$loginReq = new PasswordAuthenticationRequest();
		$loginReq->action = AuthManager::ACTION_LOGIN;
		$loginReq->username = $user;
		$loginReq->password = $oldpass;
		$loginReqs = [ PasswordAuthenticationRequest::class => $loginReq ];
		$this->assertEquals(
			AuthenticationResponse::newPass( $cuser ),
			$provider->beginPrimaryAuthentication( $loginReqs )
		);

		if ( $type === PasswordAuthenticationRequest::class ) {
			$changeReq = new $type();
			$changeReq->password = $newpass;
		} else {
			$changeReq = $this->createMock( $type );
		}
		$changeReq->action = AuthManager::ACTION_CHANGE;
		$changeReq->username = $user;
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
				$dbw->newSelectQueryBuilder()
					->select( 'user_password_expires' )
					->from( 'user' )
					->where( [ 'user_name' => $cuser ] )
					->fetchField()
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
		$user = User::newFromName( 'foo' );
		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_CREATE;
		$req->username = 'Foo';
		$req->password = 'Bar';
		$req->retype = 'Bar';
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider();
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] ),
			'No password request'
		);

		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, validated'
		);

		$req->retype = 'Baz';
		$this->assertEquals(
			StatusValue::newFatal( 'badretype' ),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, bad retype'
		);
		$req->retype = 'Bar';

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

	public function testAccountCreation() {
		$user = User::newFromName( 'Foo' );

		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_CREATE;
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider( true );
		try {
			$provider->beginPrimaryAccountCreation( $user, $user, [] );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		try {
			$provider->finishAccountCreation( $user, $user, AuthenticationResponse::newPass() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( BadMethodCallException $ex ) {
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

		$user = $this->getTestSysop()->getUser();
		$req->username = $user->getName();
		$req->password = 'NewPassword';
		$expect = AuthenticationResponse::newPass( $user->getName() );
		$expect->createRequest = $req;

		$res2 = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );
		$this->assertEquals( $expect, $res2 );

		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );

		$this->assertNull( $provider->finishAccountCreation( $user, $user, $res2 ) );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals( AuthenticationResponse::PASS, $ret->status, 'new password is set' );
	}
}
