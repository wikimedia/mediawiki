<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
function updateSearchIndex( $start, $end, $maxLockTime, $quiet ) {
	global $wgQuiet;
	global $wgDisableSearchUpdate;

	$fname = "updateSearchIndex";

	$wgQuiet = $quiet;
	$wgDisableSearchUpdate = false;

	$dbw =& wfGetDB( DB_MASTER );
	$recentchanges = $dbw->tableName( 'recentchanges' );
	
	output( "Updating searchindex between $start and $end\n" );

	# Select entries from recentchanges which are on top and between the specified times
	$start = $dbw->strencode( $start );
	$end = $dbw->strencode( $end );

	$sql = "SELECT rc_cur_id,rc_type,rc_moved_to_ns,rc_moved_to_title FROM $recentchanges
	  WHERE rc_this_oldid=0 AND rc_timestamp BETWEEN '$start' AND '$end'";
	$res = $dbw->query( $sql, $fname );

	# Lock searchindex
	if ( $maxLockTime ) {
		output( "   --- Waiting for lock ---" );
		lockSearchindex();
		$lockTime = time();
		output( "\n" );
	}

	# Loop through the results and do a search update
	while ( $row = $dbw->fetchObject( $res ) ) {
		# Allow reads to be processed
		if ( $maxLockTime && time() > $lockTime + $maxLockTime ) {
			output( "    --- Relocking ---" );
			relockSearchindex();
			$lockTime = time();
			output( "\n" );
		}
		if ( $row->rc_type == RC_LOG ) {
			continue;
		} elseif ( $row->rc_type == RC_MOVE || $row->rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Rename searchindex entry
			$titleObj = Title::makeTitle( $row->rc_moved_to_ns, $row->rc_moved_to_title );
			$title = $titleObj->getPrefixedDBkey();
			output( "$title..." );
			$u = new SearchUpdate( $row->rc_cur_id, $title, false );
			output( "\n" );
		} else {
			# Get cur row
			$curRow = $dbw->selectRow( 'cur', array( 'cur_namespace', 'cur_title', 'cur_text' ), 
				array( 'cur_id' => $row->rc_cur_id ), $fname, 'FOR UPDATE' );
			if ( $curRow ) {
				$titleObj = Title::makeTitle( $curRow->cur_namespace, $curRow->cur_title );
				$title = $titleObj->getPrefixedDBkey();
				output( $title );
				# Update searchindex
				$u = new SearchUpdate( $row->rc_cur_id, $curRow->cur_title, $curRow->cur_text );
				$u->doUpdate();
				output( "\n" );
			}
		}
	}
	
	# Unlock searchindex
	if ( $maxLockTime ) {
		unlockSearchindex();
	}
	output( "Done\n" );
}

function lockSearchindex( &$db ) {
	$dbw =& wfGetDB( DB_MASTER );
	extract( $dbw->tableNames( 'searchindex', 'cur', 'interwiki' ) );
	$dbw->query( "LOCK TABLES $searchindex LOW_PRIORITY WRITE, $cur READ, $interwiki READ" );
}

function unlockSearchindex() {
	$dbw =& wfGetDB( DB_MASTER );
	$dbw->query( "UNLOCK TABLES" );
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
