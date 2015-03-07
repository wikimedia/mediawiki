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
	 * @param bool $settable
	 * @param array $methods
	 * @return AuthnSession mock
	 */
	public function mockSession( $key, $priority, $info = null, $settable = true, $methods = array() ) {
		if ( $info === null ) {
			$info = array( 0, null, null );
		}
		$session = $this->getMockBuilder( 'AuthnSession' )
			->setConstructorArgs(
				array( $this->sessionStore, new Psr\Log\NullLogger(), $key, $priority )
			)
			->setMethods( array_merge( $methods, array(
				'canSetSessionUserInfo',
				'setSessionUserInfo',
				'setupPHPSessionHandler',
			) ) )
			->getMockForAbstractClass();
		$session->method( 'getSessionUserInfo' )
			->will( $this->returnCallback( function () use ( &$info ) { return $info; } ) );
		$session->method( 'canSetSessionUserInfo' )
			->willReturn( $settable );
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
	 * User info for a test user
	 * @return array ( $id, $name, $token )
	 */
	public function userInfo() {
		$user = User::newFromName( 'UTSysop' );
		return array(
			$user->getId(),
			$user->getName(),
			$this->managerPriv->getUserToken( $user->getId(), $user->getName() ),
		);
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

		list( $id, $name, $token ) = $this->userInfo();

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
				$this->mockSession( 'invalid session', 2, array( $id, $name, 'Bogus' ) ),
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

		$session = $this->mockSession( 'dummy', 1, null, true, array( 'resetSessionKey' ) );
		$session->expects( $this->once() )
			->method( 'resetSessionKey' );
		$this->managerPriv->session = $session;
		$this->manager->resetSessionId();
	}

	public function testPersistSession() {
		$this->initializeManager();

		$session = $this->mockSession( 'dummy', 1, null, true, array( 'resetSessionKey' ) );
		TestingAccessWrapper::newFromObject( $session )->key = null;
		$session->expects( $this->once() )
			->method( 'resetSessionKey' );
		$this->managerPriv->session = $session;
		$this->manager->persistSession();

		$session = $this->mockSession( 'dummy', 1, null, true, array( 'resetSessionKey' ) );
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

	public function testGetAuthenticatedUserInfo() {
		$this->initializeManager();

		list( $id, $name, $token ) = $this->userInfo();

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id, $name, $token ) );
		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
			'valid settable session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id, $name, 'Bogus' ) );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
		   'invalid settable session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, null, null ), false );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'no user from non-settable session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id, null, null ), false );
		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
			'user by id from non-settable session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, $name, null ), false );
		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
			'user by name from non-settable session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id, $name, null ), false );
		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
			'user by id and name from non-settable session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( -1, null, null ), false );
		try {
			$this->manager->getAuthenticatedUserInfo();
			$this->fail( 'Expected exception not thrown', 'invalid id from non-settable session' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $this->managerPriv->session ) . '::getSessionUserInfo() returned an invalid user id',
				$ex->getMessage(),
				'invalid id from non-settable session'
			);
		}

		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, 'ThisUserShouldNotExist', null ), false );
		try {
			$this->manager->getAuthenticatedUserInfo();
			$this->fail( 'Expected exception not thrown', 'invalid name from non-settable session' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $this->managerPriv->session ) . '::getSessionUserInfo() returned an invalid user name',
				$ex->getMessage(),
				'invalid name from non-settable session'
			);
		}

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id+1, $name, null ), false );
		try {
			$this->manager->getAuthenticatedUserInfo();
			$this->fail( 'Expected exception not thrown', 'mismatched id/name from non-settable session' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $this->managerPriv->session ) . '::getSessionUserInfo() returned mismatched user id and name',
				$ex->getMessage(),
				'mismatched id/name from non-settable session'
			);
		}
	}

	public function testInvalidateAuthenticationToken() {
		$this->initializeManager();

		list( $id, $name, $token ) = $this->userInfo();

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id, $name, $token ) );
		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );
		$this->manager->invalidateAuthenticationToken();
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'invalidated current session user' );
		$this->assertNotSame( $token, $this->managerPriv->getUserToken( $id, $name ),
			'invalidated current session user' );

		list( $id, $name, $token ) = $this->userInfo();

		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, null, null ) );
		$this->manager->invalidateAuthenticationToken();
		$this->assertSame( $token, $this->managerPriv->getUserToken( $id, $name ), 'sanity check 2' );
		$this->manager->invalidateAuthenticationToken( $name );
		$this->assertNotSame( $token, $this->managerPriv->getUserToken( $id, $name ),
			'invalidated user by name' );
	}

	public function testTimeSinceAuthentication() {
		$this->initializeManager();

		$this->request->setSessionData( 'AuthManager:lastAuthId', 0 );
		$this->request->setSessionData( 'AuthManager:lastAuthTimestamp', time()-5 );
		$this->assertSame( PHP_INT_MAX, $this->manager->timeSinceAuthentication(),
			'infinite time for logged-out session' );

		$userinfo = $this->userInfo();
		$this->managerPriv->session = $this->mockSession( 's', 2, $userinfo );
		$this->assertSame( PHP_INT_MAX, $this->manager->timeSinceAuthentication(),
			'infinite time for mismatched session' );

		$this->request->setSessionData( 'AuthManager:lastAuthId', $userinfo[0] );
		do { // Loop so we don't fail if the second happens to tick between setting and fetching
			$start = time();
			$this->request->setSessionData( 'AuthManager:lastAuthTimestamp', $start - 5 );
			$time = $this->manager->timeSinceAuthentication();
		} while ( $start !== time() );
		$this->assertSame( 5, $time, 'session-stored time for valid session' );

		$this->request->setSessionData( 'AuthManager:lastAuthTimestamp', null );
		$this->assertSame( PHP_INT_MAX, $this->manager->timeSinceAuthentication(),
			'infinite time for mimssing session data' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, null, null ), false );
		$this->assertSame( -1, $this->manager->timeSinceAuthentication(), 'non-settable session' );
	}

	public function testLogout() {
		$this->initializeManager();

		list( $id, $name, $token ) = $this->userInfo();

		$session = $this->mockSession( 's', 2, array( $id, $name, $token ) );
		$session->expects( $this->once() )->method( 'setSessionUserInfo' );
		$this->managerPriv->session = $session;

		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );
		$this->manager->logout();
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'logged out' );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo(),
			'logged out in session' );

		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, null, null ), false );
		try {
			$this->manager->logout();
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertSame( 'Authentication is not possible now', $ex->getMessage() );
		}
	}

}
