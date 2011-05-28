<?php
/**
 * Implements regular file uploads
 *
 * @file
 * @ingroup upload
 * @author Bryan Tong Minh
 */

class UploadFromFile extends UploadBase {

	/**
	 * @var WebRequestUpload
	 */
	protected $mUpload = null;

	/**
	 * @param $request WebRequest
	 */
	function initializeFromRequest( &$request ) {
		$upload = $request->getUpload( 'wpUploadFile' );		
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $upload->getName();
			
		return $this->initialize( $desiredDestName, $upload );
	}

	/**
	 * Initialize from a filename and a WebRequestUpload
	 * @param $name
	 * @param $webRequestUpload
	 */
	function initialize( $name, $webRequestUpload ) {
		$this->mUpload = $webRequestUpload;
		return $this->initializePathInfo( $name, 
			$this->mUpload->getTempName(), $this->mUpload->getSize() );
	}

	/**
	 * @param $request
	 * @return bool
	 */
	static function isValidRequest( $request ) {
		# Allow all requests, even if no file is present, so that an error
		# because a post_max_size or upload_max_filesize overflow
		return true;
	}

	/**
	 * @return string
	 */
	public function getSourceType() {
		return 'file';
	}

	/**
	 * @return array
	 */
	public function verifyUpload() {
		# Check for a post_max_size or upload_max_size overflow, so that a 
		# proper error can be shown to the user
		if ( is_null( $this->mTempPath ) || $this->isEmptyFile() ) {
			if ( $this->mUpload->isIniSizeOverflow() ) {
				return array( 
					'status' => UploadBase::FILE_TOO_LARGE,
					'max' => min( 
						self::getMaxUploadSize( $this->getSourceType() ), 
						wfShorthandToInteger( ini_get( 'upload_max_filesize' ) ), 
						wfShorthandToInteger( ini_get( 'post_max_size' ) )
					),
				);
			}
		}
		
		return parent::verifyUpload();
	}

	/** 
	 * Get the path to the file underlying the upload
	 * @return String path to file
	 */
	public function getFileTempname() {
		return $this->mUpload->getTempname();
	}
}
