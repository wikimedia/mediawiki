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
	protected $mAsync, $mUrl;

	/**
	 * Checks if the user is allowed to use the upload-by-URL feature. If the
	 * user is allowed, pass on permissions checking to the parent.
	 */
	public static function isAllowed( $user ) {
		if ( !$user->isAllowed( 'upload_by_url' ) )
			return 'upload_by_url';
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
		global $wgUser;
		
		$this->mUrl = $url;
		$this->mAsync = $async;
		
		$tempPath = $async ? null : $this->makeTemporaryFile();
		# File size and removeTempFile will be filled in later
		$this->initializePathInfo( $name, $tempPath, 0, false );
	}

	/**
	 * Entry point for SpecialUpload
	 * @param $request Object: WebRequest object
	 */
	public function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if ( !$desiredDestName )
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
	public static function isValidRequest( $request ) {
		global $wgUser;

		$url = $request->getVal( 'wpUploadFileURL' );
		return !empty( $url )
			&& Http::isValidURI( $url )
			&& $wgUser->isAllowed( 'upload_by_url' );
	}
	

	public function fetchFile() {
		if ( !Http::isValidURI( $this->mUrl ) ) {
			return Status::newFatal( 'http-invalid-url' );
		}
		
		if ( !$this->mAsync ) {
			return $this->reallyFetchFile();
		}
		return Status::newGood();
	}
	protected function makeTemporaryFile() {
		return tempnam( wfTempDir(), 'URL' );
	}
	private function saveTempFile( $req ) {
		if ( $this->mTempPath === false ) {
			return Status::newFatal( 'tmp-create-error' );
		}
		if ( file_put_contents( $this->mTempPath, $req->getContent() ) === false ) {
			return Status::newFatal( 'tmp-write-error' );
		}

		$this->mFileSize = filesize( $this->mTempPath );

		return Status::newGood();
	}
	protected function reallyFetchFile() {
		$req = HttpRequest::factory( $this->mUrl );
		$status = $req->execute();

		if ( !$status->isOk() ) {
			return $status;
		}

		$status = $this->saveTempFile( $req );
		if ( !$status->isGood() ) {
			return $status;
		}
		$this->mRemoveTempFile = true;

		return $status;
	}

	public function performUpload( $comment, $pageText, $watch, $user ) {
		if ( $this->mAsync ) {
			$sessionKey = $this->insertJob( $comment, $pageText, $watch, $user );
			
			$status = new Status;
			$status->error( 'async', $sessionKey );
			return $status;
		}
		
		return parent::performUpload( $comment, $pageText, $watch, $user );
	}

	
	protected function insertJob( $comment, $pageText, $watch, $user ) {
		$sessionKey = $this->getSessionKey();
		$job = new UploadFromUrlJob( $this->getTitle(), array(
			'url' => $this->mUrl,
			'comment' => $comment,
			'pageText' => $pageText,
			'watch' => $watch,
			'userName' => $user->getName(),
			'leaveMessage' => $this->mAsync == 'async-leavemessage',
			'ignoreWarnings' => $this->mIgnoreWarnings,
			'sessionKey' => $sessionKey,
		) );
		$job->insert();
		return $sessionKey;
	}
	

}
