<?php
/**
 * Implements uploading from previously stored file.
 *
 * @file
 * @ingroup upload
 * @author Bryan Tong Minh
 */

class UploadFromStash extends UploadBase {
	protected $mFileKey, $mVirtualTempPath, $mFileProps, $mSourceType;
	
	// an instance of UploadStash
	private $stash;
	
	//LocalFile repo
	private $repo;
	
	public function __construct( $user = false, $stash = false, $repo = false ) {
		// user object. sometimes this won't exist, as when running from cron.
		$this->user = $user;

		if( $repo ) {
			$this->repo = $repo;
		} else {
			$this->repo = RepoGroup::singleton()->getLocalRepo();
		}

		if( $stash ) {
			$this->stash = $stash;
		} else {
			wfDebug( __METHOD__ . " creating new UploadStash instance for " . $user->getId() . "\n" );
			$this->stash = new UploadStash( $this->repo, $this->user );
		}

		return true;
	}
	
	public static function isValidKey( $key ) {
		// this is checked in more detail in UploadStash
		return (bool)preg_match( UploadStash::KEY_FORMAT_REGEX, $key );
	}

	/**
	 * @param $request WebRequest
	 *
	 * @return Boolean
	 */
	public static function isValidRequest( $request ) {
		// this passes wpSessionKey to getText() as a default when wpFileKey isn't set.
		// wpSessionKey has no default which guarantees failure if both are missing
		// (though that should have been caught earlier)
		return self::isValidKey( $request->getText( 'wpFileKey', $request->getText( 'wpSessionKey' ) ) );
	}

	public function initialize( $key, $name = 'upload_file' ) {
		/**
		 * Confirming a temporarily stashed upload.
		 * We don't want path names to be forged, so we keep
		 * them in the session on the server and just give
		 * an opaque key to the user agent.
		 */		
		$metadata = $this->stash->getMetadata( $key );
		$this->initializePathInfo( $name,
			$this->getRealPath ( $metadata['us_path'] ),
			$metadata['us_size'],
			false
		);

		$this->mFileKey = $key;
		$this->mVirtualTempPath = $metadata['us_path'];
		$this->mFileProps = $this->stash->getFileProps( $key );
		$this->mSourceType = $metadata['us_source_type'];
	}

	/**
	 * @param $request WebRequest
	 */
	public function initializeFromRequest( &$request ) {
		// sends wpSessionKey as a default when wpFileKey is missing
		$fileKey = $request->getText( 'wpFileKey', $request->getText( 'wpSessionKey' ) );

		// chooses one of wpDestFile, wpUploadFile, filename in that order.
		$desiredDestName = $request->getText( 'wpDestFile', $request->getText( 'wpUploadFile', $request->getText( 'filename' ) ) );

		return $this->initialize( $fileKey, $desiredDestName );
	}

	public function getSourceType() { 
		return $this->mSourceType; 
	}

	/**
	 * File has been previously verified so no need to do so again.
	 *
	 * @return bool
	 */
	protected function verifyFile() {
		return true;
	}

	/**
	 * There is no need to stash the image twice
	 */
	public function stashFile() {
		if ( $this->mLocalFile ) {
			return $this->mLocalFile;
		}
		return parent::stashFile();
	}

	/**
	 * Alias for stashFile
	 */
	public function stashSession() {
		return $this->stashFile();
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @return success
	 */
	public function unsaveUploadedFile() {
		return $this->stash->removeFile( $this->mFileKey );
	}

	/**
	 * Perform the upload, then remove the database record afterward.
	 */
	public function performUpload( $comment, $pageText, $watch, $user ) {
		$rv = parent::performUpload( $comment, $pageText, $watch, $user );
		$this->unsaveUploadedFile();
		return $rv;
	}

	/**
	 * Append a chunk to the temporary file.
	 *
	 * @return void
	 */
	public function appendChunk($chunk, $chunkSize, $offset) {
		//to use $this->getFileSize() here, db needs to be updated
		//in appendToUploadFile for that
		$fileSize = $this->stash->getFile( $this->mFileKey )->getSize();
		if ( $fileSize + $chunkSize > $this->getMaxUploadSize()) {
			$status = Status::newFatal( 'file-too-large' );
		} else {
			//append chunk
			if ( $fileSize == $offset ) {
				$status = $this->appendToUploadFile( $chunk,
													 $this->mVirtualTempPath );
			} else {
				$status = Status::newFatal( 'invalid-chunk-offset' );
			}
		}
		return $status;
	}

	/**
	 * Append the final chunk and ready file for parent::performUpload()
	 * @return void
	 */
	public function finalizeFile() {
		$this->appendFinish ( $this->mVirtualTempPath );
		$this->cleanupTempFile();
		$this->mTempPath = $this->getRealPath( $this->mVirtualTempPath );
	}
}
