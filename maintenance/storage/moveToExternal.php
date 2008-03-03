<?php

define( 'REPORTING_INTERVAL', 100 );

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
		$text = $row->old_text;
		if ( $row->old_flags === '' ) {
			$flags = 'external';
		} else {
			$flags = "{$row->old_flags},external";
		}
		
		if ( strpos( $flags, 'object' ) !== false ) {
			$obj = unserialize( $text );
			$className = strtolower( get_class( $obj ) );
			if ( $className == 'historyblobstub' ) {
				resolveStub( $id, $row->old_text, $row->old_flags );
				continue;
			} elseif ( $className == 'historyblobcurstub' ) {
				$text = gzdeflate( $obj->getText() );
				$flags = 'utf-8,gzip,external';
			} elseif ( $className == 'concatenatedgziphistoryblob' ) {
				// Do nothing
			} else {
				print "Warning: unrecognised object class \"$className\"\n";
				continue;
			}
		}

		if ( strlen( $text ) < 100 ) {
			// Don't move tiny revisions
			continue;
		}

		#print "Storing "  . strlen( $text ) . " bytes to $url\n";

		$url = $ext->store( $cluster, $text );
		if ( !$url ) {
			print "Error writing to external storage\n";
			exit;
		}
		$dbw->update( 'text',
			array( 'old_flags' => $flags, 'old_text' => $url ),
			array( 'old_id' => $id ), $fname );
	}
}

?>
