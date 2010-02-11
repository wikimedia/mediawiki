<?php

require_once( "ApiSetup.php" );

class ApiTest extends ApiSetup {

	function setup() {
		if($wgServerName == "localhost" || $wgServer == "http://localhost") {
			$this->markTestIncomplete('This test needs $wgServerName and $wgServer to '.
									  'be set in LocalSettings.php');
		}
		parent::setup();
	}

	function testApi() {
		/* Haven't thought about test ordering yet -- but this depends on HttpTest.php */
		$resp = Http::get( self::$apiUrl . "?format=xml" );

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
	}

	function testApiLoginNoName() {
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
		$resp = Http::post( self::$apiUrl . "?action=login&format=xml",
						   array( "postData" => array(
									 "lgname" => self::$userName,
									 "lgpassword" => "bad" ) ) );
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->login[0]->attributes()->result;
		$this->assertEquals( ' result="WrongPass"', $a->asXML() );
	}

	function testApiLoginGoodPass() {
		$resp = Http::post( self::$apiUrl . "?action=login&format=xml",
						   array( "postData" => array(
									 "lgname" => self::$userName,
									 "lgpassword" => self::$passWord ) ) );
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->login[0]->attributes()->result;
		$this->assertEquals( ' result="Success"', $a->asXML() );
	}

	function testApiGotCookie() {
		global $wgScriptPath, $wgServerName;

		$req = HttpRequest::factory( self::$apiUrl . "?action=login&format=xml",
									 array( "method" => "POST",
											"postData" => array( "lgname" => self::$userName,
																 "lgpassword" => self::$passWord ) ) );
		$req->execute();
		$cj = $req->getCookieJar();
		$this->assertRegexp( '/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . self::$userName . '; .*Token=/',
							 $cj->serializeToHttpRequest( $wgScriptPath, $wgServerName ) );


		return $cj;
	}

	/**
	 * @depends testApiGotCookie
	 */
	function testApiListPages(CookieJar $cj) {
		$this->markTestIncomplete("Not done with this yet");

		$req = HttpRequest::factory( self::$apiUrl . "?action=query&format=xml&prop=revisions&".
									 "titles=Main%20Page&rvprop=timestamp|user|comment|content" );
		$req->setCookieJar($cj);
		$req->execute();
		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $req->getContent() );
		$this->assertNotType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
		$a = $sxe->query[0]->pages[0]->page[0]->attributes();
	}
}
