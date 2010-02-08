<?php

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class FixBug20757 extends Maintenance {
	var $batchSize = 10000;
	var $mapCache = array();
	var $mapCacheSize = 0;
	var $maxMapCacheSize = 1000000;

	function __construct() {
		parent::__construct();
		$this->mDescription = 'Script to fix bug 20757 assuming that blob_tracking is intact';
		$this->addOption( 'dry-run', 'Report only' );
	}
	
	function execute() {
		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		$dryRun = $this->getOption( 'dry-run' );
		if ( $dryRun ) {
			print "Dry run only.\n";
		}

		$startId = 0;
		$numGood = 0;
		$numFixed = 0;
		$numBad = 0;

		$totalRevs = $dbr->selectField( 'text', 'MAX(old_id)', false, __METHOD__ );

		while ( true ) {
			print "ID: $startId / $totalRevs\r";

			$res = $dbr->select(
				'text',
				array( 'old_id', 'old_flags', 'old_text' ),
				array( 
					'old_id > ' . intval( $startId ),
					'old_flags' => 'object'
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

				// Check if it really is broken
				$text = Revision::getRevisionText( $row );
				if ( $text !== false ) {
					// Not broken yet
					++$numGood;
					continue;
				}

				if ( strtolower( get_class( $obj ) ) !== 'historyblobstub' ) {
					print "{$row->old_id}: unrecoverable: unexpected object class " .
						get_class( $obj ) . "\n";
					++$numBad;
					continue;
				}

				// Queue the stub for future batch processing
				$id = intval( $obj->mOldId );
				$secondaryIds[] = $id;
				$stubs[$row->old_id] = array(
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
					'bt_moved' => 1,
				),
				__METHOD__
			);
			$trackedBlobs = array();
			foreach ( $res as $row ) {
				$trackedBlobs[$row->bt_text_id] = $row;
			}

			// Process the stubs
			$stubsToFix = array();
			foreach ( $stubs as $primaryId => $stub ) {
				$secondaryId = $stub['secondaryId'];
				if ( !isset( $trackedBlobs[$secondaryId] ) ) {
					$secondaryRow = $dbr->selectRow( 
						'text', 
						array( 'old_flags', 'old_text' ),
						array( 'old_id' => $secondaryId ), 
						__METHOD__
					);
					if ( !$secondaryRow ) {
						print "$primaryId: unrecoverable: secondary row is missing\n";
					} elseif ( strpos( $secondaryRow->old_flags, 'external' ) !== false ) {
						print "$primaryId: unrecoverable: secondary gone to {$secondaryRow->old_text}\n";
					} else {
						print "$primaryId: unrecoverable: miscellaneous corruption of secondary row\n";
					}
					++$numBad;
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
				$pageId = $this->bt_page;
				if ( $pageId === null ) {
					$revId = null;
				} else {
					$revId = $this->findTextIdInPage( $pageId, $primaryId );
					if ( $revId === null ) {
						// Actually an orphan
						$pageId = null;
					}
				}

				if ( !$dryRun ) {
					// Reset the text row to point to the original copy
					$dbw->begin();
					$dbw->update(
						'text',
						// SET
						array(
							'old_flags' => 'external', // use legacy encoding
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
							'bt_page' => $trackRow->bt_page,
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
					$dbw->commit();
					$this->waitForSlaves();
				}

				print "$primaryId: resolved to $url\n";
				++$numFixed;
			}
		}

		print "\n";
		print "Fixed: $numFixed\n";
		print "Unrecoverable: $numBad\n";
		print "Not yet broken: $numGood\n";
	}

	function waitForSlaves() {
		static $iteration = 0;
		++$iteration;
		if ( ++$iteration > 50 == 0 ) {
			wfWaitForSlaves( 5 );
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

}

$maintClass = 'FixBug20757';
require_once( DO_MAINTENANCE );

