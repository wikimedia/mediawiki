<?php
/**
 * Run all updaters.
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */ 

/** */
$wgUseMasterForMaintenance = true;
$options = array( 'quick' );
require_once( "commandLine.inc" );
require_once( "updaters.inc" );
$wgTitle = Title::newFromText( "MediaWiki database updater" );
$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );

print "Going to run database updates for $wgDBname\n";
print "Depending on the size of your database this may take a while!\n";

if( !isset( $options['quick'] ) ) {
	print "Abort with control-c in the next five seconds... ";
	
	for ($i = 5; $i >= 0; --$i) {
		echo $i;
		sleep(1);
		echo( ($i == 0) ? "\n" : chr(8) );
	}
}

do_all_updates();

print "Done.\n";

?>
