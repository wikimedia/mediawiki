<?php
# Rebuild search index table from scratch.  This takes several
# hours, depending on the database size and server configuration.

require_once( "commandLine.inc" );
require_once( "./rebuildtextindex.inc" );
$wgTitle = Title::newFromText( "Rebuild text index script" );

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

dropTextIndex();
rebuildTextIndex();
createTextIndex();

print "Done.\n";
exit();

?>
