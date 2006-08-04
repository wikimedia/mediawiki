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

if ( $wgLang->getCode() == 'en' ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}

$count = $total = 0;
$wgEnglishLang = Language::factory( 'en' );
$wgEnglishMessages = $wgEnglishLang->getAllMessages();
$wgLocalMessages = $wgLang->getAllMessages();

foreach ( $wgEnglishMessages as $code => $msg ) {
	++$total;
	if ( $wgLocalMessages[$code] == $wgEnglishMessages[$code] ) {
		echo "* $code\n";
		++$count;
	}
}

echo "{$count} messages of {$total} are duplicates\n";
?>
