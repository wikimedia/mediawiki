<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\SessionBackend
 */
class SessionBackendTest extends MediaWikiTestCase {
	const SESSIONID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	protected $manager;
	protected $config;
	protected $provider;
	protected $store;

	protected $onSessionMetadataCalled = false;

	/**
	 * Returns a non-persistent backend that thinks it has at least one session active
	 * @param User|null $user
	 */
	protected function getBackend( User $user = null ) {
		if ( !$this->config ) {
			$this->config = new \HashConfig();
			$this->manager = null;
		}
		if ( !$this->store ) {
			$this->store = new \HashBagOStuff();
			$this->manager = null;
		}

		$logger = new \Psr\Log\NullLogger();
		if ( !$this->manager ) {
			$this->manager = new SessionManager( array(
				'store' => $this->store,
				'logger' => $logger,
				'config' => $this->config,
			) );
		}

		if ( !$this->provider ) {
			$this->provider = new \DummySessionProvider();
		}
		$this->provider->setLogger( $logger );
		$this->provider->setConfig( $this->config );
		$this->provider->setManager( $this->manager );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'user' => UserInfo::newFromUser( $user ?: new User, true ),
		) );
		$id = new SessionId( $info->getId() );

		$backend = new SessionBackend( $id, $info, $this->store, 10 );
		$priv = \TestingAccessWrapper::newFromObject( $backend );
		$priv->persist = false;
		$priv->requests = array( 100 => new \FauxRequest() );

		\TestingAccessWrapper::newFromObject( $this->manager )->allSessionBackends = array(
			$backend->getId() => $backend,
		);
		\TestingAccessWrapper::newFromObject( $this->manager )->allSessionIds = array(
			$backend->getId() => $id,
		);
		\TestingAccessWrapper::newFromObject( $this->manager )->sessionProviders = array(
			(string)$this->provider => $this->provider,
		);

		return $backend;
	}

	public function testConstructor() {
		// Set variables
		$this->getBackend();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'user' => UserInfo::newFromName( 'UTSysop', false ),
		) );
		$id = new SessionId( $info->getId() );
		try {
			new SessionBackend( $id, $info, $this->store, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				"Refusing to create session for unauthenticated user {$info->getUser()}",
				$ex->getMessage()
			);
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => self::SESSIONID,
			'user' => UserInfo::newFromName( 'UTSysop', true ),
		) );
		$id = new SessionId( $info->getId() );
		try {
			new SessionBackend( $id, $info, $this->store, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Cannot create session without a provider', $ex->getMessage() );
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'user' => UserInfo::newFromName( 'UTSysop', true ),
		) );
		$id = new SessionId( '!' . $info->getId() );
		try {
			new SessionBackend( $id, $info, $this->store, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'SessionId and SessionInfo don\'t match',
				$ex->getMessage()
			);
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'user' => UserInfo::newFromName( 'UTSysop', true ),
		) );
		$id = new SessionId( $info->getId() );
		$backend = new SessionBackend( $id, $info, $this->store, 10 );
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertSame( $id, $backend->getSessionId() );
		$this->assertInstanceOf( 'User', $backend->getUser() );
		$this->assertSame( 'UTSysop', $backend->getUser()->getName() );
		$this->assertSame( $info->wasPersisted(), $backend->isPersistent() );
		$this->assertSame( $info->wasRemembered(), $backend->rememberUser() );
		$this->assertSame( $info->forceHTTPS(), $backend->forceHTTPS() );

		$expire = time() + 100;
		$this->store->set( wfMemcKey( 'MWSession', 'metadata', self::SESSIONID ),
			array( 'expires' => $expire ), 2 );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'forceHTTPS' => true,
		) );
		$id = new SessionId( $info->getId() );
		$backend = new SessionBackend( $id, $info, $this->store, 10 );
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertSame( $id, $backend->getSessionId() );
		$this->assertInstanceOf( 'User', $backend->getUser() );
		$this->assertTrue( $backend->getUser()->isAnon() );
		$this->assertSame( $info->wasPersisted(), $backend->isPersistent() );
		$this->assertSame( $info->wasRemembered(), $backend->rememberUser() );
		$this->assertSame( $info->forceHTTPS(), $backend->forceHTTPS() );
		$this->assertSame( $expire, \TestingAccessWrapper::newFromObject( $backend )->expires );
	}

	public function testSessionStuff() {
		$backend = $this->getBackend();
		$priv = \TestingAccessWrapper::newFromObject( $backend );
		$priv->requests = array(); // Remove dummy session

		$manager = \TestingAccessWrapper::newFromObject( $this->manager );

		$request1 = new \FauxRequest();
		$session1 = $backend->getSession( $request1 );
		$request2 = new \FauxRequest();
		$session2 = $backend->getSession( $request2 );

		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session1 );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session2 );
		$this->assertSame( 2, count( $priv->requests ) );

		$index = \TestingAccessWrapper::newFromObject( $session1 )->index;

		$this->assertSame( $request1, $backend->getRequest( $index ) );
		$this->assertSame( null, $backend->suggestLoginUsername( $index ) );
		$request1->setCookie( 'UserName', 'Example' );
		$this->assertSame( 'Example', $backend->suggestLoginUsername( $index ) );

		$session1 = null;
		$this->assertSame( 1, count( $priv->requests ) );
		$this->assertArrayHasKey( $backend->getId(), $manager->allSessionBackends );
		$this->assertSame( $backend, $manager->allSessionBackends[$backend->getId()] );
		try {
			$backend->getRequest( $index );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session index', $ex->getMessage() );
		}
		try {
			$backend->suggestLoginUsername( $index );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session index', $ex->getMessage() );
		}

		$session2 = null;
		$this->assertSame( 0, count( $priv->requests ) );
		$this->assertArrayNotHasKey( $backend->getId(), $manager->allSessionBackends );
		$this->assertArrayHasKey( $backend->getId(), $manager->allSessionIds );
	}

	public function testResetId() {
		$id = session_id();

		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistsSessionId' ) );
		$this->provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( false ) );
		$backend = $this->getBackend( User::newFromName( 'UTSysop' ) );
		$manager = \TestingAccessWrapper::newFromObject( $this->manager );
		$sessionId = $backend->getSessionId();
		$backend->resetId();
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertSame( $backend->getId(), $sessionId->getId() );
		$this->assertSame( $id, session_id() );
		$this->assertSame( $backend, $manager->allSessionBackends[self::SESSIONID] );

		$this->provider = null;
		$backend = $this->getBackend();
		$manager = \TestingAccessWrapper::newFromObject( $this->manager );
		$this->assertTrue( $this->provider->persistsSessionId(), 'sanity check' );
		$sessionId = $backend->getSessionId();
		$backend->resetId();
		$this->assertNotEquals( self::SESSIONID, $backend->getId() );
		$this->assertSame( $backend->getId(), $sessionId->getId() );
		$this->assertInternalType( 'array',
			$this->store->get( wfMemcKey( 'MWSession', 'data', $backend->getId() ) ) );
		$this->assertInternalType( 'array',
			$this->store->get( wfMemcKey( 'MWSession', 'metadata', $backend->getId() ) ) );
		$this->assertFalse( $this->store->get( wfMemcKey( 'MWSession', 'data', self::SESSIONID ) ) );
		$this->assertFalse( $this->store->get( wfMemcKey( 'MWSession', 'metadata', self::SESSIONID ) ) );
		$this->assertSame( $id, session_id() );
		$this->assertArrayNotHasKey( self::SESSIONID, $manager->allSessionBackends );
		$this->assertArrayHasKey( $backend->getId(), $manager->allSessionBackends );
		$this->assertSame( $backend, $manager->allSessionBackends[$backend->getId()] );
	}

	public function testPersist() {
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->once() )->method( 'persistSession' );
		$backend = $this->getBackend();
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$backend->save(); // This one shouldn't call $provider->persistSession()

		$backend->persist();
		$this->assertTrue( $backend->isPersistent(), 'sanity check' );
	}

	public function testRememberUser() {
		$backend = $this->getBackend();

		$remembered = $backend->rememberUser();
		$backend->setRememberUser( !$remembered );
		$this->assertNotEquals( $remembered, $backend->rememberUser() );
		$backend->setRememberUser( $remembered );
		$this->assertEquals( $remembered, $backend->rememberUser() );
	}

	public function testForceHTTPS() {
		$backend = $this->getBackend();

		$force = $backend->forceHTTPS();
		$backend->setForceHTTPS( !$force );
		$this->assertNotEquals( $force, $backend->forceHTTPS() );
		$backend->setForceHTTPS( $force );
		$this->assertEquals( $force, $backend->forceHTTPS() );
	}

	public function testSetUser() {
		$user = User::newFromName( 'UTSysop' );

		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistsUser' ) );
		$this->provider->expects( $this->any() )->method( 'persistsUser' )
			->will( $this->returnValue( false ) );
		$backend = $this->getBackend();
		$this->assertFalse( $backend->canSetUser() );
		try {
			$backend->setUser( $user );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Cannot set user on this session; check $session->canSetUser() first',
				$ex->getMessage()
			);
		}
		$this->assertNotSame( $user, $backend->getUser() );

		$this->provider = null;
		$backend = $this->getBackend();
		$this->assertTrue( $backend->canSetUser() );
		$this->assertNotSame( $user, $backend->getUser(), 'sanity check' );
		$backend->setUser( $user );
		$this->assertSame( $user, $backend->getUser() );
	}

	public function testDirty() {
		$backend = $this->getBackend();
		$priv = \TestingAccessWrapper::newFromObject( $backend );
		$priv->dataDirty = false;
		$backend->dirty();
		$this->assertTrue( $priv->dataDirty );
	}

	public function testGetData() {
		$backend = $this->getBackend();
		$data = $backend->getData();
		$this->assertSame( array(), $data );
		$this->assertTrue( \TestingAccessWrapper::newFromObject( $backend )->dataDirty );
		$data['???'] = '!!!';
		$this->assertSame( array( '???' => '!!!' ), $data );

		$testData = array( 'foo' => 'foo!', 'bar', array( 'baz', null ) );
		$this->store->set( wfMemcKey( 'MWSession', 'data', self::SESSIONID ), $testData );
		$backend = $this->getBackend();
		$this->assertSame( $testData, $backend->getData() );
		$this->assertFalse( \TestingAccessWrapper::newFromObject( $backend )->dataDirty );
	}

	public function testAddData() {
		$backend = $this->getBackend();
		$priv = \TestingAccessWrapper::newFromObject( $backend );

		$priv->data = array( 'foo' => 1 );
		$priv->dataDirty = false;
		$backend->addData( array( 'foo' => 1 ) );
		$this->assertSame( array( 'foo' => 1 ), $priv->data );
		$this->assertFalse( $priv->dataDirty );

		$priv->data = array( 'foo' => 1 );
		$priv->dataDirty = false;
		$backend->addData( array( 'foo' => '1' ) );
		$this->assertSame( array( 'foo' => '1' ), $priv->data );
		$this->assertTrue( $priv->dataDirty );

		$priv->data = array( 'foo' => 1 );
		$priv->dataDirty = false;
		$backend->addData( array( 'bar' => 2 ) );
		$this->assertSame( array( 'foo' => 1, 'bar' => 2 ), $priv->data );
		$this->assertTrue( $priv->dataDirty );
	}

	public function testSave() {
		$user = User::newFromName( 'UTSysop' );
		$this->store = new \HashBagOStuff();
		$dataKey = wfMemcKey( 'MWSession', 'data', self::SESSIONID );
		$metaKey = wfMemcKey( 'MWSession', 'metadata', self::SESSIONID );
		$testData = array( 'foo' => 'foo!', 'bar', array( 'baz', null ) );
		$this->store->set( $dataKey, $testData );

		$neverHook = $this->getMock( __CLASS__, array( 'onSessionMetadata' ) );
		$neverHook->expects( $this->never() )->method( 'onSessionMetadata' );

		$neverProvider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$neverProvider->expects( $this->never() )->method( 'persistSession' );

		// Not persistent or dirty
		$this->provider = $neverProvider;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $neverHook ) ) );
		$backend = $this->getBackend( $user );
		$backend->getData();
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->store->get( $metaKey ), 'making sure it didn\'t save' );

		// Not persistent, but dirty
		$this->provider = $neverProvider;
		$this->onSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$backend->getData();
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->dirty();
		$backend->save();
		$this->assertTrue( $this->onSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );

		// Persistent, not dirty
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $neverHook ) ) );
		$backend = $this->getBackend( $user );
		$backend->getData();
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->persist();
		$this->assertFalse( $this->store->get( $metaKey ), 'making sure it didn\'t save' );

		// Persistent and dirty
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->onSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$backend->getData();
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->dirty();
		$backend->persist();
		$this->assertTrue( $this->onSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );

		// Not marked dirty, but dirty data
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->onSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$backend->getData();
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataHash = 'Doesn\'t match';
		$backend->persist();
		$this->assertTrue( $this->onSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );
	}

	public function testRenew() {
		$user = User::newFromName( 'UTSysop' );
		$this->store = new \HashBagOStuff();
		$dataKey = wfMemcKey( 'MWSession', 'data', self::SESSIONID );
		$metaKey = wfMemcKey( 'MWSession', 'metadata', self::SESSIONID );
		$testData = array( 'foo' => 'foo!', 'bar', array( 'baz', null ) );
		$this->store->set( $dataKey, $testData );

		// Not persistent
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->never() )->method( 'persistSession' );
		$this->onSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->forcePersist = false;
		$backend->renew();
		$this->assertTrue( $this->onSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );

		// Persistent
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->onSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'SessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		\TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		\TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		\TestingAccessWrapper::newFromObject( $backend )->forcePersist = false;
		$backend->renew();
		$this->assertTrue( $this->onSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );
	}

	public function onSessionMetadata( SessionBackend $backend, array &$metadata, array $requests ) {
		$this->onSessionMetadataCalled = true;
		$metadata['???'] = '!!!';
	}

	public function testResetIdOfGlobalSession() {
		$backend = $this->getBackend( User::newFromName( 'UTSysop' ) );
		SessionManager::setSingletonForTest( $this->manager );

		$manager = \TestingAccessWrapper::newFromObject( $this->manager );
		$request = \RequestContext::getMain()->getRequest();
		$manager->globalSession = $backend->getSession( $request );
		$manager->globalSessionRequest = $request;

		session_id( self::SESSIONID );
		\MediaWiki\quietCall( 'session_start' );
		$backend->resetId();
		$this->assertNotEquals( self::SESSIONID, $backend->getId() );
		$this->assertSame( $backend->getId(), session_id() );
		session_write_close();

		session_id( '' );
		$this->assertNotSame( $backend->getId(), session_id(), 'sanity check' );
		$backend->persist();
		$this->assertSame( $backend->getId(), session_id() );
		session_write_close();
	}

}
