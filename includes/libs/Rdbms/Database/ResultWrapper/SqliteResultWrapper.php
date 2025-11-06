<?php

namespace Wikimedia\Rdbms;

use ArrayIterator;
use PDO;
use PDOStatement;

class SqliteResultWrapper extends ResultWrapper {
	/** @var PDOStatement|null */
	private $result;
	/** @var ArrayIterator|null */
	private $rows;

	/**
	 * @internal
	 * @param PDOStatement $result
	 */
	public function __construct( PDOStatement $result ) {
		$this->result = $result;
		// SQLite doesn't allow buffered results or data seeking etc, so we'll
		// use fetchAll. PDO has PDO::CURSOR_SCROLL but the SQLite C API doesn't
		// support it, so the driver raises an error if it is used.
		$this->rows = $result->fetchAll( PDO::FETCH_OBJ );
	}

	/** @inheritDoc */
	protected function doNumRows() {
		return count( $this->rows );
	}

	/** @inheritDoc */
	protected function doFetchObject() {
		return $this->rows[$this->currentPos] ?? false;
	}

	/** @inheritDoc */
	protected function doFetchRow() {
		$obj = $this->doFetchObject();
		if ( is_object( $obj ) ) {
			$i = 0;
			$row = get_object_vars( $obj );
			foreach ( $row as $value ) {
				$row[$i++] = $value;
			}
			return $row;
		} else {
			return $obj;
		}
	}

	/** @inheritDoc */
	protected function doSeek( $pos ) {
		// Nothing to do -- parent updates $this->currentPos
	}

	/** @inheritDoc */
	protected function doFree() {
		$this->rows = null;
		$this->result = null;
	}

	/** @inheritDoc */
	protected function doGetFieldNames() {
		if ( $this->rows ) {
			return array_keys( get_object_vars( $this->rows[0] ) );
		} else {
			return [];
		}
	}
}
