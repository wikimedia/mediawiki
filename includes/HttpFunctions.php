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
	public $status;

	/**
	 * @param $url	 string url to use
	 * @param $options array (optional) extra params to pass
	 *				 Possible keys for the array:
	 *					method
	 *					timeout
	 *					targetFilePath
	 *					requestKey
	 *					postData
	 *					proxy
	 *					noProxy
	 *					sslVerifyHost
	 *					caInfo
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

		$members = array( "targetFilePath", "requestKey", "postData",
			"proxy", "noProxy", "sslVerifyHost", "caInfo", "method" );
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
			throw new MWException( __METHOD__.': curl (http://php.net/curl) is not installed, but Http::$httpEngine is set to "curl"' );
		}

		switch( Http::$httpEngine ) {
		case 'curl':
			return new CurlHttpRequest( $url, $options );
		case 'php':
			if ( !wfIniGetBool( 'allow_url_fopen' ) ) {
				throw new MWException( __METHOD__.': allow_url_fopen needs to be enabled for pure PHP http requests to work. '.
					'If possible, curl should be used instead.  See http://php.net/curl.' );
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
	 * A generic callback to read in the response from a remote server
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
}

/**
 * HttpRequest implemented using internal curl compiled into PHP
 */
class CurlHttpRequest extends HttpRequest {
	protected $curlOptions = array();

	public function execute() {
		parent::execute();
		if ( !$this->status->isOK() ) {
			return $this->status;
		}
		$this->curlOptions[CURLOPT_PROXY] = $this->proxy;
		$this->curlOptions[CURLOPT_TIMEOUT] = $this->timeout;
		$this->curlOptions[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
		$this->curlOptions[CURLOPT_WRITEFUNCTION] = $this->callback;

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
			// re-using already translated error messages
			$this->status->fatal( 'upload-curl-error'.curl_errno( $curlHandle ).'-text' );
		}

		curl_close( $curlHandle );

		return $this->status;
	}
}

class PhpHttpRequest extends HttpRequest {
	private $fh;

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

		$options['method'] = $this->method;
		$options['timeout'] = $this->timeout;
		$options['header'] = implode("\r\n", $this->getHeaderList());
		// FOR NOW: Force everyone to HTTP 1.0
		/* if ( version_compare( "5.3.0", phpversion(), ">" ) ) { */
			$options['protocol_version'] = "1.0";
		/* } else { */
		/* 	$options['protocol_version'] = "1.1"; */
		/* } */

		if ( $this->postData ) {
			$options['content'] = $this->postData;
		}

		$context = stream_context_create( array( 'http' => $options ) );
		try {
			$this->fh = fopen( $this->url, "r", false, $context );
		} catch ( Exception $e ) {
			$this->status->fatal( $e->getMessage() ); /* need some l10n help */
			return $this->status;
		}

		$result = stream_get_meta_data( $this->fh );
		if ( $result['timed_out'] ) {
			$this->status->fatal( 'http-timed-out', $this->url );
			return $this->status;
		}

		$this->headers = $result['wrapper_data'];

		$end = false;
		while ( !$end ) {
			$contents = fread( $this->fh, 8192 );
			$size = 0;
			if ( $contents ) {
				$size = call_user_func_array( $this->callback, array( $this->fh, $contents ) );
			}
			$end = ( $size == 0 )  || feof( $this->fh );
		}
		fclose( $this->fh );

		return $this->status;
	}
}
