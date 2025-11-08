<?php
/**
 * Help mocking DbQuoter interface, DO NOT use it in production.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Libs\Rdbms;

use Wikimedia\Rdbms\Blob;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\RawSQLValue;

class AddQuoterMock implements DbQuoter {
	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function addQuotes( $s ) {
		if ( $s instanceof RawSQLValue ) {
			return $s->toSql();
		}
		if ( $s instanceof Blob ) {
			$s = $s->fetch();
		}
		if ( $s === null ) {
			return 'NULL';
		} elseif ( is_bool( $s ) ) {
			return (string)(int)$s;
		} elseif ( is_int( $s ) ) {
			return (string)$s;
		} else {
			return "'" . str_replace( "'", "\'", $s ) . "'";
		}
	}
}
