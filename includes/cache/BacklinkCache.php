<?php
/**
 * Class for fetching backlink lists, approximate backlink counts and
 * partitions.
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
 * @author Tim Starling
 * @copyright © 2009, Tim Starling, Domas Mituzas
 * @copyright © 2010, Max Sem
 * @copyright © 2011, Antoine Musso
 */

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArray;
use MediaWiki\Title\TitleArrayFromResult;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Class for fetching backlink lists, approximate backlink counts and
 * partitions. This is a shared cache.
 *
 * Instances of this class should typically be fetched with the method
 * ::getBacklinkCache() from the BacklinkCacheFactory service.
 *
 * Ideally you should only get your backlinks from here when you think
 * there is some advantage in caching them. Otherwise, it's just a waste
 * of memory.
 *
 * Introduced by r47317
 */
class BacklinkCache {

	/**
	 * Multi dimensions array representing batches. Keys are:
	 *  > (string) links table name
	 *   > (int) batch size
	 *    > 'numRows' : Number of rows for this link table
	 *    > 'batches' : [ $start, $end ]
	 *
	 * @see BacklinkCache::partitionResult()
	 * @var array[]
	 */
	protected $partitionCache = [];

	/**
	 * Contains the whole links from a database result.
	 * This is raw data that will be partitioned in $partitionCache
	 *
	 * Initialized with BacklinkCache::queryLinks()
	 *
	 * @var IResultWrapper[]
	 */
	protected $fullResultCache = [];

	/** @var WANObjectCache */
	protected $wanCache;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * Local copy of a PageReference object
	 * @var PageReference
	 */
	protected $page;

	private const CACHE_EXPIRY = 3600;

