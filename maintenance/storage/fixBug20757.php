<?php
/**
 * Script to fix bug 20757.
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
 * @ingroup Maintenance ExternalStorage
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Maintenance script to fix bug 20757.
 *
 * @ingroup Maintenance ExternalStorage
 */
class FixBug20757 extends Maintenance {
	public $batchSize = 10000;
	public $mapCache = array();
	public $mapCacheSize = 0;
	public $maxMapCacheSize = 1000000;

	function __construct() {
		parent::__construct();
		$this->mDescription = 'Script to fix bug 20757 assuming that blob_tracking is intact';
		$this->addOption( 'dry-run', 'Report only' );
		$this->addOption( 'start', 'old_id to start at', false, true );
	}

	function execute() {
		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		$dryRun = $this->getOption( 'dry-run' );
		if ( $dryRun ) {
			print "Dry run only.\n";
		}

		$startId = $this->getOption( 'start', 0 );
		$numGood = 0;
		$numFixed = 0;
		$numBad = 0;

		$totalRevs = $dbr->selectField( 'text', 'MAX(old_id)', false, __METHOD__ );

		if ( $dbr->getType() == 'mysql' ) {
			// In MySQL 4.1+, the binary field old_text has a non-working LOWER() function
			$lowerLeft = 'LOWER(CONVERT(LEFT(old_text,22) USING latin1))';
		}

		while ( true ) {
			print "ID: $startId / $totalRevs\r";

			$res = $dbr->select(
				'text',
				array( 'old_id', 'old_flags', 'old_text' ),
				array(
					'old_id > ' . intval( $startId ),
					'old_flags LIKE \'%object%\' AND old_flags NOT LIKE \'%external%\'',
					"$lowerLeft = 'o:15:\"historyblobstub\"'",
				),
				__METHOD__,
				array(
					'ORDER BY' => 'old_id',
					'LIMIT' => $this->batchSize,
				)
			);

			if ( !$res->numRows() ) {
				break;
			}

			$secondaryIds = array();
			$stubs = array();

			foreach ( $res as $row ) {
				$startId = $row->old_id;

				// Basic sanity checks
				$obj = unserialize( $row->old_text );
				if ( $obj === false ) {
					print "{$row->old_id}: unrecoverable: cannot unserialize\n";
					++$numBad;
					continue;
				}

				if ( !is_object( $obj ) ) {
					print "{$row->old_id}: unrecoverable: unserialized to type " .
						gettype( $obj ) . ", possible double-serialization\n";
					++$numBad;
					continue;
				}

				if ( strtolower( get_class( $obj ) ) !== 'historyblobstub' ) {
					print "{$row->old_id}: unrecoverable: unexpected object class " .
						get_class( $obj ) . "\n";
					++$numBad;
					continue;
				}

				// Process flags
				$flags = explode( ',', $row->old_flags );
				if ( in_array( 'utf-8', $flags ) || in_array( 'utf8', $flags ) ) {
					$legacyEncoding = false;
				} else {
					$legacyEncoding = true;
				}

				// Queue the stub for future batch processing
				$id = intval( $obj->mOldId );
				$secondaryIds[] = $id;
				$stubs[$row->old_id] = array(
					'legacyEncoding' => $legacyEncoding,
					'secondaryId' => $id,
					'hash' => $obj->mHash,
				);
			}

			$secondaryIds = array_unique( $secondaryIds );

			if ( !count( $secondaryIds ) ) {
				continue;
			}

			// Run the batch query on blob_tracking
			$res = $dbr->select(
				'blob_tracking',
				'*',
				array(
					'bt_text_id' => $secondaryIds,
				),
				__METHOD__
			);
			$trackedBlobs = array();
			foreach ( $res as $row ) {
				$trackedBlobs[$row->bt_text_id] = $row;
			}

			// Process the stubs
			foreach ( $stubs as $primaryId => $stub ) {
				$secondaryId = $stub['secondaryId'];
				if ( !isset( $trackedBlobs[$secondaryId] ) ) {
					// No tracked blob. Work out what went wrong
					$secondaryRow = $dbr->selectRow(
						'text',
						array( 'old_flags', 'old_text' ),
						array( 'old_id' => $secondaryId ),
						__METHOD__
					);
					if ( !$secondaryRow ) {
						print "$primaryId: unrecoverable: secondary row is missing\n";
						++$numBad;
					} elseif ( $this->isUnbrokenStub( $stub, $secondaryRow ) ) {
						// Not broken yet, and not in the tracked clusters so it won't get
						// broken by the current RCT run.
						++$numGood;
					} elseif ( strpos( $secondaryRow->old_flags, 'external' ) !== false ) {
						print "$primaryId: unrecoverable: secondary gone to {$secondaryRow->old_text}\n";
						++$numBad;
					} else {
						print "$primaryId: unrecoverable: miscellaneous corruption of secondary row\n";
						++$numBad;
					}
					unset( $stubs[$primaryId] );
					continue;
				}
				$trackRow = $trackedBlobs[$secondaryId];

				// Check that the specified text really is available in the tracked source row
				$url = "DB://{$trackRow->bt_cluster}/{$trackRow->bt_blob_id}/{$stub['hash']}";
				$text = ExternalStore::fetchFromURL( $url );
				if ( $text === false ) {
					print "$primaryId: unrecoverable: source text missing\n";
					++$numBad;
					unset( $stubs[$primaryId] );
					continue;
				}
				if ( md5( $text ) !== $stub['hash'] ) {
					print "$primaryId: unrecoverable: content hashes do not match\n";
					++$numBad;
					unset( $stubs[$primaryId] );
					continue;
				}

				// Find the page_id and rev_id
				// The page is probably the same as the page of the secondary row
				$pageId = intval( $trackRow->bt_page );
				if ( !$pageId ) {
					$revId = $pageId = 0;
				} else {
					$revId = $this->findTextIdInPage( $pageId, $primaryId );
					if ( !$revId ) {
						// Actually an orphan
						$pageId = $revId = 0;
					}
				}

				$newFlags = $stub['legacyEncoding'] ? 'external' : 'external,utf-8';

				if ( !$dryRun ) {
					// Reset the text row to point to the original copy
					$dbw->begin( __METHOD__ );
					$dbw->update(
						'text',
						// SET
						array(
							'old_flags' => $newFlags,
							'old_text' => $url
						),
						// WHERE
						array( 'old_id' => $primaryId ),
						__METHOD__
					);

					// Add a blob_tracking row so that the new reference can be recompressed
					// without needing to run trackBlobs.php again
					$dbw->insert( 'blob_tracking',
						array(
							'bt_page' => $pageId,
							'bt_rev_id' => $revId,
							'bt_text_id' => $primaryId,
							'bt_cluster' => $trackRow->bt_cluster,
							'bt_blob_id' => $trackRow->bt_blob_id,
							'bt_cgz_hash' => $stub['hash'],
							'bt_new_url' => null,
							'bt_moved' => 0,
						),
						__METHOD__
					);
					$dbw->commit( __METHOD__ );
					$this->waitForSlaves();
				}

				print "$primaryId: resolved to $url\n";
				++$numFixed;
			}
		}

		print "\n";
		print "Fixed: $numFixed\n";
		print "Unrecoverable: $numBad\n";
		print "Good stubs: $numGood\n";
	}

