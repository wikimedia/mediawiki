<?php

namespace Wikimedia\Rdbms;

use stdClass;

/**
 * Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {
	/** @var stdClass[]|array[] $result */

	/**
	 * @param stdClass[]|array[] $rows
	 */
	function __construct( array $rows ) {
		parent::__construct( null, $rows );
	}

	function numRows() {
		return count( $this->result );
	}

	function fetchObject() {
		$current = $this->current();

		$this->next();

		return $current;
	}

	function fetchRow() {
		$row = $this->valid() ? $this->result[$this->pos] : false;

		$this->next();

		return is_object( $row ) ? (array)$row : $row;
	}

	function seek( $pos ) {
		$this->pos = $pos;
	}

	function free() {
		$this->result = null;
	}

	function rewind() {
		$this->pos = 0;
	}

	function current() {
		$row = $this->valid() ? $this->result[$this->pos] : false;

		return is_array( $row ) ? (object)$row : $row;
	}

	function key() {
		return $this->pos;
	}

	function next() {
		$this->pos++;

		return $this->current();
	}

	function valid() {
		return array_key_exists( $this->pos, $this->result );
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( FakeResultWrapper::class, 'FakeResultWrapper' );
