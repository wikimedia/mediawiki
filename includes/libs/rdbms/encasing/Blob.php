<?php

namespace Wikimedia\Rdbms;

class Blob implements IBlob {
	/** @var string */
	protected $mData;

	/**
	 * @param string $data
	 */
	public function __construct( $data ) {
		$this->mData = $data;
	}

	public function fetch() {
		return $this->mData;
	}
}

class_alias( Blob::class, 'Blob' );
