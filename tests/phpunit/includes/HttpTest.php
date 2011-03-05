<?php

class MockCookie extends Cookie {
	public function canServeDomain( $arg ) { return parent::canServeDomain( $arg ); }
	public function canServePath( $arg ) { return parent::canServePath( $arg ); }
	public function isUnExpired() { return parent::isUnExpired(); }
}

/**
 * @group Broken
 */
class HttpTest extends MediaWikiTestCase {
	static $content;
	static $headers;
	static $has_curl;
	static $has_fopen;
	static $has_proxy = false;
	static $proxy = "http://hulk:8080/";
	var $test_geturl = array(
		"http://www.example.com/",
		"http://pecl.php.net/feeds/pkg_apc.rss",
		"http://toolserver.org/~jan/poll/dev/main.php?page=wiki_output&id=3",
		"http://meta.wikimedia.org/w/index.php?title=Interwiki_map&action=raw",
		"http://www.mediawiki.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:MediaWiki_hooks&format=php",
	);
	var $test_requesturl = array( "http://en.wikipedia.org/wiki/Special:Export/User:MarkAHershberger" );

	var $test_posturl = array( "http://www.comp.leeds.ac.uk/cgi-bin/Perl/environment-example" => "review=test" );

	function setUp() {
		putenv( "http_proxy" ); /* Remove any proxy env var, so curl doesn't get confused */
		if ( is_array( self::$content ) ) {
			return;
		}
		self::$has_curl = function_exists( 'curl_init' );
		self::$has_fopen = wfIniGetBool( 'allow_url_fopen' );

		if ( !file_exists( "/usr/bin/curl" ) ) {
			$this->markTestIncomplete( "This test requires the curl binary at /usr/bin/curl.	 If you have curl, please file a bug on this test, or, better yet, provide a patch." );
		}

		$content = tempnam( wfTempDir(), "" );
		$headers = tempnam( wfTempDir(), "" );
		if ( !$content && !$headers ) {
			die( "Couldn't create temp file!" );
		}

		// This probably isn't the best test for a proxy, but it works on my system!
		system( "curl -0 -o $content -s " . self::$proxy );
		$out = file_get_contents( $content );
		if ( $out ) {
			self::$has_proxy = true;
		}

		/* Maybe use wget instead of curl here ... just to use a different codebase? */
		foreach ( $this->test_geturl as $u ) {
			system( "curl -0 -s -D $headers '$u' -o $content" );
			self::$content["GET $u"] = file_get_contents( $content );
			self::$headers["GET $u"] = file_get_contents( $headers );
		}
		foreach ( $this->test_requesturl as $u ) {
			system( "curl -0 -s -X POST -H 'Content-Length: 0' -D $headers '$u' -o $content" );
			self::$content["POST $u"] = file_get_contents( $content );
			self::$headers["POST $u"] = file_get_contents( $headers );
		}
		foreach ( $this->test_posturl as $u => $postData ) {
			system( "curl -0 -s -X POST -d '$postData' -D $headers '$u' -o $content" );
			self::$content["POST $u => $postData"] = file_get_contents( $content );
			self::$headers["POST $u => $postData"] = file_get_contents( $headers );
		}
		unlink( $content );
		unlink( $headers );
	}


	function testInstantiation() {
		Http::$httpEngine = false;

		$r = MWHttpRequest::factory( "http://www.example.com/" );
		if ( self::$has_curl ) {
			$this->assertThat( $r, $this->isInstanceOf( 'CurlHttpRequest' ) );
		} else {
			$this->assertThat( $r, $this->isInstanceOf( 'PhpHttpRequest' ) );
		}
		unset( $r );

		if ( !self::$has_fopen ) {
			$this->setExpectedException( 'MWException' );
		}
		Http::$httpEngine = 'php';
		$r = MWHttpRequest::factory( "http://www.example.com/" );
		$this->assertThat( $r, $this->isInstanceOf( 'PhpHttpRequest' ) );
		unset( $r );

		if ( !self::$has_curl ) {
			$this->setExpectedException( 'MWException' );
		}
		Http::$httpEngine = 'curl';
		$r = MWHttpRequest::factory( "http://www.example.com/" );
		if ( self::$has_curl ) {
			$this->assertThat( $r, $this->isInstanceOf( 'CurlHttpRequest' ) );
		}
	}

