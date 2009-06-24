<?php
/**
 * Build file cache for content pages
 *
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class RebuildFileCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Build file cache for content pages";
		$this->addArgs( array( 'start', 'overwrite' ) );
	}

	public function execute() {
		global $wgUseFileCache, $wgDisableCounters, $wgTitle, $wgArticle, $wgOut;
		if( !$wgUseFileCache ) {
			$this->error( "Nothing to do -- \$wgUseFileCache is disabled.\n", true );
		}
		$wgDisableCounters = false;
		$start = intval( $this->getArg( 0, 0 ) );
		$overwrite = $this->hasArg(1) && $this->getArg(1) === 'overwrite';
		$this->output( "Building content page file cache from page {$start}!\n" );
	
		$dbr = wfGetDB( DB_SLAVE );
		$start = $start > 0 ? $start : $dbr->selectField( 'page', 'MIN(page_id)', false, __FUNCTION__ );
		$end = $dbr->selectField( 'page', 'MAX(page_id)', false, __FUNCTION__ );
		if( !$start ) {
			$this->error( "Nothing to do.\n", true );
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
					$this->output( "Page {$row->page_id} bad title\n" );
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
							$this->output( "Page {$row->page_id} already cached\n" );
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
						$this->output( "Re-cached page {$row->page_id}\n" );
					else
						$this->output( "Cached page {$row->page_id}\n" );
				} else {
					$this->output( "Page {$row->page_id} not cacheable\n" );
				}
				$dbw->commit(); // commit any changes
			}
			$blockStart += $BATCH_SIZE;
			$blockEnd += $BATCH_SIZE;
			wfWaitForSlaves( 5 );
		}
		$this->output( "Done!\n" );
	
		// Remove these to be safe
		if( isset($wgTitle) )
			unset($wgTitle);
		if( isset($wgArticle) )
			unset($wgArticle);
	}
}
require_once( "commandLine.inc" );
