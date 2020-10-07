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

/**
 * @deprecated since 1.29
 */
class_alias( Blob::class, 'Blob' );
