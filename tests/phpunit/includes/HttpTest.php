<?php

/**
 * @group Http
 */
class HttpTest extends MediaWikiTestCase {
	/**
	 * @dataProvider cookieDomains
	 * @covers Cookie::validateCookieDomain
	 */
	public function testValidateCookieDomain( $expected, $domain, $origin = null ) {
		if ( $origin ) {
			$ok = Cookie::validateCookieDomain( $domain, $origin );
			$msg = "$domain against origin $origin";
		} else {
			$ok = Cookie::validateCookieDomain( $domain );
			$msg = "$domain";
		}
		$this->assertEquals( $expected, $ok, $msg );
	}

	public static function cookieDomains() {
		return array(
			array( false, "org" ),
			array( false, ".org" ),
			array( true, "wikipedia.org" ),
			array( true, ".wikipedia.org" ),
			array( false, "co.uk" ),
			array( false, ".co.uk" ),
			array( false, "gov.uk" ),
			array( false, ".gov.uk" ),
			array( true, "supermarket.uk" ),
			array( false, "uk" ),
			array( false, ".uk" ),
			array( false, "127.0.0." ),
			array( false, "127." ),
			array( false, "127.0.0.1." ),
			array( true, "127.0.0.1" ),
			array( false, "333.0.0.1" ),
			array( true, "example.com" ),
			array( false, "example.com." ),
			array( true, ".example.com" ),

			array( true, ".example.com", "www.example.com" ),
			array( false, "example.com", "www.example.com" ),
			array( true, "127.0.0.1", "127.0.0.1" ),
			array( false, "127.0.0.1", "localhost" ),
		);
	}

	/**
	 * Test Http::isValidURI()
	 * @bug 27854 : Http::isValidURI is too lax
	 * @dataProvider provideURI
	 * @covers Http::isValidURI
	 */
	public function testIsValidUri( $expect, $URI, $message = '' ) {
		$this->assertEquals(
			$expect,
			(bool)Http::isValidURI( $URI ),
			$message
		);
	}

	/**
	 * Feeds URI to test a long regular expression in Http::isValidURI
	 */
	public static function provideURI() {
		/** Format: 'boolean expectation', 'URI to test', 'Optional message' */
		return array(
			array( false, '¿non sens before!! http://a', 'Allow anything before URI' ),

			# (http|https) - only two schemes allowed
			array( true, 'http://www.example.org/' ),
			array( true, 'https://www.example.org/' ),
			array( true, 'http://www.example.org', 'URI without directory' ),
			array( true, 'http://a', 'Short name' ),
			array( true, 'http://étoile', 'Allow UTF-8 in hostname' ), # 'étoile' is french for 'star'
			array( false, '\\host\directory', 'CIFS share' ),
			array( false, 'gopher://host/dir', 'Reject gopher scheme' ),
			array( false, 'telnet://host', 'Reject telnet scheme' ),

			# :\/\/ - double slashes
			array( false, 'http//example.org', 'Reject missing colon in protocol' ),
			array( false, 'http:/example.org', 'Reject missing slash in protocol' ),
			array( false, 'http:example.org', 'Must have two slashes' ),
			# Following fail since hostname can be made of anything
			array( false, 'http:///example.org', 'Must have exactly two slashes, not three' ),

			# (\w+:{0,1}\w*@)? - optional user:pass
			array( true, 'http://user@host', 'Username provided' ),
			array( true, 'http://user:@host', 'Username provided, no password' ),
			array( true, 'http://user:pass@host', 'Username and password provided' ),

			# (\S+) - host part is made of anything not whitespaces
			// commented these out in order to remove @group Broken
			// @todo are these valid tests? if so, fix Http::isValidURI so it can handle them
			//array( false, 'http://!"èèè¿¿¿~~\'', 'hostname is made of any non whitespace' ),
			//array( false, 'http://exam:ple.org/', 'hostname can not use colons!' ),

			# (:[0-9]+)? - port number
			array( true, 'http://example.org:80/' ),
			array( true, 'https://example.org:80/' ),
			array( true, 'http://example.org:443/' ),
			array( true, 'https://example.org:443/' ),

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
			array( true, 'http://exam#ple.org', ), # This one is valid, really!
			array( true, 'http://example.org:80#anchor' ),
			array( true, 'http://example.org/?id#anchor' ),
			array( true, 'http://example.org/?#anchor' ),

			array( false, 'http://a ¿non !!sens after', 'Allow anything after URI' ),
		);
	}

