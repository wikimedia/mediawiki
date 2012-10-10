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
	public function get( $id = null, $shard = null ) {
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
		$dbr = $this->getDB( DB_SLAVE );

		// "where" alternative
		$conditions = $this->getListConditions( $name );
		list( $conditionsTables, $conditionsVars, $conditionsConds, $conditionsOptions, $conditionsJoinConds ) = $this->buildConditions( $conditions );

		// "order by" alternative
		list( $sortsTables, $sortsVars, $sortsConds, $sortsOptions, $sortsJoinConds ) = $this->buildSorts( $sort, $order, $offset );

		$tables = $conditionsTables + $sortsTables;
		$vars = $conditionsVars + $sortsVars;
		$conds = $conditionsConds + $sortsConds;
		$options = $conditionsOptions + $sortsOptions;
		$join_conds = $conditionsJoinConds + $sortsJoinConds;

		$tables[] = $this->table;

		$tableName = $dbr->tableName( $this->table );
		$vars['entry_id'] = "$tableName.$this->idColumn";
		$vars['entry_shard'] = "$tableName.$this->shardColumn";
		$vars['entry_offset'] = isset( $vars['entry_offset'] ) ? 'CONCAT_WS("|", '.$vars['entry_offset'].", $tableName.$this->idColumn)" : "$tableName.$this->idColumn";

		/*
		 * We're not really sharding now, so we can take the easy way;
		 * when really sharding & attempting to fetch cross-shard, multiple
		 * servers will have to be queried & the results combined ;)
		 */
		if ( $shard ) {
			$conds["$tableName.$this->shardColumn"] = $shard;
		}

		$options['LIMIT'] = $limit;

		// fetch the entry ids for the requested list
		return $dbr->select(
			$tables,
			$vars,
			$conds,
			__METHOD__,
			$options,
			$join_conds
		);
	}

	/**
	 * Get the amount of entries in a certain list.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @return array
	 */
	public function getCount( $name, $shard = null ) {
		$dbr = $this->getDB( DB_SLAVE );

		// "where" alternative
		$conditions = $this->getListConditions( $name );
		list( $tables, $vars, $conds, $options, $join_conds ) = $this->buildConditions( $conditions );

		$tables[] = $this->table;

		$tableName = $dbr->tableName( $this->table );
		$vars[] = "COUNT($tableName.$this->idColumn)";

		$options['LIMIT'] = 1;

		// fetch the amount of entries for the requested list
		$row = $this->getDB( DB_SLAVE )->select(
			$tables,
			$vars,
			$conds,
			__METHOD__,
			$options,
			$join_conds
		);

		/*
		 * selectField is not an option since it does not accept $join_conds;
		 * perform regular select & extract value ourselves
		 */
		if ( $row !== false && $row->numRows() ) {
			$result = $row->fetchRow();
			return reset( $result );
		}

		return 0;
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

		foreach ( $conditions as $condition => $match ) {
			if ( $match ) {
				// add new entries
				$affected += $this->getDB( DB_MASTER )->replace(
					'datamodel_conditions',
					array( 'entry_table', 'entry_id', 'entry_shard', 'conditionmatch' ),
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
				$affected += $this->getDB( DB_MASTER )->delete(
					'datamodel_conditions',
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

		foreach ( $sorts as $sort => $sortValue ) {
			/*
			 * Different data types = different sorts.
			 * E.g. int(57) > int(8), but string("57") < string("8")
			 */
			$type = gettype( $sortValue ) == 'string' ? 'string' : 'number';

			$affected += $this->getDB( DB_MASTER )->replace(
				'datamodel_sorts',
				array( 'entry_table', 'entry_id', 'entry_shard', 'sort' ),
				array(
					'entry_table' => $this->table,
					'entry_id' => $entry->{$this->idColumn},
					'entry_shard' => $entry->{$this->shardColumn},
					'sort' => $sort,
					'sortvalue_string' => ( $type == 'string' ? $sortValue : null ),
					'sortvalue_number' => ( $type == 'number' ? $sortValue : null )
				),
				__METHOD__
			);
		}

		return $affected;
	}

	/**
	 * Build parts of the select-query related to datamodel_conditions column.
	 *
	 * @param array $conditions
	 * @return array Query details: tables, vars, conds, options, join_conds
	 */
	protected function buildConditions( $conditions ) {
		$dbr = $this->getDB( DB_SLAVE );
		$tableName = $dbr->tableName( $this->table );

		$tables = array();
		$vars = array();
		$conds = array();
		$join_conds = array();
		$options = array();

		foreach ( $conditions as $i => $condition ) {
			$tables["condition$i"] = 'datamodel_conditions';

			$join_conds["condition$i"] = array(
				'INNER JOIN',
				array(
					"condition$i.entry_table" => $this->table,
					"condition$i.entry_id = $tableName.$this->idColumn",
					"condition$i.entry_shard = $tableName.$this->shardColumn",
					"condition$i.conditionmatch" => $condition
				)
			);
		}

		return array( $tables, $vars, $conds, $options, $join_conds );
	}

	/**
	 * Build parts of the select-query related to datamodel_sorts column.
	 *
	 * @param string $sort
	 * @param string $order
	 * @param string $offset
	 * @return array Query details: tables, vars, conds, options, join_conds
	 */
	protected function buildSorts( $sort = null, $order, $offset = null ) {
		$dbr = $this->getDB( DB_SLAVE );
		$tableName = $dbr->tableName( $this->table );

		// interpret $offset
		$sortOffset = null;
		$idOffset = null;
		if ( $offset ) {
			$offset = explode( '|', $offset ); // [sortvalue]|[id] or [id]
			if ( count( $offset ) > 1 ) {
				$sortOffset = $dbr->addQuotes( $offset[0] );
				$idOffset = $dbr->addQuotes( $offset[1] );
			} else {
				$idOffset = $dbr->addQuotes( $offset[0] );
			}
		}

		$tables = array();
		$vars = array();
		$conds = array();
		$join_conds = array();
		$options = array();

		// if sort defined, use sort table
		if ( $sort != null ) {
			$tables['sorts'] = 'datamodel_sorts';

			$vars['entry_offset'] = 'CONCAT_WS("", sorts.sortvalue_string, sorts.sortvalue_number)';

			$conds['sorts.sort'] = $sort;

			$join_conds['sorts'] = array(
				'LEFT JOIN',
				array(
					"sorts.entry_table" => $this->table,
					"sorts.entry_id = $tableName.$this->idColumn",
					"sorts.entry_shard = $tableName.$this->shardColumn",
					// when sort-offset is defined, it'll be added here - see code below
				)
			);

			$options['ORDER BY'] = array(
				"sorts.sortvalue_string $order",
				"sorts.sortvalue_number $order",
				"sorts.entry_id ASC"
			);

			if ( $sortOffset ) {
				// sort offset defined; add to join condition
				$direction = $order == 'ASC' ? '>' : '<';
				$join_conds['sorts'][1][] = "
					(sorts.sortvalue_string $direction $sortOffset AND sorts.sortvalue_number IS NULL) OR
					(sorts.sortvalue_string IS NULL AND sorts.sortvalue_number $direction $sortOffset) OR
					(sorts.sortvalue_string = $sortOffset AND sorts.sortvalue_number IS NULL AND sorts.entry_id >= $idOffset) OR
					(sorts.sortvalue_string IS NULL AND sorts.sortvalue_number = $sortOffset AND sorts.entry_id >= $idOffset)";
			}

		/*
		 * If no sort is defined, just sort by id. Id is completely random, so
		 * using it as a "sort" makes no sense (if you do want a sort to make
		 * sense, define one...), but we need some logic to make sure that when
		 * fetching the next batch, we do fetch exactly what is not yet being
		 * shown, so we need to order by something to know how the slices are cut
		 */
		} else {
			$options['ORDER BY'] = array(
				"$tableName.$this->idColumn ASC"
			);

			if ( $sort === null && $idOffset ) {
				// no sort-offset defined (no sort exists); add id-offset to where
				$conds[] = "$tableName.$this->idColumn >= $idOffset";
			}
		}

		return array( $tables, $vars, $conds, $options, $join_conds );
	}
}