	function runHTTPFailureChecks() {
		// Each of the following requests should result in a failure.

		$timeout = 1;
		$start_time = time();
		$r = Http::get( "http://www.example.com:1/", $timeout );
		$end_time = time();
		$this->assertLessThan( $timeout + 2, $end_time - $start_time,
							  "Request took less than {$timeout}s via " . Http::$httpEngine );
		$this->assertEquals( $r, false, "false -- what we get on error from Http::get()" );

		$r = Http::get( "http://www.example.com/this-file-does-not-exist", $timeout );
		$this->assertFalse( $r, "False on 404s" );


		$r = MWHttpRequest::factory( "http://www.example.com/this-file-does-not-exist" );
		$er = $r->execute();
		if ( $r instanceof PhpHttpRequest && version_compare( '5.2.10', phpversion(), '>' ) ) {
			$this->assertRegexp( "/HTTP request failed/", $er->getWikiText() );
		} else {
			$this->assertRegexp( "/404 Not Found/", $er->getWikiText() );
		}
	}

	function testFailureDefault() {
		Http::$httpEngine = false;
		$this->runHTTPFailureChecks();
	}

	function testFailurePhp() {
		if ( !self::$has_fopen ) {
			$this->markTestIncomplete( "This test requires allow_url_fopen=true." );
		}

		Http::$httpEngine = "php";
		$this->runHTTPFailureChecks();
	}

	function testFailureCurl() {
		if ( !self::$has_curl ) {
			$this->markTestIncomplete( "This test requires curl." );
		}

		Http::$httpEngine = "curl";
		$this->runHTTPFailureChecks();
	}

	/* ./phase3/includes/Import.php:1108:		$data = Http::request( $method, $url ); */
	/* ./includes/Import.php:1124:			$link = Title::newFromText( "$interwiki:Special:Export/$page" ); */
	/* ./includes/Import.php:1134:			return ImportStreamSource::newFromURL( $url, "POST" ); */
	function runHTTPRequests( $proxy = null ) {
		$opt = array();

		if ( $proxy ) {
			$opt['proxy'] = $proxy;
		} elseif ( $proxy === false ) {
			$opt['noProxy'] = true;
		}

		/* no postData here because the only request I could find in code so far didn't have any */
		foreach ( $this->test_requesturl as $u ) {
			$r = Http::request( "POST", $u, $opt );
			$this->assertEquals( self::$content["POST $u"], "$r", "POST $u with " . Http::$httpEngine );
		}
	}

	function testRequestDefault() {
		Http::$httpEngine = false;
		$this->runHTTPRequests();
	}

	function testRequestPhp() {
		if ( !self::$has_fopen ) {
			$this->markTestIncomplete( "This test requires allow_url_fopen=true." );
		}

		Http::$httpEngine = "php";
		$this->runHTTPRequests();
	}

	function testRequestCurl() {
		if ( !self::$has_curl ) {
			$this->markTestIncomplete( "This test requires curl." );
		}

		Http::$httpEngine = "curl";
		$this->runHTTPRequests();
	}

	function runHTTPGets( $proxy = null ) {
		$opt = array();

		if ( $proxy ) {
			$opt['proxy'] = $proxy;
		} elseif ( $proxy === false ) {
			$opt['noProxy'] = true;
		}

		foreach ( $this->test_geturl as $u ) {
			$r = Http::get( $u, 30, $opt ); /* timeout of 30s */
			$this->assertEquals( self::$content["GET $u"], "$r", "Get $u with " . Http::$httpEngine );
		}
	}

