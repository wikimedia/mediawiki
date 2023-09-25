<?php
namespace Wikimedia\Rdbms\Database;

use Wikimedia\Rdbms\Blob;

/**
 * @internal
 */
interface DbQuoter {
	/**
	 * Escape and quote a raw value string for use in a SQL query
	 *
	 * @param string|int|float|null|bool|Blob $s
	 * @param-taint $s escapes_sql
	 * @return string
	 * @return-taint none
	 */
	public function addQuotes( $s );
}
