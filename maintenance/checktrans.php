<?php

# Check to see if all messages have been translated into
# the selected language. To run this script, you must have
# a working installation, and it checks the selected language
# of that installation.
#

if ( ! is_readable( "../LocalSettings.php" ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

$wgCommandLineMode = true;
$DP = "../includes";
include_once( "../LocalSettings.php" );

if ( "en" == $wgLanguageCode ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}
$include = "Language" . ucfirst( $wgLanguageCode ) . ".php";
if ( ! is_readable( "{$IP}/{$include}" ) ) {
	print "Translation file \"{$include}\" not found in installation directory.\n" .
	  "You must have the software installed to run this script.\n";
	exit();
}

umask( 000 );
set_time_limit( 0 );

include_once( "{$IP}/Setup.php" );
$wgTitle = Title::newFromText( "Translation checking script" );

$count = $total = 0;
$msgarray = "wgAllMessages" . ucfirst( $wgLanguageCode );

foreach ( $wgAllMessagesEn as $code => $msg ) {
	++$total;

	if ( ! array_key_exists( $code, $$msgarray ) ) {
		print "{$code}\n";
		++$count;
	}
}
print "{$count} messages of {$total} not translated.\n";

