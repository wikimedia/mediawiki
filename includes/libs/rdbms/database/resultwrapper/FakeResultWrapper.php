<?php

namespace Wikimedia\Rdbms;

use RuntimeException;
use stdClass;

/**
 * Overloads the relevant methods of the real ResultWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {
	/** @var stdClass[]|array[]|null */
	protected $result;

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

	protected function doNumRows() {
		return count( $this->result );
	}

	protected function doFetchObject() {
		$value = $this->result[$this->currentPos] ?? false;
		return is_array( $value ) ? (object)$value : $value;
	}

	protected function doFetchRow() {
		$row = $this->doFetchObject();
		return is_object( $row ) ? get_object_vars( $row ) : $row;
	}

	protected function doSeek( $pos ) {
	}

	protected function doFree() {
		$this->result = null;
	}

	protected function doGetFieldNames() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new RuntimeException( __METHOD__ . ' is unimplemented' );
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( FakeResultWrapper::class, 'FakeResultWrapper' );
