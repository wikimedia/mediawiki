<?

# Rebuild link tracking tables from scratch.  This takes several
# hours, depending on the database size and server configuration.

if ( ! is_readable( "../LocalSettings.php" ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

$DP = "../includes";
include_once( "../LocalSettings.php" );
include_once( "../AdminSettings.php" );

include_once( "{$IP}/Setup.php" );
include_once( "./rebuildrecentchanges.inc" );
$wgTitle = Title::newFromText( "Rebuild recent changes script" );
set_time_limit(0);

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

rebuildRecentChangesTablePass1();
rebuildRecentChangesTablePass2();

print "Done.\n";
exit();

?>
