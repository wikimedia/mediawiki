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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to refresh link tables.
 *
 * @ingroup Maintenance
 */
class RefreshLinks extends Maintenance {
	const REPORTING_INTERVAL = 100;

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
		$this->addOption( 'category', 'Only fix pages in this category', false, true );
		$this->addOption( 'tracking-category', 'Only fix pages in this tracking category', false, true );
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
		if ( ( $category = $this->getOption( 'category', false ) ) !== false ) {
			$title = Title::makeTitleSafe( NS_CATEGORY, $category );
			if ( !$title ) {
				$this->fatalError( "'$category' is an invalid category name!\n" );
			}
			$this->refreshCategory( $title );
		} elseif ( ( $category = $this->getOption( 'tracking-category', false ) ) !== false ) {
			$this->refreshTrackingCategory( $category );
		} elseif ( !$this->hasOption( 'dfn-only' ) ) {
			$new = $this->hasOption( 'new-only' );
			$redir = $this->hasOption( 'redirects-only' );
			$oldRedir = $this->hasOption( 'old-redirects-only' );
			$this->doRefreshLinks( $start, $new, $end, $redir, $oldRedir );
			$this->deleteLinksFromNonexistent( null, null, $this->getBatchSize(), $dfnChunkSize );
		} else {
			$this->deleteLinksFromNonexistent( $start, $end, $this->getBatchSize(), $dfnChunkSize );
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
				if ( !( ++$i % self::REPORTING_INTERVAL ) ) {
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
				if ( !( ++$i % self::REPORTING_INTERVAL ) ) {
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
				$maxPage = $dbr->selectField( 'page', 'max(page_id)', '', __METHOD__ );
				$maxRD = $dbr->selectField( 'redirect', 'max(rd_from)', '', __METHOD__ );
				$end = max( $maxPage, $maxRD );
			}
			$this->output( "Refreshing redirects table.\n" );
			$this->output( "Starting from page_id $start of $end.\n" );

			for ( $id = $start; $id <= $end; $id++ ) {
				if ( !( $id % self::REPORTING_INTERVAL ) ) {
					$this->output( "$id\n" );
					wfWaitForSlaves();
				}
				$this->fixRedirect( $id );
			}

			if ( !$redirectsOnly ) {
				$this->output( "Refreshing links tables.\n" );
				$this->output( "Starting from page_id $start of $end.\n" );

				for ( $id = $start; $id <= $end; $id++ ) {
					if ( !( $id % self::REPORTING_INTERVAL ) ) {
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

		MediaWikiServices::getInstance()->getLinkCache()->clear();

		if ( $page === null ) {
			return;
		} elseif ( $ns !== false
			&& !$page->getTitle()->inNamespace( $ns ) ) {
			return;
		}

		// Defer updates to post-send but then immediately execute deferred updates;
		// this is the simplest way to run all updates immediately (including updates
		// scheduled by other updates).
		$page->doSecondaryDataUpdates( [
			'defer' => DeferredUpdates::POSTSEND,
			'recursive' => false,
		] );
		DeferredUpdates::doUpdates();
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
	 * @param IDatabase $db
	 * @param string $var Field name
	 * @param mixed $start First value to include or null
	 * @param mixed $end Last value to include or null
	 * @return string
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

	/**
	 * Refershes links for pages in a tracking category
	 *
	 * @param string $category Category key
	 */
	private function refreshTrackingCategory( $category ) {
		$cats = $this->getPossibleCategories( $category );

		if ( !$cats ) {
			$this->error( "Tracking category '$category' is disabled\n" );
			// Output to stderr but don't bail out,
		}

		foreach ( $cats as $cat ) {
			$this->refreshCategory( $cat );
		}
	}

	/**
	 * Refreshes links to a category
	 *
	 * @param Title $category
	 */
	private function refreshCategory( Title $category ) {
		$this->output( "Refreshing pages in category '{$category->getText()}'...\n" );

		$dbr = $this->getDB( DB_REPLICA );
		$conds = [
			'page_id=cl_from',
			'cl_to' => $category->getDBkey(),
		];
		if ( $this->namespace !== false ) {
			$conds['page_namespace'] = $this->namespace;
		}

		$i = 0;
		$timestamp = '';
		$lastId = 0;
		do {
			$finalConds = $conds;
			$timestamp = $dbr->addQuotes( $timestamp );
			$finalConds [] =
				"(cl_timestamp > $timestamp OR (cl_timestamp = $timestamp AND cl_from > $lastId))";
			$res = $dbr->select( [ 'page', 'categorylinks' ],
				[ 'page_id', 'cl_timestamp' ],
				$finalConds,
				__METHOD__,
				[
					'ORDER BY' => [ 'cl_timestamp', 'cl_from' ],
					'LIMIT' => $this->getBatchSize(),
				]
			);

			foreach ( $res as $row ) {
				if ( !( ++$i % self::REPORTING_INTERVAL ) ) {
					$this->output( "$i\n" );
					wfWaitForSlaves();
				}
				$lastId = $row->page_id;
				$timestamp = $row->cl_timestamp;
				self::fixLinksFromArticle( $row->page_id );
			}

		} while ( $res->numRows() == $this->getBatchSize() );
	}

	/**
	 * Returns a list of possible categories for a given tracking category key
	 *
	 * @param string $categoryKey
	 * @return Title[]
	 */
	private function getPossibleCategories( $categoryKey ) {
		$trackingCategories = new TrackingCategories( $this->getConfig() );
		$cats = $trackingCategories->getTrackingCategories();
		if ( isset( $cats[$categoryKey] ) ) {
			return $cats[$categoryKey]['cats'];
		}
		$this->fatalError( "Unknown tracking category {$categoryKey}\n" );
	}
}

$maintClass = RefreshLinks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
