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

print "Going to run database updates for $wgDBname\n";
print "Depending on the size of your database this may take a while!\n";
print "Abort with control-c in the next five seconds or...\n";
sleep(5);


do_all_updates();

print "Done.\n";

?>
