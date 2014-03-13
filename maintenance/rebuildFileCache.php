<?php
/**
 * Build file cache for content pages
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that builds file cache for content pages.
 *
 * @ingroup Maintenance
 */
class RebuildFileCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Build file cache for content pages";
		$this->addOption( 'start', 'Page_id to start from', false, true );
		$this->addOption( 'end', 'Page_id to end on', false, true );
		$this->addOption( 'overwrite', 'Refresh page cache' );
		$this->setBatchSize( 100 );
	}

	public function finalSetup() {
		global $wgDebugToolbar;

		// Debug toolbar makes content uncacheable so we disable it.
		// Has to be done before Setup.php initialize MWDebug
		$wgDebugToolbar = false;
		parent::finalSetup();
	}

	public function execute() {
		global $wgUseFileCache, $wgReadOnly, $wgContentNamespaces, $wgRequestTime;
		global $wgOut;
		if ( !$wgUseFileCache ) {
			$this->error( "Nothing to do -- \$wgUseFileCache is disabled.", true );
		}

		$wgReadOnly = 'Building cache'; // avoid DB writes (like enotif/counters)

		$start = $this->getOption( 'start', "0" );
		if ( !ctype_digit( $start ) ) {
			$this->error( "Invalid value for start parameter.", true );
		}
		$start = intval( $start );

		$end = $this->getOption( 'end', "0" );
		if ( !ctype_digit( $end ) ) {
			$this->error( "Invalid value for end parameter.", true );
		}
		$end = intval( $end );

		$this->output( "Building content page file cache from page {$start}!\n" );

		$dbr = wfGetDB( DB_SLAVE );
		$overwrite = $this->getOption( 'overwrite', false );
		$start = ( $start > 0 )
			? $start
			: $dbr->selectField( 'page', 'MIN(page_id)', false, __FUNCTION__ );
		$end = ( $end > 0 )
			? $end
			: $dbr->selectField( 'page', 'MAX(page_id)', false, __FUNCTION__ );
		if ( !$start ) {
			$this->error( "Nothing to do.", true );
		}

		$_SERVER['HTTP_ACCEPT_ENCODING'] = 'bgzip'; // hack, no real client

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;

		$dbw = wfGetDB( DB_MASTER );
		// Go through each page and save the output
		while ( $blockEnd <= $end ) {
			// Get the pages
			$res = $dbr->select( 'page', array( 'page_namespace', 'page_title', 'page_id' ),
				array( 'page_namespace' => $wgContentNamespaces,
					"page_id BETWEEN $blockStart AND $blockEnd" ),
				array( 'ORDER BY' => 'page_id ASC', 'USE INDEX' => 'PRIMARY' )
			);

			$dbw->begin( __METHOD__ ); // for any changes
			foreach ( $res as $row ) {
				$rebuilt = false;
				$wgRequestTime = microtime( true ); # bug 22852

				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( null == $title ) {
					$this->output( "Page {$row->page_id} has bad title\n" );
					continue; // broken title?
				}

				$context = new RequestContext;
				$context->setTitle( $title );
				$article = Article::newFromTitle( $title, $context );
				$context->setWikiPage( $article->getPage() );

				$wgOut = $context->getOutput(); // set display title

				// If the article is cacheable, then load it
				if ( $article->isFileCacheable() ) {
					$cache = HTMLFileCache::newFromTitle( $title, 'view' );
					if ( $cache->isCacheGood() ) {
						if ( $overwrite ) {
							$rebuilt = true;
						} else {
							$this->output( "Page {$row->page_id} already cached\n" );
							continue; // done already!
						}
					}
					ob_start( array( &$cache, 'saveToFileCache' ) ); // save on ob_end_clean()
					$wgUseFileCache = false; // hack, we don't want $article fiddling with filecache
					$article->view();
					wfSuppressWarnings(); // header notices
					$wgOut->output();
					wfRestoreWarnings();
					$wgUseFileCache = true;
					ob_end_clean(); // clear buffer
					if ( $rebuilt ) {
						$this->output( "Re-cached page {$row->page_id}\n" );
					} else {
						$this->output( "Cached page {$row->page_id}\n" );
					}
				} else {
					$this->output( "Page {$row->page_id} not cacheable\n" );
				}
			}
			$dbw->commit( __METHOD__ ); // commit any changes (just for sanity)

			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
		}
		$this->output( "Done!\n" );
	}
}

$maintClass = "RebuildFileCache";
require_once RUN_MAINTENANCE_IF_MAIN;
