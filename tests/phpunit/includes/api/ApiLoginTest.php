<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiLogin
 */
class ApiLoginTest extends ApiTestCase {

	/**
	 * Test result of attempted login with an empty username
	 */
	public function testApiLoginNoName() {
		$session = [
			'wsTokenSecrets' => [ 'login' => 'foobar' ],
		];
		$data = $this->doApiRequest( [ 'action' => 'login',
			'lgname' => '', 'lgpassword' => self::$users['sysop']->getPassword(),
			'lgtoken' => (string)( new MediaWiki\Session\Token( 'foobar', '' ) )
		], $session );
		$this->assertEquals( 'Failed', $data[0]['login']['result'] );
	}

	public function testApiLoginBadPass() {
		global $wgServer;

		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$user->getUser()->logout();

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$ret = $this->doApiRequest( [
			"action" => "login",
			"lgname" => $userName,
			"lgpassword" => "bad",
		] );

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];
		$this->assertEquals( "NeedToken", $a );

		$token = $result["login"]["token"];

		$ret = $this->doApiRequest(
			[
				"action" => "login",
				"lgtoken" => $token,
				"lgname" => $userName,
				"lgpassword" => "badnowayinhell",
			],
			$ret[2]
		);

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];

		$this->assertEquals( 'Failed', $a );
	}

	public function testApiLoginGoodPass() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}

		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$password = $user->getPassword();
		$user->getUser()->logout();

		$ret = $this->doApiRequest( [
				"action" => "login",
				"lgname" => $userName,
				"lgpassword" => $password,
			]
		);

		$result = $ret[0];
		$this->assertNotInternalType( "bool", $result );
		$this->assertNotInternalType( "null", $result["login"] );

		$a = $result["login"]["result"];
		$this->assertEquals( "NeedToken", $a );
		$token = $result["login"]["token"];

		$ret = $this->doApiRequest(
			[
				"action" => "login",
				"lgtoken" => $token,
				"lgname" => $userName,
				"lgpassword" => $password,
			],
			$ret[2]
		);

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];

		$this->assertEquals( "Success", $a );
	}

	/**
	 * @group Broken
	 */
	public function testApiLoginGotCookie() {
		$this->markTestIncomplete( "The server can't do external HTTP requests, "
			. "and the internal one won't give cookies" );

		global $wgServer, $wgScriptPath;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$password = $user->getPassword();

		$req = MWHttpRequest::factory( self::$apiUrl . "?action=login&format=xml",
			[ "method" => "POST",
				"postData" => [
					"lgname" => $userName,
					"lgpassword" => $password
				]
			],
			__METHOD__
		);
		$req->execute();

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $req->getContent() );
		$this->assertNotInternalType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$this->assertNotInternalType( "null", $sxe->login[0] );

		$a = $sxe->login[0]->attributes()->result[0];
		$this->assertEquals( ' result="NeedToken"', $a->asXML() );
		$token = (string)$sxe->login[0]->attributes()->token;

		$req->setData( [
			"lgtoken" => $token,
			"lgname" => $userName,
			"lgpassword" => $password ] );
		$req->execute();

		$cj = $req->getCookieJar();
		$serverName = parse_url( $wgServer, PHP_URL_HOST );
		$this->assertNotEquals( false, $serverName );
		$serializedCookie = $cj->serializeToHttpRequest( $wgScriptPath, $serverName );
		$this->assertNotEquals( '', $serializedCookie );
		$this->assertRegExp(
			'/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . $user->userName . '; .*Token=/',
			$serializedCookie
		);
	}

	public function testRunLogin() {
		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$password = $user->getPassword();

		$data = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => $userName,
			'lgpassword' => $password ] );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "NeedToken", $data[0]['login']['result'] );
		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( [
			'action' => 'login',
			"lgtoken" => $token,
			"lgname" => $userName,
			"lgpassword" => $password ], $data[2] );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
	}

	public function testBotPassword() {
		global $wgServer, $wgSessionProviders;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}

		$this->setMwGlobals( [
			'wgSessionProviders' => array_merge( $wgSessionProviders, [
				[
					'class' => MediaWiki\Session\BotPasswordSessionProvider::class,
					'args' => [ [ 'priority' => 40 ] ],
				]
			] ),
			'wgEnableBotPasswords' => true,
			'wgBotPasswordsDatabase' => false,
			'wgCentralIdLookupProvider' => 'local',
			'wgGrantPermissions' => [
				'test' => [ 'read' => true ],
			],
		] );

		// Make sure our session provider is present
		$manager = TestingAccessWrapper::newFromObject( MediaWiki\Session\SessionManager::singleton() );
		if ( !isset( $manager->sessionProviders[MediaWiki\Session\BotPasswordSessionProvider::class] ) ) {
			$tmp = $manager->sessionProviders;
			$manager->sessionProviders = null;
			$manager->sessionProviders = $tmp + $manager->getProviders();
		}
		$this->assertNotNull(
			MediaWiki\Session\SessionManager::singleton()->getProvider(
				MediaWiki\Session\BotPasswordSessionProvider::class
			),
			'sanity check'
		);

		$user = self::$users['sysop'];
		$centralId = CentralIdLookup::factory()->centralIdFromLocalUser( $user->getUser() );
		$this->assertNotEquals( 0, $centralId, 'sanity check' );

		$password = 'ngfhmjm64hv0854493hsj5nncjud2clk';
		$passwordFactory = new PasswordFactory();
		$passwordFactory->init( RequestContext::getMain()->getConfig() );
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordHash = $passwordFactory->newFromPlaintext( $password );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert(
			'bot_passwords',
			[
				'bp_user' => $centralId,
				'bp_app_id' => 'foo',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => '',
				'bp_restrictions' => MWRestrictions::newDefault()->toJson(),
				'bp_grants' => '["test"]',
			],
			__METHOD__
		);

		$lgName = $user->getUser()->getName() . BotPassword::getSeparator() . 'foo';

		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => $lgName,
			'lgpassword' => $password,
		] );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$this->assertNotInternalType( 'null', $result['login'] );

		$a = $result['login']['result'];
		$this->assertEquals( 'NeedToken', $a );
		$token = $result['login']['token'];

		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgtoken' => $token,
			'lgname' => $lgName,
			'lgpassword' => $password,
		], $ret[2] );

		$result = $ret[0];
		$this->assertNotInternalType( 'bool', $result );
		$a = $result['login']['result'];

		$this->assertEquals( 'Success', $a );
	}

}
