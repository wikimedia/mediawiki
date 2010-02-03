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
	 * @param $method string HTTP method. Usually GET/POST
	 * @param $url string Full URL to act on
	 * @param $options options to pass to HttpRequest object
	 *				 Possible keys for the array:
	 *					timeout			  Timeout length in seconds
	 *					postData		  An array of key-value pairs or a url-encoded form data
	 *					proxy			  The proxy to use.	 Will use $wgHTTPProxy (if set) otherwise.
	 *					noProxy			  Override $wgHTTPProxy (if set) and don't use any proxy at all.
	 *					sslVerifyHost	  (curl only) Verify the SSL certificate
	 *					caInfo			  (curl only) Provide CA information
	 *					maxRedirects	  Maximum number of redirects to follow (defaults to 5)
	 *					followRedirects	  Whether to follow redirects (defaults to true)
	 * @returns mixed (bool)false on failure or a string on success
	 */
	public static function request( $method, $url, $options = array() ) {
		wfDebug( "HTTP: $method: $url" );
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
	 * @param $url string Full url to check
	 * @return bool
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
	 * @returns string
	 */
	public static function userAgent() {
		global $wgVersion;
		return "MediaWiki/$wgVersion";
	}

	/**
	 * Checks that the given URI is a valid one
	 * @param $uri Mixed: URI to check for validity
	 * @returns bool
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
	protected $caInfo = null;
	protected $method = "GET";
	protected $reqHeaders = array();
	protected $url;
	protected $parsedUrl;
	protected $callback;
	protected $maxRedirects = 5;
	protected $followRedirects = true;

	protected $cookieJar;

	protected $headerList = array();
	protected $respVersion = "0.9";
	protected $respStatus = "0.1";
	protected $respHeaders = array();

	public $status;

	/**
	 * @param $url	 string url to use
	 * @param $options array (optional) extra params to pass (see Http::request())
	 */
	function __construct( $url, $options = array() ) {
		global $wgHTTPTimeout;

		$this->url = $url;
		$this->parsedUrl = parse_url( $url );

		if ( !Http::isValidURI( $this->url ) ) {
			$this->status = Status::newFromFatal('http-invalid-url');
		} else {
			$this->status = Status::newGood( 100 ); // continue
		}

		if ( isset($options['timeout']) && $options['timeout'] != 'default' ) {
			$this->timeout = $options['timeout'];
		} else {
			$this->timeout = $wgHTTPTimeout;
		}

		$members = array( "postData", "proxy", "noProxy", "sslVerifyHost", "caInfo",
						  "method", "followRedirects", "maxRedirects" );
		foreach ( $members as $o ) {
			if ( isset($options[$o]) ) {
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
			throw new MWException( __METHOD__.': curl (http://php.net/curl) is not installed, but'.
								   ' Http::$httpEngine is set to "curl"' );
		}

		switch( Http::$httpEngine ) {
		case 'curl':
			return new CurlHttpRequest( $url, $options );
		case 'php':
			if ( !wfIniGetBool( 'allow_url_fopen' ) ) {
				throw new MWException( __METHOD__.': allow_url_fopen needs to be enabled for pure PHP'.
					' http requests to work. If possible, curl should be used instead. See http://php.net/curl.' );
			}
			return new PhpHttpRequest( $url, $options );
		default:
			throw new MWException( __METHOD__.': The setting of Http::$httpEngine is not valid.' );
		}
	}

	/**
	 * Get the body, or content, of the response to the request
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Take care of setting up the proxy
	 * (override in subclass)
	 * @return string
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
		}
	}

	/**
	 * Set the refererer header
	 */
	public function setReferer( $url ) {
		$this->setHeader('Referer', $url);
	}

	/**
	 * Set the user agent
	 */
	public function setUserAgent( $UA ) {
		$this->setHeader('User-Agent', $UA);
	}

	/**
	 * Set an arbitrary header
	 */
	public function setHeader($name, $value) {
		// I feel like I should normalize the case here...
		$this->reqHeaders[$name] = $value;
	}

	/**
	 * Get an array of the headers
	 */
	public function getHeaderList() {
		$list = array();

		if( $this->cookieJar ) {
			$this->reqHeaders['Cookie'] = $this->cookieJar->serializeToHttpRequest();
		}
		foreach($this->reqHeaders as $name => $value) {
			$list[] = "$name: $value";
		}
		return $list;
	}

	/**
	 * Set the callback
	 * @param $callback callback
	 */
	public function setCallback( $callback ) {
		$this->callback = $callback;
	}

	/**
	 * A generic callback to read the body of the response from a remote
	 * server.
	 * @param $fh handle
	 * @param $content string
	 */
	public function read( $fh, $content ) {
		$this->content .= $content;
		return strlen( $content );
	}

	/**
	 * Take care of whatever is necessary to perform the URI request.
	 * @return Status
	 */
	public function execute() {
		global $wgTitle;

		if( strtoupper($this->method) == "HEAD" ) {
			$this->headersOnly = true;
		}

		if ( is_array( $this->postData ) ) {
			$this->postData = wfArrayToCGI( $this->postData );
		}

		if ( is_object( $wgTitle ) && !isset($this->reqHeaders['Referer']) ) {
			$this->setReferer( $wgTitle->getFullURL() );
		}

		if ( !$this->noProxy ) {
			$this->proxySetup();
		}

		if ( !$this->callback ) {
			$this->setCallback( array( $this, 'read' ) );
		}

		if ( !isset($this->reqHeaders['User-Agent']) ) {
			$this->setUserAgent(Http::userAgent());
		}
	}

	protected function parseHeader() {
		$lastname = "";
		foreach( $this->headerList as $header ) {
			if( preg_match( "#^HTTP/([0-9.]+) (.*)#", $header, $match ) ) {
				$this->respVersion = $match[1];
				$this->respStatus = $match[2];
			} elseif( preg_match( "#^[ \t]#", $header ) ) {
				$last = count($this->respHeaders[$lastname]) - 1;
				$this->respHeaders[$lastname][$last] .= "\r\n$header";
			} elseif( preg_match( "#^([^:]*):[\t ]*(.*)#", $header, $match ) ) {
				$this->respHeaders[strtolower( $match[1] )][] = $match[2];
				$lastname = strtolower( $match[1] );
			}
		}

		$this->parseCookies();
	}

	/**
	 * Returns an associative array of response headers after the
	 * request has been executed.  Because some headers
	 * (e.g. Set-Cookie) can appear more than once the, each value of
	 * the associative array is an array of the values given.
	 * @return array
	 */
	public function getResponseHeaders() {
		if( !$this->respHeaders ) {
			$this->parseHeader();
		}
		return $this->respHeaders;
	}

	/**
	 * Tells the HttpRequest object to use this pre-loaded CookieJar.
	 * @param $jar CookieJar
	 */
	public function setCookieJar( $jar ) {
		$this->cookieJar = $jar;
	}

	/**
	 * Returns the cookie jar in use.
	 * @returns CookieJar
	 */
	public function getCookieJar() {
		if( !$this->respHeaders ) {
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
	public function setCookie( $name, $value = null, $attr = null) {
		if( !$this->cookieJar ) {
			$this->cookieJar = new CookieJar;
		}
		$this->cookieJar->setCookie($name, $value, $attr);
	}

	/**
	 * Parse the cookies in the response headers and store them in the cookie jar.
	 */
	protected function parseCookies() {
		if( isset( $this->respHeaders['set-cookie'] ) ) {
			if( !$this->cookieJar ) {
				$this->cookieJar = new CookieJar;
			}
			$url = parse_url( $this->getFinalUrl() );
			foreach( $this->respHeaders['set-cookie'] as $cookie ) {
				$this->cookieJar->parseCookieResponseHeader( $cookie, $url['host'] );
			}
		}
	}

	/**
	 * Returns the final URL after all redirections.
	 * @returns string
	 */
	public function getFinalUrl() {
		$finalUrl = $this->url;
		if ( isset( $this->respHeaders['location'] ) ) {
			$redir = $this->respHeaders['location'];
			$finalUrl = $redir[count($redir) - 1];
		}

		return $finalUrl;
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
	 * @param $name string the name of the cookie
	 * @param $value string the value of the cookie
	 * @param $attr array possible key/values:
	 *		expires	 A date string
	 *		path	 The path this cookie is used on
	 *		domain	 Domain this cookie is used on
	 */
	public function set( $value, $attr ) {
		$this->value = $value;
		if( isset( $attr['expires'] ) ) {
			$this->isSessionKey = false;
			$this->expires = strtotime( $attr['expires'] );
		}
		if( isset( $attr['path'] ) ) {
			$this->path = $attr['path'];
		} else {
			$this->path = "/";
		}
		if( isset( $attr['domain'] ) ) {
			$this->domain = $attr['domain'];
		} else {
			throw new MWException("You must specify a domain.");
		}
	}

	/**
	 * Serialize the cookie jar into a format useful for HTTP Request headers.
	 * @param $path string the path that will be used. Required.
	 * @param $domain string the domain that will be used. Required.
	 * @return string
	 */
	public function serializeToHttpRequest( $path, $domain ) {
		$ret = "";

		if( $this->canServeDomain( $domain )
				&& $this->canServePath( $path )
				&& $this->isUnExpired() ) {
			$ret = $this->name ."=". $this->value;
		}

		return $ret;
	}

	protected function canServeDomain( $domain ) {
		if( $this->domain && substr_compare( $domain, $this->domain, -strlen( $this->domain ),
											 strlen( $this->domain ), TRUE ) == 0 ) {
			return true;
		}
		return false;
	}

	protected function canServePath( $path ) {
		if( $this->path && substr_compare( $this->path, $path, 0, strlen( $this->path ) ) == 0 ) {
			return true;
		}
		return false;
	}

	protected function isUnExpired() {
		if( $this->isSessionKey || $this->expires > time() ) {
			return true;
		}
		return false;
	}

}

class CookieJar {
	private $cookie;

	/**
	 * Set a cookie in the cookie jar.	Make sure only one cookie per-name exists.
	 * @see Cookie::set()
	 */
	public function setCookie ($name, $value, $attr) {
		/* cookies: case insensitive, so this should work.
		 * We'll still send the cookies back in the same case we got them, though.
		 */
		$index = strtoupper($name);
		if( isset( $this->cookie[$index] ) ) {
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

		foreach( $this->cookie as $c ) {
			$serialized = $c->serializeToHttpRequest( $path, $domain );
			if ( $serialized ) $cookies[] = $serialized;
		}

		return implode("; ", $cookies);
	}

	/**
	 * Parse the content of an Set-Cookie HTTP Response header.
	 * @param $cookie string
	 */
	public function parseCookieResponseHeader ( $cookie, $domain = null ) {
		$len = strlen( "Set-Cookie:" );
		if ( substr_compare( "Set-Cookie:", $cookie, 0, $len, TRUE ) === 0 ) {
			$cookie = substr( $cookie, $len );
		}

		$bit = array_map( 'trim', explode( ";", $cookie ) );
		list($name, $value) = explode( "=", array_shift( $bit ), 2 );
		$attr = array();
		foreach( $bit as $piece ) {
			$parts = explode( "=", $piece );
			if( count( $parts ) > 1 ) {
				$attr[strtolower( $parts[0] )] = $parts[1];
			} else {
				$attr[strtolower( $parts[0] )] = true;
			}
		}
		$this->setCookie( $name, $value, $attr );
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
		$this->curlOptions[CURLOPT_HEADERFUNCTION] = array($this, "readHeader");
		$this->curlOptions[CURLOPT_FOLLOWLOCATION] = $this->followRedirects;
		$this->curlOptions[CURLOPT_MAXREDIRS] = $this->maxRedirects;

		/* not sure these two are actually necessary */
		if(isset($this->reqHeaders['Referer'])) {
			$this->curlOptions[CURLOPT_REFERER] = $this->reqHeaders['Referer'];
		}
		$this->curlOptions[CURLOPT_USERAGENT] = $this->reqHeaders['User-Agent'];

		if ( $this->sslVerifyHost ) {
			$this->curlOptions[CURLOPT_SSL_VERIFYHOST] = $this->sslVerifyHost;
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
		curl_setopt_array( $curlHandle, $this->curlOptions );

		if ( false === curl_exec( $curlHandle ) ) {
			$code = curl_error( $curlHandle );

			if ( isset( self::$curlMessageMap[$code] ) ) {
				$this->status->fatal( self::$curlMessageMap[$code] );
			} else {
				$this->status->fatal( 'http-curl-error', curl_error( $curlHandle ) );
			}
		} else {
			$this->headerList = explode("\r\n", $this->headerText);
		}

		curl_close( $curlHandle );

		return $this->status;
	}
}

class PhpHttpRequest extends HttpRequest {
	protected function urlToTcp( $url ) {
		$parsedUrl = parse_url( $url );

		return 'tcp://' . $parsedUrl['host'] . ':' . $parsedUrl['port'];
	}

	public function execute() {
		if ( $this->parsedUrl['scheme'] != 'http' ) {
			$this->status->fatal( 'http-invalid-scheme', $this->parsedURL['scheme'] );
		}

		parent::execute();
		if ( !$this->status->isOK() ) {
			return $this->status;
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

		if ( !$this->followRedirects ) {
			$options['max_redirects'] = 0;
		} else {
			$options['max_redirects'] = $this->maxRedirects;
		}

		$options['method'] = $this->method;
		$options['timeout'] = $this->timeout;
		$options['header'] = implode("\r\n", $this->getHeaderList());
		// Note that at some future point we may want to support
		// HTTP/1.1, but we'd have to write support for chunking
		// in version of PHP < 5.3.1
		$options['protocol_version'] = "1.0";

		if ( $this->postData ) {
			$options['content'] = $this->postData;
		}

		$oldTimeout = false;
		if ( version_compare( '5.2.1', phpversion(), '>' ) ) {
			$oldTimeout = ini_set('default_socket_timeout', $this->timeout);
		}

		$context = stream_context_create( array( 'http' => $options ) );
		wfSuppressWarnings();
		$fh = fopen( $this->url, "r", false, $context );
		wfRestoreWarnings();
		if ( $oldTimeout !== false ) {
			ini_set('default_socket_timeout', $oldTimeout);
		}
		if ( $fh === false ) {
			$this->status->fatal( 'http-request-error' );
			return $this->status;
		}

		$result = stream_get_meta_data( $fh );
		if ( $result['timed_out'] ) {
			$this->status->fatal( 'http-timed-out', $this->url );
			return $this->status;
		}
		$this->headerList = $result['wrapper_data'];

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
		fclose( $fh );

		return $this->status;
	}
}
