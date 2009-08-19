<?php
/**
 * HTTP handling class
 * @defgroup HTTP HTTP
 * @file
 * @ingroup HTTP
 */

class Http {
	const SYNC_DOWNLOAD = 1;  // syncronys upload (in a single request)
	const ASYNC_DOWNLOAD = 2; // asynchronous upload we should spawn out another process and monitor progress if possible)

	var $body = '';

	public static function request( $method, $url, $opts = array() ){
		$opts['method'] = ( strtoupper( $method ) == 'GET' || strtoupper( $method ) == 'POST' ) ? strtoupper( $method ) : null;
		$req = new HttpRequest( $url, $opts );
		$status = $req->doRequest();
		if( $status->isOK() ){
			return $status->value;
		} else {
			wfDebug( 'http error: ' . $status->getWikiText() );
			return false;
		}
	}

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 */
	public static function get( $url, $timeout = false, $opts = array() ) {
		global $wgSyncHTTPTimeout;
		if( $timeout )
			$opts['timeout'] = $timeout;
		return Http::request( 'GET', $url, $opts );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 */
	public static function post( $url, $opts = array() ) {
		return Http::request( 'POST', $url, $opts );
	}

	public static function doDownload( $url, $target_file_path, $dl_mode = self::SYNC_DOWNLOAD, $redirectCount = 0 ){
		global $wgPhpCli, $wgMaxUploadSize, $wgMaxRedirects;
		// do a quick check to HEAD to insure the file size is not > $wgMaxUploadSize
		$headRequest = new HttpRequest( $url, array( 'headers_only' => true ) );
		$headResponse = $headRequest->doRequest();
		if( !$headResponse->isOK() ){
			return $headResponse;
		}
		$head = $headResponse->value;

		// check for redirects:
		if( isset( $head['Location'] ) && strrpos( $head[0], '302' ) !== false ){
			if( $redirectCount < $wgMaxRedirects ){
				if( UploadFromUrl::isValidURI( $head['Location'] ) ){
					return self::doDownload( $head['Location'], $target_file_path, $dl_mode, $redirectCount++ );
				} else {
					return Status::newFatal( 'upload-proto-error' );
				}
			} else {
				return Status::newFatal( 'upload-too-many-redirects' );
			}
		}
		// we did not get a 200 ok response:
		if( strrpos( $head[0], '200 OK' ) === false ){
			return Status::newFatal( 'upload-http-error', htmlspecialchars( $head[0] ) );
		}

		$content_length = ( isset( $head['Content-Length'] ) ) ? $head['Content-Length'] : null;
		if( $content_length ){
			if( $content_length > $wgMaxUploadSize ){
				return Status::newFatal( 'requested file length ' . $content_length . ' is greater than $wgMaxUploadSize: ' . $wgMaxUploadSize );
			}
		}

		// check if we can find phpCliPath (for doing a background shell request to php to do the download:
		if( $wgPhpCli && wfShellExecEnabled() && $dl_mode == self::ASYNC_DOWNLOAD ){
			wfDebug( __METHOD__ . "\ASYNC_DOWNLOAD\n" );
			//setup session and shell call:
			return self::initBackgroundDownload( $url, $target_file_path, $content_length );
		} else {
			wfDebug( __METHOD__ . "\nSYNC_DOWNLOAD\n" );
			// SYNC_DOWNLOAD download as much as we can in the time we have to execute
			$opts['method'] = 'GET';
			$opts['target_file_path'] = $target_file_path;
			$req = new HttpRequest( $url, $opts );
			return $req->doRequest();
		}
	}

	/**
	 * a non blocking request (generally an exit point in the application)
	 * should write to a file location and give updates
	 *
	 */
	private static function initBackgroundDownload( $url, $target_file_path, $content_length = null ){
		global $wgMaxUploadSize, $IP, $wgPhpCli, $wgServer;
		$status = Status::newGood();

		// generate a session id with all the details for the download (pid, target_file_path )
		$upload_session_key = self::getUploadSessionKey();
		$session_id = session_id();

		// store the url and target path:
		$_SESSION['wsDownload'][$upload_session_key]['url'] = $url;
		$_SESSION['wsDownload'][$upload_session_key]['target_file_path'] = $target_file_path;
		// since we request from the cmd line we lose the original host name pass in the session:
		$_SESSION['wsDownload'][$upload_session_key]['orgServer'] = $wgServer;

		if( $content_length )
			$_SESSION['wsDownload'][$upload_session_key]['content_length'] = $content_length;

		// set initial loaded bytes:
		$_SESSION['wsDownload'][$upload_session_key]['loaded'] = 0;

		// run the background download request:
		$cmd = $wgPhpCli . ' ' . $IP . "/maintenance/http_session_download.php --sid {$session_id} --usk {$upload_session_key}";
		$pid = wfShellBackgroundExec( $cmd );
		// the pid is not of much use since we won't be visiting this same apache any-time soon.
		if( !$pid )
			return Status::newFatal( 'could not run background shell exec' );

		// update the status value with the $upload_session_key (for the user to check on the status of the upload)
		$status->value = $upload_session_key;

		// return good status
		return $status;
	}

	static function getUploadSessionKey(){
		$key = mt_rand( 0, 0x7fffffff );
		$_SESSION['wsUploadData'][$key] = array();
		return $key;
	}

	/**
	 * used to run a session based download. Is initiated via the shell.
	 *
	 * @param $session_id String: the session id to grab download details from
	 * @param $upload_session_key String: the key of the given upload session
	 *  (a given client could have started a few http uploads at once)
	 */
	public static function doSessionIdDownload( $session_id, $upload_session_key ){
		global $wgUser, $wgEnableWriteAPI, $wgAsyncHTTPTimeout, $wgServer,
				$wgSessionsInMemcached, $wgSessionHandler, $wgSessionStarted;
		wfDebug( __METHOD__ . "\n\n doSessionIdDownload :\n\n" );
		// set session to the provided key:
		session_id( $session_id );
		//fire up mediaWiki session system:
		wfSetupSession();

		// start the session
		if( session_start() === false ){
			wfDebug( __METHOD__ . ' could not start session' );
		}
		// get all the vars we need from session_id
		if( !isset( $_SESSION[ 'wsDownload' ][$upload_session_key] ) ){
			wfDebug(  __METHOD__ . ' Error:could not find upload session');
			exit();
		}
		// setup the global user from the session key we just inherited
		$wgUser = User::newFromSession();

		// grab the session data to setup the request:
		$sd =& $_SESSION['wsDownload'][$upload_session_key];

		// update the wgServer var ( since cmd line thinks we are localhost when we are really orgServer)
		if( isset( $sd['orgServer'] ) && $sd['orgServer'] ){
			$wgServer = $sd['orgServer'];
		}
		// close down the session so we can other http queries can get session updates: (if not $wgSessionsInMemcached)
		if( !$wgSessionsInMemcached )
			session_write_close();

		$req = new HttpRequest( $sd['url'], array(
			'target_file_path'  => $sd['target_file_path'],
			'upload_session_key'=> $upload_session_key,
			'timeout'           => $wgAsyncHTTPTimeout,
			'do_close_session_update' => true
		) );
		// run the actual request .. (this can take some time)
		wfDebug( __METHOD__ . 'do Session Download :: ' . $sd['url'] . ' tf: ' . $sd['target_file_path'] . "\n\n");
		$status = $req->doRequest();
		//wfDebug("done with req status is: ". $status->isOK(). ' '.$status->getWikiText(). "\n");

		// start up the session again:
		if( session_start() === false ){
			wfDebug( __METHOD__ . ' ERROR:: Could not start session');
		}
		// grab the updated session data pointer
		$sd =& $_SESSION['wsDownload'][$upload_session_key];
		// if error update status:
		if( !$status->isOK() ){
			$sd['apiUploadResult'] = ApiFormatJson::getJsonEncode(
				array( 'error' => $status->getWikiText() )
			);
		}
		// if status okay process upload using fauxReq to api:
		if( $status->isOK() ){
			// setup the FauxRequest
			$fauxReqData = $sd['mParams'];

			// Fix boolean parameters
			foreach( $fauxReqData as $k => $v ) {
				if( $v === false )
					unset( $fauxReqData[$k] );
			}

			$fauxReqData['action'] = 'upload';
			$fauxReqData['format'] = 'json';
			$fauxReqData['internalhttpsession'] = $upload_session_key;
			// evil but no other clean way about it:
			$faxReq = new FauxRequest( $fauxReqData, true );
			$processor = new ApiMain( $faxReq, $wgEnableWriteAPI );

			//init the mUpload var for the $processor
			$processor->execute();
			$processor->getResult()->cleanUpUTF8();
			$printer = $processor->createPrinterByName( 'json' );
			$printer->initPrinter( false );
			ob_start();
			$printer->execute();
			$apiUploadResult = ob_get_clean();

			// the status updates runner will grab the result form the session:
			$sd['apiUploadResult'] = $apiUploadResult;
		}
		// close the session:
		session_write_close();
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
	 * Return a standard user-agent we can use for external requests.
	 */
	public static function userAgent() {
		global $wgVersion;
		return "MediaWiki/$wgVersion";
	}
}

class HttpRequest {
	var $target_file_path;
	var $upload_session_key;
	var $supportedCurlOpts = array(
		'CURLOPT_SSL_VERIFYHOST',
		'CURLOPT_CAINFO',
		'CURLOPT_COOKIE',
		'CURLOPT_FOLLOWLOCATION',
		'CURLOPT_FAILONERROR'
	);
	function __construct( $url, $opt ){
		global $wgSyncHTTPTimeout;
		// double check that it's a valid url:
		$this->url = $url;

		// set the timeout to default sync timeout (unless the timeout option is provided)
		$this->timeout = ( isset( $opt['timeout'] ) ) ? $opt['timeout'] : $wgSyncHTTPTimeout;
		//check special key default
		if($this->timeout == 'default'){
			$opts['timeout'] = $wgSyncHTTPTimeout;
		}

		$this->method = ( isset( $opt['method'] ) ) ? $opt['method'] : 'GET';
		$this->target_file_path = ( isset( $opt['target_file_path'] ) ) ? $opt['target_file_path'] : false;
		$this->upload_session_key = ( isset( $opt['upload_session_key'] ) ) ? $opt['upload_session_key'] : false;
		$this->headers_only = ( isset( $opt['headers_only'] ) ) ? $opt['headers_only'] : false;
		$this->do_close_session_update = isset( $opt['do_close_session_update'] );
		$this->postData = isset( $opt['postdata'] ) ? $opt['postdata'] : '';

		$this->curlOpt = array();
		//check for some curl options:
		foreach($this->supportedCurlOpts as $curlOpt){
			if(isset($opt[ $curlOpt ])){
				$this->curlOpt[$curlOpt] = $opt[ $curlOpt ];
			}
		}
	}

	/**
	 * Get the contents of a file by HTTP
	 * @param $url string Full URL to act on
	 * @param $Opt associative array Optional array of options:
	 *     'method'           => 'GET', 'POST' etc.
	 *     'target_file_path' => if curl should output to a target file
	 *     'adapter'          => 'curl', 'soket'
	 */
	public function doRequest() {
		# Make sure we have a valid url
		if( !UploadFromUrl::isValidURI( $this->url ) )
			return Status::newFatal('bad-url');

		# Use curl if available
		if ( function_exists( 'curl_init' ) ) {
			return $this->doCurlReq();
		} else {
			return $this->doPhpReq();
		}
	}

	private function doCurlReq(){
		global $wgHTTPProxy, $wgTitle;

		$status = Status::newGood();
		$c = curl_init( $this->url );

		// proxy setup:
		if ( Http::isLocalURL( $this->url ) ) {
			curl_setopt( $c, CURLOPT_PROXY, 'localhost:80' );
		} else if ( $wgHTTPProxy ) {
			curl_setopt( $c, CURLOPT_PROXY, $wgHTTPProxy );
		}

		curl_setopt( $c, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt( $c, CURLOPT_USERAGENT, Http::userAgent() );

		//set any curl specific opts:
		foreach($this->curlOpt as $optKey => $optVal){
			curl_setopt($c, constant( $optKey ),  $optVal);
		}

		if ( $this->headers_only ) {
			curl_setopt( $c, CURLOPT_NOBODY, true );
			curl_setopt( $c, CURLOPT_HEADER, true );
		} elseif ( $this->method == 'POST' ) {
			curl_setopt( $c, CURLOPT_POST, true );
			curl_setopt( $c, CURLOPT_POSTFIELDS, $this->postData );
			// Suppress 'Expect: 100-continue' header, as some servers
			// will reject it with a 417 and Curl won't auto retry
			// with HTTP 1.0 fallback
			curl_setopt( $c, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
		} else {
			curl_setopt( $c, CURLOPT_CUSTOMREQUEST, $this->method );
		}

		# Set the referer to $wgTitle, even in command-line mode
		# This is useful for interwiki transclusion, where the foreign
		# server wants to know what the referring page is.
		# $_SERVER['REQUEST_URI'] gives a less reliable indication of the
		# referring page.
		if ( is_object( $wgTitle ) ) {
			curl_setopt( $c, CURLOPT_REFERER, $wgTitle->getFullURL() );
		}

		// set the write back function (if we are writing to a file)
		if( $this->target_file_path ){
			$cwrite = new simpleFileWriter( $this->target_file_path,
				$this->upload_session_key,
				$this->do_close_session_update
			);
			if( !$cwrite->status->isOK() ){
				wfDebug( __METHOD__ . "ERROR in setting up simpleFileWriter\n" );
				$status = $cwrite->status;
				return $status;
			}
			curl_setopt( $c, CURLOPT_WRITEFUNCTION, array( $cwrite, 'callbackWriteBody' ) );
		}

		// start output grabber:
		if( !$this->target_file_path )
			ob_start();

		//run the actual curl_exec:
		try {
			if ( false === curl_exec( $c ) ) {
				$error_txt ='Error sending request: #' . curl_errno( $c ) .' '. curl_error( $c );
				wfDebug( __METHOD__ . $error_txt . "\n" );
				$status = Status::newFatal( $error_txt );
			}
		} catch ( Exception $e ) {
			// do something with curl exec error?
		}
		// if direct request output the results to the stats value:
		if( !$this->target_file_path && $status->isOK() ){
			$status->value = ob_get_contents();
			ob_end_clean();
		}
		// if we wrote to a target file close up or return error
		if( $this->target_file_path ){
			$cwrite->close();
			if( !$cwrite->status->isOK() ){
				return $cwrite->status;
			}
		}

		if ( $this->headers_only ) {
			$headers = explode( "\n", $status->value );
			$headerArray = array();
			foreach ( $headers as $header ) {
				if ( !strlen( trim( $header ) ) )
					continue;
				$headerParts = explode( ':', $header, 2 );
				if ( count( $headerParts ) == 1 ) {
					$headerArray[] = trim( $header );
				} else {
					list( $key, $val ) = $headerParts;
					$headerArray[trim( $key )] = trim( $val );
				}
			}
			$status->value = $headerArray;
		} else {
			# Don't return the text of error messages, return false on error
			$retcode = curl_getinfo( $c, CURLINFO_HTTP_CODE );
			if ( $retcode != 200 ) {
				wfDebug( __METHOD__ . ": HTTP return code $retcode\n" );
				$status = Status::newFatal( "HTTP return code $retcode\n" );
			}
			# Don't return truncated output
			$errno = curl_errno( $c );
			if ( $errno != CURLE_OK ) {
				$errstr = curl_error( $c );
				wfDebug( __METHOD__ . ": CURL error code $errno: $errstr\n" );
				$status = Status::newFatal( " CURL error code $errno: $errstr\n" );
			}
		}

		curl_close( $c );

		// return the result obj
		return $status;
	}

	public function doPhpReq(){
		global $wgTitle, $wgHTTPProxy;
		# Check for php.ini allow_url_fopen
		if( !ini_get( 'allow_url_fopen' ) ){
			return Status::newFatal( 'allow_url_fopen needs to be enabled for http copy to work' );
		}

		// start with good status:
		$status = Status::newGood();

		if ( $this->headers_only ) {
			$status->value = get_headers( $this->url, 1 );
			return $status;
		}

		// setup the headers
		$headers = array( "User-Agent: " . Http::userAgent() );
		if ( is_object( $wgTitle ) ) {
			$headers[] = "Referer: ". $wgTitle->getFullURL();
		}

		if( strcasecmp( $this->method, 'post' ) == 0 ) {
			// Required for HTTP 1.0 POSTs
			$headers[] = "Content-Length: 0";
		}
		$fcontext = stream_context_create ( array(
			'http' => array(
				'method' => $this->method,
				'header' => implode( "\r\n", $headers ),
				'timeout' => $this->timeout )
			)
		);
		$fh = fopen( $this->url, "r", false, $fcontext);

		// set the write back function (if we are writing to a file)
		if( $this->target_file_path ){
			$cwrite = new simpleFileWriter( $this->target_file_path, $this->upload_session_key, $this->do_close_session_update );
			if( !$cwrite->status->isOK() ){
				wfDebug( __METHOD__ . "ERROR in setting up simpleFileWriter\n" );
				$status = $cwrite->status;
				return $status;
			}

			// read $fh into the simpleFileWriter (grab in 64K chunks since its likely a ~large~ media file)
			while ( !feof( $fh ) ) {
				$contents = fread( $fh, 65536 );
				$cwrite->callbackWriteBody( $fh, $contents );
			}
			$cwrite->close();
			// check for simpleFileWriter error:
			if( !$cwrite->status->isOK() ){
				return $cwrite->status;
			}
		} else {
			// read $fh into status->value
			$status->value = @stream_get_contents( $fh );
		}
		//close the url file wrapper
		fclose( $fh );

		// check for "false"
		if( $status->value === false ){
			$status->error( 'file_get_contents-failed' );
		}
		return $status;
	}

}

/**
 * a simpleFileWriter with session id updates
 */
class simpleFileWriter {
	var $target_file_path;
	var $status = null;
	var $session_id = null;
	var $session_update_interval = 0; // how often to update the session while downloading

	function simpleFileWriter( $target_file_path, $upload_session_key, $do_close_session_update = false ){
		$this->target_file_path = $target_file_path;
		$this->upload_session_key = $upload_session_key;
		$this->status = Status::newGood();
		$this->do_close_session_update = $do_close_session_update;
		// open the file:
		$this->fp = fopen( $this->target_file_path, 'w' );
		if( $this->fp === false ){
			$this->status = Status::newFatal( 'HTTP::could-not-open-file-for-writing' );
		}
		// true start time
		$this->prevTime = time();
	}

	public function callbackWriteBody( $ch, $data_packet ){
		global $wgMaxUploadSize, $wgLang;

		// write out the content
		if( fwrite( $this->fp, $data_packet ) === false ){
			wfDebug( __METHOD__ ." ::could-not-write-to-file\n" );
			$this->status = Status::newFatal( 'HTTP::could-not-write-to-file' );
			return 0;
		}

		// check file size:
		clearstatcache();
		$this->current_fsize = filesize( $this->target_file_path );

		if( $this->current_fsize > $wgMaxUploadSize ){
			wfDebug( __METHOD__ . " ::http download too large\n" );
			$this->status = Status::newFatal( 'HTTP::file-has-grown-beyond-upload-limit-killing: downloaded more than ' .
				$wgLang->formatSize( $wgMaxUploadSize ) . ' ' );
			return 0;
		}
		// if more than session_update_interval second have passed update_session_progress
		if( $this->do_close_session_update && $this->upload_session_key &&
			( ( time() - $this->prevTime ) > $this->session_update_interval ) ) {
				$this->prevTime = time();
				$session_status = $this->update_session_progress();
				if( !$session_status->isOK() ){
					$this->status = $session_status;
					wfDebug( __METHOD__ . ' update session failed or was canceled');
					return 0;
				}
		}
		return strlen( $data_packet );
	}

	public function update_session_progress(){
		global $wgSessionsInMemcached;
		$status = Status::newGood();
		// start the session (if necessary)
		if( !$wgSessionsInMemcached ){
			wfSuppressWarnings();
			if( session_start() === false ){
				wfDebug( __METHOD__ . ' could not start session' );
				exit( 0 );
			}
			wfRestoreWarnings();
		}
		$sd =& $_SESSION['wsDownload'][ $this->upload_session_key ];
		// check if the user canceled the request:
		if( isset( $sd['user_cancel'] ) && $sd['user_cancel'] == true ){
			//@@todo kill the download
			return Status::newFatal( 'user-canceled-request' );
		}
		// update the progress bytes download so far:
		$sd['loaded'] = $this->current_fsize;

		// close down the session so we can other http queries can get session updates:
		if( !$wgSessionsInMemcached )
			session_write_close();

		return $status;
	}

	public function close(){
		// do a final session update:
		if( $this->do_close_session_update ){
			$this->update_session_progress();
		}
		// close up the file handle:
		if( false === fclose( $this->fp ) ){
			$this->status = Status::newFatal( 'HTTP::could-not-close-file' );
		}
	}

}
