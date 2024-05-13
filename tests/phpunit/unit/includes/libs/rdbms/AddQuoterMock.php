<?php
/**
 * Help mocking DbQuoter interface, DO NOT use it in production.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
