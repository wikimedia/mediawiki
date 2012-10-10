<?php
/**
 * This class represents "a data entry".
 *
 * Note that a sharded database setup is supported and we'll heavily rely
 * on cache, mainly because of the ability to do cross-shard fetching,
 * which will result in multiple/all partitions being being looped for data,
 * which can & will get slow eventually. That in mind, we should take extra
 * care of thoughtful caching: not only should we scale well, we must maintain
 * high performance as well.
 *
 * Also note that I throw a lot of errors: I like to be verbose in what's wrong
 * rather than returning booleans or nulls when things break. It's equally easy
 * to try/catch for exceptions than it is to if/else return values, and the
 * exceptions provide a developer a more detailed explanation of what's wrong.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
abstract class DataModel {
	// @todo: datamodel_lists schema needs creation

	/**
	 * MUST BE EDITED BY EXTENDING CLASS
	 */

	/**
	 * Database table to hold the data
	 *
	 * @var string
	 */
	protected static $table;

	/**
	 * Name of column to act as unique id
	 *
	 * @var string
	 */
	protected static $idColumn;

	/**
	 * Name of column to shard data over
	 *
	 * @todo: at some point, we'll need to shard over multiple columns
	 *
	 * @var string
	 */
	protected static $shardColumn;

	/**
	 * All lists the data can be displayed as
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
			'order' => array()
		),
		// sample list that would:
		// * include no entries (condition will never evaluate to true: id won't be < 0)
		// * be sorted by entry id
		'none' => array(
			'conditions' => array( '$this->{static::getIdColumn()} < 0' ),
			'order' => array( '$this->{static::getIdColumn()}' )
		)
	*/ );


	/**
	 * CAN BE EDITED BY EXTENDING CLASS
	 */

	/**
	 * Pagination limit: how many entries should be fetched at once for lists
	 *
	 * @var int
	 */
	const LIST_LIMIT = 25;

	/**
	 * Validate the entry's data
	 *
	 * @return DataModel
	 */
	public function validate() {
		return $this;
	}

	/**
	 * Purge relevant Squid cache when updating data
	 *
	 * @return DataModel
	 */
	public function purgeSquidCache() {
		return $this;
	}

	/**
	 * Populate object's properties with database (ResultWrapper) data
	 *
	 * Assume that object properties & db columns are an exact match, if not
	 * the extending class can extend this method
	 *
	 * @param ResultWrapper $row The db row
	 * @return DataModel
	 */
	protected function toObject( $row ) {
		foreach ( $this->toArray() as $column => $value ) {
			$this->$column = $row->$column;
		}

		return $this;
	}

	/**
	 * Get array-representation of this object, ready for use by DB wrapper
	 *
	 * Assume that object properties & db columns are an exact match, if not
	 * the extending class can extend this method
	 *
	 * @return array
	 */
	protected function toArray() {
		return get_object_vars( $this );
	}


	/**
	 * PUBLICLY INTERESTING METHODS
	 */

	/**
	 * Fetch a data entry by its id & shard key
	 *
	 * @param int $id The id of the entry to fetch
	 * @param int $shard The shard key value
	 * @return DataModel
	 */
	public static function get( $id, $shard ) {
		global $wgMemc;

		$key = wfMemcKey( get_called_class(), 'get', $id, $shard );
		$entry = $wgMemc->get( $key );

		// when not found in cache, load data from DB
		if ( $entry === false ) {
			// conditions for fetching 1 single row:
			// WHERE [id-col] = [id-val] AND [shard-col] = [shard-val]
			$conds = array( static::getIdColumn() => $id, static::getShardColumn() => $shard );

			$storeGroup = RDBStoreGroup::singleton();
			$store = $storeGroup->getForTable( static::getTable() );
			$partition = $store->getPartition( static::getTable(), static::getShardColumn(), $shard );

			$row = static::getFromDB( $partition, $conds );
			if ( $row ) {
				$row = $row->fetchObject();
				if ( $row ) {
					$entry = static::loadFromRow( $row );
				}
			}
		}

		// (extend) cache
		if ( $entry ){
			$entry->cache();
		}

		return $entry;
	}

	/**
	 * Fetch a list of entries
	 *
	 * Strategy:
	 * Limit calls to the sharded data to only be on id & shard key (& index these)
	 * Other WHERE-clauses should be avoided. Instead, this "lists" concept will be used.
	 *
	 * A "list" could basically represent any query with a "special where clause", e.g.
	 * * "show all posts that have been oversighted" could be a list
	 * * "show all anonymous users" could be a list
	 * * ...
	 *
	 * We don't want to do this sort of queries on the source data table, because:
	 * * The data is sharded - cross-shard fetching is horrible
	 * * We would have to add a lot more column indexes to the source data
	 *
	 * Rather than executing these queries on the source data table, the CRUD-methods
	 * will perform these "list requirements" (basically the equivalent of WHERE-clause)
	 * in PHP and update the list entries (add/remove entries or move them around)
	 * These requirements can be defined on a per-list basis, at static::$lists
	 *
	 * These lists will be stored in the database, sharded by list name. This leaves us
	 * vulnerable to scalability issues in the event that one list would grow way out
	 * of proportion. Since the lists only contain id references though, the chance of
	 * this table hitting hardware limits are extremely small.
	 *
	 * These list results are then also saved in cache (in small chunks) so that commonly
	 * accessed lists don't overload the database.
	 *
	 * To keep reducing database connections/queries, we'll be slightly over-fetching
	 * data. Assuming one is requesting the first 25 entries, it is likely that the next
	 * 25 will be requested as well. We'll be fetching more than requested right away
	 * (since that's relatively cheap) and save this larger chunk to cache, which will
	 * enable us to do fewer queries when that data is finally requested.
	 *
	 * Note: one of the drawbacks of this approach is that a list can not be created
	 * "on the fly": we can't just apply a new SQL statement with a new WHERE-
	 * clause (because that's replaced by a PHP conditions). To add a new list when data
	 * already exists, a maintenance script will have to be run to re-populate that list.
	 *
	 * @todo: Currently still struggling to come up with a solution for data with a
	 * variable WHERE-clause (e.g. "show all users with a creation date under 30 days
	 * ago", where the exact value of "30 days ago" changes every second)
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed[optional] $shard Get only data for a certain shard value
	 * @param int[optional] $offset The offset to start from (a multiple of static::LIST_LIMIT)
	 * @param string[optional] $sort Sort the list ASC or DESC
	 * @return array
	 */
	public static function getList( $name, $shard = null, $offset = 0, $sort = 'ASC' ) {
		global $wgMemc;

		if ( !isset( static::$lists[$name] )) {
			throw new MWException( "List '$name' is no known list" );
		} elseif ( $offset % static::LIST_LIMIT != 0 ) {
			throw new MWException( 'Offset should be a multiple of ' . static::LIST_LIMIT );
		} elseif ( !in_array( $sort, array( 'ASC', 'DESC' ) ) ) {
			throw new MWException( 'Sort should be either ASC or DESC' );
		}

		// internal key to identify this exact list by
		$key = wfMemcKey( get_called_class(), 'getList', $name, $shard, $offset, $sort );

		// (try to) fetch list from cache
		$list = $wgMemc->get( $key );

		if ( $list === false ) {
			/*
			 * to reduce the amount of queries/connections to be performed on db,
			 * larger-than-requested chunks will be fetched & cached, waiting
			 * to be re-used at the next offset ;)
			 * e.g. for batches 0-25, 25-50, 50-75 & 75-100: $min will be 0
			 */
			$batchSize = static::LIST_LIMIT * 4;
			$min = floor( $offset / $batchSize ) * $batchSize;

			// fetch data from db & save results to cache
			$list = static::getListFromDB( $name, $shard, $min, $batchSize, $sort );
			$wgMemc->set( $key, $list, 60 * 60 );
		}

		/*
		 * $list now contains an array of [id] => [shard] entries
		 * we'll now want to fetch the actual feedback data for these entries
		 * some entries might already be cached, don't bother fetching those from db
		 */
		$missing = array();
		foreach ( $list as $id => $shard ) {
			$key = wfMemcKey( get_called_class(), 'get', $name, $shard, $offset );
			if ( $wgMemc->get( $key ) === false ) {
				/*
				 * while not encouraged, it is possible that a list should contain cross-shard
				 * data (e.g. simply a list of all entries) - separate all entries by shard
				 */
				$missing[$shard][] = $id;
			}
		}

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( static::getTable() );

		/*
		 * Get all partitions corresponding to all shard values we're missing.
		 *
		 * $missing now contains an array of [shard] => [array of ids] entries
		 * now go fetch the missing entries from the database all at once and
		 * cache them right away
		 */
		$partitions = $store->getMultiplePartitions( static::getTable(), static::getShardColumn(), array_keys( $missing ) );
		foreach ( $partitions as $partition ) {
			$ids = array();
			foreach ( $partition['values'] as $shard ) {
				$ids = array_merge( $ids, $missing[$shard] );
			}

			$conds = array(
				static::getIdColumn() => $ids
			);
			$rows = static::getFromDB( $partition['partition'], $conds );

			// we don't really care for the returned row but just want them cached
			foreach ( $rows as $row ) {
				$entry = static::loadFromRow( $row );
				$entry->cache();
			}
		}

		/*
		 * at this point, all entries should be in cache: splice the part we
		 * requested - if we got $list from the database, it still contains
		 * more entries than requested
		 */
		if ( count( $list ) > static::LIST_LIMIT ) {
			$list = array_splice( $list, $offset - $min, static::LIST_LIMIT );
		}

		foreach ( $list as $id => $shard ) {
			$list[$id] = static::get( $id, $shard );
		}

		return $list;
	}

	/**
	 * Get the amount of entries in a certain list
	 *
	 * This should pretty much always be cached:
	 * - it's only 1 int per list, so very cheap
	 * - when fetching from db, it required an aggregate function, so not so cheap
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
			$count = static::getCountFromDB( $name, $shard );
			$wgMemc->set( $key, $count );
		}

		return $count;
	}

	/**
	 * General save function - will figure out if insert/update should be performed
	 * based on the respective absence/presence of the unique id.
	 *
	 * @return DataModel
	 */
	public function save() {
		if ( $this->{static::getIdColumn()} === null ) {
			return $this->insert();
		} else {
			return $this->update();
		}
	}

	/**
	 * Insert entry into the DB (& cache)
	 *
	 * @return DataModel
	 */
	public function insert() {
		if ( $this->{static::getIdColumn()} !== null ) {
			throw new MWException( 'Entry has unique id (' . $this->{static::getIdColumn()} . ') already - did you intend to update rather than insert?' );
		}

		// get hold of entry shard
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( static::getTable() );
		$partition = $store->getPartition( static::getTable(), static::getShardColumn(), $this->{static::getShardColumn()} );

		// claim unique id for this entry
		$this->{static::getIdColumn()} = $this->generateId();

		// validate properties before saving them
		$this->validate();

		// insert data
		$result = $partition->insert(
			$this->toArray(),
			__METHOD__
		);

		if ( !$result ) {
			throw new MWException( 'Failed to insert new entry ' . $this->{static::getIdColumn()} );
		}

		// cache entry
		global $wgMemc;
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		$wgMemc->set( $key, $this, 60 * 60 );

		return $this
			// update this entry in all applicable lists
			->updateLists()
			// purge existing cache
			->purgeSquidCache();
	}

	/**
	 * Update entry in the DB (& cache)
	 *
	 * @return DataModel
	 */
	public function update() {
		if ( $this->{static::getIdColumn()} === null ) {
			throw new MWException( "Entry has no unique id yet - did you intend to insert rather than update?" );
		}

		// save a copy of the old object so we can update its listings later on
		$old = static::get( $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );

		// get hold of entry shard
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( static::getTable() );
		$partition = $store->getPartition( static::getTable(), static::getShardColumn(), $this->{static::getShardColumn()} );

		// validate properties before saving them
		$this->validate();

		/*
		 * rdbstore will do a sanity check to make sure that the shard column is
		 * not present in the SET-clause - shard column should always remain the
		 * same (another shard value could mean the data would need to be on
		 * another partition)
		 */
		$data = $this->toArray();
		unset( $data[static::getShardcolumn()] );

		// update data
		$result = $partition->update(
			$data,
			array(
				static::getIdColumn() => $this->{static::getIdColumn()},
				static::getShardColumn() => $this->{static::getShardColumn()}
			),
			__METHOD__
		);

		if ( !$result ) {
			throw new MWException( 'Failed to update entry ' . $this->{static::getIdColumn()} );
		}

		// update entry cache
		global $wgMemc;
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		$wgMemc->set( $key, $this, 60 * 60 );

		return $this
			// update this entry in all applicable lists
			->updateLists( $old )
			// purge existing cache
			->purgeSquidCache();
	}

	/**
	 * Get name of table to hold the data
	 *
	 * @return string
	 */
	public static function getTable() {
		if ( !static::$table ) {
			throw new MWException( 'No table name has been set in class ' . get_called_class() );
		}

		return static::$table;
	}

	/**
	 * Get name of column to act as unique id
	 *
	 * @return string
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
	 * Get name of column to shard data over
	 *
	 * @return string
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
	 * This method will determine whether or not an entry matches a certain list
	 *
	 * @param string $list The list name
	 * @return bool
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
				$order = '';
				if ( isset( $properties['order'] ) && $properties['order'] ) {
					eval( '$order = ' . (string) $properties['order'] . ';' );
				}

				$lists[$list] = $order;
			}
		}

		return $lists;
	}


	/**
	 * INTERNALS
	 */

	/**
	 * Update an entry's listing
	 *
	 * @param DataModel[optional] $old The pre-save entry, to compare lists with
	 * @return DataModel
	 */
	protected function updateLists( DataModel $old = null ) {
		$currentLists = array();
		if ( $old ) {
			$currentLists = $old->getMatchingLists();
		}

		$newLists = $this->getMatchingLists();

		foreach ( static::$lists as $list => $properties ) {
			$key = static::getListDBName( $list );

			$storeGroup = RDBStoreGroup::singleton();
			$store = $storeGroup->getForTable( 'datamodel_lists' );
			$partition = $store->getPartition( 'datamodel_lists', 'list', $key );

			$affected = 0;

			$existsNew = array_key_exists( $list, $newLists );
			$existsCurrent = array_key_exists( $list, $currentLists );

			// add to list (insert/update into db)
			if ( $existsNew ) {
				$affected = $partition->replace(
					array( 'list', 'id' ),
					array(
						'list' => $key,
						'id' => (string) $this->{static::getIdColumn()},
						'shard' => $this->{static::getShardColumn()},
						'sort' => $newLists[$list]
					),
					__METHOD__
				);

			// was present in old list but is not anymore: remove from db
			} elseif ( $existsCurrent ) {
				$affected = $partition->delete(
					array(
						'list' => $key,
						'id' => $this->{static::getIdColumn()},
						'shard' => $this->{static::getShardColumn()}
					),
					__METHOD__
				);
			}

			if ( $affected ) {
				// update list totals
				$difference = (int) $existsNew - (int) $existsCurrent;
				$this->updateCountCache( $list, $this->{static::getShardColumn()}, $difference );

				// purge list cache
				$this->purgeListCache( $list, $this->{static::getShardColumn()} );
			}
		}

		return $this;
	}

	/**
	 * Cache entry for an hour
	 *
	 * @return DataModel
	 */
	protected function cache() {
		global $wgMemc;
		$key = wfMemcKey( get_called_class(), 'get', $this->{static::getIdColumn()}, $this->{static::getShardColumn()} );
		$wgMemc->set( $key, $this, 60 * 60 );

		return $this;
	}

	/**
	 * Update count caches. This one we don't just want to purge
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard The shard value
	 * @param int $different The different to apply to current count
	 * @return DataModel
	 */
	protected function updateCountCache( $name, $shard, $difference ) {
		global $wgMemc;

		// update both shard-specific as well as general all-shard count
		foreach ( array( $shard, null ) as $shard ) {
			$class = get_called_class();
			$key = wfMemcKey( $class, 'getCount', $name, $shard );

			/**
			 * Callback method, updating the cached counts
			 *
			 * @param BagOStuff $cache
			 * @param string $key
			 * @param int $existingValue
			 * @use string $name The list name (see static::$lists)
			 * @use mixed $shard The shard value
			 * @use int $different The different to apply to current count
			 * @use string $class The called class
			 * @return int
			 */
			$callback = function( BagOStuff $cache, $key, $existingValue ) use ( $name, $shard, $difference, $class ) {
				// if cache is stale, get it from DB
				if ( $existingValue === false ) {
					return $class::getCount( $name, $shard );
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
	 * from the database all over again
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard The shard value
	 * @return DataModel
	 */
	protected function purgeListCache( $name, $shard ) {
		global $wgMemc;

		// clear both shard-specific as well as general all-shard list
		foreach ( array( $shard, null ) as $shard ) {
			$count = static::getCount( $name, $shard );

			// purge all list caches (these are relatively cheap to re-fetch from db)
			for ( $i = 0; $i <= $count; $i = $i + static::LIST_LIMIT ) {

				// purge both sort orders
				foreach ( array( 'ASC', 'DESC' ) as $sort ) {
					$key = wfMemcKey( get_called_class(), 'getList', $name, $shard, $i, $sort );
					$wgMemc->delete( $key );
				}
			}
		}

		return $this;
	}

	/**
	 * Build an entry from it's DB data
	 *
	 * @param ResultWrapper $row
	 * @return DataModel
	 */
	protected static function loadFromRow( $row ) {
		$entry = new static;
		$entry->toObject( $row );

		$entry->validate();

		return $entry;
	}

	/**
	 * Query to fetch entries from DB
	 *
	 * @param RDBStoreTablePartition $partition The partition to fetch the data from
	 * @param array $conds The conditions
	 * @return ResultWrapper
	 */
	protected static function getFromDB( $partition, $conds, $options = array() ) {
		$entry = new static;

		return $partition->select(
			DB_SLAVE,
			array_keys( $entry->toArray() ),
			$conds,
			__METHOD__,
			$options
		);
	}

	/**
	 * Fetch a list from the database
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @param int $offset The offset to start fetching entries from
	 * @param int $limit The amount of entries to fetch
	 * @param string $sort Sort the list ASC or DESC
	 * @return array
	 */
	protected static function getListFromDB( $name, $shard, $offset, $limit, $sort ) {
		$name = static::getListDBName( $name );

		// query conditions
		$conds['list'] = $name;
		if ( $shard ) {
			$conds['shard'] = $shard;
		}

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_lists' );
		$partition = $store->getPartition( 'datamodel_lists', 'list', $name );

		// fetch the entry ids for the requested list
		$entries = $partition->select(
			DB_SLAVE,
			array( 'id', 'shard' ),
			$conds,
			__METHOD__,
			array(
				'LIMIT' => $limit,
				'OFFSET' => $offset,
				'ORDER BY' => "sort $sort"
			)
		);

		if ( !$entries ) {
			return array();
		}

		// build [id] => [shard] array for requested part of list
		$list = array();
		foreach ( $entries as $entry ) {
			$list[$entry->id] = $entry->shard;
		}

		return $list;
	}

	/**
	 * Get the amount of entries in a certain list, from the database
	 *
	 * @param string $name The list name (see static::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @return array
	 */
	protected static function getCountFromDB( $name, $shard ) {
		// name should be unique per model implementation ;)
		$name = static::getListDBName( $name );

		// query conditions
		$conds['list'] = $name;
		if ( $shard ) {
			$conds['shard'] = $shard;
		}

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_lists' );
		$partition = $store->getPartition( 'datamodel_lists', 'list', $name );

		// fetch the entry ids for the requested list
		return (int) $partition->selectField(
			DB_SLAVE,
			array( 'COUNT(id)' ),
			$conds,
			__METHOD__
		);
	}

	/**
	 * Generate a new, unique id
	 *
	 * Data can be sharded over multiple servers, rendering database engine's
	 * auto-increment useless.
	 * We'll increment a new id in PHP. Rather than looping all servers to
	 * figure out the current highest id, we'll save it in memcached.
	 * To tackle possible concurrent inserts (which could generate in the same
	 * id), we'll perform a "check and set" to ensure the id's uniqueness.
	 *
	 * @return int
	 */
	protected function generateId() {
		global $wgMemc;
		$key = wfMemcKey( get_called_class(), 'getId' );
		$newId = 0;

		/**
		 * Callback method, incrementing the existing count
		 *
		 * @param BagOStuff $cache
		 * @param string $key
		 * @param int $existingValue
		 * @use int $newId
		 * @return int
		 */
		$class = get_called_class();
		$callback = function( BagOStuff $cache, $key, $existingValue ) use ( &$newId, $class ) {
			if ( $existingValue === false ) {
				$existingValue = $class::getIdFromDB();
			}
			$newId = (int) $existingValue + 1;
			return $newId;
		};

		// CAS new value, or - in case of failure - clear value to fallback to db value
		$result = $wgMemc->merge( $key, $callback );
		if ( $result === false ) {
			$wgMemc->delete( $key );
		}

		return $newId;
	}

	/**
	 * Due to it's bad performance (cause by looping all shards), this
	 * method should never be called upon directly. It is only meant as
	 * a last-resort in case the current max id can not be found in cache.
	 *
	 * @return int
	 */
	public static function getIdFromDB() {
		$maxId = 0;

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( static::getTable() );

		foreach ( $store->getAllPartitions( static::getTable(), static::getShardColumn() ) as $partition ) {
			$id = $partition->selectField(
				DB_SLAVE,
				array( static::getIdColumn() ),
				array(),
				__METHOD__,
				array(
					'ORDER BY' => static::getIdColumn() . ' DESC',
					'LIMIT' => 1
				)
			);

			$maxId = max( $maxId, $id );
		}

		return $maxId;
	}

	/**
	 * This DataModel class can be extended for multiple different implementations,
	 * which may have similar list $names.
	 * Since, for lists, the data is saved in a shared table, we'll need to make
	 * sure that a unique name is saved, which is why we'll prepend the list $name
	 * with the name of the extending class.
	 *
	 * @param string $name The list name (see static::$lists)
	 * @return string
	 */
	protected static function getListDBName( $name ) {
		return wfMemcKey( get_called_class(), $name );
	}
}