	function testGetDefault() {
		Http::$httpEngine = false;
		$this->runHTTPGets();
	}

	function testGetPhp() {
		if ( !self::$has_fopen ) {
			$this->markTestIncomplete( "This test requires allow_url_fopen=true." );
		}

		Http::$httpEngine = "php";
		$this->runHTTPGets();
	}

	function testGetCurl() {
		if ( !self::$has_curl ) {
			$this->markTestIncomplete( "This test requires curl." );
		}

		Http::$httpEngine = "curl";
		$this->runHTTPGets();
	}

	function runHTTPPosts( $proxy = null ) {
		$opt = array();

		if ( $proxy ) {
			$opt['proxy'] = $proxy;
		} elseif ( $proxy === false ) {
			$opt['noProxy'] = true;
		}

		foreach ( $this->test_posturl as $u => $postData ) {
			$opt['postData'] = $postData;
			$r = Http::post( $u, $opt );
			$this->assertEquals( self::$content["POST $u => $postData"], "$r",
								 "POST $u (postData=$postData) with " . Http::$httpEngine );
		}
	}

	function testPostDefault() {
		Http::$httpEngine = false;
		$this->runHTTPPosts();
	}

	function testPostPhp() {
		if ( !self::$has_fopen ) {
			$this->markTestIncomplete( "This test requires allow_url_fopen=true." );
		}

		Http::$httpEngine = "php";
		$this->runHTTPPosts();
	}

	function testPostCurl() {
		if ( !self::$has_curl ) {
			$this->markTestIncomplete( "This test requires curl." );
		}

		Http::$httpEngine = "curl";
		$this->runHTTPPosts();
	}

	function runProxyRequests() {
		if ( !self::$has_proxy ) {
			$this->markTestIncomplete( "This test requires a proxy." );
		}
		$this->runHTTPGets( self::$proxy );
		$this->runHTTPPosts( self::$proxy );
		$this->runHTTPRequests( self::$proxy );

		// Set false here to do noProxy
		$this->runHTTPGets( false );
		$this->runHTTPPosts( false );
		$this->runHTTPRequests( false );
	}

	function testProxyDefault() {
		Http::$httpEngine = false;
		$this->runProxyRequests();
	}

	function testProxyPhp() {
		if ( !self::$has_fopen ) {
			$this->markTestIncomplete( "This test requires allow_url_fopen=true." );
		}

		Http::$httpEngine = 'php';
		$this->runProxyRequests();
	}

	function testProxyCurl() {
		if ( !self::$has_curl ) {
			$this->markTestIncomplete( "This test requires curl." );
		}

		Http::$httpEngine = 'curl';
		$this->runProxyRequests();
	}

	function testIsLocalUrl() {
	}

	/* ./extensions/DonationInterface/payflowpro_gateway/payflowpro_gateway.body.php:559:		$user_agent = Http::userAgent(); */
	function testUserAgent() {
	}

	function testIsValidUrl() {
	}

	function testValidateCookieDomain() {
		$this->assertFalse( Cookie::validateCookieDomain( "co.uk" ) );
		$this->assertFalse( Cookie::validateCookieDomain( ".co.uk" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "gov.uk" ) );
		$this->assertFalse( Cookie::validateCookieDomain( ".gov.uk" ) );
		$this->assertTrue( Cookie::validateCookieDomain( "supermarket.uk" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "uk" ) );
		$this->assertFalse( Cookie::validateCookieDomain( ".uk" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "127.0.0." ) );
		$this->assertFalse( Cookie::validateCookieDomain( "127." ) );
		$this->assertFalse( Cookie::validateCookieDomain( "127.0.0.1." ) );
		$this->assertTrue( Cookie::validateCookieDomain( "127.0.0.1" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "333.0.0.1" ) );
		$this->assertTrue( Cookie::validateCookieDomain( "example.com" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "example.com." ) );
		$this->assertTrue( Cookie::validateCookieDomain( ".example.com" ) );

		$this->assertTrue( Cookie::validateCookieDomain( ".example.com", "www.example.com" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "example.com", "www.example.com" ) );
		$this->assertTrue( Cookie::validateCookieDomain( "127.0.0.1", "127.0.0.1" ) );
		$this->assertFalse( Cookie::validateCookieDomain( "127.0.0.1", "localhost" ) );


	}

