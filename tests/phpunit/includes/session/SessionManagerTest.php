<?php

namespace MediaWiki\Session;

use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use User;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\SessionManager
 */
class SessionManagerTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	/** @var \HashConfig */
	private $config;

	/** @var \TestLogger */
	private $logger;

	/** @var TestBagOStuff */
	private $store;

	protected function getManager() {
		$this->store = new TestBagOStuff();
		$cacheType = $this->setMainCache( $this->store );

		$this->config = new \HashConfig( [
			'LanguageCode' => 'en',
			'SessionCacheType' => $cacheType,
			'ObjectCacheSessionExpiry' => 100,
			'SessionProviders' => [
				[ 'class' => \DummySessionProvider::class ],
			]
		] );
		$this->logger = new \TestLogger( false, static function ( $m ) {
			return ( strpos( $m, 'SessionBackend ' ) === 0
				|| strpos( $m, 'SessionManager using store ' ) === 0
				// These were added for T264793 and behave somewhat erratically, not worth testing
				|| strpos( $m, 'Failed to load session, unpersisting' ) === 0
				|| preg_match( '/^(Persisting|Unpersisting) session (for|due to)/', $m )
			) ? null : $m;
		} );

		return new SessionManager( [
			'config' => $this->config,
			'logger' => $this->logger,
			'store' => $this->store,
		] );
	}

	protected function objectCacheDef( $object ) {
		return [ 'factory' => static function () use ( $object ) {
			return $object;
		} ];
	}

	public function testSingleton() {
		$reset = TestUtils::setSessionManagerSingleton( null );

		$singleton = SessionManager::singleton();
		$this->assertInstanceOf( SessionManager::class, $singleton );
		$this->assertSame( $singleton, SessionManager::singleton() );
	}

	public function testGetGlobalSession() {
		$context = \RequestContext::getMain();

		if ( !PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( SessionManager::singleton() );
		}
		$rProp = new \ReflectionProperty( PHPSessionHandler::class, 'instance' );
		$rProp->setAccessible( true );
		$handler = TestingAccessWrapper::newFromObject( $rProp->getValue() );
		$oldEnable = $handler->enable;
		$reset[] = new \Wikimedia\ScopedCallback( static function () use ( $handler, $oldEnable ) {
			if ( $handler->enable ) {
				session_write_close();
			}
			$handler->enable = $oldEnable;
		} );
		$reset[] = TestUtils::setSessionManagerSingleton( $this->getManager() );

		$handler->enable = true;
		$request = new \FauxRequest();
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
		$request = new \FauxRequest();
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

		$manager = TestingAccessWrapper::newFromObject( new SessionManager() );
		$this->assertSame( $this->getServiceContainer()->getMainConfig(), $manager->config );

		$manager = TestingAccessWrapper::newFromObject( new SessionManager( [
			'config' => $this->config,
		] ) );
		$this->assertSame( $this->store, $manager->store );

		foreach ( [
			'config' => '$options[\'config\'] must be an instance of Config',
			'logger' => '$options[\'logger\'] must be an instance of LoggerInterface',
			'store' => '$options[\'store\'] must be an instance of BagOStuff',
		] as $key => $error ) {
			try {
				new SessionManager( [ $key => new \stdClass ] );
				$this->fail( 'Expected exception not thrown' );
			} catch ( \InvalidArgumentException $ex ) {
				$this->assertSame( $error, $ex->getMessage() );
			}
		}
	}

	public function testGetSessionForRequest() {
		$manager = $this->getManager();
		$request = new \FauxRequest();
		$request->unpersist1 = false;
		$request->unpersist2 = false;

		$id1 = '';
		$id2 = '';
		$idEmpty = 'empty-session-------------------';

		$providerBuilder = $this->getMockBuilder( \DummySessionProvider::class )
			->onlyMethods(
				[ 'provideSessionInfo', 'newSessionInfo', '__toString', 'describe', 'unpersistSession' ]
			);

		$provider1 = $providerBuilder->getMock();
		$provider1->method( 'provideSessionInfo' )
			->with( $this->identicalTo( $request ) )
			->willReturnCallback( static function ( $request ) {
				return $request->info1;
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
			->willReturnCallback( static function ( $request ) {
				$request->unpersist1 = true;
			} );

		$provider2 = $providerBuilder->getMock();
		$provider2->method( 'provideSessionInfo' )
			->with( $this->identicalTo( $request ) )
			->willReturnCallback( static function ( $request ) {
				return $request->info2;
			} );
		$provider2->method( '__toString' )
			->willReturn( 'Provider2' );
		$provider2->method( 'describe' )
			->willReturn( '#2 sessions' );
		$provider2->method( 'unpersistSession' )
			->willReturnCallback( static function ( $request ) {
				$request->unpersist2 = true;
			} );

		$this->config->set( 'SessionProviders', [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		// No provider returns info
		$request->info1 = null;
		$request->info2 = null;
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $idEmpty, $session->getId() );
		$this->assertFalse( $request->unpersist1 );
		$this->assertFalse( $request->unpersist2 );

		// Both providers return info, picks best one
		$request->info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id2, $session->getId() );
		$this->assertFalse( $request->unpersist1 );
		$this->assertFalse( $request->unpersist2 );

		$request->info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id1, $session->getId() );
		$this->assertFalse( $request->unpersist1 );
		$this->assertFalse( $request->unpersist2 );

		// Tied priorities
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
			'idIsSafe' => true,
		] );
		$request->info2 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
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
			$this->assertContains( $request->info1, $ex->getSessionInfos() );
			$this->assertContains( $request->info2, $ex->getSessionInfos() );
		}
		$this->assertFalse( $request->unpersist1 );
		$this->assertFalse( $request->unpersist2 );

		// Bad provider
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$request->info2 = null;
		try {
			$manager->getSessionForRequest( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Provider1 returned session info for a different provider: ' . $request->info1,
				$ex->getMessage()
			);
		}
		$this->assertFalse( $request->unpersist1 );
		$this->assertFalse( $request->unpersist2 );

		// Unusable session info
		$this->logger->setCollect( true );
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'UTSysop', false ),
			'idIsSafe' => true,
		] );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id2, $session->getId() );
		$this->logger->setCollect( false );
		$this->assertTrue( $request->unpersist1 );
		$this->assertFalse( $request->unpersist2 );
		$request->unpersist1 = false;

		$this->logger->setCollect( true );
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'idIsSafe' => true,
		] );
		$request->info2 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'UTSysop', false ),
			'idIsSafe' => true,
		] );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id1, $session->getId() );
		$this->logger->setCollect( false );
		$this->assertFalse( $request->unpersist1 );
		$this->assertTrue( $request->unpersist2 );
		$request->unpersist2 = false;

		// Unpersisted session ID
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => false,
			'userInfo' => UserInfo::newFromName( 'UTSysop', true ),
			'idIsSafe' => true,
		] );
		$request->info2 = null;
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id1, $session->getId() );
		$this->assertTrue( $request->unpersist1 ); // The saving of the session does it
		$this->assertFalse( $request->unpersist2 );
		$session->persist();
		$this->assertTrue( $session->isPersistent() );
	}

	public function testGetSessionById() {
		$manager = $this->getManager();
		try {
			$manager->getSessionById( 'bad' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage() );
		}

		// Unknown session ID
		$id = $manager->generateSessionId();
		$session = $manager->getSessionById( $id, true );
		$this->assertInstanceOf( Session::class, $session );
		$this->assertSame( $id, $session->getId() );

		$id = $manager->generateSessionId();
		$this->assertNull( $manager->getSessionById( $id, false ) );

		// Known but unloadable session ID
		$this->logger->setCollect( true );
		$id = $manager->generateSessionId();
		$this->store->setSession( $id, [ 'metadata' => [
			'userId' => User::idFromName( 'UTSysop' ),
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
		$this->store->setSession( $id, [ 'metadata' => [
			'userId' => User::idFromName( 'UTSysop' ),
			'userToken' => 'bad',
		] ] );
		$session2 = $manager->getSessionById( $id, false );
		$this->assertInstanceOf( Session::class, $session2 );
		$this->assertSame( $id, $session2->getId() );
		unset( $session, $session2 );
		$this->logger->setCollect( true );
		$this->assertNull( $manager->getSessionById( $id, true ) );
		$this->logger->setCollect( false );

		// Failure to create an empty session
		$manager = $this->getManager();
		$provider = $this->getMockBuilder( \DummySessionProvider::class )
			->onlyMethods( [ 'provideSessionInfo', 'newSessionInfo', '__toString' ] )
			->getMock();
		$provider->method( 'provideSessionInfo' )
			->willReturn( null );
		$provider->method( 'newSessionInfo' )
			->willReturn( null );
		$provider->method( '__toString' )
			->willReturn( 'MockProvider' );
		$this->config->set( 'SessionProviders', [
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
		$request = new \FauxRequest();

		$providerBuilder = $this->getMockBuilder( \DummySessionProvider::class )
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

		$this->config->set( 'SessionProviders', [
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
		} catch ( \UnexpectedValueException $ex ) {
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
		} catch ( \UnexpectedValueException $ex ) {
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
		} catch ( \UnexpectedValueException $ex ) {
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
		} catch ( \UnexpectedValueException $ex ) {
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
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertStringStartsWith(
				'Multiple empty sessions tied for top priority: ',
				$ex->getMessage()
			);
		}

		// Bad id
		try {
			$pmanager->getEmptySessionInternal( null, 'bad' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
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
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Session ID already exists', $ex->getMessage() );
		}
	}

	public function testInvalidateSessionsForUser() {
		$user = User::newFromName( 'UTSysop' );
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( \DummySessionProvider::class )
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

		$this->config->set( 'SessionProviders', [
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		] );

		$oldToken = $user->getToken( true );
		$manager->invalidateSessionsForUser( $user );
		$this->assertNotEquals( $oldToken, $user->getToken() );
	}

	public function testGetVaryHeaders() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( \DummySessionProvider::class )
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

		$this->config->set( 'SessionProviders', [
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

		$providerBuilder = $this->getMockBuilder( \DummySessionProvider::class )
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

		$this->config->set( 'SessionProviders', [
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

		$this->config->set( 'SessionProviders', [
			[ 'class' => \DummySessionProvider::class ],
		] );
		$providers = $manager->getProviders();
		$this->assertArrayHasKey( 'DummySessionProvider', $providers );
		$provider = TestingAccessWrapper::newFromObject( $providers['DummySessionProvider'] );
		$this->assertSame( $manager->logger, $provider->logger );
		$this->assertSame( $manager->config, $provider->getConfig() );
		$this->assertSame( $realManager, $provider->getManager() );

		$this->config->set( 'SessionProviders', [
			[ 'class' => \DummySessionProvider::class ],
			[ 'class' => \DummySessionProvider::class ],
		] );
		$manager->sessionProviders = null;
		try {
			$manager->getProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Duplicate provider name "DummySessionProvider"',
				$ex->getMessage()
			);
		}
	}

	public function testShutdown() {
		$manager = TestingAccessWrapper::newFromObject( $this->getManager() );
		$manager->setLogger( new \Psr\Log\NullLogger() );

		$mock = $this->getMockBuilder( \stdClass::class )
			->addMethods( [ 'shutdown' ] )->getMock();
		$mock->expects( $this->once() )->method( 'shutdown' );

		$manager->allSessionBackends = [ $mock ];
		$manager->shutdown();
	}

	public function testGetSessionFromInfo() {
		$manager = TestingAccessWrapper::newFromObject( $this->getManager() );
		$request = new \FauxRequest();

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $manager->getProvider( 'DummySessionProvider' ),
			'id' => $id,
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'UTSysop', true ),
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

		$session = $manager->getSessionForRequest( new \FauxRequest );
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
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Backend was not registered with this SessionManager', $ex->getMessage()
			);
		}

		try {
			$manager->deregisterSessionBackend( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
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

		$providerBuilder = $this->getMockBuilder( \DummySessionProvider::class )
			->onlyMethods( [ 'preventSessionsForUser', '__toString' ] );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'preventSessionsForUser' )
			->with( 'UTSysop' );
		$provider1->method( '__toString' )
			->willReturn( 'MockProvider1' );

		$this->config->set( 'SessionProviders', [
			$this->objectCacheDef( $provider1 ),
		] );

		$this->assertFalse( $manager->isUserSessionPrevented( 'UTSysop' ) );
		$manager->preventSessionsForUser( 'UTSysop' );
		$this->assertTrue( $manager->isUserSessionPrevented( 'UTSysop' ) );
	}

	public function testLoadSessionInfoFromStore() {
		$manager = $this->getManager();
		$logger = new \TestLogger( true );
		$manager->setLogger( $logger );
		$request = new \FauxRequest();

		// TestingAccessWrapper can't handle methods with reference arguments, sigh.
		$rClass = new \ReflectionClass( $manager );
		$rMethod = $rClass->getMethod( 'loadSessionInfoFromStore' );
		$rMethod->setAccessible( true );
		$loadSessionInfoFromStore = static function ( &$info ) use ( $rMethod, $manager, $request ) {
			return $rMethod->invokeArgs( $manager, [ &$info, $request ] );
		};

		$userInfo = UserInfo::newFromName( 'UTSysop', true );
		$unverifiedUserInfo = UserInfo::newFromName( 'UTSysop', false );

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
			[ LogLevel::WARNING, 'Session "{session}": Bad data' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->store->setRawSession( $id, [ 'data' => [] ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Bad data structure' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->store->deleteSession( $id );
		$this->store->setRawSession( $id, [ 'metadata' => $metadata ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Bad data structure' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->store->setRawSession( $id, [ 'metadata' => $metadata, 'data' => true ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Bad data structure' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->store->setRawSession( $id, [ 'metadata' => true, 'data' => [] ] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Bad data structure' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		foreach ( $metadata as $key => $dummy ) {
			$tmp = $metadata;
			unset( $tmp[$key] );
			$this->store->setRawSession( $id, [ 'metadata' => $tmp, 'data' => [] ] );
			$this->assertFalse( $loadSessionInfoFromStore( $info ) );
			$this->assertSame( [
				[ LogLevel::WARNING, 'Session "{session}": Bad metadata' ],
			], $logger->getBuffer() );
			$logger->clearBuffer();
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
			[ LogLevel::WARNING, 'Session "{session}": Unknown provider Bad' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

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
				'Session "{session}": Metadata has an anonymous user, ' .
				'but a non-anon user was provided',
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

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
			$id, [ 'userId' => 0, 'userName' => 'UTSysop', 'userToken' => null ] + $metadata
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
				'Session "{session}": Metadata merge failed: {exception}',
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
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'SessionCheckInfo' => [ function ( &$reason, $i, $r, $m, $d ) use (
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
			} ]
		] );
		$this->assertFalse( $loadSessionInfoFromStore( $info ) );
		$this->assertTrue( $called );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Session "{session}": Hook aborted' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();
		$this->mergeMwGlobalArrayValue( 'wgHooks', [ 'SessionCheckInfo' => [] ] );

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
		$logger->clearBuffer();
	}

	/**
	 * @dataProvider provideLogPotentialSessionLeakage
	 */
	public function testLogPotentialSessionLeakage(
		$ip, $mwuser, $sessionData, $expectedSessionData, $expectedLogLevel
	) {
		\MWTimestamp::setFakeTime( 1234567 );
		$this->overrideConfigValue( MainConfigNames::SuspiciousIpExpiry, 600 );
		$manager = new SessionManager();
		$logger = $this->createMock( LoggerInterface::class );
		$this->setLogger( 'session-ip', $logger );
		$request = new \FauxRequest();
		$request->setIP( $ip );
		$request->setCookie( 'mwuser-sessionId', $mwuser );

		$proxyLookup = $this->createMock( \ProxyLookup::class );
		$proxyLookup->method( 'isConfiguredProxy' )->willReturnCallback( static function ( $ip ) {
			return $ip === '11.22.33.44';
		} );
		$this->setService( 'ProxyLookup', $proxyLookup );

		$session = $this->createMock( Session::class );
		$session->method( 'isPersistent' )->willReturn( true );
		$session->method( 'getUser' )->willReturn( User::newFromName( 'UTSysop' ) );
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

	public function provideLogPotentialSessionLeakage() {
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
