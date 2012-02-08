<?php
/**
 * Implements uploading from a HTTP resource.
 *
 * @ingroup Upload
 * @author Bryan Tong Minh
 * @author Michael Dale
 */
class UploadFromUrl extends UploadBase {
	protected $mAsync, $mUrl;
	protected $mIgnoreWarnings = true;

	protected $mTempPath, $mTmpHandle;

	/**
	 * Checks if the user is allowed to use the upload-by-URL feature. If the
	 * user is allowed, pass on permissions checking to the parent.
	 *
	 * @param $user User
	 *
	 * @return bool
	 */
	public static function isAllowed( $user ) {
		if ( !$user->isAllowed( 'upload_by_url' ) ) {
			return 'upload_by_url';
		}
		return parent::isAllowed( $user );
	}

	/**
	 * Checks if the upload from URL feature is enabled
	 * @return bool
	 */
	public static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled();
	}

	/**
	 * Entry point for API upload
	 *
	 * @param $name string
	 * @param $url string
	 * @param $async mixed Whether the download should be performed
	 * asynchronous. False for synchronous, async or async-leavemessage for
	 * asynchronous download.
	 */
	public function initialize( $name, $url, $async = false ) {
		global $wgAllowAsyncCopyUploads;

		$this->mUrl = $url;
		$this->mAsync = $wgAllowAsyncCopyUploads ? $async : false;
		if ( $async ) {
			throw new MWException( 'Asynchronous copy uploads are no longer possible as of r81612.' );
		}

		$tempPath = $this->mAsync ? null : $this->makeTemporaryFile();
		# File size and removeTempFile will be filled in later
		$this->initializePathInfo( $name, $tempPath, 0, false );
	}

	/**
	 * Entry point for SpecialUpload
	 * @param $request WebRequest object
	 */
	public function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if ( !$desiredDestName ) {
			$desiredDestName = $request->getText( 'wpUploadFileURL' );
		}
		return $this->initialize(
			$desiredDestName,
			trim( $request->getVal( 'wpUploadFileURL' ) ),
			false
		);
	}

	/**
	 * @param $request WebRequest object
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
		global $wgUser;

		$url = $request->getVal( 'wpUploadFileURL' );
		return !empty( $url )
			&& Http::isValidURI( $url )
			&& $wgUser->isAllowed( 'upload_by_url' );
	}

	/**
	 * @return string
	 */
	public function getSourceType() { return 'url'; }

	/**
	 * @return Status
	 */
	public function fetchFile() {
		if ( !Http::isValidURI( $this->mUrl ) ) {
			return Status::newFatal( 'http-invalid-url' );
		}

		if ( !$this->mAsync ) {
			return $this->reallyFetchFile();
		}
		return Status::newGood();
	}
	/**
	 * Create a new temporary file in the URL subdirectory of wfTempDir().
	 *
	 * @return string Path to the file
	 */
	protected function makeTemporaryFile() {
		return tempnam( wfTempDir(), 'URL' );
	}

	/**
	 * Callback: save a chunk of the result of a HTTP request to the temporary file
	 *
	 * @param $req mixed
	 * @param $buffer string
	 * @return int number of bytes handled
	 */
	public function saveTempFileChunk( $req, $buffer ) {
		$nbytes = fwrite( $this->mTmpHandle, $buffer );

		if ( $nbytes == strlen( $buffer ) ) {
			$this->mFileSize += $nbytes;
		} else {
			// Well... that's not good!
			fclose( $this->mTmpHandle );
			$this->mTmpHandle = false;
		}

		return $nbytes;
	}

	/**
	 * Download the file, save it to the temporary file and update the file
	 * size and set $mRemoveTempFile to true.
	 * @return Status
	 */
	protected function reallyFetchFile() {
		if ( $this->mTempPath === false ) {
			return Status::newFatal( 'tmp-create-error' );
		}

		// Note the temporary file should already be created by makeTemporaryFile()
		$this->mTmpHandle = fopen( $this->mTempPath, 'wb' );
		if ( !$this->mTmpHandle ) {
			return Status::newFatal( 'tmp-create-error' );
		}

		$this->mRemoveTempFile = true;
		$this->mFileSize = 0;

		$req = MWHttpRequest::factory( $this->mUrl, array(
			'followRedirects' => true
		) );
		$req->setCallback( array( $this, 'saveTempFileChunk' ) );
		$status = $req->execute();

		if ( $this->mTmpHandle ) {
			// File got written ok...
			fclose( $this->mTmpHandle );
			$this->mTmpHandle = null;
		} else {
			// We encountered a write error during the download...
			return Status::newFatal( 'tmp-write-error' );
		}

		if ( !$status->isOk() ) {
			return $status;
		}

		return $status;
	}

	/**
	 * Wrapper around the parent function in order to defer verifying the
	 * upload until the file really has been fetched.
	 */
	public function verifyUpload() {
		if ( $this->mAsync ) {
			return array( 'status' => UploadBase::OK );
		}
		return parent::verifyUpload();
	}

	/**
	 * Wrapper around the parent function in order to defer checking warnings
	 * until the file really has been fetched.
	 */
	public function checkWarnings() {
		if ( $this->mAsync ) {
			$this->mIgnoreWarnings = false;
			return array();
		}
		return parent::checkWarnings();
	}

	/**
	 * Wrapper around the parent function in order to defer checking protection
	 * until we are sure that the file can actually be uploaded
	 */
	public function verifyTitlePermissions( $user ) {
		if ( $this->mAsync ) {
			return true;
		}
		return parent::verifyTitlePermissions( $user );
	}

	/**
	 * Wrapper around the parent function in order to defer uploading to the
	 * job queue for asynchronous uploads
	 */
	public function performUpload( $comment, $pageText, $watch, $user ) {
		if ( $this->mAsync ) {
			$sessionKey = $this->insertJob( $comment, $pageText, $watch, $user );

			return Status::newFatal( 'async', $sessionKey );
		}

		return parent::performUpload( $comment, $pageText, $watch, $user );
	}

	/**
	 * @param  $comment
	 * @param  $pageText
	 * @param  $watch
	 * @param  $user User
	 * @return
	 */
	protected function insertJob( $comment, $pageText, $watch, $user ) {
		$sessionKey = $this->stashSession();
		$job = new UploadFromUrlJob( $this->getTitle(), array(
			'url' => $this->mUrl,
			'comment' => $comment,
			'pageText' => $pageText,
			'watch' => $watch,
			'userName' => $user->getName(),
			'leaveMessage' => $this->mAsync == 'async-leavemessage',
			'ignoreWarnings' => $this->mIgnoreWarnings,
			'sessionId' => session_id(),
			'sessionKey' => $sessionKey,
		) );
		$job->initializeSessionData();
		$job->insert();
		return $sessionKey;
	}

}
