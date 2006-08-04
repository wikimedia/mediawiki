<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 * Check to see if all messages have been translated into the selected language.
 * To run this script, you must have a working installation, and you can specify
 * a language, or the script will check the installation language.
 */

/** */
require_once('commandLine.inc');

#die( "This script currently *does not work*, please wait for fix.\n" );

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
	if ( !isset( $wgLocalMessages[$key] ) ) {
		print "'{$key}' => \"$msg\",\n";
		++$count;
	}
}

print "{$count} messages of {$total} are not translated in the language {$code}.\n";
?>
