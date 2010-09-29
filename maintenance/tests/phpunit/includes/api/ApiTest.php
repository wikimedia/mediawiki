<?php

require_once( dirname( __FILE__ ) . '/ApiSetup.php' );

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

class ApiTest extends ApiTestSetup {

	function setup() {
		parent::setup();
	}

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
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
	}

	/**
	 * Test result of attempted login with an empty username
	 */
	function testApiLoginNoName() {
		$data = $this->doApiRequest( array( 'action' => 'login',
			'lgname' => '', 'lgpassword' => self::$passWord
		) );
		$this->assertEquals( 'NoName', $data[0]['login']['result'] );
	}

	function testApiLoginBadPass() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$resp = Http::post( self::$apiUrl . "?action=login&format=xml",
						   array( "postData" => array(
									 "lgname" => self::$userName,
									 "lgpassword" => "bad" ) ) );
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->login[0]->attributes()->result[0];
		$this->assertEquals( ' result="NeedToken"', $a->asXML() );

		$token = (string)$sxe->login[0]->attributes()->token;

		$resp = Http::post( self::$apiUrl . "?action=login&format=xml",
						   array( "postData" => array(
									"lgtoken" => $token,
									"lgname" => self::$userName,
									"lgpassword" => "bad" ) ) );


		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->login[0]->attributes()->result[0];

		$this->assertEquals( ' result="NeedToken"', $a->asXML() );
	}

	function testApiLoginGoodPass() {
		global $wgServer;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$req = HttpRequest::factory( self::$apiUrl . "?action=login&format=xml",
			array( "method" => "POST",
				"postData" => array(
				"lgname" => self::$userName,
				"lgpassword" => self::$passWord ) ) );
		$req->execute();

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $req->getContent() );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$this->assertNotType( "null", $sxe->login[0] );

		$a = $sxe->login[0]->attributes()->result[0];
		$this->assertEquals( ' result="NeedToken"', $a->asXML() );
		$token = (string)$sxe->login[0]->attributes()->token;

		$req->setData( array(
			"lgtoken" => $token,
			"lgname" => self::$userName,
			"lgpassword" => self::$passWord ) );
		$req->execute();

		$sxe = simplexml_load_string( $req->getContent() );

		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->login[0]->attributes()->result[0];

		$this->assertEquals( ' result="Success"', $a->asXML() );
	}

	function testApiGotCookie() {
		global $wgServer, $wgScriptPath;

		if ( !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServer to be set in LocalSettings.php' );
		}
		$req = HttpRequest::factory( self::$apiUrl . "?action=login&format=xml",
			array( "method" => "POST",
				"postData" => array(
				"lgname" => self::$userName,
				"lgpassword" => self::$passWord ) ) );
		$req->execute();

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $req->getContent() );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$this->assertNotType( "null", $sxe->login[0] );

		$a = $sxe->login[0]->attributes()->result[0];
		$this->assertEquals( ' result="NeedToken"', $a->asXML() );
		$token = (string)$sxe->login[0]->attributes()->token;

		$req->setData( array(
			"lgtoken" => $token,
			"lgname" => self::$userName,
			"lgpassword" => self::$passWord ) );
		$req->execute();

		$cj = $req->getCookieJar();
		$serverName = parse_url( $wgServer, PHP_URL_HOST );
		$this->assertNotEquals( false, $serverName );
		$serializedCookie = $cj->serializeToHttpRequest( $wgScriptPath, $serverName );
		$this->assertNotEquals( '', $serializedCookie );
		$this->assertRegexp( '/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . self::$userName . '; .*Token=/', $serializedCookie );


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
		$req = HttpRequest::factory( self::$apiUrl . "?action=query&format=xml&prop=revisions&" .
									 "titles=Main%20Page&rvprop=timestamp|user|comment|content" );
		$req->setCookieJar( $cj );
		$req->execute();
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $req->getContent() );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->query[0]->pages[0]->page[0]->attributes();
	}
}
