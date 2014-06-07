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
 * @author Aaron Schulz
 * @copyright © 2009, Tim Starling, Domas Mituzas
 * @copyright © 2010, Max Sem
 * @copyright © 2011, Antoine Musso
 */

/**
 * Class for fetching backlink lists, approximate backlink counts and
 * partitions. This is a shared cache.
 *
 * Instances of this class should typically be fetched with the method
 * $title->getBacklinkCache().
 *
 * Ideally you should only get your backlinks from here when you think
 * there is some advantage in caching them. Otherwise it's just a waste
 * of memory.
 *
 * Introduced by r47317
 *
 * @internal documentation reviewed on 18 Mar 2011 by hashar
 */
class BacklinkCache {
	/** @var ProcessCacheLRU */
	protected static $cache;

	/**
	 * Multi dimensions array representing batches. Keys are:
	 *  > (string) links table name
	 *   > (int) batch size
	 *    > 'numRows' : Number of rows for this link table
	 *    > 'batches' : array( $start, $end )
	 *
	 * @see BacklinkCache::partitionResult()
	 *
	 * Cleared with BacklinkCache::clear()
	 */
	protected $partitionCache = array();

	/**
	 * Contains the whole links from a database result.
	 * This is raw data that will be partitioned in $partitionCache
	 *
	 * Initialized with BacklinkCache::getLinks()
	 * Cleared with BacklinkCache::clear()
	 */
	protected $fullResultCache = array();

	/**
	 * Local copy of a database object.
	 *
	 * Accessor: BacklinkCache::getDB()
	 * Mutator : BacklinkCache::setDB()
	 * Cleared with BacklinkCache::clear()
	 */
	protected $db;

	/**
	 * Local copy of a Title object
	 */
	protected $title;

	const CACHE_EXPIRY = 3600;

	/**
	 * Create a new BacklinkCache
	 *
	 * @param Title $title : Title object to create a backlink cache for
	 */
	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * Create a new BacklinkCache or reuse any existing one.
	 * Currently, only one cache instance can exist; callers that
	 * need multiple backlink cache objects should keep them in scope.
	 *
	 * @param Title $title : Title object to get a backlink cache for
	 * @return BacklinkCache
	 */
	public static function get( Title $title ) {
		if ( !self::$cache ) { // init cache
			self::$cache = new ProcessCacheLRU( 1 );
		}
		$dbKey = $title->getPrefixedDBkey();
		if ( !self::$cache->has( $dbKey, 'obj', 3600 ) ) {
			self::$cache->set( $dbKey, 'obj', new self( $title ) );
		}

		return self::$cache->get( $dbKey, 'obj' );
	}

	/**
	 * Serialization handler, diasallows to serialize the database to prevent
	 * failures after this class is deserialized from cache with dead DB
	 * connection.
	 *
	 * @return array
	 */
	function __sleep() {
		return array( 'partitionCache', 'fullResultCache', 'title' );
	}

	/**
	 * Clear locally stored data and database object.
	 */
	public function clear() {
		$this->partitionCache = array();
		$this->fullResultCache = array();
		unset( $this->db );
	}

	/**
	 * Set the Database object to use
	 *
	 * @param $db DatabaseBase
	 */
	public function setDB( $db ) {
		$this->db = $db;
	}

