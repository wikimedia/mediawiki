<?php
/**
 * Base code for "query" special pages.
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
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use Exception;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Skin;
use MediaWiki\Specials\SpecialAncientPages;
use MediaWiki\Specials\SpecialBrokenRedirects;
use MediaWiki\Specials\SpecialDeadendPages;
use MediaWiki\Specials\SpecialDoubleRedirects;
use MediaWiki\Specials\SpecialFewestRevisions;
use MediaWiki\Specials\SpecialLinkSearch;
use MediaWiki\Specials\SpecialListDuplicatedFiles;
use MediaWiki\Specials\SpecialListRedirects;
use MediaWiki\Specials\SpecialLonelyPages;
use MediaWiki\Specials\SpecialLongPages;
use MediaWiki\Specials\SpecialMediaStatistics;
use MediaWiki\Specials\SpecialMIMESearch;
use MediaWiki\Specials\SpecialMostCategories;
use MediaWiki\Specials\SpecialMostImages;
use MediaWiki\Specials\SpecialMostInterwikis;
use MediaWiki\Specials\SpecialMostLinked;
use MediaWiki\Specials\SpecialMostLinkedCategories;
use MediaWiki\Specials\SpecialMostLinkedTemplates;
use MediaWiki\Specials\SpecialMostRevisions;
use MediaWiki\Specials\SpecialShortPages;
use MediaWiki\Specials\SpecialUncategorizedCategories;
use MediaWiki\Specials\SpecialUncategorizedImages;
use MediaWiki\Specials\SpecialUncategorizedPages;
use MediaWiki\Specials\SpecialUncategorizedTemplates;
use MediaWiki\Specials\SpecialUnusedCategories;
use MediaWiki\Specials\SpecialUnusedImages;
use MediaWiki\Specials\SpecialUnusedTemplates;
use MediaWiki\Specials\SpecialUnwatchedPages;
use MediaWiki\Specials\SpecialWantedCategories;
use MediaWiki\Specials\SpecialWantedFiles;
use MediaWiki\Specials\SpecialWantedPages;
use MediaWiki\Specials\SpecialWantedTemplates;
use MediaWiki\Specials\SpecialWithoutInterwiki;
use stdClass;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This is a class for doing query pages; since they're almost all the same,
 * we factor out some of the functionality into a superclass, and let
 * subclasses derive from it.
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
abstract class QueryPage extends SpecialPage {
	/** @var bool Whether or not we want plain listoutput rather than an ordered list */
	protected $listoutput = false;

	/** @var int The offset and limit in use, as passed to the query() function */
	protected $offset = 0;

	/** @var int */
	protected $limit = 0;

	/**
	 * The number of rows returned by the query. Reading this variable
	 * only makes sense in functions that are run after the query has been
	 * done, such as preprocessResults() and formatRow().
	 *
	 * @var int
	 */
	protected $numRows;

	/**
	 * @var string|null|false
	 */
	protected $cachedTimestamp = null;

	/**
	 * @var bool Whether to show prev/next links
	 */
	protected $shownavigation = true;

	/** @var ILoadBalancer|null */
	private $loadBalancer = null;

	/** @var IConnectionProvider|null */
	private $databaseProvider = null;

	/** @var LinkBatchFactory|null */
	private $linkBatchFactory = null;

	/**
	 * Get a list of query page classes and their associated special pages,
	 * for periodic updates.
	 *
	 * When changing this list, you should ensure that maintenance/updateSpecialPages.php still works
	 * including when test data exists.
	 *
	 * @return array[] List of [ string $class, string $specialPageName, ?int $limit (optional) ].
	 *  Limit defaults to $wgQueryCacheLimit if not given.
	 */
	public static function getPages() {
		static $qp = null;

		if ( $qp === null ) {
			$qp = [
				[ SpecialAncientPages::class, 'Ancientpages' ],
				[ SpecialBrokenRedirects::class, 'BrokenRedirects' ],
				[ SpecialDeadendPages::class, 'Deadendpages' ],
				[ SpecialDoubleRedirects::class, 'DoubleRedirects' ],
				[ SpecialListDuplicatedFiles::class, 'ListDuplicatedFiles' ],
				[ SpecialLinkSearch::class, 'LinkSearch' ],
				[ SpecialListRedirects::class, 'Listredirects' ],
				[ SpecialLonelyPages::class, 'Lonelypages' ],
				[ SpecialLongPages::class, 'Longpages' ],
				[ SpecialMediaStatistics::class, 'MediaStatistics', SpecialMediaStatistics::MAX_LIMIT ],
				[ SpecialMIMESearch::class, 'MIMEsearch' ],
				[ SpecialMostCategories::class, 'Mostcategories' ],
				[ SpecialMostImages::class, 'Mostimages' ],
				[ SpecialMostInterwikis::class, 'Mostinterwikis' ],
				[ SpecialMostLinkedCategories::class, 'Mostlinkedcategories' ],
				[ SpecialMostLinkedTemplates::class, 'Mostlinkedtemplates' ],
				[ SpecialMostLinked::class, 'Mostlinked' ],
				[ SpecialMostRevisions::class, 'Mostrevisions' ],
				[ SpecialFewestRevisions::class, 'Fewestrevisions' ],
				[ SpecialShortPages::class, 'Shortpages' ],
				[ SpecialUncategorizedCategories::class, 'Uncategorizedcategories' ],
				[ SpecialUncategorizedPages::class, 'Uncategorizedpages' ],
				[ SpecialUncategorizedImages::class, 'Uncategorizedimages' ],
				[ SpecialUncategorizedTemplates::class, 'Uncategorizedtemplates' ],
				[ SpecialUnusedCategories::class, 'Unusedcategories' ],
				[ SpecialUnusedImages::class, 'Unusedimages' ],
				[ SpecialWantedCategories::class, 'Wantedcategories' ],
				[ SpecialWantedFiles::class, 'Wantedfiles' ],
				[ SpecialWantedPages::class, 'Wantedpages' ],
				[ SpecialWantedTemplates::class, 'Wantedtemplates' ],
				[ SpecialUnwatchedPages::class, 'Unwatchedpages' ],
				[ SpecialUnusedTemplates::class, 'Unusedtemplates' ],
				[ SpecialWithoutInterwiki::class, 'Withoutinterwiki' ],
			];
			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )->onWgQueryPages( $qp );
		}

		return $qp;
	}

	/**
	 * @since 1.36
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	final protected function setLinkBatchFactory( LinkBatchFactory $linkBatchFactory ) {
		$this->linkBatchFactory = $linkBatchFactory;
	}

	/**
	 * @since 1.36
	 * @return LinkBatchFactory
	 */
	final protected function getLinkBatchFactory(): LinkBatchFactory {
		if ( $this->linkBatchFactory === null ) {
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			$this->linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		}
		return $this->linkBatchFactory;
	}

	/**
	 * Get a list of disabled query pages and their run mode
	 * @param Config $config
	 * @return string[]
	 */
	public static function getDisabledQueryPages( Config $config ) {
		$disableQueryPageUpdate = $config->get( MainConfigNames::DisableQueryPageUpdate );

		if ( !is_array( $disableQueryPageUpdate ) ) {
			return [];
		}

		$pages = [];
		foreach ( $disableQueryPageUpdate as $name => $runMode ) {
			if ( is_int( $name ) ) {
				// The run mode may be omitted
				$pages[$runMode] = 'disabled';
			} else {
				$pages[$name] = $runMode;
			}
		}
		return $pages;
	}

	/**
	 * A mutator for $this->listoutput;
	 *
	 * @param bool $bool
	 */
	protected function setListoutput( $bool ) {
		$this->listoutput = $bool;
	}

	/**
	 * Subclasses return an SQL query here, formatted as an array with the
	 * following keys:
	 *    tables => Table(s) for passing to Database::select()
	 *    fields => Field(s) for passing to Database::select(), may be *
	 *    conds => WHERE conditions
	 *    options => options
	 *    join_conds => JOIN conditions
	 *
	 * Note that the query itself should return the following three columns:
	 * 'namespace', 'title', and 'value'. 'value' is used for sorting.
	 *
	 * These may be stored in the querycache table for expensive queries,
	 * and that cached data will be returned sometimes, so the presence of
	 * extra fields can't be relied upon. The cached 'value' column will be
	 * an integer; non-numeric values are useful only for sorting the
	 * initial query (except if they're timestamps, see usesTimestamps()).
	 *
	 * Don't include an ORDER or LIMIT clause, they will be added.
	 *
	 * @return array
	 * @since 1.18, abstract since 1.43
	 */
	abstract public function getQueryInfo();

	/**
	 * Subclasses return an array of fields to order by here. Don't append
	 * DESC to the field names, that'll be done automatically if
	 * sortDescending() returns true.
	 * @stable to override
	 * @return string[]
	 * @since 1.18
	 */
	protected function getOrderFields() {
		return [ 'value' ];
	}

	/**
	 * Does this query return timestamps rather than integers in its
	 * 'value' field? If true, this class will convert 'value' to a
	 * UNIX timestamp for caching.
	 * NOTE: formatRow() may get timestamps in TS_MW (mysql), TS_DB (pgsql)
	 *       or TS_UNIX (querycache) format, so be sure to always run them
	 *       through wfTimestamp()
	 * @stable to override
	 * @return bool
	 * @since 1.18
	 */
	public function usesTimestamps() {
		return false;
	}

	/**
	 * Override to sort by increasing values
	 *
	 * @stable to override
	 * @return bool
	 */
	protected function sortDescending() {
		return true;
	}

	/**
	 * Should this query page only be updated offline on large wikis?
	 *
	 * If the query for this page is considered too expensive to run on-demand
	 * for large wikis (e.g. every time the special page is viewed), then
	 * implement this as returning true.
	 *
	 * "Large wikis" are those that enable $wgMiserMode. The value of
	 * ::isExpensive() has no effect by default when $wgMiserMode is off.
	 *
	 * It is expected that such large wikis, periodically run the
	 * UpdateSpecialPages maintenance script to update these query pages.
	 *
	 * By enabling $wgDisableQueryPages, all query pages will be considered
	 * as expensive and updated offline only, even query pages that do not
	 * mark themselves as expensive.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isExpensive() {
		return $this->getConfig()->get( MainConfigNames::DisableQueryPages );
	}

	/**
	 * Is the output of this query cacheable? Non-cacheable expensive pages
	 * will be disabled in miser mode and will not have their results written
	 * to the querycache table.
	 * @stable to override
	 * @return bool
	 * @since 1.18
	 */
	public function isCacheable() {
		return true;
	}

	/**
	 * Whether or not the output of the page in question is retrieved from
	 * the database cache.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isCached() {
		return $this->isExpensive() && $this->getConfig()->get( MainConfigNames::MiserMode );
	}

	/**
	 * Sometimes we don't want to build rss / atom feeds.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isSyndicated() {
		return true;
	}

	/**
	 * Formats the results of the query for display. The skin is the current
	 * skin; you can use it for making links. The result is a single row of
	 * result data. You should be able to grab SQL results from it.
	 * If the function returns false, the line output will be skipped.
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string|bool String or false to skip
	 */
	abstract protected function formatResult( $skin, $result );

	/**
	 * The content returned by this function will be output before any result
	 *
	 * @stable to override
	 * @return string
	 */
	protected function getPageHeader() {
		return '';
	}

	/**
	 * Outputs some kind of an informative message (via OutputPage) to let the
	 * user know that the query returned nothing and thus there's nothing to
	 * show.
	 *
	 * @since 1.26
	 */
	protected function showEmptyText() {
		$this->getOutput()->addWikiMsg( 'specialpage-empty' );
	}

	/**
	 * If using extra form wheely-dealies, return a set of parameters here
	 * as an associative array. They will be encoded and added to the paging
	 * links (prev/next/lengths).
	 *
	 * @stable to override
	 * @return array
	 */
	protected function linkParameters() {
		return [];
	}

	/**
	 * Clear the cache and save new results
	 *
	 * @stable to override
	 *
	 * @param int|false $limit Limit for SQL statement or false for no limit
	 * @param bool $unused Unused since 1.43, kept for backwards-compatibility
	 * @throws DBError|Exception
	 * @return bool|int
	 */
	public function recache( $limit, $unused = true ) {
		if ( !$this->isCacheable() ) {
			return 0;
		}

		$fname = static::class . '::recache';
		$dbw = $this->getDatabaseProvider()->getPrimaryDatabase();

		// Do query
		$res = $this->reallyDoQuery( $limit, false );
		$num = false;
		if ( $res ) {
			$num = $res->numRows();
			// Fetch results
			$vals = [];
			foreach ( $res as $i => $row ) {
				if ( isset( $row->value ) ) {
					if ( $this->usesTimestamps() ) {
						$value = (int)wfTimestamp( TS_UNIX, $row->value );
					} else {
						$value = intval( $row->value ); // T16414
					}
				} else {
					$value = $i;
				}

				$vals[] = [
					'qc_type' => $this->getName(),
					'qc_namespace' => $row->namespace,
					'qc_title' => $row->title,
					'qc_value' => $value
				];
			}

			$dbw->doAtomicSection(
				$fname,
				function ( IDatabase $dbw, $fname ) {
					// Clear out any old cached data
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'querycache' )
						->where( [ 'qc_type' => $this->getName() ] )
						->caller( $fname )->execute();
					// Update the querycache_info record for the page
					$dbw->newInsertQueryBuilder()
						->insertInto( 'querycache_info' )
						->row( [ 'qci_type' => $this->getName(), 'qci_timestamp' => $dbw->timestamp() ] )
						->onDuplicateKeyUpdate()
						->uniqueIndexFields( [ 'qci_type' ] )
						->set( [ 'qci_timestamp' => $dbw->timestamp() ] )
						->caller( $fname )->execute();
				}
			);
			// Save results into the querycache table on the primary DB
			if ( count( $vals ) ) {
				foreach ( array_chunk( $vals, 500 ) as $chunk ) {
					$dbw->newInsertQueryBuilder()
						->insertInto( 'querycache' )
						->rows( $chunk )
						->caller( $fname )->execute();
				}
			}
		}

		return $num;
	}

	/**
	 * Get a DB connection to be used for slow recache queries
	 * @stable to override
	 * @return IReadableDatabase
	 */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase( false, 'vslow' );
	}

	/**
	 * Remove a cached result.
	 * Useful for interactive backlogs where the user can fix problems in-place.
	 * @param LinkTarget $title The page to remove.
	 * @since 1.34
	 */
	public function delete( LinkTarget $title ) {
		if ( $this->isCached() ) {
			$dbw = $this->getDatabaseProvider()->getPrimaryDatabase();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'querycache' )
				->where( [
					'qc_type' => $this->getName(),
					'qc_namespace' => $title->getNamespace(),
					'qc_title' => $title->getDBkey(),
				] )
				->caller( __METHOD__ )->execute();
		}
	}

	/**
	 * Remove all cached value
	 * This is needed when the page is no longer using the cache
	 * @since 1.36
	 */
	public function deleteAllCachedData() {
		$fname = static::class . '::' . __FUNCTION__;
		$dbw = $this->getDatabaseProvider()->getPrimaryDatabase();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'querycache' )
			->where( [ 'qc_type' => $this->getName() ] )
			->caller( $fname )->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'querycachetwo' )
			->where( [ 'qcc_type' => $this->getName() ] )
			->caller( $fname )->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'querycache_info' )
			->where( [ 'qci_type' => $this->getName() ] )
			->caller( $fname )->execute();
	}

	/**
	 * Run the query and return the result
	 * @stable to override
	 * @param int|false $limit Numerical limit or false for no limit
	 * @param int|false $offset Numerical offset or false for no offset
	 * @return IResultWrapper
	 * @since 1.18
	 */
	public function reallyDoQuery( $limit, $offset = false ) {
		$fname = static::class . '::reallyDoQuery';
		$dbr = $this->getRecacheDB();
		$query = $this->getQueryInfo();
		$order = $this->getOrderFields();

		if ( $this->sortDescending() ) {
			foreach ( $order as &$field ) {
				$field .= ' DESC';
			}
		}

		$tables = isset( $query['tables'] ) ? (array)$query['tables'] : [];
		$fields = isset( $query['fields'] ) ? (array)$query['fields'] : [];
		$conds = isset( $query['conds'] ) ? (array)$query['conds'] : [];
		$options = isset( $query['options'] ) ? (array)$query['options'] : [];
		$join_conds = isset( $query['join_conds'] ) ? (array)$query['join_conds'] : [];

		$queryBuilder = $dbr->newSelectQueryBuilder()
			->tables( $tables )
			->fields( $fields )
			->conds( $conds )
			->caller( $fname )
			->options( $options )
			->joinConds( $join_conds );
		if ( $order ) {
			$queryBuilder->orderBy( $order );
		}

		if ( $limit !== false ) {
			$queryBuilder->limit( intval( $limit ) );
		}

		if ( $offset !== false ) {
			$queryBuilder->offset( intval( $offset ) );
		}

		return $queryBuilder->fetchResultSet();
	}

	/**
	 * Somewhat deprecated, you probably want to be using execute()
	 * @param int|false $offset
	 * @param int|false $limit
	 * @return IResultWrapper
	 */
	public function doQuery( $offset = false, $limit = false ) {
		if ( $this->isCached() && $this->isCacheable() ) {
			return $this->fetchFromCache( $limit, $offset );
		} else {
			return $this->reallyDoQuery( $limit, $offset );
		}
	}

	/**
	 * Fetch the query results from the query cache
	 * @stable to override
	 *
	 * @param int|false $limit Numerical limit or false for no limit
	 * @param int|false $offset Numerical offset or false for no offset
	 * @return IResultWrapper
	 * @since 1.18
	 */
	public function fetchFromCache( $limit, $offset = false ) {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [ 'qc_type', 'namespace' => 'qc_namespace', 'title' => 'qc_title', 'value' => 'qc_value' ] )
			->from( 'querycache' )
			->where( [ 'qc_type' => $this->getName() ] );

		if ( $limit !== false ) {
			$queryBuilder->limit( intval( $limit ) );
		}

		if ( $offset !== false ) {
			$queryBuilder->offset( intval( $offset ) );
		}

		$order = $this->getCacheOrderFields();
		if ( $this->sortDescending() ) {
			$queryBuilder->orderBy( $order, SelectQueryBuilder::SORT_DESC );
		} else {
			$queryBuilder->orderBy( $order );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/**
	 * Return the order fields for fetchFromCache. Default is to always use
	 * "ORDER BY value" which was the default prior to this function.
	 * @stable to override
	 * @return array
	 * @since 1.29
	 */
	protected function getCacheOrderFields() {
		return [ 'value' ];
	}

	/**
	 * @return string|false
	 */
	public function getCachedTimestamp() {
		if ( $this->cachedTimestamp === null ) {
			$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
			$fname = static::class . '::getCachedTimestamp';
			$this->cachedTimestamp = $dbr->newSelectQueryBuilder()
				->select( 'qci_timestamp' )
				->from( 'querycache_info' )
				->where( [ 'qci_type' => $this->getName() ] )
				->caller( $fname )->fetchField();
		}
		return $this->cachedTimestamp;
	}

	/**
	 * Returns limit and offset, as returned by $this->getRequest()->getLimitOffsetForUser().
	 * Subclasses may override this to further restrict or modify limit and offset.
	 *
	 * @note Restricts the offset parameter, as most query pages have inefficient paging
	 *
	 * Its generally expected that the returned limit will not be 0, and the returned
	 * offset will be less than the max results.
	 *
	 * @since 1.26
	 * @return int[] list( $limit, $offset )
	 */
	protected function getLimitOffset() {
		[ $limit, $offset ] = $this->getRequest()
			->getLimitOffsetForUser( $this->getUser() );
		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$maxResults = $this->getMaxResults();
			// Can't display more than max results on a page
			$limit = min( $limit, $maxResults );
			// Can't skip over more than the end of $maxResults
			$offset = min( $offset, $maxResults + 1 );
		}
		return [ $limit, $offset ];
	}

	/**
	 * What is limit to fetch from DB
	 *
	 * Used to make it appear the DB stores less results then it actually does
	 * @param int $uiLimit Limit from UI
	 * @param int $uiOffset Offset from UI
	 * @return int Limit to use for DB (not including extra row to see if at end)
	 */
	protected function getDBLimit( $uiLimit, $uiOffset ) {
		$maxResults = $this->getMaxResults();
		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$limit = min( $uiLimit + 1, $maxResults - $uiOffset );
			return max( $limit, 0 );
		} else {
			return $uiLimit + 1;
		}
	}

	/**
	 * Get max number of results we can return in miser mode.
	 *
	 * Most QueryPage subclasses use inefficient paging, so limit the max amount we return
	 * This matters for uncached query pages that might otherwise accept an offset of 3 million
	 *
	 * @stable to override
	 * @since 1.27
	 * @return int
	 */
	protected function getMaxResults() {
		// Max of 10000, unless we store more than 10000 in query cache.
		return max( $this->getConfig()->get( MainConfigNames::QueryCacheLimit ), 10000 );
	}

	/**
	 * This is the actual workhorse. It does everything needed to make a
	 * real, honest-to-gosh query page.
	 * @stable to override
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->checkPermissions();

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();

		if ( $this->isCached() && !$this->isCacheable() ) {
			$out->addWikiMsg( 'querypage-disabled' );
			return;
		}

		$out->setSyndicated( $this->isSyndicated() );

		if ( $this->limit == 0 && $this->offset == 0 ) {
			[ $this->limit, $this->offset ] = $this->getLimitOffset();
		}
		$dbLimit = $this->getDBLimit( $this->limit, $this->offset );
		// @todo Use doQuery()
		if ( !$this->isCached() ) {
			// select one extra row for navigation
			$res = $this->reallyDoQuery( $dbLimit, $this->offset );
		} else {
			// Get the cached result, select one extra row for navigation
			$res = $this->fetchFromCache( $dbLimit, $this->offset );
			if ( !$this->listoutput ) {
				// Fetch the timestamp of this update
				$ts = $this->getCachedTimestamp();
				$lang = $this->getLanguage();
				$maxResults = $lang->formatNum( $this->getConfig()->get(
					MainConfigNames::QueryCacheLimit ) );

				if ( $ts ) {
					$user = $this->getUser();
					$updated = $lang->userTimeAndDate( $ts, $user );
					$updateddate = $lang->userDate( $ts, $user );
					$updatedtime = $lang->userTime( $ts, $user );
					$out->addMeta( 'Data-Cache-Time', $ts );
					$out->addJsConfigVars( 'dataCacheTime', $ts );
					$out->addWikiMsg( 'perfcachedts', $updated, $updateddate, $updatedtime, $maxResults );
				} else {
					$out->addWikiMsg( 'perfcached', $maxResults );
				}

				// If updates on this page have been disabled, let the user know
				// that the data set won't be refreshed for now
				$disabledQueryPages = self::getDisabledQueryPages( $this->getConfig() );
				if ( isset( $disabledQueryPages[$this->getName()] ) ) {
					$runMode = $disabledQueryPages[$this->getName()];
					if ( $runMode === 'disabled' ) {
						$out->wrapWikiMsg(
							"<div class=\"mw-querypage-no-updates\">\n$1\n</div>",
							'querypage-no-updates'
						);
					} else {
						// Messages used here: querypage-updates-periodical
						$out->wrapWikiMsg(
							"<div class=\"mw-querypage-updates-" . $runMode . "\">\n$1\n</div>",
							'querypage-updates-' . $runMode
						);
					}
				}
			}
		}

		$this->numRows = $res->numRows();

		$dbr = $this->getRecacheDB();
		$this->preprocessResults( $dbr, $res );

		$out->addHTML( Html::openElement( 'div', [ 'class' => 'mw-spcontent' ] ) );

		// Top header and navigation
		if ( $this->shownavigation ) {
			$out->addHTML( $this->getPageHeader() );
			if ( $this->numRows > 0 ) {
				$out->addHTML( $this->msg( 'showingresultsinrange' )->numParams(
					min( $this->numRows, $this->limit ), // do not show the one extra row, if exist
					$this->offset + 1, ( min( $this->numRows, $this->limit ) + $this->offset ) )->parseAsBlock() );
				// Disable the "next" link when we reach the end
				$miserMaxResults = $this->getConfig()->get( MainConfigNames::MiserMode )
					&& ( $this->offset + $this->limit >= $this->getMaxResults() );
				$atEnd = ( $this->numRows <= $this->limit ) || $miserMaxResults;
				$paging = $this->buildPrevNextNavigation( $this->offset,
					$this->limit, $this->linkParameters(), $atEnd, $par );
				$out->addHTML( '<p>' . $paging . '</p>' );
			} else {
				// No results to show, so don't bother with "showing X of Y" etc.
				// -- just let the user know and give up now
				$this->showEmptyText();
				$out->addHTML( Html::closeElement( 'div' ) );
				return;
			}
		}

		// The actual results; specialist subclasses will want to handle this
		// with more than a straight list, so we hand them the info, plus
		// an OutputPage, and let them get on with it
		$this->outputResults( $out,
			$this->getSkin(),
			$dbr, // Should use IResultWrapper for this
			$res,
			min( $this->numRows, $this->limit ), // do not format the one extra row, if exist
			$this->offset );

		// Repeat the paging links at the bottom
		if ( $this->shownavigation ) {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable paging is set when used here
			$out->addHTML( '<p>' . $paging . '</p>' );
		}

		$out->addHTML( Html::closeElement( 'div' ) );
	}

	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @stable to override
	 *
	 * @param OutputPage $out OutputPage to print to
	 * @param Skin $skin User skin to use
	 * @param IReadableDatabase $dbr Database (read) connection to use
	 * @param IResultWrapper $res Result pointer
	 * @param int $num Number of available result rows
	 * @param int $offset Paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		if ( $num > 0 ) {
			$html = [];
			if ( !$this->listoutput ) {
				$html[] = $this->openList( $offset );
			}

			// $res might contain the whole 1,000 rows, so we read up to
			// $num [should update this to use a Pager]
			// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.Found
			for ( $i = 0; $i < $num && $row = $res->fetchObject(); $i++ ) {
				$line = $this->formatResult( $skin, $row );
				if ( $line ) {
					$html[] = $this->listoutput
						? $line
						: "<li>{$line}</li>\n";
				}
			}

			if ( !$this->listoutput ) {
				$html[] = $this->closeList();
			}

			$html = $this->listoutput
				? $this->getContentLanguage()->listToText( $html )
				: implode( '', $html );

			$out->addHTML( $html );
		}
	}

	/**
	 * @param int $offset
	 * @return string
	 */
	protected function openList( $offset ) {
		return "\n<ol start='" . ( $offset + 1 ) . "' class='special'>\n";
	}

	/**
	 * @return string
	 */
	protected function closeList() {
		return "</ol>\n";
	}

	/**
	 * Do any necessary preprocessing of the result object.
	 * @stable to override
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	protected function preprocessResults( $db, $res ) {
	}

	/**
	 * Creates a new LinkBatch object, adds all pages from the passed result wrapper (MUST include
	 * title and optional the namespace field) and executes the batch. This operation will pre-cache
	 * LinkCache information like page existence and information for stub color and redirect hints.
	 *
	 * @note Call self::setLinkBatchFactory from special page constructor when use
	 *
	 * @param IResultWrapper $res The result wrapper to process. Needs to include the title
	 *  field and namespace field, if the $ns parameter isn't set.
	 * @param int|null $ns Use this namespace for the given titles in the result wrapper,
	 *  instead of the namespace value of $res.
	 */
	protected function executeLBFromResultWrapper( IResultWrapper $res, $ns = null ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = $this->getLinkBatchFactory()->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $res as $row ) {
			$batch->add( $ns ?? (int)$row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	/**
	 * @since 1.36
	 * @deprecated since 1.43, use self::setDatabaseProvider
	 * @param ILoadBalancer $loadBalancer
	 */
	final protected function setDBLoadBalancer( ILoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @since 1.36
	 * @deprecated since 1.43, use self::getDatabaseProvider
	 * @return ILoadBalancer
	 */
	final protected function getDBLoadBalancer(): ILoadBalancer {
		if ( $this->loadBalancer === null ) {
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			$this->loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		}
		return $this->loadBalancer;
	}

	/**
	 * @since 1.41
	 * @param IConnectionProvider $databaseProvider
	 */
	final protected function setDatabaseProvider( IConnectionProvider $databaseProvider ) {
		$this->databaseProvider = $databaseProvider;
	}

	/**
	 * @since 1.41
	 * @return IConnectionProvider
	 */
	final protected function getDatabaseProvider(): IConnectionProvider {
		if ( $this->databaseProvider === null ) {
			$this->databaseProvider = MediaWikiServices::getInstance()->getConnectionProvider();
		}
		return $this->databaseProvider;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( QueryPage::class, 'QueryPage' );
