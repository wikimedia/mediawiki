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
		$id,
		$shard,
		$title,
		$email,
		$visible,
		$timestamp;

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
	protected static $idColumn = 'id';

	/**
	 * Name of column to shard data over.
	 *
	 * @var string
	 */
	protected static $shardColumn = 'shard';

	/**
	 * All lists the data can be displayed as.
	 *
	 * Key is the filter name, the value is an array containing:
	 * * the conditions an "entry" must abide to to qualify for this list
	 * * the data to be sorted on
	 *
	 * @var array
	 */
	public static $lists = array(
		// all entries, sorted by title or timestamp
		'all' => array(
			'conditions' => array(),
			'sort' => array( 'title' => '(string) $this->title', '(string) timestamp' => '$this->timestamp' )
		),

		// all hidden entries, sorted by timestamp
		'hidden' => array(
			'conditions' => array( '!$this->visible' ),
			'sort' => array( 'title' => '(string) $this->title', '(string) timestamp' => '$this->timestamp' )
		),

		// all visible entries, sorted by timestamp
		'visible' => array(
			'conditions' => array( '$this->visible' ),
			'sort' => array( 'title' => '(string) $this->title', '(string) timestamp' => '$this->timestamp' )
		)
	);

	/**
	 * Validate the entry's data.
	 *
	 * @return DataModel
	 * @throw MWException
	 */
	public function validate() {
		// make sure that, when set, the email address is valid
		if ( filter_var( $this->email, FILTER_VALIDATE_EMAIL ) === false ) {
			throw new MWException( "Invalid email address ($this->email) entered" );
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
		if ( $this->timestamp === null ) {
			$this->timestamp = wfTimestampNow();
		}

		return parent::insert();
	}
}
