<?php
require_once( "commandLine.inc" );
include_once( "./InitialiseMessages.inc" );

$wgTitle = Title::newFromText( "Rebuild messages script" );

if ( isset( $argv[0] ) ) {
	$response = array_shift( $argv );
	if ( $response == "update" ) {
		$response = 1;
	} elseif ( $response == "rebuild" ) {
		$response = 2;
	}
} else {
	$response = 0;
}
if ( isset( $argv[0] ) ) {
	$messages = loadLanguageFile( array_shift( $argv ) );
} else {
	$messages = false;
}

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
		initialiseMessages( false, $messages );
		break;
	case 2:
		initialiseMessages( true, $messages );
		break;
}

exit();

?>
