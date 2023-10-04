<?php

namespace MediaWiki\Auth;

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use stdClass;
use TestLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\ThrottlePreAuthenticationProvider
 */
class ThrottlePreAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;

	public function testConstructor() {
		$provider = new ThrottlePreAuthenticationProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			MainConfigNames::AccountCreationThrottle => [ [
				'count' => 123,
				'seconds' => 86400,
			] ],
			MainConfigNames::PasswordAttemptThrottle => [ [
				'count' => 5,
				'seconds' => 300,
			] ],
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( [
			'accountCreationThrottle' => [ [ 'count' => 123, 'seconds' => 86400 ] ],
			'passwordAttemptThrottle' => [ [ 'count' => 5, 'seconds' => 300 ] ]
		], $providerPriv->throttleSettings );
		$accountCreationThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->accountCreationThrottle );
		$this->assertSame( [ [ 'count' => 123, 'seconds' => 86400 ] ],
			$accountCreationThrottle->conditions );
		$passwordAttemptThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->passwordAttemptThrottle );
		$this->assertSame( [ [ 'count' => 5, 'seconds' => 300 ] ],
			$passwordAttemptThrottle->conditions );

		$provider = new ThrottlePreAuthenticationProvider( [
			'accountCreationThrottle' => [ [ 'count' => 43, 'seconds' => 10000 ] ],
			'passwordAttemptThrottle' => [ [ 'count' => 11, 'seconds' => 100 ] ],
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			MainConfigNames::AccountCreationThrottle => [ [
				'count' => 123,
				'seconds' => 86400,
			] ],
			MainConfigNames::PasswordAttemptThrottle => [ [
				'count' => 5,
				'seconds' => 300,
			] ],
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( [
			'accountCreationThrottle' => [ [ 'count' => 43, 'seconds' => 10000 ] ],
			'passwordAttemptThrottle' => [ [ 'count' => 11, 'seconds' => 100 ] ],
		], $providerPriv->throttleSettings );

		$cache = new \HashBagOStuff();
		$provider = new ThrottlePreAuthenticationProvider( [ 'cache' => $cache ] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			MainConfigNames::AccountCreationThrottle => [ [ 'count' => 1, 'seconds' => 1 ] ],
			MainConfigNames::PasswordAttemptThrottle => [ [ 'count' => 1, 'seconds' => 1 ] ],
		] );
		$this->initProvider( $provider, $config );
		$accountCreationThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->accountCreationThrottle );
		$this->assertSame( $cache, $accountCreationThrottle->cache );
		$passwordAttemptThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->passwordAttemptThrottle );
		$this->assertSame( $cache, $passwordAttemptThrottle->cache );
	}

	public function testDisabled() {
		$provider = new ThrottlePreAuthenticationProvider( [
			'accountCreationThrottle' => [],
			'passwordAttemptThrottle' => [],
			'cache' => new \HashBagOStuff(),
		] );
		$this->initProvider(
			$provider,
			new HashConfig( [
				MainConfigNames::AccountCreationThrottle => null,
				MainConfigNames::PasswordAttemptThrottle => null,
			] ),
			null,
			$this->getServiceContainer()->getAuthManager()
		);

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation(
				User::newFromName( 'Created' ),
				User::newFromName( 'Creator' ),
				[]
			)
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAuthentication( [] )
		);
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 * @param bool $creatorIsSysop
	 * @param bool $succeed
	 * @param bool $hook
	 */
	public function testTestForAccountCreation( bool $creatorIsSysop, $succeed, $hook ) {
		if ( $hook ) {
			$mock = $this->getMockBuilder( stdClass::class )
				->addMethods( [ 'onExemptFromAccountCreationThrottle' ] )
				->getMock();
			$mock->method( 'onExemptFromAccountCreationThrottle' )
				->willReturn( false );
			$this->setTemporaryHook( 'ExemptFromAccountCreationThrottle', $mock );
		}

		$provider = new ThrottlePreAuthenticationProvider( [
			'accountCreationThrottle' => [ [ 'count' => 2, 'seconds' => 86400 ] ],
			'cache' => new \HashBagOStuff(),
		] );
		$this->initProvider(
			$provider,
			new HashConfig( [
				MainConfigNames::AccountCreationThrottle => null,
				MainConfigNames::PasswordAttemptThrottle => null,
			] ),
			null,
			$this->getServiceContainer()->getAuthManager(),
			$this->getServiceContainer()->getHookContainer()
		);

		$user = User::newFromName( 'RandomUser' );
		$creator = $creatorIsSysop ? $this->getTestSysop()->getUser() : $this->getTestUser()->getUser();

		$this->assertTrue(

			$provider->testForAccountCreation( $user, $creator, [] )->isOK(),
			'attempt #1'
		);
		$this->assertTrue(

			$provider->testForAccountCreation( $user, $creator, [] )->isOK(),
			'attempt #2'
		);
		$this->assertEquals(
			(bool)$succeed,
			$provider->testForAccountCreation( $user, $creator, [] )->isOK(),
			'attempt #3'
		);
	}

	public static function provideTestForAccountCreation() {
		return [
			'Normal user' => [ false, false, false ],
			'Sysop' => [ true, true, false ],
			'Normal user with hook' => [ false, true, true ],
		];
	}

	public function testTestForAuthentication() {
		$provider = new ThrottlePreAuthenticationProvider( [
			'passwordAttemptThrottle' => [ [ 'count' => 2, 'seconds' => 86400 ] ],
			'cache' => new \HashBagOStuff(),
		] );
		$this->initProvider(
			$provider,
			new HashConfig( [
				MainConfigNames::AccountCreationThrottle => null,
				MainConfigNames::PasswordAttemptThrottle => null,
			] ),
			null,
			$this->getServiceContainer()->getAuthManager()
		);

		$req = new UsernameAuthenticationRequest;
		$req->username = 'SomeUser';
		for ( $i = 1; $i <= 3; $i++ ) {
			$status = $provider->testForAuthentication( [ $req ] );
			$this->assertEquals( $i < 3, $status->isGood(), "attempt #$i" );
		}
		$this->assertStatusError( 'login-throttled', $status );

		$provider->postAuthentication( User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newFail( wfMessage( 'foo' ) ) );
		$this->assertStatusNotOk( $provider->testForAuthentication( [ $req ] ), 'after FAIL' );

		$provider->postAuthentication( User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newPass() );
		$this->assertStatusGood( $provider->testForAuthentication( [ $req ] ), 'after PASS' );

		$req1 = new UsernameAuthenticationRequest;
		$req1->username = 'foo';
		$req2 = new UsernameAuthenticationRequest;
		$req2->username = 'bar';
		$this->assertStatusGood( $provider->testForAuthentication( [ $req1, $req2 ] ) );

		$req = new UsernameAuthenticationRequest;
		$req->username = 'Some user';
		$provider->testForAuthentication( [ $req ] );
		$req->username = 'Some_user';
		$provider->testForAuthentication( [ $req ] );
		$req->username = 'some user';
		$status = $provider->testForAuthentication( [ $req ] );
		$this->assertStatusNotOk( $status, 'denormalized usernames are normalized' );
	}

	public function testPostAuthentication() {
		$provider = new ThrottlePreAuthenticationProvider( [
			'passwordAttemptThrottle' => [],
			'cache' => new \HashBagOStuff(),
		] );
		$this->initProvider(
			$provider,
			new HashConfig( [
				MainConfigNames::AccountCreationThrottle => null,
				MainConfigNames::PasswordAttemptThrottle => null,
			] ),
			null,
			$this->getServiceContainer()->getAuthManager()
		);
		$provider->postAuthentication( User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newPass() );

		$provider = new ThrottlePreAuthenticationProvider( [
			'passwordAttemptThrottle' => [ [ 'count' => 2, 'seconds' => 86400 ] ],
			'cache' => new \HashBagOStuff(),
		] );
		$logger = new TestLogger( true );
		$this->initProvider(
			$provider,
			new HashConfig( [
				MainConfigNames::AccountCreationThrottle => null,
				MainConfigNames::PasswordAttemptThrottle => null,
			] ),
			$logger,
			$this->getServiceContainer()->getAuthManager()
		);
		$provider->postAuthentication( User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newPass() );
		$this->assertSame( [
			[ \Psr\Log\LogLevel::INFO, 'throttler data not found for {user}' ],
		], $logger->getBuffer() );
	}
}
