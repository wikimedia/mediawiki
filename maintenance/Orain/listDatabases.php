<?php
require_once( __DIR__ .'/../commandLine.inc' );

foreach ($wgLocalDatabases as $db) {
print "$db\n";
}
