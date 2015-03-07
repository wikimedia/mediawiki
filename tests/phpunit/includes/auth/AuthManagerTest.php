<?php

/**
 * @group AuthManager
 * @covers AuthManager
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

		$this->sessionStore = new HashBagOStuff();
		ObjectCache::$instances['testSessionStore'] = $this->sessionStore;
		$this->request = new FauxRequest();
		$this->config = new HashConfig( array() );

		/// @todo: Put together some sort of mock AuthnSessionProvider that
		// returns a mock AuthnSession that supports basic getting/setting of
		// user info, since lots depends on that.
		// https://phpunit.de/manual/current/en/test-doubles.html might help there.
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
					'factory' => function () { return $mock; },
				);
			}
		}

		$this->config->set( 'AuthManagerConfig', $config );
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

	public function testGetSession() {
		$this->initializeManager();

		/// @todo: Finish this. Then test the rest of AuthManager.
	}

}

