<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
function compressOldPages( $start = 0 ) {
	$fname = 'compressOldPages';

	$chunksize = 50;
	print "Starting from old_id $start...\n";
	$dbw =& wfGetDB( DB_MASTER );
	$old = $dbw->tableName( 'old' );
	do {
		$end = $start + $chunksize;
		$res = $dbw->select( 'old', array( 'old_id','old_flags','old_namespace','old_title','old_text' ),
			"old_id>=$start", $fname, array( 'ORDER BY' => 'old_id', 'LIMIT' => $chunksize, 'FOR UPDATE' ) );
		if( $dbw->numRows( $res ) == 0 ) {
			break;
		}
		$last = $start;
		while( $row = $dbw->fetchObject( $res ) ) {
			# print "  {$row->old_id} - {$row->old_namespace}:{$row->old_title}\n";
			compressPage( $row );
			$last = $row->old_id;
		}
		$dbw->freeResult( $res );
		$start = $last + 1; # Deletion may leave long empty stretches
		print "$start...\n";
	} while( true );
}

function compressPage( $row ) {
	if( false !== strpos( $row->old_flags, "gzip" ) ) {
		print "Already compressed row {$row->old_id}?\n";
		return false;
	}
	$dbw =& wfGetDB( DB_MASTER );
	$flags = $row->old_flags ? "{$row->old_flags},gzip" : "gzip";
	$compress = $dbw->strencode( gzdeflate( $row->old_text ) );
	$dbw->update( 'old', 
		array( /* SET */
			'old_flags' => $flags,
			'old_text' => $compress
		), array( /* WHERE */
			'old_id' => $row->old_id
		), $fname, 'LIMIT 1'
	);
	return $res;
}

?>
