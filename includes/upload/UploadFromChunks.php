<?php
/**
 * @file
 * @ingroup upload
 * 
 * @author Michael Dale
 * 
 * first destination checks are made (if ignorewarnings is not checked) errors / warning is returned.
 *
 * we return the uploadUrl
 * we then accept chunk uploads from the client.
 * return chunk id on each POSTED chunk
 * once the client posts done=1 concatenated the files together.
 * more info at: http://firefogg.org/dev/chunk_post.html
 */
class UploadFromChunks extends UploadBase {

	var $chunk_mode; // init, chunk, done
	var $mSessionKey = false;
	var $status = array();

	const INIT 	= 1;
	const CHUNK = 2;
	const DONE 	= 3;

	function initializeFromParams( $param, &$request ) {
		$this->initFromSessionKey( $param['chunksessionkey'], $request );
		// set the chunk mode:
		if( !$this->mSessionKey && !$param['done'] ){
			// session key not set init the chunk upload system:
			$this->chunk_mode = UploadFromChunks::INIT;
			$this->mDesiredDestName = $param['filename'];
		} else if( $this->mSessionKey && !$param['done'] ){
			// this is a chunk piece
			$this->chunk_mode = UploadFromChunks::CHUNK;
		} else if( $this->mSessionKey && $param['done'] ){
			// this is the last chunk
			$this->chunk_mode = UploadFromChunks::DONE;
		}
		if( $this->chunk_mode == UploadFromChunks::CHUNK ||
			$this->chunk_mode == UploadFromChunks::DONE ){
				// set chunk related vars:
				$this->mTempPath = $request->getFileTempName( 'chunk' );
				$this->mFileSize = $request->getFileSize( 'chunk' );
		}

		return $this->status;
	}

	static function isValidRequest( $request ) {
		$sessionData = $request->getSessionData( 'wsUploadData' );
		if( !self::isValidSessionKey(
			$request->getInt( 'wpSessionKey' ),
			$sessionData ) )
				return false;
		// check for the file:
		return (bool)$request->getFileTempName( 'file' );
	}

	/* check warnings depending on chunk_mode */
	function checkWarnings(){
		$warning = array();
		return $warning;
	}

	function isEmptyFile(){
		// does not apply to chunk init
		if( $this->chunk_mode == UploadFromChunks::INIT ){
			return false;
		} else {
			return parent::isEmptyFile();
		}
	}

 	/**
	 * Verify whether the upload is sane.
	 * Returns self::OK or else an array with error information
	 */
	function verifyUpload() {
		// no checks on chunk upload mode:
		if( $this->chunk_mode ==  UploadFromChunks::INIT )
			return self::OK;

		// verify on init and last chunk request
		if(	$this->chunk_mode == UploadFromChunks::CHUNK ||
			$this->chunk_mode == UploadFromChunks::DONE )
			return parent::verifyUpload();
	}

	// only run verifyFile on completed uploaded chunks
	function verifyFile( $tmpFile ){
		if( $this->chunk_mode == UploadFromChunks::DONE ){
			// first append last chunk (so we can do a real verifyFile check... (check file type etc)
			$status = $this->doChunkAppend();
			if( $status->isOK() ){
				$this->mTempPath = $this->getRealPath( $this->mTempAppendPath );
				// verify the completed merged chunks as if it was the file that got uploaded:
				return parent::verifyFile( $this->mTempPath );
			} else {
				// conflict of status returns (have to return the error ary) ... why we don't consistantly use a status object is beyond me..
				return $status->getErrorsArray();
			}
		} else {
			return true;
		}
	}

