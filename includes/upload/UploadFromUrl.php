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
	protected $comment, $watchList;

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
	 * Entry point for API upload
	 */
	public function initialize( $name, $url, $comment, $watchlist ) {
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
			"watchlist" => $watchlist);

		$title = Title::newFromText( $name );
		/* // Check whether the user has the appropriate permissions to upload anyway */
		/* $permission = $this->isAllowed( $wgUser ); */

		/* if ( $permission !== true ) { */
		/* 	if ( !$wgUser->isLoggedIn() ) { */
		/* 		return Status::newFatal( 'uploadnologintext' ); */
		/* 	} else { */
		/* 		return Status::newFatal( 'badaccess-groups' ); */
		/* 	} */
		/* } */

		/* $permErrors = $this->verifyPermissions( $wgUser ); */
		/* if ( $permErrors !== true ) { */
		/* 	return Status::newFatal( 'badaccess-groups' ); */
		/* } */


		$job = new UploadFromUrlJob( $title, $params );
		$job->insert();
	}

	/**
	 * Initialize a queued download
	 * @param $job Job
	 */
	public function initializeFromJob( $job ) {
		$this->mUrl = $job->params['url'];
		$this->mTempPath = tempnam( $wgTmpDirectory, 'COPYUPLOAD' );
		$this->mDesiredDestName = $job->title;
		$this->comment = $job->params['comment'];
		$this->watchList = $job->params['watchlist'];
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
			false
		);
	}

	/**
	 * @param $request Object: WebRequest object
	 */
	public static function isValidRequest( $request ){
		if( !$request->getVal( 'wpUploadFileURL' ) )
			return false;
		// check that is a valid url:
		return self::isValidUrl( $request->getVal( 'wpUploadFileURL' ) );
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

	public function doUpload() {
		global $wgUser;

		$req = HttpRequest::factory($this->mUrl);
		$status = $req->execute();

		if( !$status->isOk() ) {
			return $status;
		}

		$status = $this->saveTempFile( $req );
		$this->mRemoveTempFile = true;

		if( !$status->isOk() ) {
			return $status;
		}

		$v = $this->verifyUpload();
		if( $v['status'] !== UploadBase::OK ) {
			return $this->convertVerifyErrorToStatus( $v['status'], $v['details'] );
		}

		// This has to come from API
		/* $warnings = $this->checkForWarnings(); */
		/* if( isset($warnings) ) return $warnings; */

		// Use comment as initial page text by default
		if ( is_null( $this->mParams['text'] ) ) {
			$this->mParams['text'] = $this->mParams['comment'];
		}

		$file = $this->getLocalFile();
		// This comes from ApiBase
		/* $watch = $this->getWatchlistValue( $this->mParams['watchlist'], $file->getTitle() ); */

		if ( !$status->isGood() ) {
			return $status;
		}

		$status = $this->getLocalFile()->upload( $this->mTempPath, $this->comment,
			$this->pageText, File::DELETE_SOURCE, $this->mFileProps, false, $wgUser );

		return $status;
	}
}
