<?php

require_once("commandLine.inc");

print "Deleting self externals from $wgServer\n";
$db = wfGetDB(DB_MASTER);
while (1) {
	wfWaitForSlaves( 2 );
	$db->commit();
	$encServer = $db->escapeLike( $wgServer );
	$q="DELETE /* deleteSelfExternals */ FROM externallinks WHERE el_to LIKE '$encServer/%' LIMIT 1000\n";
	print "Deleting a batch\n";
	$db->query($q);
	if (!$db->affectedRows()) exit(0);
}
