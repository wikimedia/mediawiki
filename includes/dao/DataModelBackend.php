<?php
/**
 * The data backend to power DataModel, to save the real data to.
 *
 * DataModel was originally developed with RDBStore - a class that would allow
 * data to be sharded over several servers - in mind. DataModel would allow
 * for easy implementation of a sharded data structure, by automatically taking
 * care of some of the additional logic inherent of a sharded data structure,
 * like fetching multiple (perhaps cross-shard) entries at once, caching, ...
 *
 * RDBStore is currently discontinued, with no alternative (yet) on the horizon.
 * RDBStore-related code is pulled out of DataModel and turned it into a more
 * general class, so that other backends may be used to power the DataModel class.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
abstract class DataModelBackend {
	/**
	 * DataModel details.
	 *
	 * @var string
	 */
	protected
		$datamodel,
		$table,
		$idColumn,
		$shardColumn;

	/**
	 * @param string $datamodel
	 * @param string $table
	 * @param string $idColumn
	 * @param string $shardColumn
	 */
	public function __construct( $datamodel, $table, $idColumn, $shardColumn ) {
		$this->datamodel = $datamodel;
		$this->table = $table;
		$this->idColumn = $idColumn;
		$this->shardColumn = $shardColumn;
	}

	/**
	 * Query to fetch entries from DB.
	 *
	 * @param mixed $id The id(s) to fetch
	 * @param mixed $shard The corresponding shard value(s)
	 * @return ResultWrapper
	 */
	abstract public function get( $id = null, $shard = null );

	/**
	 * Insert entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	abstract public function insert( DataModel $entry );

	/**
	 * Update entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	abstract public function update( DataModel $entry );

	/**
	 * Delete entry.
	 *
	 * @param DataModel $entry
	 * @return int
	 */
	abstract public function delete( DataModel $entry );

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
	abstract public function getList( $name, $shard = null, $offset = null, $limit, $sort = null, $order );

	/**
	 * Get the amount of entries in a certain list.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @return array
	 */
	abstract public function getCount( $name, $shard = null );

	/**
	 * Update an entry's conditions.
	 *
	 * @param DataModel $entry
	 * @param array $conditions Conditions data [condition => match]
	 * @return int
	 */
	abstract public function updateConditions( DataModel $entry, $conditions );

	/**
	 * Update an entry's sort orders.
	 *
	 * @param DataModel $entry
	 * @param array $sorts Sort data [sort column => value]
	 * @return int
	 */
	abstract public function updateSorts( DataModel $entry, $sorts );

	/**
	 * For a given list name, this will fetch the list's conditions.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @return string
	 */
	public function getListConditions( $name ) {
		$class = $this->datamodel;

		$conditions = array();
		if( isset( $class::$lists[$name]['conditions'] ) ) {
			$conditions = $class::$lists[$name]['conditions'];
		}

		if ( empty( $conditions ) ) {
			$conditions = array();
		}

		return $conditions;
	}
}
