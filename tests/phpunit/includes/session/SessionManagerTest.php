<?php

namespace MediaWiki\Session;

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
		\ObjectCache::$instances['testSessionStore'] = new \HashBagOStuff();
		$this->config = new \HashConfig( array(
			'LanguageCode' => 'en',
			'SessionCacheType' => 'testSessionStore',
			'SessionsInObjectCache' => true,
			'SessionHandler' => null,
			'ObjectCacheSessionExpiry' => 100,
			'SessionProviders' => array(
				array( 'class' => 'DummySessionProvider' ),
			)
		) );
		$this->logger = new \Psr\Log\NullLogger();
		$this->store = new \HashBagOStuff();

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
		$reset[] = new \ScopedCallback(
			array( $context, 'setConfig' ),
			array( $context->getConfig() )
		);
		$reset[] = new \ScopedCallback(
			array( $context, 'setRequest' ),
			array( $context->getRequest() )
		);

		$config = new \HashConfig();
		$config->set( 'PHPSessionHandling', 'enable' );
		$context->setConfig( $config );
		$request = new \FauxRequest();
		$context->setRequest( $request );

		$reset2 = SessionManager::setSingletonForTest( $this->getManager() );
		$id = $request->getSession()->getId();
		session_id( '' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( $id, $session->getId() );

		session_id( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', $session->getId() );
		unset( $reset2 );

		$config->set( 'PHPSessionHandling', 'disable' );
		$reset2 = SessionManager::setSingletonForTest( $this->getManager() );
		session_id( '' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( $id, $session->getId() );

		session_id( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' );
		$session = SessionManager::getGlobalSession();
		$this->assertSame( $id, $session->getId() );
		unset( $reset2 );
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

		// Make sure these don't crash
		$this->config->set( 'SessionsInObjectCache', false );
		new SessionManager( array( 'config' => $this->config ) );
		$this->config->set( 'SessionHandler', 'foo' );
		new SessionManager( array( 'config' => $this->config ) );
	}

	public function testGetSessionForRequest() {
		$manager = $this->getManager();
		$request = new \FauxRequest();

		$id1 = 'provider1-----------------------';
		$id2 = 'provider2-----------------------';
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

		// Both providers return info, picks best one
		$request->info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array(
			'provider' => $provider1,
			'id' => $id1,
			'persisted' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array(
			'provider' => $provider2,
			'id' => $id2,
			'persisted' => true,
		) );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id2, $session->getId() );

		$request->info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array(
			'provider' => $provider1,
			'id' => $id1,
			'persisted' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array(
			'provider' => $provider2,
			'id' => $id2,
			'persisted' => true,
		) );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id1, $session->getId() );

		// Tied priorities
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider1,
			'id' => $id1,
			'persisted' => true,
		) );
		$request->info2 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider2,
			'id' => $id2,
			'persisted' => true,
		) );
		try {
			$manager->getSessionForRequest( $request );
			$this->fail( 'Expcected exception not thrown' );
		} catch ( \HttpError $ex ) {
			$this->assertSame(
				'Cannot combine multiple request authentication types: #1 sessions and #2 sessions',
				$ex->getMessage()
			);
		}

		// Bad provider
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider2,
			'id' => $id1,
			'persisted' => true,
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

		// Unusable session info
		$request->info1 = new SessionInfo( SessionInfo::MAX_PRIORITY, array(
			'provider' => $provider1,
			'id' => $id1,
			'persisted' => true,
			'user' => UserInfo::newFromName( 'UTSysop', false ),
		) );
		$request->info2 = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider2,
			'id' => $id2,
			'persisted' => true,
		) );
		$session = $manager->getSessionForRequest( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id2, $session->getId() );
	}

	public function testGetSessionById() {
		$manager = $this->getManager();

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		try {
			$manager->getSessionById( 'bad' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage() );
		}

		// Unknown session ID
		$session = $manager->getSessionById( $id );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id, $session->getId() );

		$this->assertNull( $manager->getSessionById( $id, null, true ) );

		// Known but unloadable session ID
		$this->store->set( wfMemcKey( 'MWSession', 'metadata', $id ), array(
			'provider' => 'DummySessionProvider',
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
		) );

		try {
			$manager->getSessionById( $id );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Can neither load the session nor create an empty session',
				$ex->getMessage()
			);
		}

		$this->assertNull( $manager->getSessionById( $id, null, true ) );

		// Known session ID
		$this->store->set( wfMemcKey( 'MWSession', 'data', $id ), array() );
		$session = $manager->getSessionById( $id );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $id, $session->getId() );
	}

	public function testGetEmptySession() {
		$manager = $this->getManager();
		$request = new \FauxRequest();

		$providerBuilder = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods( array( 'provideSessionInfo', 'newSessionInfo', '__toString' ) );

		$expectId = null;
		$info = null;
		$provider = $providerBuilder->getMock();
		$provider->expects( $this->any() )->method( 'provideSessionInfo' )
			->will( $this->returnValue( null ) );
		$provider->expects( $this->any() )->method( 'newSessionInfo' )
			->with( $this->callback( function ( $id ) use ( &$expectId ) {
				return $id === $expectId;
			} ) )
			->will( $this->returnCallback( function () use ( &$info ) {
				return $info;
			} ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockProvider' ) );

		$this->config->set( 'SessionProviders', array(
			$this->objectCacheDef( $provider ),
		) );

		// No info
		$expectId = null;
		$info = null;
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
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => 'empty---------------------------',
			'persisted' => true,
		) );
		$session = $manager->getEmptySession();
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( 'empty---------------------------', $session->getId() );

		// Info, explicitly
		$expectId = 'expected------------------------';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $expectId,
			'persisted' => true,
		) );
		$session = $manager->getEmptySession( null, $expectId );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );
		$this->assertSame( $expectId, $session->getId() );

		// Wrong ID
		$expectId = 'expected------------------------';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => "un$expectId",
			'persisted' => true,
		) );
		try {
			$manager->getEmptySession( null, $expectId );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'MockProvider returned empty session info with a wrong id: ' .
					"un$expectId != $expectId",
				$ex->getMessage()
			);
		}

		// Wrong provider
		$expectId = null;
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $providerBuilder->getMock(),
			'id' => 'empty---------------------------',
			'persisted' => true,
		) );
		try {
			$manager->getEmptySession();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'MockProvider returned an empty session info for a different provider: ' . $info,
				$ex->getMessage()
			);
		}

		// Bad id
		try {
			$manager->getEmptySession( null, 'bad' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage() );
		}

		// Session already exists
		$expectId = 'expected------------------------';
		$this->store->set( wfMemcKey( 'MWSession', 'metadata', $expectId ), array(
			'provider' => 'MockProvider',
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
		) );
		try {
			$manager->getEmptySession( null, $expectId );
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
			'user' => UserInfo::newFromName( 'UTSysop', true ),
		) );
		$session1 = \TestingAccessWrapper::newFromObject(
			$manager->getSessionFromInfo( $info, $request )
		);
		$session2 = \TestingAccessWrapper::newFromObject(
			$manager->getSessionFromInfo( $info, $request )
		);

		$this->assertSame( $session1->backend, $session2->backend );
		$this->assertNotEquals( $session1->index, $session2->index );
	}

	public function testGenerateSessionId() {
		$manager = $this->getManager();

		$id = $manager->generateSessionId();
		$this->assertTrue( SessionManager::validateSessionId( $id ), "Generated ID: $id" );
	}

	public function testAutoCreateUser() {
		global $wgGroupPermissions, $wgMemc;

		$that = $this;

		// Ensure $wgMemc is useful
		if ( $wgMemc instanceof \EmptyBagOStuff ) {
			$this->setMwGlobals( 'wgMemc', new \HashBagOStuff() );
		}

		$this->stashMwGlobals( array( 'wgGroupPermissions' ) );
		$wgGroupPermissions['*']['createaccount'] = true;
		$wgGroupPermissions['*']['autocreateaccount'] = false;

		// Replace the global singleton with one configured for testing
		$manager = $this->getManager();
		$reset = SessionManager::setSingletonForTest( $manager );

		$session = SessionManager::getGlobalSession();

		// Can't create an already-existing user
		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$manager->autoCreateUser( $user );
		$this->assertSame( $id, $user->getId() );
		$this->assertSame( 'UTSysop', $user->getName() );

		// Sanity check that creation works at all
		$user = User::newFromName( 'UTSessionAutoCreate1' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$manager->autoCreateUser( $user );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate1', $user->getName() );
		$this->assertEquals(
			$user->getId(), User::idFromName( 'UTSessionAutoCreate1', User::READ_LATEST )
		);

		// Check lack of permissions
		$wgGroupPermissions['*']['createaccount'] = false;
		$wgGroupPermissions['*']['autocreateaccount'] = false;
		$user = User::newFromName( 'UTDoesNotExist' );
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$session->clear();

		// Check other permission
		$wgGroupPermissions['*']['createaccount'] = false;
		$wgGroupPermissions['*']['autocreateaccount'] = true;
		$user = User::newFromName( 'UTSessionAutoCreate2' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$manager->autoCreateUser( $user );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate2', $user->getName() );
		$this->assertEquals(
			$user->getId(), User::idFromName( 'UTSessionAutoCreate2', User::READ_LATEST )
		);

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
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		\ScopedCallback::consume( $reset2 );
		$session->clear();

		// Sanity check that creation still works
		$user = User::newFromName( 'UTSessionAutoCreate3' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$manager->autoCreateUser( $user );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSessionAutoCreate3', $user->getName() );
		$this->assertEquals(
			$user->getId(), User::idFromName( 'UTSessionAutoCreate3', User::READ_LATEST )
		);

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
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->setMwGlobals( array(
			'wgAuth' => $oldWgAuth,
		) );
		$session->clear();

		// Test prevention by wfReadOnly()
		$this->setMwGlobals( array(
			'wgReadOnly' => 'Because',
		) );
		$user = User::newFromName( 'UTDoesNotExist' );
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->setMwGlobals( array(
			'wgReadOnly' => false,
		) );
		$session->clear();

		// Test prevention by a previous session
		$session->set( 'MWSession::AutoCreateBlacklist', true );
		$user = User::newFromName( 'UTDoesNotExist' );
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$session->clear();

		// Test uncreatable name
		$user = User::newFromName( 'UTDoesNotExist@' );
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist@', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$session->clear();

		// Test AbortAutoAccount hook
		$mock = $this->getMock( 'stdClass', array( 'onAbortAutoAccount' ) );
		$mock->expects( $this->once() )->method( 'onAbortAutoAccount' )
			->will( $this->returnValue( false ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'AbortAutoAccount' => array( $mock ) ) );
		$user = User::newFromName( 'UTDoesNotExist' );
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'AbortAutoAccount' => array() ) );
		$session->clear();

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

		// Test for "exception backoff"
		$user = User::newFromName( 'UTDoesNotExist' );
		$backoffKey = wfMemcKey( 'MWSession', 'autocreate-failed', md5( $user->getName() ) );
		$wgMemc->set( $backoffKey, 1, 60 * 10 );
		$manager->autoCreateUser( $user );
		$this->assertSame( 0, $user->getId() );
		$this->assertNotSame( 'UTDoesNotExist', $user->getName() );
		$this->assertEquals( 0, User::idFromName( 'UTDoesNotExist', User::READ_LATEST ) );
		$wgMemc->delete( $backoffKey );
		$session->clear();

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
		$manager->autoCreateUser( $user );
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
	}

}
