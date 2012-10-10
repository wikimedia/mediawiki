<?php
/**
 * This class represents an example datamodel entry.
 *
 * For example usage see unit tests
 * @see tests/phpunit/includes/dao/DataModelSampleTest.php
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 */
class DataModelSample extends DataModel {
	/**
	 * These are the exact columns an entry consists of in the DB.
	 *
	 * @var int|string
	 */
	public
		$ds_id,
		$ds_shard,
		$ds_title,
		$ds_email,
		$ds_visible,
		$ds_timestamp;

	/**
	 * Database table to hold the data.
	 *
	 * @see includes/dao/sql/datamodel_sample.sql
	 * @var string
	 */
	protected static $table = 'datamodel_sample';

	/**
	 * Name of column to act as unique id.
	 *
	 * @var string
	 */
	protected static $idColumn = 'ds_id';

	/**
	 * Name of column to shard data over.
	 *
	 * @var string
	 */
	protected static $shardColumn = 'ds_shard';

	/**
	 * All lists the data can be displayed as
	 *
	 * Key is the filter name, the value is an array of the conditions an "entry"
	 * must abide to to qualify for this list.
	 *
	 * @var array
	 */
	public static $lists = array(
		// all entries, sorted by title or timestamp
		'all' => array(),

		// all hidden entries, sorted by timestamp
		'hidden' => array( 'ds_visible = 0' ),

		// all visible entries, sorted by timestamp
		'visible' => array( 'ds_visible = 1' ),
	);

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
	public static $sorts = array(
		'title' => 'ds_title',
		'timestamp' => 'ds_timestamp'
	);

	/**
	 * Validate the entry's data.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function validate() {
		// make sure that, when set, the email address is valid
		if ( filter_var( $this->ds_email, FILTER_VALIDATE_EMAIL ) === false ) {
			throw new MWException( "Invalid email address ($this->ds_email) entered" );
		}

		return parent::validate();
	}

	/**
	 * Insert entry.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function insert() {
		// if no creation timestamp is entered yet, fill it out
		if ( $this->ds_timestamp === null ) {
			$this->ds_timestamp = wfTimestampNow();
		}

		return parent::insert();
	}
}
