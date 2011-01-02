<?php

class MockApi extends ApiBase {
	public function execute() { }
	public function getVersion() { }

	public function __construct() { }

	public function getAllowedParams() {
		return array(
			'filename' => null,
			'enablechunks' => false,
			'sessionkey' => null,
		);
	}
}

/**
 * @group Database
 * @group Destructive
 */
class ApiTest extends ApiTestSetup {

	function testRequireOnlyOneParameterDefault() {
		$mock = new MockApi();

		$this->assertEquals(
			null, $mock->requireOnlyOneParameter( array( "filename" => "foo.txt",
													   "enablechunks" => false ), "filename", "enablechunks" ) );
	}

	/**
	 * @expectedException UsageException
	 */
	function testRequireOnlyOneParameterZero() {
		$mock = new MockApi();

		$this->assertEquals(
			null, $mock->requireOnlyOneParameter( array( "filename" => "foo.txt",
													   "enablechunks" => 0 ), "filename", "enablechunks" ) );
	}

	/**
	 * @expectedException UsageException
	 */
	function testRequireOnlyOneParameterTrue() {
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
	function testApi() {
	
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
	function testApiLoginNoName() {
		$data = $this->doApiRequest( array( 'action' => 'login',
			'lgname' => '', 'lgpassword' => $this->user->password,
		) );
		$this->assertEquals( 'NoName', $data[0]['login']['result'] );
	}

	function testApiLoginBadPass() {
		global $wgServer;

		$user = $this->user;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$ret = $this->doApiRequest( array(
			"action" => "login",
			"lgname" => $user->userName,
			"lgpassword" => "bad",
			)
		);

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];
		$this->assertEquals( "NeedToken", $a );

		$token = $result["login"]["token"];

		$ret = $this->doApiRequest( array(
			"action" => "login",
			"lgtoken" => $token,
			"lgname" => $user->userName,
			"lgpassword" => "bad",
			)
		);

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];

		$this->assertEquals( "WrongPass", $a );
	}

	function testApiLoginGoodPass() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}

		$user = $this->user;

		$ret = $this->doApiRequest( array(
			"action" => "login",
			"lgname" => $user->userName,
			"lgpassword" => $user->password,
			)
		);

		$result = $ret[0];
		$this->assertNotInternalType( "bool", $result );
		$this->assertNotInternalType( "null", $result["login"] );

		$a = $result["login"]["result"];
		$this->assertEquals( "NeedToken", $a );
		$token = $result["login"]["token"];

		$ret = $this->doApiRequest( array(
			"action" => "login",
			"lgtoken" => $token,
			"lgname" => $user->userName,
			"lgpassword" => $user->password,
			)
		);

		$result = $ret[0];

		$this->assertNotInternalType( "bool", $result );
		$a = $result["login"]["result"];

		$this->assertEquals( "Success", $a );
	}

	function testApiGotCookie() {
		$this->markTestIncomplete( "The server can't do external HTTP requests, and the internal one won't give cookies"  );

		global $wgServer, $wgScriptPath;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$req = MWHttpRequest::factory( self::$apiUrl . "?action=login&format=xml",
			array( "method" => "POST",
				"postData" => array(
				"lgname" => $this->user->userName,
				"lgpassword" => $this->user->password ) ) );
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
			"lgname" => $this->user->userName,
			"lgpassword" => $this->user->password ) );
		$req->execute();

		$cj = $req->getCookieJar();
		$serverName = parse_url( $wgServer, PHP_URL_HOST );
		$this->assertNotEquals( false, $serverName );
		$serializedCookie = $cj->serializeToHttpRequest( $wgScriptPath, $serverName );
		$this->assertNotEquals( '', $serializedCookie );
		$this->assertRegexp( '/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . $this->user->userName . '; .*Token=/', $serializedCookie );

		return $cj;
	}

	/**
	 * @depends testApiGotCookie
	 */
	function testApiListPages( CookieJar $cj ) {
		$this->markTestIncomplete( "Not done with this yet" );
		global $wgServer;

		if ( $wgServer == "http://localhost" ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$req = MWHttpRequest::factory( self::$apiUrl . "?action=query&format=xml&prop=revisions&" .
									 "titles=Main%20Page&rvprop=timestamp|user|comment|content" );
		$req->setCookieJar( $cj );
		$req->execute();
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $req->getContent() );
		$this->assertNotInternalType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->query[0]->pages[0]->page[0]->attributes();
	}
	
	function testRunLogin() {
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => $this->sysopUser->userName,
			'lgpassword' => $this->sysopUser->password ) );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "NeedToken", $data[0]['login']['result'] );
		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( array(
			'action' => 'login',
			"lgtoken" => $token,
			"lgname" => $this->sysopUser->userName,
			"lgpassword" => $this->sysopUser->password ), $data );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $data[0]['login'] );
		
		return $data;
	}
	
	function testGettingToken() {
		foreach ( array( $this->user, $this->sysopUser ) as $user ) {
			$this->runTokenTest( $user );
		}
	}

	function runTokenTest( $user ) {
		
		$data = $this->getTokenList( $user );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'pages', $data[0]['query'] );
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );

		$rights = $user->user->getRights();

		$this->assertArrayHasKey( $key, $data[0]['query']['pages'] );
		$this->assertArrayHasKey( 'edittoken', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'movetoken', $data[0]['query']['pages'][$key] );

		if ( isset( $rights['delete'] ) ) {
			$this->assertArrayHasKey( 'deletetoken', $data[0]['query']['pages'][$key] );
		}

		if ( isset( $rights['block'] ) ) {
			$this->assertArrayHasKey( 'blocktoken', $data[0]['query']['pages'][$key] );
			$this->assertArrayHasKey( 'unblocktoken', $data[0]['query']['pages'][$key] );
		}

		if ( isset( $rights['protect'] ) ) {
			$this->assertArrayHasKey( 'protecttoken', $data[0]['query']['pages'][$key] );
		}

		return $data;
	}
}
