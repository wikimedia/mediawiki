<?php

class HttpTest extends PhpUnit_Framework_TestCase {
	static $content;
	static $headers;
	static $has_curl;
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
		putenv("http_proxy");	/* Remove any proxy env var, so curl doesn't get confused */
		if ( is_array( self::$content ) ) {
			return;
		}
		self::$has_curl = function_exists( 'curl_init' );

		if ( !file_exists("/usr/bin/curl") ) {
			$this->markTestIncomplete("This test requires the curl binary at /usr/bin/curl.  If you have curl, please file a bug on this test, or, better yet, provide a patch.");
		}

		$content = tempnam( sys_get_temp_dir(), "" );
		$headers = tempnam( sys_get_temp_dir(), "" );
		if ( !$content && !$headers ) {
			die( "Couldn't create temp file!" );
		}

		// This probably isn't the best test for a proxy, but it works on my system!
		system("curl -0 -o $content -s ".self::$proxy);
		$out = file_get_contents( $content );
		if( $out ) {
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
		foreach ( $this->test_posturl as $u => $postdata ) {
			system( "curl -0 -s -X POST -d '$postdata' -D $headers '$u' -o $content" );
			self::$content["POST $u => $postdata"] = file_get_contents( $content );
			self::$headers["POST $u => $postdata"] = file_get_contents( $headers );
		}
		unlink( $content );
		unlink( $headers );
	}


	function testInstantiation() {
		global $wgHTTPEngine;

		unset($wgHTTPEngine);
	    $r = new HttpRequest("http://www.example.com/");
		if ( self::$has_curl ) {
			$this->isInstanceOf( $r, 'CurlHttpRequest' );
		} else {
			$this->isInstanceOf( $r, 'PhpHttpRequest' );
		}
		unset($r);

		$wgHTTPEngine = 'php';
		$r = new HttpRequest("http://www.example.com/");
		$this->isInstanceOf( $r, 'PhpHttpRequest' );
		unset($r);

		if( !self::$has_curl ) {
			$this->setExpectedException( 'MWException' );
		}
		$wgHTTPEngine = 'curl';
		$r = new HttpRequest("http://www.example.com/");
		if( self::$has_curl ) {
			$this->isInstanceOf( $r, 'CurlHttpRequest' );
		}
	}

	function runHTTPFailureChecks() {
		global $wgHTTPEngine;
		// Each of the following requests should result in a failure.

		$timeout = 1;
		$start_time = time();
		$r = HTTP::get( "http://www.example.com:1/", $timeout);
		$end_time = time();
		$this->assertLessThan($timeout+2, $end_time - $start_time,
							  "Request took less than {$timeout}s via $wgHTTPEngine");
		$this->assertEquals($r, false, "false -- what we get on error from Http::get()");
	}

	function testFailureDefault() {
		global $wgHTTPEngine;

		unset($wgHTTPEngine);
		self::runHTTPFailureChecks();
	}

	function testFailurePhp() {
		global $wgHTTPEngine;

		$wgHTTPEngine = "php";
		self::runHTTPFailureChecks();
	}

	function testFailureCurl() {
		global $wgHTTPEngine;

		if (!self::$has_curl ) {
			$this->markTestIncomplete("This test requires curl.");
		}

		$wgHTTPEngine = "curl";
		self::runHTTPFailureChecks();
	}

	/* ./phase3/includes/Import.php:1108:		$data = Http::request( $method, $url ); */
	/* ./includes/Import.php:1124:          $link = Title::newFromText( "$interwiki:Special:Export/$page" ); */
	/* ./includes/Import.php:1134:			return ImportStreamSource::newFromURL( $url, "POST" ); */
	function runHTTPRequests($proxy=null) {
		global $wgHTTPEngine;
		$opt = array();

		if($proxy) {
			$opt['proxy'] = $proxy;
		}

		/* no postdata here because the only request I could find in code so far didn't have any */
		foreach ( $this->test_requesturl as $u ) {
			$r = Http::request( "POST", $u, $opt );
			$this->assertEquals( self::$content["POST $u"], "$r", "POST $u with $wgHTTPEngine" );
		}
	}

	function testRequestDefault() {
		global $wgHTTPEngine;

		unset($wgHTTPEngine);
		self::runHTTPRequests();
	}

	function testRequestPhp() {
		global $wgHTTPEngine;

		$wgHTTPEngine = "php";
		self::runHTTPRequests();
	}

	function testRequestCurl() {
		global $wgHTTPEngine;

		if (!self::$has_curl ) {
			$this->markTestIncomplete("This test requires curl.");
		}

		$wgHTTPEngine = "curl";
		self::runHTTPRequests();
	}

