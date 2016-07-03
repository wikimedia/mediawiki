<?php
/**
 * This file contains database-related utility classes.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Database
 */

/**
 * Utility class
 * @ingroup Database
 *
 * This allows us to distinguish a blob from a normal string and an array of strings
 */
class Blob {
	/** @var string */
	protected $mData;

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
	/** @var resource */
	public $result;

	/** @var DatabaseBase */
	protected $db;

	/** @var int */
	protected $pos = 0;

	/** @var object|null */
	protected $currentRow = null;

	/**
	 * Create a new result object from a result resource and a Database object
	 *
	 * @param DatabaseBase $database
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

/**
 * Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {
	/** @var array */
	public $result = [];

	/** @var null And it's going to stay that way :D */
	protected $db = null;

	/** @var int */
	protected $pos = 0;

	/** @var array|stdClass|bool */
	protected $currentRow = null;

	/**
	 * @param array $array
	 */
	function __construct( $array ) {
		$this->result = $array;
	}

	/**
	 * @return int
	 */
	function numRows() {
		return count( $this->result );
	}

	/**
	 * @return array|bool
	 */
	function fetchRow() {
		if ( $this->pos < count( $this->result ) ) {
			$this->currentRow = $this->result[$this->pos];
		} else {
			$this->currentRow = false;
		}
		$this->pos++;
		if ( is_object( $this->currentRow ) ) {
			return get_object_vars( $this->currentRow );
		} else {
			return $this->currentRow;
		}
	}

	function seek( $row ) {
		$this->pos = $row;
	}

	function free() {
	}

	/**
	 * Callers want to be able to access fields with $this->fieldName
	 * @return bool|stdClass
	 */
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

	/**
	 * @return bool|stdClass
	 */
	function next() {
		return $this->fetchObject();
	}
}

/**
 * Used by DatabaseBase::buildLike() to represent characters that have special
 * meaning in SQL LIKE clauses and thus need no escaping. Don't instantiate it
 * manually, use DatabaseBase::anyChar() and anyString() instead.
 */
class LikeMatch {
	/** @var string */
	private $str;

	/**
	 * Store a string into a LikeMatch marker object.
	 *
	 * @param string $s
	 */
	public function __construct( $s ) {
		$this->str = $s;
	}

	/**
	 * Return the original stored string.
	 *
	 * @return string
	 */
	public function toString() {
		return $this->str;
	}
}

/**
 * An object representing a master or slave position in a replicated setup.
 *
 * The implementation details of this opaque type are up to the database subclass.
 */
interface DBMasterPos {
	/**
	 * @return float UNIX timestamp
	 * @since 1.25
	 */
	public function asOfTime();

	/**
	 * @param DBMasterPos $pos
	 * @return bool Whether this position is at or higher than $pos
	 * @since 1.27
	 */
	public function hasReached( DBMasterPos $pos );

	/**
	 * @param DBMasterPos $pos
	 * @return bool Whether this position appears to be for the same channel as another
	 * @since 1.27
	 */
	public function channelsMatch( DBMasterPos $pos );

	/**
	 * @return string
	 * @since 1.27
	 */
	public function __toString();
}
