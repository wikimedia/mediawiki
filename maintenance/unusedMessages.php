<?php
/**
 * Prints out messages in localisation files that are no longer used.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once('commandLine.inc');

if ( $wgLang->getCode() == 'en' ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}

$count = $total = 0;
$wgEnglishLang = Language::factory( 'en' );
$wgEnglishMessages = $wgEnglishLang->getAllMessages();
$wgLocalMessages = $wgLang->getAllMessages();

foreach ( $wgLocalMessages as $code => $msg ) {
	++$total;
	if ( !isset( $wgEnglishMessages[$code] ) ) {
		print "* $code\n";
		++$count;
	}
}

print "{$count} messages of {$total} are unused\n";
?>
