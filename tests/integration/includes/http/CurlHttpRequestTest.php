<?php

/**
 * @group large
 * @covers CurlHttpRequest
 */
class CurlHttpRequestTest extends MWHttpRequestTestCase {
	protected static $httpEngine = 'curl';

	/**
	 * Constant values are from PHP 5.3.28 using cURL 7.24.0
	 * @see https://www.php.net/manual/en/curl.constants.php
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
	 */
	public function provideCurlConstants() {
		return [
			[ 'CURLAUTH_ANY' ],
			[ 'CURLAUTH_ANYSAFE' ],
			[ 'CURLAUTH_BASIC' ],
			[ 'CURLAUTH_DIGEST' ],
			[ 'CURLAUTH_GSSNEGOTIATE' ],
			[ 'CURLAUTH_NTLM' ],
			// [ 'CURLCLOSEPOLICY_CALLBACK' ], // removed in PHP 5.6.0
			// [ 'CURLCLOSEPOLICY_LEAST_RECENTLY_USED' ], // removed in PHP 5.6.0
			// [ 'CURLCLOSEPOLICY_LEAST_TRAFFIC' ], // removed in PHP 5.6.0
			// [ 'CURLCLOSEPOLICY_OLDEST' ], // removed in PHP 5.6.0
			// [ 'CURLCLOSEPOLICY_SLOWEST' ], // removed in PHP 5.6.0
			[ 'CURLE_ABORTED_BY_CALLBACK' ],
			[ 'CURLE_BAD_CALLING_ORDER' ],
			[ 'CURLE_BAD_CONTENT_ENCODING' ],
			[ 'CURLE_BAD_FUNCTION_ARGUMENT' ],
			[ 'CURLE_BAD_PASSWORD_ENTERED' ],
			[ 'CURLE_COULDNT_CONNECT' ],
			[ 'CURLE_COULDNT_RESOLVE_HOST' ],
			[ 'CURLE_COULDNT_RESOLVE_PROXY' ],
			[ 'CURLE_FAILED_INIT' ],
			[ 'CURLE_FILESIZE_EXCEEDED' ],
			[ 'CURLE_FILE_COULDNT_READ_FILE' ],
			[ 'CURLE_FTP_ACCESS_DENIED' ],
			[ 'CURLE_FTP_BAD_DOWNLOAD_RESUME' ],
			[ 'CURLE_FTP_CANT_GET_HOST' ],
			[ 'CURLE_FTP_CANT_RECONNECT' ],
			[ 'CURLE_FTP_COULDNT_GET_SIZE' ],
			[ 'CURLE_FTP_COULDNT_RETR_FILE' ],
			[ 'CURLE_FTP_COULDNT_SET_ASCII' ],
			[ 'CURLE_FTP_COULDNT_SET_BINARY' ],
			[ 'CURLE_FTP_COULDNT_STOR_FILE' ],
			[ 'CURLE_FTP_COULDNT_USE_REST' ],
			[ 'CURLE_FTP_PORT_FAILED' ],
			[ 'CURLE_FTP_QUOTE_ERROR' ],
			[ 'CURLE_FTP_SSL_FAILED' ],
			[ 'CURLE_FTP_USER_PASSWORD_INCORRECT' ],
			[ 'CURLE_FTP_WEIRD_227_FORMAT' ],
			[ 'CURLE_FTP_WEIRD_PASS_REPLY' ],
			[ 'CURLE_FTP_WEIRD_PASV_REPLY' ],
			[ 'CURLE_FTP_WEIRD_SERVER_REPLY' ],
			[ 'CURLE_FTP_WEIRD_USER_REPLY' ],
			[ 'CURLE_FTP_WRITE_ERROR' ],
			[ 'CURLE_FUNCTION_NOT_FOUND' ],
			[ 'CURLE_GOT_NOTHING' ],
			[ 'CURLE_HTTP_NOT_FOUND' ],
			[ 'CURLE_HTTP_PORT_FAILED' ],
			[ 'CURLE_HTTP_POST_ERROR' ],
			[ 'CURLE_HTTP_RANGE_ERROR' ],
			[ 'CURLE_LDAP_CANNOT_BIND' ],
			[ 'CURLE_LDAP_INVALID_URL' ],
			[ 'CURLE_LDAP_SEARCH_FAILED' ],
			[ 'CURLE_LIBRARY_NOT_FOUND' ],
			[ 'CURLE_MALFORMAT_USER' ],
			[ 'CURLE_OBSOLETE' ],
			[ 'CURLE_OK' ],
			[ 'CURLE_OPERATION_TIMEOUTED' ],
			[ 'CURLE_OUT_OF_MEMORY' ],
			[ 'CURLE_PARTIAL_FILE' ],
			[ 'CURLE_READ_ERROR' ],
			[ 'CURLE_RECV_ERROR' ],
			[ 'CURLE_SEND_ERROR' ],
			[ 'CURLE_SHARE_IN_USE' ],
			// [ 'CURLE_SSH' ], // not present in HHVM 3.3.0-dev
			[ 'CURLE_SSL_CACERT' ],
			[ 'CURLE_SSL_CERTPROBLEM' ],
			[ 'CURLE_SSL_CIPHER' ],
			[ 'CURLE_SSL_CONNECT_ERROR' ],
			[ 'CURLE_SSL_ENGINE_NOTFOUND' ],
			[ 'CURLE_SSL_ENGINE_SETFAILED' ],
			[ 'CURLE_SSL_PEER_CERTIFICATE' ],
			[ 'CURLE_TELNET_OPTION_SYNTAX' ],
			[ 'CURLE_TOO_MANY_REDIRECTS' ],
			[ 'CURLE_UNKNOWN_TELNET_OPTION' ],
			[ 'CURLE_UNSUPPORTED_PROTOCOL' ],
			[ 'CURLE_URL_MALFORMAT' ],
			[ 'CURLE_URL_MALFORMAT_USER' ],
			[ 'CURLE_WRITE_ERROR' ],
			[ 'CURLFTPAUTH_DEFAULT' ],
			[ 'CURLFTPAUTH_SSL' ],
			[ 'CURLFTPAUTH_TLS' ],
			// [ 'CURLFTPMETHOD_MULTICWD' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLFTPMETHOD_NOCWD' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLFTPMETHOD_SINGLECWD' ], // not present in HHVM 3.3.0-dev
			[ 'CURLFTPSSL_ALL' ],
			[ 'CURLFTPSSL_CONTROL' ],
			[ 'CURLFTPSSL_NONE' ],
			[ 'CURLFTPSSL_TRY' ],
			// [ 'CURLINFO_CERTINFO' ], // not present in HHVM 3.3.0-dev
			[ 'CURLINFO_CONNECT_TIME' ],
			[ 'CURLINFO_CONTENT_LENGTH_DOWNLOAD' ],
			[ 'CURLINFO_CONTENT_LENGTH_UPLOAD' ],
			[ 'CURLINFO_CONTENT_TYPE' ],
			[ 'CURLINFO_EFFECTIVE_URL' ],
			[ 'CURLINFO_FILETIME' ],
			[ 'CURLINFO_HEADER_OUT' ],
			[ 'CURLINFO_HEADER_SIZE' ],
			[ 'CURLINFO_HTTP_CODE' ],
			[ 'CURLINFO_NAMELOOKUP_TIME' ],
			[ 'CURLINFO_PRETRANSFER_TIME' ],
			[ 'CURLINFO_PRIVATE' ],
			[ 'CURLINFO_REDIRECT_COUNT' ],
			[ 'CURLINFO_REDIRECT_TIME' ],
			// [ 'CURLINFO_REDIRECT_URL' ], // not present in HHVM 3.3.0-dev
			[ 'CURLINFO_REQUEST_SIZE' ],
			[ 'CURLINFO_SIZE_DOWNLOAD' ],
			[ 'CURLINFO_SIZE_UPLOAD' ],
			[ 'CURLINFO_SPEED_DOWNLOAD' ],
			[ 'CURLINFO_SPEED_UPLOAD' ],
			[ 'CURLINFO_SSL_VERIFYRESULT' ],
			[ 'CURLINFO_STARTTRANSFER_TIME' ],
			[ 'CURLINFO_TOTAL_TIME' ],
			[ 'CURLMSG_DONE' ],
			[ 'CURLM_BAD_EASY_HANDLE' ],
			[ 'CURLM_BAD_HANDLE' ],
			[ 'CURLM_CALL_MULTI_PERFORM' ],
			[ 'CURLM_INTERNAL_ERROR' ],
			[ 'CURLM_OK' ],
			[ 'CURLM_OUT_OF_MEMORY' ],
			[ 'CURLOPT_AUTOREFERER' ],
			[ 'CURLOPT_BINARYTRANSFER' ],
			[ 'CURLOPT_BUFFERSIZE' ],
			[ 'CURLOPT_CAINFO' ],
			[ 'CURLOPT_CAPATH' ],
			// [ 'CURLOPT_CERTINFO' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLOPT_CLOSEPOLICY' ], // removed in PHP 5.6.0
			[ 'CURLOPT_CONNECTTIMEOUT' ],
			[ 'CURLOPT_CONNECTTIMEOUT_MS' ],
			[ 'CURLOPT_COOKIE' ],
			[ 'CURLOPT_COOKIEFILE' ],
			[ 'CURLOPT_COOKIEJAR' ],
			[ 'CURLOPT_COOKIESESSION' ],
			[ 'CURLOPT_CRLF' ],
			[ 'CURLOPT_CUSTOMREQUEST' ],
			[ 'CURLOPT_DNS_CACHE_TIMEOUT' ],
			[ 'CURLOPT_DNS_USE_GLOBAL_CACHE' ],
			[ 'CURLOPT_EGDSOCKET' ],
			[ 'CURLOPT_ENCODING' ],
			[ 'CURLOPT_FAILONERROR' ],
			[ 'CURLOPT_FILE' ],
			[ 'CURLOPT_FILETIME' ],
			[ 'CURLOPT_FOLLOWLOCATION' ],
			[ 'CURLOPT_FORBID_REUSE' ],
			[ 'CURLOPT_FRESH_CONNECT' ],
			[ 'CURLOPT_FTPAPPEND' ],
			[ 'CURLOPT_FTPLISTONLY' ],
			[ 'CURLOPT_FTPPORT' ],
			[ 'CURLOPT_FTPSSLAUTH' ],
			[ 'CURLOPT_FTP_CREATE_MISSING_DIRS' ],
			// [ 'CURLOPT_FTP_FILEMETHOD' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLOPT_FTP_SKIP_PASV_IP' ], // not present in HHVM 3.3.0-dev
			[ 'CURLOPT_FTP_SSL' ],
			[ 'CURLOPT_FTP_USE_EPRT' ],
			[ 'CURLOPT_FTP_USE_EPSV' ],
			[ 'CURLOPT_HEADER' ],
			[ 'CURLOPT_HEADERFUNCTION' ],
			[ 'CURLOPT_HTTP200ALIASES' ],
			[ 'CURLOPT_HTTPAUTH' ],
			[ 'CURLOPT_HTTPGET' ],
			[ 'CURLOPT_HTTPHEADER' ],
			[ 'CURLOPT_HTTPPROXYTUNNEL' ],
			[ 'CURLOPT_HTTP_VERSION' ],
			[ 'CURLOPT_INFILE' ],
			[ 'CURLOPT_INFILESIZE' ],
			[ 'CURLOPT_INTERFACE' ],
			[ 'CURLOPT_IPRESOLVE' ],
			// [ 'CURLOPT_KEYPASSWD' ], // not present in HHVM 3.3.0-dev
			[ 'CURLOPT_KRB4LEVEL' ],
			[ 'CURLOPT_LOW_SPEED_LIMIT' ],
			[ 'CURLOPT_LOW_SPEED_TIME' ],
			[ 'CURLOPT_MAXCONNECTS' ],
			[ 'CURLOPT_MAXREDIRS' ],
			// [ 'CURLOPT_MAX_RECV_SPEED_LARGE' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLOPT_MAX_SEND_SPEED_LARGE' ], // not present in HHVM 3.3.0-dev
			[ 'CURLOPT_NETRC' ],
			[ 'CURLOPT_NOBODY' ],
			[ 'CURLOPT_NOPROGRESS' ],
			[ 'CURLOPT_NOSIGNAL' ],
			[ 'CURLOPT_PORT' ],
			[ 'CURLOPT_POST' ],
			[ 'CURLOPT_POSTFIELDS' ],
			[ 'CURLOPT_POSTQUOTE' ],
			[ 'CURLOPT_POSTREDIR' ],
			[ 'CURLOPT_PRIVATE' ],
			[ 'CURLOPT_PROGRESSFUNCTION' ],
			// [ 'CURLOPT_PROTOCOLS' ], // not present in HHVM 3.3.0-dev
			[ 'CURLOPT_PROXY' ],
			[ 'CURLOPT_PROXYAUTH' ],
			[ 'CURLOPT_PROXYPORT' ],
			[ 'CURLOPT_PROXYTYPE' ],
			[ 'CURLOPT_PROXYUSERPWD' ],
			[ 'CURLOPT_PUT' ],
			[ 'CURLOPT_QUOTE' ],
			[ 'CURLOPT_RANDOM_FILE' ],
			[ 'CURLOPT_RANGE' ],
			[ 'CURLOPT_READDATA' ],
			[ 'CURLOPT_READFUNCTION' ],
			// [ 'CURLOPT_REDIR_PROTOCOLS' ], // not present in HHVM 3.3.0-dev
			[ 'CURLOPT_REFERER' ],
			[ 'CURLOPT_RESUME_FROM' ],
			[ 'CURLOPT_RETURNTRANSFER' ],
			// [ 'CURLOPT_SSH_AUTH_TYPES' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLOPT_SSH_PRIVATE_KEYFILE' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLOPT_SSH_PUBLIC_KEYFILE' ], // not present in HHVM 3.3.0-dev
			[ 'CURLOPT_SSLCERT' ],
			[ 'CURLOPT_SSLCERTPASSWD' ],
			[ 'CURLOPT_SSLCERTTYPE' ],
			[ 'CURLOPT_SSLENGINE' ],
			[ 'CURLOPT_SSLENGINE_DEFAULT' ],
			[ 'CURLOPT_SSLKEY' ],
			[ 'CURLOPT_SSLKEYPASSWD' ],
			[ 'CURLOPT_SSLKEYTYPE' ],
			[ 'CURLOPT_SSLVERSION' ],
			[ 'CURLOPT_SSL_CIPHER_LIST' ],
			[ 'CURLOPT_SSL_VERIFYHOST' ],
			[ 'CURLOPT_SSL_VERIFYPEER' ],
			[ 'CURLOPT_STDERR' ],
			[ 'CURLOPT_TCP_NODELAY' ],
			[ 'CURLOPT_TIMECONDITION' ],
			[ 'CURLOPT_TIMEOUT' ],
			[ 'CURLOPT_TIMEOUT_MS' ],
			[ 'CURLOPT_TIMEVALUE' ],
			[ 'CURLOPT_TRANSFERTEXT' ],
			[ 'CURLOPT_UNRESTRICTED_AUTH' ],
			[ 'CURLOPT_UPLOAD' ],
			[ 'CURLOPT_URL' ],
			[ 'CURLOPT_USERAGENT' ],
			[ 'CURLOPT_USERPWD' ],
			[ 'CURLOPT_VERBOSE' ],
			[ 'CURLOPT_WRITEFUNCTION' ],
			[ 'CURLOPT_WRITEHEADER' ],
			// [ 'CURLPROTO_ALL' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_DICT' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_FILE' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_FTP' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_FTPS' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_HTTP' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_HTTPS' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_LDAP' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_LDAPS' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_SCP' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_SFTP' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_TELNET' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLPROTO_TFTP' ], // not present in HHVM 3.3.0-dev
			[ 'CURLPROXY_HTTP' ],
			// [ 'CURLPROXY_SOCKS4' ], // not present in HHVM 3.3.0-dev
			[ 'CURLPROXY_SOCKS5' ],
			// [ 'CURLSSH_AUTH_DEFAULT' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLSSH_AUTH_HOST' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLSSH_AUTH_KEYBOARD' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLSSH_AUTH_NONE' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLSSH_AUTH_PASSWORD' ], // not present in HHVM 3.3.0-dev
			// [ 'CURLSSH_AUTH_PUBLICKEY' ], // not present in HHVM 3.3.0-dev
			[ 'CURLVERSION_NOW' ],
			[ 'CURL_HTTP_VERSION_1_0' ],
			[ 'CURL_HTTP_VERSION_1_1' ],
			[ 'CURL_HTTP_VERSION_NONE' ],
			[ 'CURL_IPRESOLVE_V4' ],
			[ 'CURL_IPRESOLVE_V6' ],
			[ 'CURL_IPRESOLVE_WHATEVER' ],
			[ 'CURL_NETRC_IGNORED' ],
			[ 'CURL_NETRC_OPTIONAL' ],
			[ 'CURL_NETRC_REQUIRED' ],
			[ 'CURL_TIMECOND_IFMODSINCE' ],
			[ 'CURL_TIMECOND_IFUNMODSINCE' ],
			[ 'CURL_TIMECOND_LASTMOD' ],
			[ 'CURL_VERSION_IPV6' ],
			[ 'CURL_VERSION_KERBEROS4' ],
			[ 'CURL_VERSION_LIBZ' ],
			[ 'CURL_VERSION_SSL' ],
		];
	}

	/**
	 * Added this test based on an issue experienced with HHVM 3.3.0-dev
	 * where it did not define a cURL constant. T72570
	 *
	 * @dataProvider provideCurlConstants
	 * @coversNothing
	 */
	public function testCurlConstants( $value ) {
		$loaded = extension_loaded( 'curl' );
		if ( !$loaded ) {
			$this->markTestSkipped( "PHP extension 'curl' is not loaded, skipping." );
		}

		$this->assertTrue( defined( $value ), "Is $value defined?" );
	}
}
