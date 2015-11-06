<?php
/**
 * Various HTTP related functions.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup HTTP
 */

/**
 * @defgroup HTTP HTTP
 */

use MediaWiki\Logger\LoggerFactory;

/**
 * Various HTTP related functions
 * @ingroup HTTP
 */
class Http {
	static public $httpEngine = false;

	/**
	 * Perform an HTTP request
	 *
	 * @param string $method HTTP method. Usually GET/POST
	 * @param string $url Full URL to act on. If protocol-relative, will be expanded to an http:// URL
	 * @param array $options Options to pass to MWHttpRequest object.
	 *	Possible keys for the array:
	 *    - timeout             Timeout length in seconds
	 *    - connectTimeout      Timeout for connection, in seconds (curl only)
	 *    - postData            An array of key-value pairs or a url-encoded form data
	 *    - proxy               The proxy to use.
	 *                          Otherwise it will use $wgHTTPProxy (if set)
	 *                          Otherwise it will use the environment variable "http_proxy" (if set)
	 *    - noProxy             Don't use any proxy at all. Takes precedence over proxy value(s).
	 *    - sslVerifyHost       Verify hostname against certificate
	 *    - sslVerifyCert       Verify SSL certificate
	 *    - caInfo              Provide CA information
	 *    - maxRedirects        Maximum number of redirects to follow (defaults to 5)
	 *    - followRedirects     Whether to follow redirects (defaults to false).
	 *		                    Note: this should only be used when the target URL is trusted,
	 *		                    to avoid attacks on intranet services accessible by HTTP.
	 *    - userAgent           A user agent, if you want to override the default
	 *                          MediaWiki/$wgVersion
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool (bool)false on failure or a string on success
	 */
	public static function request( $method, $url, $options = array(), $caller = __METHOD__ ) {
		wfDebug( "HTTP: $method: $url\n" );

		$options['method'] = strtoupper( $method );

		if ( !isset( $options['timeout'] ) ) {
			$options['timeout'] = 'default';
		}
		if ( !isset( $options['connectTimeout'] ) ) {
			$options['connectTimeout'] = 'default';
		}

		$req = MWHttpRequest::factory( $url, $options, $caller );
		$status = $req->execute();

		if ( $status->isOK() ) {
			return $req->getContent();
		} else {
			$errors = $status->getErrorsByType( 'error' );
			$logger = LoggerFactory::getInstance( 'http' );
			$logger->warning( $status->getWikiText(), array( 'caller' => $caller ) );
			return false;
		}
	}

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 * @see Http::request()
	 * @since 1.25 Second parameter $timeout removed. Second parameter
	 * is now $options which can be given a 'timeout'
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller The method making this request, for profiling
	 * @return string
	 */
	public static function get( $url, $options = array(), $caller = __METHOD__ ) {
		$args = func_get_args();
		if ( isset( $args[1] ) && ( is_string( $args[1] ) || is_numeric( $args[1] ) ) ) {
			// Second was used to be the timeout
			// And third parameter used to be $options
			wfWarn( "Second parameter should not be a timeout.", 2 );
			$options = isset( $args[2] ) && is_array( $args[2] ) ?
				$args[2] : array();
			$options['timeout'] = $args[1];
			$caller = __METHOD__;
		}
		return Http::request( 'GET', $url, $options, $caller );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 * @see Http::request()
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller The method making this request, for profiling
	 * @return string
	 */
	public static function post( $url, $options = array(), $caller = __METHOD__ ) {
		return Http::request( 'POST', $url, $options, $caller );
	}

	/**
	 * Check if the URL can be served by localhost
	 *
	 * @param string $url Full url to check
	 * @return bool
	 */
	public static function isLocalURL( $url ) {
		global $wgCommandLineMode, $wgLocalVirtualHosts, $wgConf;

		if ( $wgCommandLineMode ) {
			return false;
		}

		// Extract host part
		$matches = array();
		if ( preg_match( '!^http://([\w.-]+)[/:].*$!', $url, $matches ) ) {
			$host = $matches[1];
			// Split up dotwise
			$domainParts = explode( '.', $host );
			// Check if this domain or any superdomain is listed as a local virtual host
			$domainParts = array_reverse( $domainParts );

			$domain = '';
			$countParts = count( $domainParts );
			for ( $i = 0; $i < $countParts; $i++ ) {
				$domainPart = $domainParts[$i];
				if ( $i == 0 ) {
					$domain = $domainPart;
				} else {
					$domain = $domainPart . '.' . $domain;
				}

				if ( in_array( $domain, $wgLocalVirtualHosts )
					|| $wgConf->isLocalVHost( $domain )
				) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * A standard user-agent we can use for external requests.
	 * @return string
	 */
	public static function userAgent() {
		global $wgVersion;
		return "MediaWiki/$wgVersion";
	}

	/**
	 * Checks that the given URI is a valid one. Hardcoding the
	 * protocols, because we only want protocols that both cURL
	 * and php support.
	 *
	 * file:// should not be allowed here for security purpose (r67684)
	 *
	 * @todo FIXME this is wildly inaccurate and fails to actually check most stuff
	 *
	 * @param string $uri URI to check for validity
	 * @return bool
	 */
	public static function isValidURI( $uri ) {
		return preg_match(
			'/^https?:\/\/[^\/\s]\S*$/D',
			$uri
		);
	}
}

/**
 * This wrapper class will call out to curl (if available) or fallback
 * to regular PHP if necessary for handling internal HTTP requests.
 *
 * Renamed from HttpRequest to MWHttpRequest to avoid conflict with
 * PHP's HTTP extension.
 */
class MWHttpRequest {
	const SUPPORTS_FILE_POSTS = false;

	protected $content;
	protected $timeout = 'default';
	protected $headersOnly = null;
	protected $postData = null;
	protected $proxy = null;
	protected $noProxy = false;
	protected $sslVerifyHost = true;
	protected $sslVerifyCert = true;
	protected $caInfo = null;
	protected $method = "GET";
	protected $reqHeaders = array();
	protected $url;
	protected $parsedUrl;
	protected $callback;
	protected $maxRedirects = 5;
	protected $followRedirects = false;

	/**
	 * @var CookieJar
	 */
	protected $cookieJar;

	protected $headerList = array();
	protected $respVersion = "0.9";
	protected $respStatus = "200 Ok";
	protected $respHeaders = array();

	public $status;

	/**
	 * @var Profiler
	 */
	protected $profiler;

	/**
	 * @var string
	 */
	protected $profileName;

	/**
	 * @param string $url Url to use. If protocol-relative, will be expanded to an http:// URL
	 * @param array $options (optional) extra params to pass (see Http::request())
	 * @param string $caller The method making this request, for profiling
	 * @param Profiler $profiler An instance of the profiler for profiling, or null
	 */
	protected function __construct( $url, $options = array(), $caller = __METHOD__, $profiler = null ) {
		global $wgHTTPTimeout, $wgHTTPConnectTimeout;

		$this->url = wfExpandUrl( $url, PROTO_HTTP );
		$this->parsedUrl = wfParseUrl( $this->url );

		if ( !$this->parsedUrl || !Http::isValidURI( $this->url ) ) {
			$this->status = Status::newFatal( 'http-invalid-url', $url );
		} else {
			$this->status = Status::newGood( 100 ); // continue
		}

		if ( isset( $options['timeout'] ) && $options['timeout'] != 'default' ) {
			$this->timeout = $options['timeout'];
		} else {
			$this->timeout = $wgHTTPTimeout;
		}
		if ( isset( $options['connectTimeout'] ) && $options['connectTimeout'] != 'default' ) {
			$this->connectTimeout = $options['connectTimeout'];
		} else {
			$this->connectTimeout = $wgHTTPConnectTimeout;
		}
		if ( isset( $options['userAgent'] ) ) {
			$this->setUserAgent( $options['userAgent'] );
		}

		$members = array( "postData", "proxy", "noProxy", "sslVerifyHost", "caInfo",
				"method", "followRedirects", "maxRedirects", "sslVerifyCert", "callback" );

		foreach ( $members as $o ) {
			if ( isset( $options[$o] ) ) {
				// ensure that MWHttpRequest::method is always
				// uppercased. Bug 36137
				if ( $o == 'method' ) {
					$options[$o] = strtoupper( $options[$o] );
				}
				$this->$o = $options[$o];
			}
		}

		if ( $this->noProxy ) {
			$this->proxy = ''; // noProxy takes precedence
		}

		// Profile based on what's calling us
		$this->profiler = $profiler;
		$this->profileName = $caller;
	}

	/**
	 * Simple function to test if we can make any sort of requests at all, using
	 * cURL or fopen()
	 * @return bool
	 */
	public static function canMakeRequests() {
		return function_exists( 'curl_init' ) || wfIniGetBool( 'allow_url_fopen' );
	}

	/**
	 * Generate a new request object
	 * @param string $url Url to use
	 * @param array $options (optional) extra params to pass (see Http::request())
	 * @param string $caller The method making this request, for profiling
	 * @throws MWException
	 * @return CurlHttpRequest|PhpHttpRequest
	 * @see MWHttpRequest::__construct
	 */
	public static function factory( $url, $options = null, $caller = __METHOD__ ) {
		if ( !Http::$httpEngine ) {
			Http::$httpEngine = function_exists( 'curl_init' ) ? 'curl' : 'php';
		} elseif ( Http::$httpEngine == 'curl' && !function_exists( 'curl_init' ) ) {
			throw new MWException( __METHOD__ . ': curl (http://php.net/curl) is not installed, but' .
				' Http::$httpEngine is set to "curl"' );
		}

		switch ( Http::$httpEngine ) {
			case 'curl':
				return new CurlHttpRequest( $url, $options, $caller, Profiler::instance() );
			case 'php':
				if ( !wfIniGetBool( 'allow_url_fopen' ) ) {
					throw new MWException( __METHOD__ . ': allow_url_fopen ' .
						'needs to be enabled for pure PHP http requests to ' .
						'work. If possible, curl should be used instead. See ' .
						'http://php.net/curl.'
					);
				}
				return new PhpHttpRequest( $url, $options, $caller, Profiler::instance() );
			default:
				throw new MWException( __METHOD__ . ': The setting of Http::$httpEngine is not valid.' );
		}
	}

	/**
	 * Get the body, or content, of the response to the request
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set the parameters of the request
	 *
	 * @param array $args
	 * @todo overload the args param
	 */
	public function setData( $args ) {
		$this->postData = $args;
	}

	/**
	 * Take care of setting up the proxy (do nothing if "noProxy" is set)
	 *
	 * @return void
	 */
	public function proxySetup() {
		global $wgHTTPProxy;

		// If there is an explicit proxy set and proxies are not disabled, then use it
		if ( $this->proxy && !$this->noProxy ) {
			return;
		}

		// Otherwise, fallback to $wgHTTPProxy/http_proxy (when set) if this is not a machine
		// local URL and proxies are not disabled
		if ( Http::isLocalURL( $this->url ) || $this->noProxy ) {
			$this->proxy = '';
		} elseif ( $wgHTTPProxy ) {
			$this->proxy = $wgHTTPProxy;
		} elseif ( getenv( "http_proxy" ) ) {
			$this->proxy = getenv( "http_proxy" );
		}
	}

	/**
	 * Set the user agent
	 * @param string $UA
	 */
	public function setUserAgent( $UA ) {
		$this->setHeader( 'User-Agent', $UA );
	}

	/**
	 * Set an arbitrary header
	 * @param string $name
	 * @param string $value
	 */
	public function setHeader( $name, $value ) {
		// I feel like I should normalize the case here...
		$this->reqHeaders[$name] = $value;
	}

	/**
	 * Get an array of the headers
	 * @return array
	 */
	public function getHeaderList() {
		$list = array();

		if ( $this->cookieJar ) {
			$this->reqHeaders['Cookie'] =
				$this->cookieJar->serializeToHttpRequest(
					$this->parsedUrl['path'],
					$this->parsedUrl['host']
				);
		}

		foreach ( $this->reqHeaders as $name => $value ) {
			$list[] = "$name: $value";
		}

		return $list;
	}

	/**
	 * Set a read callback to accept data read from the HTTP request.
	 * By default, data is appended to an internal buffer which can be
	 * retrieved through $req->getContent().
	 *
	 * To handle data as it comes in -- especially for large files that
	 * would not fit in memory -- you can instead set your own callback,
	 * in the form function($resource, $buffer) where the first parameter
	 * is the low-level resource being read (implementation specific),
	 * and the second parameter is the data buffer.
	 *
	 * You MUST return the number of bytes handled in the buffer; if fewer
	 * bytes are reported handled than were passed to you, the HTTP fetch
	 * will be aborted.
	 *
	 * @param callable $callback
	 * @throws MWException
	 */
	public function setCallback( $callback ) {
		if ( !is_callable( $callback ) ) {
			throw new MWException( 'Invalid MwHttpRequest callback' );
		}
		$this->callback = $callback;
	}

	/**
	 * A generic callback to read the body of the response from a remote
	 * server.
	 *
	 * @param resource $fh
	 * @param string $content
	 * @return int
	 */
	public function read( $fh, $content ) {
		$this->content .= $content;
		return strlen( $content );
	}

	/**
	 * Take care of whatever is necessary to perform the URI request.
	 *
	 * @return Status
	 */
	public function execute() {

		$this->content = "";

		if ( strtoupper( $this->method ) == "HEAD" ) {
			$this->headersOnly = true;
		}

		$this->proxySetup(); // set up any proxy as needed

		if ( !$this->callback ) {
			$this->setCallback( array( $this, 'read' ) );
		}

		if ( !isset( $this->reqHeaders['User-Agent'] ) ) {
			$this->setUserAgent( Http::userAgent() );
		}

	}

	/**
	 * Parses the headers, including the HTTP status code and any
	 * Set-Cookie headers.  This function expects the headers to be
	 * found in an array in the member variable headerList.
	 */
	protected function parseHeader() {

		$lastname = "";

		foreach ( $this->headerList as $header ) {
			if ( preg_match( "#^HTTP/([0-9.]+) (.*)#", $header, $match ) ) {
				$this->respVersion = $match[1];
				$this->respStatus = $match[2];
			} elseif ( preg_match( "#^[ \t]#", $header ) ) {
				$last = count( $this->respHeaders[$lastname] ) - 1;
				$this->respHeaders[$lastname][$last] .= "\r\n$header";
			} elseif ( preg_match( "#^([^:]*):[\t ]*(.*)#", $header, $match ) ) {
				$this->respHeaders[strtolower( $match[1] )][] = $match[2];
				$lastname = strtolower( $match[1] );
			}
		}

		$this->parseCookies();

	}

	/**
	 * Sets HTTPRequest status member to a fatal value with the error
	 * message if the returned integer value of the status code was
	 * not successful (< 300) or a redirect (>=300 and < 400).  (see
	 * RFC2616, section 10,
	 * http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html for a
	 * list of status codes.)
	 */
	protected function setStatus() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		if ( (int)$this->respStatus > 399 ) {
			list( $code, $message ) = explode( " ", $this->respStatus, 2 );
			$this->status->fatal( "http-bad-status", $code, $message );
		}
	}

	/**
	 * Get the integer value of the HTTP status code (e.g. 200 for "200 Ok")
	 * (see RFC2616, section 10, http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 * for a list of status codes.)
	 *
	 * @return int
	 */
	public function getStatus() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return (int)$this->respStatus;
	}

	/**
	 * Returns true if the last status code was a redirect.
	 *
	 * @return bool
	 */
	public function isRedirect() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		$status = (int)$this->respStatus;

		if ( $status >= 300 && $status <= 303 ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns an associative array of response headers after the
	 * request has been executed.  Because some headers
	 * (e.g. Set-Cookie) can appear more than once the, each value of
	 * the associative array is an array of the values given.
	 *
	 * @return array
	 */
	public function getResponseHeaders() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return $this->respHeaders;
	}

	/**
	 * Returns the value of the given response header.
	 *
	 * @param string $header
	 * @return string
	 */
	public function getResponseHeader( $header ) {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		if ( isset( $this->respHeaders[strtolower( $header )] ) ) {
			$v = $this->respHeaders[strtolower( $header )];
			return $v[count( $v ) - 1];
		}

		return null;
	}

	/**
	 * Tells the MWHttpRequest object to use this pre-loaded CookieJar.
	 *
	 * @param CookieJar $jar
	 */
	public function setCookieJar( $jar ) {
		$this->cookieJar = $jar;
	}

	/**
	 * Returns the cookie jar in use.
	 *
	 * @return CookieJar
	 */
	public function getCookieJar() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return $this->cookieJar;
	}

	/**
	 * Sets a cookie. Used before a request to set up any individual
	 * cookies. Used internally after a request to parse the
	 * Set-Cookie headers.
	 * @see Cookie::set
	 * @param string $name
	 * @param mixed $value
	 * @param array $attr
	 */
	public function setCookie( $name, $value = null, $attr = null ) {
		if ( !$this->cookieJar ) {
			$this->cookieJar = new CookieJar;
		}

		$this->cookieJar->setCookie( $name, $value, $attr );
	}

	/**
	 * Parse the cookies in the response headers and store them in the cookie jar.
	 */
	protected function parseCookies() {

		if ( !$this->cookieJar ) {
			$this->cookieJar = new CookieJar;
		}

		if ( isset( $this->respHeaders['set-cookie'] ) ) {
			$url = parse_url( $this->getFinalUrl() );
			foreach ( $this->respHeaders['set-cookie'] as $cookie ) {
				$this->cookieJar->parseCookieResponseHeader( $cookie, $url['host'] );
			}
		}

	}

	/**
	 * Returns the final URL after all redirections.
	 *
	 * Relative values of the "Location" header are incorrect as
	 * stated in RFC, however they do happen and modern browsers
	 * support them.  This function loops backwards through all
	 * locations in order to build the proper absolute URI - Marooned
	 * at wikia-inc.com
	 *
	 * Note that the multiple Location: headers are an artifact of
	 * CURL -- they shouldn't actually get returned this way. Rewrite
	 * this when bug 29232 is taken care of (high-level redirect
	 * handling rewrite).
	 *
	 * @return string
	 */
	public function getFinalUrl() {
		$headers = $this->getResponseHeaders();

		//return full url (fix for incorrect but handled relative location)
		if ( isset( $headers['location'] ) ) {
			$locations = $headers['location'];
			$domain = '';
			$foundRelativeURI = false;
			$countLocations = count( $locations );

			for ( $i = $countLocations - 1; $i >= 0; $i-- ) {
				$url = parse_url( $locations[$i] );

				if ( isset( $url['host'] ) ) {
					$domain = $url['scheme'] . '://' . $url['host'];
					break; //found correct URI (with host)
				} else {
					$foundRelativeURI = true;
				}
			}

			if ( $foundRelativeURI ) {
				if ( $domain ) {
					return $domain . $locations[$countLocations - 1];
				} else {
					$url = parse_url( $this->url );
					if ( isset( $url['host'] ) ) {
						return $url['scheme'] . '://' . $url['host'] .
							$locations[$countLocations - 1];
					}
				}
			} else {
				return $locations[$countLocations - 1];
			}
		}

		return $this->url;
	}

	/**
	 * Returns true if the backend can follow redirects. Overridden by the
	 * child classes.
	 * @return bool
	 */
	public function canFollowRedirects() {
		return true;
	}
}

/**
 * MWHttpRequest implemented using internal curl compiled into PHP
 */
class CurlHttpRequest extends MWHttpRequest {
	const SUPPORTS_FILE_POSTS = true;

