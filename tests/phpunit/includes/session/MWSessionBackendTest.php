<?php

/**
 * @group Session
 * @group Database
 * @covers MWSessionBackend
 * @uses MWSessionManager
 * @uses MWSessionProvider
 * @uses MWSessionInfo
 * @uses MWSessionUserInfo
 */
class MWSessionBackendTest extends MediaWikiTestCase {
	const SESSIONID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	protected $config;
	protected $provider;
	protected $store;

	protected $onMWSessionMetadataCalled = false;

	/**
	 * Returns a non-persistent backend that thinks it has at least one session active
	 * @param User|null $user
	 */
	protected function getBackend( User $user = null ) {
		if ( !$this->config ) {
			$this->config = RequestContext::getMain()->getConfig();
		}
		if ( !$this->store ) {
			$this->store = new HashBagOStuff();
		}

		$logger = new Psr\Log\NullLogger();
		$manager = new MWSessionManager( array(
			'store' => $this->store,
			'logger' => $logger,
			'config' => $this->config,
			'request' => new FauxRequest,
		) );

		if ( !$this->provider ) {
			$this->provider = new DummySessionProvider();
		}
		$this->provider->setLogger( $logger );
		$this->provider->setConfig( $this->config );
		$this->provider->setManager( $manager );

		$info = new MWSessionInfo( MWSessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'user' => MWSessionUserInfo::newFromUser( $user ?: new User, true ),
		) );

