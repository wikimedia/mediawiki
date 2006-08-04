<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 * Check to see if all messages have been translated into the selected language.
 * To run this script, you must have a working installation, and it checks the
 * selected language of that installation.
 */

/** */
require_once('commandLine.inc');

die( "This script currently *does not work*, please wait for fix.\n" );

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
	if ( !isset( $wgLocalMessages[$code] ) ) {
		print "'{$code}' => \"$msg\",\n";
		++$count;
	}
}

print "{$count} messages of {$total} not translated.\n";
?>
