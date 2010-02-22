<?php
/**
 * @file
 * @ingroup upload
 *
 * First, destination checks are made, and, if ignorewarnings is not
 * checked, errors / warning is returned.
 *
 * 1. We return the uploadUrl.
 * 2. We then accept chunk uploads from the client.
 * 3. Return chunk id on each POSTED chunk.
 * 4. Once the client posts "done=1", the files are concatenated together.
 *
 * (More info at: http://firefogg.org/dev/chunk_post.html)
 */
class UploadFromChunks extends UploadBase {

	const INIT = 1;
	const CHUNK = 2;
	const DONE = 3;

	protected $chunkMode; // INIT, CHUNK, DONE
	protected $sessionKey;
	protected $comment;
	protected $repoPath;
	protected $pageText;
	protected $watch;

	public $status;

	// Parent class requires this function even though it is only
	// used from SpecialUpload.php and we don't do chunked uploading
	// from SpecialUpload -- best to raise an exception for
	// now.
	public function initializeFromRequest( &$request ) {
		throw new MWException( 'not implemented' );
	}

	public function initialize( $done, $filename, $sessionKey, $path, $fileSize, $sessionData ) {
		$this->status = Status::newGood();

		$this->initializePathInfo( $filename, $path, 0, true );
		if ( $sessionKey !== null ) {
			$this->initFromSessionKey( $sessionKey, $sessionData, $fileSize );

			if ( $done ) {
				$this->chunkMode = self::DONE;
			} else {
				$this->mTempPath = $path;
				$this->chunkMode = self::CHUNK;
			}
		} else {
			// session key not set, init the chunk upload system:
			$this->chunkMode = self::INIT;
		}

		if ( $this->status->isOk()
			&& ( $this->mDesiredDestName === null || $this->mFileSize === null ) ) {
			$this->status = Status::newFatal( 'chunk-init-error' );
		}
	}

	/**
	 * Set session information for chunked uploads and allocate a unique key.
	 * @param $comment string
	 * @param $pageText string
	 * @param $watch boolean
	 *
	 * @returns string the session key for this chunked upload
	 */
	protected function setupChunkSession( $comment, $pageText, $watch ) {
		if ( !isset( $this->sessionKey ) ) {
			$this->sessionKey = $this->getSessionKey();
		}
		foreach ( array( 'mFilteredName', 'repoPath', 'mFileSize', 'mDesiredDestName' )
				as $key ) {
			if ( isset( $this->$key ) ) {
				$_SESSION['wsUploadData'][$this->sessionKey][$key] = $this->$key;
			}
		}
		if ( isset( $comment ) ) {
			$_SESSION['wsUploadData'][$this->sessionKey]['commment'] = $comment;
		}
		if ( isset( $pageText ) ) {
			$_SESSION['wsUploadData'][$this->sessionKey]['pageText'] = $pageText;
		}
		if ( isset( $watch ) ) {
			$_SESSION['wsUploadData'][$this->sessionKey]['watch'] = $watch;
		}
		$_SESSION['wsUploadData'][$this->sessionKey]['version'] = self::SESSION_VERSION;

		return $this->sessionKey;
	}

	/**
	 * Initialize a continuation of a chunked upload from a session key
	 * @param $sessionKey string
	 * @param $request WebRequest
	 * @param $fileSize int Size of this chunk
	 *
	 * @returns void
	 */
	protected function initFromSessionKey( $sessionKey, $sessionData, $fileSize ) {
		// testing against null because we don't want to cause obscure
		// bugs when $sessionKey is full of "0"
		$this->sessionKey = $sessionKey;

		if ( isset( $sessionData[$this->sessionKey]['version'] )
			&& $sessionData[$this->sessionKey]['version'] == self::SESSION_VERSION )
		{
			foreach ( array( 'comment', 'pageText', 'watch', 'mFilteredName', 'repoPath', 'mFileSize', 'mDesiredDestName' )
					as $key ) {
				if ( isset( $sessionData[$this->sessionKey][$key] ) ) {
					$this->$key = $sessionData[$this->sessionKey][$key];
				}
			}

			$this->mFileSize += $fileSize;
		} else {
			$this->status = Status::newFatal( 'invalid-session-key' );
		}
	}

