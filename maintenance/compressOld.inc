<?php

function compressOldPages( $start = 0 ) {
	$chunksize = 50;
	print "Starting from old_id $start...\n";
	do {
		$end = $start + $chunksize;
		$sql = "SELECT old_id,old_flags,old_namespace,old_title,old_text FROM old WHERE old_id>=$start ORDER BY old_id LIMIT $chunksize";
		$res = wfQuery( $sql, DB_READ, "compressOldPages" );
		if( wfNumRows( $res ) == 0 ) {
			break;
		}
		$last = $start;
		while( $row = wfFetchObject( $res ) ) {
			# print "  {$row->old_id} - {$row->old_namespace}:{$row->old_title}\n";
			compressPage( $row );
			$last = $row->old_id;
		}
		wfFreeResult( $res );
		$start = $last + 1; # Deletion may leave long empty stretches
		print "$start...\n";
	} while( true );
}

function compressPage( $row ) {
	if( false !== strpos( $row->old_flags, "gzip" ) ) {
		print "Already compressed row {$row->old_id}?\n";
		return false;
	}
	$flags = $row->old_flags ? "{$row->old_flags},gzip" : "gzip";
	$compress = wfStrencode( gzdeflate( $row->old_text ) );
	
	$sql = "UPDATE old SET old_flags='$flags', old_text='$compress' WHERE old_id={$row->old_id} LIMIT 1";
	 $res = wfQuery( $sql, DB_WRITE, 'compressPage' );
	return $res;
}

?>
