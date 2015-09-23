<?php
/**
 * Backend for uploading files from chunks.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Upload
 */

/**
 * Implements uploading from chunks
 *
 * @ingroup Upload
 * @author Michael Dale
 */
class UploadFromChunks extends UploadFromFile {
	protected $mOffset;
	protected $mChunkIndex;
	protected $mFileKey;
	protected $mVirtualTempPath;
	/** @var LocalRepo */
	private $repo;

	/**
	 * Setup local pointers to stash, repo and user (similar to UploadFromStash)
	 *
	 * @param User|null $user Default: null
	 * @param UploadStash|bool $stash Default: false
	 * @param FileRepo|bool $repo Default: false
	 */
	public function __construct( $user = null, $stash = false, $repo = false ) {
		// user object. sometimes this won't exist, as when running from cron.
		$this->user = $user;

		if ( $repo ) {
			$this->repo = $repo;
		} else {
			$this->repo = RepoGroup::singleton()->getLocalRepo();
		}

		if ( $stash ) {
			$this->stash = $stash;
		} else {
			if ( $user ) {
				wfDebug( __METHOD__ . " creating new UploadFromChunks instance for " . $user->getId() . "\n" );
			} else {
				wfDebug( __METHOD__ . " creating new UploadFromChunks instance with no user\n" );
			}
			$this->stash = new UploadStash( $this->repo, $this->user );
		}
	}

	/**
	 * Calls the parent stashFile and updates the uploadsession table to handle "chunks"
	 *
	 * @param User|null $user
	 * @return UploadStashFile Stashed file
	 */
	public function stashFile( User $user = null ) {
		// Stash file is the called on creating a new chunk session:
		$this->mChunkIndex = 0;
		$this->mOffset = 0;

		$this->verifyChunk();
		// Create a local stash target
		$this->mLocalFile = parent::stashFile( $user );
		// Update the initial file offset (based on file size)
		$this->mOffset = $this->mLocalFile->getSize();
		$this->mFileKey = $this->mLocalFile->getFileKey();

		// Output a copy of this first to chunk 0 location:
		$this->outputChunk( $this->mLocalFile->getPath() );

		// Update db table to reflect initial "chunk" state
		$this->updateChunkStatus();

		return $this->mLocalFile;
	}

	/**
	 * Continue chunk uploading
	 *
	 * @param string $name
	 * @param string $key
	 * @param WebRequestUpload $webRequestUpload
	 */
	public function continueChunks( $name, $key, $webRequestUpload ) {
		$this->mFileKey = $key;
		$this->mUpload = $webRequestUpload;
		// Get the chunk status form the db:
		$this->getChunkStatus();

		$metadata = $this->stash->getMetadata( $key );
		$this->initializePathInfo( $name,
			$this->getRealPath( $metadata['us_path'] ),
			$metadata['us_size'],
			false
		);
	}

	/**
	 * Append the final chunk and ready file for parent::performUpload()
	 * @return FileRepoStatus
	 */
	public function concatenateChunks() {
		$chunkIndex = $this->getChunkIndex();
		wfDebug( __METHOD__ . " concatenate {$this->mChunkIndex} chunks:" .
			$this->getOffset() . ' inx:' . $chunkIndex . "\n" );

		// Concatenate all the chunks to mVirtualTempPath
		$fileList = array();
		// The first chunk is stored at the mVirtualTempPath path so we start on "chunk 1"
		for ( $i = 0; $i <= $chunkIndex; $i++ ) {
			$fileList[] = $this->getVirtualChunkLocation( $i );
		}

		// Get the file extension from the last chunk
		$ext = FileBackend::extensionFromPath( $this->mVirtualTempPath );
		// Get a 0-byte temp file to perform the concatenation at
		$tmpFile = TempFSFile::factory( 'chunkedupload_', $ext );
		$tmpPath = false; // fail in concatenate()
		if ( $tmpFile ) {
			// keep alive with $this
			$tmpPath = $tmpFile->bind( $this )->getPath();
		}

		// Concatenate the chunks at the temp file
		$tStart = microtime( true );
		$status = $this->repo->concatenate( $fileList, $tmpPath, FileRepo::DELETE_SOURCE );
		$tAmount = microtime( true ) - $tStart;
		if ( !$status->isOk() ) {
			return $status;
		}
		wfDebugLog( 'fileconcatenate', "Combined $i chunks in $tAmount seconds." );

		// File system path
		$this->mTempPath = $tmpPath;
		// Since this was set for the last chunk previously
		$this->mFileSize = filesize( $this->mTempPath );
		$ret = $this->verifyUpload();
		if ( $ret['status'] !== UploadBase::OK ) {
			wfDebugLog( 'fileconcatenate', "Verification failed for chunked upload" );
			$status->fatal( $this->getVerificationErrorCode( $ret['status'] ) );

			return $status;
		}

		// Update the mTempPath and mLocalFile
		// (for FileUpload or normal Stash to take over)
		$tStart = microtime( true );
		$this->mLocalFile = parent::stashFile( $this->user );
		$tAmount = microtime( true ) - $tStart;
		$this->mLocalFile->setLocalReference( $tmpFile ); // reuse (e.g. for getImageInfo())
		wfDebugLog( 'fileconcatenate', "Stashed combined file ($i chunks) in $tAmount seconds." );

		return $status;
	}