	/* ./extensions/SpamBlacklist/SpamBlacklist_body.php:164:			$httpText = Http::get( $fileName ); */
	/* ./extensions/ApiSVGProxy/ApiSVGProxy.body.php:44:		$contents = Http::get( $file->getFullUrl() ); */
	/* ./extensions/BookInformation/drivers/IsbnDb.php:24:			if( ( $xml = Http::get( $uri ) ) !== false ) { */
	/* ./extensions/BookInformation/drivers/Amazon.php:23:			if( ( $xml = Http::get( $uri ) ) !== false ) { */
	/* ./extensions/TitleBlacklist/TitleBlacklist.list.php:217:			$result = Http::get( $url ); */
	/* ./extensions/TSPoll/TSPoll.php:68:      $get_server = Http::get( 'http://toolserver.org/~jan/poll/dev/main.php?page=wiki_output&id='.$id ); */
	/* ./extensions/TSPoll/TSPoll.php:70:      $get_server = Http::get( 'http://toolserver.org/~jan/poll/main.php?page=wiki_output&id='.$id ); */
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
	/* ./extensions/MWSearch/MWSearch_body.php:492:		$data = Http::get( $searchUrl, $wgLuceneSearchTimeout, $httpOpts);  */
	function runHTTPGets($proxy=null) {
		global $wgHTTPEngine;
		$opt = array();

		if($proxy) {
			$opt['proxy'] = $proxy;
		}

		foreach ( $this->test_geturl as $u ) {
			$r = Http::get( $u, 30, $opt ); /* timeout of 30s */
			$this->assertEquals( self::$content["GET $u"], "$r", "Get $u with $wgHTTPEngine" );
		}
	}

	function testGetDefault() {
		global $wgHTTPEngine;

		unset($wgHTTPEngine);
		self::runHTTPGets();
	}

	function testGetPhp() {
		global $wgHTTPEngine;

		$wgHTTPEngine = "php";
		self::runHTTPGets();
	}

	function testGetCurl() {
		global $wgHTTPEngine;

		if (!self::$has_curl ) {
			$this->markTestIncomplete("This test requires curl.");
		}

		$wgHTTPEngine = "curl";
		self::runHTTPGets();
	}

	/* ./phase3/maintenance/parserTests.inc:1618:		return Http::post( $url, array( 'postdata' => wfArrayToCGI( $data ) ) ); */
	function runHTTPPosts($proxy=null) {
		global $wgHTTPEngine;
		$opt = array();

		if($proxy) {
			$opt['proxy'] = $proxy;
		}

		foreach ( $this->test_posturl as $u => $postdata ) {
			$opt['postdata'] = $postdata;
			$r = Http::post( $u, $opt );
			$this->assertEquals( self::$content["POST $u => $postdata"], "$r",
								 "POST $u (postdata=$postdata) with $wgHTTPEngine" );
		}
	}

	function testPostDefault() {
		global $wgHTTPEngine;

		unset($wgHTTPEngine);
		self::runHTTPPosts();
	}

	function testPostPhp() {
		global $wgHTTPEngine;

		$wgHTTPEngine = "php";
		self::runHTTPPosts();
	}

	function testPostCurl() {
		global $wgHTTPEngine;

		if (!self::$has_curl ) {
			$this->markTestIncomplete("This test requires curl.");
		}

		$wgHTTPEngine = "curl";
		self::runHTTPPosts();
	}

	function runProxyRequests() {
		global $wgHTTPEngine;

		if(!self::$has_proxy) {
			$this->markTestIncomplete("This test requires a proxy.");
		}
		self::runHTTPGets(self::$proxy);
		self::runHTTPPosts(self::$proxy);
		self::runHTTPRequests(self::$proxy);
	}

	function testProxyDefault() {
		global $wgHTTPEngine;

		unset($wgHTTPEngine);
		self::runProxyRequests();
	}

	function testProxyPhp() {
		global $wgHTTPEngine;

		$wgHTTPEngine = 'php';
		self::runProxyRequests();
	}

	function testProxyCurl() {
		global $wgHTTPEngine;

		if (!self::$has_curl ) {
			$this->markTestIncomplete("This test requires curl.");
		}

		$wgHTTPEngine = 'curl';
		self::runProxyRequests();
	}

	function testIsLocalUrl() {
	}

	/* ./extensions/DonationInterface/payflowpro_gateway/payflowpro_gateway.body.php:559:		$user_agent = Http::userAgent(); */
	function testUserAgent() {
	}

	function testIsValidUrl() {
	}

}