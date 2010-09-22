<?php
/**
 * Selenium connector
 * This is implemented as a singleton.
 */

require( 'Testing/Selenium.php' );

class Selenium {
	protected static $_instance = null;

	public $isStarted = false;
	public $tester;

	protected $port;
	protected $host;
	protected $browser;
	protected $browsers;
	protected $logger;
	protected $user;
	protected $pass;
	protected $timeout = 30000;
	protected $verbose;

	/**
	 * @todo this shouldn't have to be static
	 */
	static protected $url;

	/**
	 * Override parent
	 */
	public function __construct() {
		/**
		 * @todo this is an ugly hack to make information available to
		 * other tests.  It should be fixed.
		 */
		if ( null === self::$_instance ) {
			self::$_instance = $this;
		} else {
			throw new MWException( "Already have one Selenium instance." );
 		}
	}

	public function start() {
		$this->tester = new Testing_Selenium( $this->browser, self::$url, $this->host,
			$this->port, $this->timeout );
		if ( method_exists( $this->tester, "setVerbose" ) ) $this->tester->setVerbose( $this->verbose );

		$this->tester->start();
		$this->isStarted = true;
	}

	public function stop() {
		$this->tester->stop();
		$this->tester = null;
		$this->isStarted = false;
	}

	public function login() {
		if ( strlen( $this->user ) == 0 ) {
			return;
		} 
		$this->open( self::$url . '/index.php?title=Special:Userlogin' );
		$this->type( 'wpName1', $this->user );
		$this->type( 'wpPassword1', $this->pass );
		$this->click( "//input[@id='wpLoginAttempt']" );
		$this->waitForPageToLoad( 10000 );

		// after login we redirect to the main page. So check whether the "Prefernces" top menu item exists
		$value = $this->isElementPresent( "//li[@id='pt-preferences']" );

		if ( $value != true ) {
			throw new Testing_Selenium_Exception( "Login Failed" );
		}

	}

	public static function getInstance() {
		if ( null === self::$_instance ) {
			throw new MWException( "No instance set yet" );
		}

		return self::$_instance;
	}

	public function loadPage( $title, $action ) {
		$this->open( self::$url . '/index.php?title=' . $title . '&action=' . $action );
	}

	public function setLogger( $logger ) {
		$this->logger = $logger;
	}

	public function getLogger( ) {
		return $this->logger;
	}

	public function log( $message ) {
		$this->logger->write( $message );
	}

	public function setUrl( $url ) {
		self::$url = $url;
	}

	static public function getUrl() {
		return self::$url;
	}

	public function setPort( $port ) {
		$this->port = $port;
	}

	public function setUser( $user ) {
		$this->user = $user;
	}

	public function setPass( $pass ) {
		$this->pass = $pass;
	}

	public function setHost( $host ) {
		$this->host = $host;
	}

	public function setVerbose( $verbose ) {
		$this->verbose = $verbose;
	}
	
	public function setAvailableBrowsers( $availableBrowsers ) {
		$this->browsers = $availableBrowsers;
	}

	public function setBrowser( $b ) {
		if ( !isset( $this->browsers[$b] ) ) {
			throw new MWException( "Invalid Browser: $b.\n" );
		}

		$this->browser = $this->browsers[$b];
	}
	
	public function getAvailableBrowsers() {
		return $this->browsers;
	}

	public function __call( $name, $args ) {
		$t = call_user_func_array( array( $this->tester, $name ), $args );
		return $t;
	}

	// Prevent external cloning
	protected function __clone() { }
	// Prevent external construction
	// protected function __construct() {}
}
