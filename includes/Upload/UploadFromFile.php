<?php
/**
 * Backend for regular file upload.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Upload
 */

use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebRequestUpload;

/**
 * Implements regular file uploads
 *
 * @ingroup Upload
 * @author Bryan Tong Minh
 */
class UploadFromFile extends UploadBase {
	/**
	 * @var WebRequestUpload
	 */
	protected $mUpload = null;

	/**
	 * @param WebRequest &$request
	 */
	public function initializeFromRequest( &$request ) {
		$upload = $request->getUpload( 'wpUploadFile' );
		$desiredDestName = $request->getText( 'wpDestFile' );
		if ( !$desiredDestName ) {
			$desiredDestName = $upload->getName();
		}

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable getName only null on failure
		$this->initialize( $desiredDestName, $upload );
	}

	/**
	 * Initialize from a filename and a MediaWiki\Request\WebRequestUpload
	 * @param string $name
	 * @param WebRequestUpload $webRequestUpload
	 */
	public function initialize( $name, $webRequestUpload ) {
		$this->mUpload = $webRequestUpload;
		$this->initializePathInfo( $name,
			$this->mUpload->getTempName(), $this->mUpload->getSize() );
	}

	/**
	 * @param WebRequest $request
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
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
		if ( $this->mTempPath === null || $this->isEmptyFile() ) {
			if ( $this->mUpload->isIniSizeOverflow() ) {
				return [
					'status' => UploadBase::FILE_TOO_LARGE,
					'max' => min(
						self::getMaxUploadSize( $this->getSourceType() ),
						self::getMaxPhpUploadSize()
					),
				];
			}
		}

		return parent::verifyUpload();
	}
}
