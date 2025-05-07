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

namespace MediaWiki\Cache;

use Iterator;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Psr\Log\LoggerInterface;
use RuntimeException;
use stdClass;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
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
 */
class BacklinkCache {
	/**
	 * @internal Used by ServiceWiring.php
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UpdateRowsPerJob,
	];

	/**
	 * Multi-dimensional array representing batches. Keys are:
	 *  > (string) links table name
	 *   > (int) batch size
	 *    > 'numRows' : Number of rows for this link table
	 *    > 'batches' : [ [ $start, $end ] ]
	 *
	 * @see BacklinkCache::partitionResult()
	 * @var array[]
	 */
	private $partitionCache = [];

	/**
	 * Contains the whole links from a database result.
	 * This is raw data that will be partitioned in $partitionCache
	 *
	 * Initialized with BacklinkCache::queryLinks()
	 *
	 * @var IResultWrapper[]
	 */
	private $fullResultCache = [];

	/**
	 * Cache for hasLinks()
	 *
	 * @var bool[]
	 */
	private $hasLinksCache = [];

	/** @var WANObjectCache */
	private $wanCache;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * Local copy of a PageReference object
	 * @var PageReference
	 */
	private $page;

	private const CACHE_EXPIRY = 3600;
	private IConnectionProvider $dbProvider;
	private ServiceOptions $options;
	private LinksMigration $linksMigration;
	private LoggerInterface $logger;

