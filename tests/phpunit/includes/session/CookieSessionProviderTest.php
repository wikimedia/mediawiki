<?php

namespace MediaWiki\Tests\Session;

use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\Json\JwtCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\Session\CookieSessionProvider;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Tests\Mocks\Json\PlainJsonJwtCodec;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\Utils\UrlUtils;
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

	public static function provideUseSessionCookieJwt() {
		return [
			'no JWT' => [ false ],
			'JWT' => [ true ],
		];
	}

	private function getConfig() {
		return new HashConfig( [
			MainConfigNames::CookiePrefix => 'CookiePrefix',
			MainConfigNames::CookiePath => 'CookiePath',
			MainConfigNames::CookieDomain => 'CookieDomain',
			MainConfigNames::CookieSecure => true,
			MainConfigNames::CookieHttpOnly => true,
			MainConfigNames::CookieSameSite => '',
			MainConfigNames::SessionName => false,
			MainConfigNames::ObjectCacheSessionExpiry => 50,
			MainConfigNames::CookieExpiration => 100,
			MainConfigNames::ExtendedLoginCookieExpiration => 200,
			MainConfigNames::SessionCookieJwtExpiration => 10,
			MainConfigNames::ForceHTTPS => false,
		] );
	}

	public function testConstructor() {
		try {
			new CookieSessionProvider(
				$this->createNoOpMock( JwtCodec::class ),
				$this->createNoOpMock( UrlUtils::class )
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		try {
			new CookieSessionProvider(
				$this->createNoOpMock( JwtCodec::class ),
				$this->createNoOpMock( UrlUtils::class ),
				[ 'priority' => 'foo' ]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}
		try {
			new CookieSessionProvider(
				$this->createNoOpMock( JwtCodec::class ),
				$this->createNoOpMock( UrlUtils::class ),
				[ 'priority' => SessionInfo::MIN_PRIORITY - 1 ]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}
		try {
			new CookieSessionProvider(
				$this->createNoOpMock( JwtCodec::class ),
				$this->createNoOpMock( UrlUtils::class ),
				[ 'priority' => SessionInfo::MAX_PRIORITY + 1 ]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			new CookieSessionProvider(
				$this->createNoOpMock( JwtCodec::class ),
				$this->createNoOpMock( UrlUtils::class ),
				[ 'priority' => 1, 'cookieOptions' => null ]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: cookieOptions must be an array',
				$ex->getMessage()
			);
		}

		$config = $this->getConfig();
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[ 'priority' => 1 ]
		);
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
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[ 'priority' => 3 ]
		);
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

		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[
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
			]
		);
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
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[ 'priority' => 10 ]
		);

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

	/**
	 * @dataProvider provideUseSessionCookieJwt
	 */
	public function testProvideSessionInfo( bool $useSessionCookieJwt ) {
		$startTime = 1_000_000;
		$jwtExpiry = $this->getConfig()->get( MainConfigNames::SessionCookieJwtExpiration );
		ConvertibleTimestamp::setFakeTime( $startTime );
		$centralIdMap = &$this->mockCentralIdLookup();
		$logger = new TestLogger( true );
		$logger2 = new TestLogger( true, static function ( string $message ) {
			if ( str_starts_with( $message, 'Session store:' ) ) {
				return null;
			}
			return $message;
		} );
		$params = [
			'priority' => 20,
			'sessionName' => 'session',
			'cookieOptions' => [ 'prefix' => 'x' ],
		];
		$provider = new CookieSessionProvider(
			new PlainJsonJwtCodec(),
			$this->getMockUrlUtils( canonicalServer: 'http://example.org' ),
			$params
		);
		$config = $this->getConfig();
		$config->set( MainConfigNames::UseSessionCookieJwt, $useSessionCookieJwt );
		$this->initProvider(
			$provider, $logger, $config, $this->getServiceContainer()->getSessionManager()
		);
		$this->setLogger( 'session-sampled', $logger2 );

		$user = static::getTestSysop()->getUser();
		$id = $user->getId();
		$name = $user->getName();
		$token = $user->getToken( true );
		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$centralIdMap = [ $user->getName() => 123 ];

		// No data
		$request = new FauxRequest();
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info?->__toString() );
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
		// With JWTs enabled, the missing JWT cookie should force a refresh
		$this->assertSame( $useSessionCookieJwt, $info->needsRefresh() );
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
		$this->assertSame( $useSessionCookieJwt, $info->needsRefresh() );
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
		$this->assertSame( $useSessionCookieJwt, $info->needsRefresh() );
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
		$this->assertNull( $info?->__toString() );
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
		$this->assertSame( $useSessionCookieJwt, $info->needsRefresh() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );
		$logger->clearBuffer();

		$request = new FauxRequest();
		$request->setCookies( [
			'xUserID' => $id,
		], prefix: '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info?->__toString() );
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
		$this->assertSame( $useSessionCookieJwt, $info->needsRefresh() );
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
		$this->assertNull( $info?->__toString() );
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
		$this->assertSame( $useSessionCookieJwt, $info->needsRefresh() );
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
		$this->assertNull( $info?->__toString() );
		$this->assertSame( [
			[
				LogLevel::WARNING,
				'Session "{session}" requested with mismatched UserID and UserName cookies.',
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		if ( $useSessionCookieJwt ) {
			$codec = new PlainJsonJwtCodec();
			$defaultClaims = [
				'jti' => 'random123',
				'iss' => 'http://example.org',
				'sxp' => $startTime + $jwtExpiry,
				'sub' => 'mw:mock::123',
			];

			// User with mismatching central ID
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( [ 'sub' => 'mw:mock::456' ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			// avoid printing a hundred-line diff when this assertion fails
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [ [ LogLevel::INFO, 'JWT validation failed: JWT error: wrong user ID' ] ],
				$logger->getBuffer() );
			$logger->clearBuffer();

			// User with mismatching issuer
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( [ 'iss' => 'http://evil.com' ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [ [ LogLevel::INFO, 'JWT validation failed: JWT error: wrong issuer' ] ],
				$logger->getBuffer() );
			$logger->clearBuffer();

			// Anon JWT for non-anon user
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( [ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [ [ LogLevel::INFO, 'JWT validation failed: JWT error: wrong subject' ] ],
				$logger->getBuffer() );
			$logger->clearBuffer();

			// User with valid JWT
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertNotNull( $info->getUserInfo() );
			$this->assertFalse( $info->getUserInfo()->isVerified() );
			$this->assertSame( $id, $info->getUserInfo()->getId() );
			$this->assertSame( $name, $info->getUserInfo()->getName() );
			$this->assertFalse( $info->needsRefresh() );
			$this->assertFalse( $info->forceHTTPS() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// User with JWT only
			$request = new FauxRequest();
			$request->setCookies( [
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// Anon user, non-anon JWT
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [
				[ LogLevel::DEBUG, 'Session "{session}" requested without UserID cookie' ],
				[ LogLevel::INFO, 'JWT validation failed: JWT error: wrong subject' ],
			], $logger->getBuffer() );
			$logger->clearBuffer();

			// Anon user, anon JWT
			// Note that we don't actually set JWT cookies for anon users. But it's conceptually
			// possible to have a valid JWT describing an anonymous user, so let's cover that scenario.
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'sessionJwt' => $codec->create( [ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertNotNull( $info->getUserInfo() );
			$this->assertTrue( $info->getUserInfo()->isAnon() );
			$this->assertSame( 0, $info->getUserInfo()->getId() );
			$this->assertNull( $info->getUserInfo()->getName() );
			$this->assertFalse( $info->needsRefresh() );
			$this->assertFalse( $info->forceHTTPS() );
			$this->assertFalse( $info->forceHTTPS() );
			$this->assertSame( [ [ LogLevel::DEBUG, 'Session "{session}" requested without UserID cookie' ] ],
				$logger->getBuffer() );
			$logger->clearBuffer();

			// (soft-)expired JWT
			ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE + 1 );
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertNotNull( $info->getUserInfo() );
			$this->assertFalse( $info->getUserInfo()->isVerified() );
			$this->assertSame( $id, $info->getUserInfo()->getId() );
			$this->assertSame( $name, $info->getUserInfo()->getName() );
			$this->assertTrue( $info->needsRefresh() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();
			$this->assertSame( [ [ LogLevel::WARNING, 'Soft-expired JWT cookie' ] ], $logger2->getBuffer() );
			$logger2->clearBuffer();

			// near-expired JWT
			ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry - 1 );
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertNotNull( $info->getUserInfo() );
			$this->assertFalse( $info->getUserInfo()->isVerified() );
			$this->assertSame( $id, $info->getUserInfo()->getId() );
			$this->assertSame( $name, $info->getUserInfo()->getName() );
			$this->assertTrue( $info->needsRefresh() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// JWT with valid hard-expiry
			ConvertibleTimestamp::setFakeTime( $startTime );
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( $defaultClaims + [
					'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
				] ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertNotNull( $info->getUserInfo() );
			$this->assertFalse( $info->getUserInfo()->isVerified() );
			$this->assertSame( $id, $info->getUserInfo()->getId() );
			$this->assertSame( $name, $info->getUserInfo()->getName() );
			$this->assertFalse( $info->needsRefresh() );
			$this->assertFalse( $info->forceHTTPS() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// JWT with expired hard-expiry
			ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE + 1 );
			$request = new FauxRequest();
			$request->setCookies( [
				'session' => $sessionId,
				'xUserID' => $id,
				'sessionJwt' => $codec->create( $defaultClaims + [
					'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
				] ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [ [ LogLevel::INFO, 'JWT validation failed: JWT error: hard-expired' ] ], $logger->getBuffer() );
			$logger->clearBuffer();
		}
	}

	/**
	 * @dataProvider provideUseSessionCookieJwt
	 */
	public function testGetVaryCookies( bool $useSessionCookieJwt ) {
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'MyCookiePrefix' ],
			]
		);
		$config = $this->getConfig();
		$config->set( MainConfigNames::UseSessionCookieJwt, $useSessionCookieJwt );
		$this->initProvider( $provider, null, $config );

		$expectedCookies = [
			'MyCookiePrefixToken',
			'MyCookiePrefixLoggedOut',
			'MySessionName',
			'forceHTTPS',
		];
		if ( $useSessionCookieJwt ) {
			$expectedCookies[] = 'sessionJwt';
		}
		$this->assertArrayEquals( $expectedCookies, $provider->getVaryCookies() );
	}

	public function testSuggestLoginUsername() {
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'x' ],
			]
		);
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

	/** @dataProvider providePersistSession */
	public function testPersistSession( bool $forceHTTPS, bool $useSessionCookieJwt ) {
		$startTime = 1_000_000;
		$jwtExpiry = $this->getConfig()->get( MainConfigNames::SessionCookieJwtExpiration );
		ConvertibleTimestamp::setFakeTime( $startTime );
		$centralIdMap = &$this->mockCentralIdLookup();
		$hookContainer = $this->createHookContainer();
		$provider = new CookieSessionProvider(
			new PlainJsonJwtCodec(),
			$this->getMockUrlUtils( canonicalServer: 'http://example.org' ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'x' ],
			]
		);
		$config = $this->getConfig();
		$config->set( MainConfigNames::ForceHTTPS, $forceHTTPS );
		$config->set( MainConfigNames::UseSessionCookieJwt, $useSessionCookieJwt );
		$this->initProvider( $provider, new TestLogger(), $config, SessionManager::singleton(), $hookContainer );

		$jwtDefaults = [
			'iss' => 'http://example.org',
			'iat' => $startTime,
			'sxp' => $startTime + $jwtExpiry,
			'exp' => null,
		];

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
			$this->getServiceContainer()->getSessionStore(),
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
		if ( $useSessionCookieJwt ) {
			$expectedJwtCookie = self::DELETED;
		} else {
			$expectedJwtCookie = self::UNCHANGED;
		}
		$this->assertJwtMatches( $expectedJwtCookie, $request->response()->getCookie( 'sessionJwt' ) );
		$this->assertSame( [], $backend->getData() );

		// Logged-in user, no remember
		$centralIdMap = [ $user->getName() => 123 ];
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
		if ( $useSessionCookieJwt ) {
			$expectedJwtCookie = [ 'sub' => 'mw:mock::123' ] + $jwtDefaults;
		} else {
			$expectedJwtCookie = self::UNCHANGED;
		}
		$this->assertJwtMatches( $expectedJwtCookie, $request->response()->getCookie( 'sessionJwt' ) );
		$this->assertSame( [], $backend->getData() );

		// Logged-in user, remember
		$centralIdMap = [ $user->getName() => 123 ];
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
		if ( $useSessionCookieJwt ) {
			$expectedJwtCookie = [ 'sub' => 'mw:mock::123' ] + $jwtDefaults;
		} else {
			$expectedJwtCookie = self::UNCHANGED;
		}
		$this->assertJwtMatches( $expectedJwtCookie, $request->response()->getCookie( 'sessionJwt' ) );
		$this->assertSame( [], $backend->getData() );

		// Multiple persists should not result in duplicated Set-Cookie headers
		$cookies = [];
		$expectedCookies = [ 'xUserID', 'xUserName', 'xToken' ];
		WebResponse::resetCookieCache();
		if ( $useSessionCookieJwt ) {
			$expectedCookies[] = 'sessionJwt';
		}
		$centralIdMap = [ $user->getName() => 123 ];
		$backend->setUser( $user );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( true );
		$response = $this->createPartialMock( WebResponse::class, [ 'actuallySetCookie' ] );
		$response->method( 'actuallySetCookie' )->willReturnCallback(
			function ( string $func, string $prefixedName, string $value, array $setOptions ) use ( &$cookies ): void {
				if ( array_key_exists( $prefixedName, $cookies ) ) {
					$this->fail( 'Cookie set twice: ' . $prefixedName );
				}
				$cookies[$prefixedName] = true;
			}
		);
		$request = $this->createPartialMock( WebRequest::class, [ 'response' ] );
		$request->method( 'response' )->willReturn( $response );
		$provider->persistSession( $backend, $request );
		$provider->persistSession( $backend, $request );
		$provider->persistSession( $backend, $request );
		$this->assertArrayContains( $expectedCookies, array_keys( $cookies ) );
	}

	public static function providePersistSession() {
		return [
			'default' => [ false, false ],
			'force HTTPS' => [ true, false ],
			'use session cookie JWT' => [ false, true ],
		];
	}

	/**
	 * @dataProvider provideCookieData
	 */
	public function testCookieData( bool $secure, bool $remember, bool $forceHTTPS, bool $useSessionCookieJwt ) {
		$startTime = 1_000_000;
		$sessionStoreExpiry = $this->getConfig()->get( MainConfigNames::ObjectCacheSessionExpiry );
		$normalCookieExpiry = $this->getConfig()->get( MainConfigNames::CookieExpiration );
		$extendedCookieExpiry = $this->getConfig()->get( MainConfigNames::ExtendedLoginCookieExpiration );
		$extendedCookieExpiry = (int)( $extendedCookieExpiry ?? 0 );
		$jwtExpiry = $this->getConfig()->get( MainConfigNames::SessionCookieJwtExpiration );
		ConvertibleTimestamp::setFakeTime( $startTime );
		$this->overrideConfigValues( [
			MainConfigNames::SecureLogin => false,
			MainConfigNames::ForceHTTPS => $forceHTTPS,
		] );
		// match WebRespose::clearCookie()
		$deletedTime = $startTime - ExpirationAwareness::TTL_YEAR;
		$centralIdMap = &$this->mockCentralIdLookup();

		$provider = new CookieSessionProvider(
			new PlainJsonJwtCodec(),
			$this->getMockUrlUtils( canonicalServer: 'http://example.org' ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'x' ],
			]
		);
		$config = $this->getConfig();
		$config->set( MainConfigNames::CookieSecure, $secure );
		$config->set( MainConfigNames::ForceHTTPS, $forceHTTPS );
		$config->set( MainConfigNames::UseSessionCookieJwt, $useSessionCookieJwt );
		$hookContainer = $this->createHookContainer();
		$this->initProvider( $provider, new TestLogger(), $config, SessionManager::singleton(), $hookContainer );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$user = static::getTestSysop()->getUser();
		$this->assertSame( $user->requiresHTTPS(), $forceHTTPS );
		$centralIdMap = [ $user->getName() => 123 ];

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, [
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'idIsSafe' => true,
			] ),
			$this->getServiceContainer()->getSessionStore(),
			new NullLogger(),
			$hookContainer,
			$sessionStoreExpiry
		);
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;
		$backend->setUser( $user );
		$backend->setRememberUser( $remember );
		$backend->setForceHTTPS( $secure );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );

		$defaults = [
			'expire' => $startTime + $normalCookieExpiry,
			'path' => $config->get( MainConfigNames::CookiePath ),
			'domain' => $config->get( MainConfigNames::CookieDomain ),
			'secure' => $secure || $forceHTTPS,
			'httpOnly' => $config->get( MainConfigNames::CookieHttpOnly ),
			'raw' => false,
		];

		$expect = [
			'MySessionName' => [
				'value' => (string)$sessionId,
				'expire' => 0,
			] + $defaults,
			'xUserID' => [
				'value' => (string)$user->getId(),
				'expire' => $startTime + ( $remember ? $extendedCookieExpiry : $normalCookieExpiry ),
			] + $defaults,
			'xUserName' => [
				'value' => $user->getName(),
				'expire' => $startTime + ( $remember ? $extendedCookieExpiry : $normalCookieExpiry )
			] + $defaults,
			'xToken' => [
				'value' => $remember ? $user->getToken() : '',
				'expire' => $remember ? $startTime + $extendedCookieExpiry : $deletedTime,
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
		if ( !$useSessionCookieJwt ) {
			$this->assertNull( $request->response()->getCookieData( 'sessionJwt' ) );
		} else {
			$cookieData = $request->response()->getCookieData( 'sessionJwt' );
			// no need to repeat here the check from testPersistSession()
			unset( $cookieData['value'] );
			$this->assertSame( [
				'expire' => $startTime + $jwtExpiry,
			] + $defaults, $cookieData );
		}
	}

	public static function provideCookieData() {
		return ArrayUtils::cartesianProduct(
			[ false, true ], // $secure
			[ false, true ], // $remember
			[ false, true ], // $forceHTTPS
			[ false, true ], // $useSessionCookieJwt
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

	/**
	 * @dataProvider provideUseSessionCookieJwt
	 */
	public function testUnpersistSession( bool $useSessionCookieJwt ) {
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'x' ],
			]
		);
		$config = $this->getConfig();
		$config->set( MainConfigNames::UseSessionCookieJwt, $useSessionCookieJwt );
		$this->initProvider(
			$provider, null, $config, SessionManager::singleton(), $this->createHookContainer()
		);

		$request = new FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( self::UNCHANGED, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( self::DELETED, $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame(
			$useSessionCookieJwt ? self::DELETED : self::UNCHANGED,
			$request->response()->getCookie( 'sessionJwt' )
		);

		$provider->unpersistSession( $this->getSentRequest() );
	}

	public function testSetLoggedOutCookie() {
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'x' ],
			]
		);
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
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[
				'priority' => 1,
				'sessionName' => 'MySessionName',
				'cookieOptions' => [ 'prefix' => 'x' ],
			]
		);
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
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[ 'priority' => 10 ]
		);
		$this->initProvider( $provider, null, $config, SessionManager::singleton(), $this->createHookContainer() );

		$this->assertSame( 200, $provider->getRememberUserDuration() );

		$config->set( MainConfigNames::ExtendedLoginCookieExpiration, null );

		$this->assertSame( 100, $provider->getRememberUserDuration() );

		$config->set( MainConfigNames::ExtendedLoginCookieExpiration, 0 );

		$this->assertSame( null, $provider->getRememberUserDuration() );
	}

	public function testGetLoginCookieExpiration() {
		$config = $this->getConfig();
		$provider = new CookieSessionProvider(
			$this->createNoOpMock( JwtCodec::class ),
			$this->createNoOpMock( UrlUtils::class ),
			[ 'priority' => 10 ]
		);
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

	private function getMockUrlUtils( string $canonicalServer ): UrlUtils {
		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'getCanonicalServer' ] );
		$urlUtils->method( 'getCanonicalServer' )->willReturn( $canonicalServer );
		return $urlUtils;
	}

	/**
	 * @param string|array|null $expectedCookie self::DELETED, self::UNCHANGED or a set of claims to check
	 * @param string|null $actualCookie
	 */
	private function assertJwtMatches( $expectedCookie, ?string $actualCookie ): void {
		if ( $expectedCookie === self::UNCHANGED
			|| $expectedCookie === self::DELETED
		) {
			$this->assertSame( $expectedCookie, $actualCookie );
		} else {
			$this->assertNotNull( $actualCookie );
			$claims = json_decode( $actualCookie, true, 512, JSON_THROW_ON_ERROR );
			$this->assertIsArray( $claims );
			foreach ( $expectedCookie as $expectedClaim => $expectedValue ) {
				if ( $expectedValue === null ) {
					$this->assertArrayNotHasKey( $expectedClaim, $claims );
				} else {
					$this->assertArrayHasKey( $expectedClaim, $claims );
					$this->assertSame( $expectedValue, $claims[$expectedClaim] );
				}
			}
		}
	}

	/**
	 * Create a CentralIdLookup with mocked lookupOwnedUserNames / getScope / getProviderId methods.
	 * @return array A reference to the username => ID map.
	 */
	private function &mockCentralIdLookup(): array {
		$centralIdMap = [];
		// the class is abstract but the mocked methods aren't and that apparently breaks createNoOpAbstractMock
		$lookup = $this->createNoOpMock( CentralIdLookup::class,
			[ 'lookupOwnedUserNames', 'getScope', 'getProviderId' ] );
		$lookup->method( 'lookupOwnedUserNames' )->willReturnCallback(
			static function ( $nameToId ) use ( &$centralIdMap ) {
				return array_intersect_key( $centralIdMap, $nameToId );
			}
		);
		$lookup->method( 'getScope' )->willReturn( 'mock:' );
		$lookup->method( 'getProviderId' )->willReturn( 'mock' );
		$this->setService( 'CentralIdLookup', $lookup );
		return $centralIdMap;
	}
}
