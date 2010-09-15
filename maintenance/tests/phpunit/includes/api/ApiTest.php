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

	function testApi() {
		global $wgServerName, $wgServer;

		if ( !isset( $wgServerName ) || !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServerName and $wgServer to ' .
									  'be set in LocalSettings.php' );
		}
		/* Haven't thought about test ordering yet -- but this depends on HttpTest.php */
		$resp = Http::get( self::$apiUrl . "?format=xml" );

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
	}

	function testApiLoginNoName() {
		global $wgServerName, $wgServer;

		if ( !isset( $wgServerName ) || !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServerName and $wgServer to ' .
									  'be set in LocalSettings.php' );
		}
		$resp = Http::post( self::$apiUrl . "?action=login&format=xml",
						   array( "postData" => array(
									 "lgname" => "",
									 "lgpassword" => self::$passWord ) ) );
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->login[0]->attributes()->result;
		$this->assertEquals( ' result="NoName"', $a->asXML() );
	}

	function testApiLoginBadPass() {
		global $wgServerName, $wgServer;

		if ( !isset( $wgServerName ) || !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServerName and $wgServer to ' .
									  'be set in LocalSettings.php' );
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
		global $wgServerName, $wgServer;

		if ( !isset( $wgServerName ) || !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServerName and $wgServer to ' .
									  'be set in LocalSettings.php' );
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
		global $wgServerName, $wgServer, $wgScriptPath;

		if ( !isset( $wgServerName ) || !isset( $wgServer ) ) {
			$this->markTestIncomplete( 'This test needs $wgServerName and $wgServer to ' .
									  'be set in LocalSettings.php' );
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

		$a = $sxe->login[0]->attributes()->result[0];
		$this->assertEquals( ' result="NeedToken"', $a->asXML() );
		$token = (string)$sxe->login[0]->attributes()->token;

		$req->setData( array(
			"lgtoken" => $token,
			"lgname" => self::$userName,
			"lgpassword" => self::$passWord ) );
		$req->execute();

		$cj = $req->getCookieJar();
		$this->assertRegexp( '/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . self::$userName . '; .*Token=/',
							 $cj->serializeToHttpRequest( $wgScriptPath, $wgServerName ) );


		return $cj;
	}

	/**
	 * @depends testApiGotCookie
	 */
	function testApiListPages( CookieJar $cj ) {
		$this->markTestIncomplete( "Not done with this yet" );
		global $wgServerName, $wgServer;

		if ( $wgServerName == "localhost" || $wgServer == "http://localhost" ) {
			$this->markTestIncomplete( 'This test needs $wgServerName and $wgServer to ' .
									  'be set in LocalSettings.php' );
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
