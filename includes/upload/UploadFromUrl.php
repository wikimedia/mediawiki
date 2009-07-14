<?php
class UploadFromUrl extends UploadBase {
	protected $mTempDownloadPath;
	
	//by default do a SYNC_DOWNLOAD 
	protected $dl_mode = null;
	
	static function isAllowed( $user ) {
		if( !$user->isAllowed( 'upload_by_url' ) )
			return 'upload_by_url';
		return parent::isAllowed( $user );
	}
	static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled();
	}	
	/*entry point for Api upload:: ASYNC_DOWNLOAD (if possible) */
	function initialize( $name, $url, $asyncdownload = false) {		
		global $wgTmpDirectory, $wgPhpCliPath;				
			
		//check for $asyncdownload request: 
		if($asyncdownload !== false){
			if($wgPhpCliPath && wfShellExecEnabled() ){
				$this->dl_mode = Http::ASYNC_DOWNLOAD;
			}else{
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
	/*entry point for SpecialUpload no ASYNC_DOWNLOAD possible: */
	function initializeFromRequest( &$request ) {		

		//set dl mode if not set:
		if(!$this->dl_mode)
			$this->dl_mode = Http::SYNC_DOWNLOAD;	
			
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );		
		return $this->initialize( 
			$desiredDestName, 
	 		$request->getVal('wpUploadFileURL')
		);
	}
	/**
	 * Do the real fetching stuff
	 */
	function fetchFile( ) {			
		//entry point for SpecialUplaod 
		if( self::isValidURI($this->mUrl) === false) {
			return Status::newFatal('upload-proto-error');
		}				
		//now do the actual download to the target file: 			
		$status = Http::doDownload ( $this->mUrl, $this->mTempPath, $this->dl_mode );						
		
		//update the local filesize var: 
		$this->mFileSize = filesize( $this->mTempPath );					
						
		return $status;					
	}
	
	static function isValidRequest( $request ){
		if( !$request->getVal('wpUploadFileURL') )
			return false;
		//check that is a valid url:
		return self::isValidURI( $request->getVal('wpUploadFileURL') );
	}
	static function isValidURI( $uri ){
		return preg_match('/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/',
						  $uri, $matches);
	}
}