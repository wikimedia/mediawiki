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
 */

namespace MediaWiki\FileRepo\File;

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\ScopedCallback;

/**
 * Helper class for file movement
 *
 * @ingroup FileAbstraction
 */
class LocalFileMoveBatch {
	/** @var LocalFile */
	protected $file;

	/** @var Title */
	protected $target;

	/** @var string[] */
	protected $cur;

	/** @var string[][] */
	protected $olds;

	/** @var int */
	protected $oldCount;

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

	/** @var LoggerInterface */
	private $logger;

	/** @var bool */
	private $haveSourceLock = false;

	/** @var bool */
	private $haveTargetLock = false;

	/** @var LocalFile|null */
	private $targetFile;

	public function __construct( LocalFile $file, Title $target ) {
		$this->file = $file;
		$this->target = $target;
		$this->oldHash = $this->file->repo->getHashPath( $this->file->getName() );
		$this->newHash = $this->file->repo->getHashPath( $this->target->getDBkey() );
		$this->oldName = $this->file->getName();
		$this->newName = $this->file->repo->getNameFromTitle( $this->target );
		$this->oldRel = $this->oldHash . $this->oldName;
		$this->newRel = $this->newHash . $this->newName;
		$this->db = $file->getRepo()->getPrimaryDB();

		$this->logger = LoggerFactory::getInstance( 'imagemove' );
	}

