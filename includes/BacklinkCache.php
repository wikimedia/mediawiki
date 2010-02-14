<?php
/**
 * Class for fetching backlink lists, approximate backlink counts and partitions.
 * Instances of this class should typically be fetched with $title->getBacklinkCache().
 *
 * Ideally you should only get your backlinks from here when you think there is some
 * advantage in caching them. Otherwise it's just a waste of memory.
 */
class BacklinkCache {
	var $partitionCache = array();
	var $fullResultCache = array();
	var $title;
	var $db;

	const CACHE_EXPIRY = 3600;

	/**
	 * Create a new BacklinkCache
	 */
	function __construct( $title ) {
		$this->title = $title;
	}

	/**
	 * Clear locally stored data
	 */
	function clear() {
		$this->partitionCache = array();
		$this->fullResultCache = array();
		unset( $this->db );
	}

	/**
	 * Set the Database object to use
	 */
	public function setDB( $db ) {
		$this->db = $db;
	}

	protected function getDB() {
		if ( !isset( $this->db ) ) {
			$this->db = wfGetDB( DB_SLAVE );
		}
		return $this->db;
	}

	/**
	 * Get the backlinks for a given table. Cached in process memory only.
	 * @param $table String
	 * @param $startId Integer or false
	 * @param $endId Integer or false
	 * @return TitleArray
	 */
	public function getLinks( $table, $startId = false, $endId = false ) {
		wfProfileIn( __METHOD__ );

		$fromField = $this->getPrefix( $table ) . '_from';

		if ( $startId || $endId ) {
			// Partial range, not cached
			wfDebug( __METHOD__ . ": from DB (uncacheable range)\n" );
			$conds = $this->getConditions( $table );
			// Use the from field in the condition rather than the joined page_id,
			// because databases are stupid and don't necessarily propagate indexes.
			if ( $startId ) {
				$conds[] = "$fromField >= " . intval( $startId );
			}
			if ( $endId ) {
				$conds[] = "$fromField <= " . intval( $endId );
			}
			$res = $this->getDB()->select(
				array( $table, 'page' ),
				array( 'page_namespace', 'page_title', 'page_id' ),
				$conds,
				__METHOD__,
				array(
					'STRAIGHT_JOIN',
					'ORDER BY' => $fromField
				) );
			$ta = TitleArray::newFromResult( $res );
			wfProfileOut( __METHOD__ );
			return $ta;
		}

		if ( !isset( $this->fullResultCache[$table] ) ) {
			wfDebug( __METHOD__ . ": from DB\n" );
			$res = $this->getDB()->select(
				array( $table, 'page' ),
				array( 'page_namespace', 'page_title', 'page_id' ),
				$this->getConditions( $table ),
				__METHOD__,
				array(
					'STRAIGHT_JOIN',
					'ORDER BY' => $fromField,
				) );
			$this->fullResultCache[$table] = $res;
		}
		$ta = TitleArray::newFromResult( $this->fullResultCache[$table] );
		wfProfileOut( __METHOD__ );
		return $ta;
	}

	/**
	 * Get the field name prefix for a given table
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
			throw new MWException( "Invalid table \"$table\" in " . __CLASS__ );
		}
	}

	/**
	 * Get the SQL condition array for selecting backlinks, with a join on the page table
	 */
	protected function getConditions( $table ) {
		$prefix = $this->getPrefix( $table );

		switch ( $table ) {
			case 'pagelinks':
			case 'templatelinks':
			case 'redirect':
				$conds = array(
					"{$prefix}_namespace" => $this->title->getNamespace(),
					"{$prefix}_title" => $this->title->getDBkey(),
					"page_id={$prefix}_from"
				);
				break;
			case 'imagelinks':
				$conds = array(
					'il_to' => $this->title->getDBkey(),
					'page_id=il_from'
				);
				break;
			case 'categorylinks':
				$conds = array(
					'cl_to' => $this->title->getDBkey(),
					'page_id=cl_from',
				);
				break;
			default:
				throw new MWException( "Invalid table \"$table\" in " . __CLASS__ );
		}
		return $conds;
	}

	/**
	 * Get the approximate number of backlinks
	 */
	public function getNumLinks( $table ) {
		if ( isset( $this->fullResultCache[$table] ) ) {
			return $this->fullResultCache[$table]->numRows();
		}

		if ( isset( $this->partitionCache[$table] ) ) {
			$entry = reset( $this->partitionCache[$table] );
			return $entry['numRows'];
		}

		$titleArray = $this->getLinks( $table );
		return $titleArray->count();
	}

	/**
	 * Partition the backlinks into batches.
	 * Returns an array giving the start and end of each range. The first batch has
	 * a start of false, and the last batch has an end of false.
	 *
	 * @param $table String: the links table name
	 * @param $batchSize Integer
	 * @return Array
	 */
	public function partition( $table, $batchSize ) {
		// Try cache
		if ( isset( $this->partitionCache[$table][$batchSize] ) ) {
			wfDebug( __METHOD__ . ": got from partition cache\n" );
			return $this->partitionCache[$table][$batchSize]['batches'];
		}

		$this->partitionCache[$table][$batchSize] = false;
		$cacheEntry =& $this->partitionCache[$table][$batchSize];

		// Try full result cache
		if ( isset( $this->fullResultCache[$table] ) ) {
			$cacheEntry = $this->partitionResult( $this->fullResultCache[$table], $batchSize );
			wfDebug( __METHOD__ . ": got from full result cache\n" );
			return $cacheEntry['batches'];
		}

		// Try memcached
		global $wgMemc;
		$memcKey = wfMemcKey(
			'backlinks',
			md5( $this->title->getPrefixedDBkey() ),
			$table,
			$batchSize
		);
		$memcValue = $wgMemc->get( $memcKey );

		if ( is_array( $memcValue ) ) {
			$cacheEntry = $memcValue;
			wfDebug( __METHOD__ . ": got from memcached $memcKey\n" );
			return $cacheEntry['batches'];
		}
		// Fetch from database
		$this->getLinks( $table );
		$cacheEntry = $this->partitionResult( $this->fullResultCache[$table], $batchSize );
		// Save to memcached
		$wgMemc->set( $memcKey, $cacheEntry, self::CACHE_EXPIRY );
		wfDebug( __METHOD__ . ": got from database\n" );
		return $cacheEntry['batches'];
	}

	/**
	 * Partition a DB result with backlinks in it into batches
	 */
	protected function partitionResult( $res, $batchSize ) {
		$batches = array();
		$numRows = $res->numRows();
		$numBatches = ceil( $numRows / $batchSize );

		for ( $i = 0; $i < $numBatches; $i++ ) {
			if ( $i == 0  ) {
				$start = false;
			} else {
				$rowNum = intval( $numRows * $i / $numBatches );
				$res->seek( $rowNum );
				$row = $res->fetchObject();
				$start = $row->page_id;
			}

			if ( $i == $numBatches - 1 ) {
				$end = false;
			} else {
				$rowNum = intval( $numRows * ( $i + 1 ) / $numBatches );
				$res->seek( $rowNum );
				$row = $res->fetchObject();
				$end = $row->page_id - 1;
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
