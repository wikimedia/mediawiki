<?php
/**
 * RDBStore implementation to power DataModel.
 *
 * RDBStore is currently discontinued, with no alternative (yet) on the horizon.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
class DataModelBackendRDBStore extends DataModelBackend {
	/**
	 * This is an expensive function; do not, ever, call this directly!
	 *
	 * Query to fetch entries from DB.
	 *
	 * @param string|array $id The id(s) to fetch
	 * @param int|array $shard The corresponding shard value(s)
	 * @param array[optional] $conds The conditions
	 * @return ResultWrapper
	 */
	public function get( $id, $shard, $options = array() ) {
		$id = (array) $id;
		$shard = (array) $shard;

		$data = array();

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( $this->table );

		// get all partitions corresponding to all shard values.
		$partitions = $store->getMultiplePartitions( $this->table, $this->shardColumn, array_unique( $shard ) );
		foreach ( $partitions as $partition ) {
			foreach ( $partition['values'] as $shard ) {
				$rows = $partition['partition']->select(
					DB_SLAVE,
					'*',
					array( $this->idColumn => $id, $this->shardColumn => $shard,  ),
					__METHOD__,
					$options
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
	public function getList( $name, $shard, $offset, $limit, $sort, $order ) {
		$key = $this->getListName( $name );

		// query conditions
		$conds['list'] = $key;
		$conds['sort'] = $sort;
		if ( $shard ) {
			$conds['shard'] = $shard;
		}

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_lists' );
		$partition = $store->getPartition( 'datamodel_lists', 'list', $key );

		// fetch the entry ids for the requested list
		return $partition->select(
			DB_SLAVE,
			array( 'id', 'shard' ),
			$conds,
			__METHOD__,
			array(
				'LIMIT' => $limit,
				'OFFSET' => $offset,
				'ORDER BY' => "sortvalue $order"
			)
		);
	}

	/**
	 * Get the amount of entries in a certain list.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @return array
	 */
	public function getCount( $name, $shard ) {
		$key = $this->getListName( $name );

		// query conditions
		$conds['list'] = $key;
		if ( $shard ) {
			$conds['shard'] = $shard;
		}

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_lists' );
		$partition = $store->getPartition( 'datamodel_lists', 'list', $key );

		// fetch the entry ids for the requested list
		return (int) $partition->selectField(
			DB_SLAVE,
			array( 'COUNT(id)' ),
			$conds,
			__METHOD__,
			array(
				'GROUP BY' => 'sort',
				'LIMIT' => 1
			)
		);
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
	 * Update an entry's presence & sort values in a certain list.
	 *
	 * @param DataModel $entry
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param array $sort Sort data [sort column => value]
	 * @return int
	 */
	public function updateListing( DataModel $entry, $name, array $sorts ) {
		$affected = 0;

		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_lists' );

		$key = $this->getListName( $name );
		$partition = $store->getPartition( 'datamodel_lists', 'list', $key );

		$store->begin();
		if ( !empty( $sorts ) ) {
			foreach ( $sorts as $sort => $sortValue ) {
				$affected += $partition->replace(
					array( 'list', 'id', 'sort' ),
					array(
						'list' => $key,
						'id' => (string) $entry->{$this->idColumn},
						'shard' => $entry->{$this->shardColumn},
						'sort' => $sort,
						'sortvalue' => $sortValue
					),
					__METHOD__
				);
			}
		} else {
			$affected = $partition->delete(
				array(
					'list' => $key,
					'id' => $entry->{$this->idColumn},
					'shard' => $entry->{$this->shardColumn}
				),
				__METHOD__
			);
		}
		$store->finish();

		return $affected;
	}
}
