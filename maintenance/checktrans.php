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

if ( 'en' == $wgLanguageCode ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}

$count = $total = 0;
$msgarray = 'wgAllMessages' . ucfirst( $wgLanguageCode );

foreach ( $wgAllMessagesEn as $code => $msg ) {
	++$total;
	if ( ! array_key_exists( $code, $$msgarray ) ) {
		print "'{$code}' => \"$msg\",\n";
		++$count;
	}
}

print "{$count} messages of {$total} not translated.\n";
?>
