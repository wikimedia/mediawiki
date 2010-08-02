<?php
/**
 * Selenium connector
 * This is implemented as a singleton.
 */

class Selenium extends Testing_Selenium {
	protected static $_instance = null;
	public $isStarted = false;

	public static function getInstance() {
		global $wgSeleniumTestsBrowsers, $wgSeleniumTestsSeleniumHost, $wgSeleniumTestsUseBrowser;
		global $wgSeleniumServerPort, $wgSeleniumLogger;

		if ( null === self::$_instance ) {
			$wgSeleniumLogger->write( "Browser: " . $wgSeleniumTestsBrowsers[$wgSeleniumTestsUseBrowser] );
			self::$_instance = new self( $wgSeleniumTestsBrowsers[$wgSeleniumTestsUseBrowser],
							self::getBaseUrl(),
							$wgSeleniumTestsSeleniumHost,
							$wgSeleniumServerPort );
 		}
		return self::$_instance;
	}

	public function start() {
		parent::start();
		$this->isStarted = true;
	}

	public function stop() {
		parent::stop();
		$this->isStarted = false;
	}

	static function getBaseUrl() {
		global $wgSeleniumTestsWikiUrl, $wgServer, $wgScriptPath;
		if ( $wgSeleniumTestsWikiUrl ) {
			return $wgSeleniumTestsWikiUrl;
		} else {
			return $wgServer . $wgScriptPath;
		}
	}

	public function login() {
		global $wgSeleniumTestsWikiUser, $wgSeleniumTestsWikiPassword;

		$this->open( self::getBaseUrl() . '/index.php?title=Special:Userlogin' );
		$this->type( 'wpName1', $wgSeleniumTestsWikiUser );
		$this->type( 'wpPassword1', $wgSeleniumTestsWikiPassword );
		$this->click( "//input[@id='wpLoginAttempt']" );
		$this->waitForPageToLoad(5000);
		//after login we redirect to the main page. So check whether the "Prefernces" top menu item exists
		$value = $this->isElementPresent( "//li[@id='pt-preferences']" );
		if ( $value != true ) {
			throw new Testing_Selenium_Exception( "Login Failed" );
		}
		
	}

	public function loadPage( $title, $action ) {
		$this->open( self::getBaseUrl() . '/index.php?title=' . $title . '&action=' . $action );
	}
	
	/*
	 * Log to console or html depending on the value of $wgSeleniumTestsRunMode
	 */
	public function log( $message ) {
		global $wgSeleniumLogger;
		$wgSeleniumLogger->write( $message );
	}
	
	// Prevent external cloning
	protected function __clone() { }
	// Prevent external construction
	// protected function __construct() {}
}
