<?php

namespace Wikimedia\Rdbms;

/**
 * @newable
 * @stable to extend
 */
class Blob implements IBlob {
	/** @var string */
	protected $data;

	/**
	 * @stable to call
	 * @param string $data
	 */
	public function __construct( $data ) {
		$this->data = $data;
	}

	public function fetch() {
		return $this->data;
	}
}
