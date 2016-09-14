<?php
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
