<?php

# Rebuild search index table from scratch.  This takes several
# hours, depending on the database size and server configuration.

if ( ! is_readable( "../LocalSettings.php" ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

$wgCommandLineMode = true;
$DP = "../includes";
require_once( "../LocalSettings.php" );
require_once( "../AdminSettings.php" );

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
ini_set( "include_path", "$IP$sep$include_path" );

require_once( "Setup.php" );
require_once( "./rebuildtextindex.inc" );
$wgTitle = Title::newFromText( "Rebuild text index script" );
set_time_limit(0);

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

dropTextIndex();
rebuildTextIndex();
createTextIndex();

print "Done.\n";
exit();

?>
