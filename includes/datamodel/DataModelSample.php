<?php
/**
 * This class represents a sample datamodel entry, which is
 * backed by a sharded database setup and heavy cache usage.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 */
class DataModelSample extends DataModel {
	/**
	 * These are the exact columns an entry consists of in the DB
	 *
	 * @var int|string
	 */
	public
		$id,
		$shard,
		$title,
		$email,
		$visible,
		$timestamp;

	/**
	 * Database table to hold the data
	 *
	 * CREATE TABLE `datamodel_sample` (
	 *   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	 *   `shard` bigint(20) NOT NULL,
	 *   `title` varchar(255) NOT NULL DEFAULT '',
	 *   `email` varchar(255) DEFAULT NULL,
	 *   `visible` tinyint(1) NOT NULL,
	 *   `timestamp` binary(14) DEFAULT NULL,
	 *   PRIMARY KEY (`id`),
	 *   KEY `shard` (`shard`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	 *
	 * @var string
	 */
	protected static $table = 'datamodel_sample';

	/**
	 * Name of column to act as unique id
	 *
	 * @var string
	 */
	protected static $idColumn = 'id';

	/**
	 * Name of column to shard data over
	 *
	 * @var string
	 */
	protected static $shardColumn = 'shard';

	/**
	 * All lists the data can be displayed as
	 *
	 * Key is the filter name, the value is an array of
	 * * the conditions an "entry" must abide to to qualify for this list
	 * * the column to sort on
	 *
	 * @var array
	 */
	public static $lists = array(
		// all entries, sorted by title
		'all' => array(
			'conditions' => array(),
			'order' => '$this->title'
		),

		// all hidden entries, sorted by timestamp
		'hidden' => array(
			'conditions' => array( '!$this->visible' ),
			'order' => '$this->timestamp'
		),

		// all visible entries, sorted by timestamp
		'visible' => array(
			'conditions' => array( '$this->visible' ),
			'order' => '$this->timestamp'
		)
	);

	/**
	 * Validate the entry's data
	 *
	 * @return DataModel
	 */
	public function validate() {
		// make sure that, when set out, the email address is valid
		if ( filter_var( $this->email, FILTER_VALIDATE_EMAIL ) === false ) {
			throw new MWException( "Invalid email address ($this->email) entered" );
		}

		return parent::validate();
	}

	/**
	 * Insert entry into the DB (& cache)
	 *
	 * @return DataModel
	 */
	public function insert() {
		// if no creation timestamp is entered yet, fill it out
		if ( $this->timestamp === null ) {
			$storeGroup = RDBStoreGroup::singleton();
			$store = $storeGroup->getForTable( self::getTable() );
			$partition = $store->getPartition( self::getTable(), self::getShardColumn(), $this->{self::getShardColumn()} );

			$this->timestamp = $partition->getMasterDB()->timestamp( wfTimestampNow() );
		}

		parent::insert();
	}
}