	/**
	 * Handle a chunk of the upload.  Overrides the parent method
	 * because Chunked Uploading clients (i.e. Firefogg) require
	 * specific API responses.
	 * @see UploadBase::performUpload
	 */
	public function performUpload( $comment, $pageText, $watch, $user ) {
		wfDebug( "\n\n\performUpload(chunked): comment:" . $comment . ' pageText: ' . $pageText . ' watch:' . $watch );
		global $wgUser, $wgOut;

		if ( $this->chunkMode == self::INIT ) {
			// firefogg expects a specific result per:
			// http://www.firefogg.org/dev/chunk_post.html

			// it's okay to return the token here because
			// a) the user must have requested the token to get here and
			// b) should only happen over POST
			// c) we need the token to validate chunks are coming from a non-xss request
			return Status::newGood(
				array( 'uploadUrl' => wfExpandUrl( wfScript( 'api' ) ) . "?" .
					wfArrayToCGI( array(
						'action' => 'upload',
						'token' => $wgUser->editToken(),
						'format' => 'json',
						'filename' => $this->mDesiredDestName,
						'enablechunks' => 'true',
						'chunksession' =>
						$this->setupChunkSession( $comment, $pageText, $watch ) ) ) ) );
		} else if ( $this->chunkMode == self::CHUNK ) {
			$this->setupChunkSession();
			$this->appendChunk();
			if ( !$this->status->isOK() ) {
				return $this->status;
			}
			// return success:
			// firefogg expects a specific result
			// http://www.firefogg.org/dev/chunk_post.html
			return Status::newGood(
				array( 'result' => 1, 'filesize' => $this->mFileSize )
			);
		} else if ( $this->chunkMode == self::DONE ) {
			$this->finalizeFile();
			// We ignore the passed-in parameters because these were set on the first contact.
			$status = parent::performUpload( $this->comment, $this->pageText, $this->watch, $user );

			if ( !$status->isGood() ) {
				return $status;
			}
			$file = $this->getLocalFile();

			// firefogg expects a specific result
			// http://www.firefogg.org/dev/chunk_post.html
			return Status::newGood(
				array( 'result' => 1, 'done' => 1, 'resultUrl' => wfExpandUrl( $file->getDescriptionUrl() ) )
			);
		}

		return Status::newGood();
	}

	/**
	 * Append a chunk to the Repo file
	 *
	 * @param string $srcPath Path to file to append from
	 * @param string $toAppendPath Path to file to append to
	 * @return Status Status
	 */
	protected function appendToUploadFile( $srcPath, $toAppendPath ) {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$status = $repo->append( $srcPath, $toAppendPath );
		return $status;
	}

	/**
	 * Append a chunk to the temporary file.
	 *
	 * @return void
	 */
	protected function appendChunk() {
		global $wgMaxUploadSize;

		if ( !$this->repoPath ) {
			$this->status = $this->saveTempUploadedFile( $this->mDesiredDestName, $this->mTempPath );

			if ( $this->status->isOK() ) {
				$this->repoPath = $this->status->value;
				$_SESSION['wsUploadData'][$this->sessionKey]['repoPath'] = $this->repoPath;
			}
			return;
		}
		if ( $this->getRealPath( $this->repoPath ) ) {
			$this->status = $this->appendToUploadFile( $this->repoPath, $this->mTempPath );

			if ( $this->mFileSize >	$wgMaxUploadSize )
				$this->status = Status::newFatal( 'largefileserver' );

		} else {
			$this->status = Status::newFatal( 'filenotfound', $this->repoPath );
		}
	}

	/**
	 * Append the final chunk and ready file for parent::performUpload()
	 * @return void
	 */
	protected function finalizeFile() {
		$this->appendChunk();
		$this->mTempPath = $this->getRealPath( $this->repoPath );
	}

	public function verifyUpload() {
		if ( $this->chunkMode != self::DONE ) {
			return array( 'status' => UploadBase::OK );
		}
		return parent::verifyUpload();
	}

	public function checkWarnings() {
		if ( $this->chunkMode != self::DONE ) {
			return null;
		}
		return parent::checkWarnings();
	}

	public function getImageInfo( $result ) {
		if ( $this->chunkMode != self::DONE ) {
			return null;
		}
		return parent::getImageInfo( $result );
	}
}
