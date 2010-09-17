<?php

class MockCookie extends Cookie {
	public function canServeDomain( $arg ) { return parent::canServeDomain( $arg ); }
	public function canServePath( $arg ) { return parent::canServePath( $arg ); }
	public function isUnExpired() { return parent::isUnExpired(); }
}

/**
 * @group Broken
 */
class HttpTest extends PHPUnit_Framework_TestCase {
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

	function setup() {
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

		$r = HttpRequest::factory( "http://www.example.com/" );
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
		$r = HttpRequest::factory( "http://www.example.com/" );
		$this->assertThat( $r, $this->isInstanceOf( 'PhpHttpRequest' ) );
		unset( $r );

		if ( !self::$has_curl ) {
			$this->setExpectedException( 'MWException' );
		}
		Http::$httpEngine = 'curl';
		$r = HttpRequest::factory( "http://www.example.com/" );
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


		$r = HttpRequest::factory( "http://www.example.com/this-file-does-not-exist" );
		$er = $r->execute();
		if ( is_a( $r, 'PhpHttpRequest' ) && version_compare( '5.2.10', phpversion(), '>' ) ) {
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

	/* ./extensions/SpamBlacklist/SpamBlacklist_body.php:164:			$httpText = Http::get( $fileName ); */
	/* ./extensions/ApiSVGProxy/ApiSVGProxy.body.php:44:		$contents = Http::get( $file->getFullUrl() ); */
	/* ./extensions/BookInformation/drivers/IsbnDb.php:24:			if( ( $xml = Http::get( $uri ) ) !== false ) { */
	/* ./extensions/BookInformation/drivers/Amazon.php:23:			if( ( $xml = Http::get( $uri ) ) !== false ) { */
	/* ./extensions/TitleBlacklist/TitleBlacklist.list.php:217:			$result = Http::get( $url ); */
	/* ./extensions/TSPoll/TSPoll.php:68:	   $get_server = Http::get( 'http://toolserver.org/~jan/poll/dev/main.php?page=wiki_output&id='.$id ); */
	/* ./extensions/TSPoll/TSPoll.php:70:	   $get_server = Http::get( 'http://toolserver.org/~jan/poll/main.php?page=wiki_output&id='.$id ); */
	/* ./extensions/DoubleWiki/DoubleWiki.php:56:			$translation = Http::get( $url.$sep.'action=render' ); */
	/* ./extensions/ExternalPages/ExternalPages_body.php:177:			$serializedText = Http::get( $this->mPageURL ); */
	/* ./extensions/Translate/utils/TranslationHelpers.php:143:		$suggestions = Http::get( $url, $timeout ); */
	/* ./extensions/Translate/SpecialImportTranslations.php:169:			$filedata = Http::get( $url ); ; */
	/* ./extensions/Translate/TranslateEditAddons.php:338:			$suggestions = Http::get( $url, $timeout ); */
	/* ./extensions/SecurePoll/includes/user/Auth.php:283:		$value = Http::get( $url, 20, $curlParams ); */
	/* ./extensions/DumpHTML/dumpHTML.inc:778:			$contents = Http::get( $url ); */
	/* ./extensions/DumpHTML/dumpHTML.inc:1298:			$contents = Http::get( $sourceUrl ); */
	/* ./extensions/DumpHTML/dumpHTML.inc:1373:			$contents = Http::get( $sourceUrl ); */
	/* ./phase3/maintenance/rebuildInterwiki.inc:101:	$intermap = Http::get( 'http://meta.wikimedia.org/w/index.php?title=Interwiki_map&action=raw', 30 ); */
	/* ./phase3/maintenance/findhooks.php:98:			$allhookdata = Http::get( 'http://www.mediawiki.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:MediaWiki_hooks&cmlimit=500&format=php' ); */
	/* ./phase3/maintenance/findhooks.php:109:			$oldhookdata = Http::get( 'http://www.mediawiki.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:Removed_hooks&cmlimit=500&format=php' ); */
	/* ./phase3/maintenance/dumpInterwiki.inc:95:	$intermap = Http::get( 'http://meta.wikimedia.org/w/index.php?title=Interwiki_map&action=raw', 30 ); */
	/* ./phase3/includes/parser/Parser.php:3204:		$text = Http::get($url); */
	/* ./phase3/includes/filerepo/ForeignAPIRepo.php:131:				$data = Http::get( $url ); */
	/* ./phase3/includes/filerepo/ForeignAPIRepo.php:205:			$thumb = Http::get( $foreignUrl ); */
	/* ./phase3/includes/filerepo/File.php:1105:			$res = Http::get( $renderUrl ); */
	/* ./phase3/includes/GlobalFunctions.php:2760: * @deprecated Use Http::get() instead */
	/* ./phase3/includes/GlobalFunctions.php:2764:	return Http::get( $url ); */
	/* ./phase3/includes/ExternalStoreHttp.php:18:		$ret = Http::get( $url ); */
	/* ./phase3/includes/Import.php:357:		$data = Http::get( $src ); */
	/* ./extensions/ExternalData/ED_Utils.php:291:				return Http::get( $url, 'default', array(CURLOPT_SSL_VERIFYPEER => false) ); */
	/* ./extensions/ExternalData/ED_Utils.php:293:				return Http::get( $url ); */
	/* ./extensions/ExternalData/ED_Utils.php:306:				$page = Http::get( $url, 'default', array(CURLOPT_SSL_VERIFYPEER => false) ); */
	/* ./extensions/ExternalData/ED_Utils.php:308:				$page = Http::get( $url ); */
	/* ./extensions/CodeReview/backend/Subversion.php:320:		$blob = Http::get( $target, $this->mTimeout ); */
	/* ./extensions/AmazonPlus/AmazonPlus.php:214:		$this->response = Http::get( $urlstr ); */
	/* ./extensions/StaticWiki/StaticWiki.php:24:	$text = Http::get( $url ) ; */
	/* ./extensions/StaticWiki/StaticWiki.php:64:	$history = Http::get ( $wgStaticWikiExternalSite . "index.php?title=" . urlencode ( $url_title ) . "&action=history" ) ; */
	/* ./extensions/Configure/scripts/findSettings.php:126:				$cont = Http::get( "http://www.mediawiki.org/w/index.php?title={$page}&action=raw" ); */
	/* ./extensions/TorBlock/TorBlock.class.php:148:		$data = Http::get( $url ); */
	/* ./extensions/HoneypotIntegration/HoneypotIntegration.class.php:60:		$data = Http::get( $wgHoneypotURLSource, 'default', */
	/* ./extensions/SemanticForms/includes/SF_Utils.inc:378:		$page_contents = Http::get($url); */
	/* ./extensions/LocalisationUpdate/LocalisationUpdate.class.php:172:				$basefilecontents = Http::get( $basefile ); */
	/* ./extensions/APC/SpecialAPC.php:245:		$rss = Http::get( 'http://pecl.php.net/feeds/pkg_apc.rss' ); */
	/* ./extensions/Interlanguage/Interlanguage.php:56:		$a = Http::get( $url ); */
	/* ./extensions/MWSearch/MWSearch_body.php:492:		$data = Http::get( $searchUrl, $wgLuceneSearchTimeout, $httpOpts);	*/
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

	/* ./phase3/maintenance/parserTests.inc:1618:		return Http::post( $url, array( 'postData' => wfArrayToCGI( $data ) ) ); */
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
		$r = HttpRequest::factory( "http://www.php.net/manual", array( 'followRedirects' => true ) );
		$r->execute();

		$jar = $r->getCookieJar();
		$this->assertThat( $jar, $this->isInstanceOf( 'CookieJar' ) );

		if ( is_a( $r, 'PhpHttpRequest' ) && version_compare( '5.1.7', phpversion(), '>' ) ) {
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
}