	protected $curlOptions = array();
	protected $headerText = "";

	/**
	 * @param resource $fh
	 * @param string $content
	 * @return int
	 */
	protected function readHeader( $fh, $content ) {
		$this->headerText .= $content;
		return strlen( $content );
	}

	public function execute() {

		parent::execute();

		if ( !$this->status->isOK() ) {
			return $this->status;
		}

		$this->curlOptions[CURLOPT_PROXY] = $this->proxy;
		$this->curlOptions[CURLOPT_TIMEOUT] = $this->timeout;

		// Only supported in curl >= 7.16.2
		if ( defined( 'CURLOPT_CONNECTTIMEOUT_MS' ) ) {
			$this->curlOptions[CURLOPT_CONNECTTIMEOUT_MS] = $this->connectTimeout * 1000;
		}

		$this->curlOptions[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
		$this->curlOptions[CURLOPT_WRITEFUNCTION] = $this->callback;
		$this->curlOptions[CURLOPT_HEADERFUNCTION] = array( $this, "readHeader" );
		$this->curlOptions[CURLOPT_MAXREDIRS] = $this->maxRedirects;
		$this->curlOptions[CURLOPT_ENCODING] = ""; # Enable compression

		$this->curlOptions[CURLOPT_USERAGENT] = $this->reqHeaders['User-Agent'];

		$this->curlOptions[CURLOPT_SSL_VERIFYHOST] = $this->sslVerifyHost ? 2 : 0;
		$this->curlOptions[CURLOPT_SSL_VERIFYPEER] = $this->sslVerifyCert;

		if ( $this->caInfo ) {
			$this->curlOptions[CURLOPT_CAINFO] = $this->caInfo;
		}

		if ( $this->headersOnly ) {
			$this->curlOptions[CURLOPT_NOBODY] = true;
			$this->curlOptions[CURLOPT_HEADER] = true;
		} elseif ( $this->method == 'POST' ) {
			$this->curlOptions[CURLOPT_POST] = true;
			$postData = $this->postData;
			// Don't interpret POST parameters starting with '@' as file uploads, because this
			// makes it impossible to POST plain values starting with '@' (and causes security
			// issues potentially exposing the contents of local files).
			// The PHP manual says this option was introduced in PHP 5.5 defaults to true in PHP 5.6,
			// but we support lower versions, and the option doesn't exist in HHVM 5.6.99.
			if ( defined( 'CURLOPT_SAFE_UPLOAD' ) ) {
				$this->curlOptions[CURLOPT_SAFE_UPLOAD] = true;
			} else if ( is_array( $postData ) ) {
				// In PHP 5.2 and later, '@' is interpreted as a file upload if POSTFIELDS
				// is an array, but not if it's a string. So convert $req['body'] to a string
				// for safety.
				$postData = wfArrayToCgi( $postData );
			}
			$this->curlOptions[CURLOPT_POSTFIELDS] = $postData;

			// Suppress 'Expect: 100-continue' header, as some servers
			// will reject it with a 417 and Curl won't auto retry
			// with HTTP 1.0 fallback
			$this->reqHeaders['Expect'] = '';
		} else {
			$this->curlOptions[CURLOPT_CUSTOMREQUEST] = $this->method;
		}

		$this->curlOptions[CURLOPT_HTTPHEADER] = $this->getHeaderList();

		$curlHandle = curl_init( $this->url );

		if ( !curl_setopt_array( $curlHandle, $this->curlOptions ) ) {
			throw new MWException( "Error setting curl options." );
		}

		if ( $this->followRedirects && $this->canFollowRedirects() ) {
			MediaWiki\suppressWarnings();
			if ( !curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, true ) ) {
				wfDebug( __METHOD__ . ": Couldn't set CURLOPT_FOLLOWLOCATION. " .
					"Probably safe_mode or open_basedir is set.\n" );
				// Continue the processing. If it were in curl_setopt_array,
				// processing would have halted on its entry
			}
			MediaWiki\restoreWarnings();
		}

		if ( $this->profiler ) {
			$profileSection = $this->profiler->scopedProfileIn(
				__METHOD__ . '-' . $this->profileName
			);
		}

		$curlRes = curl_exec( $curlHandle );
		if ( curl_errno( $curlHandle ) == CURLE_OPERATION_TIMEOUTED ) {
			$this->status->fatal( 'http-timed-out', $this->url );
		} elseif ( $curlRes === false ) {
			$this->status->fatal( 'http-curl-error', curl_error( $curlHandle ) );
		} else {
			$this->headerList = explode( "\r\n", $this->headerText );
		}

		curl_close( $curlHandle );

		if ( $this->profiler ) {
			$this->profiler->scopedProfileOut( $profileSection );
		}

		$this->parseHeader();
		$this->setStatus();

		return $this->status;
	}

