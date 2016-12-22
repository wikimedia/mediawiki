<?php
/**
 * Utility class
 * @ingroup Database
 *
 * Allows us to tell the DB abstraction layer we really want a number.
 */
class RawDBNumber {
	/** @var int */
	protected $mData;

	/**
	 * @param int $data A number
	 * @throws InvalidArgumentException If given something not numericish
	 */
	public function __construct( $data ) {
		if ( !is_numeric( $data ) ) {
			throw new InvalidArgumentException( "Expected a number" );
		}
		$this->mData = (int)$data;
	}

	/**
	 * @return string The number in a form suitable to embed in sql.
	 */
	public function fetch() {
		return (string)$this->mData;
	}

	public function __toString() {
		return fetch();
	}
}
