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
use Wikimedia\Rdbms\IDatabase;

/**
 * Helper class for file movement
 * @ingroup FileAbstraction
 */
class LocalFileMoveBatch {
	/** @var LocalFile */
	protected $file;

	/** @var Title */
	protected $target;

	protected $cur;

	protected $olds;

	protected $oldCount;

	protected $archive;

	/** @var IDatabase */
	protected $db;

	/** @var string */
	protected $oldHash;

	/** @var string */
	protected $newHash;

	/** @var string */
	protected $oldName;

	/** @var string */
	protected $newName;

	/** @var string */
	protected $oldRel;

	/** @var string */
	protected $newRel;

	/**
	 * @param File $file
	 * @param Title $target
	 */
	function __construct( File $file, Title $target ) {
		$this->file = $file;
		$this->target = $target;
		$this->oldHash = $this->file->repo->getHashPath( $this->file->getName() );
		$this->newHash = $this->file->repo->getHashPath( $this->target->getDBkey() );
		$this->oldName = $this->file->getName();
		$this->newName = $this->file->repo->getNameFromTitle( $this->target );
		$this->oldRel = $this->oldHash . $this->oldName;
		$this->newRel = $this->newHash . $this->newName;
		$this->db = $file->getRepo()->getMasterDB();
	}

	/**
	 * Add the current image to the batch
	 */
	public function addCurrent() {
		$this->cur = [ $this->oldRel, $this->newRel ];
	}

	/**
	 * Add the old versions of the image to the batch
	 * @return string[] List of archive names from old versions
	 */
	public function addOlds() {
		$archiveBase = 'archive';
		$this->olds = [];
		$this->oldCount = 0;
		$archiveNames = [];

		$result = $this->db->select( 'oldimage',
			[ 'oi_archive_name', 'oi_deleted' ],
			[ 'oi_name' => $this->oldName ],
			__METHOD__,
			[ 'LOCK IN SHARE MODE' ] // ignore snapshot
		);

		foreach ( $result as $row ) {
			$archiveNames[] = $row->oi_archive_name;
			$oldName = $row->oi_archive_name;
			$bits = explode( '!', $oldName, 2 );

			if ( count( $bits ) != 2 ) {
				wfDebug( "Old file name missing !: '$oldName' \n" );
				continue;
			}

			list( $timestamp, $filename ) = $bits;

			if ( $this->oldName != $filename ) {
				wfDebug( "Old file name doesn't match: '$oldName' \n" );
				continue;
			}

			$this->oldCount++;

			// Do we want to add those to oldCount?
			if ( $row->oi_deleted & File::DELETED_FILE ) {
				continue;
			}

			$this->olds[] = [
				"{$archiveBase}/{$this->oldHash}{$oldName}",
				"{$archiveBase}/{$this->newHash}{$timestamp}!{$this->newName}"
			];
		}

		return $archiveNames;
	}

	/**
	 * Perform the move.
	 * @return Status
	 */
	public function execute() {
		$repo = $this->file->repo;
		$status = $repo->newGood();
		$destFile = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
			->newFile( $this->target );

		$this->file->lock();
		$destFile->lock(); // quickly fail if destination is not available

		$triplets = $this->getMoveTriplets();
		$checkStatus = $this->removeNonexistentFiles( $triplets );
		if ( !$checkStatus->isGood() ) {
			$destFile->unlock();
			$this->file->unlock();
			$status->merge( $checkStatus ); // couldn't talk to file backend
			return $status;
		}
		$triplets = $checkStatus->value;

		// Verify the file versions metadata in the DB.
		$statusDb = $this->verifyDBUpdates();
		if ( !$statusDb->isGood() ) {
			$destFile->unlock();
			$this->file->unlock();
			$statusDb->setOK( false );

			return $statusDb;
		}

		if ( !$repo->hasSha1Storage() ) {
			// Copy the files into their new location.
			// If a prior process fataled copying or cleaning up files we tolerate any
			// of the existing files if they are identical to the ones being stored.
			$statusMove = $repo->storeBatch( $triplets, FileRepo::OVERWRITE_SAME );
			wfDebugLog( 'imagemove', "Moved files for {$this->file->getName()}: " .
				"{$statusMove->successCount} successes, {$statusMove->failCount} failures" );
			if ( !$statusMove->isGood() ) {
				// Delete any files copied over (while the destination is still locked)
				$this->cleanupTarget( $triplets );
				$destFile->unlock();
				$this->file->unlock();
				wfDebugLog( 'imagemove', "Error in moving files: "
					. $statusMove->getWikiText( false, false, 'en' ) );
				$statusMove->setOK( false );

				return $statusMove;
			}
			$status->merge( $statusMove );
		}

		// Rename the file versions metadata in the DB.
		$this->doDBUpdates();

		wfDebugLog( 'imagemove', "Renamed {$this->file->getName()} in database: " .
			"{$statusDb->successCount} successes, {$statusDb->failCount} failures" );

		$destFile->unlock();
		$this->file->unlock();

		// Everything went ok, remove the source files
		$this->cleanupSource( $triplets );

		$status->merge( $statusDb );

		return $status;
	}

