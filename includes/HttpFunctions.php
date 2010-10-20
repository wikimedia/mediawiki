<?php
/**
 * @defgroup HTTP HTTP
 */

/**
 * Various HTTP related functions
 * @ingroup HTTP
 */
class Http {
	static $httpEngine = false;

	/**
	 * Perform an HTTP request
	 *
	 * @param $method String: HTTP method. Usually GET/POST
	 * @param $url String: full URL to act on
	 * @param $options Array: options to pass to HttpRequest object.
	 *	Possible keys for the array:
	 *    - timeout             Timeout length in seconds
	 *    - postData            An array of key-value pairs or a url-encoded form data
	 *    - proxy               The proxy to use.
	 *                          Will use $wgHTTPProxy (if set) otherwise.
	 *    - noProxy             Override $wgHTTPProxy (if set) and don't use any proxy at all.
	 *    - sslVerifyHost       (curl only) Verify hostname against certificate
	 *    - sslVerifyCert       (curl only) Verify SSL certificate
	 *    - caInfo              (curl only) Provide CA information
	 *    - maxRedirects        Maximum number of redirects to follow (defaults to 5)
	 *    - followRedirects     Whether to follow redirects (defaults to false).
	 *		                    Note: this should only be used when the target URL is trusted,
	 *		                    to avoid attacks on intranet services accessible by HTTP.
	 * @return Mixed: (bool)false on failure or a string on success
	 */
	public static function request( $method, $url, $options = array() ) {
		$url = wfExpandUrl( $url );
		wfDebug( "HTTP: $method: $url\n" );
		$options['method'] = strtoupper( $method );

		if ( !isset( $options['timeout'] ) ) {
			$options['timeout'] = 'default';
		}

		$req = HttpRequest::factory( $url, $options );
		$status = $req->execute();

		if ( $status->isOK() ) {
			return $req->getContent();
		} else {
			return false;
		}
	}

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 * @see Http::request()
	 */
	public static function get( $url, $timeout = 'default', $options = array() ) {
		$options['timeout'] = $timeout;
		return Http::request( 'GET', $url, $options );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 * @see Http::request()
	 */
	public static function post( $url, $options = array() ) {
		return Http::request( 'POST', $url, $options );
	}

	/**
	 * Check if the URL can be served by localhost
	 *
	 * @param $url String: full url to check
	 * @return Boolean
	 */
	public static function isLocalURL( $url ) {
		global $wgCommandLineMode, $wgConf;

		if ( $wgCommandLineMode ) {
			return false;
		}

		// Extract host part
		$matches = array();
		if ( preg_match( '!^http://([\w.-]+)[/:].*$!', $url, $matches ) ) {
			$host = $matches[1];
			// Split up dotwise
			$domainParts = explode( '.', $host );
			// Check if this domain or any superdomain is listed in $wgConf as a local virtual host
			$domainParts = array_reverse( $domainParts );

			for ( $i = 0; $i < count( $domainParts ); $i++ ) {
				$domainPart = $domainParts[$i];
				if ( $i == 0 ) {
					$domain = $domainPart;
				} else {
					$domain = $domainPart . '.' . $domain;
				}

				if ( $wgConf->isLocalVHost( $domain ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * A standard user-agent we can use for external requests.
	 * @return String
	 */
	public static function userAgent() {
		global $wgVersion;
		return "MediaWiki/$wgVersion";
	}

	/**
	 * Checks that the given URI is a valid one
	 *
	 * @param $uri Mixed: URI to check for validity
	 * @returns Boolean
	 */
	public static function isValidURI( $uri ) {
		return preg_match(
			'/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/',
			$uri,
			$matches
		);
	}
}

/**
 * This wrapper class will call out to curl (if available) or fallback
 * to regular PHP if necessary for handling internal HTTP requests.
 */
class HttpRequest {
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

	protected $cookieJar;

	protected $headerList = array();
	protected $respVersion = "0.9";
	protected $respStatus = "200 Ok";
	protected $respHeaders = array();

	public $status;

	/**
	 * @param $url String: url to use
	 * @param $options Array: (optional) extra params to pass (see Http::request())
	 */
	function __construct( $url, $options = array() ) {
		global $wgHTTPTimeout;

		$this->url = $url;
		$this->parsedUrl = parse_url( $url );

		if ( !Http::isValidURI( $this->url ) ) {
			$this->status = Status::newFatal( 'http-invalid-url' );
		} else {
			$this->status = Status::newGood( 100 ); // continue
		}

		if ( isset( $options['timeout'] ) && $options['timeout'] != 'default' ) {
			$this->timeout = $options['timeout'];
		} else {
			$this->timeout = $wgHTTPTimeout;
		}

		$members = array( "postData", "proxy", "noProxy", "sslVerifyHost", "caInfo",
				  "method", "followRedirects", "maxRedirects", "sslVerifyCert" );

		foreach ( $members as $o ) {
			if ( isset( $options[$o] ) ) {
				$this->$o = $options[$o];
			}
		}
	}

	/**
	 * Generate a new request object
	 * @see HttpRequest::__construct
	 */
	public static function factory( $url, $options = null ) {
		if ( !Http::$httpEngine ) {
			Http::$httpEngine = function_exists( 'curl_init' ) ? 'curl' : 'php';
		} elseif ( Http::$httpEngine == 'curl' && !function_exists( 'curl_init' ) ) {
			throw new MWException( __METHOD__ . ': curl (http://php.net/curl) is not installed, but' .
								   ' Http::$httpEngine is set to "curl"' );
		}

		switch( Http::$httpEngine ) {
			case 'curl':
				return new CurlHttpRequest( $url, $options );
			case 'php':
				if ( !wfIniGetBool( 'allow_url_fopen' ) ) {
					throw new MWException( __METHOD__ . ': allow_url_fopen needs to be enabled for pure PHP' .
						' http requests to work. If possible, curl should be used instead. See http://php.net/curl.' );
				}
				return new PhpHttpRequest( $url, $options );
			default:
				throw new MWException( __METHOD__ . ': The setting of Http::$httpEngine is not valid.' );
		}
	}

	/**
	 * Get the body, or content, of the response to the request
	 *
	 * @return String
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set the parameters of the request

	 * @param $args Array
	 * @todo overload the args param
	 */
	public function setData( $args ) {
		$this->postData = $args;
	}

	/**
	 * Take care of setting up the proxy
	 * (override in subclass)
	 *
	 * @return String
	 */
	public function proxySetup() {
		global $wgHTTPProxy;

		if ( $this->proxy ) {
			return;
		}

		if ( Http::isLocalURL( $this->url ) ) {
			$this->proxy = 'http://localhost:80/';
		} elseif ( $wgHTTPProxy ) {
			$this->proxy = $wgHTTPProxy ;
		} elseif ( getenv( "http_proxy" ) ) {
			$this->proxy = getenv( "http_proxy" );
		}
	}

	/**
	 * Set the refererer header
	 */
	public function setReferer( $url ) {
		$this->setHeader( 'Referer', $url );
	}

	/**
	 * Set the user agent
	 */
	public function setUserAgent( $UA ) {
		$this->setHeader( 'User-Agent', $UA );
	}

	/**
	 * Set an arbitrary header
	 */
	public function setHeader( $name, $value ) {
		// I feel like I should normalize the case here...
		$this->reqHeaders[$name] = $value;
	}

	/**
	 * Get an array of the headers
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
	 * Set the callback
	 *
	 * @param $callback Callback
	 */
	public function setCallback( $callback ) {
		$this->callback = $callback;
	}

	/**
	 * A generic callback to read the body of the response from a remote
	 * server.
	 *
	 * @param $fh handle
	 * @param $content String
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
		global $wgTitle;

		$this->content = "";

		if ( strtoupper( $this->method ) == "HEAD" ) {
			$this->headersOnly = true;
		}

		if ( is_array( $this->postData ) ) {
			$this->postData = wfArrayToCGI( $this->postData );
		}

		if ( is_object( $wgTitle ) && !isset( $this->reqHeaders['Referer'] ) ) {
			$this->setReferer( $wgTitle->getFullURL() );
		}

		if ( !$this->noProxy ) {
			$this->proxySetup();
		}

		if ( !$this->callback ) {
			$this->setCallback( array( $this, 'read' ) );
		}

		if ( !isset( $this->reqHeaders['User-Agent'] ) ) {
			$this->setUserAgent( Http::userAgent() );
		}
	}

	/**
	 * Parses the headers, including the HTTP status code and any
	 * Set-Cookie headers.  This function expectes the headers to be
	 * found in an array in the member variable headerList.
	 *
	 * @return nothing
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
	 * Sets the member variable status to a fatal status if the HTTP
	 * status code was not 200.
	 *
	 * @return nothing
	 */
	protected function setStatus() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		if ( (int)$this->respStatus !== 200 ) {
			list( $code, $message ) = explode( " ", $this->respStatus, 2 );
			$this->status->fatal( "http-bad-status", $code, $message );
		}
	}

	/**
	 * Get the member variable status for an HTTP Request
	 *
	 * @return Integer
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
	 * @return Boolean
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
	 * @return Array
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
	 * @param $header String
	 * @return String
	 */
	public function getResponseHeader( $header ) {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		if ( isset( $this->respHeaders[strtolower ( $header ) ] ) ) {
			$v = $this->respHeaders[strtolower ( $header ) ];
			return $v[count( $v ) - 1];
		}

		return null;
	}

	/**
	 * Tells the HttpRequest object to use this pre-loaded CookieJar.
	 *
	 * @param $jar CookieJar
	 */
	public function setCookieJar( $jar ) {
		$this->cookieJar = $jar;
	}

	/**
	 * Returns the cookie jar in use.
	 *
	 * @returns CookieJar
	 */
	public function getCookieJar() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return $this->cookieJar;
	}

	/**
	 * Sets a cookie.  Used before a request to set up any individual
	 * cookies.	 Used internally after a request to parse the
	 * Set-Cookie headers.
	 * @see Cookie::set
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
	 * @return String
	 */
	public function getFinalUrl() {
		$location = $this->getResponseHeader( "Location" );

		if ( $location ) {
			return $location;
		}

		return $this->url;
	}

	/**
	 * Returns true if the backend can follow redirects. Overridden by the
	 * child classes.
	 */
	public function canFollowRedirects() {
		return true;
	}
}


class Cookie {
	protected $name;
	protected $value;
	protected $expires;
	protected $path;
	protected $domain;
	protected $isSessionKey = true;
	// TO IMPLEMENT	 protected $secure
	// TO IMPLEMENT? protected $maxAge (add onto expires)
	// TO IMPLEMENT? protected $version
	// TO IMPLEMENT? protected $comment

	function __construct( $name, $value, $attr ) {
		$this->name = $name;
		$this->set( $value, $attr );
	}

	/**
	 * Sets a cookie.  Used before a request to set up any individual
	 * cookies.	 Used internally after a request to parse the
	 * Set-Cookie headers.
	 *
	 * @param $value String: the value of the cookie
	 * @param $attr Array: possible key/values:
	 *		expires	 A date string
	 *		path	 The path this cookie is used on
	 *		domain	 Domain this cookie is used on
	 */
	public function set( $value, $attr ) {
		$this->value = $value;

		if ( isset( $attr['expires'] ) ) {
			$this->isSessionKey = false;
			$this->expires = strtotime( $attr['expires'] );
		}

		if ( isset( $attr['path'] ) ) {
			$this->path = $attr['path'];
		} else {
			$this->path = "/";
		}

		if ( isset( $attr['domain'] ) ) {
			if ( self::validateCookieDomain( $attr['domain'] ) ) {
				$this->domain = $attr['domain'];
			}
		} else {
			throw new MWException( "You must specify a domain." );
		}
	}

	/**
	 * Return the true if the cookie is valid is valid.  Otherwise,
	 * false.  The uses a method similar to IE cookie security
	 * described here:
	 * http://kuza55.blogspot.com/2008/02/understanding-cookie-security.html
	 * A better method might be to use a blacklist like
	 * http://publicsuffix.org/
	 *
	 * @param $domain String: the domain to validate
	 * @param $originDomain String: (optional) the domain the cookie originates from
	 * @return Boolean
	 */
	public static function validateCookieDomain( $domain, $originDomain = null ) {
		// Don't allow a trailing dot
		if ( substr( $domain, -1 ) == "." ) {
			return false;
		}

		$dc = explode( ".", $domain );

		// Only allow full, valid IP addresses
		if ( preg_match( '/^[0-9.]+$/', $domain ) ) {
			if ( count( $dc ) != 4 ) {
				return false;
			}

			if ( ip2long( $domain ) === false ) {
				return false;
			}

			if ( $originDomain == null || $originDomain == $domain ) {
				return true;
			}

		}

		// Don't allow cookies for "co.uk" or "gov.uk", etc, but allow "supermarket.uk"
		if ( strrpos( $domain, "." ) - strlen( $domain )  == -3 ) {
			if ( ( count( $dc ) == 2 && strlen( $dc[0] ) <= 2 )
				|| ( count( $dc ) == 3 && strlen( $dc[0] ) == "" && strlen( $dc[1] ) <= 2 ) ) {
				return false;
			}
			if ( ( count( $dc ) == 2 || ( count( $dc ) == 3 && $dc[0] == "" ) )
				&& preg_match( '/(com|net|org|gov|edu)\...$/', $domain ) ) {
				return false;
			}
		}

		if ( $originDomain != null ) {
			if ( substr( $domain, 0, 1 ) != "." && $domain != $originDomain ) {
				return false;
			}

			if ( substr( $domain, 0, 1 ) == "."
				&& substr_compare( $originDomain, $domain, -strlen( $domain ),
								   strlen( $domain ), TRUE ) != 0 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Serialize the cookie jar into a format useful for HTTP Request headers.
	 *
	 * @param $path String: the path that will be used. Required.
	 * @param $domain String: the domain that will be used. Required.
	 * @return String
	 */
	public function serializeToHttpRequest( $path, $domain ) {
		$ret = "";

		if ( $this->canServeDomain( $domain )
				&& $this->canServePath( $path )
				&& $this->isUnExpired() ) {
			$ret = $this->name . "=" . $this->value;
		}

		return $ret;
	}

	protected function canServeDomain( $domain ) {
		if ( $domain == $this->domain
			|| ( strlen( $domain ) > strlen( $this->domain )
				 && substr( $this->domain, 0, 1 ) == "."
				 && substr_compare( $domain, $this->domain, -strlen( $this->domain ),
									strlen( $this->domain ), TRUE ) == 0 ) ) {
			return true;
		}

		return false;
	}

	protected function canServePath( $path ) {
		if ( $this->path && substr_compare( $this->path, $path, 0, strlen( $this->path ) ) == 0 ) {
			return true;
		}

		return false;
	}

	protected function isUnExpired() {
		if ( $this->isSessionKey || $this->expires > time() ) {
			return true;
		}

		return false;
	}
}

class CookieJar {
	private $cookie = array();

	/**
	 * Set a cookie in the cookie jar.	Make sure only one cookie per-name exists.
	 * @see Cookie::set()
	 */
	public function setCookie ( $name, $value, $attr ) {
		/* cookies: case insensitive, so this should work.
		 * We'll still send the cookies back in the same case we got them, though.
		 */
		$index = strtoupper( $name );

		if ( isset( $this->cookie[$index] ) ) {
			$this->cookie[$index]->set( $value, $attr );
		} else {
			$this->cookie[$index] = new Cookie( $name, $value, $attr );
		}
	}

	/**
	 * @see Cookie::serializeToHttpRequest
	 */
	public function serializeToHttpRequest( $path, $domain ) {
		$cookies = array();

		foreach ( $this->cookie as $c ) {
			$serialized = $c->serializeToHttpRequest( $path, $domain );

			if ( $serialized ) {
				$cookies[] = $serialized;
			}
		}

		return implode( "; ", $cookies );
	}

	/**
	 * Parse the content of an Set-Cookie HTTP Response header.
	 *
	 * @param $cookie String
	 * @param $domain String: cookie's domain
	 */
	public function parseCookieResponseHeader ( $cookie, $domain ) {
		$len = strlen( "Set-Cookie:" );

		if ( substr_compare( "Set-Cookie:", $cookie, 0, $len, TRUE ) === 0 ) {
			$cookie = substr( $cookie, $len );
		}

		$bit = array_map( 'trim', explode( ";", $cookie ) );

		if ( count( $bit ) >= 1 ) {
			list( $name, $value ) = explode( "=", array_shift( $bit ), 2 );
			$attr = array();

			foreach ( $bit as $piece ) {
				$parts = explode( "=", $piece );
				if ( count( $parts ) > 1 ) {
					$attr[strtolower( $parts[0] )] = $parts[1];
				} else {
					$attr[strtolower( $parts[0] )] = true;
				}
			}

			if ( !isset( $attr['domain'] ) ) {
				$attr['domain'] = $domain;
			} elseif ( !Cookie::validateCookieDomain( $attr['domain'], $domain ) ) {
				return null;
			}

			$this->setCookie( $name, $value, $attr );
		}
	}
}

/**
 * HttpRequest implemented using internal curl compiled into PHP
 */
class CurlHttpRequest extends HttpRequest {
	static $curlMessageMap = array(
		6 => 'http-host-unreachable',
		28 => 'http-timed-out'
	);

	protected $curlOptions = array();
	protected $headerText = "";

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
		$this->curlOptions[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
		$this->curlOptions[CURLOPT_WRITEFUNCTION] = $this->callback;
		$this->curlOptions[CURLOPT_HEADERFUNCTION] = array( $this, "readHeader" );
		$this->curlOptions[CURLOPT_MAXREDIRS] = $this->maxRedirects;
		$this->curlOptions[CURLOPT_ENCODING] = ""; # Enable compression

		/* not sure these two are actually necessary */
		if ( isset( $this->reqHeaders['Referer'] ) ) {
			$this->curlOptions[CURLOPT_REFERER] = $this->reqHeaders['Referer'];
		}
		$this->curlOptions[CURLOPT_USERAGENT] = $this->reqHeaders['User-Agent'];

		if ( isset( $this->sslVerifyHost ) ) {
			$this->curlOptions[CURLOPT_SSL_VERIFYHOST] = $this->sslVerifyHost;
		}

		if ( isset( $this->sslVerifyCert ) ) {
			$this->curlOptions[CURLOPT_SSL_VERIFYPEER] = $this->sslVerifyCert;
		}

		if ( $this->caInfo ) {
			$this->curlOptions[CURLOPT_CAINFO] = $this->caInfo;
		}

		if ( $this->headersOnly ) {
			$this->curlOptions[CURLOPT_NOBODY] = true;
			$this->curlOptions[CURLOPT_HEADER] = true;
		} elseif ( $this->method == 'POST' ) {
			$this->curlOptions[CURLOPT_POST] = true;
			$this->curlOptions[CURLOPT_POSTFIELDS] = $this->postData;
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
			if ( ! @curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, true ) ) {
				wfDebug( __METHOD__ . ": Couldn't set CURLOPT_FOLLOWLOCATION. " .
					"Probably safe_mode or open_basedir is set.\n" );
				// Continue the processing. If it were in curl_setopt_array,
				// processing would have halted on its entry
			}
		}

		if ( false === curl_exec( $curlHandle ) ) {
			$code = curl_error( $curlHandle );

			if ( isset( self::$curlMessageMap[$code] ) ) {
				$this->status->fatal( self::$curlMessageMap[$code] );
			} else {
				$this->status->fatal( 'http-curl-error', curl_error( $curlHandle ) );
			}
		} else {
			$this->headerList = explode( "\r\n", $this->headerText );
		}

		curl_close( $curlHandle );

		$this->parseHeader();
		$this->setStatus();

		return $this->status;
	}

	public function canFollowRedirects() {
		if ( strval( ini_get( 'open_basedir' ) ) !== '' || wfIniGetBool( 'safe_mode' ) ) {
			wfDebug( "Cannot follow redirects in safe mode\n" );
			return false;
		}

		if ( !defined( 'CURLOPT_REDIR_PROTOCOLS' ) ) {
			wfDebug( "Cannot follow redirects with libcurl < 7.19.4 due to CVE-2009-0037\n" );
			return false;
		}

		return true;
	}
}

class PhpHttpRequest extends HttpRequest {
	protected function urlToTcp( $url ) {
		$parsedUrl = parse_url( $url );

		return 'tcp://' . $parsedUrl['host'] . ':' . $parsedUrl['port'];
	}

	public function execute() {
		parent::execute();

		// At least on Centos 4.8 with PHP 5.1.6, using max_redirects to follow redirects
		// causes a segfault
		$manuallyRedirect = version_compare( phpversion(), '5.1.7', '<' );

		if ( $this->parsedUrl['scheme'] != 'http' ) {
			$this->status->fatal( 'http-invalid-scheme', $this->parsedUrl['scheme'] );
		}

		$this->reqHeaders['Accept'] = "*/*";
		if ( $this->method == 'POST' ) {
			// Required for HTTP 1.0 POSTs
			$this->reqHeaders['Content-Length'] = strlen( $this->postData );
			$this->reqHeaders['Content-type'] = "application/x-www-form-urlencoded";
		}

		$options = array();
		if ( $this->proxy && !$this->noProxy ) {
			$options['proxy'] = $this->urlToTCP( $this->proxy );
			$options['request_fulluri'] = true;
		}

		if ( !$this->followRedirects || $manuallyRedirect ) {
			$options['max_redirects'] = 0;
		} else {
			$options['max_redirects'] = $this->maxRedirects;
		}

		$options['method'] = $this->method;
		$options['header'] = implode( "\r\n", $this->getHeaderList() );
		// Note that at some future point we may want to support
		// HTTP/1.1, but we'd have to write support for chunking
		// in version of PHP < 5.3.1
		$options['protocol_version'] = "1.0";

		// This is how we tell PHP we want to deal with 404s (for example) ourselves.
		// Only works on 5.2.10+
		$options['ignore_errors'] = true;

		if ( $this->postData ) {
			$options['content'] = $this->postData;
		}

		$oldTimeout = false;
		if ( version_compare( '5.2.1', phpversion(), '>' ) ) {
			$oldTimeout = ini_set( 'default_socket_timeout', $this->timeout );
		} else {
			$options['timeout'] = $this->timeout;
		}

		$context = stream_context_create( array( 'http' => $options ) );

		$this->headerList = array();
		$reqCount = 0;
		$url = $this->url;

		do {
			$reqCount++;
			wfSuppressWarnings();
			$fh = fopen( $url, "r", false, $context );
			wfRestoreWarnings();

			if ( !$fh ) {
				break;
			}

			$result = stream_get_meta_data( $fh );
			$this->headerList = $result['wrapper_data'];
			$this->parseHeader();

			if ( !$manuallyRedirect || !$this->followRedirects ) {
				break;
			}

			# Handle manual redirection
			if ( !$this->isRedirect() || $reqCount > $this->maxRedirects ) {
				break;
			}
			# Check security of URL
			$url = $this->getResponseHeader( "Location" );

			if ( substr( $url, 0, 7 ) !== 'http://' ) {
				wfDebug( __METHOD__ . ": insecure redirection\n" );
				break;
			}
		} while ( true );

		if ( $oldTimeout !== false ) {
			ini_set( 'default_socket_timeout', $oldTimeout );
		}

		$this->setStatus();

		if ( $fh === false ) {
			$this->status->fatal( 'http-request-error' );
			return $this->status;
		}

		if ( $result['timed_out'] ) {
			$this->status->fatal( 'http-timed-out', $this->url );
			return $this->status;
		}

		if ( $this->status->isOK() ) {
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
