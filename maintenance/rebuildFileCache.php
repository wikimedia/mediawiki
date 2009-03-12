<?php
/**
 * Build file cache for content pages
 *
 * @file
 * @ingroup Maintenance
 */

/** */
require_once( "commandLine.inc" );
if( !$wgUseFileCache ) {
	echo "Nothing to do -- \$wgUseFileCache is disabled.\n";
	exit(0);
}
$wgDisableCounters = false; // no real hits here

$start = isset($args[0]) ? intval($args[0]) : 0;
$overwrite = isset( $args[1] ) && $args[1] === 'overwrite';
echo "Building content page file cache from page {$start}!\n";
echo "Format: <start> [overwrite]\n";

$dbr = wfGetDB( DB_SLAVE );
$start = $start > 0 ? $start : $dbr->selectField( 'page', 'MIN(page_id)', false, __FUNCTION__ );
$end = $dbr->selectField( 'page', 'MAX(page_id)', false, __FUNCTION__ );
if( !$start ) {
	die("Nothing to do.\n");
}

$_SERVER['HTTP_ACCEPT_ENCODING'] = 'bgzip'; // hack, no real client
OutputPage::setEncodings(); # Not really used yet

$BATCH_SIZE = 100;
# Do remaining chunk
$end += $BATCH_SIZE - 1;
$blockStart = $start;
$blockEnd = $start + $BATCH_SIZE - 1;

$dbw = wfGetDB( DB_MASTER );
// Go through each page and save the output
while( $blockEnd <= $end ) {
	// Get the pages
	$res = $dbr->select( 'page', array('page_namespace','page_title','page_id'),
		array('page_namespace' => $wgContentNamespaces,
			"page_id BETWEEN $blockStart AND $blockEnd" ),
		array('ORDER BY' => 'page_id ASC','USE INDEX' => 'PRIMARY')
	);
	while( $row = $dbr->fetchObject( $res ) ) {
		$rebuilt = false;
		$wgTitle = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
		if( null == $wgTitle ) {
			echo "Page {$row->page_id} bad title\n";
			continue; // broken title?
		}
		$wgArticle = new Article( $wgTitle );
		// If the article is cacheable, then load it
		if( $wgArticle->isFileCacheable() ) {
			$cache = new HTMLFileCache( $wgTitle );
			if( $cache->isFileCacheGood() ) {
				if( $overwrite ) {
					$rebuilt = true;
				} else {
					echo "Page {$row->page_id} already cached\n";
					continue; // done already!
				}
			}
			ob_start( array(&$cache, 'saveToFileCache' ) ); // save on ob_end_clean()
			$wgUseFileCache = false; // hack, we don't want $wgArticle fiddling with filecache
			$wgArticle->view();
			@$wgOut->output(); // header notices
			$wgUseFileCache = true;
			ob_end_clean(); // clear buffer
			$wgOut = new OutputPage(); // empty out any output page garbage
			if( $rebuilt )
				echo "Re-cached page {$row->page_id}\n";
			else
				echo "Cached page {$row->page_id}\n";
		} else {
			echo "Page {$row->page_id} not cacheable\n";
		}
		$dbw->commit(); // commit any changes
	}
	$blockStart += $BATCH_SIZE;
	$blockEnd += $BATCH_SIZE;
	wfWaitForSlaves( 5 );
}
echo "Done!\n";

// Remove these to be safe
if( isset($wgTitle) )
	unset($wgTitle);
if( isset($wgArticle) )
	unset($wgArticle);
