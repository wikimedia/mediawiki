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

	// STYLE NOTE: Coding guidelines says the 'm' prefix for object
	// member variables is discouraged in new code but "stay
	// consistent within a class". UploadFromChunks is new, but extends
	// UploadBase which has the 'm' prefix.	 I'm eschewing the prefix for
	// member variables of this class.
	protected $chunkMode; // INIT, CHUNK, DONE
	protected $sessionKey;
	protected $comment;
	protected $fileSize = 0;
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

	public function initialize( &$request ) {
		$done = $request->getText( 'done' );
		$filename = $request->getText( 'filename' );
		$sessionKey = $request->getText( 'chunksessionkey' );

		$this->initFromSessionKey( $sessionKey, $request );

		if ( !$this->sessionKey && !$done ) {
			// session key not set, init the chunk upload system:
			$this->chunkMode = self::INIT;
			$this->mDesiredDestName = $filename;
		} else if ( $this->sessionKey && !$done ) {
			$this->chunkMode = self::CHUNK;
		} else if ( $this->sessionKey && $done ) {
			$this->chunkMode = self::DONE;
		}

		if ( $this->chunkMode == self::CHUNK || $this->chunkMode == self::DONE ) {
			$this->mTempPath = $request->getFileTempName( 'chunk' );
			$this->fileSize += $request->getFileSize( 'chunk' );
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
		$this->sessionKey = $this->getSessionKey();
		$_SESSION['wsUploadData'][$this->sessionKey] = array(
			'comment' => $comment,
			'pageText' => $pageText,
			'watch' => $watch,
			'mFilteredName' => $this->mFilteredName,
			'repoPath' => null,
			'mDesiredDestName' => $this->mDesiredDestName,
			'version' => self::SESSION_VERSION,
		);
		return $this->sessionKey;
	}

	/**
	 * Initialize a continuation of a chunked upload from a session key
	 * @param $sessionKey string
	 * @param $request WebRequest
	 *
	 * @returns void
	 */
	protected function initFromSessionKey( $sessionKey, $request ) {
		if ( !$sessionKey || empty( $sessionKey ) ) {
			$this->status = Status::newFromFatal( 'Missing session data.' );
			return;
		}
		$this->sessionKey = $sessionKey;
		// load the sessionData array:
		$sessionData = $request->getSessionData( 'wsUploadData' );

		if ( isset( $sessionData[$this->sessionKey]['version'] )
				&& $sessionData[$this->sessionKey]['version'] == self::SESSION_VERSION ) {
			$this->comment = $sessionData[$this->sessionKey]['comment'];
			$this->pageText = $sessionData[$this->sessionKey]['pageText'];
			$this->watch = $sessionData[$this->sessionKey]['watch'];
			$this->mFilteredName = $sessionData[$this->sessionKey]['mFilteredName'];
			$this->repoPath = $sessionData[$this->sessionKey]['repoPath'];
			$this->mDesiredDestName = $sessionData[$this->sessionKey]['mDesiredDestName'];
		} else {
			$this->status = Status::newFromFatal( 'Missing session data.' );
		}
	}

	/**
	 * Handle a chunk of the upload.  Overrides the parent method
	 * because Chunked Uploading clients (i.e. Firefogg) require
	 * specific API responses.
	 * @see UploadBase::performUpload
	 */
	public function performUpload( $comment, $pageText, $watch, $user ) {
		wfDebug( "\n\n\performUpload(chunked): sum:" . $comment . ' c: ' . $pageText . ' w:' . $watch );
		global $wgUser;

		if ( $this->chunkMode == self::INIT ) {
			// firefogg expects a specific result per:
			// http://www.firefogg.org/dev/chunk_post.html

			// it's okay to return the token here because
			// a) the user must have requested the token to get here and
			// b) should only happen over POST
			// c) we need the token to validate chunks are coming from a non-xss request
			$token = urlencode( $wgUser->editToken() );
			ob_clean();
			echo FormatJson::encode( array(
				'uploadUrl' => wfExpandUrl( wfScript( 'api' ) ) . "?action=upload&" .
				"token={$token}&format=json&enablechunks=true&chunksessionkey=" .
				$this->setupChunkSession( $comment, $pageText, $watch ) ) );
			exit( 0 );
		} else if ( $this->chunkMode == self::CHUNK ) {
			$status = $this->appendChunk();
			if ( !$status->isOK() ) {
				return $status;
			}
			// return success:
			// firefogg expects a specific result
			// http://www.firefogg.org/dev/chunk_post.html
			ob_clean();
			echo FormatJson::encode(
				array( 'result' => 1, 'filesize' => $this->fileSize )
			);
			exit( 0 );
		} else if ( $this->chunkMode == self::DONE ) {
			if ( $comment == '' )
				$comment = $this->comment;

			if ( $pageText == '' )
				$pageText = $this->pageText;

			if ( $watch == '' )
				$watch = $this->watch;

			$status = parent::performUpload( $comment, $pageText, $watch, $user );
			if ( !$status->isGood() ) {
				return $status;
			}
			$file = $this->getLocalFile();

			// firefogg expects a specific result
			// http://www.firefogg.org/dev/chunk_post.html
			ob_clean();
			echo FormatJson::encode( array(
				'result' => 1,
				'done' => 1,
				'resultUrl' => $file->getDescriptionUrl() )
			);
			exit( 0 );
		}
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

			if ( $status->isOK() ) {
				$this->repoPath = $status->value;
				$_SESSION['wsUploadData'][$this->sessionKey]['repoPath'] = $this->repoPath;
			}
			return $status;
		} else {
			if ( $this->getRealPath( $this->repoPath ) ) {
				$this->status = $this->appendToUploadFile( $this->repoPath, $this->mTempPath );
			} else {
				$this->status = Status::newFatal( 'filenotfound', $this->repoPath );
			}

			if ( $this->fileSize >  $wgMaxUploadSize )
				$this->status = Status::newFatal( 'largefileserver' );
		}
	}
}
