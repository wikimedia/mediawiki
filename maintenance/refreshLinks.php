<?php
/**
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
 */

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Refresh link tables.
 *
 * @ingroup Maintenance
 */
class RefreshLinks extends Maintenance {
	private const REPORTING_INTERVAL = 100;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Refresh link tables' );
		$this->addOption( 'verbose', 'Output information about link refresh progress', false, false, 'v' );
		$this->addOption( 'dfn-only', 'Delete links from nonexistent articles only' );
		$this->addOption( 'new-only', 'Only affect articles with just a single edit' );
		$this->addOption( 'redirects-only', 'Only fix redirects, not all links' );
		$this->addOption( 'touched-only', 'Only fix pages that have been touched after last update' );
		$this->addOption( 'e', 'Last page id to refresh', false, true );
		$this->addOption( 'dfn-chunk-size', 'Maximum number of existent IDs to check per ' .
			'query, default 100,000', false, true );
		$this->addOption( 'namespace', 'Only fix pages in this namespace', false, true );
		$this->addOption( 'category', 'Only fix pages in this category', false, true );
		$this->addOption( 'tracking-category', 'Only fix pages in this tracking category', false, true );
		$this->addOption( 'before-timestamp', 'Only fix pages that were last updated before this timestamp',
			false, true );
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
		$dfnChunkSize = (int)$this->getOption( 'dfn-chunk-size', 100_000 );

		if ( $this->hasOption( 'dfn-only' ) ) {
			$this->deleteLinksFromNonexistent( $start, $end, $this->getBatchSize(), $dfnChunkSize );
			return;
		}

		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		$builder = $dbr->newSelectQueryBuilder()
			->from( 'page' )
			->where( self::intervalCond( $dbr, 'page_id', $start, $end ) )
			->limit( $this->getBatchSize() );

		if ( $this->hasOption( 'namespace' ) ) {
			$builder->andWhere( [ 'page_namespace' => (int)$this->getOption( 'namespace' ) ] );
		}

		if ( $this->hasOption( 'before-timestamp' ) ) {
			$builder->andWhere(
				$dbr->expr( 'page_links_updated', '<', $this->getOption( 'before-timestamp' ) )
					->or( 'page_links_updated', '=', null )
			);
		}