	function waitForSlaves() {
		static $iteration = 0;
		++$iteration;
		if ( ++$iteration > 50 == 0 ) {
			wfWaitForSlaves();
			$iteration = 0;
		}
	}

	function findTextIdInPage( $pageId, $textId ) {
		$ids = $this->getRevTextMap( $pageId );
		if ( !isset( $ids[$textId] ) ) {
			return null;
		} else {
			return $ids[$textId];
		}
	}

	function getRevTextMap( $pageId ) {
		if ( !isset( $this->mapCache[$pageId] ) ) {
			// Limit cache size
			while ( $this->mapCacheSize > $this->maxMapCacheSize ) {
				$key = key( $this->mapCache );
				$this->mapCacheSize -= count( $this->mapCache[$key] );
				unset( $this->mapCache[$key] );
			}

			$dbr = wfGetDB( DB_SLAVE );
			$map = array();
			$res = $dbr->select( 'revision',
				array( 'rev_id', 'rev_text_id' ),
				array( 'rev_page' => $pageId ),
				__METHOD__
			);
			foreach ( $res as $row ) {
				$map[$row->rev_text_id] = $row->rev_id;
			}
			$this->mapCache[$pageId] = $map;
			$this->mapCacheSize += count( $map );
		}

		return $this->mapCache[$pageId];
	}

	/**
	 * This is based on part of HistoryBlobStub::getText().
	 * Determine if the text can be retrieved from the row in the normal way.
	 * @param array $stub
	 * @param stdClass $secondaryRow
	 * @return bool
	 */
	function isUnbrokenStub( $stub, $secondaryRow ) {
		$flags = explode( ',', $secondaryRow->old_flags );
		$text = $secondaryRow->old_text;
		if ( in_array( 'external', $flags ) ) {
			$url = $text;
			wfSuppressWarnings();
			list( /* $proto */, $path ) = explode( '://', $url, 2 );
			wfRestoreWarnings();

			if ( $path == "" ) {
				return false;
			}
			$text = ExternalStore::fetchFromUrl( $url );
		}
		if ( !in_array( 'object', $flags ) ) {
			return false;
		}

		if ( in_array( 'gzip', $flags ) ) {
			$obj = unserialize( gzinflate( $text ) );
		} else {
			$obj = unserialize( $text );
		}

		if ( !is_object( $obj ) ) {
			// Correct for old double-serialization bug.
			$obj = unserialize( $obj );
		}

		if ( !is_object( $obj ) ) {
			return false;
		}

		$obj->uncompress();
		$text = $obj->getItem( $stub['hash'] );

		return $text !== false;
	}
}

$maintClass = 'FixBug20757';
require_once RUN_MAINTENANCE_IF_MAIN;
