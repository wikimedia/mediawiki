<?php
/**
 * This script is used to clear the interwiki links for ALL languages in
 * memcached.
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once('commandLine.inc');

foreach ( $wgLocalDatabases as $db ) {
	print "$db ";
	foreach ( $wgLanguageNamesEn as $prefix => $name ) {
		$wgMemc->delete("$db:interwiki:$prefix");
	}
}
print "\n";
?>