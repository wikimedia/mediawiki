<?php
/**
 * Utility class.
 * @ingroup Database
 */
class DBObject {
	public $mData;

	function __construct( $data ) {
		$this->mData = $data;
	}

	function isLOB() {
		return false;
	}

	function data() {
		return $this->mData;
	}
}

/**
 * Utility class
 * @ingroup Database
 *
 * This allows us to distinguish a blob from a normal string and an array of strings
 */
class Blob {
	private $mData;

	function __construct( $data ) {
		$this->mData = $data;
	}

	function fetch() {
		return $this->mData;
	}
}

/**
 * Base for all database-specific classes representing information about database fields
 * @ingroup Database
 */
interface Field {
	/**
	 * Field name
	 * @return string
	 */
	function name();

	/**
	 * Name of table this field belongs to
	 * @return string
	 */
	function tableName();

	/**
	 * Database type
	 * @return string
	 */
	function type();

	/**
	 * Whether this field can store NULL values
	 * @return bool
	 */
	function isNullable();
}

/**
 * Result wrapper for grabbing data queried by someone else
 * @ingroup Database
 */
class ResultWrapper implements Iterator {
	var $db, $result, $pos = 0, $currentRow = null;

	/**
	 * Create a new result object from a result resource and a Database object
	 *
	 * @param DatabaseBase $database
	 * @param resource $result
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
	 * @return integer
	 */
	function numRows() {
		return $this->db->numRows( $this );
	}

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 *
	 * @return object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchObject() {
		return $this->db->fetchObject( $this );
	}

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form.  Fields are retrieved with $row['fieldname'].
	 *
	 * @return Array
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
	 * @param $row integer
	 */
	function seek( $row ) {
		$this->db->dataSeek( $this, $row );
	}

	/*********************
	 * Iterator functions
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

	function current() {
		if ( is_null( $this->currentRow ) ) {
			$this->next();
		}
		return $this->currentRow;
	}

	function key() {
		return $this->pos;
	}

	function next() {
		$this->pos++;
		$this->currentRow = $this->fetchObject();
		return $this->currentRow;
	}

	function valid() {
		return $this->current() !== false;
	}
}

/**
 * Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {
	var $result     = array();
	var $db         = null;	// And it's going to stay that way :D
	var $pos        = 0;
	var $currentRow = null;

	function __construct( $array ) {
		$this->result = $array;
	}

	/**
	 * @return int
	 */
	function numRows() {
		return count( $this->result );
	}

	function fetchRow() {
		if ( $this->pos < count( $this->result ) ) {
			$this->currentRow = $this->result[$this->pos];
		} else {
			$this->currentRow = false;
		}
		$this->pos++;
		return $this->currentRow;
	}

	function seek( $row ) {
		$this->pos = $row;
	}

	function free() {}

	// Callers want to be able to access fields with $this->fieldName
	function fetchObject() {
		$this->fetchRow();
		if ( $this->currentRow ) {
			return (object)$this->currentRow;
		} else {
			return false;
		}
	}

	function rewind() {
		$this->pos = 0;
		$this->currentRow = null;
	}

	function next() {
		return $this->fetchObject();
	}
}

/**
 * Used by DatabaseBase::buildLike() to represent characters that have special meaning in SQL LIKE clauses
 * and thus need no escaping. Don't instantiate it manually, use DatabaseBase::anyChar() and anyString() instead.
 */
class LikeMatch {
	private $str;

	/**
	 * Store a string into a LikeMatch marker object.
	 *
	 * @param String $s
	 */
	public function __construct( $s ) {
		$this->str = $s;
	}

	/**
	 * Return the original stored string.
	 *
	 * @return String
	 */
	public function toString() {
		return $this->str;
	}
}

/**
 * An object representing a master or slave position in a replicated setup.
 */
interface DBMasterPos {
}

