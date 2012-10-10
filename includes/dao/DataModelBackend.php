<?php
/**
 * The data backend to power DataModel, to save the real data to.
 *
 * DataModel was originally written with RDBStore - a class that would allow
 * data to be sharded over several servers - in mind. Datamodel would allow
 * for easy implementation of a sharded data structure, by automatically taking
 * care of some of the additional logic inherent of a sharded data structure,
 * like fetching multiple (perhaps cross-shard) entries at once, caching, ...
 *
 * RDBStore is currently discontinued, with no alternative (yet) on the horizon.
 * RDBStore-related code is pulled out of DataModel and turned it into a more
 * general class, so that other backends may use to power the DataModel class.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
abstract class DataModelBackend {
	/**
	 * Datamodel details
	 *
	 * @var string
	 */
	protected
		$table,
		$idColumn,
		$shardColumn;

	/**
	 * @param string $table
	 * @param string $idColumn
	 * @param string $shardColumn
	 */
	public function __construct( $table, $idColumn, $shardColumn ) {
		$this->table = $table;
		$this->idColumn = $idColumn;
		$this->shardColumn = $shardColumn;
	}

	/**
	 * Query to fetch entries from DB.
	 *
	 * @param string|array $id The id(s) to fetch
	 * @param int|array $shard The corresponding shard value(s)
	 * @param array[optional] $conds The conditions
	 * @return ResultWrapper
	 */
	abstract public function get( $id, $shard, $options = array() );

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
	abstract public function getList( $name, $shard, $offset, $limit, $sort, $order );

	/**
	 * Get the amount of entries in a certain list.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param mixed $shard Get only data for a certain shard value
	 * @return array
	 */
	abstract public function getCount( $name, $shard );

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
	 * Update an entry's presence & sort values in a certain list.
	 *
	 * @param DataModel $entry
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @param array $sort Sort data [sort column => value]
	 * @return int
	 */
	abstract public function updateListing( DataModel $entry, $name, array $sorts );

	/**
	 * This class can be extended for multiple different implementations,
	 * which may have similar list $names.
	 * Since, for lists, the data is saved in a shared table, we'll need to make
	 * sure that a unique name is saved, which is why we'll prepend the list $name
	 * with the name of the extending class.
	 *
	 * @param string $name The list name (see <datamodel>::$lists)
	 * @return string
	 */
	public function getListName( $name ) {
		return wfMemcKey( $this->table, $name );
	}
}
