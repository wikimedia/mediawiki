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
# TODO : check for AdminSettings file existence ? See #5725
print "Attempting connection to the database. If it fails, maybe you are\n";
print "missing a proper AdminSettings.php file in $IP\n\n";
$wgDatabase = $dbc->newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );

print "Going to run database updates for $wgDBname\n";
print "Depending on the size of your database this may take a while!\n";

if( !isset( $options['quick'] ) ) {
	print "Abort with control-c in the next five seconds... ";

	for ($i = 6; $i >= 1;) {
		print_c($i, --$i);
		sleep(1);
	}
	echo "\n";
}

if ( isset( $options['doshared'] ) ) {
	$doShared = true;
} else {
	$doShared = false;
}

do_all_updates( $doShared );

print "Done.\n";

?>