	/**
	 * Add the current image to the batch
	 *
	 * @return Status
	 */
	public function addCurrent() {
		$status = $this->acquireSourceLock();
		if ( $status->isOK() ) {
			$this->cur = [ $this->oldRel, $this->newRel ];
		}
		return $status;
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

		$result = $this->db->newSelectQueryBuilder()
			->select( [ 'oi_archive_name', 'oi_deleted' ] )
			->forUpdate() // ignore snapshot
			->from( 'oldimage' )
			->where( [ 'oi_name' => $this->oldName ] )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $result as $row ) {
			$archiveNames[] = $row->oi_archive_name;
			$oldName = $row->oi_archive_name;
			$bits = explode( '!', $oldName, 2 );

			if ( count( $bits ) != 2 ) {
				$this->logger->debug(
					'Old file name missing !: {oldName}',
					[ 'oldName' => $oldName ]
				);
				continue;
			}

			[ $timestamp, $filename ] = $bits;

			if ( $this->oldName != $filename ) {
				$this->logger->debug(
					'Old file name does not match: {oldName}',
					[ 'oldName' => $oldName ]
				);
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
	 * Acquire the source file lock, if it has not been acquired already
	 *
	 * @return Status
	 */
	protected function acquireSourceLock() {
		if ( $this->haveSourceLock ) {
			return Status::newGood();
		}
		$status = $this->file->acquireFileLock();
		if ( $status->isOK() ) {
			$this->haveSourceLock = true;
		}
		return $status;
	}

	/**
	 * Acquire the target file lock, if it has not been acquired already
	 *
	 * @return Status
	 */
	protected function acquireTargetLock() {
		if ( $this->haveTargetLock ) {
			return Status::newGood();
		}
		$status = $this->getTargetFile()->acquireFileLock();
		if ( $status->isOK() ) {
			$this->haveTargetLock = true;
		}
		return $status;
	}

	/**
	 * Release both file locks
	 */
	protected function releaseLocks() {
		if ( $this->haveSourceLock ) {
			$this->file->releaseFileLock();
			$this->haveSourceLock = false;
		}
		if ( $this->haveTargetLock ) {
			$this->getTargetFile()->releaseFileLock();
			$this->haveTargetLock = false;
		}
	}

	/**
	 * Get the target file
	 *
	 * @return LocalFile
	 */
	protected function getTargetFile() {
		if ( $this->targetFile === null ) {
			$this->targetFile = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
				->newFile( $this->target );
		}
		return $this->targetFile;
	}

	/**
	 * Perform the move.
	 * @return Status
	 */
	public function execute() {
		$repo = $this->file->repo;
		$status = $repo->newGood();

		$status->merge( $this->acquireSourceLock() );
		if ( !$status->isOK() ) {
			return $status;
		}
		$status->merge( $this->acquireTargetLock() );
		if ( !$status->isOK() ) {
			$this->releaseLocks();
			return $status;
		}
		$unlockScope = new ScopedCallback( function () {
			$this->releaseLocks();
		} );

		$triplets = $this->getMoveTriplets();
		$checkStatus = $this->removeNonexistentFiles( $triplets );
		if ( !$checkStatus->isGood() ) {
			$status->merge( $checkStatus ); // couldn't talk to file backend
			return $status;
		}
		$triplets = $checkStatus->value;

		// Verify the file versions metadata in the DB.
		$statusDb = $this->verifyDBUpdates();
		if ( !$statusDb->isGood() ) {
			$statusDb->setOK( false );

			return $statusDb;
		}

		if ( !$repo->hasSha1Storage() ) {
			// Copy the files into their new location.
			// If a prior process fataled copying or cleaning up files we tolerate any
			// of the existing files if they are identical to the ones being stored.
			$statusMove = $repo->storeBatch( $triplets, FileRepo::OVERWRITE_SAME );

			$this->logger->debug(
				'Moved files for {fileName}: {successCount} successes, {failCount} failures',
				[
					'fileName' => $this->file->getName(),
					'successCount' => $statusMove->successCount,
					'failCount' => $statusMove->failCount,
				]
			);

			if ( !$statusMove->isGood() ) {
				// Delete any files copied over (while the destination is still locked)
				$this->cleanupTarget( $triplets );

				$this->logger->debug(
					'Error in moving files: {error}',
					[ 'error' => $statusMove->getWikiText( false, false, 'en' ) ]
				);

				$statusMove->setOK( false );

				return $statusMove;
			}
			$status->merge( $statusMove );
		}

		// Rename the file versions metadata in the DB.
		$this->doDBUpdates();

		$this->logger->debug(
			'Renamed {fileName} in database: {successCount} successes, {failCount} failures',
			[
				'fileName' => $this->file->getName(),
				'successCount' => $statusDb->successCount,
				'failCount' => $statusDb->failCount,
			]
		);

		// Everything went ok, remove the source files
		$this->cleanupSource( $triplets );

		// Defer lock release until the transaction is committed.
		if ( $this->db->trxLevel() ) {
			ScopedCallback::cancel( $unlockScope );
			$this->db->onTransactionResolution( function () {
				$this->releaseLocks();
			}, __METHOD__ );
		} else {
			ScopedCallback::consume( $unlockScope );
		}

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

		// Lock the image row
		$hasCurrent = $dbw->newSelectQueryBuilder()
			->from( 'image' )
			->where( [ 'img_name' => $this->oldName ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchRowCount();

		// Lock the oldimage rows
		$oldRowCount = $dbw->newSelectQueryBuilder()
			->from( 'oldimage' )
			->where( [ 'oi_name' => $this->oldName ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchRowCount();

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

		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
		if ( ( $migrationStage & SCHEMA_COMPAT_WRITE_NEW ) && $this->file->getFileIdFromName() ) {
			$deleted = $dbw->newSelectQueryBuilder()
				->select( 'file_id' )
				->from( 'file' )
				->where( [ 'file_name' => $this->newName ] )
				->andWhere( [ 'file_deleted' => 1 ] )
				->caller( __METHOD__ )->fetchField();
			if ( $deleted ) {
				// Overwriting an existing file that was deleted.
				// Once the file deletion storage refactor starts,
				// this should change to update deleted revisions too.
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'file' )
					->where( [ 'file_name' => $this->newName ] )
					->andWhere( [ 'file_deleted' => 1 ] )
					->caller( __METHOD__ )->execute();
				// Paranoia
				$dbw->newUpdateQueryBuilder()
					->update( 'filerevision' )
					->set( [ 'fr_file' => $this->file->getFileIdFromName() ] )
					->where( [ 'fr_file' => $deleted ] )
					->caller( __METHOD__ )->execute();
			}
			$dbw->newUpdateQueryBuilder()
				->update( 'file' )
				->set( [ 'file_name' => $this->newName ] )
				->where( [ 'file_id' => $this->file->getFileIdFromName() ] )
				->caller( __METHOD__ )->execute();
		}
		// Update current image
		$dbw->newUpdateQueryBuilder()
			->update( 'image' )
			->set( [ 'img_name' => $this->newName ] )
			->where( [ 'img_name' => $this->oldName ] )
			->caller( __METHOD__ )->execute();

		// Update old images
		$dbw->newUpdateQueryBuilder()
			->update( 'oldimage' )
			->set( [
				'oi_name' => $this->newName,
				'oi_archive_name' => new RawSQLValue( $dbw->strreplace(
					'oi_archive_name',
					$dbw->addQuotes( $this->oldName ),
					$dbw->addQuotes( $this->newName )
				) ),
			] )
			->where( [ 'oi_name' => $this->oldName ] )
			->caller( __METHOD__ )->execute();
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

			$this->logger->debug(
				'Generated move triplet for {fileName}: {srcUrl} :: public :: {move1}',
				[
					'fileName' => $this->file->getName(),
					'srcUrl' => $srcUrl,
					'move1' => $move[1],
				]
			);
		}

		return $triplets;
	}

	/**
	 * Removes non-existent files from move batch.
	 * @param array[] $triplets
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
				$this->logger->debug(
					'File {file} does not exist',
					[ 'file' => $file[0] ]
				);
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

/** @deprecated class alias since 1.44 */
class_alias( LocalFileMoveBatch::class, 'LocalFileMoveBatch' );
