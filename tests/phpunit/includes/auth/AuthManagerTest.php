<?php

namespace MediaWiki\Auth;

use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\UserInfo;
use Psr\Log\LogLevel;
use StatusValue;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\AuthManager
 */
class AuthManagerTest extends \MediaWikiTestCase {
	/** @var WebRequest */
	protected $request;
	/** @var Config */
	protected $config;
	/** @var \\Psr\\Log\\LoggerInterface */
	protected $logger;

	protected $preauthMocks = array();
	protected $primaryauthMocks = array();
	protected $secondaryauthMocks = array();

	/** @var AuthManager */
	protected $manager;
	/** @var TestingAccessWrapper */
	protected $managerPriv;

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( array( 'wgAuth' => null ) );
		$this->stashMwGlobals( array( 'wgHooks' ) );
	}

	/**
	 * Sets a mock on a hook
	 * @param string $hook
	 * @param object $expect From $this->once(), $this->never(), etc.
	 * @return object $mock->expects( $expect )->method( ... ).
	 */
	protected function hook( $hook, $expect ) {
		global $wgHooks;
		$mock = $this->getMock( __CLASS__, array( "on$hook" ) );
		$wgHooks[$hook] = array( $mock );
		return $mock->expects( $expect )->method( "on$hook" );
	}

	/**
	 * Unsets a hook
	 * @param string $hook
	 */
	protected function unhook( $hook ) {
		global $wgHooks;
		$wgHooks[$hook] = array();
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
		if ( $key instanceof \MessageSpecifier ) {
			$params = $key->getParams();
			$key = $key->getKey();
		}
		$mock = $this->getMockForAbstractClass( 'MessageSpecifier' );
		$mock->key = $key;
		$mock->params = $params;
		$mock->expects( $this->any() )->method( 'getKey' )->will( $this->returnValue( $key ) );
		$mock->expects( $this->any() )->method( 'getParams' )->will( $this->returnValue( $params ) );
		return $mock;
	}

	/**
	 * Initialize the AuthManagerConfig variable in $this->config
	 *
	 * Uses data from the various 'mocks' fields.
	 */
	protected function initializeConfig() {
		$config = array(
			'preauth' => array(
			),
			'primaryauth' => array(
			),
			'secondaryauth' => array(
			),
		);

		foreach ( array( 'preauth', 'primaryauth', 'secondaryauth' ) as $type ) {
			$key = $type . 'Mocks';
			foreach ( $this->$key as $mock ) {
				$config[$type][] = array( 'factory' => function () use ( $mock ) {
					return $mock;
				} );
			}
		}

		$this->config->set( 'AuthManagerConfig', $config );
		$this->config->set( 'LanguageCode', 'en' );
	}

	/**
	 * Initialize $this->manager
	 * @param bool $regen Force a call to $this->initializeConfig()
	 */
	protected function initializeManager( $regen = false ) {
		if ( $regen || !$this->config ) {
			$this->config = new \HashConfig();
		}
		if ( $regen || !$this->request ) {
			$this->request = new \FauxRequest();
		}
		if ( !$this->logger ) {
			$this->logger = new \TestLogger();
		}

		if ( $regen || !$this->config->has( 'AuthManagerConfig' ) ) {
			$this->initializeConfig();
		}
		$this->manager = new AuthManager( $this->request, $this->config );
		$this->manager->setLogger( $this->logger );
		$this->managerPriv = \TestingAccessWrapper::newFromObject( $this->manager );
	}

	/**
	 * Setup SessionManager with a mock session provider
	 * @param bool|null $canChangeUser If non-null, canChangeUser will be mocked to return this
	 * @param array $methods Additional methods to mock
	 * @return array (\\MediaWiki\\Session\\SessionProvider, ScopedCallback)
	 */
	protected function getMockSessionProvider( $canChangeUser = null, array $methods = array() ) {
		if ( !$this->config ) {
			$this->config = new \HashConfig();
			$this->initializeConfig();
		}
		$this->config->set( 'ObjectCacheSessionExpiry', 100 );

		$methods[] = '__toString';
		$methods[] = 'describe';
		if ( $canChangeUser !== null ) {
			$methods[] = 'canChangeUser';
		}
		$provider = $this->getMockBuilder( 'DummySessionProvider' )
			->setMethods( $methods )
			->getMock();
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'MockSessionProvider' ) );
		$provider->expects( $this->any() )->method( 'describe' )
			->will( $this->returnValue( 'MockSessionProvider sessions' ) );
		if ( $canChangeUser !== null ) {
			$provider->expects( $this->any() )->method( 'canChangeUser' )
				->will( $this->returnValue( $canChangeUser ) );
		}
		$this->config->set( 'SessionProviders', array(
			array( 'factory' => function () use ( $provider ) {
				return $provider;
			} ),
		) );

		$manager = new \MediaWiki\Session\SessionManager( array(
			'config' => $this->config,
			'logger' => new \Psr\Log\NullLogger(),
			'store' => new \HashBagOStuff(),
		) );
		$manager->getProvider( (string)$provider );

		$reset = \MediaWiki\Session\SessionManager::setSingletonForTest( $manager );

		if ( $this->request ) {
			$manager->getSessionForRequest( $this->request );
		}

		return array( $provider, $reset );
	}

	public function testSingleton() {
		// Temporarily clear out the global singleton, if any, to test creating
		// one.
		$rProp = new \ReflectionProperty( 'MediaWiki\\Auth\\AuthManager', 'instance' );
		$rProp->setAccessible( true );
		$old = $rProp->getValue();
		$cb = new \ScopedCallback( array( $rProp, 'setValue' ), array( $old ) );
		$rProp->setValue( null );

		$singleton = AuthManager::singleton();
		$this->assertInstanceOf( 'MediaWiki\\Auth\\AuthManager', AuthManager::singleton() );
		$this->assertSame( $singleton, AuthManager::singleton() );
		$this->assertSame( \RequestContext::getMain()->getRequest(), $singleton->getRequest() );
		$this->assertSame(
			\RequestContext::getMain()->getConfig(),
			\TestingAccessWrapper::newFromObject( $singleton )->config
		);
	}

	public function testCanAuthenticateNow() {
		$this->initializeManager();

		list( $provider, $reset ) = $this->getMockSessionProvider( false );
		$this->assertFalse( $this->manager->canAuthenticateNow() );
		\ScopedCallback::consume( $reset );

		list( $provider, $reset ) = $this->getMockSessionProvider( true );
		$this->assertTrue( $this->manager->canAuthenticateNow() );
		\ScopedCallback::consume( $reset );
	}

	/**
	 * @dataProvider provideSecuritySensitiveOperationStatus
	 * @param bool $mutableSession
	 */
	public function testSecuritySensitiveOperationStatus( $mutableSession ) {
		$this->logger = new \Psr\Log\NullLogger();
		$user = \User::newFromName( 'UTSysop' );
		$provideUser = null;
		$reauth = $mutableSession ? AuthManager::SEC_REAUTH : AuthManager::SEC_FAIL;

		list( $provider, $reset ) = $this->getMockSessionProvider(
			$mutableSession, array( 'provideSessionInfo' )
		);
		$provider->expects( $this->any() )->method( 'provideSessionInfo' )
			->will( $this->returnCallback( function () use ( $provider, &$provideUser ) {
				return new SessionInfo( SessionInfo::MIN_PRIORITY, array(
					'provider' => $provider,
					'id' => \DummySessionProvider::ID,
					'persisted' => true,
					'user' => UserInfo::newFromUser( $provideUser, true )
				) );
			} ) );
		$this->initializeManager();

		$this->config->set( 'ReauthenticateTime', array() );
		$this->config->set( 'AllowSecuritySensitiveOperationIfCannotReauthenticate', array() );
		$provideUser = new \User;
		$session = $provider->getManager()->getSessionForRequest( $this->request );
		$this->assertSame( 0, $session->getUser()->getId(), 'sanity check' );

		// Anonymous user => reauth
		$session->set( 'AuthManager:lastAuthId', 0 );
		$session->set( 'AuthManager:lastAuthTimestamp', time() - 5 );
		$this->assertSame( $reauth, $this->manager->securitySensitiveOperationStatus( 'foo' ) );

		$provideUser = $user;
		$session = $provider->getManager()->getSessionForRequest( $this->request );
		$this->assertSame( $user->getId(), $session->getUser()->getId(), 'sanity check' );

		// Error for no default (only gets thrown for non-anonymous user)
		$session->set( 'AuthManager:lastAuthId', $user->getId() + 1 );
		$session->set( 'AuthManager:lastAuthTimestamp', time() - 5 );
		try {
			$this->manager->securitySensitiveOperationStatus( 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				$mutableSession
					? '$wgReauthenticateTime lacks a default'
					: '$wgAllowSecuritySensitiveOperationIfCannotReauthenticate lacks a default',
				$ex->getMessage()
			);
		}

		if ( $mutableSession ) {
			$this->config->set( 'ReauthenticateTime', array(
				'test' => 100,
				'test2' => -1,
				'default' => 10,
			) );

			// Mismatched user ID
			$session->set( 'AuthManager:lastAuthId', $user->getId() + 1 );
			$session->set( 'AuthManager:lastAuthTimestamp', time() - 5 );
			$this->assertSame(
				AuthManager::SEC_REAUTH, $this->manager->securitySensitiveOperationStatus( 'foo' )
			);
			$this->assertSame(
				AuthManager::SEC_REAUTH, $this->manager->securitySensitiveOperationStatus( 'test' )
			);
			$this->assertSame(
				AuthManager::SEC_OK, $this->manager->securitySensitiveOperationStatus( 'test2' )
			);

			// Missing time
			$session->set( 'AuthManager:lastAuthId', $user->getId() );
			$session->set( 'AuthManager:lastAuthTimestamp', null );
			$this->assertSame(
				AuthManager::SEC_REAUTH, $this->manager->securitySensitiveOperationStatus( 'foo' )
			);
			$this->assertSame(
				AuthManager::SEC_REAUTH, $this->manager->securitySensitiveOperationStatus( 'test' )
			);
			$this->assertSame(
				AuthManager::SEC_OK, $this->manager->securitySensitiveOperationStatus( 'test2' )
			);

			// Recent enough to pass
			$session->set( 'AuthManager:lastAuthTimestamp', time() - 5 );
			$this->assertSame(
				AuthManager::SEC_OK, $this->manager->securitySensitiveOperationStatus( 'foo' )
			);

			// Not recent enough to pass
			$session->set( 'AuthManager:lastAuthTimestamp', time() - 20 );
			$this->assertSame(
				AuthManager::SEC_REAUTH, $this->manager->securitySensitiveOperationStatus( 'foo' )
			);
			// But recent enough for the 'test' operation
			$this->assertSame(
				AuthManager::SEC_OK, $this->manager->securitySensitiveOperationStatus( 'test' )
			);
		} else {
			$this->config->set( 'AllowSecuritySensitiveOperationIfCannotReauthenticate', array(
				'test' => false,
				'default' => true,
			) );

			$this->assertEquals(
				AuthManager::SEC_OK, $this->manager->securitySensitiveOperationStatus( 'foo' )
			);

			$this->assertEquals(
				AuthManager::SEC_FAIL, $this->manager->securitySensitiveOperationStatus( 'test' )
			);
		}

		// Test hook, all three possible values
		foreach ( array(
			AuthManager::SEC_OK => AuthManager::SEC_OK,
			AuthManager::SEC_REAUTH => $reauth,
			AuthManager::SEC_FAIL => AuthManager::SEC_FAIL,
		) as $hook => $expect ) {
			$this->hook( 'SecuritySensitiveOperationStatus', $this->exactly( 2 ) )
				->with(
					$this->anything(),
					$this->anything(),
					$this->callback( function ( $s ) use ( $session ) {
						return $s->getId() === $session->getId();
					} ),
					$mutableSession ? $this->equalTo( 500, 1 ) : $this->equalTo( -1 )
				)
				->will( $this->returnCallback( function ( &$v ) use ( $hook ) {
					$v = $hook;
					return true;
				} ) );
			$session->set( 'AuthManager:lastAuthTimestamp', time() - 500 );
			$this->assertEquals(
				$expect, $this->manager->securitySensitiveOperationStatus( 'test' ), "hook $hook"
			);
			$this->assertEquals(
				$expect, $this->manager->securitySensitiveOperationStatus( 'test2' ), "hook $hook"
			);
			$this->unhook( 'SecuritySensitiveOperationStatus' );
		}

		\ScopedCallback::consume( $reset );
	}

	public function onSecuritySensitiveOperationStatus( &$status, $operation, $session, $time ) {
	}

	public static function provideSecuritySensitiveOperationStatus() {
		return array(
			array( true ),
			array( false ),
		);
	}

	/**
	 * @dataProvider provideUserCanAuthenticate
	 * @param bool $primary1Can
	 * @param bool $primary2Can
	 * @param bool $expect
	 */
	public function testUserCanAuthenticate( $primary1Can, $primary2Can, $expect ) {
		$mock1 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock1->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary1' ) );
		$mock1->expects( $this->any() )->method( 'testUserCanAuthenticate' )
			->with( $this->equalTo( 'UTSysop' ) )
			->will( $this->returnValue( $primary1Can ) );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary2' ) );
		$mock2->expects( $this->any() )->method( 'testUserCanAuthenticate' )
			->with( $this->equalTo( 'UTSysop' ) )
			->will( $this->returnValue( $primary2Can ) );
		$this->primaryauthMocks = array( $mock1, $mock2 );

		$this->initializeManager( true );
		$this->assertSame( $expect, $this->manager->userCanAuthenticate( 'UTSysop' ) );
	}

	public static function provideUserCanAuthenticate() {
		return array(
			array( false, false, false ),
			array( true, false, true ),
			array( false, true, true ),
			array( true, true, true ),
		);
	}

	public function testRevokeAccessForUser() {
		$this->initializeManager();

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary' ) );
		$mock->expects( $this->once() )->method( 'providerRevokeAccessForUser' )
			->with( $this->equalTo( 'UTSysop' ) );
		$this->primaryauthMocks = array( $mock );

		$this->initializeManager( true );
		$this->logger->setCollect( true );

		$this->manager->revokeAccessForUser( 'UTSysop' );

		$this->assertSame( array(
			array( LogLevel::INFO, 'Revoking access for UTSysop' ),
		), $this->logger->getBuffer() );
	}

	public function testProviderCreation() {
		$mocks = array(
			'pre' => $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PreAuthenticationProvider' ),
			'primary' => $this->getMockForAbstractClass(
				'MediaWiki\\Auth\\PrimaryAuthenticationProvider'
			),
			'secondary' => $this->getMockForAbstractClass(
				'MediaWiki\\Auth\\SecondaryAuthenticationProvider'
			),
		);
		foreach ( $mocks as $key => $mock ) {
			$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( $key ) );
			$mock->expects( $this->once() )->method( 'setLogger' );
			$mock->expects( $this->once() )->method( 'setManager' );
			$mock->expects( $this->once() )->method( 'setConfig' );
		}
		$this->preauthMocks = array( $mocks['pre'] );
		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );

		// Normal operation
		$this->initializeManager();
		$this->assertSame(
			$mocks['primary'],
			$this->managerPriv->getAuthenticationProvider( 'primary' )
		);
		$this->assertSame(
			$mocks['secondary'],
			$this->managerPriv->getAuthenticationProvider( 'secondary' )
		);
		$this->assertSame(
			$mocks['pre'],
			$this->managerPriv->getAuthenticationProvider( 'pre' )
		);
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
		$mock1 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PreAuthenticationProvider' );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock1->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$this->preauthMocks = array( $mock1 );
		$this->primaryauthMocks = array( $mock2 );
		$this->secondaryauthMocks = array();
		$this->initializeManager( true );
		try {
			$this->managerPriv->getAuthenticationProvider( 'Y' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \RuntimeException $ex ) {
			$class1 = get_class( $mock1 );
			$class2 = get_class( $mock2 );
			$this->assertSame(
				"Duplicate specifications for id X (classes $class1 and $class2)", $ex->getMessage()
			);
		}

		// Wrong classes
		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$class = get_class( $mock );
		$this->preauthMocks = array( $mock );
		$this->primaryauthMocks = array( $mock );
		$this->secondaryauthMocks = array( $mock );
		$this->initializeManager( true );
		try {
			$this->managerPriv->getPreAuthenticationProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of MediaWiki\\Auth\\PreAuthenticationProvider, got $class",
				$ex->getMessage()
			);
		}
		try {
			$this->managerPriv->getPrimaryAuthenticationProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of MediaWiki\\Auth\\PrimaryAuthenticationProvider, got $class",
				$ex->getMessage()
			);
		}
		try {
			$this->managerPriv->getSecondaryAuthenticationProviders();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of MediaWiki\\Auth\\SecondaryAuthenticationProvider, got $class",
				$ex->getMessage()
			);
		}
	}

	public function testForcePrimaryAuthenticationProviders() {
		$mockA = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mockB = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mockB2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mockA->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'A' ) );
		$mockB->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'B' ) );
		$mockB2->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'B' ) );
		$this->primaryauthMocks = array( $mockA );

		$this->logger = new \TestLogger( true );

		// Test without first initializing the configured providers
		$this->initializeManager();
		$this->manager->forcePrimaryAuthenticationProviders( array( $mockB ), 'testing' );
		$this->assertSame(
			array( 'B' => $mockB ), $this->managerPriv->getPrimaryAuthenticationProviders()
		);
		$this->assertSame( null, $this->managerPriv->getAuthenticationProvider( 'A' ) );
		$this->assertSame( $mockB, $this->managerPriv->getAuthenticationProvider( 'B' ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Overriding AuthManager primary authn because testing' ),
		), $this->logger->getBuffer() );
		$this->logger->clearBuffer();

		// Test with first initializing the configured providers
		$this->initializeManager();
		$this->assertSame( $mockA, $this->managerPriv->getAuthenticationProvider( 'A' ) );
		$this->assertSame( null, $this->managerPriv->getAuthenticationProvider( 'B' ) );
		$this->request->setSessionData( 'AuthManager::authnState', 'test' );
		$this->request->setSessionData( 'AuthManager::accountCreationState', 'test' );
		$this->manager->forcePrimaryAuthenticationProviders( array( $mockB ), 'testing' );
		$this->assertSame(
			array( 'B' => $mockB ), $this->managerPriv->getPrimaryAuthenticationProviders()
		);
		$this->assertSame( null, $this->managerPriv->getAuthenticationProvider( 'A' ) );
		$this->assertSame( $mockB, $this->managerPriv->getAuthenticationProvider( 'B' ) );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::authnState' ) );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Overriding AuthManager primary authn because testing' ),
			array(
				LogLevel::WARNING,
				'PrimaryAuthenticationProviders have already been accessed! I hope nothing breaks.'
			),
		), $this->logger->getBuffer() );
		$this->logger->clearBuffer();

		// Test duplicate IDs
		$this->initializeManager();
		try {
			$this->manager->forcePrimaryAuthenticationProviders( array( $mockB, $mockB2 ), 'testing' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \RuntimeException $ex ) {
			$class1 = get_class( $mockB );
			$class2 = get_class( $mockB2 );
			$this->assertSame(
				"Duplicate specifications for id B (classes $class2 and $class1)", $ex->getMessage()
			);
		}

		// Wrong classes
		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$class = get_class( $mock );
		try {
			$this->manager->forcePrimaryAuthenticationProviders( array( $mock ), 'testing' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \RuntimeException $ex ) {
			$this->assertSame(
				"Expected instance of MediaWiki\\Auth\\PrimaryAuthenticationProvider, got $class",
				$ex->getMessage()
			);
		}

	}

	public function testBeginAuthentication() {
		$this->initializeManager();

		// Immutable session
		list( $provider, $reset ) = $this->getMockSessionProvider( false );
		$this->hook( 'UserLoggedIn', $this->never() );
		$this->request->setSessionData( 'AuthManager::authnState', 'test' );
		try {
			$this->manager->beginAuthentication( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \LogicException $ex ) {
			$this->assertSame( 'Authentication is not possible now', $ex->getMessage() );
		}
		$this->unhook( 'UserLoggedIn' );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::authnState' ) );
		\ScopedCallback::consume( $reset );
		$this->initializeManager( true );

		// CreatedAccountAuthenticationRequest
		$user = \User::newFromName( 'UTSysop' );
		$reqs = array(
			new CreatedAccountAuthenticationRequest( $user->getId(), $user->getName() )
		);
		$this->hook( 'UserLoggedIn', $this->never() );
		try {
			$this->manager->beginAuthentication( $reqs );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \LogicException $ex ) {
			$this->assertSame(
				'CreatedAccountAuthenticationRequests are only valid on the same AuthManager ' .
					'that created the account',
				$ex->getMessage()
			);
		}
		$this->unhook( 'UserLoggedIn' );

		$this->request->getSession()->clear();
		$this->request->setSessionData( 'AuthManager::authnState', 'test' );
		$this->managerPriv->createdAccountAuthenticationRequests = array( $reqs[0] );
		$this->hook( 'UserLoggedIn', $this->once() )
			->with( $this->callback( function ( $u ) use ( $user ) {
				return $user->getId() === $u->getId() && $user->getName() === $u->getName();
			} ) );
		$this->hook( 'AuthManagerLoginAuthenticateAudit', $this->once() );
		$this->logger->setCollect( true );
		$ret = $this->manager->beginAuthentication( $reqs );
		$this->logger->setCollect( false );
		$this->unhook( 'UserLoggedIn' );
		$this->unhook( 'AuthManagerLoginAuthenticateAudit' );
		$this->assertSame( AuthenticationResponse::PASS, $ret->status );
		$this->assertSame( $user->getName(), $ret->username );
		$this->assertSame( $user->getId(), $this->request->getSessionData( 'AuthManager:lastAuthId' ) );
		$this->assertEquals(
			time(), $this->request->getSessionData( 'AuthManager:lastAuthTimestamp' ),
			'timestamp ±1', 1
		);
		$this->assertNull( $this->request->getSessionData( 'AuthManager::authnState' ) );
		$this->assertSame( $user->getId(), $this->request->getSession()->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::INFO, 'Logging in UTSysop after account creation' ),
		), $this->logger->getBuffer() );
	}

	/**
	 * @dataProvider provideAuthentication
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
		$user = \User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();

		// Set up lots of mocks...
		$that = $this;
		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req->pre = $preResponse;
		$req->primary = $primaryResponses;
		$req->secondary = $secondaryResponses;
		$clazz = get_class( $req );
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key ) );
			$mocks[$key . '2'] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key . '2']->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key . '2' ) );
			$mocks[$key . '3'] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key . '3']->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key . '3' ) );
		}
		foreach ( $mocks as $mock ) {
			$mock->expects( $this->any() )->method( 'getAuthenticationRequestTypes' )
				->will( $this->returnValue( array( 'X' ) ) );
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
			$mocks['primary']->expects( $this->any() )->method( 'accountCreationType' )
				->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
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
		$mocks['pre2']->expects( new \InvokedAtMost( 1 ) )->method( 'testForAuthentication' )
			->will( $this->returnValue( StatusValue::newGood() ) );
		$mocks['primary2']->expects( new \InvokedAtMost( 1 ) )->method( 'beginPrimaryAuthentication' )
				->will( $this->returnValue( $abstain ) );
		$mocks['primary2']->expects( $this->never() )->method( 'continuePrimaryAuthentication' );
		$mocks['secondary2']->expects( new \InvokedAtMost( 1 ) )->method( 'beginSecondaryAuthentication' )
				->will( $this->returnValue( $abstain ) );
		$mocks['secondary2']->expects( $this->never() )->method( 'continueSecondaryAuthentication' );
		$mocks['secondary3']->expects( new \InvokedAtMost( 1 ) )->method( 'beginSecondaryAuthentication' )
				->will( $this->returnValue( $abstain ) );
		$mocks['secondary3']->expects( $this->never() )->method( 'continueSecondaryAuthentication' );

		$this->preauthMocks = array( $mocks['pre'], $mocks['pre2'] );
		$this->primaryauthMocks = array( $mocks['primary'], $mocks['primary2'] );
		$this->secondaryauthMocks = array(
			$mocks['secondary3'], $mocks['secondary'], $mocks['secondary2']
		);
		$this->initializeManager( true );
		$this->logger->setCollect( true );

		$session = $this->request->getSession();

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
			if ( $success || (
					$response instanceof AuthenticationResponse &&
					$response->status === AuthenticationResponse::FAIL &&
					$response->message->getKey() !== 'authmanager-authn-not-in-progress' &&
					$response->message->getKey() !== 'authmanager-authn-no-primary'
				)
			) {
				$this->hook( 'AuthManagerLoginAuthenticateAudit', $this->once() );
			} else {
				$this->hook( 'AuthManagerLoginAuthenticateAudit', $this->never() );
			}

			$ex = null;
			try {
				if ( !$i ) {
					$ret = $this->manager->beginAuthentication( array( $req ) );
				} else {
					$ret = $this->manager->continueAuthentication( array( $req ) );
				}
				if ( $response instanceof \Exception ) {
					$this->fail( 'Expected exception not thrown', "Response $i" );
				}
			} catch ( \Exception $ex ) {
				if ( !$response instanceof \Exception ) {
					throw $ex;
				}
				$this->assertEquals( $response->getMessage(), $ex->getMessage(), "Response $i, exception" );
				$this->assertNull( $session->get( 'AuthManager::authnState' ),
				   "Response $i, exception, session state" );
				$this->unhook( 'UserLoggedIn' );
				$this->unhook( 'AuthManagerLoginAuthenticateAudit' );
				return;
			}

			$this->unhook( 'UserLoggedIn' );
			$this->unhook( 'AuthManagerLoginAuthenticateAudit' );
			$ret->message = $this->messageSpecifier( $ret->message );
			$this->assertEquals( $response, $ret, "Response $i, response" );
			if ( $success ) {
				$this->assertSame( $id, $session->getUser()->getId(),
				   "Response $i, authn" );
			} else {
				$this->assertSame( 0, $session->getUser()->getId(),
				   "Response $i, authn" );
			}
			if ( $success || $response->status === AuthenticationResponse::FAIL ) {
				$this->assertNull( $session->get( 'AuthManager::authnState' ),
				   "Response $i, session state" );
			} else {
				$this->assertNotNull( $session->get( 'AuthManager::authnState' ),
				   "Response $i, session state" );
			}

			$maybeLink = $session->get( 'AuthManager::maybeLink' );
			if ( $link && $response->status === AuthenticationResponse::RESTART ) {
				$this->assertSame( array( $response->createRequest ), $maybeLink, "Response $i, maybeLink" );
			} else {
				$this->assertNull( $maybeLink, "Response $i, maybeLink" );
			}
		}

		// Make sure at least one 'info'-level response was returned
		$ok = false;
		foreach ( $this->logger->getBuffer() as $log ) {
			if ( $log[0] !== LogLevel::DEBUG ) {
				$ok = true;
				break;
			}
		}
		$this->assertTrue( $ok, 'Logged at least one info-level entry during login' );
	}

	public function provideAuthentication() {
		$user = \User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();

		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req->foobar = 'baz';
		$restartResponse = AuthenticationResponse::newRestart(
			$this->messageSpecifier( 'authmanager-authn-no-local-user' )
		);
		$restartResponse->neededRequests = array( 'X' );
		$restartResponse->createRequest = $req;
		$restartResponse2 = AuthenticationResponse::newRestart(
			$this->messageSpecifier( 'authmanager-authn-no-local-user-link' )
		);
		$restartResponse2->neededRequests = array( 'X' );
		$restartResponse2->createRequest = $req;

		return array(
			array(
				'Failure in pre-auth',
				StatusValue::newFatal( 'fail-from-pre' ),
				array(),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-pre' ) ),
					AuthenticationResponse::newFail(
						$this->messageSpecifier( 'authmanager-authn-not-in-progress' )
					),
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
					new \DomainException(
						'MockPrimaryAuthenticationProvider::continuePrimaryAuthentication() returned ABSTAIN'
					)
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
					$restartResponse,
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
					$restartResponse2,
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
					new \DomainException( 'MockPrimaryAuthenticationProvider returned an invalid username: <>' ),
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
			array(
				'Secondary pass',
				StatusValue::newGood(),
				array(
					AuthenticationResponse::newPass( $name ),
				),
				array(
					AuthenticationResponse::newPass()
				),
				array(
					AuthenticationResponse::newPass( $name ),
				)
			),
		);
	}

	/**
	 * @dataProvider provideUserExists
	 * @param bool $primary1Exists
	 * @param bool $primary2Exists
	 * @param bool $expect
	 */
	public function testUserExists( $primary1Exists, $primary2Exists, $expect ) {
		$mock1 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock1->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary1' ) );
		$mock1->expects( $this->any() )->method( 'testUserExists' )
			->with( $this->equalTo( 'UTSysop' ) )
			->will( $this->returnValue( $primary1Exists ) );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary2' ) );
		$mock2->expects( $this->any() )->method( 'testUserExists' )
			->with( $this->equalTo( 'UTSysop' ) )
			->will( $this->returnValue( $primary2Exists ) );
		$this->primaryauthMocks = array( $mock1, $mock2 );

		$this->initializeManager( true );
		$this->assertSame( $expect, $this->manager->userExists( 'UTSysop' ) );
	}

	public static function provideUserExists() {
		return array(
			array( false, false, false ),
			array( true, false, true ),
			array( false, true, true ),
			array( true, true, true ),
		);
	}

	public function testAllowsAuthenticationDataChangeType() {
		$mock1 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock1->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( '1' ) );
		$mock1->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChangeType' )
			->will( $this->returnValue( true ) );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( '2' ) );
		$mock2->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChangeType' )
			->will( $this->returnValue( false ) );

		$this->primaryauthMocks = array( $mock1 );
		$this->initializeManager( true );
		$this->assertTrue( $this->manager->allowsAuthenticationDataChangeType( 'Foo' ) );

		$this->primaryauthMocks = array( $mock2 );
		$this->initializeManager( true );
		$this->assertFalse( $this->manager->allowsAuthenticationDataChangeType( 'Foo' ) );

		$this->primaryauthMocks = array( $mock1, $mock2 );
		$this->initializeManager( true );
		$this->assertFalse( $this->manager->allowsAuthenticationDataChangeType( 'Foo' ) );
	}

	/**
	 * @dataProvider provideAllowsAuthenticationDataChange
	 * @param StatusValue $primaryReturn
	 * @param StatusValue $secondaryReturn
	 * @param Status $expect
	 */
	public function testAllowsAuthenticationDataChange( $primaryReturn, $secondaryReturn, $expect ) {
		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );

		$mock1 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock1->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( '1' ) );
		$mock1->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->with( $this->equalTo( $req ) )
			->will( $this->returnValue( $primaryReturn ) );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\SecondaryAuthenticationProvider' );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( '2' ) );
		$mock2->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->with( $this->equalTo( $req ) )
			->will( $this->returnValue( $secondaryReturn ) );

		$this->primaryauthMocks = array( $mock1 );
		$this->secondaryauthMocks = array( $mock2 );
		$this->initializeManager( true );
		$this->assertEquals( $expect, $this->manager->allowsAuthenticationDataChange( $req ) );
	}

	public static function provideAllowsAuthenticationDataChange() {
		$ignored = \Status::newGood( 'ignored' );
		$ignored->warning( 'authmanager-change-not-supported' );

		$okFromPrimary = StatusValue::newGood();
		$okFromPrimary->warning( 'warning-from-primary' );
		$okFromSecondary = StatusValue::newGood();
		$okFromSecondary->warning( 'warning-from-secondary' );

		return array(
			array(
				StatusValue::newGood(),
				StatusValue::newGood(),
				\Status::newGood(),
			),
			array(
				StatusValue::newGood( 'ignored' ),
				StatusValue::newGood(),
				$ignored,
			),
			array(
				StatusValue::newFatal( 'fail from primary' ),
				StatusValue::newGood(),
				\Status::newFatal( 'fail from primary' ),
			),
			array(
				$okFromPrimary,
				StatusValue::newGood(),
				\Status::wrap( $okFromPrimary ),
			),
			array(
				StatusValue::newGood(),
				StatusValue::newFatal( 'fail from secondary' ),
				\Status::newFatal( 'fail from secondary' ),
			),
			array(
				StatusValue::newGood(),
				$okFromSecondary,
				\Status::wrap( $okFromSecondary ),
			),
		);
	}

	public function testChangeAuthenticationData() {
		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req->username = 'UTSysop';

		$mock1 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock1->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( '1' ) );
		$mock1->expects( $this->once() )->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( '2' ) );
		$mock2->expects( $this->once() )->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );

		$this->primaryauthMocks = array( $mock1, $mock2 );
		$this->initializeManager( true );
		$this->logger->setCollect( true );
		$this->manager->changeAuthenticationData( $req );
		$this->assertSame( array(
			array( LogLevel::INFO, 'Changing authentication data for UTSysop' ),
		), $this->logger->getBuffer() );
	}

	public function testCanCreateAccounts() {
		$types = array(
			PrimaryAuthenticationProvider::TYPE_CREATE => true,
			PrimaryAuthenticationProvider::TYPE_LINK => true,
			PrimaryAuthenticationProvider::TYPE_NONE => false,
		);

		foreach ( $types as $type => $can ) {
			$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
			$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( $type ) );
			$mock->expects( $this->any() )->method( 'accountCreationType' )
				->will( $this->returnValue( $type ) );
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
		} while ( \User::newFromName( $username )->getId() !== 0 );
		return $username;
	}

	public function testBeginAccountCreation() {
		$that = $this;
		$creator = \User::newFromName( 'UTSysop' );
		$this->logger = new \TestLogger( false, function ( $message, $level ) {
			return $level === LogLevel::DEBUG ? null : $message;
		} );
		$this->initializeManager();

		$this->request->setSessionData( 'AuthManager::accountCreationState', 'test' );
		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$this->manager->beginAccountCreation( self::usernameForCreation(), $creator, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \LogicException $ex ) {
			$this->assertEquals( 'Account creation is not possible', $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ) );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( true ) );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAccountCreation( self::usernameForCreation(), $creator, array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'userexists', $ret->message->getKey() );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( false ) );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAccountCreation(
			self::usernameForCreation() . '<>', $creator, array()
		);
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'noname', $ret->message->getKey() );

		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAccountCreation( $creator->getName(), $creator, array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'userexists', $ret->message->getKey() );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( false ) );
		$mock->expects( $this->any() )->method( 'testForAccountCreation' )
			->will( $this->returnValue( StatusValue::newFatal( 'fail' ) ) );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$username = self::usernameForCreation();
		$req = $this->getMockBuilder( 'MediaWiki\\Auth\\UserDataAuthenticationRequest' )
			->setMethods( array( 'populateUser' ) )
			->getMock();
		$req->expects( $this->once() )->method( 'populateUser' )
			->will( $this->returnCallback( function ( $user ) use ( $that, $username ) {
				$that->assertSame( $username, $user->getName() );
			} ) );
		$this->manager->beginAccountCreation( $username, $creator, array( $req ) );
	}

	public function testContinueAccountCreation() {
		$that = $this;
		$creator = \User::newFromName( 'UTSysop' );
		$username = self::usernameForCreation();
		$this->logger = new \TestLogger( false, function ( $message, $level ) {
			return $level === LogLevel::DEBUG ? null : $message;
		} );
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
		} catch ( \LogicException $ex ) {
			$this->assertEquals( 'Account creation is not possible', $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( false ) );
		$mock->expects( $this->any() )->method( 'beginPrimaryAccountCreation' )->will(
			$this->returnValue( AuthenticationResponse::newFail( $this->messageSpecifier( 'fail' ) ) )
		);
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->request->setSessionData( 'AuthManager::accountCreationState', null );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAccountCreation( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-create-not-in-progress', $ret->message->getKey() );

		$this->request->setSessionData( 'AuthManager::accountCreationState',
			array( 'username' => "$username<>" ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAccountCreation( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'noname', $ret->message->getKey() );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ) );

		$this->request->setSessionData( 'AuthManager::accountCreationState',
			array( 'username' => $creator->getName() ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAccountCreation( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'userexists', $ret->message->getKey() );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ) );

		$this->request->setSessionData( 'AuthManager::accountCreationState',
			array( 'userid' => $creator->getId() ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$ret = $this->manager->continueAccountCreation( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertEquals( "User \"{$username}\" should exist now, but doesn't!", $ex->getMessage() );
		}
		$this->unhook( 'LocalUserCreated' );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ) );

		$id = $creator->getId();
		$name = $creator->getName();
		$this->request->setSessionData( 'AuthManager::accountCreationState',
			array( 'username' => $name, 'userid' => $id + 1 ) + $session );
		$this->hook( 'LocalUserCreated', $this->never() );
		try {
			$ret = $this->manager->continueAccountCreation( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertEquals(
				"User \"{$name}\" exists, but ID $id != " . ( $id + 1 ) . '!', $ex->getMessage()
			);
		}
		$this->unhook( 'LocalUserCreated' );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ) );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( false ) );
		$mock->expects( $this->any() )->method( 'beginPrimaryAccountCreation' )->will(
			$this->returnValue( AuthenticationResponse::newFail( $this->messageSpecifier( 'fail' ) ) )
		);
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$req = $this->getMockBuilder( 'MediaWiki\\Auth\\UserDataAuthenticationRequest' )
			->setMethods( array( 'populateUser' ) )
			->getMock();
		$req->expects( $this->once() )->method( 'populateUser' );
		$this->request->setSessionData( 'AuthManager::accountCreationState',
			array( 'reqs' => array( $req ) ) + $session );
		$this->manager->continueAccountCreation( array() );
	}

	/**
	 * @dataProvider provideAccountCreation
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
		$creator = \User::newFromName( 'UTSysop' );
		$username = self::usernameForCreation();

		$this->initializeManager();

		// Set up lots of mocks...
		$that = $this;
		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req->preTest = $preTest;
		$req->primaryTest = $primaryTest;
		$req->secondaryTest = $secondaryTest;
		$req->primary = $primaryResponses;
		$req->secondary = $secondaryResponses;
		$clazz = get_class( $req );
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key ) );
			$mocks[$key]->expects( $this->any() )->method( 'testForAccountCreation' )
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
				$mocks[$key . $i] = $this->getMockForAbstractClass(
					"MediaWiki\\Auth\\$class", array(), "Mock$class"
				);
				$mocks[$key . $i]->expects( $this->any() )->method( 'getUniqueId' )
					->will( $this->returnValue( $key . $i ) );
				$mocks[$key . $i]->expects( new \InvokedAtMost( 1 ) )->method( 'testForAccountCreation' )
					->will( $this->returnValue( StatusValue::newGood() ) );
			}
		}

		$mocks['primary']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mocks['primary']->expects( $this->any() )->method( 'testUserExists' )
			->will( $this->returnValue( false ) );
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
		$mocks['primary2']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
		$mocks['primary2']->expects( $this->any() )->method( 'testUserExists' )
			->will( $this->returnValue( false ) );
		$mocks['primary2']->expects( new \InvokedAtMost( 1 ) )->method( 'beginPrimaryAccountCreation' )
			->will( $this->returnValue( $abstain ) );
		$mocks['primary2']->expects( $this->never() )->method( 'continuePrimaryAccountCreation' );
		$mocks['primary3']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_NONE ) );
		$mocks['primary3']->expects( $this->any() )->method( 'testUserExists' )
			->will( $this->returnValue( false ) );
		$mocks['primary3']->expects( $this->never() )->method( 'beginPrimaryAccountCreation' );
		$mocks['primary3']->expects( $this->never() )->method( 'continuePrimaryAccountCreation' );
		$mocks['secondary2']->expects( new \InvokedAtMost( 1 ) )
			->method( 'beginSecondaryAccountCreation' )
			->will( $this->returnValue( $abstain ) );
		$mocks['secondary2']->expects( $this->never() )->method( 'continueSecondaryAccountCreation' );
		$mocks['secondary3']->expects( new \InvokedAtMost( 1 ) )
			->method( 'beginSecondaryAccountCreation' )
			->will( $this->returnValue( $abstain ) );
		$mocks['secondary3']->expects( $this->never() )->method( 'continueSecondaryAccountCreation' );

		$this->preauthMocks = array( $mocks['pre'], $mocks['pre2'] );
		$this->primaryauthMocks = array( $mocks['primary3'], $mocks['primary'], $mocks['primary2'] );
		$this->secondaryauthMocks = array(
			$mocks['secondary3'], $mocks['secondary'], $mocks['secondary2']
		);

		$this->logger = new \TestLogger( true, function ( $message, $level ) {
			return $level === LogLevel::DEBUG ? null : $message;
		} );
		$expectLog = array();
		$this->initializeManager( true );

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
				$expectLog[] = array( LogLevel::INFO, "Creating user $username during account creation" );
			} else {
				$this->hook( 'LocalUserCreated', $this->never() );
			}

			$ex = null;
			try {
				if ( $first ) {
					$ret = $this->manager->beginAccountCreation( $username, $creator, array( $req ) );
				} else {
					$ret = $this->manager->continueAccountCreation( array( $req ) );
				}
				if ( $response instanceof \Exception ) {
					$this->fail( 'Expected exception not thrown', "Response $i" );
				}
			} catch ( \Exception $ex ) {
				if ( !$response instanceof \Exception ) {
					throw $ex;
				}
				$this->assertEquals( $response->getMessage(), $ex->getMessage(), "Response $i, exception" );
				$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ),
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
				$this->assertNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ),
				   "Response $i, session state" );
			} else {
				$this->assertNotNull( $this->request->getSessionData( 'AuthManager::accountCreationState' ),
				   "Response $i, session state" );
			}

			if ( $created ) {
				$this->assertNotEquals( 0, \User::idFromName( $username ) );
			} else {
				$this->assertEquals( 0, \User::idFromName( $username ) );
			}

			$first = false;
		}

		$this->assertSame( $expectLog, $this->logger->getBuffer() );
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
					new \DomainException(
						'MockPrimaryAuthenticationProvider::continuePrimaryAccountCreation() returned ABSTAIN'
					)
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
					'created' => new \DomainException(
						'MockSecondaryAuthenticationProvider::beginSecondaryAccountCreation() returned FAIL. ' .
							'Secondary providers are not allowed to fail account creation, ' .
							'that should have been done via testForAccountCreation().'
					)
				)
			),
		);
	}

	public function testAutoAccountCreation() {
		global $wgGroupPermissions, $wgHooks;

		// PHPUnit seems to have a bug where it will call the ->with()
		// callbacks for our hooks again after the test is run (WTF?), which
		// breaks here because $username no longer matches $user by the end of
		// the testing.
		$workaroundPHPUnitBug = false;

		$username = self::usernameForCreation();
		$this->initializeManager();

		$this->stashMwGlobals( array( 'wgGroupPermissions' ) );
		$wgGroupPermissions['*']['createaccount'] = true;
		$wgGroupPermissions['*']['autocreateaccount'] = false;

		\ObjectCache::$instances[__METHOD__] = new \HashBagOStuff();
		$this->setMwGlobals( array( 'wgMainCacheType' => __METHOD__ ) );

		// Set up lots of mocks...
		$mocks = array();
		foreach ( array( 'pre', 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key ) );
		}

		$good = StatusValue::newGood();
		$callback = $this->callback( function ( $user ) use ( &$username, &$workaroundPHPUnitBug ) {
			return $workaroundPHPUnitBug || $user->getName() === $username;
		} );

		$mocks['pre']->expects( $this->exactly( 8 ) )->method( 'testForAutoCreation' )
			->with( $callback )
			->will( $this->onConsecutiveCalls(
				StatusValue::newFatal( 'ok' ), StatusValue::newFatal( 'ok' ), // For testing permissions
				StatusValue::newFatal( 'fail-in-pre' ), $good, $good,
				$good, // backoff test
				$good, $good // success
			) );

		$mocks['primary']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mocks['primary']->expects( $this->any() )->method( 'testUserExists' )
			->will( $this->returnValue( true ) );
		$mocks['primary']->expects( $this->exactly( 5 ) )->method( 'testForAutoCreation' )
			->with( $callback )
			->will( $this->onConsecutiveCalls(
				StatusValue::newFatal( 'fail-in-primary' ), $good,
				$good, // backoff test
				$good, $good
			) );
		$mocks['primary']->expects( $this->exactly( 2 ) )->method( 'autoCreatedAccount' )
			->with( $callback );

		$mocks['secondary']->expects( $this->exactly( 4 ) )->method( 'testForAutoCreation' )
			->with( $callback )
			->will( $this->onConsecutiveCalls(
				StatusValue::newFatal( 'fail-in-secondary' ),
				$good, // backoff test
				$good, $good
			) );
		$mocks['secondary']->expects( $this->exactly( 2 ) )->method( 'autoCreatedAccount' )
			->with( $callback );

		$this->preauthMocks = array( $mocks['pre'] );
		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );
		$session = $this->request->getSession();

		$logger = new \TestLogger( true, function ( $m ) {
			$m = str_replace( 'MediaWiki\\Auth\\AuthManager::autoCreateUser: ', '', $m );
			$m = preg_replace( '/ - from: .*$/', ' - from: XXX', $m );
			return $m;
		} );
		$this->manager->setLogger( $logger );

		// First, check an existing user
		$session->clear();
		$user = \User::newFromName( 'UTSysop' );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$expect = \Status::newGood();
		$expect->warning( 'userexists' );
		$this->assertEquals( $expect, $ret );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSysop', $user->getName() );
		$this->assertEquals( $user->getId(), $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'already exists locally' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$session->clear();
		$user = \User::newFromName( 'UTSysop' );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, false );
		$this->unhook( 'LocalUserCreated' );
		$expect = \Status::newGood();
		$expect->warning( 'userexists' );
		$this->assertEquals( $expect, $ret );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertSame( 'UTSysop', $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'already exists locally' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Wiki is read-only
		$session->clear();
		$this->setMwGlobals( array( 'wgReadOnly' => 'Because' ) );
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'readonlytext', 'Because' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'denied by wfReadOnly(): Because' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->setMwGlobals( array( 'wgReadOnly' => false ) );

		// Session blacklisted
		$session->clear();
		$session->set( 'AuthManager::AutoCreateBlacklist', 'test' );
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'test' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'blacklisted in session' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$session->clear();
		$session->set( 'AuthManager::AutoCreateBlacklist', StatusValue::newFatal( 'test2' ) );
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'test2' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'blacklisted in session' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Uncreatable name
		$session->clear();
		$user = \User::newFromName( $username . '@' );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'noname' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username . '@', $user->getId() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'name is not creatable' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->assertSame( 'noname', $session->get( 'AuthManager::AutoCreateBlacklist' ) );

		// IP unable to create accounts
		$wgGroupPermissions['*']['createaccount'] = false;
		$wgGroupPermissions['*']['autocreateaccount'] = false;
		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'authmanager-autocreate-noperm' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'IP lacks the ability to create or autocreate accounts' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->assertSame(
			'authmanager-autocreate-noperm', $session->get( 'AuthManager::AutoCreateBlacklist' )
		);

		// Test that both permutations of permissions are allowed
		// (this hits the two "ok" entries in $mocks['pre'])
		$wgGroupPermissions['*']['createaccount'] = false;
		$wgGroupPermissions['*']['autocreateaccount'] = true;
		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'ok' ), $ret );

		$wgGroupPermissions['*']['createaccount'] = true;
		$wgGroupPermissions['*']['autocreateaccount'] = false;
		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'ok' ), $ret );
		$logger->clearBuffer();

		// Test pre-authentication provider fail
		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'fail-in-pre' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'Provider denied creation: <fail-in-pre>' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->assertEquals(
			StatusValue::newFatal( 'fail-in-pre' ), $session->get( 'AuthManager::AutoCreateBlacklist' )
		);

		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'fail-in-primary' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'Provider denied creation: <fail-in-primary>' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->assertEquals(
			StatusValue::newFatal( 'fail-in-primary' ), $session->get( 'AuthManager::AutoCreateBlacklist' )
		);

		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'fail-in-secondary' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'Provider denied creation: <fail-in-secondary>' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->assertEquals(
			StatusValue::newFatal( 'fail-in-secondary' ), $session->get( 'AuthManager::AutoCreateBlacklist' )
		);

		// Test backoff
		$cache = \ObjectCache::getLocalClusterInstance();
		$backoffKey = wfMemcKey( 'AuthManager', 'autocreate-failed', md5( $username ) );
		$cache->set( $backoffKey, true );
		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newFatal( 'authmanager-autocreate-exception' ), $ret );
		$this->assertEquals( 0, $user->getId() );
		$this->assertNotEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::DEBUG, 'denied by prior creation attempt failures' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();
		$this->assertSame( null, $session->get( 'AuthManager::AutoCreateBlacklist' ) );
		$cache->delete( $backoffKey );

		// Success!
		$session->clear();
		$user = \User::newFromName( $username );
		$this->hook( 'AuthPluginAutoCreate', $this->once() )
			->with( $callback );
		$this->hideDeprecated( 'AuthPluginAutoCreate hook (used in ' .
				get_class( $wgHooks['AuthPluginAutoCreate'][0] ) . '::onAuthPluginAutoCreate)' );
		$this->hook( 'LocalUserCreated', $this->once() )
			->with( $callback, $this->equalTo( true ) );
		$ret = $this->manager->autoCreateUser( $user, true );
		$this->unhook( 'LocalUserCreated' );
		$this->unhook( 'AuthPluginAutoCreate' );
		$this->assertEquals( \Status::newGood(), $ret );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertEquals( $username, $user->getName() );
		$this->assertEquals( $user->getId(), $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::INFO, "creating new user ($username) - from: XXX" ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$session->clear();
		$username = self::usernameForCreation();
		$user = \User::newFromName( $username );
		$this->hook( 'LocalUserCreated', $this->once() )
			->with( $callback, $this->equalTo( true ) );
		$ret = $this->manager->autoCreateUser( $user, false );
		$this->unhook( 'LocalUserCreated' );
		$this->assertEquals( \Status::newGood(), $ret );
		$this->assertNotEquals( 0, $user->getId() );
		$this->assertEquals( $username, $user->getName() );
		$this->assertEquals( 0, $session->getUser()->getId() );
		$this->assertSame( array(
			array( LogLevel::INFO, "creating new user ($username) - from: XXX" ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$workaroundPHPUnitBug = true;
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
			$mocks[$key] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key ) );
			$mocks[$key]->expects( $this->any() )->method( 'getAuthenticationRequestTypes' )
				->will( $this->returnCallback( function ( $which ) use ( $key ) {
					return array( "$key-$which", 'generic' );
				} ) );
			$mocks[$key]->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChangeType' )
				->will( $this->returnValue( true ) );
		}

		$primaries = array();
		foreach ( array(
			PrimaryAuthenticationProvider::TYPE_NONE,
			PrimaryAuthenticationProvider::TYPE_CREATE,
			PrimaryAuthenticationProvider::TYPE_LINK
		) as $type ) {
			$class = 'PrimaryAuthenticationProvider';
			$mocks["primary-$type"] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks["primary-$type"]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( "primary-$type" ) );
			$mocks["primary-$type"]->expects( $this->any() )->method( 'accountCreationType' )
				->will( $this->returnValue( $type ) );
			$mocks["primary-$type"]->expects( $this->any() )->method( 'getAuthenticationRequestTypes' )
				->will( $this->returnCallback( function ( $which ) use ( $type ) {
					return array( "primary-$type-$which", 'generic' );
				} ) );
			$mocks["primary-$type"]->expects( $this->any() )
				->method( 'providerAllowsAuthenticationDataChangeType' )
				->will( $this->returnValue( true ) );
			$this->primaryauthMocks[] = $mocks["primary-$type"];
		}

		$mocks['primary2'] = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\PrimaryAuthenticationProvider', array(), "MockPrimaryAuthenticationProvider"
		);
		$mocks['primary2']->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'primary2' ) );
		$mocks['primary2']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
		$mocks['primary2']->expects( $this->any() )->method( 'getAuthenticationRequestTypes' )
			->will( $this->returnValue( array() ) );
		$mocks['primary2']->expects( $this->any() )
			->method( 'providerAllowsAuthenticationDataChangeType' )
			->will( $this->returnCallback( function ( $type ) {
				return $type !== 'generic';
			} ) );
		$this->primaryauthMocks[] = $mocks['primary2'];

		$this->preauthMocks = array( $mocks['pre'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );

		if ( $state ) {
			if ( $which === AuthManager::ACTION_LOGIN_CONTINUE ) {
				$this->request->setSessionData( 'AuthManager::authnState', $state );
			} elseif ( $which === AuthManager::ACTION_CREATE_CONTINUE ) {
				$this->request->setSessionData( 'AuthManager::accountCreationState', $state );
			} elseif ( $which === AuthManager::ACTION_LINK_CONTINUE ) {
				$this->request->setSessionData( 'AuthManager::accountLinkState', $state );
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
				AuthManager::ACTION_LOGIN,
				array( 'pre-login', 'primary-none-login', 'primary-create-login',
					'primary-link-login', 'secondary-login', 'generic' ),
			),
			array(
				AuthManager::ACTION_CREATE,
				array( 'pre-create', 'primary-none-create', 'primary-create-create',
					'primary-link-create', 'secondary-create', 'generic' ),
			),
			array(
				AuthManager::ACTION_LINK,
				array( 'primary-link-link', 'generic' ),
			),
			array(
				AuthManager::ACTION_ALL,
				array( 'pre-all', 'primary-none-all', 'primary-create-all',
					'primary-link-all', 'secondary-all', 'generic' ),
			),
			array(
				AuthManager::ACTION_CHANGE,
				array( 'primary-none-change', 'primary-create-change', 'primary-link-change' ),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array(),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array( 'primary-none-login', 'primary-create-login',
					'primary-link-login', 'secondary-login', 'generic' ),
				array(
					'primary' => null,
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array( 'primary-none-login-continue', 'generic' ),
				array(
					'primary' => 'primary-none',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array(),
				array(
					'primary' => 'pre',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array( 'secondary-login-continue', 'generic' ),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => false ),
				),
			),
			array(
				AuthManager::ACTION_LOGIN_CONTINUE,
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => true ),
				),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array(),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array(),
				array(
					'primary' => null,
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array( 'primary-create-create-continue', 'generic' ),
				array(
					'primary' => 'primary-create',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => null,
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array(),
				),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array( 'secondary-create-continue', 'generic' ),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => false ),
				),
			),
			array(
				AuthManager::ACTION_CREATE_CONTINUE,
				array(),
				array(
					'primary' => 'primary2',
					'primaryResponse' => AuthenticationResponse::newPass( '' ),
					'secondary' => array( 'secondary' => true ),
				),
			),
			array(
				AuthManager::ACTION_LINK_CONTINUE,
				array(),
			),
			array(
				AuthManager::ACTION_LINK_CONTINUE,
				array(),
				array(
					'primary' => null,
				),
			),
			array(
				AuthManager::ACTION_LINK_CONTINUE,
				array(),
				array(
					'primary' => 'primary-create',
				),
			),
			array(
				AuthManager::ACTION_LINK_CONTINUE,
				array( 'primary-link-link-continue', 'generic' ),
				array(
					'primary' => 'primary-link',
				),
			),
		);
	}

	public function testAllowsPropertyChange() {
		$mocks = array();
		foreach ( array( 'primary', 'secondary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key ) );
			$mocks[$key]->expects( $this->any() )->method( 'providerAllowsPropertyChange' )
				->will( $this->returnCallback( function ( $prop ) use ( $key ) {
					return $prop !== $key;
				} ) );
		}

		$this->primaryauthMocks = array( $mocks['primary'] );
		$this->secondaryauthMocks = array( $mocks['secondary'] );
		$this->initializeManager( true );

		$this->assertTrue( $this->manager->allowsPropertyChange( 'foo' ) );
		$this->assertFalse( $this->manager->allowsPropertyChange( 'primary' ) );
		$this->assertFalse( $this->manager->allowsPropertyChange( 'secondary' ) );
	}

	public function testAutoCreateOnLogin() {
		$username = self::usernameForCreation();

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'primary' ) );
		$mock->expects( $this->any() )->method( 'beginPrimaryAuthentication' )
			->will( $this->returnValue( AuthenticationResponse::newPass( $username ) ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )->method( 'testForAutoCreation' )
			->will( $this->returnValue( StatusValue::newGood() ) );

		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\SecondaryAuthenticationProvider' );
		$mock2->expects( $this->any() )->method( 'getUniqueId' )
			->will( $this->returnValue( 'secondary' ) );
		$mock2->expects( $this->any() )->method( 'beginSecondaryAuthentication' )->will(
			$this->returnValue( AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ) )
		);
		$mock2->expects( $this->any() )->method( 'continueSecondaryAuthentication' )
			->will( $this->returnValue( AuthenticationResponse::newAbstain() ) );
		$mock2->expects( $this->any() )->method( 'testForAutoCreation' )
			->will( $this->returnValue( StatusValue::newGood() ) );

		$this->primaryauthMocks = array( $mock );
		$this->secondaryauthMocks = array( $mock2 );
		$this->initializeManager( true );
		$this->manager->setLogger( new \Psr\Log\NullLogger() );
		$session = $this->request->getSession();
		$session->clear();

		$this->assertSame( 0, \User::newFromName( $username )->getId(),
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

		$id = (int)\User::newFromName( $username )->getId();
		$this->assertNotSame( 0, \User::newFromName( $username )->getId() );
		$this->assertSame( 0, $session->getUser()->getId() );

		$this->hook( 'UserLoggedIn', $this->once() )->with( $callback );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->continueAuthentication( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->unhook( 'UserLoggedIn' );
		$this->assertSame( AuthenticationResponse::PASS, $ret->status );
		$this->assertSame( $username, $ret->username );
		$this->assertSame( $id, $session->getUser()->getId() );
	}

	public function testAutoCreateFailOnLogin() {
		$username = self::usernameForCreation();

		$mock = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\PrimaryAuthenticationProvider', array(), "MockPrimaryAuthenticationProvider" );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'primary' ) );
		$mock->expects( $this->any() )->method( 'beginPrimaryAuthentication' )
			->will( $this->returnValue( AuthenticationResponse::newPass( $username ) ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mock->expects( $this->any() )->method( 'testUserExists' )->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )->method( 'testForAutoCreation' )
			->will( $this->returnValue( StatusValue::newFatal( 'fail-from-primary' ) ) );

		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );
		$this->manager->setLogger( new \Psr\Log\NullLogger() );
		$session = $this->request->getSession();
		$session->clear();

		$this->assertSame( 0, $session->getUser()->getId(),
			'sanity check' );
		$this->assertSame( 0, \User::newFromName( $username )->getId(),
			'sanity check' );

		$this->hook( 'UserLoggedIn', $this->never() );
		$this->hook( 'LocalUserCreated', $this->never() );
		$ret = $this->manager->beginAuthentication( array() );
		$this->unhook( 'LocalUserCreated' );
		$this->unhook( 'UserLoggedIn' );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-authn-autocreate-failed', $ret->message->getKey() );

		$this->assertSame( 0, \User::newFromName( $username )->getId() );
		$this->assertSame( 0, $session->getUser()->getId() );
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

		$this->manager->setAuthenticationData( 'foo', 'foo!' );
		$this->manager->setAuthenticationData( 'bar', 'bar!' );
		$this->manager->removeAuthenticationData( null );
		$this->assertNull( $this->manager->getAuthenticationData( 'foo' ) );
		$this->assertNull( $this->manager->getAuthenticationData( 'bar' ) );

	}

	public function testCanLinkAccounts() {
		$types = array(
			PrimaryAuthenticationProvider::TYPE_CREATE => true,
			PrimaryAuthenticationProvider::TYPE_LINK => true,
			PrimaryAuthenticationProvider::TYPE_NONE => false,
		);

		foreach ( $types as $type => $can ) {
			$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
			$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( $type ) );
			$mock->expects( $this->any() )->method( 'accountCreationType' )
				->will( $this->returnValue( $type ) );
			$this->primaryauthMocks = array( $mock );
			$this->initializeManager( true );
			$this->assertSame( $can, $this->manager->canCreateAccounts(), $type );
		}
	}

	public function testBeginAccountLink() {
		$user = \User::newFromName( 'UTSysop' );
		$this->initializeManager();

		$this->request->setSessionData( 'AuthManager::accountLinkState', 'test' );
		try {
			$this->manager->beginAccountLink( $user, array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \LogicException $ex ) {
			$this->assertEquals( 'Account linking is not possible', $ex->getMessage() );
		}
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountLinkState' ) );

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$ret = $this->manager->beginAccountLink( new \User, array() );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'noname', $ret->message->getKey() );

		$ret = $this->manager->beginAccountLink( \User::newFromName( 'UTDoesNotExist' ), array() );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-userdoesnotexist', $ret->message->getKey() );
	}

	public function testContinueAccountLink() {
		$user = \User::newFromName( 'UTSysop' );
		$this->initializeManager();

		$session = array(
			'userid' => $user->getId(),
			'username' => $user->getName(),
			'primary' => 'X',
		);

		try {
			$this->manager->continueAccountLink( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \LogicException $ex ) {
			$this->assertEquals( 'Account linking is not possible', $ex->getMessage() );
		}

		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\PrimaryAuthenticationProvider' );
		$mock->expects( $this->any() )->method( 'getUniqueId' )->will( $this->returnValue( 'X' ) );
		$mock->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
		$mock->expects( $this->any() )->method( 'beginPrimaryAccountLink' )->will(
			$this->returnValue( AuthenticationResponse::newFail( $this->messageSpecifier( 'fail' ) ) )
		);
		$this->primaryauthMocks = array( $mock );
		$this->initializeManager( true );

		$this->request->setSessionData( 'AuthManager::accountLinkState', null );
		$ret = $this->manager->continueAccountLink( array() );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-link-not-in-progress', $ret->message->getKey() );

		$this->request->setSessionData( 'AuthManager::accountLinkState',
			array( 'username' => $user->getName() . '<>' ) + $session );
		$ret = $this->manager->continueAccountLink( array() );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'noname', $ret->message->getKey() );
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountLinkState' ) );

		$id = $user->getId();
		$this->request->setSessionData( 'AuthManager::accountLinkState',
			array( 'userid' => $id + 1 ) + $session );
		try {
			$ret = $this->manager->continueAccountLink( array() );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertEquals(
				"User \"{$user->getName()}\" is valid, but ID $id != " . ( $id + 1 ) . '!',
				$ex->getMessage()
			);
		}
		$this->assertNull( $this->request->getSessionData( 'AuthManager::accountLinkState' ) );
	}

	/**
	 * @dataProvider provideAccountLink
	 * @param string $label
	 * @param StatusValue $preTest
	 * @param array $primaryResponses
	 * @param array $managerResponses
	 */
	public function testAccountLink(
		$label, StatusValue $preTest, array $primaryResponses, array $managerResponses
	) {
		$user = \User::newFromName( 'UTSysop' );

		$this->initializeManager();

		// Set up lots of mocks...
		$that = $this;
		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req->primary = $primaryResponses;
		$clazz = get_class( $req );
		$mocks = array();

		foreach ( array( 'pre', 'primary' ) as $key ) {
			$class = ucfirst( $key ) . 'AuthenticationProvider';
			$mocks[$key] = $this->getMockForAbstractClass(
				"MediaWiki\\Auth\\$class", array(), "Mock$class"
			);
			$mocks[$key]->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( $key ) );

			for ( $i = 2; $i <= 3; $i++ ) {
				$mocks[$key . $i] = $this->getMockForAbstractClass(
					"MediaWiki\\Auth\\$class", array(), "Mock$class"
				);
				$mocks[$key . $i]->expects( $this->any() )->method( 'getUniqueId' )
					->will( $this->returnValue( $key . $i ) );
			}
		}

		$mocks['pre']->expects( $this->any() )->method( 'testForAccountLink' )
			->will( $this->returnCallback(
				function ( $u )
					use ( $that, $user, $preTest )
				{
					$that->assertSame( $user->getId(), $u->getId() );
					$that->assertSame( $user->getName(), $u->getName() );
					return $preTest;
				}
			) );

		$mocks['pre2']->expects( new \InvokedAtMost( 1 ) )->method( 'testForAccountLink' )
			->will( $this->returnValue( StatusValue::newGood() ) );

		$mocks['primary']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
		$ct = count( $req->primary );
		$callback = $this->returnCallback( function ( $u, $reqs ) use ( $that, $user, $clazz ) {
			$that->assertSame( $user->getId(), $u->getId() );
			$that->assertSame( $user->getName(), $u->getName() );
			foreach ( $reqs as $req ) {
				$that->assertSame( $user->getName(), $req->username );
			}
			$that->assertArrayHasKey( $clazz, $reqs );
			$req = $reqs[$clazz];
			return array_shift( $req->primary );
		} );
		$mocks['primary']->expects( $this->exactly( min( 1, $ct ) ) )
			->method( 'beginPrimaryAccountLink' )
			->will( $callback );
		$mocks['primary']->expects( $this->exactly( max( 0, $ct - 1 ) ) )
			->method( 'continuePrimaryAccountLink' )
			->will( $callback );

		$abstain = AuthenticationResponse::newAbstain();
		$mocks['primary2']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_LINK ) );
		$mocks['primary2']->expects( new \InvokedAtMost( 1 ) )->method( 'beginPrimaryAccountLink' )
			->will( $this->returnValue( $abstain ) );
		$mocks['primary2']->expects( $this->never() )->method( 'continuePrimaryAccountLink' );
		$mocks['primary3']->expects( $this->any() )->method( 'accountCreationType' )
			->will( $this->returnValue( PrimaryAuthenticationProvider::TYPE_CREATE ) );
		$mocks['primary3']->expects( $this->never() )->method( 'beginPrimaryAccountLink' );
		$mocks['primary3']->expects( $this->never() )->method( 'continuePrimaryAccountLink' );

		$this->preauthMocks = array( $mocks['pre'], $mocks['pre2'] );
		$this->primaryauthMocks = array( $mocks['primary3'], $mocks['primary2'], $mocks['primary'] );
		$this->logger = new \TestLogger( true, function ( $message, $level ) {
			return $level === LogLevel::DEBUG ? null : $message;
		} );
		$this->initializeManager( true );

		$first = true;
		$created = false;
		$expectLog = array();
		foreach ( $managerResponses as $i => $response ) {
			if ( $response instanceof AuthenticationResponse &&
				$response->status === AuthenticationResponse::PASS
			) {
				$expectLog[] = array( LogLevel::INFO, "Account linked to $user by primary" );
			}

			$ex = null;
			try {
				if ( $first ) {
					$ret = $this->manager->beginAccountLink( $user, array( $req ) );
				} else {
					$ret = $this->manager->continueAccountLink( array( $req ) );
				}
				if ( $response instanceof \Exception ) {
					$this->fail( 'Expected exception not thrown', "Response $i" );
				}
			} catch ( \Exception $ex ) {
				if ( !$response instanceof \Exception ) {
					throw $ex;
				}
				$this->assertEquals( $response->getMessage(), $ex->getMessage(), "Response $i, exception" );
				$this->assertNull( $this->request->getSessionData( 'AuthManager::accountLinkState' ),
				   "Response $i, exception, session state" );
				return;
			}

			$ret->message = $this->messageSpecifier( $ret->message );
			$this->assertEquals( $response, $ret, "Response $i, response" );
			if ( $response->status === AuthenticationResponse::PASS ||
				$response->status === AuthenticationResponse::FAIL
			) {
				$this->assertNull( $this->request->getSessionData( 'AuthManager::accountLinkState' ),
				   "Response $i, session state" );
			} else {
				$this->assertNotNull( $this->request->getSessionData( 'AuthManager::accountLinkState' ),
				   "Response $i, session state" );
			}

			$first = false;
		}

		$this->assertSame( $expectLog, $this->logger->getBuffer() );
	}

	public function provideAccountLink() {
		$good = StatusValue::newGood();

		return array(
			array(
				'Pre-link test fail in pre',
				StatusValue::newFatal( 'fail-from-pre' ),
				array(),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-pre' ) ),
				)
			),
			array(
				'Failure in primary',
				$good,
				$tmp = array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-from-primary' ) ),
				),
				$tmp
			),
			array(
				'All primary abstain',
				$good,
				array(
					AuthenticationResponse::newAbstain(),
				),
				array(
					AuthenticationResponse::newFail( $this->messageSpecifier( 'authmanager-link-no-primary' ) )
				)
			),
			array(
				'Primary UI, then redirect, then fail',
				$good,
				$tmp = array(
					AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newRedirect( '/foo.html', array( 'foo' => 'bar' ) ),
					AuthenticationResponse::newFail( $this->messageSpecifier( 'fail-in-primary-continue' ) ),
				),
				$tmp
			),
			array(
				'Primary redirect, then abstain',
				$good,
				array(
					$tmp = AuthenticationResponse::newRedirect( '/foo.html', array( 'foo' => 'bar' ) ),
					AuthenticationResponse::newAbstain(),
				),
				array(
					$tmp,
					new \DomainException(
						'MockPrimaryAuthenticationProvider::continuePrimaryAccountLink() returned ABSTAIN'
					)
				)
			),
			array(
				'Primary UI, then pass',
				$good,
				array(
					$tmp1 = AuthenticationResponse::newUI( array(), $this->messageSpecifier( '...' ) ),
					AuthenticationResponse::newPass(),
				),
				array(
					$tmp1,
					AuthenticationResponse::newPass( '' ),
				)
			),
			array(
				'Primary pass',
				$good,
				array(
					AuthenticationResponse::newPass( '' ),
				),
				array(
					AuthenticationResponse::newPass( '' ),
				)
			),
		);
	}
}