	/**
	 * Returns the virtual chunk location:
	 * @param int $index
	 * @return string
	 */
	function getVirtualChunkLocation( $index ) {
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
	 * @param string $chunkPath Path to temporary chunk file
	 * @param int $chunkSize Size of the current chunk
	 * @param int $offset Offset of current chunk ( mutch match database chunk offset )
	 * @return Status
	 */
	public function addChunk( $chunkPath, $chunkSize, $offset ) {
		// Get the offset before we add the chunk to the file system
		$preAppendOffset = $this->getOffset();

		if ( $preAppendOffset + $chunkSize > $this->getMaxUploadSize() ) {
			$status = Status::newFatal( 'file-too-large' );
		} else {
			// Make sure the client is uploading the correct chunk with a matching offset.
			if ( $preAppendOffset == $offset ) {
				// Update local chunk index for the current chunk
				$this->mChunkIndex++;
				try {
					# For some reason mTempPath is set to first part
					$oldTemp = $this->mTempPath;
					$this->mTempPath = $chunkPath;
					$this->verifyChunk();
					$this->mTempPath = $oldTemp;
				} catch ( UploadChunkVerificationException $e ) {
					return Status::newFatal( $e->getMessage() );
				}
				$status = $this->outputChunk( $chunkPath );
				if ( $status->isGood() ) {
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
	private function updateChunkStatus() {
		wfDebug( __METHOD__ . " update chunk status for {$this->mFileKey} offset:" .
			$this->getOffset() . ' inx:' . $this->getChunkIndex() . "\n" );

		$dbw = $this->repo->getMasterDb();
		// Use a quick transaction since we will upload the full temp file into shared
		// storage, which takes time for large files. We don't want to hold locks then.
		$dbw->begin( __METHOD__ );
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
		$dbw->commit( __METHOD__ );
	}

	/**
	 * Get the chunk db state and populate update relevant local values
	 */
	private function getChunkStatus() {
		// get Master db to avoid race conditions.
		// Otherwise, if chunk upload time < replag there will be spurious errors
		$dbw = $this->repo->getMasterDb();
		$row = $dbw->selectRow(
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
	 * @return int Index of the current chunk
	 */
	private function getChunkIndex() {
		if ( $this->mChunkIndex !== null ) {
			return $this->mChunkIndex;
		}

		return 0;
	}

	/**
	 * Get the offset at which the next uploaded chunk will be appended to
	 * @return int Current byte offset of the chunk file set
	 */
	public function getOffset() {
		if ( $this->mOffset !== null ) {
			return $this->mOffset;
		}

		return 0;
	}

	/**
	 * Output the chunk to disk
	 *
	 * @param string $chunkPath
	 * @throws UploadChunkFileException
	 * @return FileRepoStatus
	 */
	private function outputChunk( $chunkPath ) {
		// Key is fileKey + chunk index
		$fileKey = $this->getChunkFileKey();

		// Store the chunk per its indexed fileKey:
		$hashPath = $this->repo->getHashPath( $fileKey );
		$storeStatus = $this->repo->quickImport( $chunkPath,
			$this->repo->getZonePath( 'temp' ) . "/{$hashPath}{$fileKey}" );

		// Check for error in stashing the chunk:
		if ( !$storeStatus->isOK() ) {
			$error = $storeStatus->getErrorsArray();
			$error = reset( $error );
			if ( !count( $error ) ) {
				$error = $storeStatus->getWarningsArray();
				$error = reset( $error );
				if ( !count( $error ) ) {
					$error = array( 'unknown', 'no error recorded' );
				}
			}
			throw new UploadChunkFileException( "Error storing file in '$chunkPath': " .
				implode( '; ', $error ) );
		}

		return $storeStatus;
	}

	private function getChunkFileKey( $index = null ) {
		if ( $index === null ) {
			$index = $this->getChunkIndex();
		}

		return $this->mFileKey . '.' . $index;
	}

	/**
	 * Verify that the chunk isn't really an evil html file
	 *
	 * @throws UploadChunkVerificationException
	 */
	private function verifyChunk() {
		// Rest mDesiredDestName here so we verify the name as if it were mFileKey
		$oldDesiredDestName = $this->mDesiredDestName;
		$this->mDesiredDestName = $this->mFileKey;
		$this->mTitle = false;
		$res = $this->verifyPartialFile();
		$this->mDesiredDestName = $oldDesiredDestName;
		$this->mTitle = false;
		if ( is_array( $res ) ) {
			throw new UploadChunkVerificationException( $res[0] );
		}
	}
}

class UploadChunkZeroLengthFileException extends MWException {
}

class UploadChunkFileException extends MWException {
}

class UploadChunkVerificationException extends MWException {
}
