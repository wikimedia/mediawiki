<?php
/**
 * RDBStore implementation to power DataModel.
 *
 * Note: RDBStore is currently discontinued, with no alternative in sight (yet),
 * Which means that this code is pretty much useless and therefore unfinished.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
class DataModelBackendRDBStore extends DataModelBackend {
	/**
	 * @param string $datamodel
	 * @param string $table
	 * @param string $idColumn
	 * @param string $shardColumn
	 */
	public function __construct( $datamodel, $table, $idColumn, $shardColumn ) {
		throw new MWException( get_called_class().' is unfinished; use another DataModelbackend!' );
		return parent::__construct( $datamodel, $table, $idColumn, $shardColumn );
	}

	/**
	 * Query to fetch entries from DB.
	 *
	 * @param mixed $id The id(s) to fetch
	 * @param mixed $shard The corresponding shard value(s)
	 * @return ResultWrapper
	 */
	public function get( $id = null, $shard = null ) {
		// even if only 1 element (int|string), cast to array for consistent usage
		$id = !$id ? null : (array) $id;
		$shard = !$shard ? null : (array) $shard;

		$data = array();

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( $this->table );

		// get all partitions corresponding to all requested shard values
		if ( $shard !== null ) {
			$partitions = $store->getMultiplePartitions( $this->table, $this->shardColumn, array_unique( $shard ) );

		// get partitions for all shards
		} else {
			$partitions = array();
			foreach ( $store->getAllPartitions( $this->table, $this->shardColumn ) as $partition ) {
				$partitions[] = array( 'partition' => $partition, 'values' => array( null ) );
			}
		}

		foreach ( $partitions as $partition ) {
			// query conditions
			$conds = array();
			if ( $id ) {
				$conds[$this->idColumn] = $id;
			}
			if ( $shard ) {
				$conds[$this->shardColumn] = $shard;
			}

			foreach ( $partition['values'] as $shard ) {
				$rows = $partition['partition']->select(
					DB_SLAVE,
					'*',
					$conds,
					__METHOD__,
					array()
				);

				foreach ( $rows as $row ) {
					$data[] = $row;
				}
			}
		}

		return new FakeResultWrapper( $data );
	}

	/**
	 * Fetch a list.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @param int $offset The offset to start fetching entries from
	 * @param int $limit The amount of entries to fetch
	 * @param string $sort Sort to apply to list
	 * @param string $order Sort the list ASC or DESC
	 * @return ResultWrapper
	 */
	public function getList( $name, $shard = null, $offset = null, $limit, $sort = null, $order ) {
		/*
		 * @todo: this was outdated; re-implementation is pretty easy, can be
		 * based on DataModelBackend.RDBStore. All of that logic can be used,
		 * keeping in mind that entry_shard is the shard key and all servers
		 * (in the case of $shard null of $shard as array) have to be looped
		 * and results combined in the right order and everything after $limit
		 * should be cut off.
		 */
	}

	/**
	 * Get the amount of entries in a certain list.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @return array
	 */
	public function getCount( $name, $shard = null ) {
		/*
		 * @todo: this was outdated; re-implementation is pretty easy, can be
		 * based on DataModelBackend.RDBStore. All of that logic can be used,
		 * keeping in mind that entry_shard is the shard key and all servers
		 * (in the case of $shard null of $shard as array) have to be looped
		 * and results combined.
		 */
	}

	/**
	 * Insert entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	public function insert( DataModel $entry ) {
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( $this->table );
		$partition = $store->getPartition( $this->table, $this->shardColumn, $entry->{$this->shardColumn} );

		$store->begin();
		$affected = $partition->insert(
			$entry->toArray(),
			__METHOD__
		);
		$store->finish();

		return $affected;
	}

	/**
	 * Update entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	public function update( DataModel $entry ) {
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( $this->table );
		$partition = $store->getPartition( $this->table, $this->shardColumn, $entry->{$this->shardColumn} );

		/*
		 * rdbstore will do a sanity check to make sure that the shard column is
		 * not present in the SET-clause - shard column should always remain the
		 * same (another shard value could mean the data would need to be on
		 * another partition)
		 */
		$data = $entry->toArray();
		unset( $data[$this->shardColumn] );

		$store->begin();
		$affected = $partition->update(
			$data,
			array(
				$this->idColumn => $entry->{$this->idColumn},
				$this->shardColumn => $entry->{$this->shardColumn}
			),
			__METHOD__
		);
		$store->finish();

		return $affected;
	}

	/**
	 * Delete entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	public function delete( DataModel $entry ) {
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( $this->table );
		$partition = $store->getPartition( $this->table, $this->shardColumn, $entry->{$this->shardColumn} );

		$store->begin();
		$affected = $partition->delete(
			array(
				$this->idColumn => $entry->{$this->idColumn},
				$this->shardColumn => $entry->{$this->shardColumn}
			),
			__METHOD__
		);
		$store->finish();

		return $affected;
	}

	/**
	 * Update an entry's changed conditions.
	 *
	 * @param DataModel $entry
	 * @param array $conditions Conditions data [condition => match]
	 * @return int
	 */
	public function updateConditions( DataModel $entry, $conditions ) {
		$affected = 0;

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_conditions' );
		$partition = $store->getPartition( 'datamodel_conditions', 'entry_shard', $entry->{$this->shardColumn} );

		/*
		 * Even if an entry matches no condition at all, we want it to have an
		 * entry in datamodel_conditions to make it match lists that have
		 * no conditions defined ;)
		 */
		if ( empty( $conditions ) ) {
			$conditions['__none'] = true;
		}

		$store->begin();
		foreach ( $conditions as $condition => $match ) {
			if ( $match ) {
				// add new entries
				$affected += $partition->insert(
					array(
						'entry_table' => $this->table,
						'entry_id' => $entry->{$this->idColumn},
						'entry_shard' => $entry->{$this->shardColumn},
						'conditionmatch' => $condition
					),
					__METHOD__
				);
			} else {
				// delete existing entries
				$affected += $partition->delete(
					array(
						'entry_table' => $this->table,
						'entry_id' => $entry->{$this->idColumn},
						'entry_shard' => $entry->{$this->shardColumn},
						'conditionmatch' => $condition
					),
					__METHOD__
				);
			}
		}
		$store->finish();

		return $affected;
	}

	/**
	 * Update an entry's changed sort orders.
	 *
	 * @param DataModel $entry
	 * @param array $sorts Sort data [sort column => value]
	 * @return int
	 */
	public function updateSorts( DataModel $entry, $sorts ) {
		$affected = 0;

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_sorts' );
		$partition = $store->getPartition( 'datamodel_sorts', 'entry_shard', $entry->{$this->shardColumn} );

		$store->begin();
		foreach ( $sorts as $sort => $sortValue ) {
			$affected += $partition->replace(
				array( 'entry_table', 'entry_id', 'entry_shard', 'sort' ),
				array(
					'entry_table' => $this->table,
					'entry_id' => $entry->{$this->idColumn},
					'entry_shard' => $entry->{$this->shardColumn},
					'sort' => $sort,
					'sortvalue' => $sortValue
				),
				__METHOD__
			);
		}
		$store->finish();

		return $affected;
	}
}