	/**
	 * Warning:
	 *
	 * These tests are for code that makes use of an artifact of how CURL
	 * handles header reporting on redirect pages, and will need to be
	 * rewritten when bug 29232 is taken care of (high-level handling of
	 * HTTP redirects).
	 */
	public function testRelativeRedirections() {
		$h = MWHttpRequestTester::factory( 'http://oldsite/file.ext', array(), __METHOD__ );

		# Forge a Location header
		$h->setRespHeaders( 'location', array(
				'http://newsite/file.ext',
				'/newfile.ext',
			)
		);
		# Verify we correctly fix the Location
		$this->assertEquals(
			'http://newsite/newfile.ext',
			$h->getFinalUrl(),
			"Relative file path Location: interpreted as full URL"
		);

		$h->setRespHeaders( 'location', array(
				'https://oldsite/file.ext'
			)
		);
		$this->assertEquals(
			'https://oldsite/file.ext',
			$h->getFinalUrl(),
			"Location to the HTTPS version of the site"
		);

		$h->setRespHeaders( 'location', array(
				'/anotherfile.ext',
				'http://anotherfile/hoster.ext',
				'https://anotherfile/hoster.ext'
			)
		);
		$this->assertEquals(
			'https://anotherfile/hoster.ext',
			$h->getFinalUrl( "Relative file path Location: should keep the latest host and scheme!" )
		);
	}

