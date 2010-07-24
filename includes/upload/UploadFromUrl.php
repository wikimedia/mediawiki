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
	protected $comment, $watchList, $ignoreWarnings;

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
	 * @return bool
	 */
	public static function isEnabled() {
		global $wgAllowCopyUploads;
		return $wgAllowCopyUploads && parent::isEnabled();
	}

	/**
	 * Entry point for API upload
	 * @return bool true on success
	 */
	public function initialize( $name, $url, $comment, $watchList = null, $ignoreWarn = null, $async = 'async') {
		global $wgUser;

		if( !Http::isValidURI( $url ) ) {
			return Status::newFatal( 'http-invalid-url' );
		}
		$params = array(
			"userName" => $wgUser->getName(),
			"userID" => $wgUser->getID(),
			"url" => trim( $url ),
			"timestamp" => wfTimestampNow(),
			"comment" => $comment,
			"watchlist" => $watchList,
			"ignorewarnings" => $ignoreWarn);

		$title = Title::newFromText( $name );

		if ( $async == 'async' ) {
			$job = new UploadFromUrlJob( $title, $params );
			return $job->insert();
		}
		else {
			$this->mUrl = trim( $url );
			$this->comment = $comment;
			$this->watchList = $watchList;
			$this->ignoreWarnings = $ignoreWarn;
			$this->mDesiredDestName = $title;
			$this->getTitle();

			return true;
		}
	}

	/**
	 * Initialize a queued download
	 * @param $job Job
	 */
	public function initializeFromJob( $job ) {
		global $wgTmpDirectory;

		$this->mUrl = $job->params['url'];
		$this->mTempPath = tempnam( $wgTmpDirectory, 'COPYUPLOAD' );
		$this->mDesiredDestName = $job->title;
		$this->comment = $job->params['comment'];
		$this->watchList = $job->params['watchlist'];
		$this->ignoreWarnings = $job->params['ignorewarnings'];
		$this->getTitle();
	}

	/**
	 * Entry point for SpecialUpload
	 * @param $request Object: WebRequest object
	 */
	public function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFileURL' );
		return $this->initialize(
			$desiredDestName,
			$request->getVal( 'wpUploadFileURL' ),
			$request->getVal( 'wpUploadDescription' ),
			$request->getVal( 'wpWatchThis' ),
			$request->getVal( 'wpIgnoreWarnings' ),
			'async'
		);
	}

	/**
	 * @param $request Object: WebRequest object
	 */
	public static function isValidRequest( $request ){
		global $wgUser;

		$url = $request->getVal( 'wpUploadFileURL' );
		return !empty( $url )
			&& Http::isValidURI( $url )
			&& $wgUser->isAllowed( 'upload_by_url' );
	}

	private function saveTempFile( $req ) {
		$filename = tempnam( wfTempDir(), 'URL' );
		if ( $filename === false ) {
			return Status::newFatal( 'tmp-create-error' );
		}
		if ( file_put_contents( $filename, $req->getContent() ) === false ) {
			return Status::newFatal( 'tmp-write-error' );
		}

		$this->mTempPath = $filename;
		$this->mFileSize = filesize( $filename );

		return Status::newGood();
	}

	public function retrieveFileFromUrl() {
		$req = HttpRequest::factory($this->mUrl);
		$status = $req->execute();

		if( !$status->isOk() ) {
			return $status;
		}

		$status = $this->saveTempFile( $req );
		if ( !$status->isGood() ) {
			return $status;
		}
		$this->mRemoveTempFile = true;

		return $status;
	}

	public function doUpload() {
		global $wgUser;

		$status = $this->retrieveFileFromUrl();

		if ( $status->isGood() ) {

			$v = $this->verifyUpload();
			if( $v['status'] !== UploadBase::OK ) {
				return $this->convertVerifyErrorToStatus( $v['status'], $v['details'] );
			}

			$status = $this->getLocalFile()->upload( $this->mTempPath, $this->comment,
				$this->comment, File::DELETE_SOURCE, $this->mFileProps, false, $wgUser );
		}

		if ( $status->isGood() ) {
			$file = $this->getLocalFile();

			$wgUser->leaveUserMessage( wfMsg( 'successfulupload' ),
				wfMsg( 'upload-success-msg', $file->getDescriptionUrl() ) );
		} else {
			$wgUser->leaveUserMessage( wfMsg( 'upload-failure-subj' ),
				wfMsg( 'upload-failure-msg', $status->getWikiText() ) );
		}

		return $status;
	}
}
