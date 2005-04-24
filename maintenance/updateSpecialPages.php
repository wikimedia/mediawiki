<?php

# Run this script periodically if you have miser mode enabled, to refresh the caches

require_once( 'commandLine.inc' );

require_once( 'SpecialPage.php' );
require_once( 'QueryPage.php' );

$wgOut->disable();

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
		$num = $queryPage->doQuery( 0, 0, true );
		$t2 = explode( ' ', microtime() );

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
		printf( "%.2f s\n", $seconds );
	} else {
		print "cheap, skipped\n";
	}
}

?>
