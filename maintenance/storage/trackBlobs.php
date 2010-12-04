<?php

require( dirname( __FILE__ ) . '/../commandLine.inc' );


if ( count( $args ) < 1 ) {
	echo "Usage: php trackBlobs.php <cluster> [... <cluster>]\n";
	echo "Adds blobs from a given ES cluster to the blob_tracking table\n";
	echo "Automatically deletes the tracking table and starts from the start again when restarted.\n";

	exit( 1 );
}
$tracker = new TrackBlobs( $args );
$tracker->run();
echo "All done.\n";

class TrackBlobs {
	var $clusters, $textClause;
	var $doBlobOrphans;
	var $trackedBlobs = array();

	var $batchSize = 1000;
	var $reportingInterval = 10;

	function __construct( $clusters ) {
		$this->clusters = $clusters;
		if ( extension_loaded( 'gmp' ) ) {
			$this->doBlobOrphans = true;
			foreach ( $clusters as $cluster ) {
				$this->trackedBlobs[$cluster] = gmp_init( 0 );
			}
		} else {
			echo "Warning: the gmp extension is needed to find orphan blobs\n";
		}
	}

	function run() {
		$this->checkIntegrity();
		$this->initTrackingTable();
		$this->trackRevisions();
		$this->trackOrphanText();
		if ( $this->doBlobOrphans ) {
			$this->findOrphanBlobs();
		}
	}

	function checkIntegrity() {
		echo "Doing integrity check...\n";
		$dbr = wfGetDB( DB_SLAVE );

		// Scan for HistoryBlobStub objects in the text table (bug 20757)

		$exists = $dbr->selectField( 'text', 1,
			'old_flags LIKE \'%object%\' AND old_flags NOT LIKE \'%external%\' ' .
			'AND LOWER(CONVERT(LEFT(old_text,22) USING latin1)) = \'o:15:"historyblobstub"\'',
			__METHOD__
		);

		if ( $exists ) {
			echo "Integrity check failed: found HistoryBlobStub objects in your text table.\n" .
				"This script could destroy these objects if it continued. Run resolveStubs.php\n" .
				"to fix this.\n";
			exit( 1 );
		}

		// Scan the archive table for HistoryBlobStub objects or external flags (bug 22624)
		$flags = $dbr->selectField( 'archive', 'ar_flags',
			'ar_flags LIKE \'%external%\' OR (' .
			'ar_flags LIKE \'%object%\' ' .
			'AND LOWER(CONVERT(LEFT(ar_text,22) USING latin1)) = \'o:15:"historyblobstub"\' )',
			__METHOD__
		);

		if ( strpos( $flags, 'external' ) !== false ) {
			echo "Integrity check failed: found external storage pointers in your archive table.\n" .
				"Run normaliseArchiveTable.php to fix this.\n";
			exit( 1 );
		} elseif ( $flags ) {
			echo "Integrity check failed: found HistoryBlobStub objects in your archive table.\n" .
				"These objects are probably already broken, continuing would make them\n" .
				"unrecoverable. Run \"normaliseArchiveTable.php --fix-cgz-bug\" to fix this.\n";
			exit( 1 );
		}

		echo "Integrity check OK\n";
	}

	function initTrackingTable() {
		$dbw = wfGetDB( DB_MASTER );
		if ( $dbw->tableExists( 'blob_tracking' ) ) {
			$dbw->query( 'DROP TABLE ' . $dbw->tableName( 'blob_tracking' ) );
			$dbw->query( 'DROP TABLE ' . $dbw->tableName( 'blob_orphans' ) );
		}
		$dbw->sourceFile( dirname( __FILE__ ) . '/blob_tracking.sql' );
	}

	function getTextClause() {
		if ( !$this->textClause ) {
			$dbr = wfGetDB( DB_SLAVE );
			$this->textClause = '';
			foreach ( $this->clusters as $cluster ) {
				if ( $this->textClause != '' ) {
					$this->textClause .= ' OR ';
				}
				$this->textClause .= 'old_text' . $dbr->buildLike( "DB://$cluster/", $dbr->anyString() );
			}
		}
		return $this->textClause;
	}