		if ( $this->hasOption( 'category' ) ) {
			$category = $this->getOption( 'category' );
			$title = Title::makeTitleSafe( NS_CATEGORY, $category );
			if ( !$title ) {
				$this->fatalError( "'$category' is an invalid category name!\n" );
			}
			$this->refreshCategory( $builder, $title );
		} elseif ( $this->hasOption( 'tracking-category' ) ) {
			// See TrackingCategories::CORE_TRACKING_CATEGORIES for tracking category keys defined by core
			$this->refreshTrackingCategory( $builder, $this->getOption( 'tracking-category' ) );
		} else {
			$new = $this->hasOption( 'new-only' );
			$redir = $this->hasOption( 'redirects-only' );
			$touched = $this->hasOption( 'touched-only' );
			$what = $redir ? 'redirects' : 'links';
			if ( $new ) {
				$builder->andWhere( [ 'page_is_new' => 1 ] );
				$this->output( "Refreshing $what from new pages...\n" );
			} else {
				if ( $touched ) {
					$builder->andWhere( [
						$dbr->expr( 'page_links_updated', '=', null )
							->orExpr( new RawSQLExpression( 'page_touched > page_links_updated' ) ),
					] );
				}
				$this->output( "Refreshing $what from pages...\n" );
			}
			$this->doRefreshLinks( $builder, $redir );
			if ( !$this->hasOption( 'namespace' ) ) {
				$this->deleteLinksFromNonexistent( $start, $end, $this->getBatchSize(), $dfnChunkSize );
			}
		}
	}

	/**
	 * Do the actual link refreshing.
	 * @param SelectQueryBuilder $builder
	 * @param bool $redirectsOnly Only fix redirects
	 * @param array $indexFields
	 */
	private function doRefreshLinks(
		SelectQueryBuilder $builder,
		bool $redirectsOnly = false,
		array $indexFields = [ 'page_id' ]
	) {
		// Give extensions a chance to optimize settings
		$this->getHookRunner()->onMaintenanceRefreshLinksInit( $this );

		$estimateCount = $builder->caller( __METHOD__ )->estimateRowCount();
		$this->output( "Estimated page count: $estimateCount\n" );

		$i = 0;
		$lastIndexes = array_fill_keys( $indexFields, 0 );
		$selectFields = in_array( 'page_id', $indexFields )
			? $indexFields : [ 'page_id', ...$indexFields ];
		$verbose = $this->hasOption( 'verbose' );
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		do {
			$batchCond = $dbr->buildComparison( '>', $lastIndexes );
			$res = ( clone $builder )->select( $selectFields )
				->andWhere( [ $batchCond ] )
				->orderBy( $indexFields )
				->caller( __METHOD__ )->fetchResultSet();

			if ( $verbose ) {
				$this->output( "Refreshing links for {$res->numRows()} pages\n" );
			}

			foreach ( $res as $row ) {
				if ( !( ++$i % self::REPORTING_INTERVAL ) ) {
					$this->output( "$i\n" );
					$this->waitForReplication();
				}
				if ( $verbose ) {
					$this->output( "Refreshing links for page ID {$row->page_id}\n" );
				}
				self::fixRedirect( $this, $row->page_id );
				if ( !$redirectsOnly ) {
					self::fixLinksFromArticle( $row->page_id );
				}
			}
			if ( $res->numRows() ) {
				$res->seek( $res->numRows() - 1 );
				foreach ( $indexFields as $field ) {
					$lastIndexes[$field] = $res->current()->$field;
				}
			}

		} while ( $res->numRows() == $this->getBatchSize() );
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
	 * @internal
	 * @param Maintenance $maint
	 * @param int $id The page ID to check
	 */
	public static function fixRedirect( Maintenance $maint, $id ) {
		$page = $maint->getServiceContainer()->getWikiPageFactory()->newFromID( $id );

		// In case the page just got deleted.
		if ( $page === null ) {
			return;
		}

		$rt = null;
		$content = $page->getContent( RevisionRecord::RAW );
		if ( $content !== null ) {
			$rt = $content->getRedirectTarget();
		}

		$dbw = $maint->getDB( DB_PRIMARY );
		if ( $rt === null ) {
			// The page is not a redirect
			// Delete any redirect table entry for it
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'redirect' )
				->where( [ 'rd_from' => $id ] )
				->caller( __METHOD__ )->execute();
			$fieldValue = 0;
		} else {
			$page->insertRedirectEntry( $rt );
			$fieldValue = 1;
		}

		// Update the page table to be sure it is an a consistent state
		$dbw->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [ 'page_is_redirect' => $fieldValue ] )
			->where( [ 'page_id' => $id ] )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Run LinksUpdate for all links on a given page_id
	 * @param int $id The page_id
	 */
	public static function fixLinksFromArticle( $id ) {
		$services = MediaWikiServices::getInstance();
		$page = $services->getWikiPageFactory()->newFromID( $id );

		// In case the page just got deleted.
		if ( $page === null ) {
			return;
		}

		// Defer updates to post-send but then immediately execute deferred updates;
		// this is the simplest way to run all updates immediately (including updates
		// scheduled by other updates).
		$page->doSecondaryDataUpdates( [
			'defer' => DeferredUpdates::POSTSEND,
			'causeAction' => 'refresh-links-maintenance',
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
		$chunkSize = 100_000
	) {
		$this->waitForReplication();
		$this->output( "Deleting illegal entries from the links tables...\n" );
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		do {
			// Find the start of the next chunk. This is based only
			// on existent page_ids.
			$nextStart = $dbr->newSelectQueryBuilder()
				->select( 'page_id' )
				->from( 'page' )
				->where( [ self::intervalCond( $dbr, 'page_id', $start, $end ) ] )
				->orderBy( 'page_id' )
				->offset( $chunkSize )
				->caller( __METHOD__ )->fetchField();

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
		$dbw = $this->getPrimaryDB();
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		$linksTables = [
			// table name => page_id field
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
				$ids = $dbr->newSelectQueryBuilder()
					->select( $field )
					->distinct()
					->from( $table )
					->leftJoin( 'page', null, "$field = page_id" )
					->where( self::intervalCond( $dbr, $field, $tableStart, $end ) )
					->andWhere( [ 'page_id' => null ] )
					->orderBy( $field )
					->limit( $batchSize )
					->caller( __METHOD__ )->fetchFieldValues();

				$numIds = count( $ids );
				if ( $numIds ) {
					$counter += $numIds;
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( $table )
						->where( [ $field => $ids ] )
						->caller( __METHOD__ )->execute();
					$this->output( ", $counter" );
					$tableStart = $ids[$numIds - 1] + 1;
					$this->waitForReplication();
				}

			} while ( $numIds >= $batchSize && ( $end === null || $tableStart <= $end ) );

			$this->output( " deleted.\n" );
		}
	}

	/**
	 * Build a SQL expression for a closed interval.
	 *
	 * By specifying a null $start or $end, it is also possible to create
	 * half-bounded or unbounded intervals using this function.
	 *
	 * @param IReadableDatabase $db
	 * @param string $var Field name
	 * @param mixed $start First value to include or null
	 * @param mixed $end Last value to include or null
	 * @return IExpression
	 */
	private static function intervalCond( IReadableDatabase $db, $var, $start, $end ) {
		if ( $start === null && $end === null ) {
			return $db->expr( $var, '!=', null );
		} elseif ( $end === null ) {
			return $db->expr( $var, '>=', $start );
		} elseif ( $start === null ) {
			return $db->expr( $var, '<=', $end );
		} else {
			return $db->expr( $var, '>=', $start )->and( $var, '<=', $end );
		}
	}

	/**
	 * Refershes links for pages in a tracking category
	 *
	 * @param SelectQueryBuilder $builder
	 * @param string $category Category key
	 */
	private function refreshTrackingCategory( SelectQueryBuilder $builder, $category ) {
		$cats = $this->getPossibleCategories( $category );

		if ( !$cats ) {
			$this->error( "Tracking category '$category' is disabled\n" );
			// Output to stderr but don't bail out.
		}

		foreach ( $cats as $cat ) {
			$this->refreshCategory( clone $builder, $cat );
		}
	}

	/**
	 * Refreshes links to a category
	 *
	 * @param SelectQueryBuilder $builder
	 * @param LinkTarget $category
	 */
	private function refreshCategory( SelectQueryBuilder $builder, LinkTarget $category ) {
		$this->output( "Refreshing pages in category '{$category->getText()}'...\n" );

		$builder->join( 'categorylinks', null, 'page_id=cl_from' )
			->andWhere( [ 'cl_to' => $category->getDBkey() ] );
		$this->doRefreshLinks( $builder, false, [ 'cl_timestamp', 'cl_from' ] );
	}

	/**
	 * Returns a list of possible categories for a given tracking category key
	 *
	 * @param string $categoryKey
	 * @return LinkTarget[]
	 */
	private function getPossibleCategories( $categoryKey ) {
		$cats = $this->getServiceContainer()->getTrackingCategories()->getTrackingCategories();
		if ( isset( $cats[$categoryKey] ) ) {
			return $cats[$categoryKey]['cats'];
		}
		$this->fatalError( "Unknown tracking category {$categoryKey}\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = RefreshLinks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
