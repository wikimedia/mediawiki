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
	protected function mockSession( $key, $priority, $info = null, $settable = true, $methods = array() ) {
		if ( $info === null ) {
			$info = array( 0, null, null );
		}
		$builder = $this->getMockBuilder( 'AuthnSession' )
			->setConstructorArgs(
				array( $this->sessionStore, new Psr\Log\NullLogger(), $key, $priority )
			);
		if ( $methods ) {
			$builder->setMethods( array_merge( $methods, array(
				'canSetSessionUserInfo',
				'setSessionUserInfo',
				'setupPHPSessionHandler',
			) ) );
		} else {
			$builder->setMethods( array(
				'canSetSessionUserInfo',
				'setSessionUserInfo',
				'setupPHPSessionHandler',
			) );
			$builder->setMockClassName( 'AuthManagerTestMockSession' );
		}
		$session = $builder->getMockForAbstractClass();
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
	protected function userInfo() {
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
	protected function hook( $hook, $expect ) {
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
	protected function unhook( $hook ) {
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array(),
		) );
	}

	/**
	 * Convert a Message or a key + params into a MessageSpecifier
	 * @param string|Message $key
	 * @param array $params
	 * @return MessageSpecifier
	 */
	protected function messageSpecifier( $key, $params = array() ) {
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
		MediaWiki\suppressWarnings(); // Warning about headers already having been sent
		$session = $this->manager->getSession();
		MediaWiki\restoreWarnings();
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
		MediaWiki\suppressWarnings(); // Warning about headers already having been sent
		$session = $this->manager->getSession();
		MediaWiki\restoreWarnings();
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

	/**
	 * @dataProvider provideGetAuthenticatedUserInfo
	 * @param string $label
	 * @param bool $settable
	 * @param bool $autoCreate
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $token
	 * @param array|Exception $expect
	 */
	public function testGetAuthenticatedUserInfo(
		$label, $settable, $autoCreate, $id, $name, $token, $expect
	) {
		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'primary' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( true );
		if ( $autoCreate ) {
			$mock->method( 'testForAutoCreation' )->willReturn( StatusValue::newGood() );
		} else {
			$mock->expects( $this->never() )->method( 'testForAutoCreation' );
		}
		$this->primaryauthMocks = array( $mock );

		$this->initializeManager();

		// Sigh. ID isn't available from the dataProvider.
		if ( $id === '<UTSysop>' ) {
			$id = (int)User::idFromName( 'UTSysop' );
		}
		if ( is_array( $expect ) && $expect[0] === '<UTSysop>' ) {
			$expect[0] = (int)User::idFromName( 'UTSysop' );
		}

		if ( $token === '<correct>' ) {
			$token = $this->managerPriv->getUserToken( 0, 'UTSysop' );
		}

		if ( $autoCreate ) {
			$name = self::usernameForCreation();
		}

		$this->managerPriv->session = $this->mockSession( 's', 2, array( $id, $name, $token), $settable );

		try {
			$ret = $this->manager->getAuthenticatedUserInfo( $autoCreate !== false );
		} catch ( Exception $ex ) {
			$ret = $ex;
			$this->assertEquals( $expect, $ex );
			return;
		}

		if ( $autoCreate ) {
			$this->assertSame( $name, $ret[1], $label );
			$this->assertNotSame( 0, $ret[0], $label );
		} else {
			$this->assertSame( $expect, $ret, $label );
		}
	}

	public static function provideGetAuthenticatedUserInfo() {
		$id = '<UTSysop>'; // placeholder
		$name = 'UTSysop';
		$token = '<correct>'; // placeholder
		$name2 = self::usernameForCreation( '-noauto' );

		return array(
			array( 'valid settable session', true, null, $id, $name, $token, array( $id, $name ) ),
			array( 'invalid settable session', true, null, $id, $name, 'Bogus', array( 0, null ) ),
			array( 'valid settable session, id only', true, null, $id, null, $token, array( $id, $name ) ),
			array( 'valid settable session, name only', true, null, 0, $name, $token, array( $id, $name ) ),
			array( 'mismatched id/name from non-settable session (1)', true, null, -1, $name, $token, array( 0, null ) ),
			array( 'mismatched id/name from non-settable session (2)', true, null, $id, 'Wrong', $token, array( 0, null ) ),
			array( 'user for auto-creation, no flag', true, false, 0, $name2, null, array( 0, null ) ),
			array( 'user for auto-creation', true, true, 0, '<dummy>', null, array( -1, '<dummy>' ) ),

			array( 'no user from non-settable session', false, null, 0, null, null, array( 0, null ) ),
			array( 'user by id from non-settable session', false, null, $id, null, null, array( $id, $name ) ),
			array( 'user by name from non-settable session', false, null, 0, $name, null, array( $id, $name ) ),
			array( 'user by id & name from non-settable session', false, null, $id, $name, null, array( $id, $name ) ),
			array( 'invalid id from non-settable session', false, null, -1, null, null,
				new UnexpectedValueException( 'AuthManagerTestMockSession::getSessionUserInfo() returned an invalid user id' ) ),
			array( 'invalid user from non-settable session', false, null, 0, '<bad>', null, array( 0, null ) ),
			array( 'mismatched id/name from non-settable session (1)', false, null, -1, $name, null,
				new UnexpectedValueException( 'AuthManagerTestMockSession::getSessionUserInfo() returned mismatched user id and name' ) ),
			array( 'mismatched id/name from non-settable session (2)', false, null, $id, 'Wrong', null,
				new UnexpectedValueException( 'AuthManagerTestMockSession::getSessionUserInfo() returned mismatched user id and name' ) ),
			array( 'user for auto-creation, no flag', false, false, 0, $name2, null, array( 0, null ) ),
			array( 'user for auto-creation', false, true, 0, '<dummy>', null, array( -1, '<dummy>' ) ),
		);
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

	/**
	 * @dataProvider provideAuthentication
	 * @uses CreatedAccountAuthenticationRequest
	 * @uses AuthenticationResponse
	 * @param string $label
	 * @param StatusValue $preResponse
	 * @param array $primaryResponses
	 * @param array $secondaryResponses
	 * @param array $managerResponses
	 * @param bool $link Whether the primary authentication provider is a "link" provider
	 */
	public function testAuthentication(
		$label, StatusValue $preResponse, array $primaryResponses, array $secondaryResponses,
		array $managerResponses, $link = false
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
			$mocks[$key . '2'] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key . '2']->method( 'getUniqueId' )->willReturn( $key . '2' );
			$mocks[$key . '3'] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key . '3']->method( 'getUniqueId' )->willReturn( $key . '3' );
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
			->method( 'beginPrimaryAuthentication' )
			->will( $callback );
		$mocks['primary']->expects( $this->exactly( max( 0, $ct - 1 ) ) )
			->method( 'continuePrimaryAuthentication' )
			->will( $callback );
		if ( $link ) {
			$mocks['primary']->method( 'accountCreationType' )
				->willReturn( PrimaryAuthenticationProvider::TYPE_LINK );
		}

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

		$abstain = AuthenticationResponse::newAbstain();
		$mocks['pre2']->expects( $this->atMost( 1 ) )->method( 'testForAuthentication' )
			->willReturn( StatusValue::newGood() );
		$mocks['primary2']->expects( $this->atMost( 1 ) )->method( 'beginPrimaryAuthentication' )->willReturn( $abstain );
		$mocks['primary2']->expects( $this->never() )->method( 'continuePrimaryAuthentication' );
		$mocks['secondary2']->expects( $this->atMost( 1 ) )->method( 'beginSecondaryAuthentication' )->willReturn( $abstain );
		$mocks['secondary2']->expects( $this->never() )->method( 'continueSecondaryAuthentication' );
		$mocks['secondary3']->expects( $this->atMost( 1 ) )->method( 'beginSecondaryAuthentication' )->willReturn( $abstain );
		$mocks['secondary3']->expects( $this->never() )->method( 'continueSecondaryAuthentication' );

		$this->preauthMocks = array( $mocks['pre'], $mocks['pre2'] );
		$this->primaryauthMocks = array( $mocks['primary'], $mocks['primary2'] );
		$this->secondaryauthMocks = array( $mocks['secondary3'], $mocks['secondary'], $mocks['secondary2'] );
		$this->initializeManager( true );

		$sessionReq = $this->getMockForAbstractClass( 'AuthenticationRequest', array(), 'MockSessionAuthenticationRequest' );
		$session = $this->mockSession( 'dummy', 1, null, true, array( 'getAuthenticationRequestType' ) );
		$session->method( 'getAuthenticationRequestType' )->willReturn( 'MockSessionAuthenticationRequest' );
		$session->method( 'setSessionUserInfo' )
			->with( $this->anything(), $this->anything(), $this->anything(), $this->equalTo( $sessionReq ) );
		$this->managerPriv->session = $session;

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
					$ret = $this->manager->beginAuthentication( array( $req, $sessionReq ) );
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

			$maybeLink = $this->manager->getRequest()->getSessionData( 'AuthManager::maybeLink' );
			if ( $link && $response->status === AuthenticationResponse::FAIL && $response->createRequest ) {
				$this->assertSame( array( $response->createRequest ), $maybeLink, "Response $i, maybeLink" );
			} else {
				$this->assertNull( $maybeLink, "Response $i, maybeLink" );
			}
		}
	}

	public function provideAuthentication() {
		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();

		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );
		$req->foobar = 'baz';
		$failWithReq = AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-authn-no-local-user' ) );
		$failWithReq->createRequest = $req;
		$failWithReq2 = AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-authn-no-local-user-link' ) );
		$failWithReq2->createRequest = $req;

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
					new DomainException( 'MockPrimaryAuthenticationProvider::continuePrimaryAuthentication() returned ABSTAIN' )
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
				'Primary UI, then pass with no local user (link type)',
				StatusValue::newGood(),
				array(
					$tmp = AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newPass( null, $req ),
				),
				array(),
				array(
					$tmp,
					$failWithReq2,
				),
				true
			),
			array(
				'Primary pass with invalid username',
				StatusValue::newGood(),
				array(
					AuthenticationResponse::newPass( '<>', $req ),
				),
				array(),
				array(
					new DomainException( 'MockPrimaryAuthenticationProvider returned an invalid username: <>' ),
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

	public function testAllowChangingAuthenticationType() {
		$mock1 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock1->method( 'getUniqueId' )->willReturn( '1' );
		$mock1->method( 'providerAllowChangingAuthenticationType' )->willReturn( true );
		$mock2 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock2->method( 'getUniqueId' )->willReturn( '2' );
		$mock2->method( 'providerAllowChangingAuthenticationType' )->willReturn( false );

		$this->primaryauthMocks = array( $mock1 );
		$this->initializeManager( true );
		$this->assertTrue( $this->manager->allowChangingAuthenticationType( 'Foo' ) );

		$this->primaryauthMocks = array( $mock2 );
		$this->initializeManager( true );
		$this->assertFalse( $this->manager->allowChangingAuthenticationType( 'Foo' ) );

		$this->primaryauthMocks = array( $mock1, $mock2 );
		$this->initializeManager( true );
		$this->assertFalse( $this->manager->allowChangingAuthenticationType( 'Foo' ) );
	}

	/**
	 * @dataProvider provideCanChangeAuthenticationData
	 * @param StatusValue $primaryReturn
	 * @param StatusValue $secondaryReturn
	 * @param Status $expect
	 */
	public function testCanChangeAuthenticationData( $primaryReturn, $secondaryReturn, $expect ) {
		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );

		$mock1 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock1->method( 'getUniqueId' )->willReturn( '1' );
		$mock1->method( 'providerCanChangeAuthenticationData' )
			->with( $this->equalTo( $req ) )
			->willReturn( $primaryReturn );
		$mock2 = $this->getMockForAbstractClass( 'SecondaryAuthenticationProvider' );
		$mock2->method( 'getUniqueId' )->willReturn( '2' );
		$mock2->method( 'providerCanChangeAuthenticationData' )
			->with( $this->equalTo( $req ) )
			->willReturn( $secondaryReturn );

		$this->primaryauthMocks = array( $mock1 );
		$this->secondaryauthMocks = array( $mock2 );
		$this->initializeManager( true );
		$this->assertEquals( $expect, $this->manager->canChangeAuthenticationData( $req ) );
	}

	public static function provideCanChangeAuthenticationData() {
		$ignored = Status::newGood( 'ignored' );
		$ignored->warning( 'authmanager-change-not-supported' );

		$okFromPrimary = StatusValue::newGood();
		$okFromPrimary->warning( 'warning-from-primary' );
		$okFromSecondary = StatusValue::newGood();
		$okFromSecondary->warning( 'warning-from-secondary' );

		return array(
			array(
				StatusValue::newGood(),
				StatusValue::newGood(),
				Status::newGood(),
			),
			array(
				StatusValue::newGood( 'ignored' ),
				StatusValue::newGood(),
				$ignored,
			),
			array(
				StatusValue::newFatal( 'fail from primary' ),
				StatusValue::newGood(),
				Status::newFatal( 'fail from primary' ),
			),
			array(
				$okFromPrimary,
				StatusValue::newGood(),
				Status::wrap( $okFromPrimary ),
			),
			array(
				StatusValue::newGood(),
				StatusValue::newFatal( 'fail from secondary' ),
				Status::newFatal( 'fail from secondary' ),
			),
			array(
				StatusValue::newGood(),
				$okFromSecondary,
				Status::wrap( $okFromSecondary ),
			),
		);
	}

	public function testChangeAuthenticationData() {
		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );

		$mock1 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock1->method( 'getUniqueId' )->willReturn( '1' );
		$mock1->expects( $this->once() )->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );
		$mock2 = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock2->method( 'getUniqueId' )->willReturn( '2' );
		$mock2->expects( $this->once() )->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );

		$this->primaryauthMocks = array( $mock1, $mock2 );
		$this->initializeManager( true );
		$this->manager->changeAuthenticationData( $req );
	}

	public function testCanCreateAccounts() {
		$types = array(
			PrimaryAuthenticationProvider::TYPE_CREATE => true,
			PrimaryAuthenticationProvider::TYPE_LINK => true,
			PrimaryAuthenticationProvider::TYPE_NONE => false,
		);

		foreach ( $types as $type => $can ) {
			$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
			$mock->method( 'getUniqueId' )->willReturn( $type );
			$mock->method( 'accountCreationType' )->willReturn( $type );
			$this->primaryauthMocks = array( $mock );
			$this->initializeManager( true );
			$this->assertSame( $can, $this->manager->canCreateAccounts(), $type );
		}
	}

	/**
	 * @param string $uniq
	 * @return string
	 */
	private static function usernameForCreation( $uniq = '' ) {
		$i = 0;
		do {
			$username = "UTAuthManagerTestAccountCreation" . $uniq . ++$i;
		} while ( User::newFromName( $username )->getId() !== 0 );
		return $username;
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testBeginAccountCreation() {
		$that = $this;
		$creator = User::newFromName( 'UTSysop' );
		$this->initializeManager();

		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState', 'test' );
		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$this->manager->beginAccountCreation( self::usernameForCreation(), $creator, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertEquals( 'Account creation is not possible', $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ) );

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( true );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAccountCreation( self::usernameForCreation(), $creator, array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'userexists', $ret->message->getKey() );

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( false );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAccountCreation( self::usernameForCreation() . '<>', $creator, array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'noname', $ret->message->getKey() );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAccountCreation( $creator->getName(), $creator, array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'userexists', $ret->message->getKey() );

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( false );
		$mock->method( 'testForAccountCreation' )->willReturn( StatusValue::newFatal( 'fail' ) );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$username = self::usernameForCreation();
		$req = $this->getMockBuilder( 'UserDataAuthenticationRequest' )
			->setMethods( array( 'populateUser' ) )
			->getMock();
		$req->expects( $this->once() )->method( 'populateUser' )
			->will( $this->returnCallback( function ( $user ) use ( $that, $username ) {
				$that->assertSame( $username, $user->getName() );
			} ) );
		$this->manager->beginAccountCreation( $username, $creator, array( $req ) );
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testContinueAccountCreation() {
		$that = $this;
		$creator = User::newFromName( 'UTSysop' );
		$username = self::usernameForCreation();
		$this->initializeManager();

		$session = array(
			'userid' => 0,
			'username' => $username,
			'reqs' => array(),
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => array(),
		);

		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$this->manager->continueAccountCreation( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertEquals( 'Account creation is not possible', $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( false );
		$mock->method( 'beginPrimaryAccountCreation' )->willReturn(
			AuthenticationResponse::newFail( $this->messageSpecifier( 'fail' ) )
		);
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState', null );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAccountCreation( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-create-not-in-progress', $ret->message->getKey() );

		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState',
			array( 'username' => "$username<>" ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAccountCreation( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'noname', $ret->message->getKey() );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ) );

		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState',
			array( 'username' => $creator->getName() ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAccountCreation( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'userexists', $ret->message->getKey() );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ) );

		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState',
			array( 'userid' => $creator->getId() ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$ret = $this->manager->continueAccountCreation( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertEquals( "User \"{$username}\" should exist now, but doesn't!", $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ) );

		$id = $creator->getId();
		$name = $creator->getName();
		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState',
			array( 'username' => $name, 'userid' => $id + 1 ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$ret = $this->manager->continueAccountCreation( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertEquals( "User \"{$name}\" exists, but ID $id != " . ( $id + 1 ) . '!', $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );
		$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ) );

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( false );
		$mock->method( 'beginPrimaryAccountCreation' )->willReturn(
			AuthenticationResponse::newFail( $this->messageSpecifier( 'fail' ) )
		);
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$req = $this->getMockBuilder( 'UserDataAuthenticationRequest' )
			->setMethods( array( 'populateUser' ) )
			->getMock();
		$req->expects( $this->once() )->method( 'populateUser' )
			->will( $this->returnCallback( function ( $user ) use ( $that, $username ) {
				$that->assertSame( $username, $user->getName() );
			} ) );
		$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState',
			array( 'reqs' => array( $req ) ) + $session );
		$this->manager->continueAccountCreation( array() );
	}

	/**
	 * @dataProvider provideAccountCreation
	 * @uses AuthenticationResponse
	 * @uses CreatedAccountAuthenticationRequest
	 * @param string $label
	 * @param StatusValue $preTest
	 * @param StatusValue $primaryTest
	 * @param StatusValue $secondaryTest
	 * @param array $primaryResponses
	 * @param array $secondaryResponses
	 * @param array $managerResponses
	 */
	public function testAccountCreation(
		$label, StatusValue $preTest, $primaryTest, $secondaryTest,
		array $primaryResponses, array $secondaryResponses, array $managerResponses
	) {
		$creator = User::newFromName( 'UTSysop' );
		$username = self::usernameForCreation();

		$this->initializeManager();

		// Set up lots of mocks...
		$that = $this;
		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );
		$req->preTest = $preTest;
		$req->primaryTest = $primaryTest;
		$req->secondaryTest = $secondaryTest;
		$req->primary = $primaryResponses;
		$req->secondary = $secondaryResponses;
		$clazz = get_class( $req );
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key]->method( 'getUniqueId' )->willReturn( $key );
			$mocks[$key]->method( 'testForAccountCreation' )
				->will( $this->returnCallback(
					function ( $user, $creatorIn, $reqs )
						use ( $that, $username, $creator, $clazz, $key )
					{
						$that->assertSame( $username, $user->getName() );
						$that->assertSame( $creator, $creatorIn );
						foreach ( $reqs as $req ) {
							$that->assertSame( $username, $req->username );
						}
						$that->assertArrayHasKey( $clazz, $reqs );
						$req = $reqs[$clazz];
						$k = $key . 'Test';
						return $req->$k;
					}
				) );

			for ( $i = 2; $i <= 3; $i++ ) {
				$mocks[$key . $i] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
				$mocks[$key . $i]->method( 'getUniqueId' )->willReturn( $key . $i );
				$mocks[$key . $i]->expects( $this->atMost( 1 ) )->method( 'testForAccountCreation' )
					->willReturn( StatusValue::newGood() );
			}
		}

		$mocks['primary']->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mocks['primary']->method( 'testUserExists' )->willReturn( false );
		$ct = count( $req->primary );
		$callback = $this->returnCallback( function ( $user, $reqs ) use ( $that, $username, $clazz ) {
			$that->assertSame( $username, $user->getName() );
			foreach ( $reqs as $req ) {
				$that->assertSame( $username, $req->username );
			}
			$that->assertArrayHasKey( $clazz, $reqs );
			$req = $reqs[$clazz];
			return array_shift( $req->primary );
		} );
		$mocks['primary']->expects( $this->exactly( min( 1, $ct ) ) )
			->method( 'beginPrimaryAccountCreation' )
			->will( $callback );
		$mocks['primary']->expects( $this->exactly( max( 0, $ct - 1 ) ) )
			->method( 'continuePrimaryAccountCreation' )
			->will( $callback );

		$ct = count( $req->secondary );
		$callback = $this->returnCallback( function ( $user, $reqs ) use ( $that, $username, $clazz ) {
			$that->assertSame( $username, $user->getName() );
			foreach ( $reqs as $req ) {
				$that->assertSame( $username, $req->username );
			}
			$that->assertArrayHasKey( $clazz, $reqs );
			$req = $reqs[$clazz];
			return array_shift( $req->secondary );
		} );
		$mocks['secondary']->expects( $this->exactly( min( 1, $ct ) ) )
			->method( 'beginSecondaryAccountCreation' )
			->will( $callback );
		$mocks['secondary']->expects( $this->exactly( max( 0, $ct - 1 ) ) )
			->method( 'continueSecondaryAccountCreation' )
			->will( $callback );

		$abstain = AuthenticationResponse::newAbstain();
		$mocks['primary2']->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_LINK );
		$mocks['primary2']->method( 'testUserExists' )->willReturn( false );
		$mocks['primary2']->expects( $this->atMost( 1 ) )->method( 'beginPrimaryAccountCreation' )->willReturn( $abstain );
		$mocks['primary2']->expects( $this->never() )->method( 'continuePrimaryAccountCreation' );
		$mocks['primary3']->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_NONE );
		$mocks['primary3']->method( 'testUserExists' )->willReturn( false );
		$mocks['primary3']->expects( $this->never() )->method( 'beginPrimaryAccountCreation' );
		$mocks['primary3']->expects( $this->never() )->method( 'continuePrimaryAccountCreation' );
		$mocks['secondary2']->expects( $this->atMost( 1 ) )->method( 'beginSecondaryAccountCreation' )->willReturn( $abstain );
		$mocks['secondary2']->expects( $this->never() )->method( 'continueSecondaryAccountCreation' );
		$mocks['secondary3']->expects( $this->atMost( 1 ) )->method( 'beginSecondaryAccountCreation' )->willReturn( $abstain );
		$mocks['secondary3']->expects( $this->never() )->method( 'continueSecondaryAccountCreation' );

		$this->preauthMocks = array( $mocks['pre'], $mocks['pre2'] );
		$this->primaryauthMocks = array( $mocks['primary3'], $mocks['primary'], $mocks['primary2'] );
		$this->secondaryauthMocks = array( $mocks['secondary3'], $mocks['secondary'], $mocks['secondary2'] );
		$this->initializeManager( true );

		$sessionReq = $this->getMockForAbstractClass( 'AuthenticationRequest', array(), 'MockSessionAuthenticationRequest' );
		$session = $this->mockSession( 'dummy', 1, null, true, array( 'getAuthenticationRequestType' ) );
		$session->method( 'getAuthenticationRequestType' )->willReturn( 'MockSessionAuthenticationRequest' );
		$session->method( 'setSessionUserInfo' )
			->with( $this->anything(), $this->anything(), $this->anything(), $this->equalTo( $sessionReq ) );
		$this->managerPriv->session = $session;

		$first = true;
		$created = false;
		foreach ( $managerResponses as $i => $response ) {
			$success = $response instanceof AuthenticationResponse &&
				$response->status === AuthenticationResponse::PASS;
			if ( $i === 'created' ) {
				$created = true;
				$this->hook( 'LocalUserCreated', $this->once() )
					->with(
						$this->callback( function ( $user ) use ( $username ) {
							return $user->getName() === $username;
						} ),
						$this->equalTo( false )
					);
			} else {
				$this->hook( 'LocalUserCreated', $this->never() );
			}
			$ex = null;
			try {
				if ( $first ) {
					$ret = $this->manager->beginAccountCreation( $username, $creator, array( $req, $sessionReq ) );
				} else {
					$ret = $this->manager->continueAccountCreation( array( $req ) );
				}
				if ( $response instanceof Exception ) {
					$this->fail( 'Expected exception not thrown', "Response $i" );
				}
			} catch ( Exception $ex ) {
				if ( !$response instanceof Exception ) {
					throw $ex;
				}
				$this->assertEquals( $response->getMessage(), $ex->getMessage(), "Response $i, exception" );
				$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ),
				   "Response $i, exception, session state" );
				$this->unhook( 'LocalUserCreated' );
				return;
			}

			$this->unhook( 'LocalUserCreated' );
			if ( $success ) {
				$this->assertNotNull( $ret->loginRequest, "Response $i, login marker" );
				$this->assertContains(
					$ret->loginRequest, $this->managerPriv->createdAccountAuthenticationRequests,
					"Response $i, login marker"
				);

				// Set some fields in the expected $response that we couldn't
				// know in provideAccountCreation().
				$response->username = $username;
				$response->loginRequest = $ret->loginRequest;
			} else {
				$this->assertNull( $ret->loginRequest, "Response $i, login marker" );
				$this->assertSame( array(), $this->managerPriv->createdAccountAuthenticationRequests,
				   "Response $i, login marker" );
			}
			$ret->message = $this->messageSpecifier( $ret->message );
			$this->assertEquals( $response, $ret, "Response $i, response" );
			if ( $success || $response->status === AuthenticationResponse::FAIL ) {
				$this->assertNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ),
				   "Response $i, session state" );
			} else {
				$this->assertNotNull( $this->manager->getRequest()->getSessionData( 'AuthManager::accountCreationState' ),
				   "Response $i, session state" );
			}

			if ( $created ) {
				$this->assertNotEquals( 0, User::idFromName( $username ) );
			} else {
				$this->assertEquals( 0, User::idFromName( $username ) );
			}

			$first = false;
		}
	}

	public function provideAccountCreation() {
		$good = StatusValue::newGood();

		return array(
			array(
				'Pre-creation test fail in pre',
				StatusValue::newFatal( 'fail-from-pre' ), $good, $good,
				array(),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-pre' ) ),
				)
			),
			array(
				'Pre-creation test fail in primary',
				$good, StatusValue::newFatal( 'fail-from-primary' ), $good,
				array(),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-primary' ) ),
				)
			),
			array(
				'Pre-creation test fail in secondary',
				$good, $good, StatusValue::newFatal( 'fail-from-secondary' ),
				array(),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-secondary' ) ),
				)
			),
			array(
				'Failure in primary',
				$good, $good, $good,
				$tmp = array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-primary' ) ),
				),
				array(),
				$tmp
			),
			array(
				'All primary abstain',
				$good, $good, $good,
				array(
					AuthenticationResponse::newAbstain(),
				),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-create-no-primary' ) )
				)
			),
			array(
				'Primary UI, then redirect, then fail',
				$good, $good, $good,
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
				$good, $good, $good,
				array(
					$tmp = AuthenticationResponse::newRedirect( '/foo.html', array( 'foo' => 'bar' ) ),
					AuthenticationResponse::newAbstain(),
				),
				array(),
				array(
					$tmp,
					new DomainException( 'MockPrimaryAuthenticationProvider::continuePrimaryAccountCreation() returned ABSTAIN' )
				)
			),
			array(
				'Primary UI, then pass; secondary abstain',
				$good, $good, $good,
				array(
					$tmp1 = AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newPass(),
				),
				array(
					AuthenticationResponse::newAbstain(),
				),
				array(
					$tmp1,
					'created' => AuthenticationResponse::newPass( '' ),
				)
			),
			array(
				'Primary pass; secondary UI then pass',
				$good, $good, $good,
				array(
					AuthenticationResponse::newPass( '' ),
				),
				array(
					$tmp1 = AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newPass( '' ),
				),
				array(
					'created' => $tmp1,
					AuthenticationResponse::newPass( '' ),
				)
			),
			array(
				'Primary pass; secondary fail',
				$good, $good, $good,
				array(
					AuthenticationResponse::newPass(),
				),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( '...' ) ),
				),
				array(
					'created' => new DomainException(
						'MockSecondaryAuthenticationProvider::beginSecondaryAccountCreation() returned FAIL'
					)
				)
			),
		);
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testAutoAccountCreation() {
		$that = $this;
		$username = self::usernameForCreation();
		$this->initializeManager();

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'X' );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( false );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateAccount( self::usernameForCreation() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( Status::newFatal( 'nosuchusershort' ), $ret );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		// Set up lots of mocks...
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key]->method( 'getUniqueId' )->willReturn( $key );
		}

		$good = StatusValue::newGood();
		$callback = $this->callback( function ( $user ) use ( $username ) {
			return $user->getName() === $username;
		} );

		$mocks['pre']->expects( $this->exactly( 4 ) )->method( 'testForAutoCreation' )
			->with( $callback )
			->will( $this->onConsecutiveCalls(
				StatusValue::newFatal( 'fail-in-pre' ), $good, $good, $good
			) );

		$mocks['primary']->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mocks['primary']->method( 'testUserExists' )->willReturn( true );
		$mocks['primary']->expects( $this->exactly( 3 ) )->method( 'testForAutoCreation' )
			->with( $callback )
			->will( $this->onConsecutiveCalls(
				StatusValue::newFatal( 'fail-in-primary' ), $good, $good
			) );
		$mocks['primary']->expects( $this->once() )->method( 'autoCreatedAccount' )
			->with( $callback );

		$mocks['secondary']->expects( $this->exactly( 2 ) )->method( 'testForAutoCreation' )
			->with( $callback )
			->will( $this->onConsecutiveCalls(
				StatusValue::newFatal( 'fail-in-secondary' ), $good
			) );
		$mocks['secondary']->expects( $this->once() )->method( 'autoCreatedAccount' )
			->with( $callback );

		$this->preauthMocks = array( $mocks['pre'] );
		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );

		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateAccount( self::usernameForCreation() . '<>' );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( Status::newFatal( 'noname' ), $ret );
		$this->assertEquals( 0, User::idFromName( $username ) );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateAccount( 'UTSysop' );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( Status::newFatal( 'userexists' ), $ret );
		$this->assertEquals( 0, User::idFromName( $username ) );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateAccount( $username );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( Status::newFatal( 'fail-in-pre' ), $ret );
		$this->assertEquals( 0, User::idFromName( $username ) );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateAccount( $username );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( Status::newFatal( 'fail-in-primary' ), $ret );
		$this->assertEquals( 0, User::idFromName( $username ) );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateAccount( $username );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( Status::newFatal( 'fail-in-secondary' ), $ret );
		$this->assertEquals( 0, User::idFromName( $username ) );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		$this->hook( 'LocalUserCreated', $this->once() )
			->with( $callback, $this->equalTo( true ) );
		$ret = $this->manager->autoCreateAccount( $username );
		$this->unhook( 'LocalUserCreated' );
		$this->assertTrue( $ret->isGood(), 'Good' );
		$this->assertInstanceOf( 'User', $ret->value );
		$id = (int)User::idFromName( $username );
		$this->assertNotEquals( 0, $id );
		$this->assertSame( array( $id, $username ), $this->manager->getAuthenticatedUserInfo() );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $which
	 * @param array $expect
	 * @param array $state
	 */
	public function testGetAuthenticationRequestTypes( $which, $expect, $state = array() ) {
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key]->method( 'getUniqueId' )->willReturn( $key );
			$mocks[$key]->method( 'getAuthenticationRequestTypes' )
				->will( $this->returnCallback( function ( $which ) use ( $key ) {
					return array( "$key-$which", 'generic' );
				} ) );
			$mocks[$key]->method( 'providerAllowChangingAuthenticationType' )->willReturn( true );
		}

		$mocks['primary2'] = $this->getMockForAbstractClass(
			'PrimaryAuthenticationProvider', array(), "MockPrimaryAuthenticationProvider" );
		$mocks['primary2']->method( 'getUniqueId' )->willReturn( 'primary2' );
		$mocks['primary2']->method( 'getAuthenticationRequestTypes' )->willReturn( array() );
		$mocks['primary2']->method( 'providerAllowChangingAuthenticationType' )
			->will( $this->returnCallback( function ( $type ) {
				return $type !== 'generic';
			} ) );

		$this->preauthMocks = array( $mocks['pre'] );
		$this->primaryauthMocks = array( $mocks['primary'], $mocks['primary2'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );

		$session = $this->mockSession( 'dummy', 1, null, true, array( 'getAuthenticationRequestType' ) );
		$session->method( 'getAuthenticationRequestType' )->willReturn( 'session' );
		$this->managerPriv->session = $session;

		if ( $state ) {
			if ( $which === 'login-continue' ) {
				$this->manager->getRequest()->setSessionData( 'AuthManager::authnState', $state );
			} elseif ( $which === 'create-continue' ) {
				$this->manager->getRequest()->setSessionData( 'AuthManager::accountCreationState', $state );
			}
		}
		$actual = $this->manager->getAuthenticationRequestTypes( $which );
		sort( $actual );
		sort( $expect );
		$this->assertSame( $expect, $actual );
	}

	public static function provideGetAuthenticationRequestTypes() {
		return array(
			array(
				'login',
				array( 'pre-login', 'primary-login', 'secondary-login', 'session', 'generic' ),
			),
			array(
				'create',
				array( 'pre-create', 'primary-create', 'secondary-create', 'generic' ),
			),
			array(
				'all',
				array( 'pre-all', 'primary-all', 'secondary-all', 'session', 'generic' ),
			),
			array(
				'change',
				array( 'primary-change' ),
			),
			array(
				'login-continue',
				array(),
			),
			array(
				'login-continue',
				array(),
				array(
					'primary' => null,
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				'login-continue',
				array( 'primary-login-continue', 'generic' ),
				array(
					'primary' => 'primary',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				'login-continue',
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				'login-continue',
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array(),
				),
			),
			array(
				'login-continue',
				array( 'secondary-login-continue', 'generic' ),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => false ),
				),
			),
			array(
				'login-continue',
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => true ),
				),
			),
			array(
				'create-continue',
				array(),
			),
			array(
				'create-continue',
				array(),
				array(
					'primary' => null,
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				'create-continue',
				array( 'primary-create-continue', 'generic' ),
				array(
					'primary' => 'primary',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				'create-continue',
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				'create-continue',
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array(),
				),
			),
			array(
				'create-continue',
				array( 'secondary-create-continue', 'generic' ),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => false ),
				),
			),
			array(
				'create-continue',
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => true ),
				),
			),
		);
	}

	public function testAllowPropertyChange() {
		$mocks = array();
		foreach ( array( 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass( $class, array(), "Mock$class" );
			$mocks[$key]->method( 'getUniqueId' )->willReturn( $key );
			$mocks[$key]->method( 'providerAllowPropertyChange' )
				->will( $this->returnCallback( function ( $prop ) use ( $key ) {
					return $prop !== $key;
				} ) );
		}

		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );

		$this->assertTrue( $this->manager->allowPropertyChange( 'foo' ) );
		$this->assertFalse( $this->manager->allowPropertyChange( 'primary' ) );
		$this->assertFalse( $this->manager->allowPropertyChange( 'secondary' ) );
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testAutoCreateOnLogin() {
		$username = self::usernameForCreation();

		$mock = $this->getMockForAbstractClass( 'PrimaryAuthenticationProvider' );
		$mock->method( 'getUniqueId' )->willReturn( 'primary' );
		$mock->method( 'beginPrimaryAuthentication' )->willReturn(
			AuthenticationResponse::newPass( $username ) );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( true );
		$mock->method( 'testForAutoCreation' )->willReturn( StatusValue::newGood() );

		$mock2 = $this->getMockForAbstractClass( 'SecondaryAuthenticationProvider' );
		$mock2->method( 'getUniqueId' )->willReturn( 'secondary' );
		$mock2->method( 'beginSecondaryAuthentication' )->willReturn(
			AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) )
		);
		$mock2->method( 'continueSecondaryAuthentication' )->willReturn(
			AuthenticationResponse::newAbstain()
		);
		$mock2->method( 'testForAutoCreation' )->willReturn( StatusValue::newGood() );

		$this->primaryauthMocks = array( $mock );
		$this->secondaryauthMocks = array( $mock2 );
		$this->initializeManager( true );

		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );
		$this->assertSame( 0, User::newFromName( $username )->getId(),
			'sanity check' );

		$callback = $this->callback( function ( $user ) use ( $username ) {
			return $user->getName() === $username;
		} );

		$this->hook( 'UserLoggedIn', $this->never() );
		$this->hook( 'LocalUserCreated', $this->once() )->with( $callback, $this->equalTo( true ) );
		$ret = $this->manager->beginAuthentication( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->unhook( 'UserLoggedIn' );
		$this->assertSame( AuthenticationResponse::UI, $ret->status );

		$id = (int)User::newFromName( $username )->getId();
		$this->assertNotSame( 0, User::newFromName( $username )->getId() );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );

		$this->hook( 'UserLoggedIn', $this->once() )->with( $callback );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAuthentication( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->unhook( 'UserLoggedIn' );
		$this->assertSame( AuthenticationResponse::PASS, $ret->status );
		$this->assertSame( $username, $ret->username );
		$this->assertSame( array( $id, $username ), $this->manager->getAuthenticatedUserInfo() );
	}

	/**
	 * @uses AuthenticationResponse
	 */
	public function testAutoCreateFailOnLogin() {
		$username = self::usernameForCreation();

		$mock = $this->getMockForAbstractClass(
			'PrimaryAuthenticationProvider', array(), "MockPrimaryAuthenticationProvider" );
		$mock->method( 'getUniqueId' )->willReturn( 'primary' );
		$mock->method( 'beginPrimaryAuthentication' )->willReturn(
			AuthenticationResponse::newPass( $username ) );
		$mock->method( 'accountCreationType' )->willReturn( PrimaryAuthenticationProvider::TYPE_CREATE );
		$mock->method( 'testUserExists' )->willReturn( true );
		$mock->method( 'testForAutoCreation' )->willReturn( StatusValue::newFatal( 'fail-from-primary' ) );

		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo(),
			'sanity check' );
		$this->assertSame( 0, User::newFromName( $username )->getId(),
			'sanity check' );

		$this->hook( 'UserLoggedIn', $this->never() );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAuthentication( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->unhook( 'UserLoggedIn' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-authn-autocreate-failed', $ret->message->getKey() );

		$this->assertSame( 0, User::newFromName( $username )->getId() );
		$this->assertSame( array( 0, null ), $this->manager->getAuthenticatedUserInfo() );
	}

	public function testAuthenticationData() {
		$this->initializeManager( true );

		$this->assertNull( $this->manager->getAuthenticationData( 'foo' ) );
		$this->manager->setAuthenticationData( 'foo', 'foo!' );
		$this->manager->setAuthenticationData( 'bar', 'bar!' );
		$this->assertSame( 'foo!', $this->manager->getAuthenticationData( 'foo' ) );
		$this->assertSame( 'bar!', $this->manager->getAuthenticationData( 'bar' ) );
		$this->manager->removeAuthenticationData( 'foo' );
		$this->assertNull( $this->manager->getAuthenticationData( 'foo' ) );
		$this->assertSame( 'bar!', $this->manager->getAuthenticationData( 'bar' ) );
		$this->manager->removeAuthenticationData( 'bar' );
		$this->assertNull( $this->manager->getAuthenticationData( 'bar' ) );
	}
}
