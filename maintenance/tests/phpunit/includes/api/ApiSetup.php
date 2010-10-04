<?php

abstract class ApiTestSetup extends PHPUnit_Framework_TestCase {
	protected static $user;
	protected static $sysopUser;
	protected static $apiUrl;

	function setUp() {
		global $wgServer, $wgContLang, $wgAuth, $wgMemc, $wgRequest;

		self::$apiUrl = $wgServer . wfScript( 'api' );

		$wgMemc = new FakeMemCachedClient;
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		$wgRequest = new FauxRequest( array() );
		self::setupUser();
	}

	protected function doApiRequest( $params, $data = null ) {
		$_SESSION = isset( $data[2] ) ? $data[2] : array();

		$req = new FauxRequest( $params, true, $_SESSION );
		$module = new ApiMain( $req, true );
		$module->execute();

		$data[0] = $module->getResultData();
		$data[1] = $req;
		$data[2] = $_SESSION;

		return $data;
	}

	static function setupUser() {
		if ( self::$user == null || self::$sysopUser == null ) {
			self::$user = new UserWrapper( 'Useruser', 'Passpass' );
			self::$sysopUser = new UserWrapper( 'Useruser1', 'Passpass1', 'sysop' );
		}
		
		$GLOBALS['wgUser'] = self::$sysopUser->user;
	}

	function tearDown() {
		global $wgMemc;
		$wgMemc = null;
	}
}

class UserWrapper {
	public $userName, $password, $user;

	public function __construct( $userName, $password, $group = '' ) {
		$this->userName = $userName;
	    $this->password = $password;

	    $this->user = User::newFromName( $this->userName );
		if ( !$this->user->getID() ) {
			$this->user = User::createNew( $this->userName, array(
				"email" => "test@example.com",
				"real_name" => "Test User" ) );
		}
		$this->user->setPassword( $this->password );

		if ( $group !== '' ) {
			$this->user->addGroup( $group );
		}
		$this->user->saveSettings();
	}
}