	function testSetCooke() {
		$c = new MockCookie( "name", "value",
							 array(
								 "domain" => "ac.th",
								 "path" => "/path/",
							 ) );
		$this->assertFalse( $c->canServeDomain( "ac.th" ) );

		$c = new MockCookie( "name", "value",
							 array(
								 "domain" => "example.com",
								 "path" => "/path/",
							 ) );

		$this->assertTrue( $c->canServeDomain( "example.com" ) );
		$this->assertFalse( $c->canServeDomain( "www.example.com" ) );

		$c = new MockCookie( "name", "value",
							 array(
								 "domain" => ".example.com",
								 "path" => "/path/",
							 ) );

		$this->assertFalse( $c->canServeDomain( "www.example.net" ) );
		$this->assertFalse( $c->canServeDomain( "example.com" ) );
		$this->assertTrue( $c->canServeDomain( "www.example.com" ) );

		$this->assertFalse( $c->canServePath( "/" ) );
		$this->assertFalse( $c->canServePath( "/bogus/path/" ) );
		$this->assertFalse( $c->canServePath( "/path" ) );
		$this->assertTrue( $c->canServePath( "/path/" ) );

		$this->assertTrue( $c->isUnExpired() );

		$this->assertEquals( "", $c->serializeToHttpRequest( "/path/", "www.example.net" ) );
		$this->assertEquals( "", $c->serializeToHttpRequest( "/", "www.example.com" ) );
		$this->assertEquals( "name=value", $c->serializeToHttpRequest( "/path/", "www.example.com" ) );

		$c = new MockCookie( "name", "value",
							 array(
								 "domain" => "www.example.com",
								 "path" => "/path/",
							 ) );
		$this->assertFalse( $c->canServeDomain( "example.com" ) );
		$this->assertFalse( $c->canServeDomain( "www.example.net" ) );
		$this->assertTrue( $c->canServeDomain( "www.example.com" ) );

		$c = new MockCookie( "name", "value",
						 array(
							 "domain" => ".example.com",
							 "path" => "/path/",
							 "expires" => "-1 day",
						 ) );
		$this->assertFalse( $c->isUnExpired() );
		$this->assertEquals( "", $c->serializeToHttpRequest( "/path/", "www.example.com" ) );

		$c = new MockCookie( "name", "value",
						 array(
							 "domain" => ".example.com",
							 "path" => "/path/",
							 "expires" => "+1 day",
						 ) );
		$this->assertTrue( $c->isUnExpired() );
		$this->assertEquals( "name=value", $c->serializeToHttpRequest( "/path/", "www.example.com" ) );
	}

	function testCookieJarSetCookie() {
		$cj = new CookieJar;
		$cj->setCookie( "name", "value",
						 array(
							 "domain" => ".example.com",
							 "path" => "/path/",
						 ) );
		$cj->setCookie( "name2", "value",
						 array(
							 "domain" => ".example.com",
							 "path" => "/path/sub",
						 ) );
		$cj->setCookie( "name3", "value",
						 array(
							 "domain" => ".example.com",
							 "path" => "/",
						 ) );
		$cj->setCookie( "name4", "value",
						 array(
							 "domain" => ".example.net",
							 "path" => "/path/",
						 ) );
		$cj->setCookie( "name5", "value",
						 array(
							 "domain" => ".example.net",
							 "path" => "/path/",
							 "expires" => "-1 day",
						 ) );

		$this->assertEquals( "name4=value", $cj->serializeToHttpRequest( "/path/", "www.example.net" ) );
		$this->assertEquals( "name3=value", $cj->serializeToHttpRequest( "/", "www.example.com" ) );
		$this->assertEquals( "name=value; name3=value", $cj->serializeToHttpRequest( "/path/", "www.example.com" ) );

		$cj->setCookie( "name5", "value",
						 array(
							 "domain" => ".example.net",
							 "path" => "/path/",
							 "expires" => "+1 day",
						 ) );
		$this->assertEquals( "name4=value; name5=value", $cj->serializeToHttpRequest( "/path/", "www.example.net" ) );

		$cj->setCookie( "name4", "value",
						 array(
							 "domain" => ".example.net",
							 "path" => "/path/",
							 "expires" => "-1 day",
						 ) );
		$this->assertEquals( "name5=value", $cj->serializeToHttpRequest( "/path/", "www.example.net" ) );
	}

