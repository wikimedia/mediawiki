<?php
require_once('commandLine.inc');

foreach ( $wgLocalDatabases as $db ) {
	print "$db ";
	foreach ( $wgLanguageNamesEn as $prefix => $name ) {
		$wgMemc->delete("$db:interwiki:$prefix");
	}
}
print "\n";
?>
