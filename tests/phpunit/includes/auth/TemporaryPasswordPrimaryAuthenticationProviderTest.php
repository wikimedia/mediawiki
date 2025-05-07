<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\PrimaryAuthenticationProvider;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;
use MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWikiIntegrationTestCase;
use StatusValue;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * TODO clean up and reduce duplication
 *
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\AbstractTemporaryPasswordPrimaryAuthenticationProvider
 * @covers \MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider
 */
class TemporaryPasswordPrimaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;
	use DummyServicesTrait;

	private AuthManager $manager;
	private Status $validity;

	private PasswordFactory $testPasswordFactory;

	protected function setUp(): void {
		parent::setUp();

		$mwServices = $this->getServiceContainer();

		$hookContainer = $this->createHookContainer();

		$this->manager = new AuthManager(
			new FauxRequest(),
			$mwServices->getMainConfig(),
			$this->getDummyObjectFactory(),
			$hookContainer,
			$mwServices->getReadOnlyMode(),
			$this->createNoOpMock( UserNameUtils::class ),
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

		$this->validity = Status::newGood();

		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$this->testPasswordFactory = new PasswordFactory(
			$this->getConfVar( MainConfigNames::PasswordConfig ),
			'A'
		);
	}

	/**
	 * Get an instance of the provider
	 *
	 * $provider->checkPasswordValidity is mocked to return $this->validity,
	 * because we don't need to test that here.
	 *
	 * @param array $params
	 * @param UserNameUtils|null $userNameUtils
	 * @return TemporaryPasswordPrimaryAuthenticationProvider
	 */
	protected function getProvider( array $params = [], ?UserNameUtils $userNameUtils = null ) {
		$userNameUtils ??= $this->getServiceContainer()->getUserNameUtils();
		$mwServices = $this->getServiceContainer();

		$mockedMethods[] = 'checkPasswordValidity';
		$provider = $this->getMockBuilder( TemporaryPasswordPrimaryAuthenticationProvider::class )
			->onlyMethods( $mockedMethods )
			->setConstructorArgs( [
				$mwServices->getConnectionProvider(),
				$mwServices->getUserOptionsLookup(),
				$params,
			] )
			->getMock();
		$provider->method( 'checkPasswordValidity' )
			->willReturnCallback( function () {
				return $this->validity;
			} );
		$this->initProvider(
			$provider, $mwServices->getMainConfig(), null, $this->manager, null, $userNameUtils
		);

		return $provider;
	}

	protected function hookMailer( $func = null ) {
		$hookContainer = $this->getServiceContainer()->getHookContainer();

		$this->clearHook( 'AlternateUserMailer' );

		if ( $func ) {
			$reset = $hookContainer->scopedRegister( 'AlternateUserMailer', $func );
		} else {
			$reset = $hookContainer->scopedRegister( 'AlternateUserMailer', function () {
				$this->fail( 'AlternateUserMailer hook called unexpectedly' );
				return false;
			} );
		}
		return $reset;
	}

	/**
	 * Set the new password (i.e. single use temporary password)
	 * hash for the given user, with an optional expiry time.
	 *
	 * @param UserIdentity $user The user to update the new password for.
	 * @param string $hash Password hash to store.
	 * @param int|null $expiry UNIX timestamp at which the new password expires, or `null` for no expiry.
	 */
	private function setNewPassword(
		UserIdentity $user,
		string $hash,
		?int $expiry = null
	): void {
		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [
				'user_newpassword' => $hash,
				'user_newpass_time' => $expiry ? $dbw->timestamp( $expiry ) : null
			] )
			->where( [ 'user_id' => $user->getId() ] )
			->execute();
	}

	public function testBasics() {
		$provider = $this->getProvider();

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_CREATE,
			$provider->accountCreationType()
		);

		$existingUserName = $this->getTestUser()->getUserIdentity()->getName();
		$this->assertTrue( $provider->testUserExists( $existingUserName ) );
		$this->assertTrue( $provider->testUserExists( lcfirst( $existingUserName ) ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
		$this->assertFalse( $provider->testUserExists( '<invalid>' ) );

		$req = new PasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = '<invalid>';
		$provider->providerChangeAuthenticationData( $req );
	}

	public function testConfig() {
		$config = new HashConfig( [
			MainConfigNames::EnableEmail => false,
			MainConfigNames::NewPasswordExpiry => 100,
			MainConfigNames::PasswordReminderResendTime => 101,
		] );

		$provider = new TemporaryPasswordPrimaryAuthenticationProvider(
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getUserOptionsLookup()
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider( $provider, $config );
		$this->assertSame( false, $providerPriv->emailEnabled );
		$this->assertSame( 100, $providerPriv->newPasswordExpiry );
		$this->assertSame( 101, $providerPriv->passwordReminderResendTime );

		$provider = new TemporaryPasswordPrimaryAuthenticationProvider(
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getUserOptionsLookup(),
			[
				'emailEnabled' => true,
				'newPasswordExpiry' => 42,
				'passwordReminderResendTime' => 43,
			]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider( $provider, $config );
		$this->assertSame( true, $providerPriv->emailEnabled );
		$this->assertSame( 42, $providerPriv->newPasswordExpiry );
		$this->assertSame( 43, $providerPriv->passwordReminderResendTime );
	}

	/**
	 * @dataProvider provideTestUserCanAuthenticateErrorCases
	 *
	 * @param string|null $userName The user name to check, or `null` to use the user name of the test user
	 * @param callable|null $passwordProvider Optional callable that takes a `PasswordFactory` and produces
	 * a password hash override to set for the test user
	 * @param int|null $passwordExpiry Expiry to set for the password returned by `$passwordProvider`, or
	 * `null` to set no expiry.
	 * @return void
	 */
	public function testTestUserCanAuthenticateErrorCases(
		?string $userName = null,
		?callable $passwordProvider = null,
		?int $passwordExpiry = null
	): void {
		$user = self::getMutableTestUser()->getUser();

		if ( $passwordProvider !== null ) {
			$this->setNewPassword(
				$user,
				$passwordProvider( $this->testPasswordFactory ),
				$passwordExpiry
			);
		}

		$userName ??= $user->getName();

		$result = $this->getProvider( [ 'newPasswordExpiry' => 100 ] )->testUserCanAuthenticate( $userName );

		$this->assertFalse( $result );
	}

	public static function provideTestUserCanAuthenticateErrorCases(): iterable {
		yield 'invalid user name' => [ '<invalid>' ];
		yield 'nonexistent user' => [ 'DoesNotExist' ];
		yield 'user with invalid password' => [
			null,
			static fn () => PasswordFactory::newInvalidPassword()->toString()
		];
		yield 'user with expired password' => [
			null,
			static fn ( PasswordFactory $passwordFactory ) => $passwordFactory->newFromPlaintext( 'password' )->toString(),
			time() - 3_600
		];
	}

	public function testTestUserCanAuthenticateSimple(): void {
		$user = self::getMutableTestUser()->getUser();

		$this->setNewPassword(
			$user,
			$this->testPasswordFactory->newFromPlaintext( 'password' )->toString()
		);

		$result = $this->getProvider()->testUserCanAuthenticate( $user->getName() );

		$this->assertTrue( $result );
	}

	public function testTestUserCanAuthenticateCaseInsensitive(): void {
		$user = self::getMutableTestUser()->getUser();

		$this->setNewPassword(
			$user,
			$this->testPasswordFactory->newFromPlaintext( 'password' )->toString()
		);

		$result = $this->getProvider()->testUserCanAuthenticate( lcfirst( $user->getName() ) );

		$this->assertTrue( $result );
	}

	public function testTestUserCanAuthenticateWithNonExpiredTemporaryPassword(): void {
		$user = self::getMutableTestUser()->getUser();

		$this->setNewPassword(
			$user,
			$this->testPasswordFactory->newFromPlaintext( 'password' )->toString(),
			time() - 100
		);

		$result = $this->getProvider( [ 'newPasswordExpiry' => 3600 ] )->testUserCanAuthenticate( $user->getName() );

		$this->assertTrue( $result );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param bool $registered
	 * @param bool $temporary
	 * @param AuthenticationRequest[] $expected
	 */
	public function testGetAuthenticationRequests(
		string $action,
		bool $registered,
		bool $temporary,
		array $expected
	) {
		$username = $registered ? 'TestGetAuthenticationRequests' : null;
		$options = [ 'username' => $username ];

		$userNameUtils = $this->createMock( UserNameUtils::class );
		$userNameUtils->method( 'isTemp' )
			->with( $username )
			->willReturn( $temporary );

		$actual = $this->getProvider( [ 'emailEnabled' => true ], $userNameUtils )
			->getAuthenticationRequests( $action, $options );
		foreach ( $actual as $req ) {
			if ( $req instanceof TemporaryPasswordAuthenticationRequest && $req->password !== null ) {
				$req->password = 'random';
			}
		}
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetAuthenticationRequests(): iterable {
		yield 'login attempt as anonymous user' => [
			AuthManager::ACTION_LOGIN, false, false, [ new PasswordAuthenticationRequest ]
		];

		yield 'login attempt as named user' => [
			AuthManager::ACTION_LOGIN, true, false, [ new PasswordAuthenticationRequest ]
		];

		yield 'login attempt as temporary user' => [
			AuthManager::ACTION_LOGIN, true, true, [ new PasswordAuthenticationRequest ]
		];

		yield 'signup attempt as anonymous user' => [
			AuthManager::ACTION_CREATE, false, false, []
		];

		yield 'signup attempt as named user' => [
			AuthManager::ACTION_CREATE, true, false, [ new TemporaryPasswordAuthenticationRequest( 'random' ) ]
		];

		yield 'signup attempt as temporary user' => [
			AuthManager::ACTION_CREATE, true, true, []
		];

		yield 'account linking attempt as anonymous user' => [
			AuthManager::ACTION_LINK, false, false, []
		];

		yield 'account linking attempt as named user' => [
			AuthManager::ACTION_LINK, true, false, []
		];

		yield 'account linking attempt as temporary user' => [
			AuthManager::ACTION_LINK, true, true, []
		];

		yield 'credential change attempt as anonymous user' => [
			AuthManager::ACTION_CHANGE, false, false, [ new TemporaryPasswordAuthenticationRequest( 'random' ) ]
		];

		yield 'credential change attempt as named user' => [
			AuthManager::ACTION_CHANGE, true, false, [ new TemporaryPasswordAuthenticationRequest( 'random' ) ]
		];

		yield 'credential change attempt as temporary user' => [
			AuthManager::ACTION_CHANGE, true, true, [ new TemporaryPasswordAuthenticationRequest( 'random' ) ]
		];

		yield 'credential remove attempt as anonymous user' => [
			AuthManager::ACTION_REMOVE, false, false, [ new TemporaryPasswordAuthenticationRequest() ]
		];

		yield 'credential remove attempt as named user' => [
			AuthManager::ACTION_REMOVE, true, false, [ new TemporaryPasswordAuthenticationRequest() ]
		];

		yield 'credential remove attempt as temporary user' => [
			AuthManager::ACTION_REMOVE, true, true, [ new TemporaryPasswordAuthenticationRequest() ]
		];
	}

	/**
	 * @dataProvider provideAuthenticationErrorCases
	 * @param string $password
	 * @param string $expectedErrorMessage
	 * @param int $newPasswordExpiry
	 * @param StatusValue|null $validationError
	 * @return void
	 */
	public function testAuthenticationErrorCases(
		string $password,
		string $expectedErrorMessage,
		int $newPasswordExpiry = 100,
		?StatusValue $validationError = null
	) {
		$user = self::getMutableTestUser()->getUser();

		$validPassword = 'TemporaryPassword';
		$hash = ':A:' . md5( $validPassword );

		$this->setNewPassword( $user, $hash, time() - 10 );

		$req = self::makePasswordAuthenticationRequest( $user->getName(), $password );

		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider( [ 'newPasswordExpiry' => $newPasswordExpiry ] );

		$this->validity = $validationError ?? Status::newGood();

		$response = $provider->beginPrimaryAuthentication( $reqs );

		$this->assertSame( AuthenticationResponse::FAIL, $response->status );
		if ( $validationError !== null ) {
			$this->assertSame(
				$validationError->getMessages()[0]->getKey(),
				$response->message->getParams()[0]->getKey()
			);
		}
	}

	public static function provideAuthenticationErrorCases(): iterable {
		yield 'validation failure' => [
			'TemporaryPassword',
			'fatalpassworderror',
			100,
			Status::newFatal( 'arbitrary-failure' )
		];

		yield 'expired password' => [
			'TemporaryPassword',
			'wrongpassword',
			1
		];

		yield 'wrong password' => [
			'Wrong',
			'wrongpassword'
		];
	}

	/**
	 * @dataProvider provideAuthenticationAbstainCases
	 * @param PasswordAuthenticationRequest|null $req The authentication request to send,
	 * or `null` to send no requests
	 * @return void
	 */
	public function testAuthenticationAbstainCases( ?PasswordAuthenticationRequest $req ): void {
		$reqs = $req ? [ PasswordAuthenticationRequest::class => $req ] : [];

		$response = $this->getProvider()->beginPrimaryAuthentication( $reqs );

		$this->assertEquals( AuthenticationResponse::newAbstain(), $response );
	}

	public static function provideAuthenticationAbstainCases(): iterable {
		yield 'no requests' => [ null ];
		yield 'no user name' => [ self::makePasswordAuthenticationRequest( null, 'bar' ) ];
		yield 'no password' => [ self::makePasswordAuthenticationRequest( 'foo' ) ];
		yield 'invalid user name' => [ self::makePasswordAuthenticationRequest( '<invalid>', 'bar' ) ];
		yield 'nonexistent user' => [ self::makePasswordAuthenticationRequest( 'DoesNotExist', 'bar' ) ];
	}

	private static function makePasswordAuthenticationRequest(
		?string $userName = null,
		?string $password = null
	): PasswordAuthenticationRequest {
		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_LOGIN;
		$req->username = $userName;
		$req->password = $password;
		return $req;
	}

	public function testAuthenticationSuccess(): void {
		$user = self::getMutableTestUser()->getUser();

		$password = 'TemporaryPassword';
		$hash = ':A:' . md5( $password );

		$this->setNewPassword( $user, $hash, time() - 10 );

		$req = self::makePasswordAuthenticationRequest( $user->getName(), $password );
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider();

		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = Status::newGood();

		$this->assertEquals(
			AuthenticationResponse::newPass( $user->getName() ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );
	}

	public function testAuthenticationSuccessCaseInsensitive(): void {
		$user = self::getMutableTestUser()->getUser();

		$password = 'TemporaryPassword';
		$hash = ':A:' . md5( $password );

		$this->setNewPassword( $user, $hash, time() - 10 );

		$req = self::makePasswordAuthenticationRequest( lcfirst( $user->getName() ), $password );
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider();

		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = Status::newGood();

		$this->assertEquals(
			AuthenticationResponse::newPass( $user->getName() ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );
	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChange
	 *
	 * @param string $type
	 * @param callable $usernameGetter Function that takes the username of a sysop user and returns the username to
	 *  use for testing.
	 * @param Status $validity Result of the password validity check
	 * @param StatusValue $expect1 Expected result with $checkData = false
	 * @param StatusValue $expect2 Expected result with $checkData = true
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, callable $usernameGetter,
		Status $validity,
		StatusValue $expect1, StatusValue $expect2
	) {
		$user = $usernameGetter( $this->getTestSysop()->getUserIdentity()->getName() );
		if ( $type === PasswordAuthenticationRequest::class ||
			$type === TemporaryPasswordAuthenticationRequest::class
		) {
			$req = new $type();
			$req->password = 'NewPassword';
		} else {
			$req = $this->createMock( $type );
		}
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = $user;

		$provider = $this->getProvider();
		$this->validity = $validity;
		$this->assertEquals( $expect1, $provider->providerAllowsAuthenticationDataChange( $req, false ) );
		$this->assertEquals( $expect2, $provider->providerAllowsAuthenticationDataChange( $req, true ) );
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
				StatusValue::newGood( 'ignored' ),
			],
			[
				PasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newGood(),
				StatusValue::newGood( 'ignored' ),
				StatusValue::newGood( 'ignored' ),
			],
			[
				TemporaryPasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood(),
			],
			[
				TemporaryPasswordAuthenticationRequest::class,
				'lcfirst',
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood(),
			],
			[
				TemporaryPasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::wrap( $err ),
				StatusValue::newGood(),
				$err,
			],
			[
				TemporaryPasswordAuthenticationRequest::class,
				static fn ( $sysopUsername ) => $sysopUsername,
				Status::newFatal( 'arbitrary-error' ),
				StatusValue::newGood(),
				StatusValue::newFatal( 'arbitrary-error' ),
			],
			[
				TemporaryPasswordAuthenticationRequest::class,
				static fn () => 'DoesNotExist',
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood( 'ignored' ),
			],
			[
				TemporaryPasswordAuthenticationRequest::class,
				static fn () => '<invalid>',
				Status::newGood(),
				StatusValue::newGood(),
				StatusValue::newGood( 'ignored' ),
			],
		];
	}

	/**
	 * @dataProvider provideProviderChangeAuthenticationData
	 * @param string $type
	 * @param bool $changed
	 */
	public function testProviderChangeAuthenticationData( $type, $changed ) {
		$user = $this->getTestSysop()->getUserIdentity()->getName();
		$oldpass = 'OldTempPassword';
		$newpass = 'NewTempPassword';

		$dbw = $this->getDb();
		$oldHash = $dbw->newSelectQueryBuilder()
			->select( 'user_newpassword' )
			->from( 'user' )
			->where( [ 'user_name' => $user ] )
			->fetchField();
		$cb = new ScopedCallback( static function () use ( $dbw, $user, $oldHash ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [ 'user_newpassword' => $oldHash ] )
				->where( [ 'user_name' => $user ] )
				->execute();
		} );

		$hash = ':A:' . md5( $oldpass );
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_newpassword' => $hash, 'user_newpass_time' => $dbw->timestamp( time() + 1000 ) ] )
			->where( [ 'user_name' => $user ] )
			->execute();

		$provider = $this->getProvider();

		$loginReq = new PasswordAuthenticationRequest();
		$loginReq->action = AuthManager::ACTION_CHANGE;
		$loginReq->username = $user;
		$loginReq->password = $oldpass;
		$loginReqs = [ PasswordAuthenticationRequest::class => $loginReq ];
		$this->assertEquals(
			AuthenticationResponse::newPass( $user ),
			$provider->beginPrimaryAuthentication( $loginReqs )
		);

		if ( $type === PasswordAuthenticationRequest::class ||
			$type === TemporaryPasswordAuthenticationRequest::class
		) {
			$changeReq = new $type();
			$changeReq->password = $newpass;
		} else {
			$changeReq = $this->createMock( $type );
		}
		$changeReq->action = AuthManager::ACTION_CHANGE;
		$changeReq->username = $user;
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
				AuthenticationResponse::newPass( $user ),
				$ret,
				'new password should pass'
			);
			$this->assertNotNull(
				$dbw->newSelectQueryBuilder()
					->select( 'user_newpass_time' )
					->from( 'user' )
					->where( [ 'user_name' => $user ] )
					->fetchField()
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
				$dbw->newSelectQueryBuilder()
					->select( 'user_newpass_time' )
					->from( 'user' )
					->where( [ 'user_name' => $user ] )
					->fetchField()
			);
		}
	}

	public static function provideProviderChangeAuthenticationData() {
		return [
			[ AuthenticationRequest::class, false ],
			[ PasswordAuthenticationRequest::class, false ],
			[ TemporaryPasswordAuthenticationRequest::class, true ],
		];
	}

	/**
	 * @dataProvider provideChangeAuthenticationDataEmailErrorCases
	 *
	 * @param array $providerConfig Configuration to pass on to the auth provider
	 * @param string|null $caller Caller on behalf of which the request is sent
	 * @param string $expectedError Expected error message key
	 */
	public function testProviderChangeAuthenticationDataEmailError(
		array $providerConfig,
		?string $caller,
		string $expectedError
	): void {
		$user = self::getMutableTestUser()->getUser();

		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_newpass_time' => $dbw->timestamp( time() - 5 * 3600 ) ] )
			->where( [ 'user_id' => $user->getId() ] )
			->execute();

		$req = TemporaryPasswordAuthenticationRequest::newRandom();
		$req->username = $user->getName();
		$req->mailpassword = true;
		$req->caller = $caller;

		$provider = $this->getProvider( $providerConfig );
		$status = $provider->providerAllowsAuthenticationDataChange( $req );

		$this->assertFalse( $status->isGood() );
		$this->assertSame(
			[ $expectedError ],
			array_map( static fn ( MessageSpecifier $spec ) => $spec->getKey(), $status->getMessages() )
		);
	}

	public static function provideChangeAuthenticationDataEmailErrorCases(): iterable {
		yield 'email disabled' => [
			[ 'emailEnabled' => false ],
			'127.0.0.1',
			'passwordreset-emaildisabled'
		];

		yield 'password reset rate limited' => [
			[ 'emailEnabled' => true, 'passwordReminderResendTime' => 10 ],
			'127.0.0.1',
			'throttled-mailpassword'
		];

		yield 'missing caller' => [
			[ 'emailEnabled' => true, 'passwordReminderResendTime' => 0 ],
			null,
			'passwordreset-nocaller'
		];

		yield 'invalid IP caller' => [
			[ 'emailEnabled' => true, 'passwordReminderResendTime' => 0 ],
			'127.0.0.256',
			'passwordreset-nosuchcaller'
		];

		yield 'invalid registered caller' => [
			[ 'emailEnabled' => true, 'passwordReminderResendTime' => 0 ],
			'<Invalid>',
			'passwordreset-nosuchcaller'
		];
	}

	/**
	 * @dataProvider provideChangeAuthenticationDataEmailSuccessCases
	 * @param string $caller Caller on behalf of which the request is sent
	 */
	public function testProviderChangeAuthenticationDataEmailSuccess( string $caller ) {
		$user = self::getMutableTestUser()->getUser();

		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_newpass_time' => $dbw->timestamp( time() + 5 * 3600 ) ] )
			->where( [ 'user_id' => $user->getId() ] )
			->execute();

		$req = TemporaryPasswordAuthenticationRequest::newRandom();
		$req->username = $user->getName();
		$req->mailpassword = true;
		$req->caller = $caller;

		$provider = $this->getProvider( [ 'emailEnabled' => true, 'passwordReminderResendTime' => 0 ] );

		$status = $provider->providerAllowsAuthenticationDataChange( $req, true );
		$this->assertEquals( StatusValue::newGood(), $status );

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
	}

	public static function provideChangeAuthenticationDataEmailSuccessCases(): iterable {
		yield 'anonymous caller' => [ '127.0.0.1' ];
		yield 'registered caller' => [ 'TestUser' ];
	}

	/**
	 * @dataProvider provideAccountCreationSuccessCases
	 * @param AuthenticationRequest[] $reqs
	 */
	public function testTestForAccountCreationSuccess( array $reqs ) {
		$user = $this->getServiceContainer()->getUserFactory()->newFromName( 'foo' );

		$status = $this->getProvider()->testForAccountCreation( $user, $user, $reqs );

		$this->assertTrue( $status->isGood() );
	}

	public static function provideAccountCreationSuccessCases(): iterable {
		$req = new TemporaryPasswordAuthenticationRequest();
		$req->username = 'Foo';
		$req->password = 'Bar';

		yield 'no password request' => [
			[],
		];

		yield 'validated password request' => [
			[ TemporaryPasswordAuthenticationRequest::class => $req ],
		];
	}

	public function testTestForAccountCreationError(): void {
		$req = new TemporaryPasswordAuthenticationRequest();
		$req->username = 'Foo';
		$req->password = 'Bar';

		$user = $this->getServiceContainer()->getUserFactory()->newFromName( 'foo' );
		$provider = $this->getProvider();
		$this->validity->error( 'arbitrary warning' );

		$status = $provider->testForAccountCreation(
			$user, $user, [ TemporaryPasswordAuthenticationRequest::class => $req ]
		);

		$this->assertFalse( $status->isGood() );
		$this->assertTrue( $status->hasMessage( 'arbitrary warning' ) );
	}

	/**
	 * @dataProvider provideAccountCreationAbstainCases
	 * @param TemporaryPasswordAuthenticationRequest|null $req
	 * @return void
	 */
	public function testAccountCreationAbstain( ?TemporaryPasswordAuthenticationRequest $req ) {
		$resetMailer = $this->hookMailer();

		$user = $this->getServiceContainer()->getUserFactory()->newFromName( 'Foo' );

		$reqs = $req ? [ TemporaryPasswordAuthenticationRequest::class => $req ] : [];

		$provider = $this->getProvider();
		$response = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );

		$this->assertSame( AuthenticationResponse::ABSTAIN, $response->status );
	}

	public static function provideAccountCreationAbstainCases(): iterable {
		yield 'no authentication requests' => [
			null,
		];

		yield 'request without password' => [
			self::makeTemporaryPasswordAuthenticationRequest( 'foo' ),
		];

		yield 'request without username' => [
			self::makeTemporaryPasswordAuthenticationRequest( null, 'bar' ),
		];
	}

	public function testAccountCreationPassForUserNameWithDifferentCase(): void {
		$user = $this->getServiceContainer()->getUserFactory()->newFromName( 'Foo' );
		$pass = 'NewPassword';

		$req = self::makeTemporaryPasswordAuthenticationRequest( 'foo', $pass );
		$reqs = [ TemporaryPasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider();
		$response = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );

		$this->assertSame( AuthenticationResponse::PASS, $response->status );
		$this->assertSame( $response->username, $user->getName() );
		$this->assertSame(
			$response->createRequest->username,
			$user->getName()
		);
	}

	public function testAccountCreationPass(): void {
		$resetMailer = $this->hookMailer();

		$user = self::getMutableTestUser()->getUser();
		$pass = 'NewPassword';

		$req = self::makeTemporaryPasswordAuthenticationRequest( $user->getName(), $pass );
		$reqs = [ TemporaryPasswordAuthenticationRequest::class => $req ];

		$provider = $this->getProvider();
		$response = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );

		$this->assertSame( AuthenticationResponse::PASS, $response->status );
		$this->assertSame( $response->username, $user->getName() );
		$this->assertSame(
			$response->createRequest->username,
			$user->getName()
		);
		$this->assertNull( $this->manager->getAuthenticationSessionData( 'no-email' ) );

		$authreq = new PasswordAuthenticationRequest();
		$authreq->action = AuthManager::ACTION_CREATE;
		$authreq->username = $user->getName();
		$authreq->password = $pass;

		$authreqs = [ PasswordAuthenticationRequest::class => $authreq ];

		$failedAttemptResponse = $provider->beginPrimaryAuthentication( $authreqs );
		$this->assertSame( AuthenticationResponse::FAIL, $failedAttemptResponse->status, 'account creation not finished yet' );

		$this->assertSame( null, $provider->finishAccountCreation( $user, $user, $response ) );

		$response = $provider->beginPrimaryAuthentication( $authreqs );
		$this->assertSame( AuthenticationResponse::PASS, $response->status, 'new password is set' );
	}

	private static function makeTemporaryPasswordAuthenticationRequest(
		?string $userName = null,
		?string $password = null
	): TemporaryPasswordAuthenticationRequest {
		$req = new TemporaryPasswordAuthenticationRequest();
		$req->username = $userName;
		$req->password = $password;
		return $req;
	}

	/**
	 * @dataProvider provideAccountCreationEmailErrorCases
	 *
	 * @param array $providerConfig Configuration to pass on to the auth provider
	 * @param string $userEmail Email to set for the user being tested
	 * @param string $expectedError Expected error message key
	 */
	public function testAccountCreationEmailErrorCases(
		array $providerConfig,
		string $userEmail,
		string $expectedError
	): void {
		$creator = $this->getServiceContainer()->getUserFactory()->newFromName( 'Foo' );

		$user = self::getMutableTestUser()->getUser();
		$user->setEmail( $userEmail );

		$req = TemporaryPasswordAuthenticationRequest::newRandom();
		$req->username = $user->getName();
		$req->mailpassword = true;

		$provider = $this->getProvider( $providerConfig );
		$status = $provider->testForAccountCreation( $user, $creator, [ $req ] );
		$this->assertEquals( StatusValue::newFatal( $expectedError ), $status );
	}

	public static function provideAccountCreationEmailErrorCases(): iterable {
		yield 'email disabled' => [
			[ 'emailEnabled' => false ],
			'test@localhost.localdomain',
			'emaildisabled'
		];

		yield 'missing user email' => [
			[ 'emailEnabled' => true ],
			'',
			'noemailcreate'
		];
	}

	public function testAccountCreationEmailSuccess(): void {
		$creator = $this->getServiceContainer()->getUserFactory()->newFromName( 'Foo' );

		$user = self::getMutableTestUser()->getUser();
		$user->setEmail( 'test@localhost.localdomain' );

		$req = TemporaryPasswordAuthenticationRequest::newRandom();
		$req->username = $user->getName();
		$req->mailpassword = true;

		$provider = $this->getProvider( [ 'emailEnabled' => true ] );
		$status = $provider->testForAccountCreation( $user, $creator, [ $req ] );
		$this->assertEquals( StatusValue::newGood(), $status );

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
