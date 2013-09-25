<?php
/**
 * Refresh link tables.
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
 * Maintenance script to refresh link tables.
 *
 * @ingroup Maintenance
 */
class RefreshLinks extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Refresh link tables";
		$this->addOption( 'dfn-only', 'Delete links from nonexistent articles only' );
		$this->addOption( 'new-only', 'Only affect articles with just a single edit' );
		$this->addOption( 'redirects-only', 'Only fix redirects, not all links' );
		$this->addOption( 'old-redirects-only', 'Only fix redirects with no redirect table entry' );
		$this->addOption( 'm', 'Maximum replication lag', false, true );
		$this->addOption( 'e', 'Last page id to refresh', false, true );
		$this->addArg( 'start', 'Page_id to start from, default 1', false );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$max = $this->getOption( 'm', 0 );
		if ( !$this->hasOption( 'dfn-only' ) ) {
			$start = $this->getArg( 0, 1 );
			$new = $this->getOption( 'new-only', false );
			$end = $this->getOption( 'e', 0 );
			$redir = $this->getOption( 'redirects-only', false );
			$oldRedir = $this->getOption( 'old-redirects-only', false );
			$this->doRefreshLinks( $start, $new, $max, $end, $redir, $oldRedir );
		}
		$this->deleteLinksFromNonexistent( $max, $this->mBatchSize );
	}

	/**
	 * Do the actual link refreshing.
	 * @param $start int Page_id to start from
	 * @param $newOnly bool Only do pages with 1 edit
	 * @param $maxLag int Max DB replication lag
	 * @param $end int Page_id to stop at
	 * @param $redirectsOnly bool Only fix redirects
	 * @param $oldRedirectsOnly bool Only fix redirects without redirect entries
	 */
	private function doRefreshLinks( $start, $newOnly = false, $maxLag = false,
						$end = 0, $redirectsOnly = false, $oldRedirectsOnly = false ) {
		global $wgParser, $wgUseTidy;

		$reportingInterval = 100;
		$dbr = wfGetDB( DB_SLAVE );
		$start = intval( $start );

		// Give extensions a chance to optimize settings
		wfRunHooks( 'MaintenanceRefreshLinksInit', array( $this ) );

		# Don't generate extension images (e.g. Timeline)
		$wgParser->clearTagHooks();

		# Don't use HTML tidy
		$wgUseTidy = false;

		$what = $redirectsOnly ? "redirects" : "links";

		if ( $oldRedirectsOnly ) {
			# This entire code path is cut-and-pasted from below.  Hurrah.

			$conds = array(
				"page_is_redirect=1",
				"rd_from IS NULL"
			);

			if ( $end == 0 ) {
				$conds[] = "page_id >= $start";
			} else {
				$conds[] = "page_id BETWEEN $start AND $end";
			}

			$res = $dbr->select(
				array( 'page', 'redirect' ),
				'page_id',
				$conds,
				__METHOD__,
				array(),
				array( 'redirect' => array( "LEFT JOIN", "page_id=rd_from" ) )
			);
			$num = $res->numRows();
			$this->output( "Refreshing $num old redirects from $start...\n" );

			$i = 0;

			foreach ( $res as $row ) {
				if ( !( ++$i % $reportingInterval ) ) {
					$this->output( "$i\n" );
					wfWaitForSlaves();
				}
				$this->fixRedirect( $row->page_id );
			}
		} elseif ( $newOnly ) {
			$this->output( "Refreshing $what from " );
			$res = $dbr->select( 'page',
				array( 'page_id' ),
				array(
					'page_is_new' => 1,
					"page_id >= $start" ),
				__METHOD__
			);
			$num = $res->numRows();
			$this->output( "$num new articles...\n" );

			$i = 0;
			foreach ( $res as $row ) {
				if ( !( ++$i % $reportingInterval ) ) {
					$this->output( "$i\n" );
					wfWaitForSlaves();
				}
				if ( $redirectsOnly ) {
					$this->fixRedirect( $row->page_id );
				} else {
					self::fixLinksFromArticle( $row->page_id );
				}
			}
		} else {
			if ( !$end ) {
				$maxPage = $dbr->selectField( 'page', 'max(page_id)', false );
				$maxRD = $dbr->selectField( 'redirect', 'max(rd_from)', false );
				$end = max( $maxPage, $maxRD );
			}
			$this->output( "Refreshing redirects table.\n" );
			$this->output( "Starting from page_id $start of $end.\n" );

			for ( $id = $start; $id <= $end; $id++ ) {

				if ( !( $id % $reportingInterval ) ) {
					$this->output( "$id\n" );
					wfWaitForSlaves();
				}
				$this->fixRedirect( $id );
			}

			if ( !$redirectsOnly ) {
				$this->output( "Refreshing links tables.\n" );
				$this->output( "Starting from page_id $start of $end.\n" );

				for ( $id = $start; $id <= $end; $id++ ) {

					if ( !( $id % $reportingInterval ) ) {
						$this->output( "$id\n" );
						wfWaitForSlaves();
					}
					self::fixLinksFromArticle( $id );
				}
			}
		}
	}

	/**
	 * Update the redirect entry for a given page.
	 *
	 * This methods bypasses the "redirect" table to get the redirect target,
	 * and parses the page's content to fetch it. This allows to be sure that
	 * the redirect target is up to date and valid.
	 * This is particularly useful when modifying namespaces to be sure the
	 * entry in the "redirect" table points to the correct page and not to an
	 * invalid one.
	 *
	 * @param $id int The page ID to check
	 */
	private function fixRedirect( $id ) {
		$page = WikiPage::newFromID( $id );
		$dbw = wfGetDB( DB_MASTER );

		if ( $page === null ) {
			// This page doesn't exist (any more)
			// Delete any redirect table entry for it
			$dbw->delete( 'redirect', array( 'rd_from' => $id ),
				__METHOD__ );
			return;
		}

		$rt = null;
		$content = $page->getContent( Revision::RAW );
		if ( $content !== null ) {
			$rt = $content->getUltimateRedirectTarget();
		}

		if ( $rt === null ) {
			// The page is not a redirect
			// Delete any redirect table entry for it
			$dbw->delete( 'redirect', array( 'rd_from' => $id ), __METHOD__ );
			$fieldValue = 0;
		} else {
			$page->insertRedirectEntry( $rt );
			$fieldValue = 1;
		}

		// Update the page table to be sure it is an a consistent state
		$dbw->update( 'page', array( 'page_is_redirect' => $fieldValue ),
			array( 'page_id' => $id ), __METHOD__ );
	}

	/**
	 * Run LinksUpdate for all links on a given page_id
	 * @param $id int The page_id
	 */
	public static function fixLinksFromArticle( $id ) {
		$page = WikiPage::newFromID( $id );

		LinkCache::singleton()->clear();

		if ( $page === null ) {
			return;
		}

		$content = $page->getContent( Revision::RAW );
		if ( $content === null ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin( __METHOD__ );

		$updates = $content->getSecondaryDataUpdates( $page->getTitle() );
		DataUpdate::runUpdates( $updates );

		$dbw->commit( __METHOD__ );
	}

	/**
	 * Removes non-existing links from pages from pagelinks, imagelinks,
	 * categorylinks, templatelinks, externallinks, interwikilinks, langlinks and redirect tables.
	 *
	 * @param $maxLag int
	 * @param $batchSize int The size of deletion batches
	 *
	 * @author Merlijn van Deen <valhallasw@arctus.nl>
	 */
	private function deleteLinksFromNonexistent( $maxLag = 0, $batchSize = 100 ) {
		wfWaitForSlaves();

		$dbw = wfGetDB( DB_MASTER );

		$lb = wfGetLBFactory()->newMainLB();
		$dbr = $lb->getConnection( DB_SLAVE );
		$dbr->bufferResults( false );

		$linksTables = array( // table name => page_id field
			'pagelinks' => 'pl_from',
			'imagelinks' => 'il_from',
			'categorylinks' => 'cl_from',
			'templatelinks' => 'tl_from',
			'externallinks' => 'el_from',
			'iwlinks' => 'iwl_from',
			'langlinks' => 'll_from',
			'redirect' => 'rd_from',
			'page_props' => 'pp_page',
		);

		foreach ( $linksTables as $table => $field ) {
			$this->output( "Retrieving illegal entries from $table... " );

			// SELECT DISTINCT( $field ) FROM $table LEFT JOIN page ON $field=page_id WHERE page_id IS NULL;
			$results = $dbr->select(
				array( $table, 'page' ),
				$field,
				array( 'page_id' => null ),
				__METHOD__,
				'DISTINCT',
				array( 'page' => array( 'LEFT JOIN', "$field=page_id" ) )
			);

			$counter = 0;
			$list = array();
			$this->output( "0.." );
			foreach ( $results as $row ) {
				$counter++;
				$list[] = $row->$field;
				if ( ( $counter % $batchSize ) == 0 ) {
					wfWaitForSlaves();
					$dbw->delete( $table, array( $field => $list ), __METHOD__ );

					$this->output( $counter . ".." );
					$list = array();
				}
			}
			$this->output( $counter );
			if ( count( $list ) > 0 ) {
				$dbw->delete( $table, array( $field => $list ), __METHOD__ );
			}
			$this->output( "\n" );
			wfWaitForSlaves();
		}
		$lb->closeAll();
	}
}

$maintClass = 'RefreshLinks';
require_once RUN_MAINTENANCE_IF_MAIN;
