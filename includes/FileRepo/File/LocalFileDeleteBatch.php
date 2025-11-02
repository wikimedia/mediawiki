<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use MediaWiki\User\UserIdentity;
use StatusValue;
use Wikimedia\ScopedCallback;

/**
 * Helper class for file deletion
 *
 * @internal
 * @ingroup FileAbstraction
 */
class LocalFileDeleteBatch {
	/** @var LocalFile */
	private $file;

	/** @var string */
	private $reason;

	/** @var array */
	private $srcRels = [];

	/** @var array */
	private $archiveUrls = [];

	/** @var array[] Items to be processed in the deletion batch */
	private $deletionBatch;

	/** @var bool Whether to suppress all suppressable fields when deleting */
	private $suppress;

	/** @var UserIdentity */
	private $user;

	/**
	 * @param File $file
	 * @param UserIdentity $user
	 * @param string $reason
	 * @param bool $suppress
	 */
	public function __construct(
		File $file,
		UserIdentity $user,
		$reason = '',
		$suppress = false
	) {
		$this->file = $file;
		$this->user = $user;
		$this->reason = $reason;
		$this->suppress = $suppress;
	}

	public function addCurrent() {
		$this->srcRels['.'] = $this->file->getRel();
	}

	/**
	 * @param string $oldName
	 */
	public function addOld( $oldName ) {
		$this->srcRels[$oldName] = $this->file->getArchiveRel( $oldName );
		$this->archiveUrls[] = $this->file->getArchiveUrl( $oldName );
	}

	/**
	 * Add the old versions of the image to the batch
	 * @return string[] List of archive names from old versions
	 */
	public function addOlds() {
		$archiveNames = [];

		$dbw = $this->file->repo->getPrimaryDB();
		$result = $dbw->newSelectQueryBuilder()
			->select( [ 'oi_archive_name' ] )
			->from( 'oldimage' )
			->where( [ 'oi_name' => $this->file->getName() ] )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $result as $row ) {
			$this->addOld( $row->oi_archive_name );
			$archiveNames[] = $row->oi_archive_name;
		}

