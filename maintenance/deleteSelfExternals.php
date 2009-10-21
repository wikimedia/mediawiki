<?php

require_once("commandLine.inc");

print "Deleting self externals from $wgServer\n";
$db = wfGetDB(DB_MASTER);
while (1) {
	wfWaitForSlaves( 2 );
	$db->commit();
	$q = $db->limitResult( "DELETE /* deleteSelfExternals */ FROM externallinks WHERE el_to" 
		. $db->buildLike( $wgServer . '/', $db->anyString() ), 1000 );
	print "Deleting a batch\n";
	$db->query($q);
	if (!$db->affectedRows()) exit(0);
}