	function testParseResponseHeader() {
		$cj = new CookieJar;

		$h[] = "Set-Cookie: name4=value; domain=.example.com; path=/; expires=Mon, 09-Dec-2029 13:46:00 GMT";
		$cj->parseCookieResponseHeader( $h[0], "www.example.com" );
		$this->assertEquals( "name4=value", $cj->serializeToHttpRequest( "/", "www.example.com" ) );

		$h[] = "name4=value2; domain=.example.com; path=/path/; expires=Mon, 09-Dec-2029 13:46:00 GMT";
		$cj->parseCookieResponseHeader( $h[1], "www.example.com" );
		$this->assertEquals( "", $cj->serializeToHttpRequest( "/", "www.example.com" ) );
		$this->assertEquals( "name4=value2", $cj->serializeToHttpRequest( "/path/", "www.example.com" ) );

		$h[] = "name5=value3; domain=.example.com; path=/path/; expires=Mon, 09-Dec-2029 13:46:00 GMT";
		$cj->parseCookieResponseHeader( $h[2], "www.example.com" );
		$this->assertEquals( "name4=value2; name5=value3", $cj->serializeToHttpRequest( "/path/", "www.example.com" ) );

		$h[] = "name6=value3; domain=.example.net; path=/path/; expires=Mon, 09-Dec-2029 13:46:00 GMT";
		$cj->parseCookieResponseHeader( $h[3], "www.example.com" );
		$this->assertEquals( "", $cj->serializeToHttpRequest( "/path/", "www.example.net" ) );

		$h[] = "name6=value0; domain=.example.net; path=/path/; expires=Mon, 09-Dec-1999 13:46:00 GMT";
		$cj->parseCookieResponseHeader( $h[4], "www.example.net" );
		$this->assertEquals( "", $cj->serializeToHttpRequest( "/path/", "www.example.net" ) );

		$h[] = "name6=value4; domain=.example.net; path=/path/; expires=Mon, 09-Dec-2029 13:46:00 GMT";
		$cj->parseCookieResponseHeader( $h[5], "www.example.net" );
		$this->assertEquals( "name6=value4", $cj->serializeToHttpRequest( "/path/", "www.example.net" ) );
	}

	function runCookieRequests() {
		$r = MWHttpRequest::factory( "http://www.php.net/manual", array( 'followRedirects' => true ) );
		$r->execute();

		$jar = $r->getCookieJar();
		$this->assertThat( $jar, $this->isInstanceOf( 'CookieJar' ) );

		if ( $r instanceof PhpHttpRequest && version_compare( '5.1.7', phpversion(), '>' ) ) {
			$this->markTestSkipped( 'Redirection fails or crashes PHP on 5.1.6 and prior' );
		}
		$serialized = $jar->serializeToHttpRequest( "/search?q=test", "www.php.net" );
		$this->assertRegExp( '/\bCOUNTRY=[^=;]+/', $serialized );
		$this->assertRegExp( '/\bLAST_LANG=[^=;]+/', $serialized );
		$this->assertEquals( '', $jar->serializeToHttpRequest( "/search?q=test", "www.php.com" ) );
	}