		return $archiveNames;
	}

	/**
	 * @return array
	 */
	protected function getOldRels() {
		if ( !isset( $this->srcRels['.'] ) ) {
			$oldRels =& $this->srcRels;
			$deleteCurrent = false;
		} else {
			$oldRels = $this->srcRels;
			unset( $oldRels['.'] );
			$deleteCurrent = true;
		}

		return [ $oldRels, $deleteCurrent ];
	}

	/**
	 * @param StatusValue $status To add error messages to
	 * @return array
	 */
	protected function getHashes( StatusValue $status ): array {
		$hashes = [];
		[ $oldRels, $deleteCurrent ] = $this->getOldRels();

		if ( $deleteCurrent ) {
			$hashes['.'] = $this->file->getSha1();
		}

		if ( count( $oldRels ) ) {
			$dbw = $this->file->repo->getPrimaryDB();
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'oi_archive_name', 'oi_sha1' ] )
				->from( 'oldimage' )
				->where( [
					'oi_archive_name' => array_map( 'strval', array_keys( $oldRels ) ),
					'oi_name' => $this->file->getName() // performance
				] )
				->caller( __METHOD__ )->fetchResultSet();

			foreach ( $res as $row ) {
				if ( $row->oi_archive_name === '' ) {
					// File lost, the check simulates OldLocalFile::exists
					$hashes[$row->oi_archive_name] = false;
					continue;
				}
				if ( rtrim( $row->oi_sha1, "\0" ) === '' ) {
					// Get the hash from the file
					$oldUrl = $this->file->getArchiveVirtualUrl( $row->oi_archive_name );
					$props = $this->file->repo->getFileProps( $oldUrl );

					if ( $props['fileExists'] ) {
						// Upgrade the oldimage row
						$dbw->newUpdateQueryBuilder()
							->update( 'oldimage' )
							->set( [ 'oi_sha1' => $props['sha1'] ] )
							->where( [
								'oi_name' => $this->file->getName(),
								'oi_archive_name' => $row->oi_archive_name,
							] )
							->caller( __METHOD__ )->execute();
						$hashes[$row->oi_archive_name] = $props['sha1'];
					} else {
						$hashes[$row->oi_archive_name] = false;
					}
				} else {
					$hashes[$row->oi_archive_name] = $row->oi_sha1;
				}
			}
		}

		$missing = array_diff_key( $this->srcRels, $hashes );

		foreach ( $missing as $name => $rel ) {
			$status->error( 'filedelete-old-unregistered', $name );
		}

		foreach ( $hashes as $name => $hash ) {
			if ( !$hash ) {
				$status->error( 'filedelete-missing', $this->srcRels[$name] );
				unset( $hashes[$name] );
			}
		}

		return $hashes;
	}

	protected function doDBInserts() {
		$now = time();
		$dbw = $this->file->repo->getPrimaryDB();

		$commentStore = MediaWikiServices::getInstance()->getCommentStore();

		$encTimestamp = $dbw->addQuotes( $dbw->timestamp( $now ) );
		$encUserId = $dbw->addQuotes( $this->user->getId() );
		$encGroup = $dbw->addQuotes( 'deleted' );
		$ext = $this->file->getExtension();
		$dotExt = $ext === '' ? '' : ".$ext";
		$encExt = $dbw->addQuotes( $dotExt );
		[ $oldRels, $deleteCurrent ] = $this->getOldRels();

		// Bitfields to further suppress the content
		if ( $this->suppress ) {
			$bitfield = RevisionRecord::SUPPRESSED_ALL;
		} else {
			$bitfield = 'oi_deleted';
		}

		if ( $deleteCurrent ) {
			$tables = [ 'image' ];
			$fields = [
				'fa_storage_group' => $encGroup,
				'fa_storage_key' => $dbw->conditional(
					[ 'img_sha1' => '' ],
					$dbw->addQuotes( '' ),
					$dbw->buildConcat( [ "img_sha1", $encExt ] )
				),
				'fa_deleted_user' => $encUserId,
				'fa_deleted_timestamp' => $encTimestamp,
				'fa_deleted' => $this->suppress ? $bitfield : 0,
				'fa_name' => 'img_name',
				'fa_archive_name' => 'NULL',
				'fa_size' => 'img_size',
				'fa_width' => 'img_width',
				'fa_height' => 'img_height',
				'fa_metadata' => 'img_metadata',
				'fa_bits' => 'img_bits',
				'fa_media_type' => 'img_media_type',
				'fa_major_mime' => 'img_major_mime',
				'fa_minor_mime' => 'img_minor_mime',
				'fa_description_id' => 'img_description_id',
				'fa_timestamp' => 'img_timestamp',
				'fa_sha1' => 'img_sha1',
				'fa_actor' => 'img_actor',
			];
			$joins = [];

			$fields += array_map(
				[ $dbw, 'addQuotes' ],
				$commentStore->insert( $dbw, 'fa_deleted_reason', $this->reason )
			);

			$dbw->insertSelect( 'filearchive', $tables, $fields,
				[ 'img_name' => $this->file->getName() ], __METHOD__, [ 'IGNORE' ], [], $joins );
		}

		if ( count( $oldRels ) ) {
			$queryBuilder = FileSelectQueryBuilder::newForOldFile( $dbw );
			$queryBuilder
				->forUpdate()
				->where( [ 'oi_name' => $this->file->getName() ] )
				->andWhere( [ 'oi_archive_name' => array_map( 'strval', array_keys( $oldRels ) ) ] );
			$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
			if ( $res->numRows() ) {
				$reason = $commentStore->createComment( $dbw, $this->reason );
				$rowsInsert = [];
				foreach ( $res as $row ) {
					$comment = $commentStore->getComment( 'oi_description', $row );
					$rowsInsert[] = [
						// Deletion-specific fields
						'fa_storage_group' => 'deleted',
						'fa_storage_key' => ( $row->oi_sha1 === '' )
						? ''
						: "{$row->oi_sha1}{$dotExt}",
						'fa_deleted_user' => $this->user->getId(),
						'fa_deleted_timestamp' => $dbw->timestamp( $now ),
						// Counterpart fields
						'fa_deleted' => $this->suppress ? $bitfield : $row->oi_deleted,
						'fa_name' => $row->oi_name,
						'fa_archive_name' => $row->oi_archive_name,
						'fa_size' => $row->oi_size,
						'fa_width' => $row->oi_width,
						'fa_height' => $row->oi_height,
						'fa_metadata' => $row->oi_metadata,
						'fa_bits' => $row->oi_bits,
						'fa_media_type' => $row->oi_media_type,
						'fa_major_mime' => $row->oi_major_mime,
						'fa_minor_mime' => $row->oi_minor_mime,
						'fa_actor' => $row->oi_actor,
						'fa_timestamp' => $row->oi_timestamp,
						'fa_sha1' => $row->oi_sha1
					] + $commentStore->insert( $dbw, 'fa_deleted_reason', $reason )
					+ $commentStore->insert( $dbw, 'fa_description', $comment );
				}
				$dbw->newInsertQueryBuilder()
					->insertInto( 'filearchive' )
					->ignore()
					->rows( $rowsInsert )
					->caller( __METHOD__ )->execute();
			}
		}
	}

	private function doDBDeletes() {
		$dbw = $this->file->repo->getPrimaryDB();
		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);

		[ $oldRels, $deleteCurrent ] = $this->getOldRels();

		if ( count( $oldRels ) ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'oldimage' )
				->where( [
					'oi_name' => $this->file->getName(),
					'oi_archive_name' => array_map( 'strval', array_keys( $oldRels ) )
				] )
				->caller( __METHOD__ )->execute();
			if ( ( $migrationStage & SCHEMA_COMPAT_WRITE_NEW ) && $this->file->getFileIdFromName() ) {
				$delete = $dbw->newDeleteQueryBuilder()
					->deleteFrom( 'filerevision' )
					->where( [ 'fr_file' => $this->file->getFileIdFromName() ] );
				if ( !$deleteCurrent ) {
					// It's not full page deletion.
					$delete->andWhere( [ 'fr_archive_name' => array_map( 'strval', array_keys( $oldRels ) ) ] );
				}
				$delete->caller( __METHOD__ )->execute();

			}
		}

		if ( $deleteCurrent ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'image' )
				->where( [ 'img_name' => $this->file->getName() ] )
				->caller( __METHOD__ )->execute();
			if ( ( $migrationStage & SCHEMA_COMPAT_WRITE_NEW ) && $this->file->getFileIdFromName() ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'file' )
					->set( [
						'file_deleted' => $this->suppress ? 3 : 1,
						'file_latest' => 0
					] )
					->where( [ 'file_id' => $this->file->getFileIdFromName() ] )
					->caller( __METHOD__ )->execute();
				if ( !count( $oldRels ) ) {
					// Only the current version is uploaded and then deleted
					// TODO: After migration is done and old code is removed,
					// this should be refactored to become much simpler
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'filerevision' )
						->where( [ 'fr_file' => $this->file->getFileIdFromName() ] )
						->caller( __METHOD__ )->execute();
				}
			}
		}
	}

	/**
	 * Run the transaction
	 * @return Status
	 */
	public function execute() {
		$repo = $this->file->getRepo();
		$lockStatus = $this->file->acquireFileLock();
		if ( !$lockStatus->isOK() ) {
			return $lockStatus;
		}
		$unlockScope = new ScopedCallback( function () {
			$this->file->releaseFileLock();
		} );

		$status = $this->file->repo->newGood();
		// Prepare deletion batch
		$hashes = $this->getHashes( $status );
		$this->deletionBatch = [];
		$ext = $this->file->getExtension();
		$dotExt = $ext === '' ? '' : ".$ext";

		foreach ( $this->srcRels as $name => $srcRel ) {
			// Skip files that have no hash (e.g. missing DB record, or sha1 field and file source)
			if ( isset( $hashes[$name] ) ) {
				$hash = $hashes[$name];
				$key = $hash . $dotExt;
				$dstRel = $repo->getDeletedHashPath( $key ) . $key;
				$this->deletionBatch[$name] = [ $srcRel, $dstRel ];
			}
		}

		if ( !$repo->hasSha1Storage() ) {
			// Removes non-existent file from the batch, so we don't get errors.
			// This also handles files in the 'deleted' zone deleted via revision deletion.
			$checkStatus = $this->removeNonexistentFiles( $this->deletionBatch );
			if ( !$checkStatus->isGood() ) {
				$status->merge( $checkStatus );
				return $status;
			}
			$this->deletionBatch = $checkStatus->value;

			// Execute the file deletion batch
			$status = $this->file->repo->deleteBatch( $this->deletionBatch );
			if ( !$status->isGood() ) {
				$status->merge( $status );
			}
		}

		if ( !$status->isOK() ) {
			// Critical file deletion error; abort
			return $status;
		}

		$dbw = $this->file->repo->getPrimaryDB();

		$dbw->startAtomic( __METHOD__ );

		// Copy the image/oldimage rows to filearchive
		$this->doDBInserts();
		// Delete image/oldimage rows
		$this->doDBDeletes();

		// This is typically a no-op since we are wrapped by another atomic
		// section in FileDeleteForm and also the implicit transaction.
		$dbw->endAtomic( __METHOD__ );

		// Commit and return
		ScopedCallback::consume( $unlockScope );

		return $status;
	}

	/**
	 * Removes non-existent files from a deletion batch.
	 * @param array[] $batch
	 * @return Status A good status with existing files in $batch as value, or a fatal status in case of I/O errors.
	 */
	protected function removeNonexistentFiles( $batch ) {
		$files = [];

		foreach ( $batch as [ $src, /* dest */ ] ) {
			$files[$src] = $this->file->repo->getVirtualUrl( 'public' ) . '/' . rawurlencode( $src );
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		$newBatch = [];
		foreach ( $batch as $batchItem ) {
			if ( $result[$batchItem[0]] ) {
				$newBatch[] = $batchItem;
			}
		}

		return Status::newGood( $newBatch );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LocalFileDeleteBatch::class, 'LocalFileDeleteBatch' );
