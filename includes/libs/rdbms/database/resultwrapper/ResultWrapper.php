<?php

namespace Wikimedia\Rdbms;

use stdClass;
use RuntimeException;
use InvalidArgumentException;

/**
 * Result wrapper for grabbing data queried from an IDatabase object
 *
 * Only IDatabase-related classes should construct these. Other code may
 * use the FakeResultWrapper class for convenience or compatibility shims.
 *
 * Note that using the Iterator methods in combination with the non-Iterator
 * IDatabase result iteration functions may cause rows to be skipped or repeated.
 *
 * By default, this will use the iteration methods of the IDatabase handle if provided.
 * Subclasses can override methods to make it solely work on the result resource instead.
 *
 * @ingroup Database
 */
class ResultWrapper implements IResultWrapper {
	/** @var IDatabase */
	protected $db;
	/** @var mixed|null RDBMS driver-specific result resource */
	protected $result;

	/** @var int */
	protected $pos = 0;
	/** @var stdClass|bool|null */
	protected $currentRow;

	/**
	 * @param IDatabase $db Database handle that the result comes from
	 * @param self|mixed $result RDBMS driver-specific result resource
	 */
	public function __construct( IDatabase $db, $result ) {
		$this->db = $db;
		if ( $result instanceof self ) {
			$this->result = $result->result;
		} elseif ( $result !== null ) {
			$this->result = $result;
		} else {
			throw new InvalidArgumentException( "Null result resource provided" );
		}
	}

	/**
	 * Get the underlying RDBMS driver-specific result resource
	 *
	 * The result resource field should not be accessed from non-Database related classes.
	 * It is database class specific and is stored here to associate iterators with queries.
	 *
	 * @param self|mixed &$res
	 * @return mixed
	 * @since 1.34
	 */
	public static function &unwrap( &$res ) {
		if ( $res instanceof self ) {
			if ( $res->result === null ) {
				throw new RuntimeException( "The result resource was already freed" );
			}

			return $res->result;
		} else {
			return $res;
		}
	}

	public function numRows() {
		return $this->getDB()->numRows( $this );
	}

	public function fetchObject() {
		return $this->getDB()->fetchObject( $this );
	}

	public function fetchRow() {
		return $this->getDB()->fetchRow( $this );
	}

	public function seek( $pos ) {
		$this->getDB()->dataSeek( $this, $pos );
		$this->pos = $pos;
	}

	public function free() {
		$this->db = null;
		$this->result = null;
	}

	function rewind() {
		if ( $this->numRows() ) {
			$this->getDB()->dataSeek( $this, 0 );
		}
		$this->pos = 0;
		$this->currentRow = null;
	}

	function current() {
		if ( $this->currentRow === null ) {
			$this->currentRow = $this->fetchObject();
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

	/**
	 * @return IDatabase
	 * @throws RuntimeException
	 */
	private function getDB() {
		if ( !$this->db ) {
			throw new RuntimeException( "Database handle was already freed" );
		}

		return $this->db;
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( ResultWrapper::class, 'ResultWrapper' );
