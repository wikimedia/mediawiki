<?php

define( 'REPORTING_INTERVAL', 100 );
define( 'STUB_HEADER', 'O:15:"historyblobstub"' );

if ( !defined( 'MEDIAWIKI' ) ) {
	$optionsWithArgs = array( 'm' );

	require_once( '../commandLine.inc' );
	require_once( 'ExternalStoreDB.php' );
	require_once( 'resolveStubs.php' );

	$fname = 'moveToExternal';

	if ( !isset( $args[0] ) ) {
		print "Usage: php moveToExternal.php [-m <maxid>] <cluster>\n";
		exit;
	}

	$cluster = $args[0];
	$dbw =& wfGetDB( DB_MASTER );

	if ( isset( $options['m'] ) ) {
		$maxID = $options['m'];
	} else {
		$maxID = $dbw->selectField( 'text', 'MAX(old_id)', false, $fname );
	}

	moveToExternal( $cluster, $maxID );
}



function moveToExternal( $cluster, $maxID ) {
	$fname = 'moveToExternal';
	$dbw =& wfGetDB( DB_MASTER );

	print "Moving $maxID text rows to external storage\n";
	$ext = new ExternalStoreDB;
	for ( $id = 1; $id <= $maxID; $id++ ) {
		if ( !($id % REPORTING_INTERVAL) ) {
			print "$id\n";
			wfWaitForSlaves( 5 );
		}
		$row = $dbw->selectRow( 'text', array( 'old_flags', 'old_text' ),
			array( 
				'old_id' => $id,
				"old_flags NOT LIKE '%external%'",
			), $fname );
		if ( !$row ) {
			# Non-existent or already done
			continue;
		}

		# Resolve stubs
		$flags = explode( ',', $row->old_flags );
		if ( in_array( 'object', $flags ) 
			&& substr( $row->old_text, 0, strlen( STUB_HEADER ) ) === STUB_HEADER ) 
		{
			resolveStub( $id, $row->old_text, $row->old_flags );
			continue;
		}

		$url = $ext->store( $cluster, $row->old_text );
		if ( !$url ) {
			print "Error writing to external storage\n";
			exit;
		}
		if ( $row->old_flags === '' ) {
			$flags = 'external';
		} else {
			$flags = "{$row->old_flags},external";
		}
		$dbw->update( 'text', 
			array( 'old_flags' => $flags, 'old_text' => $url ),
			array( 'old_id' => $id ), $fname );
	}
}

?>
