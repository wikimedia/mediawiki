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
	 * Sets a mock on a hook
	 * @param string $hook
	 * @param object $expect From $this->once(), $this->never(), etc.
	 * @return object $mock->expects( $expect )->method( ... ).
	 */
	public function hook( $hook, $expect ) {
		$mock = $this->getMock( 'stdClass', array( "on$hook" ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array( $mock ),
		) );
		return $mock->expects( $expect )->method( "on$hook" );
	}

	/**
	 * Unsets a hook
	 * @param string $hook
	 */
	public function unhook( $hook ) {
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array(),
		) );
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
		// Temporarily clear out the global singleton, if any, to test creating
		// one.
		$rProp = new ReflectionProperty( 'AuthManager', 'instance' );
		$rProp->setAccessible( true );
		$old = $rProp->getValue();
		$cb = new ScopedCallback( array( $rProp, 'setValue' ), array( $old ) );
		$rProp->setValue( null );

		$singleton = AuthManager::singleton();
		$this->assertInstanceOf( 'AuthManager', AuthManager::singleton() );
		$this->assertSame( $singleton, AuthManager::singleton() );
		$this->assertSame( RequestContext::getMain()->getRequest(), $singleton->getRequest() );
		$this->assertSame(
			RequestContext::getMain()->getConfig(),
			TestingAccessWrapper::newFromObject( $singleton )->config
		);

		ScopedCallback::consume( $cb );

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

	public function testProviderCreation() {
		$mocks = array(
			'pre' => $this->getMockForAbstractClass( 'PreAuthenticationProvider' ),
			'primary' => $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' ),
			'secondary' => $this->getMockForAbstractClass( 'SecondaryAuthenticationProvider' ),
		);
		foreach ( $mocks as $key => $mock ) {
			$mock->method( 'getUniqueId' )->willReturn( $key );
			$mock->expects( $this->once() )->method( 'setLogger' );
			$mock->expects( $this->once() )->method( 'setManager' );
			$mock->expects( $this->once() )->method( 'setConfig' );
		}
		$this->preauthMocks = array( $mocks['pre'] );
		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );

		// Normal operation
		$this->initializeManager();
		$this->assertSame( $mocks['primary'], $this->managerPriv->getAuthenticationProvider( 'primary' ) );
		$this->assertSame( $mocks['secondary'], $this->managerPriv->getAuthenticationProvider( 'secondary' ) );
		$this->assertSame( $mocks['pre'], $this->managerPriv->getAuthenticationProvider( 'pre' ) );
		$this->assertSame(
			array( 'pre' => $mocks['pre'] ),
			$this->managerPriv->getPreAuthenticationProviders()
		);
		$this->assertSame(
			array( 'primary' => $mocks['primary'] ),
			$this->managerPriv->getPrimaryAuthenticationProviders()
		);
		$this->assertSame(
			array( 'secondary' => $mocks['secondary'] ),
			$this->managerPriv->getSecondaryAuthenticationProviders()
		);

		// Duplicate IDs
		$mock1 = $this->getMockForAbstractClass( 'PreAuthenticationProvider' );
		$mock2 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock1->method( 'getUniqueId' )->willReturn( 'X' );
		$mock2->method( 'getUniqueId' )->willReturn( 'X' );
		$this->preauthMocks = array( $mock1 );
		$this->primaryauthMocks = array( $mock2 );
		$this->secondaryauthMocks = array();
		$this->initializeManager( true );
		try {
			$this->managerPriv->getAuthenticationProvider( 'Y' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$class1 = get_class( $mock1 );
			$class2 = get_class( $mock2 );
			$this->assertSame(
				"Duplicate specifications for id X (classes $class1 and $class2)", $ex->getMessage()
			);
		}

		// Wrong classes
		$mock = $this->getMockForAbstractClass( 'AuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$class = get_class( $mock );
		$this->preauthMocks = array( $mock );
		$this->primaryauthMocks = array( $mock );
		$this->secondaryauthMocks = array( $mock );
		$this->initializeManager( true );
		try {
			$this->managerPriv->getPreAuthenticationProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of PreAuthenticationProvider, got $class", $ex->getMessage()
			);
		}
		try {
			$this->managerPriv->getPrimaryAuthenticationProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of PrimaryAuthenticationProvider, got $class", $ex->getMessage()
			);
		}
		try {
			$this->managerPriv->getSecondaryAuthenticationProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of SecondaryAuthenticationProvider, got $class", $ex->getMessage()
			);
		}
	}

	public function testForcePrimaryAuthenticationProviders() {
		$mockA = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mockB = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mockB2 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mockA->method( 'getUniqueId' )->willReturn( 'A' );
		$mockB->method( 'getUniqueId' )->willReturn( 'B' );
		$mockB2->method( 'getUniqueId' )->willReturn( 'B' );
		$this->primaryauthMocks = array( $mockA );

		// Test without first initializing the configured providers
		$this->initializeManager();
		$this->manager->forcePrimaryAuthenticationProviders( array( $mockB ), 'testing' );
		$this->assertSame( array( 'B' => $mockB ), $this->managerPriv->getPrimaryAuthenticationProviders() );
		$this->assertSame( null, $this->managerPriv->getAuthenticationProvider( 'A' ) );
		$this->assertSame( $mockB, $this->managerPriv->getAuthenticationProvider( 'B' ) );

		// Test with first initializing the configured providers
		$this->initializeManager();
		$this->assertSame( $mockA, $this->managerPriv->getAuthenticationProvider( 'A' ) );
		$this->assertSame( null, $this->managerPriv->getAuthenticationProvider( 'B' ) );
		$this->manager->getRequest()->setSessionData( 'AuthManager::authnState', 'test' );
		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState', 'test' );
		$this->manager->forcePrimaryAuthenticationProviders( array( $mockB ), 'testing' );
		$this->assertSame( array( 'B' => $mockB ), $this->managerPriv->getPrimaryAuthenticationProviders() );
		$this->assertSame( null, $this->managerPriv->getAuthenticationProvider( 'A' ) );
		$this->assertSame( $mockB, $this->managerPriv->getAuthenticationProvider( 'B' ) );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::authnState' ) );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ) );

		// Test duplicate IDs
		$this->initializeManager();
		try {
			$this->manager->forcePrimaryAuthenticationProviders( array( $mockB, $mockB2 ), 'testing' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$class1 = get_class( $mockB );
			$class2 = get_class( $mockB2 );
			$this->assertSame(
				"Duplicate specifications for id B (classes $class2 and $class1)", $ex->getMessage()
			);
		}

		// Wrong classes
		$mock = $this->getMockForAbstractClass( 'AuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$class = get_class( $mock );
		try {
			$this->manager->forcePrimaryAuthenticationProviders( array( $mock ), 'testing' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of PrimaryAuthenticationProvider, got $class", $ex->getMessage()
			);
		}

	}

	/**
	 * @uses CreatedAccountAuthenticationRequest
	 * @uses AuthenticationResponse
	 */
	public function testBeginAuthentication() {
		$this->initializeManager();

		// Immutable session
		$this->hook( 'UserLoggedIn', $this->never() );
		$this->manager->getRequest()->setSessionData( 'AuthManager::authnState', 'test' );
		$this->managerPriv->session = $this->mockSession( 's', 2, array( 0, null, null ), false );
		try {
			$this->manager->beginAuthentication( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertSame( 'Authentication is not possible now', $ex->getMessage() );
		}
		$this->unhook( 'UserLoggedIn' );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::authnState' ) );
		$this->initializeManager( true );

		// CreatedAccountAuthenticationRequest
		list( $id, $name ) = $this->userInfo();
		$reqs = array(
			new CreatedAccountAuthenticationRequest( $id, $name )
		);
		$this->hook( 'UserLoggedIn', $this->never() );
		try {
			$this->manager->beginAuthentication( $reqs );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertSame( 'CreatedAccountAuthenticationRequests are only valid on the same AuthManager that created the account', $ex->getMessage() );
		}
		$this->unhook( 'UserLoggedIn' );

		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );
		$this->manager->getRequest()->setSessionData( 'AuthManager::authnState', 'test' );
		$this->managerPriv->createdAccountAuthenticationRequests = array( $reqs[0] );
		$this->hook( 'UserLoggedIn', $this->once() )
			->with( $this->callback( function ( $user ) use ( $id, $name ) {
				return $user->getId() === $id && $user->getName() === $name;
			} ) );
		$ret = $this->manager->beginAuthentication( $reqs );
		$this->unhook( 'UserLoggedIn' );
		$this->assertSame( AuthenticationResponse::PASS, $ret->status );
		$this->assertSame( $name, $ret->username );
		$this->assertSame( $id, $this->manager->getRequest()->getSessionData( 'AuthManager:lastAuthId' ) );
		$this->assertEquals(
			time(), $this->manager->getRequest()->getSessionData( 'AuthManager:lastAuthTimestamp' ),
			'timestamp ±1', 1
		);
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::authnState' ) );
		$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo() );
	}

	private function messageSpecifier( $key, $params = array() ) {
		if ( $key === null ) {
			return null;
		}
		if ( $key instanceof MessageSpecifier ) {
			$params = $key->getParams();
			$key = $key->getKey();
		}
		$mock = $this->getMockForAbstractClass( 'MessageSpecifier' );
		$mock->key = $key;
		$mock->params = $params;
		$mock->method( 'getKey' )->willReturn( $key );
		$mock->method( 'getParams' )->willReturn( $params );
		return $mock;
	}

	/**
	 * @dataProvider provideAuthentication
	 * @uses CreatedAccountAuthenticationRequest
	 * @uses AuthenticationResponse
	 * @param string $label
	 * @param StatusValue $preResponse
	 * @param array $primaryResponses
	 * @param array $secondaryResponses
	 * @param array $managerResponses
	 */
	public function testAuthentication(
		$label, StatusValue $preResponse, array $primaryResponses, array $secondaryResponses,
		array $managerResponses
	) {
		$this->initializeManager();
		list( $id, $name ) = $this->userInfo();

		// Set up lots of mocks...
		$that = $this;
		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );
		$req->pre = $preResponse;
		$req->primary = $primaryResponses;
		$req->secondary = $secondaryResponses;
		$clazz = get_class( $req );
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key]->method( 'getUniqueId' )->willReturn( $key );
		}

		$mocks['pre']->expects( $this->once() )->method( 'testForAuthentication' )
			->will( $this->returnCallback( function ( $reqs ) use ( $that, $clazz ) {
				$that->assertArrayHasKey( $clazz, $reqs );
				$req = $reqs[$clazz];
				return $req->pre;
			} ) );

		$ct = count( $req->primary );
		$callback = $this->returnCallback( function ( $reqs ) use ( $that, $clazz ) {
			$that->assertArrayHasKey( $clazz, $reqs );
			$req = $reqs[$clazz];
			return array_shift( $req->primary );
		} );
		$mocks['primary']->expects( $this->exactly( min( 1, $ct ) ) )
			->method( 'beginAuthentication' )
			->will( $callback );
		$mocks['primary']->expects( $this->exactly( max( 0, $ct - 1 ) ) )
			->method( 'continueAuthentication' )
			->will( $callback );

		$ct = count( $req->secondary );
		$callback = $this->returnCallback( function ( $user, $reqs ) use ( $that, $id, $name, $clazz ) {
			$that->assertSame( $id, $user->getId() );
			$that->assertSame( $name, $user->getName() );
			$that->assertArrayHasKey( $clazz, $reqs );
			$req = $reqs[$clazz];
			return array_shift( $req->secondary );
		} );
		$mocks['secondary']->expects( $this->exactly( min( 1, $ct ) ) )
			->method( 'beginSecondaryAuthentication' )
			->will( $callback );
		$mocks['secondary']->expects( $this->exactly( max( 0, $ct - 1 ) ) )
			->method( 'continueSecondaryAuthentication' )
			->will( $callback );

		$this->preauthMocks = array( $mocks['pre'] );
		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );

		foreach ( $managerResponses as $i => $response ) {
			$success = $response instanceof AuthenticationResponse &&
				$response->status === AuthenticationResponse::PASS;
			if ( $success ) {
				$this->hook( 'UserLoggedIn', $this->once() )
					->with( $this->callback( function ( $user ) use ( $id, $name ) {
						return $user->getId() === $id && $user->getName() === $name;
					} ) );
			} else {
				$this->hook( 'UserLoggedIn', $this->never() );
			}
			$ex = null;
			try {
				if ( !$i ) {
					$ret = $this->manager->beginAuthentication( array( $req ) );
				} else {
					$ret = $this->manager->continueAuthentication( array( $req ) );
				}
				if ( $response instanceof Exception ) {
					$this->fail( 'Expected exception not thrown', "Response $i" );
				}
			} catch ( Exception $ex ) {
				if ( !$response instanceof Exception ) {
					throw $ex;
				}
				$this->assertEquals( $response->getMessage(), $ex->getMessage(), "Response $i, exception" );
				$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::authnState' ),
				   "Response $i, exception, session state" );
				$this->unhook( 'UserLoggedIn' );
				return;
			}

			$this->unhook( 'UserLoggedIn' );
			$ret->message = $this->messageSpecifier( $ret->message );
			$this->assertEquals( $response, $ret, "Response $i, response" );
			if ( $success ) {
				$this->assertSame( array( $id, $name ), $this->manager->getAuthenticatedUserInfo(),
				   "Response $i, authn" );
			} else {
				$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
				   "Response $i, authn" );
			}
			if ( $success || $response->status === AuthenticationResponse::FAIL ) {
				$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::authnState' ),
				   "Response $i, session state" );
			} else {
				$this->assertNotNull( $this->manager->getRequest()->getSessionData( 'AuthManager::authnState' ),
				   "Response $i, session state" );
			}
		}
	}

	function provideAuthentication() {
		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();

		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );
		$req->foobar = 'baz';
		$failWithReq = AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-authn-no-local-user' ) );
		$failWithReq->createRequest = $req;

		return array(
			array(
				'Failure in pre-auth',
				StatusValue::newFatal( 'fail-from-pre' ),
				array(),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-pre' ) ),
					AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-authn-not-in-progress' ) ),
				)
			),
			array(
				'Failure in primary',
				StatusValue::newGood(),
				$tmp = array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-primary' ) ),
				),
				array(),
				$tmp
			),
			array(
				'All primary abstain',
				StatusValue::newGood(),
				array(
					AuthenticationResponse::newAbstain(),
				),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-authn-no-primary' ) )
				)
			),
			array(
				'Primary UI, then redirect, then fail',
				StatusValue::newGood(),
				$tmp = array(
					AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newRedirect( '/foo.html', array( 'foo' => 'bar' ) ),
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-in-primary-continue' ) ),
				),
				array(),
				$tmp
			),
			array(
				'Primary redirect, then abstain',
				StatusValue::newGood(),
				array(
					$tmp = AuthenticationResponse::newRedirect( '/foo.html', array( 'foo' => 'bar' ) ),
					AuthenticationResponse::newAbstain(),
				),
				array(),
				array(
					$tmp,
					new DomainException( 'MockPrimaryAuthenticationProvider::continueAuthentication() returned ABSTAIN' )
				)
			),
			array(
				'Primary UI, then pass with no local user',
				StatusValue::newGood(),
				array(
					$tmp = AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newPass( null, $req ),
				),
				array(),
				array(
					$tmp,
					$failWithReq,
				)
			),
			array(
				'Secondary fail',
				StatusValue::newGood(),
				array(
					AuthenticationResponse::newPass( $name ),
				),
				$tmp = array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-in-secondary' ) ),
				),
				$tmp
			),
			array(
				'Secondary UI, then abstain',
				StatusValue::newGood(),
				array(
					AuthenticationResponse::newPass( $name ),
				),
				array(
					$tmp = AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newAbstain()
				),
				array(
					$tmp,
					AuthenticationResponse::newPass( $name ),
				)
			),
		);
	}

}
