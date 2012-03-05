<?php
/**
 * Implements regular file uploads
 *
 * @file
 * @ingroup upload
 * @author Bryan Tong Minh
 */

class UploadFromFile extends UploadBase {
	protected $mUpload = null;

	function initializeFromRequest( &$request ) {
		$upload = $request->getUpload( 'wpUploadFile' );		
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $upload->getName();
			
		return $this->initialize( $desiredDestName, $upload );
	}
	
	/**
	 * Initialize from a filename and a WebRequestUpload
	 */
	function initialize( $name, $webRequestUpload ) {
		$this->mUpload = $webRequestUpload;
		return $this->initializePathInfo( $name, 
			$this->mUpload->getTempName(), $this->mUpload->getSize() );
	}
	static function isValidRequest( $request ) {
		# Allow all requests, even if no file is present, so that an error
		# because a post_max_size or upload_max_filesize overflow
		return true;
	}
	
	public function verifyUpload() {
		# Check for a post_max_size or upload_max_size overflow, so that a 
		# proper error can be shown to the user
		if ( is_null( $this->mTempPath ) || $this->isEmptyFile() ) {
			if ( $this->mUpload->isIniSizeOverflow() ) {
				global $wgMaxUploadSize;
				return array( 
					'status' => self::FILE_TOO_LARGE,
					'max' => min( 
						$wgMaxUploadSize, 
						wfShorthandToInteger( ini_get( 'upload_max_filesize' ) ), 
						wfShorthandToInteger( ini_get( 'post_max_size' ) )
					),
				);
			}
		}
		
		return parent::verifyUpload();
	}
}
