<?php

namespace MediaWiki\Tests\Session;

use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Session\CookieSessionProvider;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;
use Wikimedia\ArrayUtils\ArrayUtils;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\CookieSessionProvider
 */
class CookieSessionProviderTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	/** Value of `$request->response()->getCookie()` when the cookie was deleted. */
	private const DELETED = '';
	/** Value of `$request->response()->getCookie()` when the cookie was unchanged. */
	private const UNCHANGED = null;

	private function getConfig() {
		return new HashConfig( [
			MainConfigNames::CookiePrefix => 'CookiePrefix',
			MainConfigNames::CookiePath => 'CookiePath',
			MainConfigNames::CookieDomain => 'CookieDomain',
			MainConfigNames::CookieSecure => true,
			MainConfigNames::CookieHttpOnly => true,
			MainConfigNames::CookieSameSite => '',
			MainConfigNames::SessionName => false,
			MainConfigNames::CookieExpiration => 100,
			MainConfigNames::ExtendedLoginCookieExpiration => 200,
			MainConfigNames::ForceHTTPS => false,
		] );
	}

	/**
	 * Provider for testing both values of $wgForceHTTPS
	 */
	public static function provideForceHTTPS() {
		return [
			[ false ],
			[ true ]
		];
	}

	public function testConstructor() {
		try {
			new CookieSessionProvider();
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		try {
			new CookieSessionProvider( [ 'priority' => 'foo' ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}
		try {
			new CookieSessionProvider( [ 'priority' => SessionInfo::MIN_PRIORITY - 1 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}
		try {
			new CookieSessionProvider( [ 'priority' => SessionInfo::MAX_PRIORITY + 1 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			new CookieSessionProvider( [ 'priority' => 1, 'cookieOptions' => null ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: cookieOptions must be an array',
				$ex->getMessage()
			);
		}

		$config = $this->getConfig();
		$provider = new CookieSessionProvider( [ 'priority' => 1 ] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider( $provider, new TestLogger(), $config );
		$this->assertSame( 1, $providerPriv->priority );
		$this->assertEquals( [
			'sessionName' => 'CookiePrefix_session',
		], $providerPriv->params );
		$this->assertEquals( [
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => true,
			'httpOnly' => true,
			'sameSite' => '',
		], $providerPriv->cookieOptions );

		$config->set( MainConfigNames::SessionName, 'SessionName' );
		$provider = new CookieSessionProvider( [ 'priority' => 3 ] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider( $provider, new TestLogger(), $config );
		$this->assertEquals( 3, $providerPriv->priority );
		$this->assertEquals( [
			'sessionName' => 'SessionName',
		], $providerPriv->params );
		$this->assertEquals( [
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => true,
			'httpOnly' => true,
			'sameSite' => '',
		], $providerPriv->cookieOptions );

		$provider = new CookieSessionProvider( [
			'priority' => 10,
			'cookieOptions' => [
				'prefix' => 'XPrefix',
				'path' => 'XPath',
				'domain' => 'XDomain',
				'secure' => 'XSecure',
				'httpOnly' => 'XHttpOnly',
				'sameSite' => 'XSameSite',
			],
			'sessionName' => 'XSession',
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider( $provider, new TestLogger(), $config );
		$this->assertEquals( 10, $providerPriv->priority );
		$this->assertEquals( [
			'sessionName' => 'XSession',
		], $providerPriv->params );
		$this->assertEquals( [
			'prefix' => 'XPrefix',
			'path' => 'XPath',
			'domain' => 'XDomain',
			'secure' => 'XSecure',
			'httpOnly' => 'XHttpOnly',
			'sameSite' => 'XSameSite',
		], $providerPriv->cookieOptions );
	}

	public function testBasics() {
		$provider = new CookieSessionProvider( [ 'priority' => 10 ] );

		$this->assertTrue( $provider->persistsSessionId() );
		$this->assertTrue( $provider->canChangeUser() );

		$extendedCookies = [ 'UserID', 'UserName', 'Token' ];

		$this->assertEquals(
			$extendedCookies,
			TestingAccessWrapper::newFromObject( $provider )->getExtendedLoginCookies(),
			'List of extended cookies (subclasses can add values, but we\'re calling the core one here)'
		);

		$msg = $provider->whyNoSession();
		$this->assertInstanceOf( Message::class, $msg );
		$this->assertSame( 'sessionprovider-nocookies', $msg->getKey() );
	}

	public function testProvideSessionInfo() {
		$params = [
			'priority' => 20,
			'sessionName' => 'session',
			'cookieOptions' => [ 'prefix' => 'x' ],
		];
		$provider = new CookieSessionProvider( $params );
		$logger = new TestLogger( true );
		$this->initProvider(
			$provider, $logger, $this->getConfig(), $this->getServiceContainer()->getSessionManager()
		);

		$user = static::getTestSysop()->getUser();
		$id = $user->getId();
		$name = $user->getName();
		$token = $user->getToken( true );
		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		// No data
		$request = new FauxRequest();
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// Session key only
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( 0, $info->getUserInfo()->getId() );
		$this->assertNull( $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'Session "{session}" requested without UserID cookie',
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// User, no session key
		$request = new FauxRequest();
		$request->setCookies( [
			'xUserID' => $id,
			'xToken' => $token,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertNotSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// User and session key
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => $id,
			'xToken' => $token,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// User with bad token
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => $id,
			'xToken' => 'BADTOKEN',
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
		$this->assertSame( [
			[
				LogLevel::WARNING,
				'Session "{session}" requested with invalid Token cookie.'
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// User id with no token
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => $id,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertFalse( $info->getUserInfo()->isVerified() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		$request = new FauxRequest();
		$request->setCookies( [
			'xUserID' => $id,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// User and session key, with forceHTTPS flag
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => $id,
			'xToken' => $token,
			'forceHTTPS' => true,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// Invalid user id
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => '-1',
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// User id with matching name
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => $id,
			'xUserName' => $name,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertFalse( $info->getUserInfo()->isVerified() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		// User id with wrong name
		$request = new FauxRequest();
		$request->setCookies( [
			'session' => $sessionId,
			'xUserID' => $id,
			'xUserName' => 'Wrong',
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
		$this->assertSame( [
			[
				LogLevel::WARNING,
				'Session "{session}" requested with mismatched UserID and UserName cookies.',
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();
	}

	public function testGetVaryCookies() {
		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'MyCookiePrefix' ],
		] );
		$this->assertArrayEquals( [
			'MyCookiePrefixToken',
			'MyCookiePrefixLoggedOut',
			'MySessionName',
			'forceHTTPS',
		], $provider->getVaryCookies() );
	}

	public function testSuggestLoginUsername() {
		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'x' ],
		] );
		$this->initProvider(
			$provider, null, $this->getConfig(), null, null, $this->getServiceContainer()->getUserNameUtils()
		);

		$request = new FauxRequest();
		$this->assertNull( $provider->suggestLoginUsername( $request ) );

		$request->setCookies( [
			'xUserName' => 'Example',
		], prefix: '' );
		$this->assertEquals( 'Example', $provider->suggestLoginUsername( $request ) );
	}

	/** @dataProvider provideForceHTTPS */
	public function testPersistSession( $forceHTTPS ) {
		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'x' ],
		] );
		$config = $this->getConfig();
		$config->set( MainConfigNames::ForceHTTPS, $forceHTTPS );
		$hookContainer = $this->createHookContainer();
		$this->initProvider( $provider, new TestLogger(), $config, SessionManager::singleton(), $hookContainer );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$store = new TestBagOStuff();

		// For User::requiresHTTPS
		$this->overrideConfigValue( MainConfigNames::ForceHTTPS, $forceHTTPS );

		$user = static::getTestSysop()->getUser();
		$anon = new User;

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, [
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'idIsSafe' => true,
			] ),
			$store,
			new NullLogger(),
			$hookContainer,
			10
		);
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;

		// Anonymous user
		$backend->setUser( $anon );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( false );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xToken' ) );
		if ( $forceHTTPS ) {
			$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'forceHTTPS' ) );
		} else {
			$this->assertSame( self::DELETED, $request->response()->getCookie( 'forceHTTPS' ) );
		}
		$this->assertSame( [], $backend->getData() );

		// Logged-in user, no remember
		$backend->setUser( $user );
		$backend->setRememberUser( false );
		$backend->setForceHTTPS( false );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xToken' ) );
		if ( $forceHTTPS ) {
			$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'forceHTTPS' ) );
		} else {
			$this->assertSame( self::DELETED, $request->response()->getCookie( 'forceHTTPS' ) );
		}
		$this->assertSame( [], $backend->getData() );

		// Logged-in user, remember
		$backend->setUser( $user );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( true );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( $user->getToken(), $request->response()->getCookie( 'xToken' ) );
		if ( $forceHTTPS ) {
			$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'forceHTTPS' ) );
		} else {
			$this->assertSame( 'true', $request->response()->getCookie( 'forceHTTPS' ) );
		}
		$this->assertSame( [], $backend->getData() );
	}

	/**
	 * @dataProvider provideCookieData
	 * @param bool $secure
	 * @param bool $remember
	 * @param bool $forceHTTPS
	 */
	public function testCookieData( $secure, $remember, $forceHTTPS ) {
		$startTime = 1_000_000;
		ConvertibleTimestamp::setFakeTime( $startTime );
		$this->overrideConfigValues( [
			MainConfigNames::SecureLogin => false,
			MainConfigNames::ForceHTTPS => $forceHTTPS,
		] );
		// match WebRespose::clearCookie()
		$deletedTime = $startTime - ExpirationAwareness::TTL_YEAR;

		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'x' ],
		] );
		$config = $this->getConfig();
		$config->set( MainConfigNames::CookieSecure, $secure );
		$config->set( MainConfigNames::ForceHTTPS, $forceHTTPS );
		$hookContainer = $this->createHookContainer();
		$this->initProvider( $provider, new TestLogger(), $config, SessionManager::singleton(), $hookContainer );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$user = static::getTestSysop()->getUser();
		$this->assertSame( $user->requiresHTTPS(), $forceHTTPS );

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, [
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'idIsSafe' => true,
			] ),
			new TestBagOStuff(),
			new NullLogger(),
			$hookContainer,
			10
		);
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;
		$backend->setUser( $user );
		$backend->setRememberUser( $remember );
		$backend->setForceHTTPS( $secure );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );

		$defaults = [
			'expire' => $startTime + 100,
			'path' => $config->get( MainConfigNames::CookiePath ),
			'domain' => $config->get( MainConfigNames::CookieDomain ),
			'secure' => $secure || $forceHTTPS,
			'httpOnly' => $config->get( MainConfigNames::CookieHttpOnly ),
			'raw' => false,
		];

		$normalExpiry = $config->get( MainConfigNames::CookieExpiration );
		$extendedExpiry = $config->get( MainConfigNames::ExtendedLoginCookieExpiration );
		$extendedExpiry = (int)( $extendedExpiry ?? 0 );
		$expect = [
			'MySessionName' => [
				'value' => (string)$sessionId,
				'expire' => 0,
			] + $defaults,
			'xUserID' => [
				'value' => (string)$user->getId(),
				'expire' => $startTime + ( $remember ? $extendedExpiry : $normalExpiry ),
			] + $defaults,
			'xUserName' => [
				'value' => $user->getName(),
				'expire' => $startTime + ( $remember ? $extendedExpiry : $normalExpiry )
			] + $defaults,
			'xToken' => [
				'value' => $remember ? $user->getToken() : '',
				'expire' => $remember ? $startTime + $extendedExpiry : $deletedTime,
			] + $defaults
		];
		if ( !$forceHTTPS ) {
			$expect['forceHTTPS'] = [
				'value' => $secure ? 'true' : '',
				'secure' => false,
				'expire' => $secure ? ( $remember ? $defaults['expire'] : 0 ) : $deletedTime,
			] + $defaults;
		}
		foreach ( $expect as $key => $value ) {
			$actual = $request->response()->getCookieData( $key );
			$this->assertEquals( $value, $actual, "Cookie $key" );
		}
	}

	public static function provideCookieData() {
		return ArrayUtils::cartesianProduct(
			[ false, true ], // $secure
			[ false, true ], // $remember
			[ false, true ] // $forceHTTPS
		);
	}

	protected function getSentRequest() {
		$sentResponse = $this->getMockBuilder( FauxResponse::class )
			->onlyMethods( [ 'headersSent', 'setCookie', 'header' ] )->getMock();
		$sentResponse->method( 'headersSent' )
			->willReturn( true );
		$sentResponse->expects( $this->never() )->method( 'setCookie' );
		$sentResponse->expects( $this->never() )->method( 'header' );

		$sentRequest = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'response' ] )->getMock();
		$sentRequest->method( 'response' )
			->willReturn( $sentResponse );
		return $sentRequest;
	}

	public function testUnpersistSession() {
		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'x' ],
		] );
		$this->initProvider(
			$provider, null, $this->getConfig(), SessionManager::singleton(), $this->createHookContainer()
		);

		$request = new FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'forceHTTPS' ) );

		$provider->unpersistSession( $this->getSentRequest() );
	}

	public function testSetLoggedOutCookie() {
		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'x' ],
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider(
			$provider, null, $this->getConfig(), SessionManager::singleton(), $this->createHookContainer()
		);

		$t1 = time();
		$t2 = time() - 86400 * 2;

		// Set it
		$request = new FauxRequest();
		$providerPriv->setLoggedOutCookie( $t1, $request );
		$this->assertSame( (string)$t1, $request->response()->getCookie( 'xLoggedOut' ) );

		// Too old
		$request = new FauxRequest();
		$providerPriv->setLoggedOutCookie( $t2, $request );
		$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'xLoggedOut' ) );

		// Don't reset if it's already set
		$request = new FauxRequest();
		$request->setCookies( [
			'xLoggedOut' => $t1,
		], prefix: '' );
		$providerPriv->setLoggedOutCookie( $t1, $request );
		$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'xLoggedOut' ) );
	}

	public function testGetCookie() {
		$provider = new CookieSessionProvider( [
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => [ 'prefix' => 'x' ],
		] );
		$this->initProvider(
			$provider, null, $this->getConfig(), SessionManager::singleton(), $this->createHookContainer()
		);
		$provider = TestingAccessWrapper::newFromObject( $provider );

		$request = new FauxRequest();
		$request->setCookies( [
			'xFoo' => 'foo!',
			'xBar' => 'deleted',
		], prefix: '' );
		$this->assertSame( 'foo!', $provider->getCookie( $request, 'Foo', 'x' ) );
		$this->assertNull( $provider->getCookie( $request, 'Bar', 'x' ) );
		$this->assertNull( $provider->getCookie( $request, 'Baz', 'x' ) );
	}

	public function testGetRememberUserDuration() {
		$config = $this->getConfig();
		$provider = new CookieSessionProvider( [ 'priority' => 10 ] );
		$this->initProvider( $provider, null, $config, SessionManager::singleton(), $this->createHookContainer() );

		$this->assertSame( 200, $provider->getRememberUserDuration() );

		$config->set( MainConfigNames::ExtendedLoginCookieExpiration, null );

		$this->assertSame( 100, $provider->getRememberUserDuration() );

		$config->set( MainConfigNames::ExtendedLoginCookieExpiration, 0 );

		$this->assertSame( null, $provider->getRememberUserDuration() );
	}

	public function testGetLoginCookieExpiration() {
		$config = $this->getConfig();
		$provider = new CookieSessionProvider( [
			'priority' => 10
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->initProvider( $provider, null, $config, SessionManager::singleton(), $this->createHookContainer() );

		// First cookie is an extended cookie, remember me true
		$this->assertSame( 200, $providerPriv->getLoginCookieExpiration( 'Token', true ) );
		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'User', true ) );

		// First cookie is an extended cookie, remember me false
		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'UserID', false ) );
		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'User', false ) );

		$config->set( MainConfigNames::ExtendedLoginCookieExpiration, null );

		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'Token', true ) );
		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'User', true ) );

		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'Token', false ) );
		$this->assertSame( 100, $providerPriv->getLoginCookieExpiration( 'User', false ) );
	}
}
