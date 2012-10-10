<?php
/**
 * This class represents "a data entry".
 *
 * DataModel will allow data to be written to/read from a backend (e.g.
 * MySQL), while caching the results with BagOStuff (e.g. Memcached,
 * Redis, ...). This cache was especially important if the backend is
 * sharded; where fetching a number of entries could theoretically mean
 * that 10 servers would need to be queried.
 *
 * Everything will revolve around a "shard key", e.g. passing only the id
 * to the ::get-method is not enough. This model assumes that data can
 * (depending on the backend) be sharded. This means that data is spread
 * over multiple servers with the value of the shard key determining what
 * server the data will be stored on. E.g. if the value of the shard key
 * (which could e.g. be the page id) id even, all data is saved to server 1,
 * if the value is odd, the data will be at server 2.
 *
 * This class can be easily extended (as DataModelSample.php does) for a
 * specific implementation.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
abstract class DataModel {
	/**
	 * Database table to hold the data.
	 *
	 * @var string
	 */
	protected static $table;

	/**
	 * Name of column to act as unique id.
	 *
	 * @var string
	 */
	protected static $idColumn;

	/**
	 * Name of column to shard data over.
	 *
	 * @todo: at some point, we might want to shard over multiple columns;
	 * for now, only 1 is supported
	 *
	 * @var string
	 */
	protected static $shardColumn;

	/**
	 * All lists the data can be displayed as.
	 *
	 * Key is the filter name, the value is an array containing:
	 * * the conditions an "entry" must abide to to qualify for this list
	 * * the data to be sorted on
	 *
	 * @var array
	 */
	public static $lists = array( /*
		// sample list that would:
		// * include all entries (there are no conditions)
		// * be sorted by insertion time (no order is defined)
		'all' => array(
			'conditions' => array(),
			'sort' => array()
		),
		// sample list that would:
		// * include no entries (condition will never evaluate to true: id won't be < 0)
		// * be sorted by entry id
		'none' => array(
			'conditions' => array( '$this->{static::getIdColumn()} < 0' ),
			'sort' => array( 'id' => '$this->{static::getIdColumn()}' )
		)
*/ );

	/**
	 * Pagination limit: how many entries should be fetched at once for lists.
	 *
	 * Note that this is a constant per DataModel implementation, and a call
	 * to getList will not accept any limit parameter. This is intentional:
	 * if any arbitrary limit would be accepted, it would become madness to
	 * cache the results. Therefore, limit is a fixed number, so that it
	 * does make sense to cache the data.
	 *
	 * @var int
	 */
	const LIST_LIMIT = 25;

	/**
	 * Instance of the backend object.
	 *
	 * @var DataModelBackend
	 */
	protected static $backend;

	/**
	 * Validate the entry's data.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function validate() {
		/*
		 * If data needs to validate to certain rules before it can be saved,
		 * this method can be extended by the implementing class.
		 *
		 * Throw MWException for whatever if wrong.
		 */

		return $this;
	}

	/**
	 * Purge relevant Squid cache when updating data.
	 *
	 * @return DataModel
	 */
	public function purgeSquidCache() {
		/*
		 * If there are Squid cached to clear after having saved data, this
		 * is the place to do it. Example code:
		 *
		 * 	$uri = wfAppendQuery(
		 * 		wfScript( 'api' ),
		 * 		array(
		 * 			'action'       => 'query',
		 * 			'format'       => 'json',
		 * 			'list'         => 'xyz',
		 * 			...
		 * 			'maxage'       => 0,
		 * 			'smaxage'      => 60 * 60 * 24 * 30 // 30 days
		 * 		)
		 * 	);
		 * 	$squidUpdate = new SquidUpdate( array( $uri ) );
		 * 	$squidUpdate->doUpdate();
		 */

		return $this;
	}

	/**
	 * Populate object's properties with database (ResultWrapper) data.
	 *
	 * Assume that object properties & db columns are an exact match;
	 * if not, the extending class can extend this method.
	 *
	 * @param stdClass $row The db row
	 * @return DataModel
	 */
	public function toObject( stdClass $row ) {
		foreach ( $this->toArray() as $column => $value ) {
			$this->$column = $row->$column;
		}

		return $this;
	}

	/**
	 * Get array-representation of this object, ready for use by Backend.
	 *
	 * Assume that object properties & db columns are an exact match;
	 * if not, the extending class can extend this method.
	 *
	 * @param ResultWrapper $row The db row
	 * @return DataModel
	 */
	public function toArray() {
		return get_object_vars( $this );
	}

	/**
	 * Fetch a data entry by its id & shard key.
	 *
	 * @param int $id The id of the entry to fetch
	 * @param int $shard The shard key value
	 * @return DataModel|boolean
	 */
	public static function get( $id, $shard ) {
		global $wgMemc;

		$key = wfMemcKey( get_called_class(), 'get', $id, $shard );
		$entry = $wgMemc->get( $key );

		// when not found in cache, load data from DB
		if ( $entry === false ) {
			$row = static::getBackend()->get( $id, $shard );
			if ( $row ) {
				$row = $row->fetchObject();
				if ( $row ) {
					$entry = static::loadFromRow( $row );
				}
			}
		}

		// (extend) cache
		if ( $entry ) {
			$entry->cache();
		}

		return $entry;
	}

	/**
	 * Fetch a list of entries.
	 *
	 * Strategy:
	 * Limit calls to sharded data to only be on id & shard key (& index these)
	 * Other WHERE-clauses should be avoided. Instead, this "lists" concept will be used.
	 *
	 * A "list" could basically represent any query with a "special where clause", e.g.
	 * * "show all posts that have been oversighted" could be a list
	 * * "show all anonymous users" could be a list
	 * * ...
	 *
	 * We don't want to do this sort of queries on the source data table, because:
	 * * The data can be sharded - cross-shard fetching is horrible
	 * * We would have to add a lot more column indexes to the source data
	 *
	 * Rather than executing these queries on the source data table, the CRUD-methods
	 * will evaluate these "list requirements" (basically the equivalent of WHERE-clause)
	 * in PHP and update the list entries (add/remove entries or move them around)
	 * These requirements can be defined on a per-list basis, at static::$lists
	 *
	 * These lists will be stored in the database (datamodel_lists), sharded by list name.
	 * This theoretically leaves us vulnerable to scalability issues in the event that one
	 * list would grow way out of proportion. Since the lists only contain id references
	 * though, the chance of this table hitting hardware limits are extremely small.
	 *
	 * These list results are then saved in cache (in small chunks) so that commonly
	 * accessed lists don't need to query the database (scaling the reads).
	 *
	 * To keep reducing database connections/queries, we'll be slightly over-fetching
	 * data. Assuming one is requesting the first 25 entries, it is likely that the next
	 * 25 will be requested as well. We'll be fetching more than requested right away
	 * (since that's relatively cheap) and save this larger chunk to cache, which will
	 * enable us to do fewer queries when that data is finally requested.
	 *
	 * Note: one of the drawbacks of this list-approach is that a list can not be created
	 * "on the fly": we can't just apply a new SQL statement with a new WHERE-
	 * clause (because that's replaced by a PHP conditions). To add a new list when data
	 * already exists, a maintenance script will have to be run to nuke & re-populate lists.
	 *
	 * @todo: Currently still struggling to come up with a solution for data with a
	 * variable WHERE-clause (e.g. "show all users with a creation date under 30 days
	 * ago", where the exact value of "30 days ago" changes every second). Possible
	 * solution could be to nuke & re-evaluate all entries to all list periodically.
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed[optional] $shard Get only data for a certain shard value
	 * @param int[optional] $offset The offset to start from (a multiple of static::LIST_LIMIT)
	 * @param string[optional] $sort Sort to apply to list
	 * @param string[optional] $order Sort the list ASC or DESC
	 * @return array
	 * @throw MWException
	 */
	public static function getList( $name, $shard = null, $offset = 0, $sort = null, $order = 'ASC' ) {
		global $wgMemc;

		$order = strtoupper( $order );

		if ( !isset( static::$lists[$name] )) {
			throw new MWException( "List '$name' is no known list" );
		} elseif ( $offset % static::LIST_LIMIT != 0 ) {
			throw new MWException( 'Offset should be a multiple of ' . static::LIST_LIMIT );
		} elseif ( $sort !== null && !in_array( $sort, array_keys( static::$lists[$name]['sort'] ) ) ) {
			throw new MWException( "Sort '$sort' does not exist for list '$name'" );
		} elseif ( !in_array( $order, array( 'ASC', 'DESC' ) ) ) {
			throw new MWException( 'Order should be either ASC or DESC' );
		}

		if ( $sort === null ) {
			$sort = static::getIdColumn();
		}

		// internal key to identify this exact list by
		$keyGetList = wfMemcKey( get_called_class(), 'getList', $name, $shard, $offset, $sort, $order );
		$keyGetListValidity = wfMemcKey( get_called_class(), 'getListValidity', $name, $shard );

		// get data from cache
		$cache = $wgMemc->get( $keyGetList );

		// check if cached lists for this list/shard are valid
		$list = false;
		$validity = $wgMemc->get( $keyGetListValidity );
		if ( $validity === false || $cache['time'] > $validity ) {
			$list = $cache['list'];
		}

		if ( $list === false ) {
			/*
			 * To reduce the amount of queries/connections to be performed on db,
			 * larger-than-requested chunks will be fetched & cached, waiting
			 * to be re-used at the next offset ;)
			 * e.g. for batches 0-25, 25-50, 50-75 & 75-100: $min will be 0
			 */
			$batchSize = static::LIST_LIMIT * 4;
			$min = floor( $offset / $batchSize ) * $batchSize;

			// fetch data from db
			$rows = static::getBackend()->getList( $name, $shard, $min, $batchSize, $sort, $order );

			// build [id] => [shard] array for requested part of list
			$list = array();
			if ( $rows ) {
				foreach ( $rows as $row ) {
					$list[$row->id] = $row->shard;
				}
			}
		}

		/*
		 * $list now contains an array of [id] => [shard] entries.
		 * We'll now want to fetch the actual feedback data for these entries; gather a
		 * list of entries that are no yet cached, so we can fetch all of them at once
		 */
		$missing = array();
		foreach ( $list as $id => $shard ) {
			$keyGet = wfMemcKey( get_called_class(), 'get', $id, $shard );
			if ( $wgMemc->get( $keyGet ) === false ) {
				$missing[$id] = $shard;
			}
		}

		if ( $missing ) {
			// fetch the missing entries from all at once and cache them right away
			$rows = static::getBackend()->get( array_keys( $missing ), array_values( $missing ) );

			// we don't really care for the returned rows but just want them cached
			foreach ( $rows as $row ) {
				$entry = static::loadFromRow( $row );
				$entry->cache();
			}
		}

		/*
		 * At this point, all entries (including the pre-fetched ones) should be in
		 * cache. Now splice the part we requested (if we got $list from the database,
		 * it still contains more entries than requested - the pre-fetched ones)
		 */
		if ( count( $list ) > static::LIST_LIMIT ) {
			$list = array_slice( $list, $offset - $min, static::LIST_LIMIT, true );
		}

		// cache list data
		$cache = array( 'time' => wfTimestampNow(), 'list' => $list );
		$wgMemc->set( $keyGetList, $cache, 60 * 60 );

		foreach ( $list as $id => $shard ) {
			$list[$id] = static::get( $id, $shard );
		}

		return $list;
	}

	/**
	 * Get the amount of entries in a certain list.
	 *
	 * This should pretty much always be cached:
	 * - it's only 1 int per list/shard, so won't consume too much memory
	 * - when fetching from db, it requires an aggregate function, so not so cheap
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed[optional] $shard Get only data for a certain shard value
	 * @return int
	 */
	public static function getCount( $name, $shard = null ) {
		global $wgMemc;

		if ( !isset( static::$lists[$name] )) {
			throw new MWException( "List '$name' is no known list" );
		}

		// internal key to identify this exact list by
		$key = wfMemcKey( get_called_class(), 'getCount', $name, $shard );

		// (try to) fetch list from cache
		$count = $wgMemc->get( $key );

		if ( $count === false ) {
			$count = static::getBackend()->getCount( $name, $shard );
			$wgMemc->set( $key, $count );
		}

		return $count;
	}

	/**
	 * Insert entry.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function insert() {
		// claim unique id for this entry
		if ( $this->{static::getIdColumn()} === null ) {
			$this->{static::getIdColumn()} = $this->generateId();
		}

		// validate properties before saving them
		$this->validate();

		// insert into DB
		static::getBackend()->insert( $this );

		return $this
			// update this entry in all applicable lists
			->updateLists()
			// cache entry
			->cache()
			// purge existing cache
			->purgeSquidCache();
	}

	/**
	 * Update entry.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function update() {
		if ( $this->{static::getIdColumn()} === null ) {
			throw new MWException( "Entry has no unique id yet - did you intend to insert rather than update?" );
		}

		// save a copy of the old object so we can update its listings later on
		$old = static::get( $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );

		// validate properties before saving them
		$this->validate();

		// insert into DB
		static::getBackend()->update( $this );

		return $this
			// update this entry in all applicable lists
			->updateLists( $old )
			// cache entry
			->cache()
			// purge existing cache
			->purgeSquidCache();
	}

	/**
	 * Delete entry.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function delete() {
		if ( $this->{static::getIdColumn()} === null ) {
			throw new MWException( "Entry has no unique id" );
		}

		// save a copy of the old object so we can delete its listings later on
		$old = static::get( $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );

		// delete from DB
		static::getBackend()->delete( $this );

		return $this
			// update this entry in all applicable lists
			->updateLists( $old )
			// cache entry
			->uncache()
			// purge existing cache
			->purgeSquidCache();
	}

	/**
	 * Get name of table to hold the data.
	 *
	 * @return string
	 * @throw MWException
	 */
	public static function getTable() {
		if ( !static::$table ) {
			throw new MWException( 'No table name has been set in class ' . get_called_class() );
		}

		return static::$table;
	}

	/**
	 * Get name of column to act as unique id.
	 *
	 * @return string
	 * @throw MWException
	 */
	public static function getIdColumn() {
		if ( !static::$idColumn ) {
			throw new MWException( 'No id column has been set in class ' . get_called_class() );
		} elseif ( !property_exists( get_called_class(), static::$idColumn ) ) {
			throw new MWException( 'Id column does not exist in object representation in class ' . get_called_class() );
		}

		return static::$idColumn;
	}

	/**
	 * Get name of column to shard data over.
	 *
	 * @return string
	 * @throw MWException
	 */
	public static function getShardColumn () {
		if ( !static::$shardColumn ) {
			throw new MWException( 'No shard column has been set in class ' . get_called_class() );
		} elseif ( !property_exists( get_called_class(), static::$shardColumn ) ) {
			throw new MWException( 'Shard column does not exist in object representation in class ' . get_called_class() );
		}

		return static::$shardColumn;
	}

	/**
	 * Get the backend object that'll store the data for real.
	 *
	 * @return DataModelBackend
	 */
	public static function getBackend() {
		if ( self::$backend === null ) {
			global $wgDataModelBackendClass;
			$class = isset( $wgDataModelBackendClass ) ? $wgDataModelBackendClass : 'DataModelBackendLBFactory';

			self::$backend = new $class( static::getTable(), static::getIdColumn(), static::getShardColumn() );
		}

		return self::$backend;
	}

	/**
	 * Cache entry for an hour.
	 *
	 * @return DataModel
	 */
	public function cache() {
		global $wgMemc;
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		$wgMemc->set( $key, $this, 60 * 60 );

		return $this;
	}

	/**
	 * Delete entry from cache.
	 *
	 * @return DataModel
	 */
	public function uncache() {
		global $wgMemc;
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		$wgMemc->delete( $key );

		return $this;
	}

	/**
	 * This method will return an array of lists the entry matches to and
	 * the sort values for each of the defined sort methods.
	 *
	 * @return array
	 */
	public function getMatchingLists() {
		$lists = array();

		foreach ( static::$lists as $list => $properties ) {
			// check if entry complies to list conditions
			$match = true;
			if ( isset( $properties['conditions'] ) && $properties['conditions'] ) {
				foreach ( (array) $properties['conditions'] as $condition ) {
					if ( $condition ) {
						eval( '$match &= ' . $condition . ';' ); // ieuw - eval :o
					}
				}
			}

			if ( $match ) {
				// compile order and push to result array
				if ( isset( $properties['sort'] ) && $properties['sort'] ) {
					foreach ( (array) $properties['sort'] as $sort => $sortValue ) {
						eval( '$lists[$list][$sort] = ' . (string) $sortValue . ';' );
					}
				}

				// if no sort is set, just use id
				if ( !isset( $lists[$list] ) ) {
					$lists[$list][static::getIdColumn()] = $this->{static::getIdColumn()};
				}
			}
		}

		return $lists;
	}

	/**
	 * Update an entry's presence & sort values in all defined list.
	 *
	 * @param DataModel[optional] $old The pre-save entry, to compare lists with
	 * @return DataModel
	 */
	public function updateLists( DataModel $old = null ) {
		$newLists = $this->getMatchingLists();
		$currentLists = array();
		if ( $old ) {
			$currentLists = $old->getMatchingLists();
		}

		foreach ( static::$lists as $list => $properties ) {
			$existsNew = array_key_exists( $list, $newLists );
			$existsCurrent = array_key_exists( $list, $currentLists );

			// add/update/delete entry listings in DB
			$sorts = $existsNew ? $newLists[$list] : array();
			$affected = static::getBackend()->updateListing( $this, $list, $sorts );

			// update list totals
			$difference = (int) $existsNew - (int) $existsCurrent;
			$this->updateCountCache( $list, $this->{static::getShardColumn()}, $difference );

			// purge list cache
			if ( $affected ) {
				$this->purgeListCache( $list, $this->{static::getShardColumn()} );
			}
		}

		return $this;
	}

	/**
	 * Update count caches. This one we don't just want to purge.
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard The shard value
	 * @param int $difference The difference to apply to current count
	 * @return DataModel
	 */
	protected function updateCountCache( $name, $shard, $difference ) {
		global $wgMemc;

		// update both shard-specific as well as general all-shard count
		foreach ( array( $shard, null ) as $shard ) {
			$class = get_called_class();
			$key = wfMemcKey( $class, 'getCount', $name, $shard );

			/**
			 * Callback method, updating the cached counts.
			 *
			 * @param BagOStuff $cache
			 * @param string $key
			 * @param int $existingValue
			 * @use string $name The list name (see static::$lists)
			 * @use mixed $shard The shard value
			 * @use int $difference The difference to apply to current count
			 * @use string $class The called class
			 * @return int
			 */
			$callback = function( BagOStuff $cache, $key, $existingValue ) use ( $name, $shard, $difference, $class ) {
				// if cache is stale, get it from DB
				if ( $existingValue === false ) {
					// getCount will cache by itself
					$class::getCount( $name, $shard );
					return false;
				}

				// if count is in cache already, update it right away, avoiding any more DB reads
				return $existingValue + $difference;
			};

			// CAS new value, or - in case of failure - clear value to fallback to db value
			$result = $wgMemc->merge( $key, $callback );
			if ( $result === false ) {
				$wgMemc->delete( $key );
			}
		}

		return $this;
	}

	/**
	 * Purge all cached lists, effectively forcing fresh data to be read
	 * from the database all over again.
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard The shard value
	 * @return DataModel
	 */
	protected function purgeListCache( $name, $shard ) {
		global $wgMemc;

		/*
		 * All calls to ::getList result in partial (length of static::LIST_LIMIT)
		 * caches. Having to invalidate all of them would not scale: if the amount
		 * of entries grows really large, the potential amount of partial caches
		 * to be purged would continue to grow and it would take longer and longer
		 * to purge a list.
		 * Instead of purging all of this, we'll keep another value in cache that will
		 * define the validity of all partial caches of a certain list.
		 *
		 * Validity cache expiration is set to the same time of list cache expiration,
		 * so if validity key is not set, the cache is valid. A cache is also valid
		 * if the timestamp stored along with it is more recent than the timestamp
		 * found in this validity cache.
		 */

		$key = wfMemcKey( get_called_class(), 'getListValidity', $name, $shard );
		$wgMemc->set( $key, wfTimestampNow(), 60 * 60 );

		return $this;
	}

	/**
	 * Build a DataModel entry from it's ResultWrapper data.
	 *
	 * @param stdClass $row
	 * @return DataModel
	 */
	public static function loadFromRow( stdClass $row ) {
		$entry = new static;
		return $entry->toObject( $row );
	}

	/**
	 * Generate a new, unique id.
	 *
	 * Data can be sharded over multiple servers, rendering database engine's
	 * auto-increment useless to generate a unique id.
	 *
	 * @return string
	 */
	protected function generateId() {
		global $wgMemc;

		$time = microtime();

		/**
		 * Callback method, updating the cached counts. This will ensure that no
		 * multiple concurrent processes that had generated the same microtime()
		 * can result the same value (only 1 of then can be merged)
		 *
		 * @param BagOStuff $cache
		 * @param string $key
		 * @param int $existingValue
		 * @use string $time Generated unique timestamp
		 * @return string
		 */
		$callback = function( BagOStuff $cache, $key, $existingValue ) use ( $time ) {
			/*
			 * The only possible way microtime() could result in a non-unique value,
			 * is when it's called at the exact same time. Stall until microtime()
			 * generates a new id.
			 */
			while ( $time === microtime() );

			return $time;
		};

		$key = wfMemcKey( get_called_class(), 'generateId' );
		if ( !$wgMemc->merge( $key, $callback, 1, 1 ) ) {
			/*
			 * $time could not be merged, but current microtime() should have
			 * elapsed already - let's restart generating!
			 */
			return $this->generateId();
		}

		/*
		 * At this point, we're certain that $time is unique. Manipulate the result
		 * to not expose the exact timestamp in the ID (not that it really matters).
		 * Don't worry; although theoretically, this lossy manipulation could again
		 * result in the same id's, it won't for at least the first couple of million
		 * years ;)
		 */
		return md5( $time );
	}
}
