<?php

# Rebuild link tracking tables from scratch.  This takes several
# hours, depending on the database size and server configuration.

require_once( "commandLine.inc" );
require_once( "./rebuildlinks.inc" );

$wgTitle = Title::newFromText( "Rebuild links script" );

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

rebuildLinkTables();

print "Done.\n";
exit();

?>