	/**
	 * @return bool
	 */
	public function canFollowRedirects() {
		if ( strval( ini_get( 'open_basedir' ) ) !== '' || wfIniGetBool( 'safe_mode' ) ) {
			wfDebug( "Cannot follow redirects in safe mode\n" );
			return false;
		}

		$curlVersionInfo = curl_version();
		if ( $curlVersionInfo['version_number'] < 0x071304 ) {
			wfDebug( "Cannot follow redirects with libcurl < 7.19.4 due to CVE-2009-0037\n" );
			return false;
		}

		return true;
	}
}

class PhpHttpRequest extends MWHttpRequest {

	private $fopenErrors = array();

	/**
	 * @param string $url
	 * @return string
	 */
	protected function urlToTcp( $url ) {
		$parsedUrl = parse_url( $url );

		return 'tcp://' . $parsedUrl['host'] . ':' . $parsedUrl['port'];
	}

	/**
	 * Returns an array with a 'capath' or 'cafile' key that is suitable to be merged into the 'ssl' sub-array of a
	 * stream context options array. Uses the 'caInfo' option of the class if it is provided, otherwise uses the system
	 * default CA bundle if PHP supports that, or searches a few standard locations.
	 * @return array
	 * @throws DomainException
	 */
	protected function getCertOptions() {
		$certOptions = array();
		$certLocations = array();
		if ( $this->caInfo ) {
			$certLocations = array( 'manual' => $this->caInfo );
		} elseif ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
			// Default locations, based on
			// https://www.happyassassin.net/2015/01/12/a-note-about-ssltls-trusted-certificate-stores-and-platforms/
			// PHP 5.5 and older doesn't have any defaults, so we try to guess ourselves. PHP 5.6+ gets the CA location
			// from OpenSSL as long as it is not set manually, so we should leave capath/cafile empty there.
			$certLocations = array_filter( array(
				getenv( 'SSL_CERT_DIR' ),
				getenv( 'SSL_CERT_PATH' ),
				'/etc/pki/tls/certs/ca-bundle.crt', # Fedora et al
				'/etc/ssl/certs',  # Debian et al
				'/etc/pki/tls/certs/ca-bundle.trust.crt',
				'/etc/pki/ca-trust/extracted/pem/tls-ca-bundle.pem',
				'/System/Library/OpenSSL', # OSX
			) );
		}

