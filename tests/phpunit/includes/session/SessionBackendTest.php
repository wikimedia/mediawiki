<?php

namespace MediaWiki\Tests\Session;

use BadMethodCallException;
use DummySessionProvider;
use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\SessionStore;
use MediaWiki\Session\SingleBackendSessionStore;
use MediaWiki\Session\UserInfo;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use UnexpectedValueException;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\SessionBackend
 */
class SessionBackendTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	private const SESSIONID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	/** @var SessionManager */
	protected $manager;

	/** @var Config */
	protected $config;

	/** @var SessionProvider */
	protected $provider;

	/** @var SessionStore */
	private $store;

	/**
	 * @return HookContainer
	 */
	private function getHookContainer() {
		return $this->getServiceContainer()->getHookContainer();
	}

	/**
	 * Returns a non-persistent backend that thinks it has at least one session active
	 * @param User|null $user
	 * @param string|null $id
	 * @return SessionBackend
	 */
	protected function getBackend( ?User $user = null, $id = null ) {
		if ( !$this->config ) {
			$this->config = new HashConfig();
			$this->manager = null;
		}

		if ( !$this->store ) {
			$this->store = $this->getServiceContainer()->getSessionStore();
		}

		$logger = new NullLogger();
		$hookContainer = $this->getHookContainer();

		if ( !$this->manager ) {
			$this->manager = new SessionManager(
				$this->config,
				$logger,
				$this->getServiceContainer()->getCentralIdLookup(),
				$hookContainer,
				$this->getServiceContainer()->getObjectFactory(),
				$this->getServiceContainer()->getProxyLookup(),
				$this->getServiceContainer()->getUrlUtils(),
				$this->getServiceContainer()->getUserNameUtils(),
				$this->store
			);
		}

		if ( !$this->provider ) {
			$this->provider = new DummySessionProvider();
		}
		$this->initProvider( $this->provider, null, $this->config, $this->manager, $hookContainer );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => $id ?: self::SESSIONID,
			'persisted' => true,
			'userInfo' => UserInfo::newFromUser( $user ?: new User, true ),
			'idIsSafe' => true,
		] );
		$id = new SessionId( $info->getId() );

		$backend = new SessionBackend( $id, $info, $this->store, $logger, $hookContainer, 10 );
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->persist = false;
		$priv->requests = [ 100 => new FauxRequest() ];
		$priv->requests[100]->setSessionId( $id );
		$priv->usePhpSessionHandling = false;

		$manager = TestingAccessWrapper::newFromObject( $this->manager );
		$manager->allSessionBackends = [ $backend->getId() => $backend ] + $manager->allSessionBackends;
		$manager->sessionProviders = [ (string)$this->provider => $this->provider ];

		return $backend;
	}

	public function testConstructor() {
		$username = 'TestConstructor';
		// Set variables
		$this->getBackend();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( $username, false ),
			'idIsSafe' => true,
		] );
		$id = new SessionId( $info->getId() );
		$logger = new NullLogger();
		$hookContainer = $this->getHookContainer();
		try {
			new SessionBackend( $id, $info, $this->store, $logger, $hookContainer, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Refusing to create session for unverified user ' . $info->getUserInfo(),
				$ex->getMessage()
			);
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => self::SESSIONID,
			'userInfo' => UserInfo::newFromName( $username, true ),
			'idIsSafe' => true,
		] );
		$id = new SessionId( $info->getId() );
		try {
			new SessionBackend( $id, $info, $this->store, $logger, $hookContainer, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Cannot create session without a provider', $ex->getMessage() );
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( $username, true ),
			'idIsSafe' => true,
		] );
		$id = new SessionId( '!' . $info->getId() );
		try {
			new SessionBackend( $id, $info, $this->store, $logger, $hookContainer, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'SessionId and SessionInfo don\'t match',
				$ex->getMessage()
			);
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'userInfo' => UserInfo::newFromName( $username, true ),
			'idIsSafe' => true,
		] );
		$id = new SessionId( $info->getId() );
		$backend = new SessionBackend( $id, $info, $this->store, $logger, $hookContainer, 10 );
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertSame( $id, $backend->getSessionId() );
		$this->assertSame( $this->provider, $backend->getProvider() );
		$this->assertInstanceOf( User::class, $backend->getUser() );
		$this->assertSame( $username, $backend->getUser()->getName() );
		$this->assertSame( $info->wasPersisted(), $backend->isPersistent() );
		$this->assertSame( $info->wasRemembered(), $backend->shouldRememberUser() );
		$this->assertSame( $info->forceHTTPS(), $backend->shouldForceHTTPS() );

		$expire = time() + 100;
		$this->setSessionBlob( [ 'metadata' => [ 'expires' => $expire ] ], $info );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'forceHTTPS' => true,
			'metadata' => [ 'foo' ],
			'idIsSafe' => true,
		] );
		$id = new SessionId( $info->getId() );
		$backend = new SessionBackend( $id, $info, $this->store, $logger, $hookContainer, 10 );
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertSame( $id, $backend->getSessionId() );
		$this->assertSame( $this->provider, $backend->getProvider() );
		$this->assertInstanceOf( User::class, $backend->getUser() );
		$this->assertTrue( $backend->getUser()->isAnon() );
		$this->assertSame( $info->wasPersisted(), $backend->isPersistent() );
		$this->assertSame( $info->wasRemembered(), $backend->shouldRememberUser() );
		$this->assertSame( $info->forceHTTPS(), $backend->shouldForceHTTPS() );
		$this->assertSame( $expire, TestingAccessWrapper::newFromObject( $backend )->expires );
		$this->assertSame( [ 'foo' ], $backend->getProviderMetadata() );
	}

	private function setSessionBlob( array $blob, $info = null ) {
		$info ??= new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'idIsSafe' => true,
		] );

		$blob += [
			'data' => [],
			'metadata' => [],
		];
		$blob['metadata'] += [
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
			'provider' => 'DummySessionProvider',
		];

		$expiry = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::ObjectCacheSessionExpiry );
		$this->store->set( $info, $blob, $expiry );
	}

	public function testSessionStuff() {
		$backend = $this->getBackend();
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->requests = []; // Remove dummy session

		$manager = TestingAccessWrapper::newFromObject( $this->manager );

		$request1 = new FauxRequest();
		$session1 = $backend->getSession( $request1 );
		$request2 = new FauxRequest();
		$session2 = $backend->getSession( $request2 );

		$this->assertInstanceOf( Session::class, $session1 );
		$this->assertInstanceOf( Session::class, $session2 );
		$this->assertCount( 2, $priv->requests );

		$index = TestingAccessWrapper::newFromObject( $session1 )->index;

		$this->assertSame( $request1, $backend->getRequest( $index ) );
		$this->assertSame( null, $backend->suggestLoginUsername( $index ) );
		$request1->setCookie( 'UserName', 'Example' );
		$this->assertSame( 'Example', $backend->suggestLoginUsername( $index ) );

		$session1 = null;
		$this->assertCount( 1, $priv->requests );
		$this->assertArrayHasKey( $backend->getId(), $manager->allSessionBackends );
		$this->assertSame( $backend, $manager->allSessionBackends[$backend->getId()] );
		try {
			$backend->getRequest( $index );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session index', $ex->getMessage() );
		}
		try {
			$backend->suggestLoginUsername( $index );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session index', $ex->getMessage() );
		}

		$session2 = null;
		$this->assertSame( [], $priv->requests );
	}

	public function testSetProviderMetadata() {
		$backend = $this->getBackend();
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->providerMetadata = [ 'dummy' ];

		try {
			$backend->setProviderMetadata( 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( '$metadata must be an array or null', $ex->getMessage() );
		}

		try {
			$backend->setProviderMetadata( (object)[] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( '$metadata must be an array or null', $ex->getMessage() );
		}

		$this->assertFalse( $this->store->get( $priv->getSessionInfo() ) );
		$backend->setProviderMetadata( [ 'dummy' ] );
		$this->assertFalse( $this->store->get( $priv->getSessionInfo() ) );

		$this->assertFalse( $this->store->get( $priv->getSessionInfo() ) );
		$backend->setProviderMetadata( [ 'test' ] );
		$this->assertNotFalse( $this->store->get( $priv->getSessionInfo() ) );
		$this->assertSame( [ 'test' ], $backend->getProviderMetadata() );
		$this->store->delete( $priv->getSessionInfo() );

		$this->assertFalse( $this->store->get( $priv->getSessionInfo() ) );
		$backend->setProviderMetadata( null );
		$this->assertNotFalse( $this->store->get( $priv->getSessionInfo() ) );
		$this->assertSame( null, $backend->getProviderMetadata() );
		$this->deleteSession( self::SESSIONID );
	}

	public function testResetId() {
		$id = session_id();

		$builder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistsSessionId', 'sessionIdWasReset' ] );

		$this->provider = $builder->getMock();
		$this->provider->method( 'persistsSessionId' )
			->willReturn( false );
		$this->provider->expects( $this->never() )->method( 'sessionIdWasReset' );
		$backend = $this->getBackend( User::newFromName( 'TestResetId' ) );
		$manager = TestingAccessWrapper::newFromObject( $this->manager );
		$sessionId = $backend->getSessionId();
		$backend->resetId();
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertSame( $backend->getId(), $sessionId->getId() );
		$this->assertSame( $id, session_id() );
		$this->assertSame( $backend, $manager->allSessionBackends[self::SESSIONID] );

		$this->provider = $builder->getMock();
		$this->provider->method( 'persistsSessionId' )
			->willReturn( true );
		$backend = $this->getBackend();
		$this->provider->expects( $this->once() )->method( 'sessionIdWasReset' )
			->with( $this->identicalTo( $backend ), $this->identicalTo( self::SESSIONID ) );
		$manager = TestingAccessWrapper::newFromObject( $this->manager );
		$sessionId = $backend->getSessionId();
		$backend->resetId();
		$this->assertNotEquals( self::SESSIONID, $backend->getId() );
		$this->assertSame( $backend->getId(), $sessionId->getId() );
		$store = TestingAccessWrapper::newFromObject( $this->store )->store;
		$this->assertIsArray( $store->get( $store->makeKey( 'MWSession', $backend->getId() ) ) );
		$this->assertFalse( $this->getSession( self::SESSIONID ) );
		$this->assertSame( $id, session_id() );
		$this->assertArrayNotHasKey( self::SESSIONID, $manager->allSessionBackends );
		$this->assertArrayHasKey( $backend->getId(), $manager->allSessionBackends );
		$this->assertSame( $backend, $manager->allSessionBackends[$backend->getId()] );
	}

	public function testPersist() {
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistSession' ] )->getMock();
		$this->provider->expects( $this->once() )->method( 'persistSession' );
		$backend = $this->getBackend();
		$this->assertFalse( $backend->isPersistent() );
		$backend->save(); // This one shouldn't call $provider->persistSession()

		$backend->persist();
		$this->assertTrue( $backend->isPersistent() );

		$this->provider = null;
		$backend = $this->getBackend();
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$wrap->persist = true;
		$wrap->expires = 0;
		$backend->persist();
		$this->assertNotEquals( 0, $wrap->expires );
	}

	public function testUnpersist() {
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'unpersistSession' ] )->getMock();
		$this->provider->expects( $this->once() )->method( 'unpersistSession' );
		$backend = $this->getBackend();
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$wrap->persist = true;
		$wrap->dataDirty = true;
		$sessionStore = TestingAccessWrapper::newFromObject( $wrap->sessionStore );

		$backend->save(); // This one shouldn't call $provider->persistSession(), but should save
		$this->assertTrue( $backend->isPersistent() );
		$this->assertNotFalse( $sessionStore->get( $wrap->getSessionInfo() ) );

		$backend->unpersist();
		$this->assertFalse( $backend->isPersistent() );

		$this->assertNotFalse( $sessionStore->get( $wrap->getSessionInfo() ) );
	}

	public function testRememberUser() {
		$backend = $this->getBackend();

		$remembered = $backend->shouldRememberUser();
		$backend->setRememberUser( !$remembered );
		$this->assertNotEquals( $remembered, $backend->shouldRememberUser() );
		$backend->setRememberUser( $remembered );
		$this->assertEquals( $remembered, $backend->shouldRememberUser() );
	}

	public function testForceHTTPS() {
		$backend = $this->getBackend();

		$force = $backend->shouldForceHTTPS();
		$backend->setForceHTTPS( !$force );
		$this->assertNotEquals( $force, $backend->shouldForceHTTPS() );
		$backend->setForceHTTPS( $force );
		$this->assertEquals( $force, $backend->shouldForceHTTPS() );
	}

	public function testLoggedOutTimestamp() {
		$backend = $this->getBackend();

		$backend->setLoggedOutTimestamp( 42 );
		$this->assertSame( 42, $backend->getLoggedOutTimestamp() );
		$backend->setLoggedOutTimestamp( '123' );
		$this->assertSame( 123, $backend->getLoggedOutTimestamp() );
	}

	public function testSetUser() {
		$user = static::getTestSysop()->getUser();

		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'canChangeUser' ] )->getMock();
		$this->provider->method( 'canChangeUser' )
			->willReturn( false );
		$backend = $this->getBackend();
		$this->assertFalse( $backend->canSetUser() );
		try {
			$backend->setUser( $user );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame(
				'Cannot set user on this session; check $session->canSetUser() first',
				$ex->getMessage()
			);
		}
		$this->assertNotSame( $user, $backend->getUser() );

		$this->provider = null;
		$backend = $this->getBackend();
		$this->assertTrue( $backend->canSetUser() );
		$this->assertNotSame( $user, $backend->getUser() );
		$backend->setUser( $user );
		$this->assertSame( $user, $backend->getUser() );
	}

	public function testDirty() {
		$backend = $this->getBackend();
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->dataDirty = false;
		$backend->dirty();
		$this->assertTrue( $priv->dataDirty );
	}

	public function testGetData() {
		$backend = $this->getBackend();
		$data = $backend->getData();
		$this->assertSame( [], $data );
		$this->assertTrue( TestingAccessWrapper::newFromObject( $backend )->dataDirty );
		$data['???'] = '!!!';
		$this->assertSame( [ '???' => '!!!' ], $data );

		$testData = [ 'foo' => 'foo!', 'bar', [ 'baz', null ] ];
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend();
		$this->assertSame( $testData, $backend->getData() );
		$this->assertFalse( TestingAccessWrapper::newFromObject( $backend )->dataDirty );
	}

	public function testAddData() {
		$backend = $this->getBackend();
		$priv = TestingAccessWrapper::newFromObject( $backend );

		$priv->data = [ 'foo' => 1 ];
		$priv->dataDirty = false;
		$backend->addData( [ 'foo' => 1 ] );
		$this->assertSame( [ 'foo' => 1 ], $priv->data );
		$this->assertFalse( $priv->dataDirty );

		$priv->data = [ 'foo' => 1 ];
		$priv->dataDirty = false;
		$backend->addData( [ 'foo' => '1' ] );
		$this->assertSame( [ 'foo' => '1' ], $priv->data );
		$this->assertTrue( $priv->dataDirty );

		$priv->data = [ 'foo' => 1 ];
		$priv->dataDirty = false;
		$backend->addData( [ 'bar' => 2 ] );
		$this->assertSame( [ 'foo' => 1, 'bar' => 2 ], $priv->data );
		$this->assertTrue( $priv->dataDirty );
	}

	public function testDelaySave() {
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$backend = $this->getBackend();
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->persist = true;

		// Saves happen normally when no delay is in effect
		$sessionMetadataCalled = false;
		$priv->metaDirty = true;
		$backend->save();
		$this->assertTrue( $sessionMetadataCalled );

		$sessionMetadataCalled = false;
		$priv->metaDirty = true;
		$priv->autosave();
		$this->assertTrue( $sessionMetadataCalled );

		$delay = $backend->delaySave();

		// Autosave doesn't happen when delay is in effect
		$sessionMetadataCalled = false;
		$priv->metaDirty = true;
		$priv->autosave();
		$this->assertFalse( $sessionMetadataCalled );

		// Save still does happen when delay is in effect
		$priv->save();
		$this->assertTrue( $sessionMetadataCalled );

		// Save happens when delay is consumed
		$sessionMetadataCalled = false;
		$priv->metaDirty = true;
		ScopedCallback::consume( $delay );
		$this->assertTrue( $sessionMetadataCalled );

		// No save happens when there was no autosave during the delay
		$delay = $backend->delaySave();
		$sessionMetadataCalled = false;
		$priv->metaDirty = true;
		ScopedCallback::consume( $delay );
		$this->assertFalse( $sessionMetadataCalled );
		$priv->metaDirty = false;

		// Test multiple delays
		$delay1 = $backend->delaySave();
		$delay2 = $backend->delaySave();
		$delay3 = $backend->delaySave();
		$sessionMetadataCalled = false;
		$priv->metaDirty = true;
		$priv->autosave();
		$this->assertFalse( $sessionMetadataCalled );
		ScopedCallback::consume( $delay3 );
		$this->assertFalse( $sessionMetadataCalled );
		ScopedCallback::consume( $delay1 );
		$this->assertFalse( $sessionMetadataCalled );
		ScopedCallback::consume( $delay2 );
		$this->assertTrue( $sessionMetadataCalled );
	}

	private function deleteSession( $id ) {
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => $id,
			'persisted' => true,
			'idIsSafe' => true,
		] );

		$this->store->delete( $info );
	}

	private function getSession( $id ) {
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => $id ] );

		return $this->store->get( $info );
	}

	public function testSave() {
		$user = static::getTestSysop()->getUser();
		$this->store = new SingleBackendSessionStore( new TestBagOStuff() );
		$testData = [ 'foo' => 'foo!', 'bar', [ 'baz', null ] ];

		$builder = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistSession', 'unpersistSession' ] );

		$neverProvider = $builder->getMock();
		$neverProvider->expects( $this->never() )->method( 'persistSession' );
		$neverProvider->expects( $this->never() )->method( 'unpersistSession' );

		// Not persistent or dirty
		$this->provider = $neverProvider;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				self::fail( 'Unexpected call to hook SessionMetadata' );
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		$this->assertFalse( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );

		// (but does unpersist if forced)
		$this->provider = $builder->getMock();
		$this->provider->expects( $this->never() )->method( 'persistSession' );
		$this->provider->expects( $this->atLeastOnce() )->method( 'unpersistSession' );
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				self::fail( 'Unexpected call to hook SessionMetadata' );
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = false;
		TestingAccessWrapper::newFromObject( $backend )->forcePersist = true;
		$this->assertFalse( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );

		// (but not to a WebRequest associated with a different session)
		$this->provider = $neverProvider;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				self::fail( 'Unexpected call to hook SessionMetadata' );
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		TestingAccessWrapper::newFromObject( $backend )->requests[100]
			->setSessionId( new SessionId( 'x' ) );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = false;
		TestingAccessWrapper::newFromObject( $backend )->forcePersist = true;
		$this->assertFalse( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );

		// Not persistent, but dirty
		$this->provider = $neverProvider;
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		$this->assertFalse( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = true;
		$backend->save();
		$this->assertTrue( $sessionMetadataCalled );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );

		$timeNow = time() + 100;
		$store = TestingAccessWrapper::newFromObject( $this->store )->store;
		// Ensure that we expire items so we don't find them when we look up
		$store->setMockTime( $timeNow );
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save to backend' );

		// Persistent, not dirty
		$this->provider = $neverProvider;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				self::fail( 'Unexpected call to hook SessionMetadata' );
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );

		// (but will persist if forced)
		$this->provider = $builder->getMock();
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->provider->expects( $this->never() )->method( 'unpersistSession' );
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				self::fail( 'Unexpected call to hook SessionMetadata' );
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		TestingAccessWrapper::newFromObject( $backend )->forcePersist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );

		// Persistent and dirty
		$this->provider = $neverProvider;
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = true;
		$backend->save();
		$this->assertTrue( $sessionMetadataCalled );

		$timeNow = time() - 100;
		$store = TestingAccessWrapper::newFromObject( $this->store )->store;
		// Ensure that we don't expire items, so we find them when we look up
		$store->setMockTime( $timeNow );

		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob, 'making sure it did save to backend' );

		// (also persists if forced)
		$this->provider = $builder->getMock();
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->provider->expects( $this->never() )->method( 'unpersistSession' );
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		TestingAccessWrapper::newFromObject( $backend )->forcePersist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = true;
		$backend->save();
		$this->assertTrue( $sessionMetadataCalled );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob, 'making sure it did save to backend' );

		// (also persists if metadata dirty)
		$this->provider = $builder->getMock();
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->provider->expects( $this->never() )->method( 'unpersistSession' );
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = true;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertTrue( $sessionMetadataCalled );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob, 'making sure it did save to backend' );

		// Not marked dirty, but dirty data
		// (e.g. indirect modification from ArrayAccess::offsetGet)
		$this->provider = $neverProvider;
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataHash = 'Doesn\'t match';
		$backend->save();
		$this->assertTrue( $sessionMetadataCalled );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$blob = $this->getSession( self::SESSIONID );
		$this->assertIsArray( $blob, 'making sure it did save to backend' );

		// Bad hook
		$this->provider = null;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				$metadata['userId']++;
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$backend->dirty();
		try {
			$backend->save();
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'SessionMetadata hook changed metadata key "userId"',
				$ex->getMessage()
			);
		}

		// SessionManager::preventSessionsForUser
		TestingAccessWrapper::newFromObject( $this->manager )->preventUsers = [
			$user->getName() => true,
		];
		$this->provider = $neverProvider;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) {
				self::fail( 'Unexpected call to hook SessionMetadata' );
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = true;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = true;
		$backend->save();
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );
	}

	public function testRenew() {
		$user = static::getTestSysop()->getUser();
		$this->store = new SingleBackendSessionStore( new TestBagOStuff() );
		$testData = [ 'foo' => 'foo!', 'bar', [ 'baz', null ] ];

		// Not persistent, expiring
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistSession' ] )->getMock();
		$this->provider->expects( $this->never() )->method( 'persistSession' );
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$sessionStore = TestingAccessWrapper::newFromObject( $wrap->sessionStore );
		$this->assertFalse( $backend->isPersistent() );
		$wrap->metaDirty = false;
		$wrap->dataDirty = false;
		$wrap->forcePersist = false;
		$wrap->expires = 0;
		$backend->renew();
		$this->assertTrue( $sessionMetadataCalled );
		$blob = $sessionStore->get( $wrap->getSessionInfo(), self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->assertNotEquals( 0, $wrap->expires );

		// Persistent, not expiring
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistSession' ] )->getMock();
		$this->provider->expects( $this->never() )->method( 'persistSession' );
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$wrap->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		$wrap->metaDirty = false;
		$wrap->dataDirty = false;
		$wrap->forcePersist = false;
		$expires = time() + $wrap->lifetime + 100;
		$wrap->expires = $expires;
		$backend->renew();
		$this->assertFalse( $sessionMetadataCalled );
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );
		$this->assertEquals( $expires, $wrap->expires );

		// Persistent, expiring
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistSession' ] )->getMock();
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$wrap->persist = true;
		$this->assertTrue( $backend->isPersistent() );
		$wrap->metaDirty = false;
		$wrap->dataDirty = false;
		$wrap->forcePersist = false;
		$wrap->expires = 0;
		$backend->renew();
		$this->assertTrue( $sessionMetadataCalled );
		$blob = $sessionStore->get( $wrap->getSessionInfo(), self::SESSIONID );
		$this->assertIsArray( $blob );
		$this->assertArrayHasKey( 'metadata', $blob );
		$metadata = $blob['metadata'];
		$this->assertIsArray( $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->assertNotEquals( 0, $wrap->expires );

		// Not persistent, not expiring
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'persistSession' ] )->getMock();
		$this->provider->expects( $this->never() )->method( 'persistSession' );
		$sessionMetadataCalled = false;
		$this->setTemporaryHook( 'SessionMetadata',
			static function ( SessionBackend $backend, array &$metadata, array $requests ) use ( &$sessionMetadataCalled ) {
				$sessionMetadataCalled = true;
				$metadata['???'] = '!!!';
			}
		);
		$this->setSessionBlob( [ 'data' => $testData ] );
		$backend = $this->getBackend( $user );
		$this->deleteSession( self::SESSIONID );
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$this->assertFalse( $backend->isPersistent() );
		$wrap->metaDirty = false;
		$wrap->dataDirty = false;
		$wrap->forcePersist = false;
		$expires = time() + $wrap->lifetime + 100;
		$wrap->expires = $expires;
		$backend->renew();
		$this->assertFalse( $sessionMetadataCalled );
		$this->assertFalse( $this->getSession( self::SESSIONID ), 'making sure it didn\'t save' );
		$this->assertEquals( $expires, $wrap->expires );
	}

	private function ensurePHPSessionHandlerEnabled(): ?ScopedCallback {
		$scope = null;
		if ( !PHPSessionHandler::isEnabled() ) {
			$staticAccess = TestingAccessWrapper::newFromClass( PHPSessionHandler::class );
			$handler = TestingAccessWrapper::newFromObject( $staticAccess->instance );
			$scope = new ScopedCallback( static function () use ( $handler ) {
				session_write_close();
				$handler->enable = false;
			} );
			$handler->enable = true;
		}
		return $scope;
	}

	public function testTakeOverGlobalSession() {
		$scope = $this->ensurePHPSessionHandlerEnabled();

		$backend = $this->getBackend( static::getTestSysop()->getUser() );
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = true;

		$this->setService( 'SessionManager', $this->manager );
		PHPSessionHandler::install( $this->manager );

		$manager = TestingAccessWrapper::newFromObject( $this->manager );
		$request = RequestContext::getMain()->getRequest();

		session_id( '' );
		TestingAccessWrapper::newFromObject( $backend )->checkPHPSession();
		$this->assertSame( $backend->getId(), session_id() );
		session_write_close();

		$backend2 = $this->getBackend(
			User::newFromName( 'TestTakeOverGlobalSession' ), 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb'
		);
		TestingAccessWrapper::newFromObject( $backend2 )->usePhpSessionHandling = true;

		session_id( '' );
		TestingAccessWrapper::newFromObject( $backend2 )->checkPHPSession();
		$this->assertSame( '', session_id() );
	}

	public function testResetIdOfGlobalSession() {
		$scope = $this->ensurePHPSessionHandlerEnabled();

		$backend = $this->getBackend( User::newFromName( 'TestResetIdOfGlobalSession' ) );
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = true;

		$this->setService( 'SessionManager', $this->manager );
		PHPSessionHandler::install( $this->manager );

		$manager = TestingAccessWrapper::newFromObject( $this->manager );
		$request = RequestContext::getMain()->getRequest();

		session_id( self::SESSIONID );
		@session_start();
		$_SESSION['foo'] = __METHOD__;
		$backend->resetId();
		$this->assertNotEquals( self::SESSIONID, $backend->getId() );
		$this->assertSame( $backend->getId(), session_id() );
		$this->assertArrayHasKey( 'foo', $_SESSION );
		$this->assertSame( __METHOD__, $_SESSION['foo'] );
		session_write_close();
	}

	public function testUnpersistOfGlobalSession() {
		$scope = $this->ensurePHPSessionHandlerEnabled();

		$backend = $this->getBackend( User::newFromName( 'TestUnpersistOfGlobalSession' ) );
		$wrap = TestingAccessWrapper::newFromObject( $backend );
		$wrap->usePhpSessionHandling = true;
		$wrap->persist = true;

		$this->setService( 'SessionManager', $this->manager );
		PHPSessionHandler::install( $this->manager );

		$manager = TestingAccessWrapper::newFromObject( $this->manager );
		$request = RequestContext::getMain()->getRequest();

		session_id( self::SESSIONID . 'x' );
		@session_start();
		$backend->unpersist();
		$this->assertSame( self::SESSIONID . 'x', session_id() );
		session_write_close();

		session_id( self::SESSIONID );
		$wrap->persist = true;
		$backend->unpersist();
		$this->assertSame( '', session_id() );
	}

	public function testGetAllowedUserRights() {
		$this->provider = $this->getMockBuilder( DummySessionProvider::class )
			->onlyMethods( [ 'getAllowedUserRights' ] )
			->getMock();
		$this->provider->method( 'getAllowedUserRights' )
			->willReturn( [ 'foo', 'bar' ] );

		$backend = $this->getBackend();
		$this->assertSame( [ 'foo', 'bar' ], $backend->getAllowedUserRights() );
	}

}
