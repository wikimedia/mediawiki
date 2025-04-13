<?php
/**
 * Update image metadata records.
 *
 * Usage: php rebuildImages.php [--missing] [--dry-run]
 * Options:
 *   --missing  Crawl the uploads dir for images without records, and
 *              add them only.
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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
 * @author Brooke Vibber <bvibber@wikimedia.org>
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Specials\SpecialUpload;
use MediaWiki\User\User;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Maintenance script to update image metadata records.
 *
 * @ingroup Maintenance
 */
class ImageBuilder extends Maintenance {
	/**
	 * @var IMaintainableDatabase
	 */
	protected $dbw;

	/** @var bool */
	private $dryrun;

	/** @var LocalRepo|null */
	private $repo;

	/** @var int */
	private $updated;

	/** @var int */
	private $processed;

	/** @var int */
	private $count;

	/** @var float */
	private $startTime;

	/** @var string */
	private $table;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to update image metadata records' );

		$this->addOption( 'missing', 'Check for files without associated database record' );
		$this->addOption( 'dry-run', 'Only report, don\'t update the database' );
	}

	public function execute() {
		$this->dbw = $this->getPrimaryDB();
		$this->dryrun = $this->hasOption( 'dry-run' );
		if ( $this->dryrun ) {
			$this->getServiceContainer()->getReadOnlyMode()
				->setReason( 'Dry run mode, image upgrades are suppressed' );
		}

		if ( $this->hasOption( 'missing' ) ) {
			$this->crawlMissing();
		} else {
			$this->build();
		}
	}

	/**
	 * @return LocalRepo
	 */
	private function getRepo() {
		if ( $this->repo === null ) {
			$this->repo = $this->getServiceContainer()->getRepoGroup()
				->newCustomLocalRepo( [
					// make sure to update old, but compatible img_metadata fields.
					'updateCompatibleMetadata' => true
				] );
		}

		return $this->repo;
	}

	private function build() {
		$this->buildImage();
		$this->buildOldImage();
	}

	/**
	 * @param int $count
	 * @param string $table
	 */
	private function init( $count, $table ) {
		$this->processed = 0;
		$this->updated = 0;
		$this->count = $count;
		$this->startTime = microtime( true );
		$this->table = $table;
	}

	private function progress( int $updated ) {
		$this->updated += $updated;
		$this->processed++;
		if ( $this->processed % 100 != 0 ) {
			return;
		}
		$portion = $this->processed / $this->count;
		$updateRate = $this->updated / $this->processed;

		$now = microtime( true );
		$delta = $now - $this->startTime;
		$estimatedTotalTime = $delta / $portion;
		$eta = $this->startTime + $estimatedTotalTime;
		$rate = $this->processed / $delta;

		$this->output( sprintf( "%s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec <%.2f%% updated>\n",
			wfTimestamp( TS_DB, intval( $now ) ),
			$portion * 100.0,
			$this->table,
			wfTimestamp( TS_DB, intval( $eta ) ),
			$this->processed,
			$this->count,
			$rate,
			$updateRate * 100.0 ) );
		flush();
	}

	private function buildTable( string $table, SelectQueryBuilder $queryBuilder, callable $callback ) {
		$count = $this->dbw->newSelectQueryBuilder()
			->select( 'count(*)' )
			->from( $table )
			->caller( __METHOD__ )->fetchField();
		$this->init( $count, $table );
		$this->output( "Processing $table...\n" );

		$result = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		foreach ( $result as $row ) {
			$update = $callback( $row );
			if ( $update ) {
				$this->progress( 1 );
			} else {
				$this->progress( 0 );
			}
		}
		$this->output( "Finished $table... $this->updated of $this->processed rows updated\n" );
	}

	private function buildImage() {
		$callback = [ $this, 'imageCallback' ];
		$this->buildTable( 'image', FileSelectQueryBuilder::newForFile( $this->getReplicaDB() ), $callback );
	}

	private function imageCallback( \stdClass $row ): bool {
		// Create a File object from the row
		// This will also upgrade it
		$file = $this->getRepo()->newFileFromRow( $row );

		return $file->getUpgraded();
	}

	private function buildOldImage() {
		$this->buildTable( 'oldimage', FileSelectQueryBuilder::newForOldFile( $this->getReplicaDB() ),
			[ $this, 'oldimageCallback' ] );
	}

	private function oldimageCallback( \stdClass $row ): bool {
		// Create a File object from the row
		// This will also upgrade it
		if ( $row->oi_archive_name == '' ) {
			$this->output( "Empty oi_archive_name for oi_name={$row->oi_name}\n" );

			return false;
		}
		$file = $this->getRepo()->newFileFromRow( $row );

		return $file->getUpgraded();
	}

	private function crawlMissing() {
		$this->getRepo()->enumFiles( [ $this, 'checkMissingImage' ] );
	}

	public function checkMissingImage( string $fullpath ) {
		$filename = wfBaseName( $fullpath );
		$row = $this->dbw->newSelectQueryBuilder()
			->select( [ 'img_name' ] )
			->from( 'image' )
			->where( [ 'img_name' => $filename ] )
			->caller( __METHOD__ )->fetchRow();

		if ( !$row ) {
			// file not registered
			$this->addMissingImage( $filename, $fullpath );
		}
	}

	private function addMissingImage( string $filename, string $fullpath ) {
		$timestamp = $this->dbw->timestamp( $this->getRepo()->getFileTimestamp( $fullpath ) );
		$services = $this->getServiceContainer();

		$altname = $services->getContentLanguage()->checkTitleEncoding( $filename );
		if ( $altname != $filename ) {
			if ( $this->dryrun ) {
				$filename = $altname;
				$this->output( "Estimating transcoding... $altname\n" );
			} else {
				// @fixme create renameFile()
				// @phan-suppress-next-line PhanUndeclaredMethod See comment above...
				$filename = $this->renameFile( $filename );
			}
		}

		if ( $filename == '' ) {
			$this->output( "Empty filename for $fullpath\n" );

			return;
		}
		if ( !$this->dryrun ) {
			$file = $services->getRepoGroup()->getLocalRepo()->newFile( $filename );
			$pageText = SpecialUpload::getInitialPageText(
				'(recovered file, missing upload log entry)'
			);
			$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
			$status = $file->recordUpload3(
				'',
				'(recovered file, missing upload log entry)',
				$pageText,
				$user,
				false,
				$timestamp
			);
			if ( !$status->isOK() ) {
				$this->output( "Error uploading file $fullpath\n" );

				return;
			}
		}
		$this->output( $fullpath . "\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ImageBuilder::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
