<?php
/**
 * Result wrapper for grabbing data queried by someone else
 * @ingroup Database
 */
class ResultWrapper implements Iterator {
	/** @var resource */
	public $result;

	/** @var IDatabase */
	protected $db;

	/** @var int */
	protected $pos = 0;

	/** @var object|null */
	protected $currentRow = null;

	/**
	 * Create a new result object from a result resource and a Database object
	 *
	 * @param IDatabase $database
	 * @param resource|ResultWrapper $result
	 */
	function __construct( $database, $result ) {
		$this->db = $database;

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
	function numRows() {
		return $this->db->numRows( $this );
	}

	/**
	 * Fetch the next row from the given result object, in object form. Fields can be retrieved with
	 * $row->fieldname, with fields acting like member variables. If no more rows are available,
	 * false is returned.
	 *
	 * @return stdClass|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchObject() {
		return $this->db->fetchObject( $this );
	}

	/**
	 * Fetch the next row from the given result object, in associative array form. Fields are
	 * retrieved with $row['fieldname']. If no more rows are available, false is returned.
	 *
	 * @return array|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchRow() {
		return $this->db->fetchRow( $this );
	}

	/**
	 * Free a result object
	 */
	function free() {
		$this->db->freeResult( $this );
		unset( $this->result );
		unset( $this->db );
	}

	/**
	 * Change the position of the cursor in a result object.
	 * See mysql_data_seek()
	 *
	 * @param int $row
	 */
	function seek( $row ) {
		$this->db->dataSeek( $this, $row );
	}

	/*
	 * ======= Iterator functions =======
	 * Note that using these in combination with the non-iterator functions
	 * above may cause rows to be skipped or repeated.
	 */

	function rewind() {
		if ( $this->numRows() ) {
			$this->db->dataSeek( $this, 0 );
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

	/**
	 * @return bool
	 */
	function valid() {
		return $this->current() !== false;
	}
}
