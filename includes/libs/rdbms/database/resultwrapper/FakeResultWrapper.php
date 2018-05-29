<?php

namespace Wikimedia\Rdbms;

use stdClass;

/**
 * Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {
	/** @var stdClass[] $result */

	/**
	 * @param stdClass[] $rows
	 */
	function __construct( array $rows ) {
		parent::__construct( null, $rows );
	}

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
 * @deprecated since 1.29
 */
class_alias( FakeResultWrapper::class, 'FakeResultWrapper' );
