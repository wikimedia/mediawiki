<?php

namespace MediaWiki\Tests\Session;

use DummySessionProvider;
use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\MultiBackendSessionStore;
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
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\SessionBackend
 */
class MultiBackendSessionStoreTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;
	use SessionStoreTestTrait;

	private const SESSIONID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	/** @var SessionManager */
	protected $manager;

	/** @var Config */
	protected $config;

	/** @var SessionProvider */
	protected $provider;

	/** @var SessionStore */
	private $store;

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::AnonSessionCacheType => false,
			MainConfigNames::SessionCacheType => CACHE_DB,
		] );
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
			$this->store = new MultiBackendSessionStore(
				new TestBagOStuff(), new TestBagOStuff(), new NullLogger(), StatsFactory::newNull()
			);
		}

		$logger = new NullLogger();
		$hookContainer = $this->getServiceContainer()->getHookContainer();

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

	public function testSessionStoreBackendInUsage() {
		$sessionStore = $this->getServiceContainer()->getSessionStore();
		$this->assertInstanceOf( SingleBackendSessionStore::class, $sessionStore );

		// This should trigger a multi-backend store even though the cache type is
		// the same. TODO: Used for testing, but in the future, it should trigger a single
		// backend store instead.
		$this->overrideConfigValues( [
			MainConfigNames::AnonSessionCacheType => CACHE_DB,
			MainConfigNames::SessionCacheType => CACHE_DB,
		] );

		$sessionStore = $this->getServiceContainer()->getSessionStore();
		$this->assertInstanceOf( MultiBackendSessionStore::class, $sessionStore );

		// The cache types are different, and should now be a multi-backend store.
		$this->overrideConfigValues( [
			MainConfigNames::AnonSessionCacheType => CACHE_HASH,
			MainConfigNames::SessionCacheType => CACHE_DB,
		] );

		$sessionStore = $this->getServiceContainer()->getSessionStore();
		$this->assertInstanceOf( MultiBackendSessionStore::class, $sessionStore );
	}

	public function testSave() {
		$user = $this->getTestSysop()->getUser();
		$this->store = new MultiBackendSessionStore(
			new TestBagOStuff(),
			new TestBagOStuff(),
			new NullLogger(),
			StatsFactory::newNull()
		);
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
		$priv = TestingAccessWrapper::newFromObject( $this->store );
		// Ensure that we expire items so we don't find them when we look up
		[ $store, ] = $priv->getActiveStore( new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => self::SESSIONID ] ) );
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
		$priv = TestingAccessWrapper::newFromObject( $this->store );
		[ $store, ] = $priv->getActiveStore( new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => self::SESSIONID ] ) );
		$store->setMockTime( $timeNow );
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
		$user = $this->getTestSysop()->getUser();
		$this->store = new MultiBackendSessionStore(
			new TestBagOStuff(),
			new TestBagOStuff(),
			new NullLogger(),
			StatsFactory::newNull()
		);
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
}
