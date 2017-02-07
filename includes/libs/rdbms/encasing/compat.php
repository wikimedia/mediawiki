<?php

/**
 * Temporary hack to alias global "Blob" class to Rdbms one with type-hint support
 * @todo: delete and move code to Blob.php
 * @deprecated 1.29
 */
class Blob implements Wikimedia\Rdbms\IBlob {
	/** @var string */
	protected $mData;

	/**
	 * @param $data string
	 */
	public function __construct( $data ) {
		$this->mData = $data;
	}

	public function fetch() {
		return $this->mData;
	}
}
