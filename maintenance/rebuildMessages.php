<?php
$wgCommandLineMode = true;

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
if ( $argv[1] ) {
	$lang = $argv[1];
	putenv( "wikilang=$lang");
	$settingsFile = "/apache/htdocs/{$argv[1]}/w/LocalSettings.php";
	$newpath = "/apache/common/php$sep";
} else {
	$settingsFile = "../LocalSettings.php";
	$newpath = "";
}

if ( $argv[2] == "update" ) {
	$response = 1;
} elseif ( $argv[2] == "reinitialise" ) {
	$response = 2;
} else {
	$response = 0;
}

if ( ! is_readable( $settingsFile ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

ini_set( "include_path", "../includes$sep../languages$sep$newpath$IP$sep$include_path" );

$wgCommandLineMode = true;
$DP = "../includes";
include_once( $settingsFile );

include_once( "Setup.php" );
include_once( "./InitialiseMessages.inc" );
include_once( "../install-utils.inc" );
$wgTitle = Title::newFromText( "Rebuild messages script" );
$wgCommandLineMode = true;
set_time_limit(0);

if ( $response == 0 ) {
	$row = wfGetArray( "cur", array("count(*) as c"), array("cur_namespace" => NS_MEDIAWIKI) );
	print "Current namespace size: {$row->c}\n";

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
}

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
