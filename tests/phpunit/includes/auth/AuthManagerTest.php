<?php

/**
 * @group AuthManager
 * @group Database
 * @covers AuthManager
 * @uses AuthnSession
 * @uses AbstractAuthnSessionProvider
 */
class AuthManagerTest extends MediaWikiTestCase {
	protected $request;
	protected $config;
	protected $sessionStore;

	protected $sessionMocks = array();
	protected $preauthMocks = array();
	protected $primaryauthMocks = array();
	protected $secondaryauthMocks = array();

	protected $manager;
	protected $managerPriv;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array( 'wgAuth' => null ) );

		$this->sessionStore = new HashBagOStuff();
		ObjectCache::$instances['testSessionStore'] = $this->sessionStore;
		$this->request = new FauxRequest();
		$this->config = new HashConfig( array() );

		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( $this->mockSession( 'generic dummy session', 1 ) );
		$this->sessionMocks[] = $provider;

		/// @todo: Put together some sort of mock AuthnSessionProvider that
		// returns a mock AuthnSession that supports basic getting/setting of
		// user info, since lots depends on that.
		// https://phpunit.de/manual/current/en/test-doubles.html might help there.
	}

	/**
	 * Session-maker
	 * @param string $key
	 * @param int $priority
	 * @param array $info
	 * @param array $methods
	 * @return AuthnSession mock
	 */
	public function mockSession( $key, $priority, $info = null, $methods = array() ) {
		if ( $info === null ) {
			$info = array( 0, null, null );
		}
		$session = $this->getMockBuilder( 'AuthnSession' )
			->setConstructorArgs(
				array( $this->sessionStore, new Psr\Log\NullLogger(), $key, $priority )
			)
			->setMethods( array_merge( $methods, array(
				'canSetSessionUserInfo',
				'setupPHPSessionHandler',
			) ) )
			->getMockForAbstractClass();
		$session->method( 'getSessionUserInfo' )
			->will( $this->returnCallback( function () use ( &$info ) { return $info; } ) );
		$session->method( 'canSetSessionUserInfo' )
			->willReturn( true );
		$session->method( 'setSessionUserInfo' )
			->will( $this->returnCallback( function ( $id, $name, $token, $req ) use ( &$info ) {
				$info = array( $id, $name, $token );
			} ) );

		// PHPUnit b0rks sessions
		$session->method( 'setupPHPSessionHandler' )
			->will( $this->returnCallback( function () {} ) );

		return $session;
	}


	/**
	 * Initialize the AuthManagerConfig variable in $this->config
	 *
	 * Uses data from the various 'mocks' fields.
	 */
	protected function initializeConfig() {
		$config = array(
			'sessionstore' => array(
				'type' => 'testSessionStore',
			),
			'session' => array(
			),
			'preauth' => array(
			),
			'primaryauth' => array(
			),
			'secondaryauth' => array(
			),
			'logger' => null,
		);

		foreach ( array( 'session', 'preauth', 'primaryauth', 'secondaryauth' ) as $type ) {
			$key = $type . 'Mocks';
			foreach ( $this->$key as $mock ) {
				$config[$type][] = array(
					'factory' => function () use ( $mock ) { return $mock; },
				);
			}
		}

		$this->config->set( 'AuthManagerConfig', $config );
		$this->config->set( 'SessionsInObjectCache', true );
		$this->config->set( 'ObjectCacheSessionExpiry', 3600 );
		$this->config->set( 'LanguageCode', 'en' );
	}

	/**
	 * Initialize $this->manager
	 * @param bool $regen Force a call to $this->initializeConfig()
	 */
	protected function initializeManager( $regen = false ) {
		if ( $regen || !$this->config->has( 'AuthManagerConfig' ) ) {
			$this->initializeConfig();
		}
		$this->manager = new AuthManager( $this->request, $this->config );
		$this->managerPriv = TestingAccessWrapper::newFromObject( $this->manager );
	}

	public function testBasics() {
		$singleton = AuthManager::singleton();
		$this->assertInstanceOf( 'AuthManager', AuthManager::singleton() );
		$this->assertSame( $singleton, AuthManager::singleton() );
		$this->assertSame( RequestContext::getMain()->getRequest(), $singleton->getRequest() );
		$this->assertSame(
			RequestContext::getMain()->getConfig(),
			TestingAccessWrapper::newFromObject( $singleton )->config
		);

		$this->initializeManager();
		$this->assertInstanceOf( 'AuthManager', $this->manager );
		$this->assertSame( $this->request, $this->manager->getRequest() );
		$this->assertSame( $this->config, $this->managerPriv->config );
	}

	/**
	 * @uses NullAuthnSession
	 */
	public function testGetSession() {
		$this->initializeManager();

		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();
		$token = $this->managerPriv->getUserToken( $id, $name );

		// Get a generic session
		$session = $this->manager->getSession();
		$this->assertInstanceOf( 'AuthnSession', $session );
		$this->assertEquals( 'generic dummy session', $session->getSessionKey() );

		// A provider that doesn't return a session
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( null );
		$this->sessionMocks = array( $provider );
		$this->initializeManager( true );
		wfSuppressWarnings(); // Warning about headers already having been sent
		$session = $this->manager->getSession();
		wfRestoreWarnings();
		$this->assertInstanceOf( 'NullAuthnSession', $session );

		// A provider that returns an invalid session, then an empty one
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->expects( $this->exactly( 2 ) )
			->method( 'provideAuthnSession' )
			->withConsecutive(
				array( $this->anything() ),
				array( $this->anything(), $this->equalTo( true ) )
			)
			->will( $this->onConsecutiveCalls(
				$this->mockSession( 'invalid session', 2, array( $id, $name, 'Bogus' ) ),
				$this->mockSession( 'empty session', 2 )
			) );
		$this->sessionMocks = array( $provider );
		$this->initializeManager( true );
		$session = $this->manager->getSession();
		$this->assertInstanceOf( 'AuthnSession', $session );
		$this->assertEquals( 'empty session', $session->getSessionKey() );

		// A provider that returns a valid logged-in session
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->expects( $this->once() )
			->method( 'provideAuthnSession' )
			->willReturn(
				$this->mockSession( 'valid session', 2, array( $id, $name, $token ) )
			);
		$this->sessionMocks = array( $provider );
		$this->initializeManager( true );
		$session = $this->manager->getSession();
		$this->assertInstanceOf( 'AuthnSession', $session );
		$this->assertEquals( 'valid session', $session->getSessionKey() );

		// A provider that returns an invalid session, then no session
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->expects( $this->exactly( 2 ) )
			->method( 'provideAuthnSession' )
			->withConsecutive(
				array( $this->anything() ),
				array( $this->anything(), $this->equalTo( true ) )
			)
			->will( $this->onConsecutiveCalls(
				$this->mockSession( 'invalid session', 2, array( 1, 'Test', 'Bogus' ) ),
				null
			) );
		$this->sessionMocks = array( $provider );
		$this->initializeManager( true );
		wfSuppressWarnings(); // Warning about headers already having been sent
		$session = $this->manager->getSession();
		wfRestoreWarnings();
		$this->assertInstanceOf( 'NullAuthnSession', $session );

		// Priority
		$this->sessionMocks = array();
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( $this->mockSession( 'session #1', 1 ) );
		$this->sessionMocks[] = $provider;
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( $this->mockSession( 'session #3', 3 ) );
		$this->sessionMocks[] = $provider;
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( $this->mockSession( 'session #2', 2 ) );
		$this->sessionMocks[] = $provider;
		$this->initializeManager( true );
		$session = $this->manager->getSession();
		$this->assertInstanceOf( 'AuthnSession', $session );
		$this->assertEquals( 'session #3', $session->getSessionKey() );

		// Priority conflict
		$this->sessionMocks = array();
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( $this->mockSession( 'session A', 1 ) );
		$provider->method( 'describeSessions' )->willReturn( 'AAA' );
		$this->sessionMocks[] = $provider;
		$provider = $this->getMockForAbstractClass( 'AbstractAuthnSessionProvider' );
		$provider->method( 'provideAuthnSession' )
			->willReturn( $this->mockSession( 'session B', 1 ) );
		$provider->method( 'describeSessions' )->willReturn( 'BBB' );
		$this ->sessionMocks[] = $provider;
		$this->initializeManager( true );
		try {
			$this->manager->getSession();
			$this->fail( "Expected exception not thrown" );
		} catch ( HttpError $ex ) {
			$this->assertEquals( 400, $ex->getStatusCode() );
		}
	}

	public function testResetSessionId() {
		$this->initializeManager();

		$session = $this->mockSession( 'dummy', 1, null, array( 'resetSessionKey' ) );
		$session->expects( $this->once() )
			->method( 'resetSessionKey' );
		$this->managerPriv->session = $session;
		$this->manager->resetSessionId();
	}

	public function testPersistSession() {
		$this->initializeManager();

		$session = $this->mockSession( 'dummy', 1, null, array( 'resetSessionKey' ) );
		TestingAccessWrapper::newFromObject( $session )->key = null;
		$session->expects( $this->once() )
			->method( 'resetSessionKey' );
		$this->managerPriv->session = $session;
		$this->manager->persistSession();

		$session = $this->mockSession( 'dummy', 1, null, array( 'resetSessionKey' ) );
		$session->expects( $this->never() )
			->method( 'resetSessionKey' );
		$this->managerPriv->session = $session;
		$this->manager->persistSession();
	}

	public function testGetVaryHeaders() {
		$builder = $this->getMockBuilder( 'AbstractAuthnSessionProvider' )
			->setMethods( array( 'getVaryHeaders' ) );

		$provider = $builder->getMockForAbstractClass();
		$provider->method( 'getVaryHeaders' )
			->willReturn( array( 'Foo' => null, 'Bar' => array( 'a' => 1 ) ) );
		$this->sessionMocks[] = $provider;
		$provider = $builder->getMockForAbstractClass();
		$provider->method( 'getVaryHeaders' )
			->willReturn( array( 'Bar' => array( 'b' => 2 ) ) );
		$this->sessionMocks[] = $provider;
		$this->initializeManager();

		$this->assertEquals( array(
			'Foo' => array(),
			'Bar' => array( 'a' => 1, 'b' => 2 ),
		), $this->manager->getVaryHeaders() );
	}

	public function testGetVaryCookies() {
		$builder = $this->getMockBuilder( 'AbstractAuthnSessionProvider' )
			->setMethods( array( 'getVaryCookies' ) );

		$provider = $builder->getMockForAbstractClass();
		$provider->method( 'getVaryCookies' )
			->willReturn( array( 'Foo', 'Bar' ) );
		$this->sessionMocks[] = $provider;
		$provider = $builder->getMockForAbstractClass();
		$provider->method( 'getVaryCookies' )
			->willReturn( array( 'Foo', 'Baz' ) );
		$this->sessionMocks[] = $provider;
		$this->initializeManager();

		$this->assertEquals( array( 'Foo', 'Bar', 'Baz' ), $this->manager->getVaryCookies() );
	}

	public function testCanAuthenticateNow() {
		$this->initializeManager();

		$this->manager->getSession()
			->expects( $this->once() )
			->method( 'canSetSessionUserInfo' );
		$this->assertSame( true, $this->manager->canAuthenticateNow() );
	}

}

