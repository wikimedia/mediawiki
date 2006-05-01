<?php

/**
 * Maintenance script to update the site_stats.ss_images field
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 * @licence GNU General Public Licence 2.0 or later
 */

require_once( 'commandLine.inc' );

function recountImages() {
	$dbw =& wfGetDB( DB_MASTER );
	$count = $dbw->selectField( 'images', 'COUNT(*)', '', 'recountImages' );
	# Replication safe update; set to NULL first to force the change to slaves
	$dbw->update( 'site_stats', array( 'ss_images' => NULL ), array( 'ss_row_id' => 1 ), 'recountImages' );
	$dbw->update( 'site_stats', array( 'ss_images' => $count ), array( 'ss_row_id' => 1 ), 'recountImages' );
	return $count;
}

echo( "Updating image count in site statistics..." );
recountImages();
echo( "set to {$count}.\n\n" );

?>