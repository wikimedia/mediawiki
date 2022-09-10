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
	 * @return string
	 */
	public function addQuotes( $s );
}
