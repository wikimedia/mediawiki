<?php

namespace MediaWiki\Tests\Session;

use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\CookieSessionProvider;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SingleBackendSessionStore;
use MediaWiki\Session\UserInfo;
use MediaWiki\Tests\Mocks\Json\PlainJsonJwtCodec;
use MediaWiki\User\BotPassword;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\BotPasswordSessionProvider
 */
class BotPasswordSessionProviderTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	/** @var HashConfig */
	private $config;
	/** @var string */
	private $configHash;

	public static function provideUseSessionCookieJwt() {
		return [
			'no JWT' => [ false ],
			'JWT' => [ true ],
		];
	}

	private function getProvider( $name = null, $prefix = null, $isApiRequest = true ) {
		global $wgSessionProviders;

		$params = [
			'priority' => 40,
			'sessionCookieName' => $name,
			'sessionCookieOptions' => [],
			'isApiRequest' => $isApiRequest,
		];
		if ( $prefix !== null ) {
			$params['sessionCookieOptions']['prefix'] = $prefix;
		}

		$emptySessionProvider = array_filter(
			$wgSessionProviders,
			static fn ( $spec ) => $spec['class'] === CookieSessionProvider::class
		);
		$sessionProviders = [
			BotPasswordSessionProvider::class => [
				'class' => BotPasswordSessionProvider::class,
				'args' => [ $params ],
				'services' => [ 'GrantsInfo' ],
			],
		] + $emptySessionProvider;

		$configHash = json_encode( [ $name, $prefix, $isApiRequest ] );
		if ( !$this->config || $this->configHash !== $configHash ) {
			$this->config = new HashConfig( [
				MainConfigNames::CookiePrefix => 'wgCookiePrefix',
				MainConfigNames::EnableBotPasswords => true,
				MainConfigNames::SessionProviders => $sessionProviders,
				MainConfigNames::SessionCookieJwtExpiration => 10,
				MainConfigNames::JwtSessionCookieIssuer => 'http://example.org',
			] );
			$this->configHash = $configHash;
		}

		$manager = new SessionManager(
			new MultiConfig( [ $this->config, $this->getServiceContainer()->getMainConfig() ] ),
			new NullLogger,
			$this->getServiceContainer()->getCentralIdLookup(),
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUrlUtils(),
			$this->getServiceContainer()->getUserNameUtils(),
			$this->getServiceContainer()->getSessionStore()
		);

		$this->setService( 'SessionManager', $manager );
		// Use PlainJsonJwtCodec mock so we don't run into JWT handling errors
		$this->setService( 'JwtCodec', new PlainJsonJwtCodec() );

		return $manager->getProvider( BotPasswordSessionProvider::class );
	}

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::EnableBotPasswords => true,
			MainConfigNames::CentralIdLookupProvider => 'local',
			MainConfigNames::GrantPermissions => [
				'test' => [ 'read' => true ],
			],
		] );
	}

	public function addDBDataOnce() {
		$passwordFactory = $this->getServiceContainer()->getPasswordFactory();
		$passwordHash = $passwordFactory->newFromPlaintext( 'foobaz' );

		$sysop = static::getTestSysop()->getUser();
		$userId = $this->getServiceContainer()
			->getCentralIdLookupFactory()
			->getLookup( 'local' )
			->centralIdFromName( $sysop->getName() );

		$dbw = $this->getDb();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'bot_passwords' )
			->where( [ 'bp_user' => $userId, 'bp_app_id' => 'BotPasswordSessionProvider' ] )
			->caller( __METHOD__ )->execute();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'bot_passwords' )
			->row( [
				'bp_user' => $userId,
				'bp_app_id' => 'BotPasswordSessionProvider',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => 'token!',
				'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
				'bp_grants' => '["test"]',
			] )
			->caller( __METHOD__ )
			->execute();
	}

	public function testConstructor() {
		$grantsInfo = $this->getServiceContainer()->getGrantsInfo();

		try {
			$provider = new BotPasswordSessionProvider( $grantsInfo );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider(
				$grantsInfo,
				[
					'priority' => SessionInfo::MIN_PRIORITY - 1
				]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider(
				$grantsInfo,
				[
					'priority' => SessionInfo::MAX_PRIORITY + 1
				]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		$provider = new BotPasswordSessionProvider(
			$grantsInfo,
			[ 'priority' => 40 ]
		);
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 40, $priv->priority );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );
		$this->assertSame( [], $priv->sessionCookieOptions );

		$provider = new BotPasswordSessionProvider(
			$grantsInfo,
			[
				'priority' => 40,
				'sessionCookieName' => null,
			]
		);
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );

		$provider = new BotPasswordSessionProvider(
			$grantsInfo,
			[
				'priority' => 40,
				'sessionCookieName' => 'Foo',
				'sessionCookieOptions' => [ 'Bar' ],
			]
		);
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 'Foo', $priv->sessionCookieName );
		$this->assertSame( [ 'Bar' ], $priv->sessionCookieOptions );
	}

	public function testBasics() {
		$provider = $this->getProvider();

		$this->assertTrue( $provider->persistsSessionId() );
		$this->assertFalse( $provider->canChangeUser() );

		$this->assertNull( $provider->newSessionInfo() );
		$this->assertNull( $provider->newSessionInfo( 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' ) );
	}

	/**
	 * Create a CentralIdLookup with mocked lookupOwnedUserNames / getScope / getProviderId methods.
	 * @return array A reference to the username => ID map.
	 */
	private function &mockCentralIdLookup(): array {
		$centralIdMap = [];
		// the class is abstract but the mocked methods aren't and that apparently breaks createNoOpAbstractMock
		$lookup = $this->createNoOpMock( CentralIdLookup::class,
			[ 'lookupOwnedUserNames', 'getScope', 'getProviderId', 'nameFromCentralId', 'centralIdFromLocalUser' ] );
		$lookup->method( 'lookupOwnedUserNames' )->willReturnCallback(
			static function ( $nameToId ) use ( &$centralIdMap ) {
				return array_intersect_key( $centralIdMap, $nameToId ) + $centralIdMap;
			}
		);
		$lookup->method( 'nameFromCentralId' )->willReturnCallback(
			static function ( $centralId ) use ( &$centralIdMap ) {
				return array_flip( $centralIdMap )[$centralId] ?? null;
			}
		);
		$lookup->method( 'centralIdFromLocalUser' )->willReturnCallback(
			static function ( UserIdentity $user ) use ( &$centralIdMap ) {
				return $centralIdMap[$user->getName()] ?? 0;
			}
		);
		$lookup->method( 'getScope' )->willReturn( 'mock:' );
		$lookup->method( 'getProviderId' )->willReturn( 'mock' );
		$this->setService( 'CentralIdLookup', $lookup );
		return $centralIdMap;
	}

	/**
	 * @dataProvider provideUseSessionCookieJwt
	 */
	public function testProvideSessionInfo( bool $useSessionCookieJwt ) {
		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$user = $this->getMutableTestUser( [], 'Expected' )->getUser();
		$otherUser = $this->getMutableTestUser( [], 'Unexpected' )->getUser();
		$centralIdMap = &$this->mockCentralIdLookup();
		$request = new FauxRequest;
		$request->setCookie( '_BPsession', $sessionId, 'wgCookiePrefix' );

		$provider = $this->getProvider( null, null, false );
		$this->assertNull( $provider->provideSessionInfo( $request ) );

		$centralIdMap = [ $user->getName() => 123, $otherUser->getName() => 456 ];
		$logger = new TestLogger( true );
		$logger2 = new TestLogger( true, static function ( string $message ) {
			if ( str_starts_with( $message, 'Session store:' ) ) {
				return null;
			}
			return $message;
		} );
		$this->setLogger( 'session-sampled', $logger2 );

		$this->config->set( MainConfigNames::EnableBotPasswords, false );
		$this->assertNull( $provider->provideSessionInfo( $request ) );
		$this->config->set( MainConfigNames::EnableBotPasswords, true );
		$this->assertNull( $provider->provideSessionInfo( new FauxRequest ) );

		$provider = $this->getProvider( null, '' );
		$this->initProvider( $provider, $logger, $this->config, $this->getServiceContainer()->getSessionManager() );
		if ( $useSessionCookieJwt ) {
			$this->config->set( MainConfigNames::UseSessionCookieJwt, true );
			$this->config->set( MainConfigNames::UseSessionCookieForBotPasswords, true );
			$startTime = 1_000_000;
			ConvertibleTimestamp::setFakeTime( $startTime );
			$jwtExpiry = $this->config->get( MainConfigNames::SessionCookieJwtExpiration );
			$codec = new PlainJsonJwtCodec();
			$defaultClaims = [
				'jti' => 'random123',
				'iss' => 'http://example.org',
				'sxp' => $startTime + $jwtExpiry,
				'sub' => 'mw:mock::123',
			];

			// User with mismatching issuer
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( [ 'iss' => 'http://evil.com' ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			// avoid printing a hundred-line diff when this assertion fails
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [ [ LogLevel::INFO, 'JWT validation failed: JWT error: wrong issuer' ] ],
				$logger->getBuffer() );
			$logger->clearBuffer();

			// Anon JWT for non-anon user
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( [ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertInstanceOf( SessionInfo::class, $info );
			$this->assertNotNull( $info?->__toString() );

			// User with valid JWT
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info?->__toString() );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertFalse( $info->getUserInfo()?->isVerified() );
			$this->assertSame( $user->getName(), $info->getUserInfo()->getName() );
			$this->assertFalse( $info->needsRefresh() );
			$this->assertFalse( $info->forceHTTPS() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// Different user with valid JWT
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( [ 'sub' => 'mw:mock::456' ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info?->__toString() );
			$this->assertSame( $otherUser->getName(), $info->getUserInfo()?->getName() );
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

			// Anon JWT
			// Should not happen, but just in case something somewhere sets one, make sure it is
			// handled gracefully.
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( [ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info?->__toString() );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertTrue( $info->getUserInfo()?->isAnon() );
			$this->assertFalse( $info->needsRefresh() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			$this->assertSame( [], $logger2->getBuffer() );
			$logger2->clearBuffer();

			// (soft-)expired JWT
			ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE + 1 );
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info?->__toString() );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertFalse( $info->getUserInfo()?->isVerified() );
			$this->assertSame( $user->getName(), $info->getUserInfo()?->getName() );
			$this->assertTrue( $info->needsRefresh() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();
			$this->assertSame( [ [ LogLevel::WARNING, 'Soft-expired JWT cookie' ] ], $logger2->getBuffer() );
			$logger2->clearBuffer();

			// near-expired JWT
			ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry - 1 );
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( $defaultClaims ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info?->__toString() );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertFalse( $info->getUserInfo()?->isVerified() );
			$this->assertSame( $user->getName(), $info->getUserInfo()?->getName() );
			$this->assertTrue( $info->needsRefresh() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// JWT with valid hard-expiry
			ConvertibleTimestamp::setFakeTime( $startTime );
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( $defaultClaims + [
						'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
					] ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNotNull( $info?->__toString() );
			$this->assertSame( $sessionId, $info->getId() );
			$this->assertFalse( $info->getUserInfo()?->isVerified() );
			$this->assertSame( $user->getName(), $info->getUserInfo()?->getName() );
			$this->assertFalse( $info->needsRefresh() );
			$this->assertFalse( $info->forceHTTPS() );
			$this->assertSame( [], $logger->getBuffer() );
			$logger->clearBuffer();

			// JWT with expired hard-expiry
			ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE + 1 );
			$request = new FauxRequest();
			$request->setCookies( [
				'_BPsession' => $sessionId,
				'sessionJwt' => $codec->create( $defaultClaims + [
						'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
					] ),
			], prefix: '' );
			$info = $provider->provideSessionInfo( $request );
			$this->assertNull( $info?->__toString() );
			$this->assertSame( [ [ LogLevel::INFO, 'JWT validation failed: The token is expired' ] ], $logger->getBuffer() );
			$logger->clearBuffer();

			$this->assertSame( [], $logger2->getBuffer() );
			$logger2->clearBuffer();
		}
	}

	/**
	 * Integration test for provideSessionInfo() + SessionManager::loadSessionInfoFromStore())
	 */
	public function testGetSessionForRequest() {
		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$user = $this->getMutableTestUser( [], 'Expected' )->getUser();
		$otherUser = $this->getMutableTestUser( [], 'Unexpected' )->getUser();
		$centralIdMap = &$this->mockCentralIdLookup();
		$centralIdMap = [ $user->getName() => 123, $otherUser->getName() => 456 ];

		$logger = new TestLogger( true );
		$logger2 = new TestLogger( true, static function ( string $message ) {
			if ( str_starts_with( $message, 'Session store:' ) ) {
				return null;
			}
			return $message;
		} );
		$this->setLogger( 'session-sampled', $logger2 );

		$store = new TestBagOStuff();
		$this->setService( 'SessionStore', new SingleBackendSessionStore(
				$store, new NullLogger(), $this->getServiceContainer()->getStatsFactory() )
		);

		$bp = BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => 'bot',
		] );
		$status = $bp->save( 'insert' );
		$this->assertStatusGood( $status );

		$provider = $this->getProvider( null, '', true );
		$this->initProvider( $provider, $logger, $this->config, $this->getServiceContainer()->getSessionManager() );
		$this->config->set( MainConfigNames::EnableBotPasswords, true );
		$this->config->set( MainConfigNames::UseSessionCookieJwt, true );
		$this->config->set( MainConfigNames::UseSessionCookieForBotPasswords, true );
		$this->config->set( MainConfigNames::ForceHTTPS, false );

		$setUserInStore = static function ( $sessionId, User $user, $bp ) use ( $store, $provider ) {
			$store->setUser( $sessionId, $user, [
				'metadata' => [
					'provider' => (string)$provider,
					'providerMetadata' => [
						'centralId' => $bp->getUserCentralId(),
						'appId' => $bp->getAppId(),
						'token' => $bp->getToken(),
					],
				],
			] );
		};

		$startTime = 1_000_000;
		ConvertibleTimestamp::setFakeTime( $startTime );
		$jwtExpiry = $this->config->get( MainConfigNames::SessionCookieJwtExpiration );
		$codec = new PlainJsonJwtCodec();
		$defaultClaims = [
			'jti' => 'random123',
			'iss' => 'http://example.org',
			'sxp' => $startTime + $jwtExpiry,
			'sub' => 'mw:mock::123',
		];

		// No stored data
		str_increment( $sessionId );
		$store->clear();
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$request->setCookies( [
			'_BPsession' => $sessionId,
			'sessionJwt' => $codec->create( $defaultClaims ),
		], prefix: '' );
		RequestContext::getMain()->setRequest( $request );
		$manager = $this->getServiceContainer()->getSessionManager();
		$session = $manager->getSessionForRequest( $request );
		$this->assertNotSame( $sessionId, $session->getId() );
		$this->assertSame( '1.2.3.4', $session->getUser()->getName() );
		$this->assertFalse( $session->isPersistent() );
		$this->assertNotInstanceOf( BotPasswordSessionProvider::class, $session->getProvider() );
		$this->assertSame( [], $logger->getBuffer() );

		// Stored data matches
		str_increment( $sessionId );
		$store->clear();
		$setUserInStore( $sessionId, $user, $bp );
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$request->setCookies( [
			'_BPsession' => $sessionId,
			'sessionJwt' => $codec->create( $defaultClaims ),
		], prefix: '' );
		RequestContext::getMain()->setRequest( $request );
		$manager = $this->getServiceContainer()->getSessionManager();
		$session = $manager->getSessionForRequest( $request );
		$this->assertSame( $sessionId, $session->getId() );
		$this->assertSame( $user->getName(), $session->getUser()->getName() );
		$this->assertTrue( $session->isPersistent() );
		$this->assertInstanceOf( BotPasswordSessionProvider::class, $session->getProvider() );
		$this->assertSame( [], $logger->getBuffer() );

		// Stored data mismatches
		str_increment( $sessionId );
		$store->clear();
		$setUserInStore( $sessionId, $user, $bp );
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$request->setCookies( [
			'_BPsession' => $sessionId,
			'sessionJwt' => $codec->create( [ 'sub' => 'mw:mock::456' ] + $defaultClaims ),
		], prefix: '' );
		RequestContext::getMain()->setRequest( $request );
		$manager = $this->getServiceContainer()->getSessionManager();
		$session = $manager->getSessionForRequest( $request );
		$this->assertNotSame( $sessionId, $session->getId() );
		$this->assertSame( '1.2.3.4', $session->getUser()->getName() );
		$this->assertFalse( $session->isPersistent() );
		$this->assertNotInstanceOf( BotPasswordSessionProvider::class, $session->getProvider() );
		$this->assertSame( [], $logger->getBuffer() );

		// no JWT
		str_increment( $sessionId );
		$store->clear();
		$setUserInStore( $sessionId, $user, $bp );
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$request->setCookies( [
			'_BPsession' => $sessionId,
		], prefix: '' );
		RequestContext::getMain()->setRequest( $request );
		$manager = $this->getServiceContainer()->getSessionManager();
		$session = $manager->getSessionForRequest( $request );
		$this->assertSame( $sessionId, $session->getId() );
		$this->assertSame( $user->getName(), $session->getUser()->getName() );
		$this->assertTrue( $session->isPersistent() );
		$this->assertInstanceOf( BotPasswordSessionProvider::class, $session->getProvider() );
		$this->assertSame( [], $logger->getBuffer() );

		// Stored data matches but user is anon
		str_increment( $sessionId );
		$store->clear();
		$setUserInStore( $sessionId, new User(), $bp );
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$request->setCookies( [
			'_BPsession' => $sessionId,
			'sessionJwt' => $codec->create( [ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims ),
		], prefix: '' );
		RequestContext::getMain()->setRequest( $request );
		$manager = $this->getServiceContainer()->getSessionManager();
		$session = $manager->getSessionForRequest( $request );
		$this->assertNotSame( $sessionId, $session->getId() );
		$this->assertSame( '1.2.3.4', $session->getUser()->getName() );
		$this->assertFalse( $session->isPersistent() );
		$this->assertNotInstanceOf( BotPasswordSessionProvider::class, $session->getProvider() );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Bot password sessions cannot be anonymous' ]
		], $logger->getBuffer() );
		$logger->clearBuffer();
	}

	public function testNewSessionInfoForRequest() {
		$provider = $this->getProvider();
		$user = static::getTestSysop()->getUser();
		$request = new FauxRequest();
		$request->setIP( '127.0.0.1' );
		$bp = BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$session = $provider->newSessionForRequest( $user, $bp, $request );
		$this->assertInstanceOf( Session::class, $session );

		$this->assertEquals( $session->getId(), $request->getSession()->getId() );
		$this->assertEquals( $user->getName(), $session->getUser()->getName() );

		$this->assertEquals( [
			'centralId' => $bp->getUserCentralId(),
			'appId' => $bp->getAppId(),
			'token' => $bp->getToken(),
			'rights' => [ 'read' ],
			'restrictions' => $bp->getRestrictions()->toJson(),
		], $session->getProviderMetadata() );

		$this->assertEquals( [ 'read' ], $session->getAllowedUserRights() );
	}

	public function testCheckSessionInfo() {
		$logger = new TestLogger( true );
		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger, $this->config );

		$user = static::getTestSysop()->getUser();
		$request = new FauxRequest();
		$request->setIP( '127.0.0.1' );
		$bp = BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$data = [
			'provider' => $provider,
			'id' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'userInfo' => UserInfo::newFromUser( $user, true ),
			'persisted' => false,
			'metadata' => [
				'centralId' => $bp->getUserCentralId(),
				'appId' => $bp->getAppId(),
				'token' => $bp->getToken(),
			],
		];
		$dataMD = $data['metadata'];

		foreach ( $data['metadata'] as $key => $_ ) {
			$data['metadata'] = $dataMD;
			unset( $data['metadata'][$key] );
			$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
			$metadata = $info->getProviderMetadata();

			$this->assertFalse( $provider->refreshSessionInfo( $info, $request, $metadata ) );
			$this->assertSame( [
				[ LogLevel::INFO, 'Session "{session}": Missing metadata: {missing}' ]
			], $logger->getBuffer() );
			$logger->clearBuffer();
		}

		$data['metadata'] = $dataMD;
		$data['metadata']['appId'] = 'Foobar';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->refreshSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": No BotPassword for {centralId} {appId}' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$data['metadata'] = $dataMD;
		$data['metadata']['token'] = 'Foobar';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->refreshSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": BotPassword token check failed' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$request2 = new FauxRequest();
		$request2->setIP( '10.0.0.1' );
		$data['metadata'] = $dataMD;
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->refreshSessionInfo( $info, $request2, $metadata ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": Restrictions check failed' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertTrue( $provider->refreshSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( [], $logger->getBuffer() );
		$this->assertEquals( $dataMD + [ 'rights' => [ 'read' ] ], $metadata );
	}

	public function testGetAllowedUserRights() {
		$logger = new TestLogger( true );
		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger );

		$backend = TestUtils::getDummySessionBackend();
		$backendPriv = TestingAccessWrapper::newFromObject( $backend );

		try {
			$provider->getAllowedUserRights( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Backend\'s provider isn\'t $this', $ex->getMessage() );
		}

		$backendPriv->provider = $provider;
		$backendPriv->providerMetadata = [ 'rights' => [ 'foo', 'bar', 'baz' ] ];
		$this->assertSame( [ 'foo', 'bar', 'baz' ], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [], $logger->getBuffer() );

		$backendPriv->providerMetadata = [ 'foo' => 'bar' ];
		$this->assertSame( [], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'MediaWiki\\Session\\BotPasswordSessionProvider::getAllowedUserRights: ' .
					'No provider metadata, returning no rights allowed'
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$backendPriv->providerMetadata = [ 'rights' => 'bar' ];
		$this->assertSame( [], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'MediaWiki\\Session\\BotPasswordSessionProvider::getAllowedUserRights: ' .
					'No provider metadata, returning no rights allowed'
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$backendPriv->providerMetadata = null;
		$this->assertSame( [], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'MediaWiki\\Session\\BotPasswordSessionProvider::getAllowedUserRights: ' .
					'No provider metadata, returning no rights allowed'
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();
	}

	/**
	 * @dataProvider provideUseSessionCookieJwt
	 */
	public function testGetVaryCookies( bool $useSessionCookieJwt ) {
		$logger = new TestLogger( true );
		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger, $this->config );

		$this->config->set( MainConfigNames::UseSessionCookieJwt, $useSessionCookieJwt );
		$this->config->set( MainConfigNames::UseSessionCookieForBotPasswords, true );
		$this->initProvider( $provider, null, $this->config );

		$expectedCookies = [
			'wgCookiePrefix_BPsession',
		];
		if ( $useSessionCookieJwt ) {
			$expectedCookies[] = 'sessionJwt';
		}
		$this->assertArrayEquals( $expectedCookies, $provider->getVaryCookies() );
	}

	public static function providePersistSession() {
		return [
			'default' => [ false ],
			'force HTTPS' => [ true ],
		];
	}

	/**
	 * @dataProvider providePersistSession
	 */
	public function testPersistSession( $forceHTTPS ) {
		$startTime = 1_000_000;
		ConvertibleTimestamp::setFakeTime( $startTime );

		$provider = $this->getProvider();
		$hookContainer = $this->createHookContainer();
		$config = $this->config;
		$config->set( MainConfigNames::ForceHTTPS, $forceHTTPS );
		$config->set( MainConfigNames::UseSessionCookieJwt, true );
		$config->set( MainConfigNames::UseSessionCookieForBotPasswords, true );
		$config->set( MainConfigNames::JwtSessionCookieIssuer, 'http://example.org' );
		$this->initProvider(
			$provider,
			new TestLogger(),
			$config,
			$this->getServiceContainer()->getSessionManager(),
			$hookContainer
		);

		$user = $this->getTestSysop()->getUser();
		$userInfo = UserInfo::newFromUser( $user, true );

		$this->overrideConfigValue( MainConfigNames::ForceHTTPS, $forceHTTPS );
		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, [
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'metaDirty' => true,
				'idIsSafe' => true,
				'userInfo' => $userInfo,
			] ),
			$this->getServiceContainer()->getSessionStore(),
			new NullLogger(),
			$hookContainer,
			10
		);
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;

		// Logged-in user, no remember
		$backend->setRememberUser( false );
		$backend->setForceHTTPS( false );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertNotEmpty( $request->response()->getCookie( 'sessionJwt' ) );
		$this->assertSame( [], $backend->getData() );

		// Logged-in user, remember
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( true );
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertNotEmpty( $request->response()->getCookie( 'sessionJwt' ) );
		$this->assertSame( [], $backend->getData() );

		// Multiple persists should not result in duplicated Set-Cookie headers
		$cookies = [];
		WebResponse::resetCookieCache();
		$expectedCookies[] = 'sessionJwt';
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
}
