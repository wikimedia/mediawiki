<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiTest extends ApiTestCase {

	public function testRequireOnlyOneParameterDefault() {
		$mock = new MockApi();

		$this->assertEquals(
			null, $mock->requireOnlyOneParameter( array( "filename" => "foo.txt",
			"enablechunks" => false ), "filename", "enablechunks" ) );
	}

	/**
	 * @expectedException UsageException
	 */
	public function testRequireOnlyOneParameterZero() {
		$mock = new MockApi();

		$this->assertEquals(
			null, $mock->requireOnlyOneParameter( array( "filename" => "foo.txt",
			"enablechunks" => 0 ), "filename", "enablechunks" ) );
	}

	/**
	 * @expectedException UsageException
	 */
	public function testRequireOnlyOneParameterTrue() {
		$mock = new MockApi();

		$this->assertEquals(
			null, $mock->requireOnlyOneParameter( array( "filename" => "foo.txt",
			"enablechunks" => true ), "filename", "enablechunks" ) );
	}

	/**
	 * Test that the API will accept a FauxRequest and execute. The help action
	 * (default) throws a UsageException. Just validate we're getting proper XML
	 *
	 * @expectedException UsageException
	 */
	public function testApi() {
		$api = new ApiMain(
			new FauxRequest( array( 'action' => 'help', 'format' => 'xml' ) )
		);
		$api->execute();
		$api->getPrinter()->setBufferResult( true );
		$api->printResult( false );
		$resp = $api->getPrinter()->getBuffer();

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotInternalType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
	}

	/**
	 * Test result of attempted login with an empty username
	 */
	public function testApiLoginNoName() {
		$data = $this->doApiRequest( array( 'action' => 'login',
			'lgname' => '', 'lgpassword' => self::$users['sysop']->password,
		) );
		$this->assertEquals( 'NoName', $data[0]['login']['result'] );
	}

	public function testApiLoginBadPass() {
		global $wgServer;

		$user = self::$users['sysop'];
		$user->user->logOut();

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$ret = $this->doApiRequest( array(
			"action" => "login",
			"lgname" => $user->username,
			"lgpassword" => "bad",
		) );

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];
		$this->assertEquals( "NeedToken", $a );

		$token = $result["login"]["token"];

		$ret = $this->doApiRequest(
			array(
				"action" => "login",
				"lgtoken" => $token,
				"lgname" => $user->username,
				"lgpassword" => "badnowayinhell",
			),
			$ret[2]
		);

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];

		$this->assertEquals( "WrongPass", $a );
	}

	public function testApiLoginGoodPass() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}

		$user = self::$users['sysop'];
		$user->user->logOut();

		$ret = $this->doApiRequest( array(
				"action" => "login",
				"lgname" => $user->username,
				"lgpassword" => $user->password,
			)
		);

		$result = $ret[0];
		$this->assertNotInternalType( "bool", $result );
		$this->assertNotInternalType( "null", $result["login"] );

		$a = $result["login"]["result"];
		$this->assertEquals( "NeedToken", $a );
		$token = $result["login"]["token"];

		$ret = $this->doApiRequest(
			array(
				"action" => "login",
				"lgtoken" => $token,
				"lgname" => $user->username,
				"lgpassword" => $user->password,
			),
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
	public function testApiGotCookie() {
		$this->markTestIncomplete( "The server can't do external HTTP requests, and the internal one won't give cookies" );

		global $wgServer, $wgScriptPath;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$user = self::$users['sysop'];

		$req = MWHttpRequest::factory( self::$apiUrl . "?action=login&format=xml",
			array( "method" => "POST",
				"postData" => array(
					"lgname" => $user->username,
					"lgpassword" => $user->password
				)
			)
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

		$req->setData( array(
			"lgtoken" => $token,
			"lgname" => $user->username,
			"lgpassword" => $user->password ) );
		$req->execute();

		$cj = $req->getCookieJar();
		$serverName = parse_url( $wgServer, PHP_URL_HOST );
		$this->assertNotEquals( false, $serverName );
		$serializedCookie = $cj->serializeToHttpRequest( $wgScriptPath, $serverName );
		$this->assertNotEquals( '', $serializedCookie );
		$this->assertRegexp( '/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . $user->userName . '; .*Token=/', $serializedCookie );

		return $cj;
	}

	public function testRunLogin() {
		$sysopUser = self::$users['sysop'];
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => $sysopUser->username,
			'lgpassword' => $sysopUser->password ) );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "NeedToken", $data[0]['login']['result'] );
		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( array(
			'action' => 'login',
			"lgtoken" => $token,
			"lgname" => $sysopUser->username,
			"lgpassword" => $sysopUser->password ), $data[2] );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $data[0]['login'] );

		return $data;
	}

	public function testGettingToken() {
		foreach ( self::$users as $user ) {
			$this->runTokenTest( $user );
		}
	}

	function runTokenTest( $user ) {
		$tokens = $this->getTokenList( $user );

		$rights = $user->user->getRights();

		$this->assertArrayHasKey( 'edittoken', $tokens );
		$this->assertArrayHasKey( 'movetoken', $tokens );

		if ( isset( $rights['delete'] ) ) {
			$this->assertArrayHasKey( 'deletetoken', $tokens );
		}

		if ( isset( $rights['block'] ) ) {
			$this->assertArrayHasKey( 'blocktoken', $tokens );
			$this->assertArrayHasKey( 'unblocktoken', $tokens );
		}

		if ( isset( $rights['protect'] ) ) {
			$this->assertArrayHasKey( 'protecttoken', $tokens );
		}

		return $tokens;
	}
}
