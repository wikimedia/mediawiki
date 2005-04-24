<?php

# Run this script periodically if you have miser mode enabled, to refresh the caches

require_once( 'commandLine.inc' );

require_once( 'SpecialPage.php' );
require_once( 'QueryPage.php' );

$wgOut->disable();
$dbw =& wfGetDB( DB_MASTER );

foreach ( $wgQueryPages as $page ) {
	list( $class, $special ) = $page;

	$specialObj = SpecialPage::getPage( $special );
	if ( !$specialObj ) {
		print "No such special page: $special\n";
		exit;
	}
	$file = $specialObj->getFile();
	if ( $file ) {
		require_once( $file );
	}
	$queryPage = new $class;

	printf( '%-30s',  $special );

	if ( $queryPage->isExpensive() ) {
		$t1 = explode( ' ', microtime() );
		# Do the query
		$num = $queryPage->recache();
		$t2 = explode( ' ', microtime() );

		if ( $num === false ) {
			print "FAILED: database error\n";
		} else {
			print "got $num rows in ";

			$elapsed = ($t2[0] - $t1[0]) + ($t2[1] - $t1[1]);
			$hours = intval( $elapsed / 3600 );
			$minutes = intval( $elapsed % 3600 / 60 );
			$seconds = $elapsed - $hours * 3600 - $minutes * 60;
			if ( $hours ) {
				print $hours . 'h ';
			}
			if ( $minutes ) {
				print $minutes . 'm ';
			}
			printf( "%.2fs\n", $seconds );
		}

		# Reopen any connections that have closed
		if ( !$wgLoadBalancer->pingAll())  {
			print "\n";
			do {
				print "Connection failed, reconnecting in 10 seconds...\n";
				sleep(10);
			} while ( !$wgLoadBalancer->pingAll() );
			print "Reconnected\n\n";
		} else {
			# Commit the results
			$dbw->immediateCommit();
		}
	} else {
		print "cheap, skipped\n";
	}
}

?>
