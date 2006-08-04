<?php
/**
 * Prints out messages that are the same as the message with the corrisponding
 * key in the English file
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once('commandLine.inc');

echo "Note: the script also lists the messages which are not defined in this language file, please wait for the bugfix.\n\n";

if ( isset( $args[0] ) ) {
	$code = $args[0];
} else {
	$code = $wgLang->getCode();
}

if ( $code == 'en' ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}

$count = $total = 0;
$wgEnglishMessages = Language::getMessagesFor( 'en' );
$wgLocalMessages = Language::getMessagesFor( $code );

foreach ( $wgEnglishMessages as $key => $msg ) {
	++$total;
	if ( $wgLocalMessages[$key] == $wgEnglishMessages[$key] ) {
		echo "* $key\n";
		++$count;
	}
}

echo "{$count} messages of {$total} are duplicates in the language {$code}\n";
?>
