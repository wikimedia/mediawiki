<?php
/**
 * @file
 * @ingroup upload
 *
 * Implements uploading from a HTTP resource.
 *
 * @author Bryan Tong Minh
 * @author Michael Dale
 */
class UploadFromUrl extends UploadBase {
	protected $mTempDownloadPath;

	/**
	 * Checks if the user is allowed to use the upload-by-URL feature. If the
	 * user is allowed, pass on permissions checking to the parent.
	 */
	public static function isAllowed( $user ) {
		if( !$user->isAllowed( 'upload_by_url' ) )
			return 'upload_by_url';
		return parent::isAllowed( $user );
	}

	/**
	 * Checks if the upload from URL feature is enabled
	 */
	public static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled();
	}

	/**
	 * Entry point for API upload
	 */
	public function initialize( $name, $url, $na, $nb = false ) {
		global $wgTmpDirectory;

		$localFile = tempnam( $wgTmpDirectory, 'WEBUPLOAD' );
		$this->initializePathInfo( $name, $localFile, 0, true );

		$this->mUrl = trim( $url );
	}

	/**
	 * Entry point for SpecialUpload
	 * @param $request Object: WebRequest object
	 */
	public function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFileURL' );
		return $this->initialize(
			$desiredDestName,
			$request->getVal( 'wpUploadFileURL' ),
			false
		);
	}

	/**
	 * @param $request Object: WebRequest object
	 */
	public static function isValidRequest( $request ){
		if( !$request->getVal( 'wpUploadFileURL' ) )
			return false;
		// check that is a valid url:
		return self::isValidUrl( $request->getVal( 'wpUploadFileURL' ) );
	}

	public static function isValidUrl( $url ) {
		// Only allow HTTP or FTP for now
		return (bool)preg_match( '!^(http://|ftp://)!', $url );
	}

	/**
	 * Do the real fetching stuff
	 */
	function fetchFile() {
		if( !self::isValidUrl( $this->mUrl ) ) {
			return Status::newFatal( 'upload-proto-error' );
		}
		$res = $this->curlCopy();
		if( $res !== true ) {
			return Status::newFatal( $res );
		}
		return Status::newGood();
	}

	/**
	 * Safe copy from URL
	 * Returns true if there was an error, false otherwise
	 */
	private function curlCopy() {
		global $wgOut;

		# Open temporary file
		$this->mCurlDestHandle = @fopen( $this->mTempPath, "wb" );
		if( $this->mCurlDestHandle === false ) {
			# Could not open temporary file to write in
			return 'upload-file-error';
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, 1.0); # Probably not needed, but apparently can work around some bug
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10); # 10 seconds timeout
		curl_setopt( $ch, CURLOPT_LOW_SPEED_LIMIT, 512); # 0.5KB per second minimum transfer speed
		curl_setopt( $ch, CURLOPT_URL, $this->mUrl);
		curl_setopt( $ch, CURLOPT_WRITEFUNCTION, array( $this, 'uploadCurlCallback' ) );
		curl_exec( $ch );
		$error =  curl_errno( $ch );
		curl_close( $ch );

		fclose( $this->mCurlDestHandle );
		unset( $this->mCurlDestHandle );

		if( $error )
			return "upload-curl-error$errornum";

		return true;
	}

	/**
	 * Callback function for CURL-based web transfer
	 * Write data to file unless we've passed the length limit;
	 * if so, abort immediately.
	 * @access private
	 */
	function uploadCurlCallback( $ch, $data ) {
		global $wgMaxUploadSize;
		$length = strlen( $data );
		$this->mFileSize += $length;
		if( $this->mFileSize > $wgMaxUploadSize ) {
			return 0;
		}
		fwrite( $this->mCurlDestHandle, $data );
		return $length;
	}
}
