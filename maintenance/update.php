<?php
/**
 * Run all updaters.
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */ 

/** */
require_once( "commandLine.inc" );
require_once( "updaters.inc" );
$wgTitle = Title::newFromText( "MediaWiki database updater" );
$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );

do_all_updates();

print "Done.\n";

?>
