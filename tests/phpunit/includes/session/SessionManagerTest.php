<?php

namespace MediaWiki\Session;

use Psr\Log\LogLevel;
use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\SessionManager
 */
class SessionManagerTest extends MediaWikiTestCase {

	protected $config, $logger, $store;

	protected function getManager() {
		\ObjectCache::$instances['testSessionStore'] = new TestBagOStuff();
		$this->config = new \HashConfig( array(
			'LanguageCode' => 'en',
			'SessionCacheType' => 'testSessionStore',
			'ObjectCacheSessionExpiry' => 100,
			'SessionProviders' => array(
				array( 'class' => 'DummySessionProvider' ),
			)
		) );
		$this->logger = new \TestLogger( false, function ( $m ) {
			return substr( $m, 0, 15 ) === 'SessionBackend ' ? null : $m;
		} );
		$this->store = new TestBagOStuff();

		return new SessionManager( array(
			'config' => $this->config,
			'logger' => $this->logger,
			'store' => $this->store,
		) );
	}

	protected function objectCacheDef( $object ) {
		return array( 'factory' => function () use ( $object ) {
			return $object;
		} );
	}

	public function testSingleton() {
		$reset = SessionManager::setSingletonForTest( null );

		$singleton = SessionManager::singleton();
		$this->assertInstanceOf( 'MediaWiki\\Session\\SessionManager', $singleton );
		$this->assertSame( $singleton, SessionManager::singleton() );
	}

	public function testGetGlobalSession() {
		$context = \RequestContext::getMain();

		if ( !PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( SessionManager::singleton() );
		}
		$rProp = new \ReflectionProperty( 'MediaWiki\\Session\\PHPSessionHandler', 'instance' );
		$rProp->setAccessible( true );
		$handler = \TestingAccessWrapper::newFromObject( $rProp->getValue() );
		$oldEnable = $handler->enable;
		$reset[] = new \ScopedCallback( function () use ( $handler, $oldEnable ) {
			if ( $handler->enable ) {
				session_write_close();
			}
			$handler->enable = $oldEnable;
		} );
		$reset[] = SessionManager::setSingletonForTest( $this->getManager() );

		$handler->enable = true;
		$request = new \FauxRequest();
		$context->setRequest( $request );
		$id = $request->getSession()->getId();

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
		$manager = \TestingAccessWrapper::newFromObject( $this->getManager() );
		$this->assertSame( $this->config, $manager->config );
		$this->assertSame( $this->logger, $manager->logger );
		$this->assertSame( $this->store, $manager->store );

		$manager = \TestingAccessWrapper::newFromObject( new SessionManager() );
		$this->assertSame( \RequestContext::getMain()->getConfig(), $manager->config );

		$manager = \TestingAccessWrapper::newFromObject( new SessionManager( array(
			'config' => $this->config,
		) ) );
		$this->assertSame( \ObjectCache::$instances['testSessionStore'], $manager->store );

		foreach ( array(
			'config' => '$options[\'config\'] must be an instance of Config',
			'logger' => '$options[\'logger\'] must be an instance of LoggerInterface',
			'store' => '$options[\'store\'] must be an instance of BagOStuff',
		) as $key => $error ) {
			try {
				new SessionManager( array( $key => new \stdClass ) );
				$this->fail( 'Expected exception not thrown' );
			} catch ( \InvalidArgumentException $ex ) {
				$this->assertSame( $error, $ex->getMessage() );
			}
		}
	}

