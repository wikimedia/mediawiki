<?
$wgCommandLineMode = true;

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
include_once( "./InitialiseMessages.inc" );
$wgTitle = Title::newFromText( "Rebuild messages script" );
$wgCommandLineMode = true;
set_time_limit(0);

initialiseMessages( true );

exit();

?>
