<?php

define( 'REPORTING_INTERVAL', 100 );

if ( !defined( 'MEDIAWIKI' ) ) {
	$optionsWithArgs = array( 'm' );

	require_once( '../commandLine.inc' );
	require_once( 'includes/ExternalStoreDB.php' );

	resolveStubs();
}

/**
 * Convert history stubs that point to an external row to direct
 * external pointers
 */
function resolveStubs() {
	$fname = 'resolveStubs';

	print "Retrieving stub rows...\n";
	$dbr =& wfGetDB( DB_SLAVE );
	$maxID = $dbr->selectField( 'text', 'MAX(old_id)', false, $fname );
	$stubs = array();
	$flagsArray = array();

	# Do it in 100 blocks
	for ( $b = 0; $b < 100; $b++ ) {
		print "$b%\r";
		$start = intval($maxID / 100) * $b + 1;
		$end = intval($maxID / 100) * ($b + 1);

		$res = $dbr->select( 'text', array( 'old_id', 'old_text', 'old_flags' ),
			"old_id>=$start AND old_id<=$end AND old_flags like '%object%' ".
			"AND old_text LIKE 'O:15:\"historyblobstub\"%'", $fname );
		while ( $row = $dbr->fetchObject( $res ) ) {
			$stubs[$row->old_id] = $row->old_text;
			$flagsArray[$row->old_id] = $row->old_flags;
		}
		$dbr->freeResult( $res );
	}
	print "100%\n";

	print "\nConverting " . count( $stubs ) . " rows ...\n";

	# Get master database, no transactions
	$dbw =& wfGetDB( DB_MASTER );
	$dbw->clearFlag( DBO_TRX );
	$dbw->immediateCommit();

	$i = 0;
	foreach( $stubs as $id => $stub ) {
		if ( !(++$i % REPORTING_INTERVAL) ) {
			print "$i\n";
			wfWaitForSlaves( 5 );
		}

		resolveStub( $id, $stub, $flagsArray[$id] );
	}
}

/**
 * Resolve a history stub
 */
function resolveStub( $id, $stubText, $flags ) {
	$fname = 'resolveStub';

	$stub = unserialize( $stubText );
	$flags = explode( ',', $flags );

	$dbr =& wfGetDB( DB_SLAVE );
	$dbw =& wfGetDB( DB_MASTER );
	
	if ( get_class( $stub ) !== 'historyblobstub' ) {
		print "Error, invalid stub object\n";
		return;
	}

	# Get the (maybe) external row
	$externalRow = $dbr->selectRow( 'text', array( 'old_text' ), 
		array( 'old_id' => $stub->mOldId, "old_flags LIKE '%external%'" ),
		$fname 
	);

	if ( !$externalRow ) {
		# Object wasn't external
		continue;
	}

	# Preserve the legacy encoding flag, but switch from object to external
	if ( in_array( 'utf-8', $flags ) ) {
		$newFlags = 'external,utf-8';
	} else {
		$newFlags = 'external';
	}

	# Update the row
	$dbw->update( 'text',
		array( /* SET */
			'old_flags' => $newFlags, 
			'old_text' => $externalRow->old_text . '/' . $stub->mHash
		), 
		array( /* WHERE */ 
			'old_id' => $id 
		), $fname 
	);
}
?>
