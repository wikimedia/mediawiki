<?php

namespace Wikimedia\Rdbms;

use stdClass;

/**
 * Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper implements IResultWrapper {
	/** @var stdClass[]|array[] */
	protected $result;

	/** @var int */
	protected $pos = 0;

	/**
	 * @param stdClass[]|array[]|FakeResultWrapper $result
	 */
	public function __construct( $result ) {
		if ( $result instanceof self ) {
			$this->result = $result->result;
		} else {
			$this->result = $result;
		}
	}

	public function numRows() {
		return count( $this->result );
	}

	public function fetchObject() {
		$current = $this->current();

		$this->next();

		return $current;
	}

	public function fetchRow() {
		$row = $this->valid() ? $this->result[$this->pos] : false;

		$this->next();

		return is_object( $row ) ? get_object_vars( $row ) : $row;
	}

	public function seek( $pos ) {
		$this->pos = $pos;
	}

	public function free() {
		$this->result = null;
	}

	public function rewind() {
		$this->pos = 0;
	}

	public function current() {
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		$row = $this->valid() ? $this->result[$this->pos] : false;

		return is_array( $row ) ? (object)$row : $row;
	}

	public function key() {
		return $this->pos;
	}

	public function next() {
		$this->pos++;

		return $this->current();
	}

	public function valid() {
		return array_key_exists( $this->pos, $this->result );
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( FakeResultWrapper::class, 'FakeResultWrapper' );
