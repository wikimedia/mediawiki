<?php
/**
 * Script to refresh image metadata fields. See also rebuildImages.php
 *
 * Usage: php refreshImageMetadata.php
 *
 * Copyright © 2011 Brian Wolff
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
 * @author Brian Wolff
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class RefreshImageMetadata extends Maintenance {

	/**
	 * @var DatabaseBase
	 */
	protected $dbw;

	function __construct() {
		parent::__construct();

		$this->mDescription = 'Script to update image metadata records';
		$this->setBatchSize( 200 );

		$this->addOption( 'force', 'Reload metadata from file even if the metadata looks ok', false, false, 'f' );
		$this->addOption( 'broken-only', 'Only fix really broken records, leave old but still compatible records alone.' );
		$this->addOption( 'verbose', 'Output extra information about each upgraded/non-upgraded file.', false, false, 'v' );
		$this->addOption( 'start', 'Name of file to start with', false, true );
		$this->addOption( 'end', 'Name of file to end with', false, true );

		$this->addOption( 'mime', '(Inefficient!) Only refresh files with this mime type. Can accept wild-card image/*' , false, true );
		$this->addOption( 'metadata-contains', '(Inefficient!) Only refresh files where the img_metadata field contains this string. Can be used if its known a specific property was being extracted incorrectly.', false, true );

	}

	public function execute() {
		$force = $this->hasOption( 'force' );
		$brokenOnly = $this->hasOption( 'broken-only' );
		$verbose = $this->hasOption( 'verbose' );
		$start = $this->getOption( 'start', false );
		$this->setupParameters( $force, $brokenOnly );

		$upgraded = 0;
		$leftAlone = 0;
		$error = 0;

		$dbw = wfGetDB( DB_MASTER );
		if ( $this->mBatchSize <= 0 ) {
			$this->error( "Batch size is too low...", 12 );
		}

		$repo = RepoGroup::singleton()->getLocalRepo();
		$conds = $this->getConditions( $dbw );

		// For the WHERE img_name > 'foo' condition that comes after doing a batch
		$conds2 = array();
		if ( $start !== false ) {
			$conds2[] = 'img_name >= ' . $dbw->addQuotes( $start );
		}

		$options = array(
			'LIMIT' => $this->mBatchSize,
			'ORDER BY' => 'img_name ASC',
		);

		do {
			$res = $dbw->select(
				'image',
				'*',
				array_merge( $conds, $conds2 ),
				__METHOD__,
				$options
			);

			if ( $res->numRows() > 0 ) {
				$row1 = $res->current();
				$this->output( "Processing next {$this->mBatchSize} rows starting with {$row1->img_name}.\n");
				$res->rewind();
			} else {
				$this->error( "No images to process.", 4 );
			}

			foreach ( $res as $row ) {
				$file = $repo->newFileFromRow( $row );
				if ( $file->getUpgraded() ) {
					// File was upgraded.
					$upgraded++;
					$newLength = strlen( $file->getMetadata() );
					$oldLength = strlen( $row->img_metadata );
					if ( $newLength < $oldLength - 5 ) {
						// If after updating, the metadata is smaller then
						// what it was before, that's probably not a good thing
						// because we extract more data with time, not less.
						// Thus this probably indicates an error of some sort,
						// or at the very least is suspicious. Have the - 5 just
						// to weed out any inconsequential changes.
						$error++;
						$this->output( "Warning: File:{$row->img_name} used to have " .
						"$oldLength bytes of metadata but now has $newLength bytes.\n" );
					} elseif ( $verbose ) {
						$this->output("Refreshed File:{$row->img_name}.\n" );
					}
				} else {
					$leftAlone++;
					if ( $force ) {
						$file->upgradeRow();
						$newLength = strlen( $file->getMetadata() );
						$oldLength = strlen( $row->img_metadata );
						if ( $newLength < $oldLength - 5 ) {
							$error++;
							$this->output( "Warning: File:{$row->img_name} used to have " .
							"$oldLength bytes of metadata but now has $newLength bytes. (forced)\n" );

						}
						if ( $verbose ) {
							$this->output("Forcibly refreshed File:{$row->img_name}.\n" );
						}
					}
					else {
						if ( $verbose ) {
							$this->output( "Skipping File:{$row->img_name}.\n" );
						}
					}
				}

			}
			$conds2 = array( 'img_name > ' . $dbw->addQuotes( $row->img_name ) );
			wfWaitForSlaves();
		} while( $res->numRows() === $this->mBatchSize );

		$total = $upgraded + $leftAlone;
		if ( $force ) {
			$this->output( "\nFinished refreshing file metadata for $total files. $upgraded needed to be refreshed, $leftAlone did not need to be but were refreshed anyways, and $error refreshes were suspicious.\n" );
		} else {
			$this->output( "\nFinished refreshing file metadata for $total files. $upgraded were refreshed, $leftAlone were already up to date, and $error refreshes were suspicious.\n" );
		}
	}

	/**
	 * @param $dbw DatabaseBase
	 * @return array
	 */
	function getConditions( $dbw ) {
		$conds = array();

		$end = $this->getOption( 'end', false );
		$mime = $this->getOption( 'mime', false );
		$like = $this->getOption( 'metadata-contains', false );

		if ( $end !== false ) {
			$conds[] = 'img_name <= ' . $dbw->addQuotes( $end ) ;
		}
		if ( $mime !== false ) {
			list( $major, $minor ) = File::splitMime( $mime );
			$conds['img_major_mime'] = $major;
			if ( $minor !== '*' ) {
				$conds['img_minor_mime'] = $minor;
			}
		}
		if ( $like ) {
			$conds[] = 'img_metadata ' . $dbw->buildLike( $dbw->anyString(), $like, $dbw->anyString() );
		}
		return $conds;
	}

	/**
	 * @param $force bool
	 * @param $brokenOnly bool
	 */
	function setupParameters( $force, $brokenOnly ) {
		global $wgUpdateCompatibleMetadata;

		if ( $brokenOnly ) {
			$wgUpdateCompatibleMetadata = false;
		} else {
			$wgUpdateCompatibleMetadata = true;
		}

		if ( $brokenOnly && $force ) {
			$this->error( 'Cannot use --broken-only and --force together. ', 2 );
		}
	}
}


$maintClass = 'RefreshImageMetadata';
require_once( RUN_MAINTENANCE_IF_MAIN );