	function getRealPath( $srcPath ){
		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $repo->isVirtualUrl( $srcPath ) ) {
			return $repo->resolveVirtualUrl( $srcPath );
		}
	}

	// pretty ugly inter-mixing of mParam and local vars
	function setupChunkSession( $summary, $comment, $watch ) {
		$this->mSessionKey = $this->getSessionKey();
		$_SESSION['wsUploadData'][$this->mSessionKey] = array(
			'mComment'			=> $comment,
			'mSummary'			=> $summary,
			'mWatch'			=> $watch,
			'mIgnorewarnings' 	=> true, //ignore warning on chunk uploads (for now)
			'mFilteredName'		=> $this->mFilteredName,
			'mTempAppendPath'	=> null, // the repo append path (not temporary local node mTempPath)
			'mDesiredDestName'	=> $this->mDesiredDestName,
			'version'			=> self::SESSION_VERSION,
		);
		return $this->mSessionKey;
	}

	function initFromSessionKey( $sessionKey, $request ){
		if( !$sessionKey || empty( $sessionKey ) ){
			return false;
		}
		$this->mSessionKey = $sessionKey;
		// load the sessionData array:
		$sessionData = $request->getSessionData( 'wsUploadData' );

		if( isset( $sessionData[$this->mSessionKey]['version'] ) &&
			$sessionData[$this->mSessionKey]['version'] == self::SESSION_VERSION ) {
			// update the local object from the session
			$this->mComment          = $sessionData[$this->mSessionKey]['mComment'];
			$this->mSummary          = $sessionData[$this->mSessionKey]['mSummary'];
			$this->mWatch            = $sessionData[$this->mSessionKey]['mWatch'];
			$this->mIgnorewarnings   = $sessionData[$this->mSessionKey]['mIgnorewarnings'];
			$this->mFilteredName	 = $sessionData[$this->mSessionKey]['mFilteredName'];
			$this->mTempAppendPath   = $sessionData[$this->mSessionKey]['mTempAppendPath'];
			$this->mDesiredDestName	 = $sessionData[$this->mSessionKey]['mDesiredDestName'];
		} else {
			$this->status = array( 'error' => 'missing session data' );
			return false;
		}
	}

	// Lets us return an api result (as flow for chunk uploads is kind of different than others.
	function performUpload( $summary = '', $comment = '', $watch = '', $user ){
		global $wgServer, $wgScriptPath, $wgUser;

		if( $this->chunk_mode == UploadFromChunks::INIT ){
			// firefogg expects a specific result per:
			// http://www.firefogg.org/dev/chunk_post.html

			// it's okay to return the token here because
			// a) the user must have requested the token to get here and
			// b) should only happen over POST
			// c) (we need the token to validate chunks are coming from a non-xss request)
			$token = urlencode( $wgUser->editToken() );
			ob_clean();
			echo ApiFormatJson::getJsonEncode( array(
					'uploadUrl' => "{$wgServer}{$wgScriptPath}/api.php?action=upload&".
									"token={$token}&format=json&enablechunks=true&chunksessionkey=".
									$this->setupChunkSession( $summary, $comment, $watch ) ) );
			exit( 0 );
		} else if( $this->chunk_mode == UploadFromChunks::CHUNK ){
			$status = $this->doChunkAppend();
			if( $status->isOK() ){
				// return success:
				// firefogg expects a specific result per:
				// http://www.firefogg.org/dev/chunk_post.html
				ob_clean();
				echo ApiFormatJson::getJsonEncode( array(
						'result' => 1,
						'filesize' => filesize( $this->getRealPath( $this->mTempAppendPath ) )
					)
				);
				exit( 0 );
				/*return array(
					'result' => 1
				);*/
			} else {
				return $status;
			}
		} else if( $this->chunk_mode == UploadFromChunks::DONE ){
			// update the values from the local (session init) if not paseed again)
			if( $summary == '' )
				$summary = $this->mSummary;

			if( $comment == '' )
				$comment = $this->mComment;

			if( $watch == '' )
				$watch = $this->mWatch;
			$status = parent::performUpload( $summary, $comment, $watch, $user );
			if( !$status->isGood() ) {
				return $status;
			}
			$file = $this->getLocalFile();
			// firefogg expects a specific result per:
			// http://www.firefogg.org/dev/chunk_post.html
			ob_clean();
			echo ApiFormatJson::getJsonEncode( array(
					'result' => 1,
					'done' => 1,
					'resultUrl' => $file->getDescriptionUrl()
				)
			);
			exit( 0 );

		}
	}

	// append the given chunk to the temporary uploaded file. (if no temporary uploaded file exists created it.
	function doChunkAppend(){
		global $wgMaxUploadSize;
		// if we don't have a mTempAppendPath to generate a file from the chunk packaged var:
		if( !$this->mTempAppendPath ){
			// get temp name:
			// make a chunk store path. (append tmp file to chunk)
			$status = $this->saveTempUploadedFile( $this->mDestName, $this->mTempPath );

			if( $status->isOK() ) {
				$this->mTempAppendPath = $status->value;
				$_SESSION['wsUploadData'][$this->mSessionKey]['mTempAppendPath'] = $this->mTempAppendPath;
			}
			return $status;
		} else {
			if( is_file( $this->getRealPath( $this->mTempAppendPath ) ) ){
				$status = $this->appendToUploadFile( $this->mTempAppendPath, $this->mTempPath );
			} else {
				$status = Status::newFatal( 'filenotfound', $this->mTempAppendPath );
			}
			//check to make sure we have not expanded beyond $wgMaxUploadSize
			if( filesize(  $this->getRealPath( $this->mTempAppendPath ) ) >  $wgMaxUploadSize )
				$status = Status::newFatal( 'largefileserver' );

			return $status;
		}
	}

}
