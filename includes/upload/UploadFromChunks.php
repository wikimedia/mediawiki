<?php

use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequestUpload;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use Psr\Log\LoggerInterface;
use Wikimedia\FileBackend\FileBackend;

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
	/** @var LocalRepo */
	private $repo;
	/** @var UploadStash */
	public $stash;
	/** @var User */
	public $user;

	/** @var int|null */
	protected $mOffset;
	/** @var int|null */
	protected $mChunkIndex;
	/** @var string */
	protected $mFileKey;
	/** @var string|null */
	protected $mVirtualTempPath;

	private LoggerInterface $logger;

	/** @noinspection PhpMissingParentConstructorInspection */

	/**
	 * Setup local pointers to stash, repo and user (similar to UploadFromStash)
	 *
	 * @param User $user
	 * @param UploadStash|false $stash Default: false
	 * @param FileRepo|false $repo Default: false
	 */
	public function __construct( User $user, $stash = false, $repo = false ) {
		$this->user = $user;

		if ( $repo ) {
			$this->repo = $repo;
		} else {
			$this->repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		}

		if ( $stash ) {
			$this->stash = $stash;
		} else {
			wfDebug( __METHOD__ . " creating new UploadFromChunks instance for " . $user->getId() );
			$this->stash = new UploadStash( $this->repo, $this->user );
		}

		$this->logger = LoggerFactory::getInstance( 'upload' );
		parent::__construct();
	}

	/**
	 * @inheritDoc
	 */
	public function tryStashFile( User $user, $isPartial = false ) {
		try {
			$this->verifyChunk();
		} catch ( UploadChunkVerificationException $e ) {
			return Status::newFatal( $e->msg );
		}

		return parent::tryStashFile( $user, $isPartial );
	}

	/**
	 * Calls the parent doStashFile and updates the uploadsession table to handle "chunks"
	 *
	 * @param User|null $user
	 * @return UploadStashFile Stashed file
	 */
	protected function doStashFile( ?User $user = null ) {
		// Stash file is the called on creating a new chunk session:
		$this->mChunkIndex = 0;
		$this->mOffset = 0;

		// Create a local stash target
		$this->mStashFile = parent::doStashFile( $user );
		// Update the initial file offset (based on file size)
		$this->mOffset = $this->mStashFile->getSize();
		$this->mFileKey = $this->mStashFile->getFileKey();
		$this->mVirtualTempPath = $this->mStashFile->getPath();

		// Output a copy of this first to chunk 0 location:
		$this->outputChunk( $this->mStashFile->getPath() );

		// Update db table to reflect initial "chunk" state
		$this->updateChunkStatus();

		return $this->mStashFile;
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
		$tempPath = $this->getRealPath( $metadata['us_path'] );
		if ( $tempPath === false ) {
			throw new UploadStashBadPathException( wfMessage( 'uploadstash-bad-path' ) );
		}
		$this->initializePathInfo( $name,
			$tempPath,
			$metadata['us_size'],
			false
		);
	}

	/**
	 * Append the final chunk and ready file for parent::performUpload()
	 * @return Status
	 */
	public function concatenateChunks() {
		$oldFileKey = $this->mFileKey;
		$chunkIndex = $this->getChunkIndex();
		$this->logger->debug(
			__METHOD__ . ' concatenate {totalChunks} chunks: {offset} inx: {curIndex}',
			[
				'offset' => $this->getOffset(),
				'totalChunks' => $this->mChunkIndex,
				'curIndex' => $chunkIndex,
				'filekey' => $oldFileKey
			]
		);

		// Concatenate all the chunks to mVirtualTempPath
		$fileList = [];
		// The first chunk is stored at the mVirtualTempPath path so we start on "chunk 1"
		for ( $i = 0; $i <= $chunkIndex; $i++ ) {
			$fileList[] = $this->getVirtualChunkLocation( $i );
		}

		// Get the file extension from the last chunk
		$ext = FileBackend::extensionFromPath( $this->mVirtualTempPath );
		// Get a 0-byte temp file to perform the concatenation at
		$tmpFile = MediaWikiServices::getInstance()->getTempFSFileFactory()
			->newTempFSFile( 'chunkedupload_', $ext );
		$tmpPath = false; // fail in concatenate()
		if ( $tmpFile ) {
			// keep alive with $this
			$tmpPath = $tmpFile->bind( $this )->getPath();
		} else {
			$this->logger->warning( "Error getting tmp file", [ 'filekey' => $oldFileKey ] );
		}

		// Concatenate the chunks at the temp file
		$tStart = microtime( true );
		$status = $this->repo->concatenate( $fileList, $tmpPath );
		$tAmount = microtime( true ) - $tStart;
		if ( !$status->isOK() ) {
			// This is a backend error and not user-related, so log is safe
			// Upload verification further on is not safe to log server side
			$this->logFileBackendStatus(
				$status,
				'[{type}] Error on concatenate {chunks} stashed files ({details})',
				[ 'chunks' => $chunkIndex, 'filekey' => $oldFileKey ]
			);
			return $status;
		} else {
			// Delete old chunks in deferred job. Put in deferred job because deleting
			// lots of chunks can take a long time, sometimes to the point of causing
			// a timeout, and we do not want that to tank the operation. Note that chunks
			// are also automatically deleted after a set time by cleanupUploadStash.php
			// Additionally, using AutoCommitUpdate ensures that we do not delete files
			// if the main transaction is rolled back for some reason.
			DeferredUpdates::addUpdate( new AutoCommitUpdate(
				$this->repo->getPrimaryDB(),
				__METHOD__,
				function () use( $fileList, $oldFileKey ) {
					$status = $this->repo->quickPurgeBatch( $fileList );
					if ( !$status->isOK() ) {
						$this->logger->warning(
							"Could not delete chunks of {filekey} - {status}",
							[
								'status' => (string)$status,
								'filekey' => $oldFileKey,
							]
						);
					}
				}
			) );
		}

		wfDebugLog( 'fileconcatenate', "Combined $i chunks in $tAmount seconds." );

		// File system path of the actual full temp file
		$this->setTempFile( $tmpPath );

		$ret = $this->verifyUpload();
		if ( $ret['status'] !== UploadBase::OK ) {
			$this->logger->info(
				"Verification failed for chunked upload {filekey}",
				[
					'user' => $this->user->getName(),
					'filekey' => $oldFileKey
				]
			);
			// @phan-suppress-next-line PhanTypeMismatchReturnProbablyReal
			return $this->convertVerifyErrorToStatus( $ret );
		}

		// Update the mTempPath and mStashFile
		// (for FileUpload or normal Stash to take over)
		$tStart = microtime( true );
		// This is a re-implementation of UploadBase::tryStashFile(), we can't call it because we
		// override doStashFile() with completely different functionality in this class...
		$error = $this->runUploadStashFileHook( $this->user );
		if ( $error ) {
			$status->fatal( ...$error );
			$this->logger->info( "Aborting stash upload due to hook - {status}",
				[
					'status' => (string)$status,
					'user' => $this->user->getName(),
					'filekey' => $this->mFileKey
				]
			);
			return $status;
		}
		try {
			$this->mStashFile = parent::doStashFile( $this->user );
		} catch ( UploadStashException $e ) {
			$this->logger->warning( "Could not stash file for {user} because {error} {msg}",
				[
					'user' => $this->user->getName(),
					'error' => get_class( $e ),
					'msg' => $e->getMessage(),
					'filekey' => $this->mFileKey
				]
			);
			$status->fatal( 'uploadstash-exception', get_class( $e ), $e->getMessage() );
			return $status;
		}

		$tAmount = microtime( true ) - $tStart;
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable tmpFile is set when tmpPath is set here
		$this->mStashFile->setLocalReference( $tmpFile ); // reuse (e.g. for getImageInfo())
		$this->logger->info( "Stashed combined ({chunks} chunks) of {oldkey} under new name {filekey}",
			[
				'chunks' => $i,
				'stashTime' => $tAmount,
				'oldpath' => $this->mVirtualTempPath,
				'filekey' => $this->mStashFile->getFileKey(),
				'oldkey' => $oldFileKey,
				'newpath' => $this->mStashFile->getPath(),
				'user' => $this->user->getName()
			]
		);
		wfDebugLog( 'fileconcatenate', "Stashed combined file ($i chunks) in $tAmount seconds." );

		return $status;
	}

	/**
	 * Returns the virtual chunk location:
	 * @param int $index
	 * @return string
	 */
	private function getVirtualChunkLocation( $index ) {
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
					$this->logger->info( "Error verifying upload chunk {msg}",
						[
							'user' => $this->user->getName(),
							'msg' => $e->getMessage(),
							'chunkIndex' => $this->mChunkIndex,
							'filekey' => $this->mFileKey
						]
					);

					return Status::newFatal( $e->msg );
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
		$this->logger->info( "update chunk status for {filekey} offset: {offset} inx: {inx}",
			[
				'offset' => $this->getOffset(),
				'inx' => $this->getChunkIndex(),
				'filekey' => $this->mFileKey,
				'user' => $this->user->getName()
			]
		);

		$dbw = $this->repo->getPrimaryDB();
		$dbw->newUpdateQueryBuilder()
			->update( 'uploadstash' )
			->set( [
				'us_status' => 'chunks',
				'us_chunk_inx' => $this->getChunkIndex(),
				'us_size' => $this->getOffset()
			] )
			->where( [ 'us_key' => $this->mFileKey ] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * Get the chunk db state and populate update relevant local values
	 */
	private function getChunkStatus() {
		// get primary db to avoid race conditions.
		// Otherwise, if chunk upload time < replag there will be spurious errors
		$dbw = $this->repo->getPrimaryDB();
		$row = $dbw->newSelectQueryBuilder()
			->select( [ 'us_chunk_inx', 'us_size', 'us_path' ] )
			->from( 'uploadstash' )
			->where( [ 'us_key' => $this->mFileKey ] )
			->caller( __METHOD__ )->fetchRow();
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
		return $this->mChunkIndex ?? 0;
	}

	/**
	 * Get the offset at which the next uploaded chunk will be appended to
	 * @return int Current byte offset of the chunk file set
	 */
	public function getOffset() {
		return $this->mOffset ?? 0;
	}

	/**
	 * Output the chunk to disk
	 *
	 * @param string $chunkPath
	 * @throws UploadChunkFileException
	 * @return Status
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
			$error = $this->logFileBackendStatus(
				$storeStatus,
				'[{type}] Error storing chunk in "{chunkPath}" for {fileKey} ({details})',
				[ 'chunkPath' => $chunkPath, 'fileKey' => $fileKey ]
			);
			throw new UploadChunkFileException( "Error storing file in '{chunkPath}': " .
				implode( '; ', $error ), [ 'chunkPath' => $chunkPath ] );
		}

		return $storeStatus;
	}

	private function getChunkFileKey( ?int $index = null ): string {
		return $this->mFileKey . '.' . ( $index ?? $this->getChunkIndex() );
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
			throw new UploadChunkVerificationException( $res );
		}
	}

	/**
	 * Log a status object from FileBackend functions (via FileRepo functions) to the upload log channel.
	 * Return a array with the first error to build up a exception message
	 *
	 * @param Status $status
	 * @param string $logMessage
	 * @param array $context
	 * @return array
	 */
	private function logFileBackendStatus( Status $status, string $logMessage, array $context = [] ): array {
		$logger = $this->logger;
		$errorToThrow = null;
		$warningToThrow = null;

		foreach ( $status->getErrors() as $errorItem ) {
			// The message key stands for distinct error situation from the file backend,
			// each error situation should be shown up in aggregated stats as own point, replace in message
			$logMessageType = str_replace( '{type}', $errorItem['message'], $logMessage );

			// The message arguments often contains the name of the failing datacenter or file names
			// and should not show up in aggregated stats, add to context
			$context['details'] = implode( '; ', $errorItem['params'] );
			$context['user'] = $this->user->getName();

			if ( $errorItem['type'] === 'error' ) {
				// Use the first error of the list for the exception text
				$errorToThrow ??= [ $errorItem['message'], ...$errorItem['params'] ];
				$logger->error( $logMessageType, $context );
			} else {
				// When no error is found, fall back to the first warning
				$warningToThrow ??= [ $errorItem['message'], ...$errorItem['params'] ];
				$logger->warning( $logMessageType, $context );
			}
		}
		return $errorToThrow ?? $warningToThrow ?? [ 'unknown', 'no error recorded' ];
	}
}
