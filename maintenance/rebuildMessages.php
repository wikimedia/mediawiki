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
ini_set( "include_path", "../includes$sep../languages$sep$include_path" );

include_once( "Setup.php" );
include_once( "./InitialiseMessages.inc" );
include_once( "../install-utils.inc" );
$wgTitle = Title::newFromText( "Rebuild messages script" );
$wgCommandLineMode = true;
set_time_limit(0);

print	"1. Update messages to include latest additions to Language.php\n" . 
		"2. Delete all messages and reinitialise namespace\n" .
		"3. Quit\n\n".
		
		"Please enter a number: ";

do {
	$response = IntVal(readconsole());
	if ( $response >= 1 && $response <= 3 ) {
		$good = true;
	} else {
		$good = false;
		print "Please type a number between 1 and 3: ";
	}
} while ( !$good );

switch ( $response ) {
	case 1:
		initialiseMessages( false );
		break;
	case 2:
		initialiseMessages( true );
		break;
}

exit();

?>
