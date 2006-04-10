<?php

/**
 * This script fixes timestamp corruption caused by one or more webservers 
 * temporarily being set to the wrong time. The time offset must be known and
 * consistent. Start and end times (in 14-character format) restrict the search, 
 * and must bracket the damage. There must be a majority of good timestamps in the 
 * search period.
 */

require_once( 'commandLine.inc' );

if ( count( $args ) < 3 ) {
	echo "Usage: php fixTimestamps.php <offset in hours> <start time> <end time>\n";
	exit(1);
}

$offset = $args[0] * 3600;
$start = $args[1];
$end = $args[2];
$fname = 'fixTimestamps.php';
$grace = 60; // maximum normal clock offset

# Find bounding revision IDs
$dbw =& wfGetDB( DB_MASTER );
$revisionTable = $dbw->tableName( 'revision' );
$res = $dbw->query( "SELECT MIN(rev_id) as minrev, MAX(rev_id) as maxrev FROM $revisionTable " .
	"WHERE rev_timestamp BETWEEN $start AND $end", $fname );
$row = $dbw->fetchObject( $res );
$minRev = $row->minrev;
$maxRev = $row->maxrev;

# Select all timestamps and IDs
$sql = "SELECT rev_id, rev_timestamp FROM $revisionTable " .
	"WHERE rev_id BETWEEN $minRev AND $maxRev";
if ( $offset > 0 ) {
	$sql .= " ORDER BY rev_id DESC";
	$expectedSign = -1;
} else {
	$expectedSign = 1;
}

$res = $dbw->query( $sql, $fname );

$lastNormal = 0;
$badRevs = array();
$numGoodRevs = 0;

while ( $row = $dbw->fetchObject( $res ) ) {
	$timestamp = wfTimestamp( TS_UNIX, $row->rev_timestamp );
	$delta = $timestamp - $lastNormal;
	$sign = $delta / abs( $delta );
	if ( $sign == $expectedSign ) {
		// Monotonic change
		$lastNormal = $timestamp;
		++ $numGoodRevs;
		continue;
	} elseif ( abs( $delta ) <= $grace ) {
		// Non-monotonic change within grace interval
		++ $numGoodRevs;
		continue;
	} else {
		// Non-monotonic change larger than grace interval
		$badRevs[] = $row->rev_id;
	}
}
$dbw->freeResult( $res );

if ( count( $badRevs ) > $numGoodRevs ) {
	echo 
"The majority of revisions in the search interval are marked as bad.

Are you sure the offset ($offset) has the right sign? Positive means the clock 
was incorrectly set forward, negative means the clock was incorrectly set back.

If the offset is right, then increase the search interval until there are enough 
good revisions to provide a majority reference.
";

	exit(1);
}

printf( "Fixing %d revisions (%.2f%% of revisions in search interval)\n", 
	count( $badRevs ), count($badRevs) / $numGoodRevs * 100 );

$sql = "UPDATE $revisionTable " .
	"SET rev_timestamp=DATE_FORMAT(DATE_ADD(rev_timestamp, INTERVAL $fixup SECOND), '%Y%m%d%H%i%s') " .
	"WHERE rev_id IN (" . $dbw->makeList( $badRevs ) . ')';
echo $sql;
//$dbw->query( $sql, $fname );
//echo "Done\n";

?>