	/**
	 * Create a new BacklinkCache
	 *
	 * @param WANObjectCache $wanCache
	 * @param HookContainer $hookContainer
	 * @param PageReference $page Page to create a backlink cache for
	 */
	public function __construct(
		WANObjectCache $wanCache,
		HookContainer $hookContainer,
		PageReference $page
	) {
		$this->page = $page;
		$this->wanCache = $wanCache;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Create a new BacklinkCache or reuse any existing one.
	 * Currently, only one cache instance can exist; callers that
	 * need multiple backlink cache objects should keep them in scope.
	 *
	 * @deprecated since 1.37 Use BacklinkCacheFactory::getBacklinkCache() instead. Hard deprecated in 1.40.
	 *
	 * @param PageReference $page Page to get a backlink cache for
	 * @return BacklinkCache
	 */
	public static function get( PageReference $page ): self {
		wfDeprecated( __METHOD__, '1.37' );
		$backlinkCacheFactory = MediaWikiServices::getInstance()->getBacklinkCacheFactory();

		return $backlinkCacheFactory->getBacklinkCache( $page );
	}

	/**
	 * @since 1.37
	 * @return PageReference
	 */
	public function getPage(): PageReference {
		return $this->page;
	}

	/**
	 * Get the replica DB connection to the database
	 *
	 * @return IDatabase
	 */
	protected function getDB() {
		return wfGetDB( DB_REPLICA );
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 * @param string $table
	 * @param int|bool $startId
	 * @param int|bool $endId
	 * @param int|float $max Integer, or INF for no max
	 * @return Iterator<PageIdentity>
	 * @since 1.37
	 */
	public function getLinkPages(
		string $table, $startId = false, $endId = false, $max = INF
	): Iterator {
		foreach ( $this->queryLinks( $table, $startId, $endId, $max ) as $row ) {
			yield PageIdentityValue::localIdentity(
				$row->page_id, $row->page_namespace, $row->page_title );
		}
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 *
	 * @deprecated in 1.37, use getLinkPages(). Hard deprecated in 1.40.
	 * @param string $table
	 * @param int|bool $startId
	 * @param int|bool $endId
	 * @param int|float $max Integer, or INF for no max
	 * @return TitleArrayFromResult
	 */
	public function getLinks( $table, $startId = false, $endId = false, $max = INF ) {
		wfDeprecated( __METHOD__, '1.37' );
		return TitleArray::newFromResult( $this->queryLinks( $table, $startId, $endId, $max ) );
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 * @param string $table
	 * @param int|bool $startId
	 * @param int|bool $endId
	 * @param int $max
	 * @param string $select 'all' or 'ids'
	 * @return IResultWrapper
	 */
	protected function queryLinks( $table, $startId, $endId, $max, $select = 'all' ) {
		if ( !$startId && !$endId && is_infinite( $max )
			&& isset( $this->fullResultCache[$table] )
		) {
			wfDebug( __METHOD__ . ": got results from cache" );
			$res = $this->fullResultCache[$table];
		} else {
			wfDebug( __METHOD__ . ": got results from DB" );
			$queryBuilder = $this->initQueryBuilderForTable( $table, $select );
			$fromField = $this->getPrefix( $table ) . '_from';
			// Use the from field in the condition rather than the joined page_id,
			// because databases are stupid and don't necessarily propagate indexes.
			if ( $startId ) {
				$queryBuilder->where(
					$this->getDB()->buildComparison( '>=', [ $fromField => $startId ] )
				);
			}
			if ( $endId ) {
				$queryBuilder->where(
					$this->getDB()->buildComparison( '<=', [ $fromField => $endId ] )
				);
			}
			$queryBuilder->orderBy( $fromField );
			if ( is_finite( $max ) && $max > 0 ) {
				$queryBuilder->limit( $max );
			}

			$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

			if ( $select === 'all' && !$startId && !$endId && $res->numRows() < $max ) {
				// The full results fit within the limit, so cache them
				$this->fullResultCache[$table] = $res;
			} else {
				wfDebug( __METHOD__ . ": results from DB were uncacheable" );
			}
		}

		return $res;
	}

	/**
	 * Get the field name prefix for a given table
	 * @param string $table
	 * @throws MWException
	 * @return null|string
	 */
	protected function getPrefix( $table ) {
		static $prefixes = [
			'pagelinks' => 'pl',
			'imagelinks' => 'il',
			'categorylinks' => 'cl',
			'templatelinks' => 'tl',
			'redirect' => 'rd',
		];

		if ( isset( $prefixes[$table] ) ) {
			return $prefixes[$table];
		} else {
			$prefix = null;
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$this->hookRunner->onBacklinkCacheGetPrefix( $table, $prefix );
			if ( $prefix ) {
				return $prefix;
			} else {
				throw new MWException( "Invalid table \"$table\" in " . __CLASS__ );
			}
		}
	}

	/**
	 * Initialize a new SelectQueryBuilder for selecting backlinks,
	 * with a join on the page table if needed.
	 *
	 * @param string $table
	 * @param string $select
	 * @return SelectQueryBuilder
	 * @throws MWException
	 */
	private function initQueryBuilderForTable( string $table, string $select ): SelectQueryBuilder {
		$prefix = $this->getPrefix( $table );
		$queryBuilder = $this->getDB()->newSelectQueryBuilder();
		$joinPageTable = $select !== 'ids';

		if ( $select === 'ids' ) {
			$queryBuilder->select( [ 'page_id' => $prefix . '_from' ] );
		} else {
			$queryBuilder->select( [ 'page_namespace', 'page_title', 'page_id' ] );
		}
		$queryBuilder->from( $table );

		/**
		 * If the table is one of the tables known to this method,
		 * we can use a nice join() method later, always joining on page_id={$prefix}_from.
		 * If the table is unknown here, and only supported via a hook,
		 * the hook only produces a single $conds array,
		 * so we have to use a traditional / ANSI-89 JOIN,
		 * with the page table just added to the list of tables and the join conds in the WHERE part.
		 */
		$knownTable = true;

		switch ( $table ) {
			case 'pagelinks':
				$queryBuilder->where( [
					"{$prefix}_namespace" => $this->page->getNamespace(),
					"{$prefix}_title" => $this->page->getDBkey(),
				] );
				break;
			case 'templatelinks':
				$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
				$queryBuilder->where(
					$linksMigration->getLinksConditions( $table, TitleValue::newFromPage( $this->page ) ) );
				break;
			case 'redirect':
				$queryBuilder->where( [
					"{$prefix}_namespace" => $this->page->getNamespace(),
					"{$prefix}_title" => $this->page->getDBkey(),
					$this->getDB()->makeList( [
						"{$prefix}_interwiki" => '',
						"{$prefix}_interwiki IS NULL",
					], LIST_OR ),
				] );
				break;
			case 'imagelinks':
			case 'categorylinks':
				$queryBuilder->where( [
					"{$prefix}_to" => $this->page->getDBkey(),
				] );
				break;
			default:
				$knownTable = false;
				$conds = null;
				$this->hookRunner->onBacklinkCacheGetConditions( $table,
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
					Title::castFromPageReference( $this->page ),
					// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
					$conds
				);
				if ( !$conds ) {
					throw new MWException( "Invalid table \"$table\" in " . __CLASS__ );
				}
				if ( $joinPageTable ) {
					$queryBuilder->table( 'page' ); // join condition in $conds
				} else {
					// remove any page_id condition from $conds
					$conds = array_filter( (array)$conds, static function ( $clause ) { // kind of janky
						return !preg_match( '/(\b|=)page_id(\b|=)/', (string)$clause );
					} );
				}
				$queryBuilder->where( $conds );
				break;
		}

		if ( $knownTable && $joinPageTable ) {
			$queryBuilder->join( 'page', null, "page_id={$prefix}_from" );
		}
		if ( $joinPageTable ) {
			$queryBuilder->straightJoinOption();
		}

		return $queryBuilder;
	}

	/**
	 * Check if there are any backlinks
	 * @param string $table
	 * @return bool
	 */
	public function hasLinks( $table ) {
		return ( $this->getNumLinks( $table, 1 ) > 0 );
	}

	/**
	 * Get the approximate number of backlinks
	 * @param string $table
	 * @param int|float $max Only count up to this many backlinks, or INF for no max
	 * @return int
	 */
	public function getNumLinks( $table, $max = INF ) {
		if ( isset( $this->partitionCache[$table] ) ) {
			$entry = reset( $this->partitionCache[$table] );

			return min( $max, $entry['numRows'] );
		}

		if ( isset( $this->fullResultCache[$table] ) ) {
			return min( $max, $this->fullResultCache[$table]->numRows() );
		}

		$count = $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey(
				'numbacklinks',
				CacheKeyHelper::getKeyForPage( $this->page ),
				$table
			),
			self::CACHE_EXPIRY,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $table, $max ) {
				$config = MediaWikiServices::getInstance()->getMainConfig();

				$setOpts += Database::getCacheSetOptions( $this->getDB() );

				if ( is_infinite( $max ) ) {
					// Use partition() since it will batch the query and skip the JOIN.
					// Use $wgUpdateRowsPerJob just to encourage cache reuse for jobs.
					$batchSize = $config->get( MainConfigNames::UpdateRowsPerJob );
					$this->partition( $table, $batchSize );
					$value = $this->partitionCache[$table][$batchSize]['numRows'];
				} else {
					// Fetch the full title info, since the caller will likely need it.
					// Cache the row count if the result set limit made no difference.
					$value = iterator_count( $this->getLinkPages( $table, false, false, $max ) );
					if ( $value >= $max ) {
						$ttl = WANObjectCache::TTL_UNCACHEABLE;
					}
				}

				return $value;
			}
		);

		return min( $max, $count );
	}

	/**
	 * Partition the backlinks into batches.
	 * Returns an array giving the start and end of each range. The first
	 * batch has a start of false, and the last batch has an end of false.
	 *
	 * @param string $table The links table name
	 * @param int $batchSize
	 * @return array
	 */
	public function partition( $table, $batchSize ) {
		if ( isset( $this->partitionCache[$table][$batchSize] ) ) {
			wfDebug( __METHOD__ . ": got from partition cache" );

			return $this->partitionCache[$table][$batchSize]['batches'];
		}

		$this->partitionCache[$table][$batchSize] = false;
		$cacheEntry =& $this->partitionCache[$table][$batchSize];

		if ( isset( $this->fullResultCache[$table] ) ) {
			$cacheEntry = $this->partitionResult( $this->fullResultCache[$table], $batchSize );
			wfDebug( __METHOD__ . ": got from full result cache" );

			return $cacheEntry['batches'];
		}

		$cacheEntry = $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey(
				'backlinks',
				CacheKeyHelper::getKeyForPage( $this->page ),
				$table,
				$batchSize
			),
			self::CACHE_EXPIRY,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $table, $batchSize ) {
				$setOpts += Database::getCacheSetOptions( $this->getDB() );

				$value = [ 'numRows' => 0, 'batches' => [] ];

				// Do the selects in batches to avoid client-side OOMs (T45452).
				// Use a LIMIT that plays well with $batchSize to keep equal sized partitions.
				$selectSize = max( $batchSize, 200000 - ( 200000 % $batchSize ) );
				$start = false;
				do {
					$res = $this->queryLinks( $table, $start, false, $selectSize, 'ids' );
					$partitions = $this->partitionResult( $res, $batchSize, false );
					// Merge the link count and range partitions for this chunk
					$value['numRows'] += $partitions['numRows'];
					$value['batches'] = array_merge( $value['batches'], $partitions['batches'] );
					if ( count( $partitions['batches'] ) ) {
						[ , $lEnd ] = end( $partitions['batches'] );
						$start = $lEnd + 1; // pick up after this inclusive range
					}
				} while ( $partitions['numRows'] >= $selectSize );
				// Make sure the first range has start=false and the last one has end=false
				if ( count( $value['batches'] ) ) {
					$value['batches'][0][0] = false;
					$value['batches'][count( $value['batches'] ) - 1][1] = false;
				}

				return $value;
			}
		);

		return $cacheEntry['batches'];
	}

