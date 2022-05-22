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

use InvalidArgumentException;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Timestamp\ConvertibleTimestamp;

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

	public function makeList( array $a, $mode = self::LIST_COMMA ) {
		$first = true;
		$list = '';

		foreach ( $a as $field => $value ) {
			if ( $first ) {
				$first = false;
			} else {
				if ( $mode == self::LIST_AND ) {
					$list .= ' AND ';
				} elseif ( $mode == self::LIST_OR ) {
					$list .= ' OR ';
				} else {
					$list .= ',';
				}
			}

			if ( ( $mode == self::LIST_AND || $mode == self::LIST_OR ) && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( $mode == self::LIST_SET && is_numeric( $field ) ) {
				$list .= "$value";
			} elseif (
				( $mode == self::LIST_AND || $mode == self::LIST_OR ) && is_array( $value )
			) {
				// Remove null from array to be handled separately if found
				$includeNull = false;
				foreach ( array_keys( $value, null, true ) as $nullKey ) {
					$includeNull = true;
					unset( $value[$nullKey] );
				}
				if ( count( $value ) == 0 && !$includeNull ) {
					throw new InvalidArgumentException(
						__METHOD__ . ": empty input for field $field" );
				} elseif ( count( $value ) == 0 ) {
					// only check if $field is null
					$list .= "$field IS NULL";
				} else {
					// IN clause contains at least one valid element
					if ( $includeNull ) {
						// Group subconditions to ensure correct precedence
						$list .= '(';
					}
					if ( count( $value ) == 1 ) {
						// Special-case single values, as IN isn't terribly efficient
						// Don't necessarily assume the single key is 0; we don't
						// enforce linear numeric ordering on other arrays here.
						$value = array_values( $value )[0];
						$list .= $field . " = " . $this->quoter->addQuotes( $value );
					} else {
						$list .= $field . " IN (" . $this->makeList( $value ) . ") ";
					}
					// if null present in array, append IS NULL
					if ( $includeNull ) {
						$list .= " OR $field IS NULL)";
					}
				}
			} elseif ( $value === null ) {
				if ( $mode == self::LIST_AND || $mode == self::LIST_OR ) {
					$list .= "$field IS ";
				} elseif ( $mode == self::LIST_SET ) {
					$list .= "$field = ";
				}
				$list .= 'NULL';
			} else {
				if (
					$mode == self::LIST_AND || $mode == self::LIST_OR || $mode == self::LIST_SET
				) {
					$list .= "$field = ";
				}
				$list .= $mode == self::LIST_NAMES ? $value : $this->quoter->addQuotes( $value );
			}
		}

		return $list;
	}

	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
		$conds = [];

		foreach ( $data as $base => $sub ) {
			if ( count( $sub ) ) {
				$conds[] = $this->makeList(
					[ $baseKey => $base, $subKey => array_map( 'strval', array_keys( $sub ) ) ],
					self::LIST_AND
				);
			}
		}

		if ( $conds ) {
			return $this->makeList( $conds, self::LIST_OR );
		} else {
			// Nothing to search for...
			return false;
		}
	}

	public function factorConds( $condsArray ) {
		if ( count( $condsArray ) === 0 ) {
			throw new InvalidArgumentException(
				__METHOD__ . ": empty condition array" );
		}
		$condsByFieldSet = [];
		foreach ( $condsArray as $conds ) {
			if ( !count( $conds ) ) {
				throw new InvalidArgumentException(
					__METHOD__ . ": empty condition subarray" );
			}
			$fieldKey = implode( ',', array_keys( $conds ) );
			$condsByFieldSet[$fieldKey][] = $conds;
		}
		$result = '';
		foreach ( $condsByFieldSet as $conds ) {
			if ( $result !== '' ) {
				$result .= ' OR ';
			}
			$result .= $this->factorCondsWithCommonFields( $conds );
		}
		return $result;
	}

	/**
	 * Same as factorConds() but with each element in the array having the same
	 * set of array keys. Validation is done by the caller.
	 *
	 * @param array $condsArray
	 * @return string
	 */
	private function factorCondsWithCommonFields( $condsArray ) {
		$first = $condsArray[array_key_first( $condsArray )];
		if ( count( $first ) === 1 ) {
			// IN clause
			$field = array_key_first( $first );
			$values = [];
			foreach ( $condsArray as $conds ) {
				$values[] = $conds[$field];
			}
			return $this->makeList( [ $field => $values ], self::LIST_AND );
		}

		$field1 = array_key_first( $first );
		$nullExpressions = [];
		$expressionsByField1 = [];
		foreach ( $condsArray as $conds ) {
			$value1 = $conds[$field1];
			unset( $conds[$field1] );
			if ( $value1 === null ) {
				$nullExpressions[] = $conds;
			} else {
				$expressionsByField1[$value1][] = $conds;
			}

		}
		$wrap = false;
		$result = '';
		foreach ( $expressionsByField1 as $value1 => $expressions ) {
			if ( $result !== '' ) {
				$result .= ' OR ';
				$wrap = true;
			}
			$factored = $this->factorCondsWithCommonFields( $expressions );
			$result .= "($field1 = " . $this->quoter->addQuotes( $value1 ) .
				" AND $factored)";
		}
		if ( count( $nullExpressions ) ) {
			$factored = $this->factorCondsWithCommonFields( $nullExpressions );
			if ( $result !== '' ) {
				$result .= ' OR ';
				$wrap = true;
			}
			$result .= "($field1 IS NULL AND $factored)";
		}
		if ( $wrap ) {
			return "($result)";
		} else {
			return $result;
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildConcat( $stringList ) {
		return 'CONCAT(' . implode( ',', $stringList ) . ')';
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function limitResult( $sql, $limit, $offset = false ) {
		if ( !is_numeric( $limit ) ) {
			throw new DBLanguageError(
				"Invalid non-numeric limit passed to " . __METHOD__
			);
		}
		// This version works in MySQL and SQLite. It will very likely need to be
		// overridden for most other RDBMS subclasses.
		return "$sql LIMIT "
			. ( ( is_numeric( $offset ) && $offset != 0 ) ? "{$offset}," : "" )
			. "{$limit} ";
	}

	/**
	 * @stable to override
	 * @param string $s
	 * @param string $escapeChar
	 * @return string
	 */
	public function escapeLikeInternal( $s, $escapeChar = '`' ) {
		return str_replace(
			[ $escapeChar, '%', '_' ],
			[ "{$escapeChar}{$escapeChar}", "{$escapeChar}%", "{$escapeChar}_" ],
			$s
		);
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildLike( $param, ...$params ) {
		if ( is_array( $param ) ) {
			$params = $param;
		} else {
			$params = func_get_args();
		}

		$s = '';

		// We use ` instead of \ as the default LIKE escape character, since addQuotes()
		// may escape backslashes, creating problems of double escaping. The `
		// character has good cross-DBMS compatibility, avoiding special operators
		// in MS SQL like ^ and %
		$escapeChar = '`';

		foreach ( $params as $value ) {
			if ( $value instanceof LikeMatch ) {
				$s .= $value->toString();
			} else {
				$s .= $this->escapeLikeInternal( $value, $escapeChar );
			}
		}

		return ' LIKE ' .
			$this->quoter->addQuotes( $s ) . ' ESCAPE ' . $this->quoter->addQuotes( $escapeChar ) . ' ';
	}

	public function anyChar() {
		return new LikeMatch( '_' );
	}

	public function anyString() {
		return new LikeMatch( '%' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function unionSupportsOrderAndLimit() {
		return true; // True for almost every DB supported
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function unionQueries( $sqls, $all ) {
		$glue = $all ? ') UNION ALL (' : ') UNION (';

		return '(' . implode( $glue, $sqls ) . ')';
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function conditional( $cond, $caseTrueExpression, $caseFalseExpression ) {
		if ( is_array( $cond ) ) {
			$cond = $this->makeList( $cond, self::LIST_AND );
		}

		return "(CASE WHEN $cond THEN $caseTrueExpression ELSE $caseFalseExpression END)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function strreplace( $orig, $old, $new ) {
		return "REPLACE({$orig}, {$old}, {$new})";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function timestamp( $ts = 0 ) {
		$t = new ConvertibleTimestamp( $ts );
		// Let errors bubble up to avoid putting garbage in the DB
		return $t->getTimestamp( TS_MW );
	}

	public function timestampOrNull( $ts = null ) {
		if ( $ts === null ) {
			return null;
		} else {
			return $this->timestamp( $ts );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInfinity() {
		return 'infinity';
	}

	public function encodeExpiry( $expiry ) {
		return ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() )
			? $this->getInfinity()
			: $this->timestamp( $expiry );
	}

	public function decodeExpiry( $expiry, $format = TS_MW ) {
		if ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() ) {
			return 'infinity';
		}

		return ConvertibleTimestamp::convert( $format, $expiry );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildSubstring( $input, $startPosition, $length = null ) {
		$this->assertBuildSubstringParams( $startPosition, $length );
		$functionBody = "$input FROM $startPosition";
		if ( $length !== null ) {
			$functionBody .= " FOR $length";
		}
		return 'SUBSTRING(' . $functionBody . ')';
	}

	/**
	 * Check type and bounds for parameters to self::buildSubstring()
	 *
	 * All supported databases have substring functions that behave the same for
	 * positive $startPosition and non-negative $length, but behaviors differ when
	 * given negative $startPosition or negative $length. The simplest
	 * solution to that is to just forbid those values.
	 *
	 * @param int $startPosition
	 * @param int|null $length
	 * @since 1.31 in Database, moved to SQLPlatform in 1.39
	 */
	protected function assertBuildSubstringParams( $startPosition, $length ) {
		if ( $startPosition === 0 ) {
			// The DBMSs we support use 1-based indexing here.
			throw new InvalidArgumentException( 'Use 1 as $startPosition for the beginning of the string' );
		}
		if ( !is_int( $startPosition ) || $startPosition < 0 ) {
			throw new InvalidArgumentException(
				'$startPosition must be a positive integer'
			);
		}
		if ( !( is_int( $length ) && $length >= 0 || $length === null ) ) {
			throw new InvalidArgumentException(
				'$length must be null or an integer greater than or equal to 0'
			);
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildStringCast( $field ) {
		// In theory this should work for any standards-compliant
		// SQL implementation, although it may not be the best way to do it.
		return "CAST( $field AS CHARACTER )";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildIntegerCast( $field ) {
		return 'CAST( ' . $field . ' AS INTEGER )';
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function implicitOrderby() {
		return true;
	}
}