	/**
	 * Verify the database updates and return a new Status indicating how
	 * many rows would be updated.
	 *
	 * @return Status
	 */
	protected function verifyDBUpdates() {
		$repo = $this->file->repo;
		$status = $repo->newGood();
		$dbw = $this->db;

		$hasCurrent = $dbw->lockForUpdate(
			'image',
			[ 'img_name' => $this->oldName ],
			__METHOD__
		);
		$oldRowCount = $dbw->lockForUpdate(
			'oldimage',
			[ 'oi_name' => $this->oldName ],
			__METHOD__
		);

		if ( $hasCurrent ) {
			$status->successCount++;
		} else {
			$status->failCount++;
		}
		$status->successCount += $oldRowCount;
		// T36934: oldCount is based on files that actually exist.
		// There may be more DB rows than such files, in which case $affected
		// can be greater than $total. We use max() to avoid negatives here.
		$status->failCount += max( 0, $this->oldCount - $oldRowCount );
		if ( $status->failCount ) {
			$status->error( 'imageinvalidfilename' );
		}

		return $status;
	}

	/**
	 * Do the database updates and return a new Status indicating how
	 * many rows where updated.
	 */
	protected function doDBUpdates() {
		$dbw = $this->db;

		// Update current image
		$dbw->update(
			'image',
			[ 'img_name' => $this->newName ],
			[ 'img_name' => $this->oldName ],
			__METHOD__
		);

		// Update old images
		$dbw->update(
			'oldimage',
			[
				'oi_name' => $this->newName,
				'oi_archive_name = ' . $dbw->strreplace( 'oi_archive_name',
					$dbw->addQuotes( $this->oldName ), $dbw->addQuotes( $this->newName ) ),
			],
			[ 'oi_name' => $this->oldName ],
			__METHOD__
		);
	}

	/**
	 * Generate triplets for FileRepo::storeBatch().
	 * @return array[]
	 */
	protected function getMoveTriplets() {
		$moves = array_merge( [ $this->cur ], $this->olds );
		$triplets = []; // The format is: (srcUrl, destZone, destUrl)

		foreach ( $moves as $move ) {
			// $move: (oldRelativePath, newRelativePath)
			$srcUrl = $this->file->repo->getVirtualUrl() . '/public/' . rawurlencode( $move[0] );
			$triplets[] = [ $srcUrl, 'public', $move[1] ];
			wfDebugLog(
				'imagemove',
				"Generated move triplet for {$this->file->getName()}: {$srcUrl} :: public :: {$move[1]}"
			);
		}

		return $triplets;
	}

	/**
	 * Removes non-existent files from move batch.
	 * @param array $triplets
	 * @return Status
	 */
	protected function removeNonexistentFiles( $triplets ) {
		$files = [];

		foreach ( $triplets as $file ) {
			$files[$file[0]] = $file[0];
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		$filteredTriplets = [];
		foreach ( $triplets as $file ) {
			if ( $result[$file[0]] ) {
				$filteredTriplets[] = $file;
			} else {
				wfDebugLog( 'imagemove', "File {$file[0]} does not exist" );
			}
		}

		return Status::newGood( $filteredTriplets );
	}

	/**
	 * Cleanup a partially moved array of triplets by deleting the target
	 * files. Called if something went wrong half way.
	 * @param array[] $triplets
	 */
	protected function cleanupTarget( $triplets ) {
		// Create dest pairs from the triplets
		$pairs = [];
		foreach ( $triplets as $triplet ) {
			// $triplet: (old source virtual URL, dst zone, dest rel)
			$pairs[] = [ $triplet[1], $triplet[2] ];
		}

		$this->file->repo->cleanupBatch( $pairs );
	}

	/**
	 * Cleanup a fully moved array of triplets by deleting the source files.
	 * Called at the end of the move process if everything else went ok.
	 * @param array[] $triplets
	 */
	protected function cleanupSource( $triplets ) {
		// Create source file names from the triplets
		$files = [];
		foreach ( $triplets as $triplet ) {
			$files[] = $triplet[0];
		}

		$this->file->repo->cleanupBatch( $files );
	}
}
