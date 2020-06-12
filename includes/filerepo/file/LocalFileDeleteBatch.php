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
use MediaWiki\Revision\RevisionRecord;

/**
 * Helper class for file deletion
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

	/** @var array Items to be processed in the deletion batch */
	private $deletionBatch;

	/** @var bool Whether to suppress all suppressable fields when deleting */
	private $suppress;

	/** @var Status */
	private $status;

	/** @var User */
	private $user;

	/**
	 * @param File $file
	 * @param string|User $param2
	 * @param bool|string $param3
	 * @param User|null|bool $param4
	 *
	 * Old signature: $file, $reason = '', $suppress = false, $user = null
	 * New signature: $file, $user, $reason = '', $suppress = false
	 *
	 * See T245710 for more
	 */
	public function __construct(
		File $file,
		$param2 = '',
		$param3 = '',
		$param4 = false
	) {
		$this->file = $file;

		if ( $param2 instanceof User ) {
			// New signature
			$user = $param2;
			$reason = $param3;
			$suppress = $param4;
		} else {
			// Old signature
			wfDeprecatedMsg(
				'Construction of ' . __CLASS__ . ' without passing a user as ' .
				'the second parameter was deprecated in MediaWiki 1.35. ' .
				'See T245710 for more',
				'1.35'
			);

			$reason = $param2;

			// Suppress defaults to false if not provided
			$suppress = ( $param3 === '' ? false : $param3 );

			if ( $param4 === false ) {
				global $wgUser;
				$user = $wgUser;
			} else {
				$user = $param4;
			}
		}

		$this->reason = $reason;
		$this->suppress = $suppress;
		$this->user = $user;
		$this->status = $file->repo->newGood();
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

		$dbw = $this->file->repo->getMasterDB();
		$result = $dbw->select( 'oldimage',
			[ 'oi_archive_name' ],
			[ 'oi_name' => $this->file->getName() ],
			__METHOD__
		);

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
	 * @return array
	 */
	protected function getHashes() {
		$hashes = [];
		list( $oldRels, $deleteCurrent ) = $this->getOldRels();

		if ( $deleteCurrent ) {
			$hashes['.'] = $this->file->getSha1();
		}

		if ( count( $oldRels ) ) {
			$dbw = $this->file->repo->getMasterDB();
			$res = $dbw->select(
				'oldimage',
				[ 'oi_archive_name', 'oi_sha1' ],
				[ 'oi_archive_name' => array_map( 'strval', array_keys( $oldRels ) ),
					'oi_name' => $this->file->getName() ], // performance
				__METHOD__
			);

			foreach ( $res as $row ) {
				if ( rtrim( $row->oi_sha1, "\0" ) === '' ) {
					// Get the hash from the file
					$oldUrl = $this->file->getArchiveVirtualUrl( $row->oi_archive_name );
					$props = $this->file->repo->getFileProps( $oldUrl );

					if ( $props['fileExists'] ) {
						// Upgrade the oldimage row
						$dbw->update( 'oldimage',
							[ 'oi_sha1' => $props['sha1'] ],
							[ 'oi_name' => $this->file->getName(), 'oi_archive_name' => $row->oi_archive_name ],
							__METHOD__ );
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
			$this->status->error( 'filedelete-old-unregistered', $name );
		}

		foreach ( $hashes as $name => $hash ) {
			if ( !$hash ) {
				$this->status->error( 'filedelete-missing', $this->srcRels[$name] );
				unset( $hashes[$name] );
			}
		}

		return $hashes;
	}

	protected function doDBInserts() {
		$now = time();
		$dbw = $this->file->repo->getMasterDB();

		$commentStore = MediaWikiServices::getInstance()->getCommentStore();
		$actorMigration = ActorMigration::newMigration();

		$encTimestamp = $dbw->addQuotes( $dbw->timestamp( $now ) );
		$encUserId = $dbw->addQuotes( $this->user->getId() );
		$encGroup = $dbw->addQuotes( 'deleted' );
		$ext = $this->file->getExtension();
		$dotExt = $ext === '' ? '' : ".$ext";
		$encExt = $dbw->addQuotes( $dotExt );
		list( $oldRels, $deleteCurrent ) = $this->getOldRels();

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
				[ 'img_name' => $this->file->getName() ], __METHOD__, [], [], $joins );
		}

		if ( count( $oldRels ) ) {
			$fileQuery = OldLocalFile::getQueryInfo();
			$res = $dbw->select(
				$fileQuery['tables'],
				$fileQuery['fields'],
				[
					'oi_name' => $this->file->getName(),
					'oi_archive_name' => array_map( 'strval', array_keys( $oldRels ) )
				],
				__METHOD__,
				[ 'FOR UPDATE' ],
				$fileQuery['joins']
			);
			$rowsInsert = [];
			if ( $res->numRows() ) {
				$reason = $commentStore->createComment( $dbw, $this->reason );
				foreach ( $res as $row ) {
					$comment = $commentStore->getComment( 'oi_description', $row );
					$user = User::newFromAnyId( $row->oi_user, $row->oi_user_text, $row->oi_actor );
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
						'fa_timestamp' => $row->oi_timestamp,
						'fa_sha1' => $row->oi_sha1
					] + $commentStore->insert( $dbw, 'fa_deleted_reason', $reason )
					+ $commentStore->insert( $dbw, 'fa_description', $comment )
					+ $actorMigration->getInsertValues( $dbw, 'fa_user', $user );
				}
			}

			$dbw->insert( 'filearchive', $rowsInsert, __METHOD__ );
		}
	}

	private function doDBDeletes() {
		$dbw = $this->file->repo->getMasterDB();
		list( $oldRels, $deleteCurrent ) = $this->getOldRels();

		if ( count( $oldRels ) ) {
			$dbw->delete( 'oldimage',
				[
					'oi_name' => $this->file->getName(),
					'oi_archive_name' => array_map( 'strval', array_keys( $oldRels ) )
				], __METHOD__ );
		}

		if ( $deleteCurrent ) {
			$dbw->delete( 'image', [ 'img_name' => $this->file->getName() ], __METHOD__ );
		}
	}

	/**
	 * Run the transaction
	 * @return Status
	 */
	public function execute() {
		$repo = $this->file->getRepo();
		$this->file->lock();

		// Prepare deletion batch
		$hashes = $this->getHashes();
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
				$this->status->merge( $checkStatus );
				return $this->status;
			}
			$this->deletionBatch = $checkStatus->value;

			// Execute the file deletion batch
			$status = $this->file->repo->deleteBatch( $this->deletionBatch );
			if ( !$status->isGood() ) {
				$this->status->merge( $status );
			}
		}

		if ( !$this->status->isOK() ) {
			// Critical file deletion error; abort
			$this->file->unlock();

			return $this->status;
		}

		// Copy the image/oldimage rows to filearchive
		$this->doDBInserts();
		// Delete image/oldimage rows
		$this->doDBDeletes();

		// Commit and return
		$this->file->unlock();

		return $this->status;
	}

	/**
	 * Removes non-existent files from a deletion batch.
	 * @param array[] $batch
	 * @return Status
	 */
	protected function removeNonexistentFiles( $batch ) {
		$files = $newBatch = [];

		foreach ( $batch as $batchItem ) {
			list( $src, ) = $batchItem;
			$files[$src] = $this->file->repo->getVirtualUrl( 'public' ) . '/' . rawurlencode( $src );
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		foreach ( $batch as $batchItem ) {
			if ( $result[$batchItem[0]] ) {
				$newBatch[] = $batchItem;
			}
		}

		return Status::newGood( $newBatch );
	}
}
