<?php

# Rebuild link tracking tables from scratch.  This takes several
# hours, depending on the database size and server configuration.

require_once( "commandLine.inc" );

#require_once( "rebuildlinks.inc" );
require_once( "refreshlinks.inc" );
require_once( "rebuildtextindex.inc" );
require_once( "rebuildrecentchanges.inc" );

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

# Doesn't work anymore
# rebuildLinkTables();

# Use the slow incomplete one instead. It's designed to work in the background
#refreshLinks( 1 );

dropTextIndex();
rebuildTextIndex();
createTextIndex();

rebuildRecentChangesTablePass1();
rebuildRecentChangesTablePass2();

print "Done.\n";
exit();

?>
