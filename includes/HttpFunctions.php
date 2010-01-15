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
		$status = $req->doRequest();
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
	/**
	 * Fetch a URL, write the result to a file.
	 * @params $url string url to fetch
	 * @params $targetFilePath string full path (including filename) to write the file to
	 * @params $async bool whether the download should be asynchronous (defaults to false)
	 * @params $redirectCount int used internally to keep track of the number of redirects
	 *
	 * @returns Status -- for async requests this will contain the request key
	 */
	public static function doDownload( $url, $targetFilePath, $async = false, $redirectCount = 0 ) {
		global $wgPhpCli, $wgMaxUploadSize, $wgMaxRedirects;

		// do a quick check to HEAD to insure the file size is not > $wgMaxUploadSize
		$headRequest = HttpRequest::factory( $url, array( 'headersOnly' => true ) );
		$headResponse = $headRequest->doRequest();
		if ( !$headResponse->isOK() ) {
			return $headResponse;
		}
		$head = $headResponse->value;

		// check for redirects:
		if ( $redirectCount < 0 ) {
			$redirectCount = 0;
		}
		if ( isset( $head['Location'] ) && strrpos( $head[0], '302' ) !== false ) {
			if ( $redirectCount < $wgMaxRedirects ) {
				if ( self::isValidURI( $head['Location'] ) ) {
					return self::doDownload( $head['Location'], $targetFilePath,
											 $async, $redirectCount++ );
				} else {
					return Status::newFatal( 'upload-proto-error' );
				}
			} else {
				return Status::newFatal( 'upload-too-many-redirects' );
			}
		}
		// we did not get a 200 ok response:
		if ( strrpos( $head[0], '200 OK' ) === false ) {
			return Status::newFatal( 'upload-http-error', htmlspecialchars( $head[0] ) );
		}

		$contentLength = $head['Content-Length'];
		if ( $contentLength ) {
			if ( $contentLength > $wgMaxUploadSize ) {
				return Status::newFatal( 'requested file length ' . $contentLength .
										 ' is greater than $wgMaxUploadSize: ' . $wgMaxUploadSize );
			}
		}

		// check if we can find phpCliPath (for doing a background shell request to
		// php to do the download:
		if ( $async && $wgPhpCli && wfShellExecEnabled() ) {
			wfDebug( __METHOD__ . "\nASYNC_DOWNLOAD\n" );
			// setup session and shell call:
			return self::startBackgroundRequest( $url, $targetFilePath, $contentLength );
		} else {
			wfDebug( __METHOD__ . "\nSYNC_DOWNLOAD\n" );
			// SYNC_DOWNLOAD download as much as we can in the time we have to execute
			$opts['method'] = 'GET';
			$opts['targetFilePath'] = $mTargetFilePath;
			$req = HttpRequest::factory( $url, $opts );
			return $req->doRequest();
		}
	}

	/**
	 * Start backgrounded (i.e. non blocking) request.	The
	 * backgrounded request will provide updates to the user's session
	 * data.
	 * @param $url string the URL to download
	 * @param $targetFilePath string the destination for the downloaded file
	 * @param $contentLength int (optional) the length of the download from the HTTP header
	 *
	 * @returns Status
	 */
	private static function startBackgroundRequest( $url, $targetFilePath, $contentLength = null ) {
		global $IP, $wgPhpCli, $wgServer;
		$status = Status::newGood();

		// generate a session id with all the details for the download (pid, targetFilePath )
		$requestKey = self::createRequestKey();
		$sessionID = session_id();

		// store the url and target path:
		$_SESSION['wsBgRequest'][$requestKey]['url'] = $url;
		$_SESSION['wsBgRequest'][$requestKey]['targetFilePath'] = $targetFilePath;
		// since we request from the cmd line we lose the original host name pass in the session:
		$_SESSION['wsBgRequest'][$requestKey]['orgServer'] = $wgServer;

		if ( $contentLength ) {
			$_SESSION['wsBgRequest'][$requestKey]['contentLength'] = $contentLength;
		}

		// set initial loaded bytes:
		$_SESSION['wsBgRequest'][$requestKey]['loaded'] = 0;

		// run the background download request:
		$cmd = $wgPhpCli . ' ' . $IP . "/maintenance/httpSessionDownload.php " .
			"--sid {$sessionID} --usk {$requestKey} --wiki " . wfWikiId();
		$pid = wfShellBackgroundExec( $cmd );
		// the pid is not of much use since we won't be visiting this same apache any-time soon.
		if ( !$pid )
			return Status::newFatal( 'http-could-not-background' );

		// update the status value with the $requestKey (for the user to
		// check on the status of the download)
		$status->value = $requestKey;

		// return good status
		return $status;
	}

	/**
	 * Returns a unique, random string that can be used as an request key and
	 * preloads it into the session data.
	 *
	 * @returns string
	 */
	static function createRequestKey() {
		if ( !array_key_exists( 'wsBgRequest', $_SESSION ) ) {
			$_SESSION['wsBgRequest'] = array();
		}

		$key = uniqid( 'bgrequest', true );

		// This is probably over-defensive.
		while ( array_key_exists( $key, $_SESSION['wsBgRequest'] ) ) {
			$key = uniqid( 'bgrequest', true );
		}
		$_SESSION['wsBgRequest'][$key] = array();

		return $key;
	}

	/**
	 * Recover the necessary session and request information
	 * @param $sessionID string
	 * @param $requestKey string the HTTP request key
	 *
	 * @returns array request information
	 */
	private static function recoverSession( $sessionID, $requestKey ) {
		global $wgUser, $wgServer, $wgSessionsInMemcached;

		// set session to the provided key:
		session_id( $sessionID );
		// fire up mediaWiki session system:
		wfSetupSession();

		// start the session
		if ( session_start() === false ) {
			wfDebug( __METHOD__ . ' could not start session' );
		}
		// get all the vars we need from session_id
		if ( !isset( $_SESSION[ 'wsBgRequest' ][ $requestKey ] ) ) {
			wfDebug(	__METHOD__ . ' Error:could not find upload session' );
			exit();
		}
		// setup the global user from the session key we just inherited
		$wgUser = User::newFromSession();

		// grab the session data to setup the request:
		$sd =& $_SESSION['wsBgRequest'][$requestKey];

		// update the wgServer var ( since cmd line thinks we are localhost
		// when we are really orgServer)
		if ( isset( $sd['orgServer'] ) && $sd['orgServer'] ) {
			$wgServer = $sd['orgServer'];
		}
		// close down the session so we can other http queries can get session
		// updates: (if not $wgSessionsInMemcached)
		if ( !$wgSessionsInMemcached ) {
			session_write_close();
		}

		return $sd;
	}

	/**
	 * Update the session with the finished information.
	 * @param $sessionID string
	 * @param $requestKey string the HTTP request key
	 */
	private static function updateSession( $sessionID, $requestKey, $status ) {

		if ( session_start() === false ) {
			wfDebug( __METHOD__ . ' ERROR:: Could not start session' );
		}

		$sd =& $_SESSION['wsBgRequest'][$requestKey];
		if ( !$status->isOK() ) {
			$sd['apiUploadResult'] = FormatJson::encode(
				array( 'error' => $status->getWikiText() )
			);
		} else {
			$sd['apiUploadResult'] = FormatJson::encode( $status->value );
		}

		session_write_close();
	}

	/**
	 * Take care of the downloaded file
	 * @param $sd array
	 * @param $status Status
	 *
	 * @returns Status
	 */
	private static function doFauxRequest( $sd, $status ) {
		global $wgEnableWriteAPI;

		if ( $status->isOK() ) {
			$fauxReqData = $sd['mParams'];

			// Fix boolean parameters
			foreach ( $fauxReqData as $k => $v ) {
				if ( $v === false )
					unset( $fauxReqData[$k] );
			}

			$fauxReqData['action'] = 'upload';
			$fauxReqData['format'] = 'json';
			$fauxReqData['internalhttpsession'] = $requestKey;

			// evil but no other clean way about it:
			$fauxReq = new FauxRequest( $fauxReqData, true );
			$processor = new ApiMain( $fauxReq, $wgEnableWriteAPI );

			// init the mUpload var for the $processor
			$processor->execute();
			$processor->getResult()->cleanUpUTF8();
			$printer = $processor->createPrinterByName( 'json' );
			$printer->initPrinter( false );
			ob_start();
			$printer->execute();

			// the status updates runner will grab the result form the session:
			$status->value = ob_get_clean();
		}
		return $status;
	}

	/**
	 * Run a session based download.
	 *
	 * @param $sessionID string: the session id with the download details
	 * @param $requestKey string: the key of the given upload session
	 *	(a given client could have started a few http uploads at once)
	 */
	public static function doSessionIdDownload( $sessionID, $requestKey ) {
		global $wgAsyncHTTPTimeout;

		wfDebug( __METHOD__ . "\n\n doSessionIdDownload :\n\n" );
		$sd = self::recoverSession( $sessionID );
		$req = HttpRequest::factory( $sd['url'],
									 array(
										 'targetFilePath'	  => $sd['targetFilePath'],
										 'requestKey'		  => $requestKey,
										 'timeout'			  => $wgAsyncHTTPTimeout,
									 ) );

		// run the actual request .. (this can take some time)
		wfDebug( __METHOD__ . 'do Session Download :: ' . $sd['url'] . ' tf: ' .
				 $sd['targetFilePath'] . "\n\n" );
		$status = $req->doRequest();

		self::updateSession( $sessionID, $requestKey,
							 self::handleFauxResponse( $sd, $status ) );
	}
}

