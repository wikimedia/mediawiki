<?php

function updateSearchIndex( $start, $end, $batchSize, $quiet ) {
	global $wgQuiet;
	global $wgDisableSearchUpdate;

	$fname = "updateSearchIndex";

	$wgQuiet = $quiet;
	$wgDisableSearchUpdate = false;

	output( "Updating searchindex between $start and $end\n" );

	# Select entries from recentchanges which are on top and between the specified times
	$start = wfStrencode( $start );
	$end = wfStrencode( $end );

	$doneTitles = array();

	$sql = "SELECT rc_cur_id,rc_type,rc_moved_to_ns,rc_moved_to_title,rc_timestamp FROM recentchanges
	  WHERE rc_this_oldid=0 AND rc_timestamp BETWEEN '$start' AND '$end'";
	$res = wfQuery( $sql, DB_READ, $fname );

	do {
		# Read a batch of rows and construct the update objects
		$updates = array();
		output( 'Reading' );
		for ( $i=0; $i<$batchSize && $row = wfFetchObject( $res ); $i++ ) {
			if ( $row->rc_type == RC_LOG ) {
				$u = false;
			} elseif ( $row->rc_type == RC_MOVE || $row->rc_type == RC_MOVE_OVER_REDIRECT ) {
				# Rename searchindex entry
				$titleObj = Title::makeTitle( $row->rc_moved_to_ns, $row->rc_moved_to_title );
				$title = $titleObj->getPrefixedDBkey();
				$u = new SearchUpdate( $row->rc_cur_id, $title, false );
				$key = "{$row->rc_timestamp}\t$title";
			} else {
				# Edit
				# Get cur row
				$curRow = wfGetArray( 'cur', array( 'cur_namespace', 'cur_title', 'cur_text' ), array( 'cur_id' => $row->rc_cur_id ) );
				$u = false;
				if ( $curRow ) {
					$titleObj = Title::makeTitle( $curRow->cur_namespace, $curRow->cur_title );
					$title = $titleObj->getPrefixedDBkey();
					# Only update if this article has not been updated during this run
					if ( empty( $doneTitles[$title] ) ) {
						$doneTitles[$title] = 1;
						# Update searchindex
						$u = new SearchUpdate( $row->rc_cur_id, $curRow->cur_title, $curRow->cur_text );
						$key = "{$row->rc_timestamp}\t$title";
					}
				}
			}
			if ( $u ) {
				$updates[$key] = $u;
			}
			output( '.' );
		}
		output( "\n" );

		# Perform updates
		if ( count( $updates ) ) {
			lockSearchindex();
			foreach ( $updates as $key => $u) {
				output( "$key" );
				$u->doUpdate();
				output( "\n" );
			}
			unlockSearchindex();
		}
	} while ( $row );
	output( "Done\n" );
}

function lockSearchindex() {
	wfQuery( "LOCK TABLES searchindex LOW_PRIORITY WRITE", DB_WRITE );
}

function unlockSearchindex() {
	wfQuery( "UNLOCK TABLES", DB_WRITE );
}

function output( $text ) {
	global $wgQuiet;
	if ( !$wgQuiet ) {
		print $text;
	}
}

?>
