<?php
/**
 * Selenium connector
 * This is implemented as a singleton.
 */

if ( !defined( 'MEDIAWIKI' ) || !defined( 'SELENIUMTEST' ) ) {
	echo "This script cannot be run standalone";
	exit( 1 );
}

class Selenium extends Testing_Selenium {
	protected static $_instance = null;
	public $isStarted = false;

	public static function getInstance() {
		global $wgSeleniumTestsBrowsers, $wgSeleniumTestsSeleniumHost, $wgSeleniumTestsUseBrowser;
		global $wgSeleniumTestsWikiUrl, $wgSeleniumServerPort;
		if ( null === self::$_instance ) {
			self::$_instance = new self( $wgSeleniumTestsBrowsers[$wgSeleniumTestsUseBrowser],
							$wgSeleniumTestsWikiUrl,
							$wgSeleniumTestsSeleniumHost,
							$wgSeleniumServerPort );
 		}
		return self::$_instance;
	}

	public function start() {
		global $wgSeleniumTestsBrowsers, $wgSeleniumTestsSeleniumHost;
		parent::start();
		$this->isStarted = true;
	}

	public function stop() {
		parent::stop();
		$this->isStarted = false;
	}

	public function login() {
		global $wgSeleniumTestsWikiUser, $wgSeleniumTestsWikiPassword, $wgSeleniumTestsWikiUrl;

		$this->open( $wgSeleniumTestsWikiUrl . '/index.php?title=Special:Userlogin' );
		$this->type( 'wpName1', $wgSeleniumTestsWikiUser );
		$this->type( 'wpPassword1', $wgSeleniumTestsWikiPassword );
		$this->click( "//input[@id='wpLoginAttempt']" );
		$value = $this->doCommand( 'assertTitle', array( 'Login successful*' ) );
	}

	public function loadPage( $title, $action ) {
		global $wgSeleniumTestsWikiUrl;
		$this->open( $wgSeleniumTestsWikiUrl . '/index.php?title=' . $title . '&action=' . $action );
	}

	// Prevent external cloning
	protected function __clone() { }
	// Prevent external construction
	// protected function __construct() {}
}