		foreach( $certLocations as $key => $cert ) {
			if ( is_dir( $cert ) ) {
				$certOptions['capath'] = $cert;
				break;
			} elseif ( is_file( $cert ) ) {
				$certOptions['cafile'] = $cert;
				break;
			} elseif ( $key === 'manual' ) {
				// fail more loudly if a cert path was manually configured and it is not valid
				throw new DomainException( "Invalid CA info passed: $cert" );
			}
		}

		return $certOptions;
	}

	/**
	 * Custom error handler for dealing with fopen() errors. fopen() tends to fire multiple errors in succession, and the last one
	 * is completely useless (something like "fopen: failed to open stream") so normal methods of handling errors programmatically
	 * like get_last_error() don't work.
	 */
	public function errorHandler( $errno, $errstr ) {
		$n = count( $this->fopenErrors ) + 1;
		$this->fopenErrors += array( "errno$n" => $errno, "errstr$n" => $errstr );
	}

	public function execute() {

		parent::execute();

		if ( is_array( $this->postData ) ) {
			$this->postData = wfArrayToCgi( $this->postData );
		}

		if ( $this->parsedUrl['scheme'] != 'http'
			&& $this->parsedUrl['scheme'] != 'https' ) {
			$this->status->fatal( 'http-invalid-scheme', $this->parsedUrl['scheme'] );
		}

		$this->reqHeaders['Accept'] = "*/*";
		$this->reqHeaders['Connection'] = 'Close';
		if ( $this->method == 'POST' ) {
			// Required for HTTP 1.0 POSTs
			$this->reqHeaders['Content-Length'] = strlen( $this->postData );
			if ( !isset( $this->reqHeaders['Content-Type'] ) ) {
				$this->reqHeaders['Content-Type'] = "application/x-www-form-urlencoded";
			}
		}

		// Set up PHP stream context
		$options = array(
			'http' => array(
				'method' => $this->method,
				'header' => implode( "\r\n", $this->getHeaderList() ),
				'protocol_version' => '1.1',
				'max_redirects' => $this->followRedirects ? $this->maxRedirects : 0,
				'ignore_errors' => true,
				'timeout' => $this->timeout,
				// Curl options in case curlwrappers are installed
				'curl_verify_ssl_host' => $this->sslVerifyHost ? 2 : 0,
				'curl_verify_ssl_peer' => $this->sslVerifyCert,
			),
			'ssl' => array(
				'verify_peer' => $this->sslVerifyCert,
				'SNI_enabled' => true,
			),
		);

		if ( $this->proxy ) {
			$options['http']['proxy'] = $this->urlToTCP( $this->proxy );
			$options['http']['request_fulluri'] = true;
		}

		if ( $this->postData ) {
			$options['http']['content'] = $this->postData;
		}

		if ( $this->sslVerifyHost ) {
			// PHP 5.6.0 deprecates CN_match, in favour of peer_name which
			// actually checks SubjectAltName properly.
			if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
				$options['ssl']['peer_name'] = $this->parsedUrl['host'];
			} else {
				$options['ssl']['CN_match'] = $this->parsedUrl['host'];
			}
		}

