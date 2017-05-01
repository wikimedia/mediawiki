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
	/** @var int|bool */
	protected $namespace = false;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Refresh link tables' );
		$this->addOption( 'dfn-only', 'Delete links from nonexistent articles only' );
		$this->addOption( 'new-only', 'Only affect articles with just a single edit' );
		$this->addOption( 'redirects-only', 'Only fix redirects, not all links' );
		$this->addOption( 'old-redirects-only', 'Only fix redirects with no redirect table entry' );
		$this->addOption( 'e', 'Last page id to refresh', false, true );
		$this->addOption( 'dfn-chunk-size', 'Maximum number of existent IDs to check per ' .
			'query, default 100000', false, true );
		$this->addOption( 'namespace', 'Only fix pages in this namespace', false, true );
		$this->addArg( 'start', 'Page_id to start from, default 1', false );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		// Note that there is a difference between not specifying the start
		// and end IDs and using the minimum and maximum values from the page
		// table. In the latter case, deleteLinksFromNonexistent() will not
		// delete entries for nonexistent IDs that fall outside the range.
		$start = (int)$this->getArg( 0 ) ?: null;
		$end = (int)$this->getOption( 'e' ) ?: null;
		$dfnChunkSize = (int)$this->getOption( 'dfn-chunk-size', 100000 );
		$ns = $this->getOption( 'namespace' );
		if ( $ns === null ) {
			$this->namespace = false;
		} else {
			$this->namespace = (int)$ns;
		}
		if ( !$this->hasOption( 'dfn-only' ) ) {
			$new = $this->getOption( 'new-only', false );
			$redir = $this->getOption( 'redirects-only', false );
			$oldRedir = $this->getOption( 'old-redirects-only', false );
			$this->doRefreshLinks( $start, $new, $end, $redir, $oldRedir );
			$this->deleteLinksFromNonexistent( null, null, $this->mBatchSize, $dfnChunkSize );
		} else {
			$this->deleteLinksFromNonexistent( $start, $end, $this->mBatchSize, $dfnChunkSize );
		}
	}

	private function namespaceCond() {
		return $this->namespace !== false
			? [ 'page_namespace' => $this->namespace ]
			: [];
	}

	/**
	 * Do the actual link refreshing.
	 * @param int|null $start Page_id to start from
	 * @param bool $newOnly Only do pages with 1 edit
	 * @param int|null $end Page_id to stop at
	 * @param bool $redirectsOnly Only fix redirects
	 * @param bool $oldRedirectsOnly Only fix redirects without redirect entries
	 */
	private function doRefreshLinks( $start, $newOnly = false,
		$end = null, $redirectsOnly = false, $oldRedirectsOnly = false
	) {
		$reportingInterval = 100;
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		if ( $start === null ) {
			$start = 1;
		}

		// Give extensions a chance to optimize settings
		Hooks::run( 'MaintenanceRefreshLinksInit', [ $this ] );

		$what = $redirectsOnly ? "redirects" : "links";

		if ( $oldRedirectsOnly ) {
			# This entire code path is cut-and-pasted from below.  Hurrah.

			$conds = [
				"page_is_redirect=1",
				"rd_from IS NULL",
				self::intervalCond( $dbr, 'page_id', $start, $end ),
			] + $this->namespaceCond();

			$res = $dbr->select(
				[ 'page', 'redirect' ],
				'page_id',
				$conds,
				__METHOD__,
				[],
				[ 'redirect' => [ "LEFT JOIN", "page_id=rd_from" ] ]
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
				[ 'page_id' ],
				[
					'page_is_new' => 1,
					self::intervalCond( $dbr, 'page_id', $start, $end ),
				] + $this->namespaceCond(),
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
					self::fixLinksFromArticle( $row->page_id, $this->namespace );
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
					self::fixLinksFromArticle( $id, $this->namespace );
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
	 * @param int $id The page ID to check
	 */
	private function fixRedirect( $id ) {
		$page = WikiPage::newFromID( $id );
		$dbw = $this->getDB( DB_MASTER );

		if ( $page === null ) {
			// This page doesn't exist (any more)
			// Delete any redirect table entry for it
			$dbw->delete( 'redirect', [ 'rd_from' => $id ],
				__METHOD__ );

			return;
		} elseif ( $this->namespace !== false
			&& !$page->getTitle()->inNamespace( $this->namespace )
		) {
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
			$dbw->delete( 'redirect', [ 'rd_from' => $id ], __METHOD__ );
			$fieldValue = 0;
		} else {
			$page->insertRedirectEntry( $rt );
			$fieldValue = 1;
		}

		// Update the page table to be sure it is an a consistent state
		$dbw->update( 'page', [ 'page_is_redirect' => $fieldValue ],
			[ 'page_id' => $id ], __METHOD__ );
	}

	/**
	 * Run LinksUpdate for all links on a given page_id
	 * @param int $id The page_id
	 * @param int|bool $ns Only fix links if it is in this namespace
	 */
	public static function fixLinksFromArticle( $id, $ns = false ) {
		$page = WikiPage::newFromID( $id );

		LinkCache::singleton()->clear();

		if ( $page === null ) {
			return;
		} elseif ( $ns !== false
			&& !$page->getTitle()->inNamespace( $ns ) ) {
			return;
		}

		$content = $page->getContent( Revision::RAW );
		if ( $content === null ) {
			return;
		}

		foreach ( $content->getSecondaryDataUpdates( $page->getTitle() ) as $update ) {
			DeferredUpdates::addUpdate( $update );
		}
	}

	/**
	 * Removes non-existing links from pages from pagelinks, imagelinks,
	 * categorylinks, templatelinks, externallinks, interwikilinks, langlinks and redirect tables.
	 *
	 * @param int|null $start Page_id to start from
	 * @param int|null $end Page_id to stop at
	 * @param int $batchSize The size of deletion batches
	 * @param int $chunkSize Maximum number of existent IDs to check per query
	 *
	 * @author Merlijn van Deen <valhallasw@arctus.nl>
	 */
	private function deleteLinksFromNonexistent( $start = null, $end = null, $batchSize = 100,
		$chunkSize = 100000
	) {
		wfWaitForSlaves();
		$this->output( "Deleting illegal entries from the links tables...\n" );
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		do {
			// Find the start of the next chunk. This is based only
			// on existent page_ids.
			$nextStart = $dbr->selectField(
				'page',
				'page_id',
				[ self::intervalCond( $dbr, 'page_id', $start, $end ) ]
				+ $this->namespaceCond(),
				__METHOD__,
				[ 'ORDER BY' => 'page_id', 'OFFSET' => $chunkSize ]
			);

			if ( $nextStart !== false ) {
				// To find the end of the current chunk, subtract one.
				// This will serve to limit the number of rows scanned in
				// dfnCheckInterval(), per query, to at most the sum of
				// the chunk size and deletion batch size.
				$chunkEnd = $nextStart - 1;
			} else {
				// This is the last chunk. Check all page_ids up to $end.
				$chunkEnd = $end;
			}

			$fmtStart = $start !== null ? "[$start" : '(-INF';
			$fmtChunkEnd = $chunkEnd !== null ? "$chunkEnd]" : 'INF)';
			$this->output( "  Checking interval $fmtStart, $fmtChunkEnd\n" );
			$this->dfnCheckInterval( $start, $chunkEnd, $batchSize );

			$start = $nextStart;

		} while ( $nextStart !== false );
	}

	/**
	 * @see RefreshLinks::deleteLinksFromNonexistent()
	 * @param int|null $start Page_id to start from
	 * @param int|null $end Page_id to stop at
	 * @param int $batchSize The size of deletion batches
	 */
	private function dfnCheckInterval( $start = null, $end = null, $batchSize = 100 ) {
		$dbw = $this->getDB( DB_MASTER );
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		$linksTables = [ // table name => page_id field
			'pagelinks' => 'pl_from',
			'imagelinks' => 'il_from',
			'categorylinks' => 'cl_from',
			'templatelinks' => 'tl_from',
			'externallinks' => 'el_from',
			'iwlinks' => 'iwl_from',
			'langlinks' => 'll_from',
			'redirect' => 'rd_from',
			'page_props' => 'pp_page',
		];

		foreach ( $linksTables as $table => $field ) {
			$this->output( "    $table: 0" );
			$tableStart = $start;
			$counter = 0;
			do {
				$ids = $dbr->selectFieldValues(
					$table,
					$field,
					[
						self::intervalCond( $dbr, $field, $tableStart, $end ),
						"$field NOT IN ({$dbr->selectSQLText( 'page', 'page_id' )})",
					],
					__METHOD__,
					[ 'DISTINCT', 'ORDER BY' => $field, 'LIMIT' => $batchSize ]
				);

				$numIds = count( $ids );
				if ( $numIds ) {
					$counter += $numIds;
					$dbw->delete( $table, [ $field => $ids ], __METHOD__ );
					$this->output( ", $counter" );
					$tableStart = $ids[$numIds - 1] + 1;
					wfWaitForSlaves();
				}

			} while ( $numIds >= $batchSize && ( $end === null || $tableStart <= $end ) );

			$this->output( " deleted.\n" );
		}
	}

	/**
	 * Build a SQL expression for a closed interval (i.e. BETWEEN).
	 *
	 * By specifying a null $start or $end, it is also possible to create
	 * half-bounded or unbounded intervals using this function.
	 *
	 * @param IDatabase $db Database connection
	 * @param string $var Field name
	 * @param mixed $start First value to include or null
	 * @param mixed $end Last value to include or null
	 */
	private static function intervalCond( IDatabase $db, $var, $start, $end ) {
		if ( $start === null && $end === null ) {
			return "$var IS NOT NULL";
		} elseif ( $end === null ) {
			return "$var >= {$db->addQuotes( $start )}";
		} elseif ( $start === null ) {
			return "$var <= {$db->addQuotes( $end )}";
		} else {
			return "$var BETWEEN {$db->addQuotes( $start )} AND {$db->addQuotes( $end )}";
		}
	}
}

$maintClass = 'RefreshLinks';
require_once RUN_MAINTENANCE_IF_MAIN;