/**
 * This wrapper class will call out to curl (if available) or fallback
 * to regular PHP if necessary for handling internal HTTP requests.
 */
class HttpRequest {
	private $targetFilePath;
	private $requestKey;
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
	function __construct( $url = null, $opt ) {
		global $wgHTTPTimeout;

		$this->url = $url;

		if ( !ini_get( 'allow_url_fopen' ) ) {
			$this->status = Status::newFatal( 'allow_url_fopen needs to be enabled for http copy to work' );
		} elseif ( !Http::isValidURI( $this->url ) ) {
			$this->status = Status::newFatal( 'bad-url' );
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
		global $wgForceHTTPEngine;

		if ( function_exists( 'curl_init' ) && $wgForceHTTPEngine == "curl" ) {
			return new CurlHttpRequest( $url, $opt );
		} else {
			return new PhpHttpRequest( $url, $opt );
		}
	}

	public function getContent() {
		return $this->content;
	}

	public function handleOutput() {
		// if we wrote to a target file close up or return error
		if ( $this->targetFilePath ) {
			$this->writer->close();
			if ( !$this->writer->status->isOK() ) {
				$this->status = $this->writer->status;
				return $this->status;
			}
		}
	}

	public function doRequest() {
		global $wgTitle;

		if ( !$this->status->isOK() ) {
			return $this->status;
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

		$this->setupOutputHandler();

		if ( $this->status->isOK() ) {
			$this->spinTheWheel();
		}

		if ( !$this->status->isOK() ) {
			return $this->status;
		}

		$this->handleOutput();

		$this->finish();
		return $this->status;
	}

	public function setupOutputHandler() {
		if ( $this->targetFilePath ) {
			$this->writer = new SimpleFileWriter( $this->targetFilePath,
												  $this->requestKey );
			if ( !$this->writer->status->isOK() ) {
				wfDebug( __METHOD__ . "ERROR in setting up SimpleFileWriter\n" );
				$this->status = $this->writer->status;
				return $this->status;
			}
			$this->setCallback( array( $this, 'readAndSave' ) );
		} else {
			$this->setCallback( array( $this, 'readOnly' ) );
		}
	}
	
	public function setReferrer( $url ) {  }

}

/**
 * HttpRequest implemented using internal curl compiled into PHP
 */
class CurlHttpRequest extends HttpRequest {
	private $c;

	public function initRequest() {
		$this->c = curl_init( $this->url );
	}

	public function proxySetup() {
		global $wgHTTPProxy;

		if ( is_string( $this->proxy ) ) {
			curl_setopt( $this->c, CURLOPT_PROXY, $this->proxy );
		} else if ( Http::isLocalURL( $this->url ) ) { /* Not sure this makes any sense. */
			curl_setopt( $this->c, CURLOPT_PROXY, 'localhost:80' );
		} else if ( $wgHTTPProxy ) {
			curl_setopt( $this->c, CURLOPT_PROXY, $wgHTTPProxy );
		}
	}

	public function setCallback( $cb ) {
		curl_setopt( $this->c, CURLOPT_WRITEFUNCTION, $cb );
	}

	public function spinTheWheel() {
		curl_setopt( $this->c, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt( $this->c, CURLOPT_USERAGENT, Http::userAgent() );
		curl_setopt( $this->c, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );

		if ( $this->sslVerifyHost ) {
			curl_setopt( $this->c, CURLOPT_SSL_VERIFYHOST, $this->sslVerifyHost );
		}

		if ( $this->caInfo ) {
			curl_setopt( $this->c, CURLOPT_CAINFO, $this->caInfo );
		}

		if ( $this->headersOnly ) {
			curl_setopt( $this->c, CURLOPT_NOBODY, true );
			curl_setopt( $this->c, CURLOPT_HEADER, true );
		} elseif ( $this->method == 'POST' ) {
			curl_setopt( $this->c, CURLOPT_POST, true );
			curl_setopt( $this->c, CURLOPT_POSTFIELDS, $this->postdata );
			// Suppress 'Expect: 100-continue' header, as some servers
			// will reject it with a 417 and Curl won't auto retry
			// with HTTP 1.0 fallback
			curl_setopt( $this->c, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
		} else {
			curl_setopt( $this->c, CURLOPT_CUSTOMREQUEST, $this->method );
		}

		try {
			if ( false === curl_exec( $this->c ) ) {
				$error_txt = 'Error sending request: #' . curl_errno( $this->c ) . ' ' . curl_error( $this->c );
				wfDebug( __METHOD__ . $error_txt . "\n" );
				$this->status->fatal( $error_txt ); /* i18n? */
			}
		} catch ( Exception $e ) {
			$errno = curl_errno( $this->c );
			if ( $errno != CURLE_OK ) {
				$errstr = curl_error( $this->c );
				wfDebug( __METHOD__ . ": CURL error code $errno: $errstr\n" );
				$this->status->fatal( "CURL error code $errno: $errstr\n" ); /* i18n? */
			}
		}
	}

	public function readOnly( $curlH, $content ) {
		$this->content .= $content;
		return strlen( $content );
	}

	public function readAndSave( $curlH, $content ) {
		return $this->writer->write( $content );
	}

	public function getCode() {
		# Don't return truncated output
		$code = curl_getinfo( $this->c, CURLINFO_HTTP_CODE );
		if ( $code < 400 ) {
			$this->status->setResult( true, $code );
		} else {
			$this->status->setResult( false, $code );
		}
	}

	public function finish() {
		curl_close( $this->c );
	}

}

class PhpHttpRequest extends HttpRequest {
	private $reqHeaders;
	private $callback;
	private $fh;

	public function initRequest() {
		$this->reqHeaders[] = "User-Agent: " . Http::userAgent();
		$this->reqHeaders[] = "Accept: */*";
		if ( $this->method == 'POST' ) {
			// Required for HTTP 1.0 POSTs
			$this->reqHeaders[] = "Content-Length: " . strlen( $this->postdata );
			$this->reqHeaders[] = "Content-type: application/x-www-form-urlencoded";
		}
	}

	public function proxySetup() {
		global $wgHTTPProxy;

		if ( $this->proxy ) {
			$this->proxy = 'tcp://' . $this->proxy;
		} elseif ( Http::isLocalURL( $this->url ) ) {
			$this->proxy = 'tcp://localhost:80';
		} elseif ( $wgHTTPProxy ) {
			$this->proxy = 'tcp://' . $wgHTTPProxy ;
		}
	}

	public function setReferrer( $url ) {
		$this->reqHeaders[] = "Referer: $url";
	}

	public function setCallback( $cb ) {
		$this->callback = $cb;
	}

	public function readOnly( $contents ) {
		if ( $this->headersOnly ) {
			return false;
		}
		$this->content .= $contents;

        return strlen( $contents );
	}

	public function readAndSave( $contents ) {
		if ( $this->headersOnly ) {
			return false;
		}
		return $this->writer->write( $content );
	}

	public function finish() {
		fclose( $this->fh );
	}

	public function spinTheWheel() {
		$opts = array();
		if ( $this->proxy && !$this->no_proxy ) {
			$opts['proxy'] = $this->proxy;
			$opts['request_fulluri'] = true;
		}

		$opts['method'] = $this->method;
		$opts['timeout'] = $this->timeout;
		$opts['header'] = implode( "\r\n", $this->reqHeaders );
		if ( version_compare( "5.3.0", phpversion(), ">" ) ) {
			$opts['protocol_version'] = "1.0";
		} else {
			$opts['protocol_version'] = "1.1";
		}

		if ( $this->postdata ) {
			$opts['content'] = $this->postdata;
		}

		$context = stream_context_create( array( 'http' => $opts ) );
		$this->fh = fopen( $this->url, "r", false, $context );
		$result = stream_get_meta_data( $this->fh );

		if ( $result['timed_out'] ) {
			$this->status->error( __CLASS__ . '::timed-out-in-headers' );
		}

		$this->headers = $result['wrapper_data'];

		$end = false;
		$size = 8192;
		while ( !$end ) {
			$contents = fread( $this->fh, $size );
			$size = call_user_func( $this->callback, $contents );
			$end = ( $size == 0 )  || feof( $this->fh );
		}
	}
}

/**
 * SimpleFileWriter with session id updates
 */
class SimpleFileWriter {
	private $targetFilePath = null;
	private $status = null;
	private $sessionId = null;
	private $sessionUpdateInterval = 0; // how often to update the session while downloading
	private $currentFileSize = 0;
	private $requestKey = null;
	private $prevTime = 0;
	private $fp = null;

	/**
	 * @param $targetFilePath string the path to write the file out to
	 * @param $requestKey string the request to update
	 */
	function __construct__( $targetFilePath, $requestKey ) {
		$this->targetFilePath = $targetFilePath;
		$this->requestKey = $requestKey;
		$this->status = Status::newGood();
		// open the file:
		$this->fp = fopen( $this->targetFilePath, 'w' );
		if ( $this->fp === false ) {
			$this->status = Status::newFatal( 'HTTP::could-not-open-file-for-writing' );
		}
		// true start time
		$this->prevTime = time();
	}

	public function write( $dataPacket ) {
		global $wgMaxUploadSize, $wgLang;

		if ( !$this->status->isOK() ) {
			return false;
		}

		// write out the content
		if ( fwrite( $this->fp, $dataPacket ) === false ) {
			wfDebug( __METHOD__ . " ::could-not-write-to-file\n" );
			$this->status = Status::newFatal( 'HTTP::could-not-write-to-file' );
			return false;
		}

		// check file size:
		clearstatcache();
		$this->currentFileSize = filesize( $this->targetFilePath );

		if ( $this->currentFileSize > $wgMaxUploadSize ) {
			wfDebug( __METHOD__ . " ::http-download-too-large\n" );
			$this->status = Status::newFatal( 'HTTP::file-has-grown-beyond-upload-limit-killing: ' . /* i18n? */
											  'downloaded more than ' .
											  $wgLang->formatSize( $wgMaxUploadSize ) . ' ' );
			return false;
		}
		// if more than session_update_interval second have passed updateProgress
		if ( $this->requestKey &&
			( ( time() - $this->prevTime ) > $this->sessionUpdateInterval ) ) {
			$this->prevTime = time();
			$session_status = $this->updateProgress();
			if ( !$session_status->isOK() ) {
				$this->status = $session_status;
				wfDebug( __METHOD__ . ' update session failed or was canceled' );
				return false;
			}
		}
		return strlen( $dataPacket );
	}

	public function updateProgress() {
		global $wgSessionsInMemcached;

		// start the session (if necessary)
		if ( !$wgSessionsInMemcached ) {
			wfSuppressWarnings();
			if ( session_start() === false ) {
				wfDebug( __METHOD__ . ' could not start session' );
				exit( 0 );
			}
			wfRestoreWarnings();
		}
		$sd =& $_SESSION['wsBgRequest'][ $this->requestKey ];
		// check if the user canceled the request:
		if ( $sd['userCancel'] ) {
			// @@todo kill the download
			return Status::newFatal( 'user-canceled-request' );
		}
		// update the progress bytes download so far:
		$sd['loaded'] = $this->currentFileSize;

		// close down the session so we can other http queries can get session updates:
		if ( !$wgSessionsInMemcached )
			session_write_close();

		return Status::newGood();
	}

	public function close() {
		$this->updateProgress();

		// close up the file handle:
		if ( false === fclose( $this->fp ) ) {
			$this->status = Status::newFatal( 'HTTP::could-not-close-file' );
		}
	}

}
