<?php
/**
 * Result wrapper for grabbing data queried from an IDatabase object
 *
 * Note that using the Iterator methods in combination with the non-Iterator
 * DB result iteration functions may cause rows to be skipped or repeated.
 *
 * By default, this will use the iteration methods of the IDatabase handle if provided.
 * Subclasses can override methods to make it solely work on the result resource instead.
 * If no database is provided, and the subclass does not override the DB iteration methods,
 * then a RuntimeException will be thrown when iteration is attempted.
 *
 * The result resource field should not be accessed from non-Database related classes.
 * It is database class specific and is stored here to associate iterators with queries.
 *
 * @ingroup Database
 */
class ResultWrapper implements Iterator {
	/** @var resource|array|null Optional underlying result handle for subclass usage */
	public $result;

	/** @var IDatabase|null */
	protected $db;

	/** @var int */
	protected $pos = 0;
	/** @var stdClass|null */
	protected $currentRow = null;

	/**
	 * Create a row iterator from a result resource and an optional Database object
	 *
	 * Only Database-related classes should construct ResultWrapper. Other code may
	 * use the FakeResultWrapper subclass for convenience or compatibility shims, however.
	 *
	 * @param IDatabase|null $db Optional database handle
	 * @param ResultWrapper|array|resource $result Optional underlying result handle
	 */
	public function __construct( IDatabase $db = null, $result ) {
		$this->db = $db;
		if ( $result instanceof ResultWrapper ) {
			$this->result = $result->result;
		} else {
			$this->result = $result;
		}
	}

	/**
	 * Get the number of rows in a result object
	 *
	 * @return int
	 */
	public function numRows() {
		return $this->getDB()->numRows( $this );
	}

	/**
	 * Fetch the next row from the given result object, in object form. Fields can be retrieved with
	 * $row->fieldname, with fields acting like member variables. If no more rows are available,
	 * false is returned.
	 *
	 * @return stdClass|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchObject() {
		return $this->getDB()->fetchObject( $this );
	}

	/**
	 * Fetch the next row from the given result object, in associative array form. Fields are
	 * retrieved with $row['fieldname']. If no more rows are available, false is returned.
	 *
	 * @return array|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchRow() {
		return $this->getDB()->fetchRow( $this );
	}

	/**
	 * Change the position of the cursor in a result object.
	 * See mysql_data_seek()
	 *
	 * @param int $row
	 */
	public function seek( $row ) {
		$this->getDB()->dataSeek( $this, $row );
	}

	/**
	 * Free a result object
	 *
	 * This either saves memory in PHP (buffered queries) or on the server (unbuffered queries).
	 * In general, queries are not large enough in result sets for this to be worth calling.
	 */
	public function free() {
		if ( $this->db ) {
			$this->db->freeResult( $this );
			$this->db = null;
		}
		$this->result = null;
	}

	/**
	 * @return IDatabase
	 * @throws RuntimeException
	 */
	private function getDB() {
		if ( !$this->db ) {
			throw new RuntimeException( get_class( $this ) . ' needs a DB handle for iteration.' );
		}

		return $this->db;
	}

	function rewind() {
		if ( $this->numRows() ) {
			$this->getDB()->dataSeek( $this, 0 );
		}
		$this->pos = 0;
		$this->currentRow = null;
	}

	/**
	 * @return stdClass|array|bool
	 */
	function current() {
		if ( is_null( $this->currentRow ) ) {
			$this->next();
		}

		return $this->currentRow;
	}

	/**
	 * @return int
	 */
	function key() {
		return $this->pos;
	}

	/**
	 * @return stdClass
	 */
	function next() {
		$this->pos++;
		$this->currentRow = $this->fetchObject();

		return $this->currentRow;
	}

	function valid() {
		return $this->current() !== false;
	}
}
