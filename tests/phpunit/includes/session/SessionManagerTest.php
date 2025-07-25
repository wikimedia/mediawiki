<?php

namespace MediaWiki\Tests\Session;

use DummySessionProvider;
use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\ProxyLookup;
use MediaWiki\Session\CookieSessionProvider;
use MediaWiki\Session\MetadataMergeException;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SessionOverflowException;
use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\UserInfo;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use ReflectionClass;
use stdClass;
use TestLogger;
use UnexpectedValueException;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\SessionManager
 */
class SessionManagerTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	private HashConfig $config;
	private TestLogger $logger;
	private TestLogger $sampledLogger;
	private TestBagOStuff $store;

	protected function getManager() {
		$this->store = new TestBagOStuff();
		$cacheType = $this->setMainCache( $this->store );

		$this->config = new HashConfig( [
			MainConfigNames::LanguageCode => 'en',
			MainConfigNames::SessionCacheType => $cacheType,
			MainConfigNames::ObjectCacheSessionExpiry => 100,
			MainConfigNames::SessionProviders => [
				[ 'class' => DummySessionProvider::class ],
			]
		] );
		$this->logger = new TestLogger( false, static function ( $m ) {
			return ( str_starts_with( $m, 'SessionBackend ' )
				|| str_starts_with( $m, 'SessionManager using store ' )
				// These were added for T264793 and behave somewhat erratically, not worth testing
				|| str_starts_with( $m, 'Failed to load session, unpersisting' )
				|| preg_match( '/^(Persisting|Unpersisting) session (for|due to)/', $m )
			) ? null : $m;
		} );
		$this->sampledLogger = new TestLogger( true );
		$this->setLogger( 'session-sampled', $this->sampledLogger );

		return new SessionManager(
			$this->config,
			$this->logger,
			$this->store,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUserNameUtils()
		);
	}

	protected function objectCacheDef( $object ) {
		return [ 'factory' => static function () use ( $object ) {
			return $object;
		} ];
	}

	public function testGetGlobalSession() {
		$context = RequestContext::getMain();

		$this->setService( 'SessionManager', $this->getManager() );
		PHPSessionHandler::install( SessionManager::singleton() );
		$staticAccess = TestingAccessWrapper::newFromClass( PHPSessionHandler::class );
		$handler = TestingAccessWrapper::newFromObject( $staticAccess->instance );

		$oldEnable = $handler->enable;
		$reset[] = new ScopedCallback( static function () use ( $handler, $oldEnable ) {
			if ( $handler->enable ) {
				session_write_close();
			}
			$handler->enable = $oldEnable;
		} );

		$handler->enable = true;
		$request = new FauxRequest();
		$context->setRequest( $request );
		$id = $request->getSession()->getId();

		session_write_close();
		session_id( '' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( $id, $session->getId() );

		session_id( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', $session->getId() );
		$this->assertSame( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', $request->getSession()->getId() );

		session_write_close();
		$handler->enable = false;
		$request = new FauxRequest();
		$context->setRequest( $request );
		$id = $request->getSession()->getId();

		session_id( '' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( $id, $session->getId() );

		session_id( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( $id, $session->getId() );
		$this->assertSame( $id, $request->getSession()->getId() );
	}

	public function testConstructor() {
		$manager = TestingAccessWrapper::newFromObject( $this->getManager() );
		$this->assertSame( $this->config, $manager->config );
		$this->assertSame( $this->logger, $manager->logger );
		$this->assertSame( $this->store, $manager->store );

		$manager = TestingAccessWrapper::newFromObject( $this->getServiceContainer()->getSessionManager() );
		$this->assertSame( $this->getServiceContainer()->getMainConfig(), $manager->config );

		$manager = TestingAccessWrapper::newFromObject( new SessionManager(
			$this->config,
			$this->logger,
			$this->store,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUserNameUtils()
		) );
		$this->assertSame( $this->store, $manager->store );
	}

	public function testGetSessionForRequest() {
		$manager = $this->getManager();
		$request = new FauxRequest();
		$requestUnpersist1 = false;
		$requestUnpersist2 = false;
		$requestInfo1 = null;
		$requestInfo2 = null;

		$id1 = '';
		$id2 = '';
		$idEmpty = 'empty-session-------------------';

		$providerBuilder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods(
				[ 'provideSessionInfo', 'newSessionInfo', '__toString', 'describe', 'unpersistSession' ]
			);

		$provider1 = $providerBuilder->getMock();
		$provider1->method( 'provideSessionInfo' )
			->with( $this->identicalTo( $request ) )
			->willReturnCallback( static function ( $request ) use ( &$requestInfo1 ) {
				return $requestInfo1;
			} );
		$provider1->method( 'newSessionInfo' )
			->willReturnCallback( static function () use ( $idEmpty, $provider1 ) {
				return new SessionInfo( SessionInfo::MIN_PRIORITY, [
					'provider' => $provider1,
					'id' => $idEmpty,
					'persisted' => true,
					'idIsSafe' => true,
				] );
			} );
		$provider1->method( '__toString' )
			->willReturn( 'Provider1' );
		$provider1->method( 'describe' )
			->willReturn( '#1 sessions' );
		$provider1->method( 'unpersistSession' )
			->willReturnCallback( static function ( $request ) use ( &$requestUnpersist1 ) {
				$requestUnpersist1 = true;
			} );

		$provider2 = $providerBuilder->getMock();
		$provider2->method( 'provideSessionInfo' )
			->with( $this->identicalTo( $request ) )
			->willReturnCallback( static function ( $request ) use ( &$requestInfo2 ) {
				return $requestInfo2;
			} );
		$provider2->method( '__toString' )
			->willReturn( 'Provider2' );
		$provider2->method( 'describe' )
			->willReturn( '#2 sessions' );
		$provider2->method( 'unpersistSession' )
			->willReturnCallback( static function ( $request ) use ( &$requestUnpersist2 ) {
				$requestUnpersist2 = true;
			} );

		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		// No provider returns info
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $idEmpty, $session->getId() );
		$this->assertFalse( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );

		// Both providers return info, picks best one
		$requestInfo1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$requestInfo2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id2, $session->getId() );
		$this->assertFalse( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );

		$requestInfo1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$requestInfo2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id1, $session->getId() );
		$this->assertFalse( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );

		// Tied priorities
		$requestInfo1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
			'idIsSafe' => true,
		] );
		$requestInfo2 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
			'idIsSafe' => true,
		] );
		try {
			$manager->getSessionForRequest( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( SessionOverflowException $ex ) {
			$this->assertStringStartsWith(
				'Multiple sessions for this request tied for top priority: ',
				$ex->getMessage()
			);
			$this->assertCount( 2, $ex->getSessionInfos() );
			$this->assertContains( $requestInfo1, $ex->getSessionInfos() );
			$this->assertContains( $requestInfo2, $ex->getSessionInfos() );
		}
		$this->assertFalse( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );

		// Bad provider
		$requestInfo1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$requestInfo2 = null;
		try {
			$manager->getSessionForRequest( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Provider1 returned session info for a different provider: ' . $requestInfo1,
				$ex->getMessage()
			);
		}
		$this->assertFalse( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );

		// Unusable session info
		$this->logger->setCollect( true );
		$requestInfo1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'TestGetSessionForRequest', false ),
			'idIsSafe' => true,
		] );
		$requestInfo2 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id2, $session->getId() );
		$this->logger->setCollect( false );
		$this->assertTrue( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );
		$requestUnpersist1 = false;

		$this->logger->setCollect( true );
		$requestInfo1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$requestInfo2 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'TestGetSessionForRequest', false ),
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id1, $session->getId() );
		$this->logger->setCollect( false );
		$this->assertFalse( $requestUnpersist1 );
		$this->assertTrue( $requestUnpersist2 );
		$requestUnpersist2 = false;

		// Unpersisted session ID
		$requestInfo1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => false,
			'userInfo' => UserInfo::newFromName( 'TestGetSessionForRequest', true ),
			'idIsSafe' => true,
		] );
		$requestInfo2 = null;
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id1, $session->getId() );
		$this->assertFalse( $requestUnpersist1 );
		$this->assertFalse( $requestUnpersist2 );
		$session->persist();
		$this->assertTrue( $session->isPersistent() );
	}

	public function testGetSessionById() {
		$manager = $this->getManager();
		try {
			$manager->getSessionById( 'bad' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage() );
		}

		// Unknown session ID
		$id = $manager->generateSessionId();
		$session = $manager->getSessionById( $id, true );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id, $session->getId() );

		$id = $manager->generateSessionId();
		$this->assertNull( $manager->getSessionById( $id, false ) );

		$userIdentity = $this->getTestSysop()->getUserIdentity();
		// Known but unloadable session ID
		$this->logger->setCollect( true );
		$id = $manager->generateSessionId();
		$this->store->setSession( $id, [ 'metadata' => [
			'userId' => $userIdentity->getId(),
			'userToken' => 'bad',
		] ] );

		$this->assertNull( $manager->getSessionById( $id, true ) );
		$this->assertNull( $manager->getSessionById( $id, false ) );
		$this->logger->setCollect( false );

		// Known session ID
		$this->store->setSession( $id, [] );
		$session = $manager->getSessionById( $id, false );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id, $session->getId() );

		// Store isn't checked if the session is already loaded
		// Save the session before overriding the stored data, to make sure the dirty flags
		// are false and later internal save() calls are noops. Otherwise the fixture would get messed up.
		$session->save();
		$this->store->setSession( $id, [ 'metadata' => [
			'userId' => $userIdentity->getId(),
			'userToken' => 'bad',
		] ] );
		$session2 = $manager->getSessionById( $id, false );
		$this->assertInstanceOf( Session::class, $session2 );
		$this->assertSame( $id, $session2->getId() );
		// Unset all Session objects, which will deregister the session backend and trigger a load next time
		unset( $session, $session2 );
		$this->logger->setCollect( true );
		$this->assertNull( $manager->getSessionById( $id, true ) );
		$this->logger->setCollect( false );

		// Failure to create an empty session
		$manager = $this->getManager();
		$provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'provideSessionInfo', 'newSessionInfo', '__toString' ] )
			->getMock();
		$provider->method( 'provideSessionInfo' )
			->willReturn( null );
		$provider->method( 'newSessionInfo' )
			->willReturn( null );
		$provider->method( '__toString' )
			->willReturn( 'MockProvider' );
		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider ),
		] );
		$this->logger->setCollect( true );
		$this->assertNull( $manager->getSessionById( $id, true ) );
		$this->logger->setCollect( false );
		$this->assertSame( [
			[ LogLevel::ERROR, 'Failed to create empty session: {exception}' ]
		], $this->logger->getBuffer() );
	}

	public function testGetEmptySession() {
		$manager = $this->getManager();
		$pmanager = TestingAccessWrapper::newFromObject( $manager );

		$providerBuilder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'provideSessionInfo', 'newSessionInfo', '__toString' ] );

		$expectId = null;
		$info1 = null;
		$info2 = null;

		$provider1 = $providerBuilder->getMock();
		$provider1->method( 'provideSessionInfo' )
			->willReturn( null );
		$provider1->method( 'newSessionInfo' )
			->with( $this->callback( static function ( $id ) use ( &$expectId ) {
				return $id === $expectId;
			} ) )
			->willReturnCallback( static function () use ( &$info1 ) {
				return $info1;
			} );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider1' );

		$provider2 = $providerBuilder->getMock();
		$provider2->method( 'provideSessionInfo' )
			->willReturn( null );
		$provider2->method( 'newSessionInfo' )
			->with( $this->callback( static function ( $id ) use ( &$expectId ) {
				return $id === $expectId;
			} ) )
			->willReturnCallback( static function () use ( &$info2 ) {
				return $info2;
			} );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider2' );

		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		// No info
		$expectId = null;
		$info1 = null;
		$info2 = null;
		try {
			$manager->getEmptySession();
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'No provider could provide an empty session!',
				$ex->getMessage()
			);
		}

		// Info
		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider1,
			'id' => 'empty---------------------------',
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$info2 = null;
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( 'empty---------------------------', $session->getId() );

		// Info, explicitly
		$expectId = 'expected------------------------';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider1,
			'id' => $expectId,
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$info2 = null;
		$session = $pmanager->getEmptySessionInternal( null, $expectId );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $expectId, $session->getId() );

		// Wrong ID
		$expectId = 'expected-----------------------2';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider1,
			'id' => "un$expectId",
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$info2 = null;
		try {
			$pmanager->getEmptySessionInternal( null, $expectId );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'MockProvider1 returned empty session info with a wrong id: ' .
					"un$expectId != $expectId",
				$ex->getMessage()
			);
		}

		// Unsafe ID
		$expectId = 'expected-----------------------2';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider1,
			'id' => $expectId,
			'persisted' => true,
		] );
		$info2 = null;
		try {
			$pmanager->getEmptySessionInternal( null, $expectId );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'MockProvider1 returned empty session info with id flagged unsafe',
				$ex->getMessage()
			);
		}

		// Wrong provider
		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => 'empty---------------------------',
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$info2 = null;
		try {
			$manager->getEmptySession();
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'MockProvider1 returned an empty session info for a different provider: ' . $info1,
				$ex->getMessage()
			);
		}

		// Highest priority wins
		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [
			'provider' => $provider1,
			'id' => 'empty1--------------------------',
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => 'empty2--------------------------',
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( 'empty1--------------------------', $session->getId() );

		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [
			'provider' => $provider1,
			'id' => 'empty1--------------------------',
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, [
			'provider' => $provider2,
			'id' => 'empty2--------------------------',
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( 'empty2--------------------------', $session->getId() );

		// Tied priorities throw an exception
		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider1,
			'id' => 'empty1--------------------------',
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
			'idIsSafe' => true,
		] );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => 'empty2--------------------------',
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
			'idIsSafe' => true,
		] );
		try {
			$manager->getEmptySession();
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertStringStartsWith(
				'Multiple empty sessions tied for top priority: ',
				$ex->getMessage()
			);
		}

		// Bad id
		try {
			$pmanager->getEmptySessionInternal( null, 'bad' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage() );
		}

		// Session already exists
		$expectId = 'expected-----------------------3';
		$this->store->setSessionMeta( $expectId, [
			'provider' => 'MockProvider2',
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
		] );
		try {
			$pmanager->getEmptySessionInternal( null, $expectId );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Session ID already exists', $ex->getMessage() );
		}
	}

	public function testInvalidateSessionsForUser() {
		$user = $this->getTestSysop()->getUser();
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'invalidateSessionsForUser', '__toString' ] );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'invalidateSessionsForUser' )
			->with( $this->identicalTo( $user ) );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider1' );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->once() )->method( 'invalidateSessionsForUser' )
			->with( $this->identicalTo( $user ) );
		$provider2->method( '__toString' )
			->willReturn( 'MockProvider2' );

		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		$oldToken = $user->getToken( true );
		$manager->invalidateSessionsForUser( $user );
		$this->assertNotEquals( $oldToken, $user->getToken() );
	}

	public function testGetVaryHeaders() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'getVaryHeaders', '__toString' ] );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'getVaryHeaders' )
			->willReturn( [
				'Foo' => null,
				'Bar' => [ 'X', 'Bar1' ],
				'Quux' => null,
			] );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider1' );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->once() )->method( 'getVaryHeaders' )
			->willReturn( [
				'Baz' => null,
				'Bar' => [ 'X', 'Bar2' ],
				'Quux' => [ 'Quux' ],
			] );
		$provider2->method( '__toString' )
			->willReturn( 'MockProvider2' );

		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		$expect = [
			'Foo' => null,
			'Bar' => null,
			'Quux' => null,
			'Baz' => null,
		];

		$this->assertEquals( $expect, $manager->getVaryHeaders() );

		// Again, to ensure it's cached
		$this->assertEquals( $expect, $manager->getVaryHeaders() );
	}

	public function testGetVaryCookies() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'getVaryCookies', '__toString' ] );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'getVaryCookies' )
			->willReturn( [ 'Foo', 'Bar' ] );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider1' );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->once() )->method( 'getVaryCookies' )
			->willReturn( [ 'Foo', 'Baz' ] );
		$provider2->method( '__toString' )
			->willReturn( 'MockProvider2' );

		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		$expect = [ 'Foo', 'Bar', 'Baz' ];

		$this->assertEquals( $expect, $manager->getVaryCookies() );

		// Again, to ensure it's cached
		$this->assertEquals( $expect, $manager->getVaryCookies() );
	}

	public function testGetProviders() {
		$realManager = $this->getManager();
		$manager = TestingAccessWrapper::newFromObject( $realManager );

		$this->config->set( MainConfigNames::SessionProviders, [
			[ 'class' => DummySessionProvider::class ],
		] );
		$providers = $manager->getProviders();
		$this->assertArrayHasKey( 'DummySessionProvider', $providers );
		$provider = TestingAccessWrapper::newFromObject( $providers['DummySessionProvider'] );
		$this->assertSame( $manager->logger, $provider->logger );
		$this->assertSame( $manager->config, $provider->getConfig() );
		$this->assertSame( $realManager, $provider->getManager() );

		$this->config->set( MainConfigNames::SessionProviders, [
			[ 'class' => DummySessionProvider::class ],
			[ 'class' => DummySessionProvider::class ],
		] );
		$manager->sessionProviders = null;
		try {
			$manager->getProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Duplicate provider name "DummySessionProvider"',
				$ex->getMessage()
			);
		}
	}

	public function testShutdown() {
		$manager = TestingAccessWrapper::newFromObject( $this->getManager() );
		$manager->setLogger( new NullLogger() );

		$mock = $this->getMockBuilder( stdClass::class )
			->addMethods( [ 'shutdown' ] )->getMock();
		$mock->expects( $this->once() )->method( 'shutdown' );

		$manager->allSessionBackends = [ $mock ];
		$manager->shutdown();
	}

	public function testGetSessionFromInfo() {
		$manager = TestingAccessWrapper::newFromObject( $this->getManager() );
		$request = new FauxRequest();

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $manager->getProvider( 'DummySessionProvider' ),
			'id' => $id,
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'TestGetSessionFromInfo', true ),
			'idIsSafe' => true,
		] );
		TestingAccessWrapper::newFromObject( $info )->idIsSafe = true;
		$session1 = TestingAccessWrapper::newFromObject(
			$manager->getSessionFromInfo( $info, $request )
		);
		$session2 = TestingAccessWrapper::newFromObject(
			$manager->getSessionFromInfo( $info, $request )
		);

		$this->assertSame( $session1->backend, $session2->backend );
		$this->assertNotEquals( $session1->index, $session2->index );
		$this->assertSame( $session1->getSessionId(), $session2->getSessionId() );
		$this->assertSame( $id, $session1->getId() );

		TestingAccessWrapper::newFromObject( $info )->idIsSafe = false;
		$session3 = $manager->getSessionFromInfo( $info, $request );
		$this->assertNotSame( $id, $session3->getId() );
	}

	public function testBackendRegistration() {
		$manager = $this->getManager();

		$session = $manager->getSessionForRequest( new FauxRequest );
		$backend = TestingAccessWrapper::newFromObject( $session )->backend;
		$sessionId = $session->getSessionId();
		$id = (string)$sessionId;

		$this->assertSame( $sessionId, $manager->getSessionById( $id, true )->getSessionId() );

		$manager->changeBackendId( $backend );
		$this->assertSame( $sessionId, $session->getSessionId() );
		$this->assertNotEquals( $id, (string)$sessionId );
		$id = (string)$sessionId;

		$this->assertSame( $sessionId, $manager->getSessionById( $id, true )->getSessionId() );

		// Destruction of the session here causes the backend to be deregistered
		$session = null;

		try {
			$manager->changeBackendId( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Backend was not registered with this SessionManager', $ex->getMessage()
			);
		}

		try {
			$manager->deregisterSessionBackend( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Backend was not registered with this SessionManager', $ex->getMessage()
			);
		}

		$session = $manager->getSessionById( $id, true );
		$this->assertSame( $sessionId, $session->getSessionId() );
	}

	public function testGenerateSessionId() {
		$manager = $this->getManager();

		$id = $manager->generateSessionId();
		$this->assertTrue( SessionManager::validateSessionId( $id ), "Generated ID: $id" );
	}

	public function testPreventSessionsForUser() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'preventSessionsForUser', '__toString' ] );

		$username = 'TestPreventSessionsForUser';
		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'preventSessionsForUser' )
			->with( $username );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider1' );

		$this->config->set( MainConfigNames::SessionProviders, [
			$this->objectCacheDef( $provider1 ),
		] );

		$this->assertFalse( $manager->isUserSessionPrevented( $username ) );
		$manager->preventSessionsForUser( $username );
		$this->assertTrue( $manager->isUserSessionPrevented( $username ) );
	}

	public function testLoadSessionInfoFromStore() {
		$manager = $this->getManager();
		$logger = new TestLogger( true );
		$manager->setLogger( $logger );
		$request = new FauxRequest();

		// TestingAccessWrapper can't handle methods with reference arguments, sigh.
		$rClass = new ReflectionClass( $manager );
		$rMethod = $rClass->getMethod( 'loadSessionInfoFromStore' );
		$rMethod->setAccessible( true );
		$loadSessionInfoFromStore = static function ( &$info ) use ( $rMethod, $manager, $request ) {
			return $rMethod->invokeArgs( $manager, [ &$info, $request ] );
		};

		$username = $this->getTestSysop()->getUserIdentity()->getName();
		$userInfo = UserInfo::newFromName( $username, true );
		$unverifiedUserInfo = UserInfo::newFromName( $username, false );

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$metadata = [
			'userId' => $userInfo->getId(),
			'userName' => $userInfo->getName(),
			'userToken' => $userInfo->getToken( true ),
			'provider' => 'Mock',
		];

		$builder = $this->getMockBuilder( SessionProvider::class )
			->onlyMethods( [ '__toString', 'mergeMetadata', 'refreshSessionInfo' ] );

		$provider = $builder->getMockForAbstractClass();
		$this->initProvider( $provider, null, null, $manager );
		$provider->method( 'persistsSessionId' )
			->willReturn( true );
		$provider->method( 'canChangeUser' )
			->willReturn( true );
		$provider->method( 'refreshSessionInfo' )
			->willReturn( true );
		$provider->method( '__toString' )
			->willReturn( 'Mock' );
		$provider->method( 'mergeMetadata' )
			->willReturnCallback( static function ( $a, $b ) {
				if ( $b === [ 'Throw' ] ) {
					throw new MetadataMergeException( 'no merge!' );
				}
				return [ 'Merged' ];
			} );

		$provider2 = $builder->getMockForAbstractClass();
		$this->initProvider( $provider2, null, null, $manager );
		$provider2->method( 'persistsSessionId' )
			->willReturn( false );
		$provider2->method( 'canChangeUser' )
			->willReturn( false );
		$provider2->method( '__toString' )
			->willReturn( 'Mock2' );
		$provider2->method( 'refreshSessionInfo' )
			->willReturnCallback( static function ( $info, $request, &$metadata ) {
				$metadata['changed'] = true;
				return true;
			} );

		$provider3 = $builder->getMockForAbstractClass();
		$this->initProvider( $provider3, null, null, $manager );
		$provider3->method( 'persistsSessionId' )
			->willReturn( true );
		$provider3->method( 'canChangeUser' )
			->willReturn( true );
		$provider3->expects( $this->once() )->method( 'refreshSessionInfo' )
			->willReturn( false );
		$provider3->method( '__toString' )
			->willReturn( 'Mock3' );

		TestingAccessWrapper::newFromObject( $manager )->sessionProviders = [
			(string)$provider => $provider,
			(string)$provider2 => $provider2,
			(string)$provider3 => $provider3,
		];

		// No metadata, basic usage
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'userInfo' => $userInfo
		] );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Unverified user, no metadata
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $unverifiedUserInfo
		] );
		$this->assertSame( $unverifiedUserInfo, $info->getUserInfo() );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[
				LogLevel::INFO,
				'Session "{session}": Unverified user provided and no metadata to auth it',
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// No metadata, missing data
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Null provider and no metadata' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertInstanceOf( UserInfo::class, $info->getUserInfo() );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertTrue( $info->getUserInfo()->isAnon() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => $id,
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": No user provided and provider cannot set user' ]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Incomplete/bad metadata
		$this->store->setRawSession( $id, true );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		$this->store->setRawSession( $id, [ 'data' => [] ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		$this->store->deleteSession( $id );
		$this->store->setRawSession( $id, [ 'metadata' => $metadata ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		$this->store->setRawSession( $id, [ 'metadata' => $metadata, 'data' => true ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		$this->store->setRawSession( $id, [ 'metadata' => true, 'data' => [] ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		foreach ( $metadata as $key => $dummy ) {
			$tmp = $metadata;
			unset( $tmp[$key] );
			$this->store->setRawSession( $id, [ 'metadata' => $tmp, 'data' => [] ] );
			$this->assertFalse( $loadSessionInfoFromStore( $info ) );
			$this->assertSame( [
				[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
			], $this->sampledLogger->getBuffer() );
			$logger->clearBuffer();
			$this->sampledLogger->clearBuffer();
		}

		// Basic usage with metadata
		$this->store->setRawSession( $id, [ 'metadata' => $metadata, 'data' => [] ] );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Mismatched provider
		$this->store->setSessionMeta( $id, [ 'provider' => 'Bad' ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Wrong provider Bad !== Mock' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Unknown provider
		$this->store->setSessionMeta( $id, [ 'provider' => 'Bad' ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		// Fill in provider
		$this->store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Bad user metadata
		$this->store->setSessionMeta( $id, [ 'userId' => -1, 'userToken' => null ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::ERROR, 'Session "{session}": {exception}' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->store->setSessionMeta(
			$id, [ 'userId' => 0, 'userName' => '<X>', 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::ERROR, 'Session "{session}": {exception}', ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Mismatched user by ID
		$this->store->setSessionMeta(
			$id, [ 'userId' => $userInfo->getId() + 1, 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": User ID mismatch, {uid_a} !== {uid_b}' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Mismatched user by name
		$this->store->setSessionMeta(
			$id, [ 'userId' => 0, 'userName' => 'X', 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": User name mismatch, {uname_a} !== {uname_b}' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// ID matches, name doesn't
		$this->store->setSessionMeta(
			$id, [ 'userId' => $userInfo->getId(), 'userName' => 'X', 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[
				LogLevel::WARNING,
				'Session "{session}": User ID matched but name didn\'t (rename?), {uname_a} !== {uname_b}'
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Mismatched anon user
		$this->store->setSessionMeta(
			$id, [ 'userId' => 0, 'userName' => null, 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[
				LogLevel::WARNING,
				'Session "{session}": the session store entry is for an anonymous user, ' .
					'but the session metadata indicates a non-anonymous user',
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();

		// Lookup user by ID
		$this->store->setSessionMeta( $id, [ 'userToken' => null ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( $userInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Lookup user by name
		$this->store->setSessionMeta(
			$id, [ 'userId' => 0, 'userName' => $username, 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( $userInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Lookup anonymous user
		$this->store->setSessionMeta(
			$id, [ 'userId' => 0, 'userName' => null, 'userToken' => null ] + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->getUserInfo()->isAnon() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Unverified user with metadata
		$this->store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $unverifiedUserInfo
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertSame( $unverifiedUserInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertSame( $unverifiedUserInfo->getName(), $info->getUserInfo()->getName() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Unverified user with metadata
		$this->store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $unverifiedUserInfo
		] );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertSame( $unverifiedUserInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertSame( $unverifiedUserInfo->getName(), $info->getUserInfo()->getName() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( [], $logger->getBuffer() );

		// Wrong token
		$this->store->setSessionMeta( $id, [ 'userToken' => 'Bad' ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": User token mismatch' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Provider metadata
		$this->store->setSessionMeta( $id, [ 'provider' => 'Mock2' ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => $id,
			'userInfo' => $userInfo,
			'metadata' => [ 'Info' ],
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [ 'Info', 'changed' => true ], $info->getProviderMetadata() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'providerMetadata' => [ 'Saved' ] ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [ 'Saved' ], $info->getProviderMetadata() );
		$this->assertSame( [], $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
			'metadata' => [ 'Info' ],
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [ 'Merged' ], $info->getProviderMetadata() );
		$this->assertSame( [], $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
			'metadata' => [ 'Throw' ],
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[
				LogLevel::WARNING,
				'Session "{session}": Metadata merge failed: no merge!',
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Remember from session
		$this->store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'remember' => true ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'remember' => false ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertSame( [], $logger->getBuffer() );

		// forceHTTPS from session
		$this->store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'forceHTTPS' => true ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'forceHTTPS' => false ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
			'forceHTTPS' => true
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( [], $logger->getBuffer() );

		// "Persist" flag from session
		$this->store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'persisted' => true ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertSame( [], $logger->getBuffer() );

		$this->store->setSessionMeta( $id, [ 'persisted' => false ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
			'persisted' => true
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertSame( [], $logger->getBuffer() );

		// Provider refreshSessionInfo() returning false
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider3,
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [], $logger->getBuffer() );

		// Hook
		$called = false;
		$data = [ 'foo' => 1 ];
		$this->store->setSession( $id, [ 'metadata' => $metadata, 'data' => $data ] );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$manager->setHookContainer( $this->createHookContainer( [
			'SessionCheckInfo' => function ( &$reason, $i, $r, $m, $d ) use (
				$info, $metadata, $data, $request, &$called
			) {
				$this->assertSame( $info->getId(), $i->getId() );
				$this->assertSame( $info->getProvider(), $i->getProvider() );
				$this->assertSame( $info->getUserInfo(), $i->getUserInfo() );
				$this->assertSame( $request, $r );
				$this->assertEquals( $metadata, $m );
				$this->assertEquals( $data, $d );
				$called = true;
				return false;
			}
		] ) );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $called );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Hook aborted' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();
		$manager->setHookContainer( $this->createHookContainer() );

		// forceUse deletes bad backend data
		$this->store->setSessionMeta( $id, [ 'userToken' => 'Bad' ] + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
			'forceUse' => true,
		] );
		$this->assertTrue( $loadSessionInfoFromStore( $info ) );
		$this->assertFalse( $this->store->getSession( $id ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": User token mismatch' ],
		], $logger->getBuffer() );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session store: {action} for {reason}' ],
		], $this->sampledLogger->getBuffer() );
		$logger->clearBuffer();
		$this->sampledLogger->clearBuffer();
	}

	/**
	 * @dataProvider provideLogPotentialSessionLeakage
	 */
	public function testLogPotentialSessionLeakage(
		$ip, $mwuser, $sessionData, $expectedSessionData, $expectedLogLevel
	) {
		MWTimestamp::setFakeTime( 1234567 );
		$this->overrideConfigValue( MainConfigNames::SuspiciousIpExpiry, 600 );

		$proxyLookup = $this->createMock( ProxyLookup::class );
		$proxyLookup->method( 'isConfiguredProxy' )->willReturnCallback( static function ( $ip ) {
			return $ip === '11.22.33.44';
		} );
		$this->setService( 'ProxyLookup', $proxyLookup );

		$manager = $this->getServiceContainer()->getSessionManager();
		$logger = $this->createMock( LoggerInterface::class );
		$this->setLogger( 'session-ip', $logger );
		$request = new FauxRequest();
		$request->setIP( $ip );
		$request->setCookie( 'mwuser-sessionId', $mwuser );

		$session = $this->createMock( Session::class );
		$session->method( 'isPersistent' )->willReturn( true );
		$session->method( 'getUser' )->willReturn( $this->getTestSysop()->getUser() );
		$session->method( 'getRequest' )->willReturn( $request );
		$session->method( 'getProvider' )->willReturn(
			$this->createMock( CookieSessionProvider::class ) );
		$session->method( 'get' )
			->with( 'SessionManager-logPotentialSessionLeakage' )
			->willReturn( $sessionData );
		$session->expects( $this->exactly( isset( $expectedSessionData ) ) )->method( 'set' )
			->with( 'SessionManager-logPotentialSessionLeakage', $expectedSessionData );

		$logger->expects( $this->exactly( isset( $expectedLogLevel ) ) )->method( 'log' )
			->with( $expectedLogLevel );

		$manager->logPotentialSessionLeakage( $session );
	}

	public static function provideLogPotentialSessionLeakage() {
		$now = 1234567;
		$valid = $now - 100;
		$expired = $now - 1000;
		return [
			'no log for new IP' => [
				'ip' => '1.2.3.4',
				'mwuser' => null,
				'sessionData' => [],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => null, 'timestamp' => $now ],
				'expectedLogLevel' => null,
			],
			'no log for same IP' => [
				'ip' => '1.2.3.4',
				'mwuser' => null,
				'sessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => null, 'timestamp' => $valid ],
				'expectedSessionData' => null,
				'expectedLogLevel' => null,
			],
			'no log for expired IP' => [
				'ip' => '1.2.3.4',
				'mwuser' => null,
				'sessionData' => [ 'ip' => '10.20.30.40', 'mwuser' => null, 'timestamp' => $expired ],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => null, 'timestamp' => $now ],
				'expectedLogLevel' => null,
			],
			'INFO log for changed IP' => [
				'ip' => '1.2.3.4',
				'mwuser' => null,
				'sessionData' => [ 'ip' => '10.20.30.40', 'mwuser' => null, 'timestamp' => $valid ],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => null, 'timestamp' => $now ],
				'expectedLogLevel' => LogLevel::INFO,
			],

			'no log for new mwuser' => [
				'ip' => '1.2.3.4',
				'mwuser' => 'new',
				'sessionData' => [],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'new', 'timestamp' => $now ],
				'expectedLogLevel' => null,
			],
			'no log for same mwuser' => [
				'ip' => '1.2.3.4',
				'mwuser' => 'old',
				'sessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'old', 'timestamp' => $valid ],
				'expectedSessionData' => null,
				'expectedLogLevel' => null,
			],
			'NOTICE log for changed mwuser' => [
				'ip' => '1.2.3.4',
				'mwuser' => 'new',
				'sessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'old', 'timestamp' => $valid ],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'new', 'timestamp' => $now ],
				'expectedLogLevel' => LogLevel::NOTICE,
			],
			'no expiration for mwuser' => [
				'ip' => '1.2.3.4',
				'mwuser' => 'new',
				'sessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'old', 'timestamp' => $expired ],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'new', 'timestamp' => $now ],
				'expectedLogLevel' => LogLevel::NOTICE,
			],
			'WARNING log for changed IP + mwuser' => [
				'ip' => '1.2.3.4',
				'mwuser' => 'new',
				'sessionData' => [ 'ip' => '10.20.30.40', 'mwuser' => 'old', 'timestamp' => $valid ],
				'expectedSessionData' => [ 'ip' => '1.2.3.4', 'mwuser' => 'new', 'timestamp' => $now ],
				'expectedLogLevel' => LogLevel::WARNING,
			],

			'special IPs are ignored (1)' => [
				'ip' => '127.0.0.1',
				'mwuser' => 'new',
				'sessionData' => [ 'ip' => '10.20.30.40', 'mwuser' => 'old', 'timestamp' => $valid ],
				'expectedSessionData' => null,
				'expectedLogLevel' => null,
			],
			'special IPs are ignored (2)' => [
				'ip' => '11.22.33.44',
				'mwuser' => 'new',
				'sessionData' => [ 'ip' => '10.20.30.40', 'mwuser' => 'old', 'timestamp' => $valid ],
				'expectedSessionData' => null,
				'expectedLogLevel' => null,
			],
		];
	}
}
