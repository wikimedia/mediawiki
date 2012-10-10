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
	 * All lists the data can be displayed as
	 *
	 * Key is the filter name, the value is an array of the conditions an "entry"
	 * must abide to to qualify for this list.
	 *
	 * @var array
	 */
	public static $lists = array( /*
		// sample list that would include all entries (there are no conditions)
		'all' => array(),
		// sample list that would include no entries (condition will never evaluate to true: id won't be < 0)
		'none' => array( 'id < 0' ),
*/	);

	/**
	 * Available sorts to order the data
	 *
	 * Key is the sort name, the value is the condition for the ORDER BY clause.
	 *
	 * When creating indexes on the database, create a compound index for each of
	 * the sort-columns, along with the id column.
	 *
	 * @var array
	 */
	public static $sorts = array( /*
		// sample order on id value - note: unlike a traditional auto-increment id,
		// this would not result in entries in insertion order; the id generated
		// by datamodel is a rather random hash
		'id' => 'id',
*/	);

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
	 * Instance of cache object.
	 *
	 * @var BagOStuff
	 */
	protected static $cache;

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
	 * @return array
	 */
	public function toArray() {
		return get_object_vars( $this );
	}

	/**
	 * Fetch a data entry by its id & shard key.
	 *
	 * @param int $id The id of the entry to fetch
	 * @param int $shard The shard key value
	 * @return DataModel|bool
	 */
	public static function get( $id, $shard ) {
		$key = wfMemcKey( get_called_class(), 'get', $id, $shard );
		$entry = static::getCache()->get( $key );

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
	 * A "list" could basically represent any query with a "special where clause", e.g.
	 * * "show all posts that have been oversighted" could be a list
	 * * "show all anonymous users" could be a list
	 * * ...
	 *
	 * Because we want to minimize fetching data from the DB (especially cross-shard),
	 * these results will be cached. Upon editing an entry, there'll be some funkiness
	 * to get the results of all lists' conditions (WHERE-clauses) to determine when
	 * caches need to be purged again (because WHERE-condition matches have changed)
	 *
	 * After fetching the data from the database, these list results are saved in cache
	 * (in small chunks) so that commonly accessed lists don't need to query the database
	 * (scaling the reads).
	 *
	 * To keep reducing database connections/queries, we'll be slightly over-fetching
	 * data. Assuming one is requesting the first 25 entries, it is likely that the next
	 * 25 will be requested as well. We'll be fetching more than requested right away
	 * (since that's relatively cheap) and save this larger chunk to cache, which will
	 * enable us to do fewer queries when that data is finally requested.
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed[optional] $shard Get only data for a certain shard value
	 * @param string[optional] $offset The offset to start from
	 * @param string[optional] $sort Sort to apply to list
	 * @param string[optional] $order Sort the list ASC or DESC
	 * @return DataModelList
	 * @throw MWException
	 */
	public static function getList( $name, $shard = null, $offset = null, $sort = null, $order = 'ASC' ) {
		$order = strtoupper( $order );

		if ( !isset( static::$lists[$name] ) ) {
			throw new MWException( "List '$name' is no known list" );
		} elseif ( $sort !== null && !in_array( $sort, array_keys( static::$sorts ) ) ) {
			throw new MWException( "Sort '$sort' does not exist" );
		} elseif ( !in_array( $order, array( 'ASC', 'DESC' ) ) ) {
			throw new MWException( 'Order should be either ASC or DESC' );
		}

		// internal key to identify this exact list by
		$keyGetList = wfMemcKey( get_called_class(), 'getList', $name, $shard, $offset, $sort, $order );
		$keyGetListValidity = wfMemcKey( get_called_class(), 'getListValidity', $name, $shard );

		// get data from cache
		$cache = static::getCache()->get( $keyGetList );

		$list = false;
		if ( $cache != false ) {
			// check if cached lists for this list/shard are valid
			$validity = static::getCache()->get( $keyGetListValidity );
			if ( $validity === false || $cache['time'] > $validity ) {
				$list = $cache['list'];
			}
		}

		// if found in cache, extend cache
		if ( $list !== false ) {
			static::cacheList( $list, $name, $shard, $offset, $sort, $order );

		// if not found in cache, get from db
		} else {
			/*
			 * To reduce the amount of queries/connections to be performed on db,
			 * larger-than-requested chunks will be fetched & cached, waiting
			 * to be re-used at the next offset ;)
			 */
			$batchSize = static::LIST_LIMIT * 4;

			// fetch data from db
			$rows = static::getBackend()->getList( $name, $shard, $offset, $batchSize + 1, $sort, $order );

			$entries = array();
			foreach ( $rows as $row ) {
				// pre-cache entries
				$entry = static::loadFromRow( $row );
				$entry->cache();

				// build list of id's
				$entries[] = array(
					'id' => $row->{static::getIdColumn()},
					'shard' => $row->{static::getShardColumn()},
					'offset' => $row->offset_value
				);
			}

			$size = 0;
			$first = false;

			// splice into smaller chunks & cache lists
			while ( $entries ) {
				/**
				 * Get offsets; for the first chunk, we'll cache it under the
				 * requested offset value; following chunks will be pre-cached
				 * under the value we retrieved from backend.
				 */
				$cacheOffset = !$first ? $offset : $entries[0]['offset'];
				$nextOffset = isset( $entries[static::LIST_LIMIT] ) ? $entries[static::LIST_LIMIT]['offset'] : false;

				$partial = array_splice( $entries, 0, static::LIST_LIMIT );

				// build list object
				$list = new DataModelList( $partial, get_called_class() );
				if ( $nextOffset ) {
					$list->setNextOffset( $nextOffset );
				}

				static::cacheList( $list, $name, $shard, $cacheOffset, $sort, $order );

				// we will want to return the first of these batches
				if ( !$first ) {
					$first = $list;
				}

				// after full batch size is processed, break out
				$size += count( $partial );
				if ( $size >= $batchSize ) {
					break;
				}
			}
			$list = $first;
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
		if ( !isset( static::$lists[$name] ) ) {
			throw new MWException( "List '$name' is no known list" );
		}

		// internal key to identify this exact list by
		$key = wfMemcKey( get_called_class(), 'getCount', $name, $shard );

		// (try to) fetch list from cache
		$count = static::getCache()->get( $key );

		if ( $count === false ) {
			$count = static::getBackend()->getCount( $name, $shard );
			static::getCache()->set( $key, $count );
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

		// validate properties before saving them
		$this->validate();

		// before updating in db, let's re-evaluate all list conditions & sorts
		$old = static::getBackend()->evaluateConditions( $this );

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

		// before updating in db, let's re-evaluate all list conditions & sorts
		$old = static::getBackend()->evaluateConditions( $this );

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
	 * Get the cache instance.
	 *
	 * @return BagOStuff
	 */
	public static function getCache() {
		if ( static::$cache === null ) {
			global $wgMemc;

			/*
			 * If no cache is set, use HashBagOStuff; even though it won't persist
			 * across requests. ::getList will retrieve all info from DB already and
			 * "cache" it, so that ::get (which is called by DataModelList) does not
			 * need to re-fetch it from DB because it's in cache or HashBagOStuff.
			 */
			$cache = get_class( $wgMemc ) != 'EmptyBagOStuff' ? $wgMemc : new HashBagOStuff;
			static::setCache( $cache );
		}

		return static::$cache;
	}

	/**
	 * Set the cache instance.
	 *
	 * @param BagOStuff $cache
	 * @return BagOStuff
	 */
	public static function setCache( BagOStuff $cache ) {
		static::$cache = $cache;
		return static::$cache;
	}

	/**
	 * Get the backend object that'll store the data for real.
	 *
	 * @return DataModelBackend
	 */
	public static function getBackend() {
		if ( static::$backend === null ) {
			global $wgDataModelBackendClass;
			$class = isset( $wgDataModelBackendClass ) ? $wgDataModelBackendClass : 'DataModelBackendLBFactory';

			$backend = new $class( get_called_class(), static::getTable(), static::getIdColumn(), static::getShardColumn() );
			static::setBackend( $backend );
		}

		return static::$backend;
	}

	/**
	 * Set the backend object.
	 *
	 * @param DataModelBackend $backend
	 * @return DataModelBackend
	 */
	public static function setBackend( DataModelBackend $backend ) {
		static::$backend = $backend;
		return static::$backend;
	}

	/**
	 * Will fetch a couple of items (from DB) and cache them.
	 *
	 * Fetching & caching as much as (useful) entries as possible will result
	 * in more efficient (fewer) queries to the backend.
	 *
	 * @param array $entries Array of items to be preloaded, in [id] => [shard] format
	 */
	public static function preload( array $entries ) {
		/*
		 * $entries contains an array of [id] => [shard] entries.
		 * We'll now want to fetch the actual data for these entries; gather a
		 * list of entries that are not yet cached, so we can fetch all of them at once
		 */
		$missing = array();
		foreach ( $entries as $entry ) {
			$id = $entry['id'];
			$shard = $entry['shard'];

			$keyGet = wfMemcKey( get_called_class(), 'get', $id, $shard );
			if ( static::getCache()->get( $keyGet ) === false ) {
				$missing[$id] = $shard;
			}
		}

		if ( $missing ) {
			// fetch all missing entries at once and cache them right away
			$rows = static::getBackend()->get( array_keys( $missing ), array_values( $missing ) );

			// we don't really care for the returned rows but just want them cached
			foreach ( $rows as $row ) {
				$entry = static::loadFromRow( $row );
				$entry->cache();
			}
		}
	}

	/**
	 * Cache entry for an hour.
	 *
	 * @return DataModel
	 */
	public function cache() {
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		static::getCache()->set( $key, $this, 60 * 60 );

		return $this;
	}

	/**
	 * Delete entry from cache.
	 *
	 * @return DataModel
	 */
	public function uncache() {
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		static::getCache()->delete( $key );

		return $this;
	}

	/**
	 * Cache list for an hour.
	 *
	 * @param DataModelList $list
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard The shard value
	 * @param string $offset The offset to start from
	 * @param string $sort Sort to apply to list
	 * @param string $order Sort the list ASC or DESC
	 * @return DataModelList
	 */
	public static function cacheList( $list, $name, $shard, $offset, $sort, $order ) {
		$cache = array( 'time' => wfTimestampNow(), 'list' => $list );
		$keyGetList = wfMemcKey( get_called_class(), 'getList', $name, $shard, $offset, $sort, $order );
		static::getCache()->set( $keyGetList, $cache, 60 * 60 );
	}

	/**
	 * Purge all cached lists, effectively forcing fresh data to be read
	 * from the database all over again.
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard The shard value
	 */
	public static function uncacheList( $name, $shard ) {
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
		foreach ( array( $shard, null ) as $shard ) {
			$key = wfMemcKey( get_called_class(), 'getListValidity', $name, $shard );
			static::getCache()->set( $key, wfTimestampNow(), 60 * 60 );
		}
	}

	/**
	 * Get a list's conditions.
	 *
	 * @param string $name
	 * @return array
	 * @throws MWException
	 */
	public static function getListConditions( $name ) {
		if ( !isset( static::$lists[$name] ) ) {
			throw new MWException( "List '$name' is no known list" );
		}

		return static::$lists[$name];
	}

	/**
	 * Update an entry's presence & sort values in all defined list.
	 *
	 * @param stdClass[optional] $old The pre-save conditions' results
	 * @return DataModel
	 */
	public function updateLists( $old = null ) {
		$new = static::getBackend()->evaluateConditions( $this );

		foreach ( static::$lists as $list => $properties ) {
			$existsOld = true;
			$existsNew = true;
			foreach ( static::getListConditions( $list ) as $condition ) {
				$existsOld &= isset( $old->{$condition} ) && $old->{$condition};
				$existsNew &= isset( $new->{$condition} ) && $new->{$condition};
			}

			// update list totals
			$difference = (int) $existsNew - (int) $existsOld;
			$this->updateCountCache( $list, $this->{static::getShardColumn()}, $difference );

			// check if sort has changed
			$sortChanged = false;
			foreach ( static::$sorts as $sort ) {
				if ( !isset( $old->{$sort} ) || !isset( $new->{$sort} ) || $old->{$sort} != $new->{$sort} ) {
					$sortChanged = true;
					break;
				}
			}

			// purge list cache
			if ( $difference != 0 || $sortChanged ) {
				static::uncacheList( $list, $this->{static::getShardColumn()} );
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
				// if nothing is cached, leave be; cache will rebuild when it's requested
				if ( $existingValue === false ) {
					return false;
				}

				// if count is in cache already, update it right away, avoiding any more DB reads
				return $existingValue + $difference;
			};

			// CAS new value, or - in case of failure - clear value to fallback to db value
			$result = static::getCache()->merge( $key, $callback );
			if ( $result === false ) {
				static::getCache()->delete( $key );
			}
		}

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
		if ( !static::getCache()->merge( $key, $callback, 1, 1 ) ) {
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