	function interpretPointer( $text ) {
		if ( !preg_match( '!^DB://(\w+)/(\d+)(?:/([0-9a-fA-F]+)|)$!', $text, $m ) ) {
			return false;
		}
		return array(
			'cluster' => $m[1],
			'id' => intval( $m[2] ),
			'hash' => isset( $m[3] ) ? $m[3] : null
		);
	}

	/**
	 *  Scan the revision table for rows stored in the specified clusters
	 */
	function trackRevisions() {
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE );

		$textClause = $this->getTextClause();
		$startId = 0;
		$endId = $dbr->selectField( 'revision', 'MAX(rev_id)', false, __METHOD__ );
		$batchesDone = 0;
		$rowsInserted = 0;

		echo "Finding revisions...\n";

		while ( true ) {
			$res = $dbr->select( array( 'revision', 'text' ),
				array( 'rev_id', 'rev_page', 'old_id', 'old_flags', 'old_text' ),
				array(
					'rev_id > ' . $dbr->addQuotes( $startId ),
					'rev_text_id=old_id',
					$textClause,
					'old_flags ' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
				),
				__METHOD__,
				array(
					'ORDER BY' => 'rev_id',
					'LIMIT' => $this->batchSize
				)
			);
			if ( !$res->numRows() ) {
				break;
			}

			$insertBatch = array();
			foreach ( $res as $row ) {
				$startId = $row->rev_id;
				$info = $this->interpretPointer( $row->old_text );
				if ( !$info ) {
					echo "Invalid DB:// URL in rev_id {$row->rev_id}\n";
					continue;
				}
				if ( !in_array( $info['cluster'], $this->clusters ) ) {
					echo "Invalid cluster returned in SQL query: {$info['cluster']}\n";
					continue;
				}
				$insertBatch[] = array(
					'bt_page' => $row->rev_page,
					'bt_rev_id' => $row->rev_id,
					'bt_text_id' => $row->old_id,
					'bt_cluster' => $info['cluster'],
					'bt_blob_id' => $info['id'],
					'bt_cgz_hash' => $info['hash']
				);
				if ( $this->doBlobOrphans ) {
					gmp_setbit( $this->trackedBlobs[$info['cluster']], $info['id'] );
				}
			}
			$dbw->insert( 'blob_tracking', $insertBatch, __METHOD__ );
			$rowsInserted += count( $insertBatch );

			++$batchesDone;
			if ( $batchesDone >= $this->reportingInterval ) {
				$batchesDone = 0;
				echo "$startId / $endId\n";
				wfWaitForSlaves( 5 );
			}
		}
		echo "Found $rowsInserted revisions\n";
	}

	/**
	 * Scan the text table for orphan text
	 * Orphan text here does not imply DB corruption -- deleted text tracked by the
	 * archive table counts as orphan for our purposes.
	 */
	function trackOrphanText() {
		# Wait until the blob_tracking table is available in the slave
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE );
		$pos = $dbw->getMasterPos();
		$dbr->masterPosWait( $pos, 100000 );

		$textClause = $this->getTextClause( $this->clusters );
		$startId = 0;
		$endId = $dbr->selectField( 'text', 'MAX(old_id)', false, __METHOD__ );
		$rowsInserted = 0;
		$batchesDone = 0;

		echo "Finding orphan text...\n";

		# Scan the text table for orphan text
		while ( true ) {
			$res = $dbr->select( array( 'text', 'blob_tracking' ),
				array( 'old_id', 'old_flags', 'old_text' ),
				array(
					'old_id>' . $dbr->addQuotes( $startId ),
					$textClause,
					'old_flags ' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
					'bt_text_id IS NULL'
				),
				__METHOD__,
				array(
					'ORDER BY' => 'old_id',
					'LIMIT' => $this->batchSize
				),
				array( 'blob_tracking' => array( 'LEFT JOIN', 'bt_text_id=old_id' ) )
			);
			$ids = array();
			foreach ( $res as $row ) {
				$ids[] = $row->old_id;
			}

			if ( !$res->numRows() ) {
				break;
			}

			$insertBatch = array();
			foreach ( $res as $row ) {
				$startId = $row->old_id;
				$info = $this->interpretPointer( $row->old_text );
				if ( !$info ) {
					echo "Invalid DB:// URL in old_id {$row->old_id}\n";
					continue;
				}
				if ( !in_array( $info['cluster'], $this->clusters ) ) {
					echo "Invalid cluster returned in SQL query\n";
					continue;
				}

				$insertBatch[] = array(
					'bt_page' => 0,
					'bt_rev_id' => 0,
					'bt_text_id' => $row->old_id,
					'bt_cluster' => $info['cluster'],
					'bt_blob_id' => $info['id'],
					'bt_cgz_hash' => $info['hash']
				);
				if ( $this->doBlobOrphans ) {
					gmp_setbit( $this->trackedBlobs[$info['cluster']], $info['id'] );
				}
			}
			$dbw->insert( 'blob_tracking', $insertBatch, __METHOD__ );

			$rowsInserted += count( $insertBatch );
			++$batchesDone;
			if ( $batchesDone >= $this->reportingInterval ) {
				$batchesDone = 0;
				echo "$startId / $endId\n";
				wfWaitForSlaves( 5 );
			}
		}
		echo "Found $rowsInserted orphan text rows\n";
	}

	/**
	 * Scan the blobs table for rows not registered in blob_tracking (and thus not
	 * registered in the text table).
	 *
	 * Orphan blobs are indicative of DB corruption. They are inaccessible and
	 * should probably be deleted.
	 */
	function findOrphanBlobs() {
		if ( !extension_loaded( 'gmp' ) ) {
			echo "Can't find orphan blobs, need bitfield support provided by GMP.\n";
			return;
		}

		$dbw = wfGetDB( DB_MASTER );

		foreach ( $this->clusters as $cluster ) {
			echo "Searching for orphan blobs in $cluster...\n";
			$lb = wfGetLBFactory()->getExternalLB( $cluster );
			try {
				$extDB = $lb->getConnection( DB_SLAVE );
			} catch ( DBConnectionError $e ) {
				if ( strpos( $e->error, 'Unknown database' ) !== false ) {
					echo "No database on $cluster\n";
				} else {
					echo "Error on $cluster: " . $e->getMessage() . "\n";
				}
				continue;
			}
			$table = $extDB->getLBInfo( 'blobs table' );
			if ( is_null( $table ) ) {
				$table = 'blobs';
			}
			if ( !$extDB->tableExists( $table ) ) {
				echo "No blobs table on cluster $cluster\n";
				continue;
			}
			$startId = 0;
			$batchesDone = 0;
			$actualBlobs = gmp_init( 0 );
			$endId = $extDB->selectField( $table, 'MAX(blob_id)', false, __METHOD__ );

			// Build a bitmap of actual blob rows
			while ( true ) {
				$res = $extDB->select( $table,
					array( 'blob_id' ),
					array( 'blob_id > ' . $extDB->addQuotes( $startId ) ),
					__METHOD__,
					array( 'LIMIT' => $this->batchSize, 'ORDER BY' => 'blob_id' )
				);

				if ( !$res->numRows() ) {
					break;
				}

				foreach ( $res as $row ) {
					gmp_setbit( $actualBlobs, $row->blob_id );
				}
				$startId = $row->blob_id;

				++$batchesDone;
				if ( $batchesDone >= $this->reportingInterval ) {
					$batchesDone = 0;
					echo "$startId / $endId\n";
				}
			}

			// Find actual blobs that weren't tracked by the previous passes
			// This is a set-theoretic difference A \ B, or in bitwise terms, A & ~B
			$orphans = gmp_and( $actualBlobs, gmp_com( $this->trackedBlobs[$cluster] ) );

			// Traverse the orphan list
			$insertBatch = array();
			$id = 0;
			$numOrphans = 0;
			while ( true ) {
				$id = gmp_scan1( $orphans, $id );
				if ( $id == -1 ) {
					break;
				}
				$insertBatch[] = array(
					'bo_cluster' => $cluster,
					'bo_blob_id' => $id
				);
				if ( count( $insertBatch ) > $this->batchSize ) {
					$dbw->insert( 'blob_orphans', $insertBatch, __METHOD__ );
					$insertBatch = array();
				}

				++$id;
				++$numOrphans;
			}
			if ( $insertBatch ) {
				$dbw->insert( 'blob_orphans', $insertBatch, __METHOD__ );
			}
			echo "Found $numOrphans orphan(s) in $cluster\n";
		}
	}
}
