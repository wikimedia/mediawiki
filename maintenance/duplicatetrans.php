<?php
/**
 * Prints out messages that are the same as the message with the corrisponding
 * key in the Language.php file
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
	if ( @$wgAllMessagesEn[$code] == $msg ) {
		echo "* $code\n";
		++$count;
	}
}

echo "{$count} messages of {$total} are duplicates\n";
?>