	/**
	 * Constant values are from PHP 5.3.28 using cURL 7.24.0
	 * @see http://php.net/manual/en/curl.constants.php
	 *
	 * All constant values are present so that developers don’t need to remember
	 * to add them if added at a later date. The commented out constants were
	 * not found anywhere in the MediaWiki core code.
	 *
	 * Commented out constants that were not available in:
	 * HipHop VM 3.3.0 (rel)
	 * Compiler: heads/master-0-g08810d920dfff59e0774cf2d651f92f13a637175
	 * Repo schema: 3214fc2c684a4520485f715ee45f33f2182324b1
	 * Extension API: 20140829
	 *
	 * Commented out constants that were removed in PHP 5.6.0
	 *
	 * @covers CurlHttpRequest::execute
	 */
	public function provideCurlConstants() {
		return array(
			array( 'CURLAUTH_ANY' ),
			array( 'CURLAUTH_ANYSAFE' ),
			array( 'CURLAUTH_BASIC' ),
			array( 'CURLAUTH_DIGEST' ),
			array( 'CURLAUTH_GSSNEGOTIATE' ),
			array( 'CURLAUTH_NTLM' ),
			// array( 'CURLCLOSEPOLICY_CALLBACK' ), // removed in PHP 5.6.0
			// array( 'CURLCLOSEPOLICY_LEAST_RECENTLY_USED' ), // removed in PHP 5.6.0
			// array( 'CURLCLOSEPOLICY_LEAST_TRAFFIC' ), // removed in PHP 5.6.0
			// array( 'CURLCLOSEPOLICY_OLDEST' ), // removed in PHP 5.6.0
			// array( 'CURLCLOSEPOLICY_SLOWEST' ), // removed in PHP 5.6.0
			array( 'CURLE_ABORTED_BY_CALLBACK' ),
			array( 'CURLE_BAD_CALLING_ORDER' ),
			array( 'CURLE_BAD_CONTENT_ENCODING' ),
			array( 'CURLE_BAD_FUNCTION_ARGUMENT' ),
			array( 'CURLE_BAD_PASSWORD_ENTERED' ),
			array( 'CURLE_COULDNT_CONNECT' ),
			array( 'CURLE_COULDNT_RESOLVE_HOST' ),
			array( 'CURLE_COULDNT_RESOLVE_PROXY' ),
			array( 'CURLE_FAILED_INIT' ),
			array( 'CURLE_FILESIZE_EXCEEDED' ),
			array( 'CURLE_FILE_COULDNT_READ_FILE' ),
			array( 'CURLE_FTP_ACCESS_DENIED' ),
			array( 'CURLE_FTP_BAD_DOWNLOAD_RESUME' ),
			array( 'CURLE_FTP_CANT_GET_HOST' ),
			array( 'CURLE_FTP_CANT_RECONNECT' ),
			array( 'CURLE_FTP_COULDNT_GET_SIZE' ),
			array( 'CURLE_FTP_COULDNT_RETR_FILE' ),
			array( 'CURLE_FTP_COULDNT_SET_ASCII' ),
			array( 'CURLE_FTP_COULDNT_SET_BINARY' ),
			array( 'CURLE_FTP_COULDNT_STOR_FILE' ),
			array( 'CURLE_FTP_COULDNT_USE_REST' ),
			array( 'CURLE_FTP_PORT_FAILED' ),
			array( 'CURLE_FTP_QUOTE_ERROR' ),
			array( 'CURLE_FTP_SSL_FAILED' ),
			array( 'CURLE_FTP_USER_PASSWORD_INCORRECT' ),
			array( 'CURLE_FTP_WEIRD_227_FORMAT' ),
			array( 'CURLE_FTP_WEIRD_PASS_REPLY' ),
			array( 'CURLE_FTP_WEIRD_PASV_REPLY' ),
			array( 'CURLE_FTP_WEIRD_SERVER_REPLY' ),
			array( 'CURLE_FTP_WEIRD_USER_REPLY' ),
			array( 'CURLE_FTP_WRITE_ERROR' ),
			array( 'CURLE_FUNCTION_NOT_FOUND' ),
			array( 'CURLE_GOT_NOTHING' ),
			array( 'CURLE_HTTP_NOT_FOUND' ),
			array( 'CURLE_HTTP_PORT_FAILED' ),
			array( 'CURLE_HTTP_POST_ERROR' ),
			array( 'CURLE_HTTP_RANGE_ERROR' ),
			array( 'CURLE_LDAP_CANNOT_BIND' ),
			array( 'CURLE_LDAP_INVALID_URL' ),
			array( 'CURLE_LDAP_SEARCH_FAILED' ),
			array( 'CURLE_LIBRARY_NOT_FOUND' ),
			array( 'CURLE_MALFORMAT_USER' ),
			array( 'CURLE_OBSOLETE' ),
			array( 'CURLE_OK' ),
			array( 'CURLE_OPERATION_TIMEOUTED' ),
			array( 'CURLE_OUT_OF_MEMORY' ),
			array( 'CURLE_PARTIAL_FILE' ),
			array( 'CURLE_READ_ERROR' ),
			array( 'CURLE_RECV_ERROR' ),
			array( 'CURLE_SEND_ERROR' ),
			array( 'CURLE_SHARE_IN_USE' ),
			// array( 'CURLE_SSH' ), // not present in HHVM 3.3.0-dev
			array( 'CURLE_SSL_CACERT' ),
			array( 'CURLE_SSL_CERTPROBLEM' ),
			array( 'CURLE_SSL_CIPHER' ),
			array( 'CURLE_SSL_CONNECT_ERROR' ),
			array( 'CURLE_SSL_ENGINE_NOTFOUND' ),
			array( 'CURLE_SSL_ENGINE_SETFAILED' ),
			array( 'CURLE_SSL_PEER_CERTIFICATE' ),
			array( 'CURLE_TELNET_OPTION_SYNTAX' ),
			array( 'CURLE_TOO_MANY_REDIRECTS' ),
			array( 'CURLE_UNKNOWN_TELNET_OPTION' ),
			array( 'CURLE_UNSUPPORTED_PROTOCOL' ),
			array( 'CURLE_URL_MALFORMAT' ),
			array( 'CURLE_URL_MALFORMAT_USER' ),
			array( 'CURLE_WRITE_ERROR' ),
			array( 'CURLFTPAUTH_DEFAULT' ),
			array( 'CURLFTPAUTH_SSL' ),
			array( 'CURLFTPAUTH_TLS' ),
			// array( 'CURLFTPMETHOD_MULTICWD' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLFTPMETHOD_NOCWD' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLFTPMETHOD_SINGLECWD' ), // not present in HHVM 3.3.0-dev
			array( 'CURLFTPSSL_ALL' ),
			array( 'CURLFTPSSL_CONTROL' ),
			array( 'CURLFTPSSL_NONE' ),
			array( 'CURLFTPSSL_TRY' ),
			// array( 'CURLINFO_CERTINFO' ), // not present in HHVM 3.3.0-dev
			array( 'CURLINFO_CONNECT_TIME' ),
			array( 'CURLINFO_CONTENT_LENGTH_DOWNLOAD' ),
			array( 'CURLINFO_CONTENT_LENGTH_UPLOAD' ),
			array( 'CURLINFO_CONTENT_TYPE' ),
			array( 'CURLINFO_EFFECTIVE_URL' ),
			array( 'CURLINFO_FILETIME' ),
			array( 'CURLINFO_HEADER_OUT' ),
			array( 'CURLINFO_HEADER_SIZE' ),
			array( 'CURLINFO_HTTP_CODE' ),
			array( 'CURLINFO_NAMELOOKUP_TIME' ),
			array( 'CURLINFO_PRETRANSFER_TIME' ),
			array( 'CURLINFO_PRIVATE' ),
			array( 'CURLINFO_REDIRECT_COUNT' ),
			array( 'CURLINFO_REDIRECT_TIME' ),
			// array( 'CURLINFO_REDIRECT_URL' ), // not present in HHVM 3.3.0-dev
			array( 'CURLINFO_REQUEST_SIZE' ),
			array( 'CURLINFO_SIZE_DOWNLOAD' ),
			array( 'CURLINFO_SIZE_UPLOAD' ),
			array( 'CURLINFO_SPEED_DOWNLOAD' ),
			array( 'CURLINFO_SPEED_UPLOAD' ),
			array( 'CURLINFO_SSL_VERIFYRESULT' ),
			array( 'CURLINFO_STARTTRANSFER_TIME' ),
			array( 'CURLINFO_TOTAL_TIME' ),
			array( 'CURLMSG_DONE' ),
			array( 'CURLM_BAD_EASY_HANDLE' ),
			array( 'CURLM_BAD_HANDLE' ),
			array( 'CURLM_CALL_MULTI_PERFORM' ),
			array( 'CURLM_INTERNAL_ERROR' ),
			array( 'CURLM_OK' ),
			array( 'CURLM_OUT_OF_MEMORY' ),
			array( 'CURLOPT_AUTOREFERER' ),
			array( 'CURLOPT_BINARYTRANSFER' ),
			array( 'CURLOPT_BUFFERSIZE' ),
			array( 'CURLOPT_CAINFO' ),
			array( 'CURLOPT_CAPATH' ),
			// array( 'CURLOPT_CERTINFO' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLOPT_CLOSEPOLICY' ), // removed in PHP 5.6.0
			array( 'CURLOPT_CONNECTTIMEOUT' ),
			array( 'CURLOPT_CONNECTTIMEOUT_MS' ),
			array( 'CURLOPT_COOKIE' ),
			array( 'CURLOPT_COOKIEFILE' ),
			array( 'CURLOPT_COOKIEJAR' ),
			array( 'CURLOPT_COOKIESESSION' ),
			array( 'CURLOPT_CRLF' ),
			array( 'CURLOPT_CUSTOMREQUEST' ),
			array( 'CURLOPT_DNS_CACHE_TIMEOUT' ),
			array( 'CURLOPT_DNS_USE_GLOBAL_CACHE' ),
			array( 'CURLOPT_EGDSOCKET' ),
			array( 'CURLOPT_ENCODING' ),
			array( 'CURLOPT_FAILONERROR' ),
			array( 'CURLOPT_FILE' ),
			array( 'CURLOPT_FILETIME' ),
			array( 'CURLOPT_FOLLOWLOCATION' ),
			array( 'CURLOPT_FORBID_REUSE' ),
			array( 'CURLOPT_FRESH_CONNECT' ),
			array( 'CURLOPT_FTPAPPEND' ),
			array( 'CURLOPT_FTPLISTONLY' ),
			array( 'CURLOPT_FTPPORT' ),
			array( 'CURLOPT_FTPSSLAUTH' ),
			array( 'CURLOPT_FTP_CREATE_MISSING_DIRS' ),
			// array( 'CURLOPT_FTP_FILEMETHOD' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLOPT_FTP_SKIP_PASV_IP' ), // not present in HHVM 3.3.0-dev
			array( 'CURLOPT_FTP_SSL' ),
			array( 'CURLOPT_FTP_USE_EPRT' ),
			array( 'CURLOPT_FTP_USE_EPSV' ),
			array( 'CURLOPT_HEADER' ),
			array( 'CURLOPT_HEADERFUNCTION' ),
			array( 'CURLOPT_HTTP200ALIASES' ),
			array( 'CURLOPT_HTTPAUTH' ),
			array( 'CURLOPT_HTTPGET' ),
			array( 'CURLOPT_HTTPHEADER' ),
			array( 'CURLOPT_HTTPPROXYTUNNEL' ),
			array( 'CURLOPT_HTTP_VERSION' ),
			array( 'CURLOPT_INFILE' ),
			array( 'CURLOPT_INFILESIZE' ),
			array( 'CURLOPT_INTERFACE' ),
			array( 'CURLOPT_IPRESOLVE' ),
			// array( 'CURLOPT_KEYPASSWD' ), // not present in HHVM 3.3.0-dev
			array( 'CURLOPT_KRB4LEVEL' ),
			array( 'CURLOPT_LOW_SPEED_LIMIT' ),
			array( 'CURLOPT_LOW_SPEED_TIME' ),
			array( 'CURLOPT_MAXCONNECTS' ),
			array( 'CURLOPT_MAXREDIRS' ),
			// array( 'CURLOPT_MAX_RECV_SPEED_LARGE' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLOPT_MAX_SEND_SPEED_LARGE' ), // not present in HHVM 3.3.0-dev
			array( 'CURLOPT_NETRC' ),
			array( 'CURLOPT_NOBODY' ),
			array( 'CURLOPT_NOPROGRESS' ),
			array( 'CURLOPT_NOSIGNAL' ),
			array( 'CURLOPT_PORT' ),
			array( 'CURLOPT_POST' ),
			array( 'CURLOPT_POSTFIELDS' ),
			array( 'CURLOPT_POSTQUOTE' ),
			array( 'CURLOPT_POSTREDIR' ),
			array( 'CURLOPT_PRIVATE' ),
			array( 'CURLOPT_PROGRESSFUNCTION' ),
			// array( 'CURLOPT_PROTOCOLS' ), // not present in HHVM 3.3.0-dev
			array( 'CURLOPT_PROXY' ),
			array( 'CURLOPT_PROXYAUTH' ),
			array( 'CURLOPT_PROXYPORT' ),
			array( 'CURLOPT_PROXYTYPE' ),
			array( 'CURLOPT_PROXYUSERPWD' ),
			array( 'CURLOPT_PUT' ),
			array( 'CURLOPT_QUOTE' ),
			array( 'CURLOPT_RANDOM_FILE' ),
			array( 'CURLOPT_RANGE' ),
			array( 'CURLOPT_READDATA' ),
			array( 'CURLOPT_READFUNCTION' ),
			// array( 'CURLOPT_REDIR_PROTOCOLS' ), // not present in HHVM 3.3.0-dev
			array( 'CURLOPT_REFERER' ),
			array( 'CURLOPT_RESUME_FROM' ),
			array( 'CURLOPT_RETURNTRANSFER' ),
			// array( 'CURLOPT_SSH_AUTH_TYPES' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLOPT_SSH_PRIVATE_KEYFILE' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLOPT_SSH_PUBLIC_KEYFILE' ), // not present in HHVM 3.3.0-dev
			array( 'CURLOPT_SSLCERT' ),
			array( 'CURLOPT_SSLCERTPASSWD' ),
			array( 'CURLOPT_SSLCERTTYPE' ),
			array( 'CURLOPT_SSLENGINE' ),
			array( 'CURLOPT_SSLENGINE_DEFAULT' ),
			array( 'CURLOPT_SSLKEY' ),
			array( 'CURLOPT_SSLKEYPASSWD' ),
			array( 'CURLOPT_SSLKEYTYPE' ),
			array( 'CURLOPT_SSLVERSION' ),
			array( 'CURLOPT_SSL_CIPHER_LIST' ),
			array( 'CURLOPT_SSL_VERIFYHOST' ),
			array( 'CURLOPT_SSL_VERIFYPEER' ),
			array( 'CURLOPT_STDERR' ),
			array( 'CURLOPT_TCP_NODELAY' ),
			array( 'CURLOPT_TIMECONDITION' ),
			array( 'CURLOPT_TIMEOUT' ),
			array( 'CURLOPT_TIMEOUT_MS' ),
			array( 'CURLOPT_TIMEVALUE' ),
			array( 'CURLOPT_TRANSFERTEXT' ),
			array( 'CURLOPT_UNRESTRICTED_AUTH' ),
			array( 'CURLOPT_UPLOAD' ),
			array( 'CURLOPT_URL' ),
			array( 'CURLOPT_USERAGENT' ),
			array( 'CURLOPT_USERPWD' ),
			array( 'CURLOPT_VERBOSE' ),
			array( 'CURLOPT_WRITEFUNCTION' ),
			array( 'CURLOPT_WRITEHEADER' ),
			// array( 'CURLPROTO_ALL' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_DICT' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_FILE' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_FTP' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_FTPS' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_HTTP' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_HTTPS' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_LDAP' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_LDAPS' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_SCP' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_SFTP' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_TELNET' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLPROTO_TFTP' ), // not present in HHVM 3.3.0-dev
			array( 'CURLPROXY_HTTP' ),
			// array( 'CURLPROXY_SOCKS4' ), // not present in HHVM 3.3.0-dev
			array( 'CURLPROXY_SOCKS5' ),
			// array( 'CURLSSH_AUTH_DEFAULT' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLSSH_AUTH_HOST' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLSSH_AUTH_KEYBOARD' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLSSH_AUTH_NONE' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLSSH_AUTH_PASSWORD' ), // not present in HHVM 3.3.0-dev
			// array( 'CURLSSH_AUTH_PUBLICKEY' ), // not present in HHVM 3.3.0-dev
			array( 'CURLVERSION_NOW' ),
			array( 'CURL_HTTP_VERSION_1_0' ),
			array( 'CURL_HTTP_VERSION_1_1' ),
			array( 'CURL_HTTP_VERSION_NONE' ),
			array( 'CURL_IPRESOLVE_V4' ),
			array( 'CURL_IPRESOLVE_V6' ),
			array( 'CURL_IPRESOLVE_WHATEVER' ),
			array( 'CURL_NETRC_IGNORED' ),
			array( 'CURL_NETRC_OPTIONAL' ),
			array( 'CURL_NETRC_REQUIRED' ),
			array( 'CURL_TIMECOND_IFMODSINCE' ),
			array( 'CURL_TIMECOND_IFUNMODSINCE' ),
			array( 'CURL_TIMECOND_LASTMOD' ),
			array( 'CURL_VERSION_IPV6' ),
			array( 'CURL_VERSION_KERBEROS4' ),
			array( 'CURL_VERSION_LIBZ' ),
			array( 'CURL_VERSION_SSL' ),
		);
	}

