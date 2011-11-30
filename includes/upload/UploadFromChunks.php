<?php
/**
 * Implements uploading from chunks
 *
 * @file
 * @ingroup upload
 * @author Michael Dale
 */

class UploadFromChunks extends UploadFromFile {
	protected $mOffset, $mChunkIndex, $mFileKey, $mVirtualTempPath;
	
	/**
	 * Setup local pointers to stash, repo and user ( similar to UploadFromStash )
	 * 
	 * @param $user User
	 * @param $stash UploadStash
	 * @param $repo FileRepo
	 */
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
			if( $user ) {
				wfDebug( __METHOD__ . " creating new UploadFromChunks instance for " . $user->getId() . "\n" );
			} else {
				wfDebug( __METHOD__ . " creating new UploadFromChunks instance with no user\n" );
			}
			$this->stash = new UploadStash( $this->repo, $this->user );
		}

		return true;
	}
	/**
	 * Calls the parent stashFile and updates the uploadsession table to handle "chunks" 
	 *
	 * @return UploadStashFile stashed file
	 */
	public function stashFile() {
		// Stash file is the called on creating a new chunk session: 
		$this->mChunkIndex = 0;
		$this->mOffset = 0;
		// Create a local stash target
		$this->mLocalFile = parent::stashFile();
		// Update the initial file offset ( based on file size ) 
		$this->mOffset = $this->mLocalFile->getSize();
		$this->mFileKey = $this->mLocalFile->getFileKey();

		// Output a copy of this first to chunk 0 location:
		$status = $this->outputChunk( $this->mLocalFile->getPath() );
		
		// Update db table to reflect initial "chunk" state 
		$this->updateChunkStatus();
		return $this->mLocalFile;
	}
	
	/**
	 * Continue chunk uploading
	 */	
	public function continueChunks( $name, $key, $webRequestUpload ) {
		$this->mFileKey = $key;
		$this->mUpload = $webRequestUpload;
		// Get the chunk status form the db: 
		$this->getChunkStatus();
		
		$metadata = $this->stash->getMetadata( $key );
		$this->initializePathInfo( $name,
			$this->getRealPath ( $metadata['us_path'] ),
			$metadata['us_size'],
			false
		);
	}
	
	/**
	 * Append the final chunk and ready file for parent::performUpload()
	 * @return void
	 */
	public function concatenateChunks() {
		wfDebug( __METHOD__ . " concatenate {$this->mChunkIndex} chunks:" . 
					$this->getOffset() . ' inx:' . $this->getChunkIndex() . "\n" );
					
		// Concatenate all the chunks to mVirtualTempPath
		$fileList = Array();
		// The first chunk is stored at the mVirtualTempPath path so we start on "chunk 1"
		for( $i = 0; $i <= $this->getChunkIndex(); $i++ ){
			$fileList[] = $this->getVirtualChunkLocation( $i );
		}

		// Concatinate into the mVirtualTempPath location;
		$status = $this->repo->concatenate( $fileList,  $this->mVirtualTempPath, FileRepo::DELETE_SOURCE );
		if( !$status->isOk() ){
			return $status; 
		}
		// Update the mTempPath variable ( for FileUpload or normal Stash to take over )  
		$this->mTempPath = $this->getRealPath( $this->mVirtualTempPath );
		return $status;
	}
	/**
	 * Returns the virtual chunk location: 	
	 * @param unknown_type $index
	 */
	function getVirtualChunkLocation( $index ){
		return $this->repo->getVirtualUrl( 'temp' ) . 
				'/' .
				$this->repo->getHashPath( 
					$this->getChunkFileKey( $index )
				) . 
				$this->getChunkFileKey( $index );
	}
	/**
	 * Add a chunk to the temporary directory
	 *
	 * @param $chunkPath path to temporary chunk file
	 * @param $chunkSize size of the current chunk
	 * @param $offset offset of current chunk ( mutch match database chunk offset ) 
	 * @return Status
	 */
	public function addChunk( $chunkPath, $chunkSize, $offset ) {
		// Get the offset before we add the chunk to the file system
		$preAppendOffset = $this->getOffset();
		
		if ( $preAppendOffset + $chunkSize > $this->getMaxUploadSize()) {
			$status = Status::newFatal( 'file-too-large' );
		} else {
			// Make sure the client is uploading the correct chunk with a matching offset.
			if ( $preAppendOffset == $offset ) {
				// Update local chunk index for the current chunk   
				$this->mChunkIndex++;
				$status = $this->outputChunk( $chunkPath );
				if( $status->isGood() ){
					// Update local offset: 
					$this->mOffset = $preAppendOffset + $chunkSize;
					// Update chunk table status db		
					$this->updateChunkStatus();		
				}
			} else {
				$status = Status::newFatal( 'invalid-chunk-offset' );
			}
		}
		return $status;
	}
	
	/**
	 * Update the chunk db table with the current status: 
	 */
	private function updateChunkStatus(){
		wfDebug( __METHOD__ . " update chunk status for {$this->mFileKey} offset:" . 
					$this->getOffset() . ' inx:' . $this->getChunkIndex() . "\n" );
		
		$dbw = $this->repo->getMasterDb();
		$dbw->update(
			'uploadstash',
			array( 
				'us_status' => 'chunks',
				'us_chunk_inx' => $this->getChunkIndex(),
				'us_size' => $this->getOffset()
			),
			array( 'us_key' => $this->mFileKey ),
			__METHOD__
		);
	}
	/**
	 * Get the chunk db state and populate update relevant local values
	 */
	private function getChunkStatus(){
		$dbr = $this->repo->getSlaveDb();
		$row = $dbr->selectRow(
			'uploadstash', 
			array( 
				'us_chunk_inx',
				'us_size',
				'us_path',
			),
			array( 'us_key' => $this->mFileKey ),
			__METHOD__
		);
		// Handle result:
		if ( $row ) {
			$this->mChunkIndex = $row->us_chunk_inx;
			$this->mOffset = $row->us_size;
			$this->mVirtualTempPath = $row->us_path;
		}
	}
	/**
	 * Get the current Chunk index 
	 * @return Integer index of the current chunk
	 */
	private function getChunkIndex(){
		if( $this->mChunkIndex !== null ){
			return $this->mChunkIndex;
		}
		return 0;
	}
	
	/**
	 * Gets the current offset in fromt the stashedupload table 
	 * @return Integer current byte offset of the chunk file set 
	 */
	private function getOffset(){
		if ( $this->mOffset !== null ){
			return $this->mOffset;
		}
		return 0;
	}
	
	/**
	 * Output the chunk to disk
	 * 
	 * @param $chunk 
	 * @param unknown_type $path
	 */
	private function outputChunk( $chunkPath ){
		// Key is fileKey + chunk index
		$fileKey = $this->getChunkFileKey();
		
		// Store the chunk per its indexed fileKey: 
		$hashPath = $this->repo->getHashPath( $fileKey );		
		$storeStatus = $this->repo->store( $chunkPath, 'temp', "$hashPath$fileKey" );
		
		// Check for error in stashing the chunk:
		if ( ! $storeStatus->isOK() ) {
			$error = $storeStatus->getErrorsArray();
			$error = reset( $error );
			if ( ! count( $error ) ) {
				$error = $storeStatus->getWarningsArray();
				$error = reset( $error );
				if ( ! count( $error ) ) {
					$error = array( 'unknown', 'no error recorded' );
				}
			}
			throw new UploadChunkFileException( "error storing file in '$path': " . implode( '; ', $error ) );
		}
		return $storeStatus;
	}
	private function getChunkFileKey( $index = null ){
		if( $index === null ){
			$index = $this->getChunkIndex();
		}
		return $this->mFileKey . '.' . $index ;
	}
}

class UploadChunkZeroLengthFileException extends MWException {};
class UploadChunkFileException extends MWException {};
