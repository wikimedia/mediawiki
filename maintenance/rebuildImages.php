<?php
/**
 * Update image metadata records.
 *
 * Usage: php rebuildImages.php [--missing] [--dry-run]
 * Options:
 *   --missing  Crawl the uploads dir for images without records, and
 *              add them only.
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * @author Brion Vibber <brion at pobox.com>
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to update image metadata records.
 *
 * @ingroup Maintenance
 */
class ImageBuilder extends Maintenance {

	/**
	 * @var DatabaseBase
	 */
	protected $dbw;

	function __construct() {
		parent::__construct();

		global $wgUpdateCompatibleMetadata;
		//make sure to update old, but compatible img_metadata fields.
		$wgUpdateCompatibleMetadata = true;

		$this->mDescription = 'Script to update image metadata records';

		$this->addOption( 'missing', 'Check for files without associated database record' );
		$this->addOption( 'dry-run', 'Only report, don\'t update the database' );
	}

	public function execute() {
		$this->dbw = wfGetDB( DB_MASTER );
		$this->maxLag = 10; # if slaves are lagged more than 10 secs, wait
		$this->dryrun = $this->hasOption( 'dry-run' );
		if ( $this->dryrun ) {
			$GLOBALS['wgReadOnly'] = 'Dry run mode, image upgrades are suppressed';
		}

		if ( $this->hasOption( 'missing' ) ) {
			$this->crawlMissing();
		} else {
			$this->build();
		}
	}

	/**
	 * @return FileRepo
	 */
	function getRepo() {
		if ( !isset( $this->repo ) ) {
			$this->repo = RepoGroup::singleton()->getLocalRepo();
		}
		return $this->repo;
	}

	function build() {
		$this->buildImage();
		$this->buildOldImage();
	}

	function init( $count, $table ) {
		$this->processed = 0;
		$this->updated = 0;
		$this->count = $count;
		$this->startTime = microtime( true );
		$this->table = $table;
	}

	function progress( $updated ) {
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

	function buildTable( $table, $key, $callback ) {
		$count = $this->dbw->selectField( $table, 'count(*)', '', __METHOD__ );
		$this->init( $count, $table );
		$this->output( "Processing $table...\n" );

		$result = wfGetDB( DB_SLAVE )->select( $table, '*', array(), __METHOD__ );

		foreach ( $result as $row ) {
			$update = call_user_func( $callback, $row, null );
			if ( $update ) {
				$this->progress( 1 );
			} else {
				$this->progress( 0 );
			}
		}
		$this->output( "Finished $table... $this->updated of $this->processed rows updated\n" );
	}

	function buildImage() {
		$callback = array( $this, 'imageCallback' );
		$this->buildTable( 'image', 'img_name', $callback );
	}

	function imageCallback( $row, $copy ) {
		// Create a File object from the row
		// This will also upgrade it
		$file = $this->getRepo()->newFileFromRow( $row );
		return $file->getUpgraded();
	}

	function buildOldImage() {
		$this->buildTable( 'oldimage', 'oi_archive_name', array( $this, 'oldimageCallback' ) );
	}

	function oldimageCallback( $row, $copy ) {
		// Create a File object from the row
		// This will also upgrade it
		if ( $row->oi_archive_name == '' ) {
			$this->output( "Empty oi_archive_name for oi_name={$row->oi_name}\n" );
			return false;
		}
		$file = $this->getRepo()->newFileFromRow( $row );
		return $file->getUpgraded();
	}

	function crawlMissing() {
		$this->getRepo()->enumFiles( array( $this, 'checkMissingImage' ) );
	}

	function checkMissingImage( $fullpath ) {
		$filename = wfBaseName( $fullpath );
		$row = $this->dbw->selectRow( 'image',
			array( 'img_name' ),
			array( 'img_name' => $filename ),
			__METHOD__ );

		if ( !$row ) { // file not registered
			$this->addMissingImage( $filename, $fullpath );
		}
	}

	function addMissingImage( $filename, $fullpath ) {
		global $wgContLang;

		$timestamp = $this->dbw->timestamp( $this->getRepo()->getFileTimestamp( $fullpath ) );

		$altname = $wgContLang->checkTitleEncoding( $filename );
		if ( $altname != $filename ) {
			if ( $this->dryrun ) {
				$filename = $altname;
				$this->output( "Estimating transcoding... $altname\n" );
			} else {
				# @todo FIXME: create renameFile()
				$filename = $this->renameFile( $filename );
			}
		}

		if ( $filename == '' ) {
			$this->output( "Empty filename for $fullpath\n" );
			return;
		}
		if ( !$this->dryrun ) {
			$file = wfLocalFile( $filename );
			if ( !$file->recordUpload( '', '(recovered file, missing upload log entry)', '', '', '',
				false, $timestamp ) )
			{
				$this->output( "Error uploading file $fullpath\n" );
				return;
			}
		}
		$this->output( $fullpath . "\n" );
	}
}

$maintClass = 'ImageBuilder';
require_once RUN_MAINTENANCE_IF_MAIN;