		$options['ssl'] += $this->getCertOptions();

		$context = stream_context_create( $options );

		$this->headerList = array();
		$reqCount = 0;
		$url = $this->url;

		$result = array();

		if ( $this->profiler ) {
			$profileSection = $this->profiler->scopedProfileIn(
				__METHOD__ . '-' . $this->profileName
			);
		}
		do {
			$reqCount++;
			$this->fopenErrors = array();
			set_error_handler( array( $this, 'errorHandler' ) );
			$fh = fopen( $url, "r", false, $context );
			restore_error_handler();

			if ( !$fh ) {
				// HACK for instant commons.
				// If we are contacting (commons|upload).wikimedia.org
				// try again with CN_match for en.wikipedia.org
				// as php does not handle SubjectAltName properly
				// prior to "peer_name" option in php 5.6
				if ( isset( $options['ssl']['CN_match'] )
					&& ( $options['ssl']['CN_match'] === 'commons.wikimedia.org'
						|| $options['ssl']['CN_match'] === 'upload.wikimedia.org' )
				) {
					$options['ssl']['CN_match'] = 'en.wikipedia.org';
					$context = stream_context_create( $options );
					continue;
				}
				break;
			}

			$result = stream_get_meta_data( $fh );
			$this->headerList = $result['wrapper_data'];
			$this->parseHeader();

			if ( !$this->followRedirects ) {
				break;
			}

			# Handle manual redirection
			if ( !$this->isRedirect() || $reqCount > $this->maxRedirects ) {
				break;
			}
			# Check security of URL
			$url = $this->getResponseHeader( "Location" );

			if ( !Http::isValidURI( $url ) ) {
				wfDebug( __METHOD__ . ": insecure redirection\n" );
				break;
			}
		} while ( true );
		if ( $this->profiler ) {
			$this->profiler->scopedProfileOut( $profileSection );
		}

		$this->setStatus();

		if ( $fh === false ) {
			if ( $this->fopenErrors ) {
				LoggerFactory::getInstance( 'http' )->warning( __CLASS__
					. ': error opening connection: {errstr1}', $this->fopenErrors );
			}
			$this->status->fatal( 'http-request-error' );
			return $this->status;
		}

		if ( $result['timed_out'] ) {
			$this->status->fatal( 'http-timed-out', $this->url );
			return $this->status;
		}

		// If everything went OK, or we received some error code
		// get the response body content.
		if ( $this->status->isOK() || (int)$this->respStatus >= 300 ) {
			while ( !feof( $fh ) ) {
				$buf = fread( $fh, 8192 );

				if ( $buf === false ) {
					$this->status->fatal( 'http-read-error' );
					break;
				}

				if ( strlen( $buf ) ) {
					call_user_func( $this->callback, $fh, $buf );
				}
			}
		}
		fclose( $fh );

		return $this->status;
	}
}