	/**
	 * Partition a DB result with backlinks in it into batches
	 * @param IResultWrapper $res Database result
	 * @param int $batchSize
	 * @param bool $isComplete Whether $res includes all the backlinks
	 * @throws MWException
	 * @return array
	 */
	protected function partitionResult( $res, $batchSize, $isComplete = true ) {
		$batches = [];
		$numRows = $res->numRows();
		$numBatches = ceil( $numRows / $batchSize );

		for ( $i = 0; $i < $numBatches; $i++ ) {
			if ( $i == 0 && $isComplete ) {
				$start = false;
			} else {
				$rowNum = $i * $batchSize;
				$res->seek( $rowNum );
				$row = $res->fetchObject();
				$start = (int)$row->page_id;
			}

			if ( $i == ( $numBatches - 1 ) && $isComplete ) {
				$end = false;
			} else {
				$rowNum = min( $numRows - 1, ( $i + 1 ) * $batchSize - 1 );
				$res->seek( $rowNum );
				$row = $res->fetchObject();
				$end = (int)$row->page_id;
			}

			# Check order
			if ( $start && $end && $start > $end ) {
				throw new MWException( __METHOD__ . ': Internal error: query result out of order' );
			}

			$batches[] = [ $start, $end ];
		}

		return [ 'numRows' => $numRows, 'batches' => $batches ];
	}

