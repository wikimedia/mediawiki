<?php
/**
 * Prints out messages that are no longer used.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once('commandLine.inc');

if ( 'en' == $wgLanguageCode ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}

$count = $total = 0;
$msgarray = 'wgAllMessages' . ucfirst( $wgLanguageCode );

foreach ( $$msgarray as $code => $msg ) {
	++$total;
	if ( ! array_key_exists( $code, $wgAllMessagesEn ) ) {
		print "* $code\n";
		++$count;
	}
}

print "{$count} messages of {$total} are redundant\n";
?>
