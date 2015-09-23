<?php
/**
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
 * @ingroup RevisionDelete
 */

/**
 * List for oldimage table items
 */
class RevDelFileList extends RevDelList {
	/** @var array */
	public $storeBatch;

	/** @var array */
	public $deleteBatch;

	/** @var array */
	public $cleanupBatch;

	public function getType() {
		return 'oldimage';
	}

	public static function getRelationType() {
		return 'oi_archive_name';
	}

	public static function getRestriction() {
		return 'deleterevision';
	}

	public static function getRevdelConstant() {
		return File::DELETED_FILE;
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$archiveNames = array();
		foreach ( $this->ids as $timestamp ) {
			$archiveNames[] = $timestamp . '!' . $this->title->getDBkey();
		}

		return $db->select(
			'oldimage',
			OldLocalFile::selectFields(),
			array(
				'oi_name' => $this->title->getDBkey(),
				'oi_archive_name' => $archiveNames
			),
			__METHOD__,
			array( 'ORDER BY' => 'oi_timestamp DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDelFileItem( $this, $row );
	}

	public function clearFileOps() {
		$this->deleteBatch = array();
		$this->storeBatch = array();
		$this->cleanupBatch = array();
	}

	public function doPreCommitUpdates() {
		$status = Status::newGood();
		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $this->storeBatch ) {
			$status->merge( $repo->storeBatch( $this->storeBatch, FileRepo::OVERWRITE_SAME ) );
		}
		if ( !$status->isOK() ) {
			return $status;
		}
		if ( $this->deleteBatch ) {
			$status->merge( $repo->deleteBatch( $this->deleteBatch ) );
		}
		if ( !$status->isOK() ) {
			// Running cleanupDeletedBatch() after a failed storeBatch() with the DB already
			// modified (but destined for rollback) causes data loss
			return $status;
		}
		if ( $this->cleanupBatch ) {
			$status->merge( $repo->cleanupDeletedBatch( $this->cleanupBatch ) );
		}

		return $status;
	}

	public function doPostCommitUpdates() {
		$file = wfLocalFile( $this->title );
		$file->purgeCache();
		$file->purgeDescription();
		$purgeUrls = array();
		foreach ( $this->ids as $timestamp ) {
			$archiveName = $timestamp . '!' . $this->title->getDBkey();
			$file->purgeOldThumbnails( $archiveName );
			$purgeUrls[] = $file->getArchiveUrl( $archiveName );
		}
		if ( $this->getConfig()->get( 'UseSquid' ) ) {
			// purge full images from cache
			SquidUpdate::purge( $purgeUrls );
		}

		return Status::newGood();
	}

	public function getSuppressBit() {
		return File::DELETED_RESTRICTED;
	}
}
