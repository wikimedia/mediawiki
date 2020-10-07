<?php
/**
 * Local file in the wiki's own database.
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
 * @ingroup FileAbstraction
 */

use MediaWiki\MediaWikiServices;

/**
 * Helper class for file undeletion
 * @ingroup FileAbstraction
 */
class LocalFileRestoreBatch {
	/** @var LocalFile */
	private $file;

	/** @var string[] List of file IDs to restore */
	private $cleanupBatch;

	/** @var string[] List of file IDs to restore */
	private $ids;

	/** @var bool Add all revisions of the file */
	private $all;

	/** @var bool Whether to remove all settings for suppressed fields */
	private $unsuppress = false;

	/**
	 * @param LocalFile $file
	 * @param bool $unsuppress
	 */
	public function __construct( LocalFile $file, $unsuppress = false ) {
		$this->file = $file;
		$this->cleanupBatch = [];
		$this->ids = [];
		$this->unsuppress = $unsuppress;
	}

	/**
	 * Add a file by ID
	 * @param int $fa_id
	 */
	public function addId( $fa_id ) {
		$this->ids[] = $fa_id;
	}

	/**
	 * Add a whole lot of files by ID
	 * @param int[] $ids
	 */
	public function addIds( $ids ) {
		$this->ids = array_merge( $this->ids, $ids );
	}

	/**
	 * Add all revisions of the file
	 */
	public function addAll() {
		$this->all = true;
	}