	/**
	 * Get the slave connection to the database
	 * When non existing, will initialize the connection.
	 * @return DatabaseBase object
	 */
	protected function getDB() {
		if ( !isset( $this->db ) ) {
			$this->db = wfGetDB( DB_SLAVE );
		}

		return $this->db;
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 * @param string $table
	 * @param int|bool $startId
	 * @param int|bool $endId
	 * @param int|INF $max
	 * @return TitleArrayFromResult
	 */
	public function getLinks( $table, $startId = false, $endId = false, $max = INF ) {
		return TitleArray::newFromResult( $this->queryLinks( $table, $startId, $endId, $max ) );
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 * @param $table String
	 * @param $startId Integer|false
	 * @param $endId Integer|false
	 * @param $max Integer|INF
	 * @param $select string 'all' or 'ids'
	 * @return ResultWrapper
	 */
	protected function queryLinks( $table, $startId, $endId, $max, $select = 'all' ) {
		wfProfileIn( __METHOD__ );

		$fromField = $this->getPrefix( $table ) . '_from';

		if ( !$startId && !$endId && is_infinite( $max )
			&& isset( $this->fullResultCache[$table] )
		) {
			wfDebug( __METHOD__ . ": got results from cache\n" );
			$res = $this->fullResultCache[$table];
		} else {
			wfDebug( __METHOD__ . ": got results from DB\n" );
			$conds = $this->getConditions( $table );
			// Use the from field in the condition rather than the joined page_id,
			// because databases are stupid and don't necessarily propagate indexes.
			if ( $startId ) {
				$conds[] = "$fromField >= " . intval( $startId );
			}
			if ( $endId ) {
				$conds[] = "$fromField <= " . intval( $endId );
			}
			$options = array( 'ORDER BY' => $fromField );
			if ( is_finite( $max ) && $max > 0 ) {
				$options['LIMIT'] = $max;
			}

			if ( $select === 'ids' ) {
				// Just select from the backlink table and ignore the page JOIN
				$res = $this->getDB()->select(
					$table,
					array( $this->getPrefix( $table ) . '_from AS page_id' ),
					array_filter( $conds, function ( $clause ) { // kind of janky
						return !preg_match( '/(\b|=)page_id(\b|=)/', $clause );
					} ),
					__METHOD__,
					$options
				);
			} else {
				// Select from the backlink table and JOIN with page title information
				$res = $this->getDB()->select(
					array( $table, 'page' ),
					array( 'page_namespace', 'page_title', 'page_id' ),
					$conds,
					__METHOD__,
					array_merge( array( 'STRAIGHT_JOIN' ), $options )
				);
			}

			if ( $select === 'all' && !$startId && !$endId && $res->numRows() < $max ) {
				// The full results fit within the limit, so cache them
				$this->fullResultCache[$table] = $res;
			} else {
				wfDebug( __METHOD__ . ": results from DB were uncacheable\n" );
			}
		}

		wfProfileOut( __METHOD__ );

		return $res;
	}

	/**
	 * Get the field name prefix for a given table
	 * @param $table String
	 * @throws MWException
	 * @return null|string
	 */
	protected function getPrefix( $table ) {
		static $prefixes = array(
			'pagelinks' => 'pl',
			'imagelinks' => 'il',
			'categorylinks' => 'cl',
			'templatelinks' => 'tl',
			'redirect' => 'rd',
		);

		if ( isset( $prefixes[$table] ) ) {
			return $prefixes[$table];
		} else {
			$prefix = null;
			wfRunHooks( 'BacklinkCacheGetPrefix', array( $table, &$prefix ) );
			if ( $prefix ) {
				return $prefix;
			} else {
				throw new MWException( "Invalid table \"$table\" in " . __CLASS__ );
			}
		}
	}

	/**
	 * Get the SQL condition array for selecting backlinks, with a join
	 * on the page table.
	 * @param $table String
	 * @throws MWException
	 * @return array|null
	 */
	protected function getConditions( $table ) {
		$prefix = $this->getPrefix( $table );

		switch ( $table ) {
			case 'pagelinks':
			case 'templatelinks':
				$conds = array(
					"{$prefix}_namespace" => $this->title->getNamespace(),
					"{$prefix}_title" => $this->title->getDBkey(),
					"page_id={$prefix}_from"
				);
				break;
			case 'redirect':
				$conds = array(
					"{$prefix}_namespace" => $this->title->getNamespace(),
					"{$prefix}_title" => $this->title->getDBkey(),
					$this->getDb()->makeList( array(
						"{$prefix}_interwiki" => '',
						"{$prefix}_interwiki IS NULL",
					), LIST_OR ),
					"page_id={$prefix}_from"
				);
				break;
			case 'imagelinks':
			case 'categorylinks':
				$conds = array(
					"{$prefix}_to" => $this->title->getDBkey(),
					"page_id={$prefix}_from"
				);
				break;
			default:
				$conds = null;
				wfRunHooks( 'BacklinkCacheGetConditions', array( $table, $this->title, &$conds ) );
				if ( !$conds ) {
					throw new MWException( "Invalid table \"$table\" in " . __CLASS__ );
				}
		}

		return $conds;
	}

	/**
	 * Check if there are any backlinks
	 * @param $table String
	 * @return bool
	 */
	public function hasLinks( $table ) {
		return ( $this->getNumLinks( $table, 1 ) > 0 );
	}

	/**
	 * Get the approximate number of backlinks
	 * @param $table String
	 * @param $max integer|INF Only count up to this many backlinks
	 * @return integer
	 */
	public function getNumLinks( $table, $max = INF ) {
		global $wgMemc, $wgUpdateRowsPerJob;

		// 1) try partition cache ...
		if ( isset( $this->partitionCache[$table] ) ) {
			$entry = reset( $this->partitionCache[$table] );

			return min( $max, $entry['numRows'] );
		}

		// 2) ... then try full result cache ...
		if ( isset( $this->fullResultCache[$table] ) ) {
			return min( $max, $this->fullResultCache[$table]->numRows() );
		}

		$memcKey = wfMemcKey( 'numbacklinks', md5( $this->title->getPrefixedDBkey() ), $table );

		// 3) ... fallback to memcached ...
		$count = $wgMemc->get( $memcKey );
		if ( $count ) {
			return min( $max, $count );
		}

		// 4) fetch from the database ...
		if ( is_infinite( $max ) ) { // no limit at all
			// Use partition() since it will batch the query and skip the JOIN.
			// Use $wgUpdateRowsPerJob just to encourage cache reuse for jobs.
			$this->partition( $table, $wgUpdateRowsPerJob ); // updates $this->partitionCache
			return $this->partitionCache[$table][$wgUpdateRowsPerJob]['numRows'];
		} else { // probably some sane limit
			// Fetch the full title info, since the caller will likely need it next
			$count = $this->getLinks( $table, false, false, $max )->count();
			if ( $count < $max ) { // full count
				$wgMemc->set( $memcKey, $count, self::CACHE_EXPIRY );
			}
		}

		return min( $max, $count );
	}

	/**
	 * Partition the backlinks into batches.
	 * Returns an array giving the start and end of each range. The first
	 * batch has a start of false, and the last batch has an end of false.
	 *
	 * @param string $table the links table name
	 * @param $batchSize Integer
	 * @return Array
	 */
	public function partition( $table, $batchSize ) {
		global $wgMemc;

		// 1) try partition cache ...
		if ( isset( $this->partitionCache[$table][$batchSize] ) ) {
			wfDebug( __METHOD__ . ": got from partition cache\n" );

			return $this->partitionCache[$table][$batchSize]['batches'];
		}

		$this->partitionCache[$table][$batchSize] = false;
		$cacheEntry =& $this->partitionCache[$table][$batchSize];

		// 2) ... then try full result cache ...
		if ( isset( $this->fullResultCache[$table] ) ) {
			$cacheEntry = $this->partitionResult( $this->fullResultCache[$table], $batchSize );
			wfDebug( __METHOD__ . ": got from full result cache\n" );

			return $cacheEntry['batches'];
		}

		$memcKey = wfMemcKey(
			'backlinks',
			md5( $this->title->getPrefixedDBkey() ),
			$table,
			$batchSize
		);

		// 3) ... fallback to memcached ...
		$memcValue = $wgMemc->get( $memcKey );
		if ( is_array( $memcValue ) ) {
			$cacheEntry = $memcValue;
			wfDebug( __METHOD__ . ": got from memcached $memcKey\n" );

			return $cacheEntry['batches'];
		}

		// 4) ... finally fetch from the slow database :(
		$cacheEntry = array( 'numRows' => 0, 'batches' => array() ); // final result
		// Do the selects in batches to avoid client-side OOMs (bug 43452).
		// Use a LIMIT that plays well with $batchSize to keep equal sized partitions.
		$selectSize = max( $batchSize, 200000 - ( 200000 % $batchSize ) );
		$start = false;
		do {
			$res = $this->queryLinks( $table, $start, false, $selectSize, 'ids' );
			$partitions = $this->partitionResult( $res, $batchSize, false );
			// Merge the link count and range partitions for this chunk
			$cacheEntry['numRows'] += $partitions['numRows'];
			$cacheEntry['batches'] = array_merge( $cacheEntry['batches'], $partitions['batches'] );
			if ( count( $partitions['batches'] ) ) {
				list( , $lEnd ) = end( $partitions['batches'] );
				$start = $lEnd + 1; // pick up after this inclusive range
			}
		} while ( $partitions['numRows'] >= $selectSize );
		// Make sure the first range has start=false and the last one has end=false
		if ( count( $cacheEntry['batches'] ) ) {
			$cacheEntry['batches'][0][0] = false;
			$cacheEntry['batches'][count( $cacheEntry['batches'] ) - 1][1] = false;
		}

		// Save partitions to memcached
		$wgMemc->set( $memcKey, $cacheEntry, self::CACHE_EXPIRY );

		// Save backlink count to memcached
		$memcKey = wfMemcKey( 'numbacklinks', md5( $this->title->getPrefixedDBkey() ), $table );
		$wgMemc->set( $memcKey, $cacheEntry['numRows'], self::CACHE_EXPIRY );

		wfDebug( __METHOD__ . ": got from database\n" );

		return $cacheEntry['batches'];
	}

	/**
	 * Partition a DB result with backlinks in it into batches
	 * @param $res ResultWrapper database result
	 * @param $batchSize integer
	 * @param $isComplete bool Whether $res includes all the backlinks
	 * @throws MWException
	 * @return array
	 */
	protected function partitionResult( $res, $batchSize, $isComplete = true ) {
		$batches = array();
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

			# Sanity check order
			if ( $start && $end && $start > $end ) {
				throw new MWException( __METHOD__ . ': Internal error: query result out of order' );
			}

			$batches[] = array( $start, $end );
		}

		return array( 'numRows' => $numRows, 'batches' => $batches );
	}
}
