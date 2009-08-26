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

	// by default do a SYNC_DOWNLOAD
	protected $dl_mode =  Http::SYNC_DOWNLOAD;

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
	 * Entry point for API upload:: ASYNC_DOWNLOAD (if possible) 
	 */
	public function initialize( $name, $url, $asyncdownload, $na = false ) {
		global $wgTmpDirectory, $wgPhpCli;

		// check for $asyncdownload request:
		if( $asyncdownload !== false){
			if( $wgPhpCli && wfShellExecEnabled() ){
				$this->dl_mode = Http::ASYNC_DOWNLOAD;
			} else {
				$this->dl_mode = Http::SYNC_DOWNLOAD;
			}
		}

		$localFile = tempnam( $wgTmpDirectory, 'WEBUPLOAD' );
		parent::initialize( $name, $localFile, 0, true );

		$this->mUrl = trim( $url );
	}

	public function isAsync(){
		return $this->dl_mode == Http::ASYNC_DOWNLOAD;
	}

	/**
	 * Entry point for SpecialUpload no ASYNC_DOWNLOAD possible
	 * @param $request Object: WebRequest object
	 */
	public function initializeFromRequest( &$request ) {
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
	public function fetchFile() {
		// Entry point for SpecialUpload
		if( Http::isValidURI( $this->mUrl ) === false ) {
			return Status::newFatal( 'upload-proto-error' );
		}

		// Now do the actual download to the target file:
		$status = Http::doDownload( $this->mUrl, $this->mTempPath, $this->dl_mode );

		// Update the local filesize var:
		$this->mFileSize = filesize( $this->mTempPath );

		return $status;
	}

	/**
	 * @param $request Object: WebRequest object
	 */
	public static function isValidRequest( $request ){
		if( !$request->getVal( 'wpUploadFileURL' ) )
			return false;
		// check that is a valid url:
		return Http::isValidURI( $request->getVal( 'wpUploadFileURL' ) );
	}



}