	/**
	 * Get a PageIdentity iterator for cascade-protected template/file use backlinks
	 *
	 * @return Iterator<PageIdentity>
	 * @since 1.37
	 */
	public function getCascadeProtectedLinkPages(): Iterator {
		foreach ( $this->getCascadeProtectedLinksInternal() as $row ) {
			yield PageIdentityValue::localIdentity(
				$row->page_id, $row->page_namespace, $row->page_title );
		}
	}

	/**
	 * Get a Title iterator for cascade-protected template/file use backlinks
	 *
	 * @deprecated since 1.37, use getCascadeProtectedLinkPages(). Hard deprecated in 1.40.
	 * @return TitleArray
	 * @since 1.25
	 */
	public function getCascadeProtectedLinks() {
		wfDeprecated( __METHOD__, '1.37' );
		return TitleArray::newFromResult(
			new FakeResultWrapper( $this->getCascadeProtectedLinksInternal() ) );
	}

	/**
	 * Get an array of cascade-protected template/file use backlinks
	 *
	 * @return stdClass[]
	 */
	private function getCascadeProtectedLinksInternal(): array {
		$dbr = $this->getDB();

		// @todo: use UNION without breaking tests that use temp tables
		$resSets = [];
		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
		$linkConds = $linksMigration->getLinksConditions( 'templatelinks', TitleValue::newFromPage( $this->page ) );
		$resSets[] = $dbr->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title', 'page_id' ] )
			->from( 'templatelinks' )
			->join( 'page_restrictions', null, 'tl_from = pr_page' )
			->join( 'page', null, 'page_id = tl_from' )
			->where( $linkConds )
			->andWhere( [ 'pr_cascade' => 1 ] )
			->distinct()
			->caller( __METHOD__ )->fetchResultSet();
		if ( $this->page->getNamespace() === NS_FILE ) {
			$resSets[] = $dbr->newSelectQueryBuilder()
				->select( [ 'page_namespace', 'page_title', 'page_id' ] )
				->from( 'imagelinks' )
				->join( 'page_restrictions', null, 'il_from = pr_page' )
				->join( 'page', null, 'page_id = il_from' )
				->where( [
					'il_to' => $this->page->getDBkey(),
					'pr_cascade' => 1,
				] )
				->distinct()
				->caller( __METHOD__ )->fetchResultSet();
		}

		// Combine and de-duplicate the results
		$mergedRes = [];
		foreach ( $resSets as $res ) {
			foreach ( $res as $row ) {
				// Index by page_id to remove duplicates
				$mergedRes[$row->page_id] = $row;
			}
		}

		// Now that we've de-duplicated, throw away the keys
		return array_values( $mergedRes );
	}
}
