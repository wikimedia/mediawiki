<?php
/**
 * @defgroup HTTP HTTP
 */

/**
 * Various HTTP related functions
 * @ingroup HTTP
 */
class Http {
	/**
	 * Perform an HTTP request
	 * @param $method string HTTP method. Usually GET/POST
	 * @param $url string Full URL to act on
	 * @param $opts options to pass to HttpRequest object
	 * @returns mixed (bool)false on failure or a string on success
	 */
	public static function request( $method, $url, $opts = array() ) {
		$opts['method'] = strtoupper( $method );
		if ( !array_key_exists( 'timeout', $opts ) ) {
			$opts['timeout'] = 'default';
		}
		$req = HttpRequest::factory( $url, $opts );
		$status = $req->execute();
		if ( $status->isOK() ) {
			return $req;
		} else {
			return false;
		}
	}

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 * @see Http::request()
	 */
	public static function get( $url, $timeout = 'default', $opts = array() ) {
		$opts['timeout'] = $timeout;
		return Http::request( 'GET', $url, $opts );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 * @see Http::request()
	 */
	public static function post( $url, $opts = array() ) {
		return Http::request( 'POST', $url, $opts );
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
	protected $postdata = null;
	protected $proxy = null;
	protected $no_proxy = false;
	protected $sslVerifyHost = true;
	protected $caInfo = null;
	protected $method = "GET";
	protected $url;
	protected $parsed_url;
	public $status;

	/**
	 * @param $url	 string url to use
	 * @param $options array (optional) extra params to pass
	 *				 Possible keys for the array:
	 *					method
	 *					timeout
	 *					targetFilePath
	 *					requestKey
	 *					headersOnly
	 *					postdata
	 *					proxy
	 *					no_proxy
	 *					sslVerifyHost
	 *					caInfo
	 */
	function __construct( $url = null, $opt = array()) {
		global $wgHTTPTimeout, $wgTitle;

		$this->url = $url;
		$this->parsed_url = parse_url($url);

		if ( !ini_get( 'allow_url_fopen' ) ) {
			throw new MWException( 'allow_url_fopen needs to be enabled for http requests to work' );
		} elseif ( !Http::isValidURI( $this->url ) ) {
			throw new MWException( 'bad-uri' );
		} else {
			$this->status = Status::newGood( 100 ); // continue
		}

		if ( array_key_exists( 'timeout', $opt ) && $opt['timeout'] != 'default' ) {
			$this->timeout = $opt['timeout'];
		} else {
			$this->timeout = $wgHTTPTimeout;
		}

		$members = array( "targetFilePath", "requestKey", "headersOnly", "postdata",
						 "proxy", "no_proxy", "sslVerifyHost", "caInfo", "method" );
		foreach ( $members as $o ) {
			if ( array_key_exists( $o, $opt ) ) {
				$this->$o = $opt[$o];
			}
		}

		if ( is_array( $this->postdata ) ) {
			$this->postdata = wfArrayToCGI( $this->postdata );
		}

		$this->initRequest();

		if ( !$this->no_proxy ) {
			$this->proxySetup();
		}

		# Set the referer to $wgTitle, even in command-line mode
		# This is useful for interwiki transclusion, where the foreign
		# server wants to know what the referring page is.
		# $_SERVER['REQUEST_URI'] gives a less reliable indication of the
		# referring page.
		if ( is_object( $wgTitle ) ) {
			$this->setReferrer( $wgTitle->getFullURL() );
		}
	}

	/**
	 * For backwards compatibility, we provide a __toString method so
	 * that any code that expects a string result from Http::Get()
	 * will see the content of the request.
	 */
	function __toString() {
		return $this->content;
	}

	/**
	 * Generate a new request object
	 * @see HttpRequest::__construct
	 */
	public static function factory( $url, $opt ) {
		global $wgHTTPEngine;
		$engine = $wgHTTPEngine;

		if ( !$wgHTTPEngine ) {
			$wgHTTPEngine = function_exists( 'curl_init' ) ? 'curl' : 'php';
		} elseif ( $wgHTTPEngine == 'curl' && !function_exists( 'curl_init' ) ) {
			throw new MWException( 'FIXME' );
		}

		switch( $wgHTTPEngine ) {
		case 'curl':
			return new CurlHttpRequest( $url, $opt );
		case 'php':
			return new PhpHttpRequest( $url, $opt );
		default:
			throw new MWException( 'FIXME' );
		}
	}

	public function getContent() {
		return $this->content;
	}

	public function initRequest() {}
	public function proxySetup() {}
	public function setReferrer( $url ) {}
	public function setCallback( $cb ) {}
	public function read($fh, $content) {}
	public function getCode() {}
	public function execute() {}
}

/**
 * HttpRequest implemented using internal curl compiled into PHP
 */
class CurlHttpRequest extends HttpRequest {
	protected $curlHandle;
	protected $curlCBSet;

	public function initRequest() {
		$this->curlHandle = curl_init( $this->url );
	}

	public function proxySetup() {
		global $wgHTTPProxy;

		if ( is_string( $this->proxy ) ) {
			curl_setopt( $this->curlHandle, CURLOPT_PROXY, $this->proxy );
		} else if ( Http::isLocalURL( $this->url ) ) { /* Not sure this makes any sense. */
			curl_setopt( $this->curlHandle, CURLOPT_PROXY, 'localhost:80' );
		} else if ( $wgHTTPProxy ) {
			curl_setopt( $this->curlHandle, CURLOPT_PROXY, $wgHTTPProxy );
		}
	}

	public function setCallback( $cb ) {
		if ( !$this->curlCBSet ) {
			$this->curlCBSet = true;
			curl_setopt( $this->curlHandle, CURLOPT_WRITEFUNCTION, $cb );
		}
	}

	public function execute() {
		if( !$this->status->isOK() ) {
			return $this->status;
		}

		$this->setCallback( array($this, 'read') );

		curl_setopt( $this->curlHandle, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt( $this->curlHandle, CURLOPT_USERAGENT, Http::userAgent() );
		curl_setopt( $this->curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );

		if ( $this->sslVerifyHost ) {
			curl_setopt( $this->curlHandle, CURLOPT_SSL_VERIFYHOST, $this->sslVerifyHost );
		}

		if ( $this->caInfo ) {
			curl_setopt( $this->curlHandle, CURLOPT_CAINFO, $this->caInfo );
		}

		if ( $this->headersOnly ) {
			curl_setopt( $this->curlHandle, CURLOPT_NOBODY, true );
			curl_setopt( $this->curlHandle, CURLOPT_HEADER, true );
		} elseif ( $this->method == 'POST' ) {
			curl_setopt( $this->curlHandle, CURLOPT_POST, true );
			curl_setopt( $this->curlHandle, CURLOPT_POSTFIELDS, $this->postdata );
			// Suppress 'Expect: 100-continue' header, as some servers
			// will reject it with a 417 and Curl won't auto retry
			// with HTTP 1.0 fallback
			curl_setopt( $this->curlHandle, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
		} else {
			curl_setopt( $this->curlHandle, CURLOPT_CUSTOMREQUEST, $this->method );
		}

		try {
			if ( false === curl_exec( $this->curlHandle ) ) {
				$error_txt = 'Error sending request: #' . curl_errno( $this->curlHandle ) . ' ' .
					curl_error( $this->curlHandle );
				wfDebug( __METHOD__ . $error_txt . "\n" );
				$this->status->fatal( $error_txt ); /* i18n? */
			}
		} catch ( Exception $e ) {
			$errno = curl_errno( $this->curlHandle );
			if ( $errno != CURLE_OK ) {
				$errstr = curl_error( $this->curlHandle );
				wfDebug( __METHOD__ . ": CURL error code $errno: $errstr\n" );
				$this->status->fatal( "CURL error code $errno: $errstr\n" ); /* i18n? */
			}
		}

		curl_close( $this->curlHandle );

		return $this->status;
	}

	public function read( $curlH, $content ) {
		$this->content .= $content;
		return strlen( $content );
	}

	public function getCode() {
		# Don't return truncated output
		$code = curl_getinfo( $this->curlHandle, CURLINFO_HTTP_CODE );
		if ( $code < 400 ) {
			$this->status->setResult( true, $code );
		} else {
			$this->status->setResult( false, $code );
		}
	}
}

class PhpHttpRequest extends HttpRequest {
	private $reqHeaders;
	private $callback;
	private $fh;

	public function initRequest() {
		$this->setCallback( array( $this, 'read' ) );

		$this->reqHeaders[] = "User-Agent: " . Http::userAgent();
		$this->reqHeaders[] = "Accept: */*";
		if ( $this->method == 'POST' ) {
			// Required for HTTP 1.0 POSTs
			$this->reqHeaders[] = "Content-Length: " . strlen( $this->postdata );
			$this->reqHeaders[] = "Content-type: application/x-www-form-urlencoded";
		}

		if( $this->parsed_url['scheme'] != 'http' ) {
			$this->status->fatal( "Only http:// is supported currently." );
	    }
	}

	protected function urlToTcp($url) {
		$parsed_url = parse_url($url);

		return 'tcp://'.$parsed_url['host'].':'.$parsed_url['port'];
	}

	public function proxySetup() {
		global $wgHTTPProxy;

		if ( Http::isLocalURL( $this->url ) ) {
			$this->proxy = 'http://localhost:80/';
		} elseif ( $wgHTTPProxy ) {
			$this->proxy = $wgHTTPProxy ;
		}
	}

	public function setReferrer( $url ) {
		$this->reqHeaders[] = "Referer: $url";
	}

	public function setCallback( $cb ) {
		$this->callback = $cb;
	}

	public function read( $fh, $contents ) {
		if ( $this->headersOnly ) {
			return false;
		}
		$this->content .= $contents;

        return strlen( $contents );
	}

	public function execute() {
		if( !$this->status->isOK() ) {
			return $this->status;
		}

		$opts = array();
		if ( $this->proxy && !$this->no_proxy ) {
			$opts['proxy'] = $this->urlToTCP($this->proxy);
			$opts['request_fulluri'] = true;
		}

		$opts['method'] = $this->method;
		$opts['timeout'] = $this->timeout;
		$opts['header'] = implode( "\r\n", $this->reqHeaders );
		// FOR NOW: Force everyone to HTTP 1.0
		/* if ( version_compare( "5.3.0", phpversion(), ">" ) ) { */
			$opts['protocol_version'] = "1.0";
		/* } else { */
		/* 	$opts['protocol_version'] = "1.1"; */
		/* } */

		if ( $this->postdata ) {
			$opts['content'] = $this->postdata;
		}

		$context = stream_context_create( array( 'http' => $opts ) );
		try {
			$this->fh = fopen( $this->url, "r", false, $context );
		} catch (Exception $e) {
			$this->status->fatal($e->getMessage());
			return $this->status;
		}

		$result = stream_get_meta_data( $this->fh );
		if ( $result['timed_out'] ) {
			$this->status->error( __CLASS__ . '::timed-out-in-headers' );
		}

		$this->headers = $result['wrapper_data'];

		$end = false;
		while ( !$end ) {
			$contents = fread( $this->fh, 8192 );
			$size = call_user_func_array( $this->callback, array( $this->fh, $contents ) );
			$end = ( $size == 0 )  || feof( $this->fh );
		}
		fclose( $this->fh );

		return $this->status;
	}
}
