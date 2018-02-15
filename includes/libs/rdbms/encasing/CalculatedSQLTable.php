<?php

namespace Wikimedia\Rdbms;

class CalculatedSQLTable {
	/** @var string */
	private $sql;

	/**
	 * @param string $sql SQL query defining the table
	 */
	public function __construct( $sql ) {
		$this->sql = $sql;
	}

	/**
	 * @return string Original SQL query
	 */
	public function __toString() {
		return $this->sql;
	}
}
