<?php

$wgUseRootUser = true;
require_once( 'commandLine.inc' );

//$wgDebugLogFile = '/dev/stdout';

$slaveIndexes = array();
for ( $i = 1; $i < count( $wgDBservers ); $i++ ) {
	if ( $wgLoadBalancer->isNonZeroLoad( $i ) ) {
		$slaveIndexes[] = $i;
	}
}
/*
foreach ( $wgLoadBalancer->mServers as $i => $server ) {
	$wgLoadBalancer->mServers[$i]['flags'] |= DBO_DEBUG;
}*/
$reportingInterval = 1000;

if ( isset( $args[0] ) ) {
	desyncFixPage( $args[0] );
} else {
	$dbw =& wfGetDB( DB_MASTER );
	$maxPage = $dbw->selectField( 'page', 'MAX(page_id)', false, 'fixDesync.php' );
	for ( $i=1; $i <= $maxPage; $i++ ) {
		desyncFixPage( $i );
		if ( !($i % $reportingInterval) ) {
			print "$i\n";
		}
	}
}

function desyncFixPage( $pageID ) {
	global $slaveIndexes;
	$fname = 'desyncFixPage';

	# Check for a corrupted page_latest
	$dbw =& wfGetDB( DB_MASTER );
	$realLatest = $dbw->selectField( 'page', 'page_latest', array( 'page_id' => $pageID ), $fname );
	$found = false;
	foreach ( $slaveIndexes as $i ) {
		$db =& wfGetDB( $i );
		$latest = $db->selectField( 'page', 'page_latest', array( 'page_id' => $pageID ), $fname );
		$max = $db->selectField( 'revision', 'MAX(rev_id)', false, $fname );
		if ( $latest != $realLatest && $realLatest < $max ) {
			print "page_latest corrupted in page $pageID, server $i\n";
			$found = true;
			break;
		}
	}
	if ( !$found ) {
		return;
	}

	# Find the missing revision
	$res = $dbw->select( 'revision', array( 'rev_id' ), array( 'rev_page' => $pageID ), $fname );
	$masterIDs = array();
	while ( $row = $dbw->fetchObject( $res ) ) {
		$masterIDs[] = $row->rev_id;
	}
	$dbw->freeResult( $res );

	$res = $db->select( 'revision', array( 'rev_id' ), array( 'rev_page' => $pageID ), $fname );
	$slaveIDs = array();
	while ( $row = $db->fetchObject( $res ) ) {
		$slaveIDs[] = $row->rev_id;
	}
	$db->freeResult( $res );
	$missingIDs = array_diff( $masterIDs, $slaveIDs );

	if ( count( $missingIDs ) ) {
		print "Found " . count( $missingIDs ) . " missing revision(s), copying from master... ";
		foreach ( $missingIDs as $rid ) {
			print "$rid ";
			# Revision
			$row = $dbw->selectRow( 'revision', '*', array( 'rev_id' => $rid ), $fname );
			foreach ( $slaveIndexes as $i ) {
				$db =& wfGetDB( $i );
				$db->insert( 'revision', get_object_vars( $row ), $fname, 'IGNORE' );
			}

			# Text
			$row = $dbw->selectRow( 'text', '*', array( 'old_id' => $row->rev_text_id ), $fname );
			foreach ( $slaveIndexes as $i ) {
				$db =& wfGetDB( $i );
				$db->insert( 'text', get_object_vars( $row ), $fname, 'IGNORE' );
			}
		}
		print "done\n";
	}

	print "Fixing page_latest... ";
	foreach ( $slaveIndexes as $i ) {
		$db =& wfGetDB( $i );
		$db->update( 'page', array( 'page_latest' => $realLatest ), array( 'page_id' => $pageID ), $fname );
	}
	print "done\n";
}

?>
