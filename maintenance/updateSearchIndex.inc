<?php

function updateSearchIndex( $start, $end, $maxLockTime, $quiet ) {
	global $wgQuiet;
	$wgQuiet = $quiet;
	$fname = "updateSearchIndex";

	output( "Updating searchindex between $start and $end\n" );

	# Select entries from recentchanges which are on top and between the specified times
	$start = wfStrencode( $start );
	$end = wfStrencode( $end );

	$sql = "SELECT rc_cur_id FROM recentchanges
	  WHERE rc_this_oldid=0 AND rc_timestamp BETWEEN '$start' AND '$end'";
	$res = wfQuery( $sql, DB_READ, $fname );

	# Lock searchindex
	if ( $maxLockTime ) {
		output( "Waiting for lock..." );
		lockSearchindex();
		$lockTime = time();
		output( "done\n" );
	}

	# Loop through the results and do a search update
	while ( $row = wfFetchObject( $res ) ) {
		# Allow reads to be processed
		if ( $maxLockTime && time() > $lockTime + $maxLockTime ) {
			output( "Relocking..." );
			relockSearchindex();
			$lockTime = time();
			output( "\n" );
		}
		# Get cur row
		$curRow = wfGetArray( 'cur', array( 'cur_namespace', 'cur_title', 'cur_text' ), array( 'cur_id' => $row->rc_cur_id ) );
		if ( $curRow ) {
			$titleObj = Title::makeTitle( $curRow->cur_namespace, $curRow->cur_title );
			$title = $titleObj->getPrefixedDBkey();
			output( "$title ..." );
			# Update searchindex
			$u = new SearchUpdate( $row->rc_cur_id, $curRow->cur_title, $curRow->cur_text );
			$u->doUpdate();
			output( "\n" );
		}
	}
	
	# Unlock searchindex
	if ( $maxLockTime ) {
		unlockSearchindex();
	}
	output( "Done\n" );
}

function lockSearchindex() {
	wfQuery( "LOCK TABLES searchindex LOW_PRIORITY WRITE, cur READ", DB_WRITE );
}

function unlockSearchindex() {
	wfQuery( "UNLOCK TABLES", DB_WRITE );
}

# Unlock and lock again
# Since the lock is low-priority, queued reads will be able to complete
function relockSearchindex() {
	unlockSearchindex();
	lockSearchindex();
}

function output( $text ) {
	global $wgQuiet;
	if ( !$wgQuiet ) {
		print $text;
	}
}

?>
