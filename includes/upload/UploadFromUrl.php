<?php

class UploadFromUrl extends UploadBase {
	protected $mTempDownloadPath;

	// by default do a SYNC_DOWNLOAD
	protected $dl_mode = null;

	/**
	 * Checks if the user is allowed to use the upload-by-URL feature
	 */
	static function isAllowed( $user ) {
		if( !$user->isAllowed( 'upload_by_url' ) )
			return 'upload_by_url';
		return parent::isAllowed( $user );
	}

	/**
	 * Checks if the upload from URL feature is enabled
	 */
	static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled();
	}

	/* entry point for API upload:: ASYNC_DOWNLOAD (if possible) */
	function initialize( $name, $url, $asyncdownload, $na = false ) {
		global $wgTmpDirectory, $wgPhpCli;

		// check for $asyncdownload request:
		if( $asyncdownload !== false){
			if( $wgPhpCli && wfShellExecEnabled() ){
				$this->dl_mode = Http::ASYNC_DOWNLOAD;
			} else {
				$this->dl_mode = Http::SYNC_DOWNLOAD;
			}
		}

		$local_file = tempnam( $wgTmpDirectory, 'WEBUPLOAD' );
		parent::initialize( $name, $local_file, 0, true );

		$this->mUrl = trim( $url );
	}

	public function isAsync(){
		return $this->dl_mode == Http::ASYNC_DOWNLOAD;
	}

	/**
	 * Entry point for SpecialUpload no ASYNC_DOWNLOAD possible
	 * @param $request Object: WebRequest object
	 */
	function initializeFromRequest( &$request ) {

		// set dl mode if not set:
		if( !$this->dl_mode )
			$this->dl_mode = Http::SYNC_DOWNLOAD;

		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );
		return $this->initialize(
			$desiredDestName,
	 		$request->getVal( 'wpUploadFileURL' ),
			false
		);
	}

	/**
	 * Do the real fetching stuff
	 */
	function fetchFile() {
		// entry point for SpecialUplaod
		if( self::isValidURI( $this->mUrl ) === false ) {
			return Status::newFatal( 'upload-proto-error' );
		}

		// now do the actual download to the target file:
		$status = Http::doDownload( $this->mUrl, $this->mTempPath, $this->dl_mode );

		// update the local filesize var:
		$this->mFileSize = filesize( $this->mTempPath );

		return $status;
	}

	/**
	 * @param $request Object: WebRequest object
	 */
	static function isValidRequest( $request ){
		if( !$request->getVal( 'wpUploadFileURL' ) )
			return false;
		// check that is a valid url:
		return self::isValidURI( $request->getVal( 'wpUploadFileURL' ) );
	}

	/**
	 * Checks that the given URI is a valid one
	 * @param $uri Mixed: URI to check for validity
	 */
	static function isValidURI( $uri ){
		return preg_match(
			'/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/',
			$uri,
			$matches
		);
	}

}