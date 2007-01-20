<?php
/**
 * Rebuild link tracking tables from scratch.  This takes several
 * hours, depending on the database size and server configuration.
 * @todo document
 * @addtogroup Maintenance
 */

/** */
require_once( "commandLine.inc" );

#require_once( "rebuildlinks.inc" );
require_once( "refreshLinks.inc" );
require_once( "rebuildtextindex.inc" );
require_once( "rebuildrecentchanges.inc" );

$database = Database::newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );

print "** Rebuilding fulltext search index (if you abort this will break searching; run this script again to fix):\n";
dropTextIndex( $database );
rebuildTextIndex( $database );
createTextIndex( $database );

print "\n\n** Rebuilding recentchanges table:\n";
rebuildRecentChangesTablePass1();
rebuildRecentChangesTablePass2();

# Doesn't work anymore
# rebuildLinkTables();

# Use the slow incomplete one instead. It's designed to work in the background
print "\n\n** Rebuilding links tables -- this can take a long time. It should be safe to abort via ctrl+C if you get bored.\n";
refreshLinks( 1 );

print "Done.\n";
exit();

?>