	/**
	 * Added this test based on an issue experienced with HHVM 3.3.0-dev
	 * where it did not define a cURL constant.
	 *
	 * @bug 70570
	 * @dataProvider provideCurlConstants
	 */
	public function testCurlConstants( $value ) {
		$this->assertTrue( defined( $value ), $value . ' not defined' );
	}
}

/**
 * Class to let us overwrite MWHttpRequest respHeaders variable
 */
class MWHttpRequestTester extends MWHttpRequest {
	// function derived from the MWHttpRequest factory function but
	// returns appropriate tester class here
	public static function factory( $url, $options = null, $caller = __METHOD__ ) {
		if ( !Http::$httpEngine ) {
			Http::$httpEngine = function_exists( 'curl_init' ) ? 'curl' : 'php';
		} elseif ( Http::$httpEngine == 'curl' && !function_exists( 'curl_init' ) ) {
			throw new MWException( __METHOD__ . ': curl (http://php.net/curl) is not installed, but' .
				'Http::$httpEngine is set to "curl"' );
		}

		switch ( Http::$httpEngine ) {
			case 'curl':
				return new CurlHttpRequestTester( $url, $options, $caller );
			case 'php':
				if ( !wfIniGetBool( 'allow_url_fopen' ) ) {
					throw new MWException( __METHOD__ .
						': allow_url_fopen needs to be enabled for pure PHP HTTP requests to work. '
							. 'If possible, curl should be used instead. See http://php.net/curl.' );
				}

				return new PhpHttpRequestTester( $url, $options, $caller );
			default:
		}
	}
}

class CurlHttpRequestTester extends CurlHttpRequest {
	function setRespHeaders( $name, $value ) {
		$this->respHeaders[$name] = $value;
	}
}

class PhpHttpRequestTester extends PhpHttpRequest {
	function setRespHeaders( $name, $value ) {
		$this->respHeaders[$name] = $value;
	}
}
