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
$dbclass = 'Database' . ucfirst( $wgDBtype ) ;
require_once("$dbclass.php");
$dbc = new $dbclass;

echo( "MediaWiki {$wgVersion} Updater\n\n" );

# Do a pre-emptive check to ensure we've got credentials supplied
# We can't, at this stage, check them, but we can detect their absence,
# which seems to cause most of the problems people whinge about
if( !isset( $wgDBadminuser ) || !isset( $wgDBadminpassword ) ) {
	echo( "No superuser credentials could be found. Please provide the details\n" );
	echo( "of a user with appropriate permissions to update the database. See\n" );
	echo( "AdminSettings.sample for more details.\n\n" );
	exit();
}

# Attempt to connect to the database as a privileged user
# This will vomit up an error if there are permissions problems
$wgDatabase = $dbc->newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname, 1 );

if( !$wgDatabase->isOpen() ) {
	# Appears to have failed
	echo( "A connection to the database could not be established. Check the\n" );
	# Let's be a bit clever and guess at what's wrong
	if( isset( $wgDBadminuser ) && isset( $wgDBadminpassword ) ) {
		# Tell the user the value(s) are wrong
		echo( 'values of $wgDBadminuser and $wgDBadminpassword.' . "\n" );
	}
	exit();
}

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