	/**
	 * Run the transaction, except the cleanup batch.
	 * The cleanup batch should be run in a separate transaction, because it locks different
	 * rows and there's no need to keep the image row locked while it's acquiring those locks
	 * The caller may have its own transaction open.
	 * So we save the batch and let the caller call cleanup()
	 * @return Status
	 */
	public function execute() {
		/** @var Language */
		global $wgLang;

		$repo = $this->file->getRepo();
		if ( !$this->all && !$this->ids ) {
			// Do nothing
			return $repo->newGood();
		}

		$lockOwnsTrx = $this->file->lock();

		$dbw = $this->file->repo->getMasterDB();

		$commentStore = MediaWikiServices::getInstance()->getCommentStore();
		$actorMigration = ActorMigration::newMigration();

		$status = $this->file->repo->newGood();

		$exists = (bool)$dbw->selectField( 'image', '1',
			[ 'img_name' => $this->file->getName() ],
			__METHOD__,
			// The lock() should already prevents changes, but this still may need
			// to bypass any transaction snapshot. However, if lock() started the
			// trx (which it probably did) then snapshot is post-lock and up-to-date.
			$lockOwnsTrx ? [] : [ 'LOCK IN SHARE MODE' ]
		);

		// Fetch all or selected archived revisions for the file,
		// sorted from the most recent to the oldest.
		$conditions = [ 'fa_name' => $this->file->getName() ];

		if ( !$this->all ) {
			$conditions['fa_id'] = $this->ids;
		}

		$arFileQuery = ArchivedFile::getQueryInfo();
		$result = $dbw->select(
			$arFileQuery['tables'],
			$arFileQuery['fields'],
			$conditions,
			__METHOD__,
			[ 'ORDER BY' => 'fa_timestamp DESC' ],
			$arFileQuery['joins']
		);

		$idsPresent = [];
		$storeBatch = [];
		$insertBatch = [];
		$insertCurrent = false;
		$deleteIds = [];
		$first = true;
		$archiveNames = [];

		foreach ( $result as $row ) {
			$idsPresent[] = $row->fa_id;

			if ( $row->fa_name != $this->file->getName() ) {
				$status->error( 'undelete-filename-mismatch', $wgLang->timeanddate( $row->fa_timestamp ) );
				$status->failCount++;
				continue;
			}

			if ( $row->fa_storage_key == '' ) {
				// Revision was missing pre-deletion
				$status->error( 'undelete-bad-store-key', $wgLang->timeanddate( $row->fa_timestamp ) );
				$status->failCount++;
				continue;
			}

			$deletedRel = $repo->getDeletedHashPath( $row->fa_storage_key ) .
				$row->fa_storage_key;
			$deletedUrl = $repo->getVirtualUrl() . '/deleted/' . $deletedRel;

			if ( isset( $row->fa_sha1 ) ) {
				$sha1 = $row->fa_sha1;
			} else {
				// old row, populate from key
				$sha1 = LocalRepo::getHashFromKey( $row->fa_storage_key );
			}

			# Fix leading zero
			if ( strlen( $sha1 ) == 32 && $sha1[0] == '0' ) {
				$sha1 = substr( $sha1, 1 );
			}

			if ( $row->fa_major_mime === null || $row->fa_major_mime == 'unknown'
				|| $row->fa_minor_mime === null || $row->fa_minor_mime == 'unknown'
				|| $row->fa_media_type === null || $row->fa_media_type == 'UNKNOWN'
				|| $row->fa_metadata === null
			) {
				// Refresh our metadata
				// Required for a new current revision; nice for older ones too. :)
				$props = MediaWikiServices::getInstance()->getRepoGroup()->getFileProps( $deletedUrl );
			} else {
				$props = [
					'minor_mime' => $row->fa_minor_mime,
					'major_mime' => $row->fa_major_mime,
					'media_type' => $row->fa_media_type,
					'metadata' => $row->fa_metadata
				];
			}

			$comment = $commentStore->getComment( 'fa_description', $row );
			$user = User::newFromAnyId( $row->fa_user, $row->fa_user_text, $row->fa_actor );
			if ( $first && !$exists ) {
				// This revision will be published as the new current version
				$destRel = $this->file->getRel();
				$commentFields = $commentStore->insert( $dbw, 'img_description', $comment );
				$actorFields = $actorMigration->getInsertValues( $dbw, 'img_user', $user );
				$insertCurrent = [
					'img_name' => $row->fa_name,
					'img_size' => $row->fa_size,
					'img_width' => $row->fa_width,
					'img_height' => $row->fa_height,
					'img_metadata' => $props['metadata'],
					'img_bits' => $row->fa_bits,
					'img_media_type' => $props['media_type'],
					'img_major_mime' => $props['major_mime'],
					'img_minor_mime' => $props['minor_mime'],
					'img_timestamp' => $row->fa_timestamp,
					'img_sha1' => $sha1
				] + $commentFields + $actorFields;

				// The live (current) version cannot be hidden!
				if ( !$this->unsuppress && $row->fa_deleted ) {
					$status->fatal( 'undeleterevdel' );
					$this->file->unlock();
					return $status;
				}
			} else {
				$archiveName = $row->fa_archive_name;

				if ( $archiveName == '' ) {
					// This was originally a current version; we
					// have to devise a new archive name for it.
					// Format is <timestamp of archiving>!<name>
					$timestamp = wfTimestamp( TS_UNIX, $row->fa_deleted_timestamp );

					do {
						$archiveName = wfTimestamp( TS_MW, $timestamp ) . '!' . $row->fa_name;
						$timestamp++;
					} while ( isset( $archiveNames[$archiveName] ) );
				}

				$archiveNames[$archiveName] = true;
				$destRel = $this->file->getArchiveRel( $archiveName );
				$insertBatch[] = [
					'oi_name' => $row->fa_name,
					'oi_archive_name' => $archiveName,
					'oi_size' => $row->fa_size,
					'oi_width' => $row->fa_width,
					'oi_height' => $row->fa_height,
					'oi_bits' => $row->fa_bits,
					'oi_timestamp' => $row->fa_timestamp,
					'oi_metadata' => $props['metadata'],
					'oi_media_type' => $props['media_type'],
					'oi_major_mime' => $props['major_mime'],
					'oi_minor_mime' => $props['minor_mime'],
					'oi_deleted' => $this->unsuppress ? 0 : $row->fa_deleted,
					'oi_sha1' => $sha1
				] + $commentStore->insert( $dbw, 'oi_description', $comment )
				+ $actorMigration->getInsertValues( $dbw, 'oi_user', $user );
			}

			$deleteIds[] = $row->fa_id;

			if ( !$this->unsuppress && $row->fa_deleted & File::DELETED_FILE ) {
				// private files can stay where they are
				$status->successCount++;
			} else {
				$storeBatch[] = [ $deletedUrl, 'public', $destRel ];
				$this->cleanupBatch[] = $row->fa_storage_key;
			}

			$first = false;
		}

		unset( $result );

		// Add a warning to the status object for missing IDs
		$missingIds = array_diff( $this->ids, $idsPresent );

		foreach ( $missingIds as $id ) {
			$status->error( 'undelete-missing-filearchive', $id );
		}

		if ( !$repo->hasSha1Storage() ) {
			// Remove missing files from batch, so we don't get errors when undeleting them
			$checkStatus = $this->removeNonexistentFiles( $storeBatch );
			if ( !$checkStatus->isGood() ) {
				$status->merge( $checkStatus );
				return $status;
			}
			$storeBatch = $checkStatus->value;

			// Run the store batch
			// Use the OVERWRITE_SAME flag to smooth over a common error
			$storeStatus = $this->file->repo->storeBatch( $storeBatch, FileRepo::OVERWRITE_SAME );
			$status->merge( $storeStatus );

			if ( !$status->isGood() ) {
				// Even if some files could be copied, fail entirely as that is the
				// easiest thing to do without data loss
				$this->cleanupFailedBatch( $storeStatus, $storeBatch );
				$status->setOK( false );
				$this->file->unlock();

				return $status;
			}
		}

		// Run the DB updates
		// Because we have locked the image row, key conflicts should be rare.
		// If they do occur, we can roll back the transaction at this time with
		// no data loss, but leaving unregistered files scattered throughout the
		// public zone.
		// This is not ideal, which is why it's important to lock the image row.
		if ( $insertCurrent ) {
			$dbw->insert( 'image', $insertCurrent, __METHOD__ );
		}

		if ( $insertBatch ) {
			$dbw->insert( 'oldimage', $insertBatch, __METHOD__ );
		}

		if ( $deleteIds ) {
			$dbw->delete( 'filearchive',
				[ 'fa_id' => $deleteIds ],
				__METHOD__ );
		}

		// If store batch is empty (all files are missing), deletion is to be considered successful
		if ( $status->successCount > 0 || !$storeBatch || $repo->hasSha1Storage() ) {
			if ( !$exists ) {
				wfDebug( __METHOD__ . " restored {$status->successCount} items, creating a new current" );

				DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [ 'images' => 1 ] ) );

				$this->file->purgeEverything();
			} else {
				wfDebug( __METHOD__ . " restored {$status->successCount} as archived versions" );
				$this->file->purgeDescription();
			}
		}

		$this->file->unlock();

		return $status;
	}

	/**
	 * Removes non-existent files from a store batch.
	 * @param array[] $triplets
	 * @return Status
	 */
	protected function removeNonexistentFiles( $triplets ) {
		$files = $filteredTriplets = [];
		foreach ( $triplets as $file ) {
			$files[$file[0]] = $file[0];
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		foreach ( $triplets as $file ) {
			if ( $result[$file[0]] ) {
				$filteredTriplets[] = $file;
			}
		}

		return Status::newGood( $filteredTriplets );
	}

	/**
	 * Removes non-existent files from a cleanup batch.
	 * @param string[] $batch
	 * @return string[]
	 */
	protected function removeNonexistentFromCleanup( $batch ) {
		$files = $newBatch = [];
		$repo = $this->file->repo;

		foreach ( $batch as $file ) {
			$files[$file] = $repo->getVirtualUrl( 'deleted' ) . '/' .
				rawurlencode( $repo->getDeletedHashPath( $file ) . $file );
		}

		$result = $repo->fileExistsBatch( $files );

		foreach ( $batch as $file ) {
			if ( $result[$file] ) {
				$newBatch[] = $file;
			}
		}

		return $newBatch;
	}

	/**
	 * Delete unused files in the deleted zone.
	 * This should be called from outside the transaction in which execute() was called.
	 * @return Status
	 */
	public function cleanup() {
		if ( !$this->cleanupBatch ) {
			return $this->file->repo->newGood();
		}

		$this->cleanupBatch = $this->removeNonexistentFromCleanup( $this->cleanupBatch );

		$status = $this->file->repo->cleanupDeletedBatch( $this->cleanupBatch );

		return $status;
	}

	/**
	 * Cleanup a failed batch. The batch was only partially successful, so
	 * rollback by removing all items that were successfully copied.
	 *
	 * @param Status $storeStatus
	 * @param array[] $storeBatch
	 */
	protected function cleanupFailedBatch( $storeStatus, $storeBatch ) {
		$cleanupBatch = [];

		foreach ( $storeStatus->success as $i => $success ) {
			// Check if this item of the batch was successfully copied
			if ( $success ) {
				// Item was successfully copied and needs to be removed again
				// Extract ($dstZone, $dstRel) from the batch
				$cleanupBatch[] = [ $storeBatch[$i][1], $storeBatch[$i][2] ];
			}
		}
		$this->file->repo->cleanupBatch( $cleanupBatch );
	}
}
