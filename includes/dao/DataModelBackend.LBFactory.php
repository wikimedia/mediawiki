<?php
/**
 * "wfGetDB" implementation to power DataModel.
 *
 * This class will connect to a single database setup with master/slaves
 * architecture.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
class DataModelBackendLBFactory extends DataModelBackend {
	/**
	 * Wrapper function for wfGetDB.
	 *
	 * @param $db Integer: index of the connection to get. May be DB_MASTER for the
	 *            master (for write queries), DB_SLAVE for potentially lagged read
	 *            queries, or an integer >= 0 for a particular server.
	 * @param $groups Mixed: query groups. An array of group names that this query
	 *                belongs to. May contain a single string if the query is only
	 *                in one group.
	 * @param $wiki String: the wiki ID, or false for the current wiki
	 */
	protected function getDB( $db, $groups = array(), $wiki = false ) {
		return wfGetDB( $db, $groups, $wiki );
	}

	/**
	 * Query to fetch entries from DB.
	 *
	 * @param mixed $id The id(s) to fetch
	 * @param mixed $shard The corresponding shard value(s)
	 * @return ResultWrapper
	 */
	public function get( $id, $shard ) {
		// even if only 1 element (int|string), cast to array for consistent usage
		$id = !$id ? null : (array) $id;
		$shard = !$shard ? null : (array) $shard;

		// query conditions
		$conds = array();
		if ( $id ) {
			$conds[$this->idColumn] = $id;
		}
		if ( $shard ) {
			$conds[$this->shardColumn] = $shard;
		}

		return $this->getDB( DB_SLAVE )->select(
			$this->table,
			'*',
			$conds,
			__METHOD__,
			array()
		);
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

		// fetch the entry ids for the requested list
		return $this->getDB( DB_SLAVE )->select(
			'datamodel_lists',
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

		// fetch the entry ids for the requested list
		return (int) $this->getDB( DB_SLAVE )->selectField(
			'datamodel_lists',
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
		return $this->getDB( DB_MASTER )->insert(
			$this->table,
			$entry->toArray(),
			__METHOD__
		);
	}

	/**
	 * Update entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	public function update( DataModel $entry ) {
		$data = $entry->toArray();
		unset( $data[$this->shardColumn] );

		return $this->getDB( DB_MASTER )->update(
			$this->table,
			$data,
			array(
				$this->idColumn => $entry->{$this->idColumn},
				$this->shardColumn => $entry->{$this->shardColumn}
			),
			__METHOD__
		);
	}

	/**
	 * Delete entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	public function delete( DataModel $entry ) {
		return $this->getDB( DB_MASTER )->delete(
			$this->table,
			array(
				$this->idColumn => $entry->{$this->idColumn},
				$this->shardColumn => $entry->{$this->shardColumn}
			),
			__METHOD__
		);
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

		$key = $this->getListName( $name );

		if ( !empty( $sorts ) ) {
			foreach ( $sorts as $sort => $sortValue ) {
				$affected += $this->getDB( DB_MASTER )->replace(
					'datamodel_lists',
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
			$affected = $this->getDB( DB_MASTER )->delete(
				'datamodel_lists',
				array(
					'list' => $key,
					'id' => $entry->{$this->idColumn},
					'shard' => $entry->{$this->shardColumn}
				),
				__METHOD__
			);
		}

		return $affected;
	}
}
