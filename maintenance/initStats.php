<?php

/**
 * Maintenance script to re-initialise or update the site statistics table
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Brion Vibber
 * @author Rob Church <robchur@gmail.com>
 * @licence GNU General Public Licence 2.0 or later
 */
 
$options = array( 'help', 'update', 'noviews' );
require_once( 'commandLine.inc' );
echo( "Refresh Site Statistics\n\n" );
$dbr =& wfGetDB( DB_SLAVE );
$fname = 'initStats';

if( isset( $options['help'] ) ) {
	showHelp();
	exit();
}

echo( "Counting total edits..." );
$edits = $dbr->selectField( 'revision', 'COUNT(*)', '', $fname );
echo( "{$edits}\nCounting number of articles..." );

global $wgContentNamespaces;
$good  = $dbr->selectField( 'page', 'COUNT(*)', array( 'page_namespace' => $wgContentNamespaces, 'page_is_redirect' => 0, 'page_len > 0' ), $fname );
echo( "{$good}\nCounting total pages..." );

$pages = $dbr->selectField( 'page', 'COUNT(*)', '', $fname );
echo( "{$pages}\nCounting number of users..." );

$users = $dbr->selectField( 'user', 'COUNT(*)', '', $fname );
echo( "{$users}\nCounting number of admins..." );

$admin = $dbr->selectField( 'user_groups', 'COUNT(*)', array( 'ug_group' => 'sysop' ), $fname );
echo( "{$admin}\nCounting number of images..." );

$image = $dbr->selectField( 'image', 'COUNT(*)', '', $fname );
echo( "{$image}\n" );

if( !isset( $options['noviews'] ) ) {
	echo( "Counting total page views..." );
	$views = $dbr->selectField( 'page', 'SUM(page_counter)', '', $fname );
	echo( "{$views}\n" );
}

echo( "\nUpdating site statistics..." );

$dbw =& wfGetDB( DB_MASTER );
$values = array( 'ss_total_edits' => $edits,
				'ss_good_articles' => $good,
				'ss_total_pages' => $pages,
				'ss_users' => $users,
				'ss_admins' => $admin,
				'ss_images' => $image );
$conds = array( 'ss_row_id' => 1 );
$views = array( 'ss_total_views' => isset( $views ) ? $views : 0 );
				
if( isset( $options['update'] ) ) {
	$dbw->update( 'site_stats', $values, $conds, $fname );
} else {
	$dbw->delete( 'site_stats', $conds, $fname );
	$dbw->insert( 'site_stats', array_merge( $values, $conds, $views ), $fname );
}

echo( "done.\n\n" );

function showHelp() {
	echo( "Re-initialise the site statistics tables.\n\n" );
	echo( "Usage: php initStats.php [--update|--noviews]\n\n" );
	echo( "	--update : Update the existing statistics (preserves the ss_total_views field)\n" );
	echo( "--noviews : Don't update the page view counter\n\n" );
}

?>