	function testCookieRequestDefault() {
		Http::$httpEngine = false;
		$this->runCookieRequests();
	}
	function testCookieRequestPhp() {
		if ( !self::$has_fopen ) {
			$this->markTestIncomplete( "This test requires allow_url_fopen=true." );
		}

		Http::$httpEngine = 'php';
		$this->runCookieRequests();
	}
	function testCookieRequestCurl() {
		if ( !self::$has_curl ) {
			$this->markTestIncomplete( "This test requires curl." );
		}

		Http::$httpEngine = 'curl';
		$this->runCookieRequests();
	}

	/**
	 * Test Http::isValidURI()
	 * @bug 27854 : Http::isValidURI is to lax
	 *@dataProvider provideURI */
	function testIsValidUri( $expect, $URI, $message = '' ) {
		$this->assertEquals(
			$expect,
			(bool) Http::isValidURI( $URI ),
			$message
		);
	}

	/**
	 * Feeds URI to test a long regular expression in Http::isValidURI
	 */
	function provideURI() {
		/** Format: 'boolean expectation', 'URI to test', 'Optional message' */
		return array(
			array( false, '¿non sens before!! http://a', 'Allow anything before URI' ),

			# (ftp|http|https) - only three schemes allowed 
			array( true,  'http://www.example.org/' ),
			array( true,  'https://www.example.org/' ),
			array( true,  'ftp://www.example.org/' ),
			array( true,  'http://www.example.org', 'URI without directory' ),
			array( true,  'http://a', 'Short name' ),
			array( true, 'http://étoile', 'Allow UTF-8 in hostname' ),  # 'étoile' is french for 'star'
			array( false, '\\host\directory', 'CIFS share' ),
			array( false, 'gopher://host/dir', 'Reject gopher scheme' ),
			array( false, 'telnet://host', 'Reject telnet scheme' ),
			
			# :\/\/ - double slashes
			array( false,  'http//example.org', 'Reject missing column in protocol' ),
			array( false,  'http:/example.org', 'Reject missing slash in protocol' ),
			array( false,  'http:example.org', 'Must have two slashes' ),
			# Following fail since hostname can be made of anything
			array( false,  'http:///example.org', 'Must have exactly two slashes, not three' ),

			# (\w+:{0,1}\w*@)? - optional user:pass
			array( true,  'http://user@host', 'Username provided' ),
			array( true,  'http://user:@host', 'Username provided, no password' ),
			array( true,  'http://user:pass@host', 'Username and password provided' ),

			# (\S+) - host part is made of anything not whitespaces
			array( false, 'http://!"èèè¿¿¿~~\'', 'hostname is made of any non whitespace' ),
			array( false, 'http://exam:ple.org/', 'hostname can not use columns!' ),

			# (:[0-9]+)? - port number
			array( true, 'http://example.org:80/' ),
			array( true, 'https://example.org:80/' ),
			array( true, 'http://example.org:443/' ),
			array( true, 'https://example.org:443/' ),
			array( true, 'ftp://example.org:1/', 'Minimum' ),
			array( true, 'ftp://example.org:65535/', 'Maximum port number' ),

			# Part after the hostname is / or / with something else
			array( true, 'http://example/#' ),
			array( true, 'http://example/!' ),
			array( true, 'http://example/:' ),
			array( true, 'http://example/.' ),
			array( true, 'http://example/?' ),
			array( true, 'http://example/+' ),
			array( true, 'http://example/=' ),
			array( true, 'http://example/&' ),
			array( true, 'http://example/%' ),
			array( true, 'http://example/@' ),
			array( true, 'http://example/-' ),
			array( true, 'http://example//' ),
			array( true, 'http://example/&' ),

			# Fragment
			array( true, 'http://exam#ple.org', ),  # This one is valid, really!
			array( true, 'http://example.org:80#anchor' ),
			array( true, 'http://example.org/?id#anchor' ),
			array( true, 'http://example.org/?#anchor' ),

			array( false, 'http://a ¿non !!sens after', 'Allow anything after URI' ),
		);
	}

}
