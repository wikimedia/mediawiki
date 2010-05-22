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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class RebuildFileCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Build file cache for content pages";
		$this->addArg( 'start', 'Page_id to start from', true );
		$this->addArg( 'overwrite', 'Refresh page cache', false );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgUseFileCache, $wgDisableCounters, $wgContentNamespaces, $wgRequestTime;
		global $wgTitle, $wgArticle, $wgOut, $wgUser;
		if ( !$wgUseFileCache ) {
			$this->error( "Nothing to do -- \$wgUseFileCache is disabled.", true );
		}
		$wgDisableCounters = false;
		$start = $this->getArg( 0, "0" );
		if ( !ctype_digit( $start ) ) {
			$this->error( "Invalid value for start parameter.", true );
		}
		$start = intval( $start );
		$overwrite = $this->hasArg( 1 ) && $this->getArg( 1 ) === 'overwrite';
		$this->output( "Building content page file cache from page {$start}!\n" );

		$dbr = wfGetDB( DB_SLAVE );
		$start = $start > 0 ? $start : $dbr->selectField( 'page', 'MIN(page_id)', false, __FUNCTION__ );
		$end = $dbr->selectField( 'page', 'MAX(page_id)', false, __FUNCTION__ );
		if ( !$start ) {
			$this->error( "Nothing to do.", true );
		}

		$_SERVER['HTTP_ACCEPT_ENCODING'] = 'bgzip'; // hack, no real client
		OutputPage::setEncodings(); # Not really used yet

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
			foreach ( $res as $row ) {
				$rebuilt = false;
				$wgRequestTime = wfTime(); # bug 22852
				$wgTitle = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( null == $wgTitle ) {
					$this->output( "Page {$row->page_id} has bad title\n" );
					continue; // broken title?
				}
				$wgOut->setTitle( $wgTitle ); // set display title
				$wgUser->getSkin( $wgTitle ); // set skin title
				$wgArticle = new Article( $wgTitle );
				// If the article is cacheable, then load it
				if ( $wgArticle->isFileCacheable() ) {
					$cache = new HTMLFileCache( $wgTitle );
					if ( $cache->isFileCacheGood() ) {
						if ( $overwrite ) {
							$rebuilt = true;
						} else {
							$this->output( "Page {$row->page_id} already cached\n" );
							continue; // done already!
						}
					}
					ob_start( array( &$cache, 'saveToFileCache' ) ); // save on ob_end_clean()
					$wgUseFileCache = false; // hack, we don't want $wgArticle fiddling with filecache
					$wgArticle->view();
					@$wgOut->output(); // header notices
					$wgUseFileCache = true;
					ob_end_clean(); // clear buffer
					$wgOut = new OutputPage(); // empty out any output page garbage
					if ( $rebuilt )
						$this->output( "Re-cached page {$row->page_id}\n" );
					else
						$this->output( "Cached page {$row->page_id}\n" );
				} else {
					$this->output( "Page {$row->page_id} not cacheable\n" );
				}
				$dbw->commit(); // commit any changes
			}
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves( 5 );
		}
		$this->output( "Done!\n" );
	
		// Remove these to be safe
		if ( isset( $wgTitle ) )
			unset( $wgTitle );
		if ( isset( $wgArticle ) )
			unset( $wgArticle );
	}
}

$maintClass = "RebuildFileCache";
require_once( DO_MAINTENANCE );
