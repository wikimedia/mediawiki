<?php
/**
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
namespace Wikimedia\Rdbms\Platform;

/**
 * @since 1.39
 * @see ISQLPlatform
 */
class MySQLPlatform extends SQLPlatform {
	/**
	 * MySQL uses `backticks` for identifier quoting instead of the sql standard "double quotes".
	 *
	 * @param string $s
	 * @return string
	 */
	public function addIdentifierQuotes( $s ) {
		// Characters in the range \u0001-\uFFFF are valid in a quoted identifier
		// Remove NUL bytes and escape backticks by doubling
		return '`' . str_replace( [ "\0", '`' ], [ '', '``' ], $s ) . '`';
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return strlen( $name ) && $name[0] == '`' && substr( $name, -1, 1 ) == '`';
	}

	public function buildStringCast( $field ) {
		return "CAST( $field AS BINARY )";
	}

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 */
	public function buildIntegerCast( $field ) {
		return 'CAST( ' . $field . ' AS SIGNED )';
	}

	protected function normalizeJoinType( string $joinType ) {
		switch ( strtoupper( $joinType ) ) {
			case 'STRAIGHT_JOIN':
			case 'STRAIGHT JOIN':
				return 'STRAIGHT_JOIN';

			default:
				return parent::normalizeJoinType( $joinType );
		}
	}

	/**
	 * @param string $index
	 * @return string
	 */
	public function useIndexClause( $index ) {
		return "FORCE INDEX (" . $this->indexName( $index ) . ")";
	}

	/**
	 * @param string $index
	 * @return string
	 */
	public function ignoreIndexClause( $index ) {
		return "IGNORE INDEX (" . $this->indexName( $index ) . ")";
	}
}
