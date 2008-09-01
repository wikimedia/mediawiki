<?php


class UploadFromUrl extends UploadBase {
	static function isAllowed( User $user ) {
		if( !$user->isAllowed( 'upload_by_url' ) )
			return 'upload_by_url';
		return parent::isAllowed( $user );
	}
	static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled();
	}
	
	function initialize( $url ) {
		global $wgTmpDirectory;
		$local_file = tempnam( $wgTmpDirectory, 'WEBUPLOAD' );

		$this->mTempPath       = $local_file;
		$this->mFileSize       = 0; # Will be set by curlCopy
		$this->mCurlError      = $this->curlCopy( $url, $local_file );
		$pathParts             = explode( '/', $url );
		$this->mSrcName        = array_pop( $pathParts );
		$this->mSessionKey     = false;
		$this->mStashed        = false;

		// PHP won't auto-cleanup the file
		$this->mRemoveTempFile = file_exists( $local_file );
	}
	
	
		/**
	 * Safe copy from URL
	 * Returns true if there was an error, false otherwise
	 */
	private function curlCopy( $url, $dest ) {
		global $wgUser, $wgOut;

		// Bad bad bad!
		if( !$wgUser->isAllowed( 'upload_by_url' ) ) {
			$wgOut->permissionRequired( 'upload_by_url' );
			return true;
		}

		# Maybe remove some pasting blanks :-)
		$url =  trim( $url );
		if( stripos($url, 'http://') !== 0 && stripos($url, 'ftp://') !== 0 ) {
			# Only HTTP or FTP URLs
			$wgOut->showErrorPage( 'upload-proto-error', 'upload-proto-error-text' );
			return true;
		}

		# Open temporary file
		$this->mCurlDestHandle = @fopen( $this->mTempPath, "wb" );
		if( $this->mCurlDestHandle === false ) {
			# Could not open temporary file to write in
			$wgOut->showErrorPage( 'upload-file-error', 'upload-file-error-text');
			return true;
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, 1.0); # Probably not needed, but apparently can work around some bug
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10); # 10 seconds timeout
		curl_setopt( $ch, CURLOPT_LOW_SPEED_LIMIT, 512); # 0.5KB per second minimum transfer speed
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_WRITEFUNCTION, array( $this, 'uploadCurlCallback' ) );
		curl_exec( $ch );
		$error = curl_errno( $ch ) ? true : false;
		$errornum =  curl_errno( $ch );
		// if ( $error ) print curl_error ( $ch ) ; # Debugging output
		curl_close( $ch );

		fclose( $this->mCurlDestHandle );
		unset( $this->mCurlDestHandle );
		if( $error ) {
			unlink( $dest );
			if( wfEmptyMsg( "upload-curl-error$errornum", wfMsg("upload-curl-error$errornum") ) )
				$wgOut->showErrorPage( 'upload-misc-error', 'upload-misc-error-text' );
			else
				$wgOut->showErrorPage( "upload-curl-error$errornum", "upload-curl-error$errornum-text" );
		}

		return $error;
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
	
	function execute( &$resultDetails ) {
		/* Check for curl error */
		if( $this->mCurlError ) {
			return self::BEFORE_PROCESSING;
		}
		return parent::execute( $resultDetails );
	}
}
