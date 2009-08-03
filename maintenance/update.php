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
$wgUseMasterForMaintenance = true;
require_once( dirname(__FILE__) . '/commandLine.inc' );
require( "updaters.inc" );

# Don't try to load stuff from l10n_cache yet
$lc = Language::getLocalisationCache();
$lc->disableBackend();

$wgTitle = Title::newFromText( "MediaWiki database updater" );

echo( "MediaWiki {$wgVersion} Updater\n\n" );

install_version_checks();

# Attempt to connect to the database as a privileged user
# This will vomit up an error if there are permissions problems
$wgDatabase = wfGetDB( DB_MASTER );

print "Going to run database updates for ".wfWikiID()."\n";
print "Depending on the size of your database this may take a while!\n";

if( !isset( $options['quick'] ) ) {
	print "Abort with control-c in the next five seconds... ";
	wfCountDown( 5 );
}

$shared = isset( $options['doshared'] );
$purge = !isset( $options['nopurge'] );

do_all_updates( $shared, $purge );

print "Done.\n";


