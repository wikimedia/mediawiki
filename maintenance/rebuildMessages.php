<?

if ( ! is_readable( "../LocalSettings.php" ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

$DP = "../includes";
include_once( "../LocalSettings.php" );
include_once( "../AdminSettings.php" );

include_once( "{$IP}/Setup.php" );
include_once( "./InitialiseMessages.inc" );
$wgTitle = Title::newFromText( "Rebuild messages script" );
set_time_limit(0);

initialiseMessages( true );

exit();

?>