	/**
	 * Create a new BacklinkCache
	 *
	 * @param ServiceOptions $options
	 * @param LinksMigration $linksMigration
	 * @param WANObjectCache $wanCache
	 * @param HookContainer $hookContainer
	 * @param IConnectionProvider $dbProvider
	 * @param LoggerInterface $logger
	 * @param PageReference $page Page to create a backlink cache for
	 */
	public function __construct(
		ServiceOptions $options,
		LinksMigration $linksMigration,
		WANObjectCache $wanCache,
		HookContainer $hookContainer,
		IConnectionProvider $dbProvider,
		LoggerInterface $logger,
		PageReference $page
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->linksMigration = $linksMigration;
		$this->wanCache = $wanCache;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
		$this->page = $page;
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
	 * @return IReadableDatabase
	 */
	private function getDB() {
		return $this->dbProvider->getReplicaDatabase();
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
		$i = 0;
		foreach ( $this->queryLinks( $table, $startId, $endId, $max ) as $row ) {
			yield PageIdentityValue::localIdentity(
				$row->page_id, $row->page_namespace, $row->page_title );

			// queryLinks() may return too many rows
			if ( is_finite( $max ) && ++$i >= $max ) {
				break;
			}
		}
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 *
	 * @param string $table
	 * @param int|bool $startId
	 * @param int|bool $endId
	 * @param int|float $max A hint for the maximum number of rows to return.
	 *   May return more rows if there is a previously cached result set.
	 * @param string $select 'all' or 'ids'
	 * @return IResultWrapper
	 */
	private function queryLinks( $table, $startId, $endId, $max, $select = 'all' ) {
		if ( !$startId && !$endId && isset( $this->fullResultCache[$table] ) ) {
			$this->logger->debug( __METHOD__ . ': got results from cache' );
			return $this->fullResultCache[$table];
		}

		$this->logger->debug( __METHOD__ . ': got results from DB' );
		$queryBuilder = $this->initQueryBuilderForTable( $table, $select );
		$fromField = $this->getPrefix( $table ) . '_from';
		// Use the from field in the condition rather than the joined page_id,
		// because databases are stupid and don't necessarily propagate indexes.
		if ( $startId ) {
			$queryBuilder->where(
				$this->getDB()->expr( $fromField, '>=', $startId )
			);
		}
		if ( $endId ) {
			$queryBuilder->where(
				$this->getDB()->expr( $fromField, '<=', $endId )
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
			$this->logger->debug( __METHOD__ . ": results from DB were uncacheable" );
		}

		return $res;
	}

	/**
	 * Get the field name prefix for a given table
	 * @param string $table
	 * @return null|string
	 */
	private function getPrefix( $table ) {
		static $prefixes = [
			'pagelinks' => 'pl',
			'imagelinks' => 'il',
			'categorylinks' => 'cl',
			'templatelinks' => 'tl',
			'redirect' => 'rd',
			'existencelinks' => 'exl',
		];

		if ( isset( $prefixes[$table] ) ) {
			return $prefixes[$table];
		} else {
			$prefix = null;
			$this->hookRunner->onBacklinkCacheGetPrefix( $table, $prefix );
			if ( $prefix ) {
				return $prefix;
			} else {
				throw new LogicException( "Invalid table \"$table\" in " . __CLASS__ );
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

		/*
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
			case 'templatelinks':
			case 'existencelinks':
				$queryBuilder->where(
					$this->linksMigration->getLinksConditions( $table, TitleValue::newFromPage( $this->page ) )
				);
				break;
			case 'redirect':
				$queryBuilder->where( [
					"{$prefix}_namespace" => $this->page->getNamespace(),
					"{$prefix}_title" => $this->page->getDBkey(),
					"{$prefix}_interwiki" => [ '', null ],
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
					Title::newFromPageReference( $this->page ),
					$conds
				);
				if ( !$conds ) {
					throw new LogicException( "Invalid table \"$table\" in " . __CLASS__ );
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
	 * Check if there are any backlinks. Only use the process cache, since the
	 * WAN cache is potentially stale (T368006).
	 *
	 * @param string $table
	 * @return bool
	 */
	public function hasLinks( $table ) {
		if ( isset( $this->hasLinksCache[$table] ) ) {
			return $this->hasLinksCache[$table];
		}
		if ( isset( $this->partitionCache[$table] ) ) {
			$entry = reset( $this->partitionCache[$table] );
			return (bool)$entry['numRows'];
		}
		if ( isset( $this->fullResultCache[$table] ) ) {
			return (bool)$this->fullResultCache[$table]->numRows();
		}
		$hasLinks = (bool)$this->queryLinks( $table, false, false, 1 )->numRows();
		$this->hasLinksCache[$table] = $hasLinks;
		return $hasLinks;
	}

	/**
	 * Get the approximate number of backlinks
	 * @param string $table
	 * @return int
	 */
	public function getNumLinks( $table ) {
		if ( isset( $this->partitionCache[$table] ) ) {
			$entry = reset( $this->partitionCache[$table] );
			return $entry['numRows'];
		}

		if ( isset( $this->fullResultCache[$table] ) ) {
			return $this->fullResultCache[$table]->numRows();
		}

		return $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey(
				'numbacklinks',
				CacheKeyHelper::getKeyForPage( $this->page ),
				$table
			),
			self::CACHE_EXPIRY,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $table ) {
				$setOpts += Database::getCacheSetOptions( $this->getDB() );

				// Use partition() since it will batch the query and skip the JOIN.
				// Use $wgUpdateRowsPerJob just to encourage cache reuse for jobs.
				$batchSize = $this->options->get( MainConfigNames::UpdateRowsPerJob );
				$this->partition( $table, $batchSize );
				return $this->partitionCache[$table][$batchSize]['numRows'];
			}
		);
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
			$this->logger->debug( __METHOD__ . ": got from partition cache" );

			return $this->partitionCache[$table][$batchSize]['batches'];
		}

		$this->partitionCache[$table][$batchSize] = false;
		$cacheEntry =& $this->partitionCache[$table][$batchSize];

		if ( isset( $this->fullResultCache[$table] ) ) {
			$res = $this->fullResultCache[$table];
			$numRows = $res->numRows();
			$batches = $this->partitionResult( $res, $numRows, $batchSize );
			$this->openBatchEnds( $batches );
			$cacheEntry = [ 'numRows' => $numRows, 'batches' => $batches ];
			$this->logger->debug( __METHOD__ . ": got from full result cache" );

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
				$selectSize = max( $batchSize, 200_000 - ( 200_000 % $batchSize ) );
				$start = false;
				do {
					$res = $this->queryLinks( $table, $start, false, $selectSize, 'ids' );
					$numRows = $res->numRows();
					$batches = $this->partitionResult( $res, $numRows, $batchSize );
					// Merge the link count and range partitions for this chunk
					$value['numRows'] += $numRows;
					$value['batches'] = array_merge( $value['batches'], $batches );
					if ( count( $batches ) ) {
						// pick up after this inclusive range
						$start = end( $batches )[1] + 1;
					}
				} while ( $numRows >= $selectSize );
				// Make sure the first range has start=false and the last one has end=false
				$this->openBatchEnds( $value['batches'] );

				return $value;
			}
		);

		return $cacheEntry['batches'];
	}

	/**
	 * Modify an array of batches, setting the start of the first batch to
	 * false, and the end of the last batch to false, so that the complete
	 * set of batches covers the entire ID range from 0 to infinity.
	 */
	private function openBatchEnds( array &$batches ) {
		if ( !count( $batches ) ) {
			$batches = [ [ false, false ] ];
		} else {
			$batches[0][0] = false;
			$batches[ array_key_last( $batches ) ][1] = false;
		}
	}

	/**
	 * Partition a DB result with backlinks in it into batches
	 * @param IResultWrapper $res Database result
	 * @param int $numRows The number of rows to use from the result set
	 * @param int $batchSize
	 * @return int[][]
	 */
	private function partitionResult( $res, $numRows, $batchSize ) {
		$numBatches = ceil( $numRows / $batchSize );
		$batches = [];
		for ( $i = 0; $i < $numBatches; $i++ ) {
			$rowNum = $i * $batchSize;
			$res->seek( $rowNum );
			$row = $res->fetchObject();
			$start = (int)$row->page_id;

			$rowNum = min( $numRows - 1, ( $i + 1 ) * $batchSize - 1 );
			$res->seek( $rowNum );
			$row = $res->fetchObject();
			$end = (int)$row->page_id;

			// Check order
			if ( $start && $end && $start > $end ) {
				throw new RuntimeException( __METHOD__ . ': Internal error: query result out of order' );
			}

			$batches[] = [ $start, $end ];
		}

		return $batches;
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
	 * Get an array of cascade-protected template/file use backlinks
	 *
	 * @return stdClass[]
	 */
	private function getCascadeProtectedLinksInternal(): array {
		$dbr = $this->getDB();

		// @todo: use UNION without breaking tests that use temp tables
		$resSets = [];
		$linkConds = $this->linksMigration->getLinksConditions(
			'templatelinks', TitleValue::newFromPage( $this->page )
		);
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

/** @deprecated class alias since 1.42 */
class_alias( BacklinkCache::class, 'BacklinkCache' );
