<?php
/**
 * Prints out messages that are the same as the message with the corrisponding
 * key in the English file
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
	if ( @$wgEnglishMessages[$key] == $msg ) {
		echo "* $key\n";
		++$count;
	}
}

echo "{$count} messages of {$total} are duplicates in the language {$code}\n";
?>
