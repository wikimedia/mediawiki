<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Rdbms;

use Stringable;

/**
 * @ingroup Database
 */
class Subquery implements Stringable {
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
		return '(' . $this->sql . ')';
	}
}
