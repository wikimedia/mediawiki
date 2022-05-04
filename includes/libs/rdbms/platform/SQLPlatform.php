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

use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\DBLanguageError;

/**
 * Sql abstraction object.
 * This class nor any of its subclasses shouldn't create a db connection.
 * It also should not become stateful. The constructor should only rely on addQuotes() method in Database.
 * Later that should be replaced with an implementation that doesn't use db connections.
 * @since 1.39
 */
class SQLPlatform implements ISQLPlatform {
	/** @var DbQuoter */
	protected $quoter;

	public function __construct( DbQuoter $quoter ) {
		$this->quoter = $quoter;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function bitNot( $field ) {
		return "(~$field)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function bitAnd( $fieldLeft, $fieldRight ) {
		return "($fieldLeft & $fieldRight)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function bitOr( $fieldLeft, $fieldRight ) {
		return "($fieldLeft | $fieldRight)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function addIdentifierQuotes( $s ) {
		return '"' . str_replace( '"', '""', $s ) . '"';
	}

	/**
	 * @inheritDoc
	 */
	public function buildGreatest( $fields, $values ) {
		return $this->buildSuperlative( 'GREATEST', $fields, $values );
	}

	/**
	 * @inheritDoc
	 */
	public function buildLeast( $fields, $values ) {
		return $this->buildSuperlative( 'LEAST', $fields, $values );
	}

	/**
	 * Build a superlative function statement comparing columns/values
	 *
	 * Integer and float values in $values will not be quoted
	 *
	 * If $fields is an array, then each value with a string key is treated as an expression
	 * (which must be manually quoted); such string keys do not appear in the SQL and are only
	 * descriptive aliases.
	 *
	 * @param string $sqlfunc Name of a SQL function
	 * @param string|string[] $fields Name(s) of column(s) with values to compare
	 * @param string|int|float|string[]|int[]|float[] $values Values to compare
	 * @return string
	 */
	protected function buildSuperlative( $sqlfunc, $fields, $values ) {
		$fields = is_array( $fields ) ? $fields : [ $fields ];
		$values = is_array( $values ) ? $values : [ $values ];

		$encValues = [];
		foreach ( $fields as $alias => $field ) {
			if ( is_int( $alias ) ) {
				$encValues[] = $this->addIdentifierQuotes( $field );
			} else {
				$encValues[] = $field; // expression
			}
		}
		foreach ( $values as $value ) {
			if ( is_int( $value ) || is_float( $value ) ) {
				$encValues[] = $value;
			} elseif ( is_string( $value ) ) {
				$encValues[] = $this->quoter->addQuotes( $value );
			} elseif ( $value === null ) {
				throw new DBLanguageError( 'Null value in superlative' );
			} else {
				throw new DBLanguageError( 'Unexpected value type in superlative' );
			}
		}

		return $sqlfunc . '(' . implode( ',', $encValues ) . ')';
	}

}
