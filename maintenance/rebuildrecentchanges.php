<?php
/**
 * Rebuild link tracking tables from scratch.  This takes several
 * hours, depending on the database size and server configuration.
 *
 * @todo document
 * @addtogroup Maintenance
 */

/** */
require_once( "commandLine.inc" );
require_once( "rebuildrecentchanges.inc" );
$wgTitle = Title::newFromText( "Rebuild recent changes script" );

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

rebuildRecentChangesTablePass1();
rebuildRecentChangesTablePass2();
rebuildRecentChangesTablePass3(); // flag bot edits

print "Done.\n";
exit();

?>
