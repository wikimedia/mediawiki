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
		$this->addOption( 'e', 'Last page id to refresh', false, true );
		$this->addArg( 'start', 'Page_id to start from, default 1', false );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		if ( !$this->hasOption( 'dfn-only' ) ) {
			$start = $this->getArg( 0, 1 );
			$new = $this->getOption( 'new-only', false );
			$end = $this->getOption( 'e', 0 );
			$redir = $this->getOption( 'redirects-only', false );
			$oldRedir = $this->getOption( 'old-redirects-only', false );
			$this->doRefreshLinks( $start, $new, $end, $redir, $oldRedir );
		}
		$this->deleteLinksFromNonexistent( $this->mBatchSize );
	}

	/**
	 * Do the actual link refreshing.
	 * @param int $start Page_id to start from
	 * @param bool $newOnly Only do pages with 1 edit
	 * @param int $end Page_id to stop at
	 * @param bool $redirectsOnly Only fix redirects
	 * @param bool $oldRedirectsOnly Only fix redirects without redirect entries
	 */
	private function doRefreshLinks( $start, $newOnly = false,
		$end = 0, $redirectsOnly = false, $oldRedirectsOnly = false
	) {
		global $wgParser, $wgUseTidy;

		$dbr = wfGetDB( DB_SLAVE );
		$start = (int)$start;
		$end = (int)$end;

		// Give extensions a chance to optimize settings
		wfRunHooks( 'MaintenanceRefreshLinksInit', array( $this ) );

		# Don't generate extension images (e.g. Timeline)
		$wgParser->clearTagHooks();

		# Don't use HTML tidy
		$wgUseTidy = false;

		$newMsg = $newOnly ? ' (NEW PAGES ONLY)' : '';
		if ( $oldRedirectsOnly ) {
			$this->output( "Refreshing old redirects{$newMsg}...\n" );
		} elseif ( $redirectsOnly ) {
			$this->output( "Refreshing redirects table{$newMsg}...\n" );
		} else {
			$this->output( "Refreshing links tables{$newMsg}...\n" );
		}

		if ( !$end ) {
			$end = (int)$dbr->selectField( 'page', 'MAX(page_id)', '', __METHOD__ );
		}

		$conds = array( 'page_id <= ' . $dbr->addQuotes( $end ) );
		if ( $oldRedirectsOnly ) {
			$conds['page_is_redirect'] = 1;
			$conds[] = "page_id NOT IN ({$dbr->selectSQLText( 'redirect', 'rd_from' )})";
		}
		if ( $newOnly ) {
			$conds['page_is_new'] = 1;
		}

		do {
			$res = $dbr->select(
				'page',
				WikiPage::selectFields(),
				array_merge( $conds, array( 'page_id >= ' . $dbr->addQuotes( $start ) ) ),
				__METHOD__,
				array( 'ORDER BY' => 'page_id', 'LIMIT' => $this->mBatchSize )
			);

			$row = null;
			foreach ( $res as $row ) {
				$page = WikiPage::newFromRow( $row );
				$this->fixRedirect( $page );
				if ( !$oldRedirectsOnly && !$redirectsOnly ) {
					self::fixLinksFromArticle( $page );
				}
			}

			if ( $row ) {
				$this->output( "...refreshed page_id(s) from $start to {$row->page_id}.\n" );
				$start = $row->page_id + 1;
				wfWaitForSlaves();
			}

		} while ( $res->numRows() );

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
	 * @param WikiPage $page The page to check
	 */
	private function fixRedirect( WikiPage $page ) {
		$dbw = wfGetDB( DB_MASTER );

		$rt = null;
		$content = $page->getContent( Revision::RAW );
		if ( $content !== null ) {
			$rt = $content->getUltimateRedirectTarget();
		}

		if ( $rt === null ) {
			// The page is not a redirect
			// Delete any redirect table entry for it
			$dbw->delete( 'redirect', array( 'rd_from' => $page->getId() ), __METHOD__ );
			$fieldValue = 0;
		} else {
			$page->insertRedirectEntry( $rt );
			$fieldValue = 1;
		}

		// Update the page table to be sure it is an a consistent state
		$dbw->update( 'page', array( 'page_is_redirect' => $fieldValue ),
			array( 'page_id' => $page->getId() ), __METHOD__ );
	}

	/**
	 * Run LinksUpdate for all links on a given page
	 * @param WikiPage|int $page The page
	 */
	public static function fixLinksFromArticle( $page ) {
		if ( is_numeric( $page ) ) {
			$page = WikiPage::newFromID( $page ); // BC
		}

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
	 * @param int $batchSize The size of deletion batches
	 *
	 * @author Merlijn van Deen <valhallasw@arctus.nl>
	 */
	private function deleteLinksFromNonexistent( $batchSize = 100 ) {
		wfWaitForSlaves();

		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE );

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

			$start = 0;
			$counter = 0;
			$this->output( "0.." );

			do {
				$ids = $dbr->selectFieldValues(
					$table,
					$field,
					array(
						"$field >= {$dbr->addQuotes( $start )}",
						"$field NOT IN ({$dbr->selectSQLText( 'page', 'page_id' )})",
					),
					__METHOD__,
					array( 'DISTINCT', 'ORDER BY' => $field, 'LIMIT' => $batchSize )
				);

				$numIds = count( $ids );
				if ( $numIds ) {
					$counter += $numIds;
					wfWaitForSlaves();
					$dbw->delete( $table, array( $field => $ids ), __METHOD__ );
					$this->output( $counter . ".." );
					$start = $ids[$numIds - 1] + 1;
				}

			} while ( $numIds >= $batchSize );

			$this->output( "\n" );
			wfWaitForSlaves();
		}
	}
}

$maintClass = 'RefreshLinks';
require_once RUN_MAINTENANCE_IF_MAIN;
