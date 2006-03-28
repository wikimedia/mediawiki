<?php

// Quickie hack; patch-ss_images.sql uses variables which don't
// replicate properly.

require_once( "commandLine.inc" );

$dbw =& wfGetDB( DB_MASTER );
$count = $dbw->selectField( 'image', 'COUNT(*)' );

echo "$wgDBname: setting ss_images to $count\n";
$dbw->update( 'site_stats',
	array( 'ss_images' => $count ),
	array( 'ss_row_id' => 1 ) );

?>