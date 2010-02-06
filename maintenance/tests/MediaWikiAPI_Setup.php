<?php

abstract class MediaWikiAPI_Setup extends PHPUnit_Framework_TestCase {
	protected static $userName;
	protected static $passWord;
	protected static $user;
	protected static $apiUrl;

	function setup() {
		global $wgServerName, $wgServer, $wgContLang, $wgAuth, $wgScriptPath,
			$wgScriptExtension, $wgMemc;

		if($wgServerName == "localhost" || $wgServer == "http://localhost") {
			$this->markTestIncomplete('This test needs $wgServerName and $wgServer to '.
									  'be set in LocalSettings.php');
		}
		self::$apiUrl = $wgServer.$wgScriptPath."/api".$wgScriptExtension;

		$wgMemc = new FakeMemCachedClient;
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		self::setupUser();
	}

	static function setupUser() {
		if ( self::$user == NULL ) {
			self::$userName = "Useruser";
			self::$passWord = User::randomPassword();

			self::$user = User::newFromName(self::$userName);
			if ( !self::$user->getID() ) {
				self::$user = User::createNew(self::$userName, array(
					"password" => self::$passWord,
					"email" => "test@example.com",
					"real_name" => "Test User"));
			} else {
				self::$user->setPassword(self::$passWord);
			}
			self::$user->saveSettings();
		}
	}
}
