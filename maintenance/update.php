<?php
require_once 'counter.php';
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
$dbclass = 'Database'.ucfirst($wgDBtype);
require_once("$dbclass.php");
$dbc = new $dbclass;
$wgDatabase = $dbc->newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );

print "Going to run database updates for $wgDBname\n";
print "Depending on the size of your database this may take a while!\n";

if( !isset( $options['quick'] ) ) {
	print "Abort with control-c in the next five seconds... ";

	for ($i = 6; $i >= 1;) {
		print_c($i, --$i);
		sleep(1);
	}
	die();
}

do_all_updates();

print "Done.\n";

?>
