<?php

require( dirname( __FILE__ ) .'/../commandLine.inc' );


if ( count( $args ) < 1 ) {
	echo "Usage: php trackBlobs.php <cluster> [... <cluster>]\n";
	echo "Adds blobs from a given ES cluster to the blob_tracking table\n";
	exit( 1 );
}

trackBlobs( $args );

function trackBlobs( $clusters ) {
	initTrackingTable();
	trackRevisions( $clusters );
	trackOrphans( $clusters );
}

function initTrackingTable() {
	$dbw = wfGetDB( DB_MASTER );
	if ( !$dbw->tableExists( 'blob_tracking' ) ) {
		$dbw->sourceFile( dirname( __FILE__ ) . '/blob_tracking.sql' );
	}
}

function getTextClause( $clusters ) {
	$dbr = wfGetDB( DB_SLAVE );
	$textClause = '';
	foreach ( $clusters as $cluster ) {
		if ( $textClause != '' ) {
			$textClause .= ' OR ';
		}
		$textClause .= 'old_text LIKE ' . $dbr->addQuotes( $dbr->escapeLike( "DB://$cluster/" ) . '%' );
	}
	return $textClause;
}

function interpretPointer( $text ) {
	if ( !preg_match( '!^DB://(\w+)/(\d+)(?:/([0-9a-fA-F]+)|)$!', $text, $m ) ) {
		return false;
	}
	return array(
		'cluster' => $m[1],
		'id' => intval( $m[2] ),
		'hash' => isset( $m[3] ) ? $m[2] : null
	);
}

/**
 *  Scan the revision table for rows stored in the specified clusters
 */
function trackRevisions( $clusters ) {
	$dbw = wfGetDB( DB_MASTER );
	$dbr = wfGetDB( DB_SLAVE );
	$batchSize = 10;
	$reportingInterval = 10;

	$textClause = getTextClause( $clusters );
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
				"old_flags LIKE '%external%'",
			),
			__METHOD__,
			array(
				'ORDER BY' => 'rev_id',
				'LIMIT' => $batchSize
			)
		);
		if ( !$res->numRows() ) {
			break;
		}

		$insertBatch = array();
		foreach ( $res as $row ) {
			$startId = $row->rev_id;
			$info = interpretPointer( $row->old_text );
			if ( !$info ) {
				echo "Invalid DB:// URL in rev_id {$row->rev_id}\n";
				continue;
			}
			if ( !in_array( $info['cluster'], $clusters ) ) {
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
		}
		$dbw->insert( 'blob_tracking', $insertBatch, __METHOD__ );
		$rowsInserted += count( $insertBatch );

		++$batchesDone;
		if ( $batchesDone >= $reportingInterval ) {
			$batchesDone = 0;
			echo "$startId / $endId\n";
			wfWaitForSlaves( 5 );
		}
	}
	echo "Found $rowsInserted revisions\n";
}

/**
 * Scan the text table for orphan text
 */
function trackOrphans( $clusters ) {
	# Wait until the blob_tracking table is available in the slave
	$dbw = wfGetDB( DB_MASTER );
	$dbr = wfGetDB( DB_SLAVE );
	$pos = $dbw->getMasterPos();
	$dbr->masterPosWait( $pos, 100000 );

	$batchSize = 10;
	$reportingInterval = 10;

	$textClause = getTextClause( $clusters );
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
				"old_flags LIKE '%external%'",
				'bt_text_id IS NULL'
			),
			__METHOD__,
			array(
				'ORDER BY' => 'old_id',
				'LIMIT' => $batchSize 
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
			$info = interpretPointer( $row->old_text );
			if ( !$info ) {
				echo "Invalid DB:// URL in old_id {$row->old_id}\n";
				continue;
			}
			if ( !in_array( $info['cluster'], $clusters ) ) {
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
		}
		$dbw->insert( 'blob_tracking', $insertBatch, __METHOD__ );

		$rowsInserted += count( $insertBatch );
		++$batchesDone;
		if ( $batchesDone >= $reportingInterval ) {
			$batchesDone = 0;
			echo "$startId / $endId\n";
			wfWaitForSlaves( 5 );
		}
	}
	echo "Found $rowsInserted orphan text rows\n";
}