		$backend = new MWSessionBackend( $info, $this->store, 10 );
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->persist = false;
		$priv->requests = array( 100 => new FauxRequest() );
		return $backend;
	}

	public function testConstructor() {
		// Set variables
		$this->getBackend();

		$info = new MWSessionInfo( MWSessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'user' => MWSessionUserInfo::newFromName( 'UTSysop', false ),
		) );
		try {
			new MWSessionBackend( $info, $this->store, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				"Refusing to create session for unauthenticated user {$info->getUser()}",
				$ex->getMessage()
			);
		}

		$info = new MWSessionInfo( MWSessionInfo::MIN_PRIORITY, array(
			'id' => self::SESSIONID,
			'user' => MWSessionUserInfo::newFromName( 'UTSysop', true ),
		) );
		try {
			new MWSessionBackend( $info, $this->store, 10 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Cannot create session without a provider', $ex->getMessage() );
		}

		$info = new MWSessionInfo( MWSessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'user' => MWSessionUserInfo::newFromName( 'UTSysop', true ),
		) );
		$backend = new MWSessionBackend( $info, $this->store, 10 );
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertInstanceOf( 'User', $backend->getUser() );
		$this->assertSame( 'UTSysop', $backend->getUser()->getName() );
		$this->assertSame( $info->wasPersisted(), $backend->isPersistent() );
		$this->assertSame( $info->wasRemembered(), $backend->rememberUser() );
		$this->assertSame( $info->forceHTTPS(), $backend->forceHTTPS() );

		$info = new MWSessionInfo( MWSessionInfo::MIN_PRIORITY, array(
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'forceHTTPS' => true,
		) );
		$backend = new MWSessionBackend( $info, $this->store, 10 );
		$this->assertSame( self::SESSIONID, $backend->getId() );
		$this->assertInstanceOf( 'User', $backend->getUser() );
		$this->assertTrue( $backend->getUser()->isAnon() );
		$this->assertSame( $info->wasPersisted(), $backend->isPersistent() );
		$this->assertSame( $info->wasRemembered(), $backend->rememberUser() );
		$this->assertSame( $info->forceHTTPS(), $backend->forceHTTPS() );
	}

	/**
	 * @uses MWSession
	 */
	public function testSessionStuff() {
		$backend = $this->getBackend();
		$priv = TestingAccessWrapper::newFromObject( $backend );
		$priv->requests = array(); // Remove dummy session

		$manager = TestingAccessWrapper::newFromObject( $this->provider->getManager() );
		$manager->allSessionBackends = array( $backend->getId() => $backend );

		$request1 = new FauxRequest();
		$session1 = $backend->getSession( $request1 );
		$request2 = new FauxRequest();
		$session2 = $backend->getSession( $request2 );

		$this->assertInstanceOf( 'MWSession', $session1 );
		$this->assertInstanceOf( 'MWSession', $session2 );
		$this->assertSame( 2, count( $priv->requests ) );

		$index = TestingAccessWrapper::newFromObject( $session1 )->index;

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
		$this->assertSame( 0, count( $priv->requests ) );
		$this->assertArrayNotHasKey( $backend->getId(), $manager->allSessionBackends );
	}

	public function testResetId() {
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistsSessionId' ) );
		$this->provider->expects( $this->any() )->method( 'persistsSessionId' )->willReturn( false );
		$backend = $this->getBackend( User::newFromName( 'UTSysop' ) );
		$backend->resetId();
		$this->assertSame( self::SESSIONID, $backend->getId() );

		$this->provider = null;
		$backend = $this->getBackend();
		$this->assertTrue( $this->provider->persistsSessionId(), 'sanity check' );
		$backend->resetId();
		$this->assertNotEquals( self::SESSIONID, $backend->getId() );
		$this->assertInternalType( 'array',
			$this->store->get( wfMemcKey( 'MWSession', 'data', $backend->getId() ) ) );
		$this->assertInternalType( 'array',
			$this->store->get( wfMemcKey( 'MWSession', 'metadata', $backend->getId() ) ) );
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
		$this->provider->expects( $this->any() )->method( 'persistsUser' )->willReturn( false );
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
		$this->assertNotSame( $user, $backend->getUser(), 'sanity check' );
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
		$this->assertSame( array(), $data );
		$this->assertTrue( TestingAccessWrapper::newFromObject( $backend )->dataDirty );
		$data['???'] = '!!!';
		$this->assertSame( array( '???' => '!!!' ), $data );

		$testData = array( 'foo' => 'foo!', 'bar', array( 'baz', null ) );
		$this->store->set( wfMemcKey( 'MWSession', 'data', self::SESSIONID ), $testData );
		$backend = $this->getBackend();
		$this->assertSame( $testData, $backend->getData() );
		$this->assertFalse( TestingAccessWrapper::newFromObject( $backend )->dataDirty );
	}

	public function testSave() {
		$user = User::newFromName( 'UTSysop' );
		$this->store = new HashBagOStuff();
		$dataKey = wfMemcKey( 'MWSession', 'data', self::SESSIONID );
		$metaKey = wfMemcKey( 'MWSession', 'metadata', self::SESSIONID );
		$testData = array( 'foo' => 'foo!', 'bar', array( 'baz', null ) );
		$this->store->set( $dataKey, $testData );

		$neverHook = $this->getMock( __CLASS__, array( 'onMWSessionMetadata' ) );
		$neverHook->expects( $this->never() )->method( 'onMWSessionMetadata' );

		$neverProvider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$neverProvider->expects( $this->never() )->method( 'persistSession' );

		// Not persistent or dirty
		$this->provider = $neverProvider;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'MWSessionMetadata' => array( $neverHook ) ) );
		$backend = $this->getBackend( $user );
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->save();
		$this->assertFalse( $this->store->get( $metaKey ), 'making sure it didn\'t save' );

		// Not persistent, but dirty
		$this->provider = $neverProvider;
		$this->onMWSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'MWSessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->dirty();
		$backend->save();
		$this->assertTrue( $this->onMWSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );

		// Persistent, not dirty
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'MWSessionMetadata' => array( $neverHook ) ) );
		$backend = $this->getBackend( $user );
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->persist();
		$this->assertFalse( $this->store->get( $metaKey ), 'making sure it didn\'t save' );

		// Persistent and dirty
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->onMWSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'MWSessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		$backend->dirty();
		$backend->persist();
		$this->assertTrue( $this->onMWSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );
	}

	public function testRenew() {
		$user = User::newFromName( 'UTSysop' );
		$this->store = new HashBagOStuff();
		$dataKey = wfMemcKey( 'MWSession', 'data', self::SESSIONID );
		$metaKey = wfMemcKey( 'MWSession', 'metadata', self::SESSIONID );
		$testData = array( 'foo' => 'foo!', 'bar', array( 'baz', null ) );
		$this->store->set( $dataKey, $testData );

		// Not persistent
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->never() )->method( 'persistSession' );
		$this->onMWSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'MWSessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		$this->assertFalse( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->forcePersist = false;
		$backend->renew();
		$this->assertTrue( $this->onMWSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );

		// Persistent
		$this->provider = $this->getMock( 'DummySessionProvider', array( 'persistSession' ) );
		$this->provider->expects( $this->atLeastOnce() )->method( 'persistSession' );
		$this->onMWSessionMetadataCalled = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'MWSessionMetadata' => array( $this ) ) );
		$backend = $this->getBackend( $user );
		TestingAccessWrapper::newFromObject( $backend )->persist = true;
		$this->assertTrue( $backend->isPersistent(), 'sanity check' );
		$this->assertFalse( $this->store->get( $metaKey ), 'sanity check' );
		TestingAccessWrapper::newFromObject( $backend )->metaDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->dataDirty = false;
		TestingAccessWrapper::newFromObject( $backend )->forcePersist = false;
		$backend->renew();
		$this->assertTrue( $this->onMWSessionMetadataCalled );
		$metadata = $this->store->get( $metaKey );
		$this->assertInternalType( 'array', $metadata );
		$this->assertArrayHasKey( '???', $metadata );
		$this->assertSame( '!!!', $metadata['???'] );
		$this->store->delete( $metaKey );
	}

	public function onMWSessionMetadata( MWSessionBackend $backend, array &$metadata, array $requests ) {
		$this->onMWSessionMetadataCalled = true;
		$metadata['???'] = '!!!';
	}

}
