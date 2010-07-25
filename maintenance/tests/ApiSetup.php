<?php

abstract class ApiTestSetup extends PHPUnit_Framework_TestCase {
	protected static $userName;
	protected static $passWord;
	protected static $user;
	protected static $apiUrl;

	function setup() {
		global $wgServer, $wgContLang, $wgAuth, $wgScriptPath,
			$wgScriptExtension, $wgMemc, $wgRequest;

		self::$apiUrl = $wgServer . $wgScriptPath . "/api" . $wgScriptExtension;

		$wgMemc = new FakeMemCachedClient;
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		$wgRequest = new FauxRequest( array() );
		self::setupUser();
	}

	static function setupUser() {
		if ( self::$user == NULL ) {
			self::$userName = "Useruser";
			self::$passWord = User::randomPassword();

			self::$user = User::newFromName( self::$userName );
			if ( !self::$user->getID() ) {
				self::$user = User::createNew( self::$userName, array(
					"email" => "test@example.com",
					"real_name" => "Test User" ) );
			}
			self::$user->setPassword( self::$passWord );
			self::$user->saveSettings();
		}
	}
}
