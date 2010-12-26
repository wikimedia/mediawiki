<?php

require_once( dirname( __FILE__ ) . '/commandLine.inc' );

function purgeStaleMemcachedText() {
	global $wgMemc, $wgDBname;
	$db = wfGetDB( DB_MASTER );
	$maxTextId = $db->selectField( 'text', 'max(old_id)' );
	$latestReplicatedTextId = $db->selectField( array( 'recentchanges', 'revision' ), 'rev_text_id', 
		array( 'rev_id = rc_this_oldid', "rc_timestamp < '20101225183000'"),  'purgeStaleMemcachedText', 
		array( 'ORDER BY' => 'rc_timestamp DESC' ) );
	$latestReplicatedTextId -= 100; # A bit of paranoia

	echo "Going to purge text entries from $latestReplicatedTextId to $maxTextId in $wgDBname\n";

	for ( $i = $latestReplicatedTextId; $i < $maxTextId; $i++ ) {
		$keys = array();
		$keys[] = wfMemcKey( 'flaggedrevs', 'countPending', $i );
		$keys[] = wfMemcKey( 'flaggedrevs', 'includesSynced', $i );
		$keys[] = wfMemcKey( 'flaggedrevs', 'overrideRedirect', $i );
		$keys[] = wfMemcKey( 'unreviewedPages', 'underReview', $i );

		foreach ( $keys as $key ) {
			while (1) {
				if (! $wgMemc->delete( $key ) ) {
					echo "Memcache delete for $key returned false\n";
				}
				if ( $wgMemc->get( $key ) ) {
					echo "There's still content in $key!\n";
				} else {
					break;
				}
			}
		}
	}
}

purgeStaleMemcachedText();

