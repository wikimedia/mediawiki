<?php
/**
 * Prints out messages in localisation files that are no longer used.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once('commandLine.inc');

if ( isset( $args[0] ) ) {
	$code = $args[0];
} else {
	$code = $wgLang->getCode();
}

if ( $code == 'en' ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}

$filename = Language::getMessagesFileName( $code );
if ( file_exists( $filename ) ) {
	require( $filename );
} else {
	$messages = array();
}

$count = $total = 0;
$wgEnglishMessages = Language::getMessagesFor( 'en' );
$wgLocalMessages = $messages;

foreach ( $wgLocalMessages as $key => $msg ) {
	++$total;
	if ( !isset( $wgEnglishMessages[$key] ) ) {
		print "* $key\n";
		++$count;
	}
}

print "{$count} messages of {$total} are unused in the language {$code}\n";
?>
