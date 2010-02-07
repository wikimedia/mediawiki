<?php
/**
 * Run all updaters.
 *
 * This is used when the database schema is modified and we need to apply patches.
 *
 * @file
 * @todo document
 * @ingroup Maintenance
 */

/** */
define( 'MW_CMDLINE_CALLBACK', 'wfSetupUpdateScript' );
$wgUseMasterForMaintenance = true;
require_once( dirname(__FILE__) . '/commandLine.inc' );
require( "updaters.inc" );

$wgTitle = Title::newFromText( "MediaWiki database updater" );

echo( "MediaWiki {$wgVersion} Updater\n\n" );

if( !isset( $options['skip-compat-checks'] ) ) {
	install_version_checks();
} else {
	print "Skipping compatibility checks, proceed at your own risk (Ctrl+C to abort)\n";
	wfCountdown(5);
}

# Attempt to connect to the database as a privileged user
# This will vomit up an error if there are permissions problems
$wgDatabase = wfGetDB( DB_MASTER );

print "Going to run database updates for ".wfWikiID()."\n";
print "Depending on the size of your database this may take a while!\n";

if( !isset( $options['quick'] ) ) {
	print "Abort with control-c in the next five seconds (skip this countdown with --quick) ... ";
	wfCountDown( 5 );
}

$shared = isset( $options['doshared'] );
$purge = !isset( $options['nopurge'] );

do_all_updates( $shared, $purge );

print "Done.\n";

function wfSetupUpdateScript() {
	global $wgLocalisationCacheConf;

	# Don't try to access the database
	# This needs to be disabled early since extensions will try to use the l10n 
	# cache from $wgExtensionSetupFunctions (bug 20471)
	$wgLocalisationCacheConf = array(
		'class' => 'LocalisationCache',	
		'storeClass' => 'LCStore_Null',
		'storeDirectory' => false,
		'manualRecache' => false,
	);
}