	public function testGetSessionForRequest() {
		$manager = $this->getManager();
		$request = new \FauxRequest();

		$id1 = '';
		$id2 = '';
		$idEmpty = 'empty-session-------------------';

		$providerBuilder = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods(
				array( 'provideSessionInfo', 'newSessionInfo', '__toString', 'describe' )
			);

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->any() )->method( 'provideSessionInfo' )
			->with( $this->identicalTo( $request ) )
			->will( $this->returnCallback( function ( $request ) {
				return $request->info1;
			} ) );
		$provider1->expects( $this->any() )->method( 'newSessionInfo' )
			->will( $this->returnCallback( function () use ( $idEmpty, $provider1 ) {
				return new SessionInfo( SessionInfo::MIN_PRIORITY, array(
					'provider' => $provider1,
					'id' => $idEmpty,
					'persisted' => true,
					'testIdIsSafe' => true,
				) );
			} ) );
		$provider1->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Provider1' ) );
		$provider1->expects( $this->any() )->method( 'describe' )
			->will( $this->returnValue( '#1 sessions' ) );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->any() )->method( 'provideSessionInfo' )
			->with( $this->identicalTo( $request ) )
			->will( $this->returnCallback( function ( $request ) {
				return $request->info2;
			} ) );
		$provider2->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Provider2' ) );
		$provider2->expects( $this->any() )->method( 'describe' )
			->will( $this->returnValue( '#2 sessions' ) );

		$this->config->set( 'SessionProviders', array(
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		) );

		// No provider returns info
		$request->info1 = null;
		$request->info2 = null;
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $idEmpty, $session->getId() );
		$this->assertNull( $manager->getPersistedSessionId( $request ) );

		// Both providers return info, picks best one
		$request->info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array(
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array(
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id2, $session->getId() );
		$this->assertSame( $id2, $manager->getPersistedSessionId( $request ) );

		$request->info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array(
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array(
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id1, $session->getId() );
		$this->assertSame( $id1, $manager->getPersistedSessionId( $request ) );

		// Tied priorities
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		try {
			$manager->getSessionForRequest( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( \HttpError $ex ) {
			$this->assertSame(
				'Cannot combine multiple request authentication types: #1 sessions and #2 sessions.',
				$ex->getMessage()
			);
		}
		try {
			$manager->getPersistedSessionId( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Multiple sessions for this request tied for top priority: ' .
					$request->info1 . ', ' . $request->info2,
				$ex->getMessage()
			);
		}

		// Bad provider
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider2,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
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
		try {
			$manager->getPersistedSessionId( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Provider1 returned session info for a different provider: ' . $request->info1,
				$ex->getMessage()
			);
		}

		// Unusable session info
		$this->logger->setCollect( true );
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'UTSysop', false ),
			'testIdIsSafe' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider2,
			'id' => ( $id2 = $manager->generateSessionId() ),
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id2, $session->getId() );
		$this->assertSame( $id2, $manager->getPersistedSessionId( $request ) );
		$this->logger->setCollect( false );

		// Unpersisted session ID
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider1,
			'id' => ( $id1 = $manager->generateSessionId() ),
			'persisted' => false,
			'userInfo' => UserInfo::newFromName( 'UTSysop', true ),
			'testIdIsSafe' => true,
		) );
		$request->info2 = null;
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id1, $session->getId() );
		$session->persist();
		$this->assertTrue( $session->isPersistent(), 'sanity check' );
		$this->assertNull( $manager->getPersistedSessionId( $request ) );
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
		$session = $manager->getSessionById( $id );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id, $session->getId() );

		$id = $manager->generateSessionId();
		$this->assertNull( $manager->getSessionById( $id, true ) );

		// Known but unloadable session ID
		$this->logger->setCollect( true );
		$id = $manager->generateSessionId();
		$this->store->setRawSession( $id, array( 'metadata' => array(
			'provider' => 'DummySessionProvider',
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
		) ) );

		try {
			$manager->getSessionById( $id );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Can neither load the session nor create an empty session',
				$ex->getMessage()
			);
		}

		$this->assertNull( $manager->getSessionById( $id, true ) );
		$this->logger->setCollect( false );

		// Known session ID
		$this->store->setSession( $id, array() );
		$session = $manager->getSessionById( $id );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id, $session->getId() );
	}

	public function testGetEmptySession() {
		$manager = $this->getManager();
		$pmanager = \TestingAccessWrapper::newFromObject( $manager );
		$request = new \FauxRequest();

		$providerBuilder = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods( array( 'provideSessionInfo', 'newSessionInfo', '__toString' ) );

		$expectId = null;
		$info1 = null;
		$info2 = null;

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->any() )->method( 'provideSessionInfo' )
			->will( $this->returnValue( null ) );
		$provider1->expects( $this->any() )->method( 'newSessionInfo' )
			->with( $this->callback( function ( $id ) use ( &$expectId ) {
				return $id === $expectId;
			} ) )
			->will( $this->returnCallback( function () use ( &$info1 ) {
				return $info1;
			} ) );
		$provider1->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider1' ) );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->any() )->method( 'provideSessionInfo' )
			->will( $this->returnValue( null ) );
		$provider2->expects( $this->any() )->method( 'newSessionInfo' )
			->with( $this->callback( function ( $id ) use ( &$expectId ) {
				return $id === $expectId;
			} ) )
			->will( $this->returnCallback( function () use ( &$info2 ) {
				return $info2;
			} ) );
		$provider1->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider2' ) );

		$this->config->set( 'SessionProviders', array(
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		) );

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
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider1,
			'id' => 'empty---------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$info2 = null;
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( 'empty---------------------------', $session->getId() );

		// Info, explicitly
		$expectId = 'expected------------------------';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider1,
			'id' => $expectId,
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$info2 = null;
		$session = $pmanager->getEmptySessionInternal( null, $expectId );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $expectId, $session->getId() );

		// Wrong ID
		$expectId = 'expected-----------------------2';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider1,
			'id' => "un$expectId",
			'persisted' => true,
			'byId' => true,
		) );
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
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider1,
			'id' => $expectId,
			'persisted' => true,
		) );
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
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider2,
			'id' => 'empty---------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
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
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array(
			'provider' => $provider1,
			'id' => 'empty1--------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider2,
			'id' => 'empty2--------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( 'empty1--------------------------', $session->getId() );

		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array(
			'provider' => $provider1,
			'id' => 'empty1--------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array(
			'provider' => $provider2,
			'id' => 'empty2--------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( 'empty2--------------------------', $session->getId() );

		// Tied priorities throw an exception
		$expectId = null;
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider1,
			'id' => 'empty1--------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider2,
			'id' => 'empty2--------------------------',
			'persisted' => true,
			'testIdIsSafe' => true,
		) );
		try {
			$manager->getEmptySession();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Multiple empty sessions tied for top priority: ' . $info1 . ', ' . $info2,
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
		$this->store->setSessionMeta( $expectId, array(
			'provider' => 'MockProvider2',
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
		) );
		try {
			$pmanager->getEmptySessionInternal( null, $expectId );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Session ID already exists', $ex->getMessage() );
		}
	}

	public function testGetVaryHeaders() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods( array( 'getVaryHeaders', '__toString' ) );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'getVaryHeaders' )
			->will( $this->returnValue( array(
				'Foo' => null,
				'Bar' => array( 'X', 'Bar1' ),
				'Quux' => null,
			) ) );
		$provider1->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider1' ) );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->once() )->method( 'getVaryHeaders' )
			->will( $this->returnValue( array(
				'Baz' => null,
				'Bar' => array( 'X', 'Bar2' ),
				'Quux' => array( 'Quux' ),
			) ) );
		$provider2->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider2' ) );

		$this->config->set( 'SessionProviders', array(
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		) );

		$expect = array(
			'Foo' => array(),
			'Bar' => array( 'X', 'Bar1', 3 => 'Bar2' ),
			'Quux' => array( 'Quux' ),
			'Baz' => array(),
			'Quux' => array( 'Quux' ),
		);

		$this->assertEquals( $expect, $manager->getVaryHeaders() );

		// Again, to ensure it's cached
		$this->assertEquals( $expect, $manager->getVaryHeaders() );
	}

	public function testGetVaryCookies() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods( array( 'getVaryCookies', '__toString' ) );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'getVaryCookies' )
			->will( $this->returnValue( array( 'Foo', 'Bar' ) ) );
		$provider1->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider1' ) );

		$provider2 = $providerBuilder->getMock();
		$provider2->expects( $this->once() )->method( 'getVaryCookies' )
			->will( $this->returnValue( array( 'Foo', 'Baz' ) ) );
		$provider2->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider2' ) );

		$this->config->set( 'SessionProviders', array(
			$this->objectCacheDef( $provider1 ),
			$this->objectCacheDef( $provider2 ),
		) );

		$expect = array( 'Foo', 'Bar', 'Baz' );

		$this->assertEquals( $expect, $manager->getVaryCookies() );

		// Again, to ensure it's cached
		$this->assertEquals( $expect, $manager->getVaryCookies() );
	}

	public function testGetProviders() {
		$realManager = $this->getManager();
		$manager = \TestingAccessWrapper::newFromObject( $realManager );

		$this->config->set( 'SessionProviders', array(
			array( 'class' => 'DummySessionProvider' ),
		) );
		$providers = $manager->getProviders();
		$this->assertArrayHasKey( 'DummySessionProvider', $providers );
		$provider = \TestingAccessWrapper::newFromObject( $providers['DummySessionProvider'] );
		$this->assertSame( $manager->logger, $provider->logger );
		$this->assertSame( $manager->config, $provider->config );
		$this->assertSame( $realManager, $provider->getManager() );

		$this->config->set( 'SessionProviders', array(
			array( 'class' => 'DummySessionProvider' ),
			array( 'class' => 'DummySessionProvider' ),
		) );
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
		$manager = \TestingAccessWrapper::newFromObject( $this->getManager() );
		$manager->setLogger( new \Psr\Log\NullLogger() );

		$mock = $this->getMock( 'stdClass', array( 'save' ) );
		$mock->expects( $this->once() )->method( 'save' );

		$manager->allSessionBackends = array( $mock );
		$manager->shutdown();
	}

	public function testGetSessionFromInfo() {
		$manager = \TestingAccessWrapper::newFromObject( $this->getManager() );
		$request = new \FauxRequest();

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $manager->getProvider( 'DummySessionProvider' ),
			'id' => $id,
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( 'UTSysop', true ),
			'testIdIsSafe' => true,
		) );
		\TestingAccessWrapper::newFromObject( $info )->idIsSafe = true;
		$session1 = \TestingAccessWrapper::newFromObject(
			$manager->getSessionFromInfo( $info, $request )
		);
		$session2 = \TestingAccessWrapper::newFromObject(
			$manager->getSessionFromInfo( $info, $request )
		);

		$this->assertSame( $session1->backend, $session2->backend );
		$this->assertNotEquals( $session1->index, $session2->index );
		$this->assertSame( $session1->getSessionId(), $session2->getSessionId() );
		$this->assertSame( $id, $session1->getId() );

		\TestingAccessWrapper::newFromObject( $info )->idIsSafe = false;
		$session3 = $manager->getSessionFromInfo( $info, $request );
		$this->assertNotSame( $id, $session3->getId() );
	}

	public function testBackendRegistration() {
		$manager = $this->getManager();

		$session = $manager->getSessionForRequest( new \FauxRequest );
		$backend = \TestingAccessWrapper::newFromObject( $session )->backend;
		$sessionId = $session->getSessionId();
		$id = (string)$sessionId;

		$this->assertSame( $sessionId, $manager->getSessionById( $id )->getSessionId() );

		$manager->changeBackendId( $backend );
		$this->assertSame( $sessionId, $session->getSessionId() );
		$this->assertNotEquals( $id, (string)$sessionId );
		$id = (string)$sessionId;

		$this->assertSame( $sessionId, $manager->getSessionById( $id )->getSessionId() );

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

		$session = $manager->getSessionById( $id );
		$this->assertSame( $sessionId, $session->getSessionId() );
	}

	public function testGenerateSessionId() {
		$manager = $this->getManager();

		$id = $manager->generateSessionId();
		$this->assertTrue( SessionManager::validateSessionId( $id ), "Generated ID: $id" );
	}

	public function testAutoCreateUser() {
		global $wgGroupPermissions;

		$that = $this;

		\ObjectCache::$instances[__METHOD__] = new \HashBagOStuff();
		$this->setMwGlobals( array( 'wgMainCacheType' => __METHOD__ ) );

		$this->stashMwGlobals( array( 'wgGroupPermissions' ) );
		$wgGroupPermissions['*']['createaccount'] = true;
		$wgGroupPermissions['*']['autocreateaccount'] = false;

		// Replace the global singleton with one configured for testing
		$manager = $this->getManager();
		$reset = SessionManager::setSingletonForTest( $manager );

		$logger = new \TestLogger( true, function ( $m ) {
			if ( substr( $m, 0, 15 ) === 'SessionBackend ' ) {
				// Don't care.
				return null;
			}
			$m = str_replace( 'MediaWiki\Session\SessionManager::autoCreateUser: ', '', $m );
			$m = preg_replace( '/ - from: .*$/', ' - from: XXX', $m );
			return $m;
		} );
		$manager->setLogger( $logger );

		$session = SessionManager::getGlobalSession();

		// Can't create an already-existing user
		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( $id, $user->getId() );
		$this->assertSame( 'UTSysop', $user->getName() );
		$this->assertSame( array(), $logger->getBuffer() );
		$logger->clearBuffer();

		// Sanity check that creation works at all
		$user = User::newFromName( 'UTSessionAutoCreate1' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$this->assertTrue( $manager->autoCreateUser( $user ) );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate1', $user->getName() );
		$this->assertEquals(
			$user->getId(), User::idFromName( 'UTSessionAutoCreate1', User::READ_LATEST )
		);
		$this->assertSame( array(
			array( LogLevel::INFO, 'creating new user (UTSessionAutoCreate1) - from: XXX' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Check lack of permissions
		$wgGroupPermissions['*']['createaccount'] = false;
		$wgGroupPermissions['*']['autocreateaccount'] = false;
		$user = User::newFromName( 'UTDoesNotExist' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'user is blocked from this wiki, blacklisting' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Check other permission
		$wgGroupPermissions['*']['createaccount'] = false;
		$wgGroupPermissions['*']['autocreateaccount'] = true;
		$user = User::newFromName( 'UTSessionAutoCreate2' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$this->assertTrue( $manager->autoCreateUser( $user ) );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate2', $user->getName() );
		$this->assertEquals(
			$user->getId(), User::idFromName( 'UTSessionAutoCreate2', User::READ_LATEST )
		);
		$this->assertSame( array(
			array( LogLevel::INFO, 'creating new user (UTSessionAutoCreate2) - from: XXX' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test account-creation block
		$anon = new User;
		$block = new \Block( array(
			'address' => $anon->getName(),
			'user' => $id,
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
			'createAccount' => true,
		) );
		$block->insert();
		$this->assertInstanceOf( 'Block', $anon->isBlockedFromCreateAccount(), 'sanity check' );
		$reset2 = new \ScopedCallback( array( $block, 'delete' ) );
		$user = User::newFromName( 'UTDoesNotExist' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		\ScopedCallback::consume( $reset2 );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'user is blocked from this wiki, blacklisting' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Sanity check that creation still works
		$user = User::newFromName( 'UTSessionAutoCreate3' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$this->assertTrue( $manager->autoCreateUser( $user ) );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate3', $user->getName() );
		$this->assertEquals(
			$user->getId(), User::idFromName( 'UTSessionAutoCreate3', User::READ_LATEST )
		);
		$this->assertSame( array(
			array( LogLevel::INFO, 'creating new user (UTSessionAutoCreate3) - from: XXX' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test prevention by AuthPlugin
		global $wgAuth;
		$oldWgAuth = $wgAuth;
		$mockWgAuth = $this->getMock( 'AuthPlugin', array( 'autoCreate' ) );
		$mockWgAuth->expects( $this->once() )->method( 'autoCreate' )
			->will( $this->returnValue( false ) );
		$this->setMwGlobals( array(
			'wgAuth' => $mockWgAuth,
		) );
		$user = User::newFromName( 'UTDoesNotExist' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->setMwGlobals( array(
			'wgAuth' => $oldWgAuth,
		) );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'denied by AuthPlugin' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test prevention by wfReadOnly()
		$this->setMwGlobals( array(
			'wgReadOnly' => 'Because',
		) );
		$user = User::newFromName( 'UTDoesNotExist' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->setMwGlobals( array(
			'wgReadOnly' => false,
		) );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'denied by wfReadOnly()' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test prevention by a previous session
		$session->set( 'MWSession::AutoCreateBlacklist', 'test' );
		$user = User::newFromName( 'UTDoesNotExist' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'blacklisted in session (test)' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test uncreatable name
		$user = User::newFromName( 'UTDoesNotExist@' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist@', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'Invalid username, blacklisting' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test AbortAutoAccount hook
		$mock = $this->getMock( __CLASS__, array( 'onAbortAutoAccount' ) );
		$mock->expects( $this->once() )->method( 'onAbortAutoAccount' )
			->will( $this->returnCallback( function ( User $user, &$msg ) {
				$msg = 'No way!';
				return false;
			} ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'AbortAutoAccount' => array( $mock ) ) );
		$user = User::newFromName( 'UTDoesNotExist' );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'AbortAutoAccount' => array() ) );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'denied by hook: No way!' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test AbortAutoAccount hook screwing up the name
		$mock = $this->getMock( 'stdClass', array( 'onAbortAutoAccount' ) );
		$mock->expects( $this->once() )->method( 'onAbortAutoAccount' )
			->will( $this->returnCallback( function ( User $user ) {
				$user->setName( 'UTDoesNotExistEither' );
			} ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'AbortAutoAccount' => array( $mock ) ) );
		try {
			$user = User::newFromName( 'UTDoesNotExist' );
			$manager->autoCreateUser( $user );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'AbortAutoAccount hook tried to change the user name',
				$ex->getMessage()
			);
		}
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertNotSame( 'UTDoesNotExistEither', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExistEither', User::READ_LATEST ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'AbortAutoAccount' => array() ) );
		$session->clear();
		$this->assertSame( array(), $logger->getBuffer() );
		$logger->clearBuffer();

		// Test for "exception backoff"
		$user = User::newFromName( 'UTDoesNotExist' );
		$cache = \ObjectCache::getLocalClusterInstance();
		$backoffKey = wfMemcKey( 'MWSession', 'autocreate-failed', md5( $user->getName() ) );
		$cache->set( $backoffKey, 1, 60 * 10 );
		$this->assertFalse( $manager->autoCreateUser( $user ) );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$cache->delete( $backoffKey );
		$session->clear();
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'denied by prior creation attempt failures' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Sanity check that creation still works, and test completion hook
		$cb = $this->callback( function ( User $user ) use ( $that ) {
			$that->assertNotEquals( 0, $user->getId() );
			$that->assertSame( 'UTSessionAutoCreate4', $user->getName() );
			$that->assertEquals(
				$user->getId(), User::idFromName( 'UTSessionAutoCreate4', User::READ_LATEST )
			);
			return true;
		} );
		$mock = $this->getMock( 'stdClass',
			array( 'onAuthPluginAutoCreate', 'onLocalUserCreated' ) );
		$mock->expects( $this->once() )->method( 'onAuthPluginAutoCreate' )
			->with( $cb );
		$mock->expects( $this->once() )->method( 'onLocalUserCreated' )
			->with( $cb, $this->identicalTo( true ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'AuthPluginAutoCreate' => array( $mock ),
			'LocalUserCreated' => array( $mock ),
		) );
		$user = User::newFromName( 'UTSessionAutoCreate4' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$this->assertTrue( $manager->autoCreateUser( $user ) );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate4', $user->getName() );
		$this->assertEquals(
			$user->getId(),
			User::idFromName( 'UTSessionAutoCreate4', User::READ_LATEST )
		);
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'AuthPluginAutoCreate' => array(),
			'LocalUserCreated' => array(),
		) );
		$this->assertSame( array(
			array( LogLevel::INFO, 'creating new user (UTSessionAutoCreate4) - from: XXX' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
	}

	public function onAbortAutoAccount( User $user, &$msg ) {
	}

	public function testPreventSessionsForUser() {
		$manager = $this->getManager();

		$providerBuilder = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods( array( 'preventSessionsForUser', '__toString' ) );

		$provider1 = $providerBuilder->getMock();
		$provider1->expects( $this->once() )->method( 'preventSessionsForUser' )
			->with( $this->equalTo( 'UTSysop' ) );
		$provider1->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider1' ) );

		$this->config->set( 'SessionProviders', array(
			$this->objectCacheDef( $provider1 ),
		) );

		$user = User::newFromName( 'UTSysop' );
		$token = $user->getToken( true );

		$this->assertFalse( $manager->isUserSessionPrevented( 'UTSysop' ) );
		$manager->preventSessionsForUser( 'UTSysop' );
		$this->assertNotEquals( $token, User::newFromName( 'UTSysop' )->getToken() );
		$this->assertTrue( $manager->isUserSessionPrevented( 'UTSysop' ) );
	}

}
