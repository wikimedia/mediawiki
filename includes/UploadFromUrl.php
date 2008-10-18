<?php


class UploadFromUrl extends UploadBase {
	static function isAllowed( $user ) {
		if( !$user->isAllowed( 'upload_by_url' ) )
			return 'upload_by_url';
		return parent::isAllowed( $user );
	}
	static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled() && function_exists( 'curl_init' );
	}
	
	function initialize( $name, $url ) {
		global $wgTmpDirectory;
		$local_file = tempnam( $wgTmpDirectory, 'WEBUPLOAD' );
		$this-initialize( $name, $local_file, 0, true );

		$this->mUrl = trim( $url );
	}
	
	/**
	 * Do the real fetching stuff
	 */
	function fetchFile() {
		if( stripos($this->mUrl, 'http://') !== 0 && stripos($this->mUrl, 'ftp://') !== 0 ) {
			return array(
				'status' => self::BEFORE_PROCESSING,
				'error' => 'upload-proto-error',
			);
		}
		$res = $this->curlCopy();
		if( $res !== true ) {
			return array(
				'status' => self::BEFORE_PROCESSING,
				'error' => $res,
			);
		}
		return self::OK;
	}
	
	/**
	 * Safe copy from URL
	 * Returns true if there was an error, false otherwise
	 */
	private function curlCopy() {
		global $wgUser, $wgOut;

		# Open temporary file
		$this->mCurlDestHandle = @fopen( $this->mTempPath, "wb" );
		if( $this->mCurlDestHandle === false ) {
			# Could not open temporary file to write in
			return 'upload-file-error';
		}
		
		$opts = array(	CURLOPT_HTTP_VERSION => 1.0,
						CURLOPT_LOW_SPEED_LIMIT => 512,
						CURLOPT_WRITEFUNCTION => array( $this, 'uploadCurlCallback' )
						);
		Http::get( $this->mUrl, 10, $opts );
		
		fclose( $this->mCurlDestHandle );
		unset( $this->mCurlDestHandle );
		
		if( $this->curlErrno !== CURLE_OK ) 
			return "upload-curl-error" . $this->curlErrno;

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
		$this->curlErrno = curl_errno( $ch );
		return $length;
	}
}
