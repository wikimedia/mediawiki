<?php

/*
 * Makes the required database updates for rev_parent_id
 * to be of any use. It can be used for some simple tracking
 * and to find new page edits by users.
 */

define( 'BATCH_SIZE', 200 );

require_once 'commandLine.inc';
	
$db =& wfGetDB( DB_MASTER );
if ( !$db->tableExists( 'revision' ) ) {
	echo "revision table does not exist\n";
	exit( 1 );
}

populate_rev_parent_id( $db );

function populate_rev_parent_id( $db ) {
	echo "Populating rev_parent_id column\n";
	$start = $db->selectField( 'revision', 'MIN(rev_id)', false, __FUNCTION__ );
	$end = $db->selectField( 'revision', 'MAX(rev_id)', false, __FUNCTION__ );
	# Do remaining chunk
	$end += BATCH_SIZE - 1;
	$blockStart = $start;
	$blockEnd = $start + BATCH_SIZE - 1;
	$count = 0;
	$changed = 0;
	while( $blockEnd <= $end ) {
		echo "...doing rev_id from $blockStart to $blockEnd\n";
		$cond = "rev_id BETWEEN $blockStart AND $blockEnd";
		$res = $db->select( 'revision', 
			array('rev_id','rev_page','rev_timestamp','rev_parent_id'), 
			$cond, __FUNCTION__ );
		# Go through and update rev_parent_id from these rows.
		# Assume that the previous revision of the title was
		# the original previous revision of the title when the
		# edit was made...
		foreach( $res as $row ) {
			$previousID = $db->selectField( 'revision', 'rev_id', 
				array( 'rev_page' => $row->rev_page, "rev_timestamp < '{$row->rev_timestamp}'" ), 
				__FUNCTION__,
				array( 'ORDER BY' => 'rev_timestamp DESC' ) );
			$previousID = intval($previousID);
			if( $previousID != $row->rev_parent_id )
				$changed++;
			# Update the row...
			$db->update( 'revision',
				array( 'rev_parent_id' => $previousID ),
				array( 'rev_id' => $row->rev_id ),
				__FUNCTION__ );
			$count++;
		}
		$blockStart += BATCH_SIZE - 1;
		$blockEnd += BATCH_SIZE - 1;
		wfWaitForSlaves( 5 );
	}
	$logged = $db->insert( 'updatelog',
		array( 'ul_key' => 'populate rev_parent_id' ),
		__FUNCTION__,
		'IGNORE' );
	if( $logged ) {
		echo "rev_parent_id population complete ... {$count} rows [{$changed} changed]\n";
		return true;
	} else {
		echo "Could not insert rev_parent_id population row.\n";
		return false;
	}
}


