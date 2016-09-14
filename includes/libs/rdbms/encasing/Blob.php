<?php
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
