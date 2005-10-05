<?php

# Run this script periodically if you have miser mode enabled, to refresh the caches
$options = array('only','help');

require_once( 'commandLine.inc' );

require_once( 'SpecialPage.php' );
require_once( 'QueryPage.php' );

if(@$options['help']) {
	print "usage:updateSpecialPages.php [--help] [--only=page]\n";
	print "  --help      : this help message\n";
	print "  --only=page : only update 'page'. Ex: --only=BrokenRedirects\n";
	die();
}

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

	if( !(isset($options['only'])) or ($options['only'] == $queryPage->getName()) ) {
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

		# Wait for the slave to catch up
		$slaveDB =& wfGetDB( DB_SLAVE, array('QueryPage::recache', 'vslow' ) );
		while( $slaveDB->getLag() > 600 ) {
			print "Slave lagged, waiting...\n";
			sleep(30);

		}

	} else {
		print "cheap, skipped\n";
	}
	}
}

?>
