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
include_once( "../LocalSettings.php" );
include_once( "../AdminSettings.php" );

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
ini_set( "include_path", "$IP$sep$include_path" );

include_once( "Setup.php" );
include_once( "./compressOld.inc" );
$wgTitle = Title::newFromText( "Compress old pages script" );
set_time_limit(0);

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

if( !function_exists( "gzdeflate" ) ) {
	print "You must enable zlib support in PHP to compress old revisions!\n";
	print "Please see http://www.php.net/manual/en/ref.zlib.php\n\n";
	die();
}

print "Depending on the size of your database this may take a while!\n";
print "If you abort the script while it's running it shouldn't harm anything,\n";
print "but if you haven't backed up your data, you SHOULD abort now!\n\n";
print "Press control-c to abort first (will proceed automatically in 5 seconds)\n";
sleep(5);

$n = 0;
if( !empty( $argv[1] ) ) {
	$n = intval( $argv[1] );
}
compressOldPages( $n );

print "Done.\n";
exit();

?>
