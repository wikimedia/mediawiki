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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\Query;
use Wikimedia\Rdbms\QueryBuilderFromRawSql;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\Subquery;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Sql abstraction object.
 * This class nor any of its subclasses shouldn't create a db connection.
 * It also should not become stateful. The constructor should only rely on addQuotes() method in Database.
 * Later that should be replaced with an implementation that doesn't use db connections.
 * @since 1.39
 */
class SQLPlatform implements ISQLPlatform {
	/** @var array[] Current map of (table => (dbname, schema, prefix) map) */
	protected $tableAliases = [];
	protected DatabaseDomain $currentDomain;
	/** @var array|null Current variables use for schema element placeholders */
	protected $schemaVars;
	protected DbQuoter $quoter;
	protected LoggerInterface $logger;
	/** @var callable Error logging callback */
	protected $errorLogger;

	/**
	 * @param DbQuoter $quoter
	 * @param ?LoggerInterface $logger
	 * @param ?DatabaseDomain $currentDomain
	 * @param callable|null $errorLogger
	 */
	public function __construct(
		DbQuoter $quoter,
		?LoggerInterface $logger = null,
		?DatabaseDomain $currentDomain = null,
		$errorLogger = null
	) {
		$this->quoter = $quoter;
		$this->logger = $logger ?? new NullLogger();
		$this->currentDomain = $currentDomain ?? DatabaseDomain::newUnspecified();
		$this->errorLogger = $errorLogger ?? static function ( Throwable $e ) {
			trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
		};
	}

	public function bitNot( $field ) {
		return "(~$field)";
	}

	public function bitAnd( $fieldLeft, $fieldRight ) {
		return "($fieldLeft & $fieldRight)";
	}

	public function bitOr( $fieldLeft, $fieldRight ) {
		return "($fieldLeft | $fieldRight)";
	}

	public function addIdentifierQuotes( $s ) {
		if ( strcspn( $s, "\0\"`'." ) !== strlen( $s ) ) {
			throw new DBLanguageError(
				"Identifier must not contain quote, dot or null characters: got '$s'"
			);
		}
		$quoteChar = $this->getIdentifierQuoteChar();
		return $quoteChar . $s . $quoteChar;
	}

	/**
	 * Get the character used for identifier quoting
	 * @return string
	 */
	protected function getIdentifierQuoteChar() {
		return '"';
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

	public function buildComparison( string $op, array $conds ): string {
		if ( !in_array( $op, [ '>', '>=', '<', '<=' ] ) ) {
			throw new InvalidArgumentException( "Comparison operator must be one of '>', '>=', '<', '<='" );
		}
		if ( count( $conds ) === 0 ) {
			throw new InvalidArgumentException( "Empty input" );
		}

		// Construct a condition string by starting with the least significant part of the index, and
		// adding more significant parts progressively to the left of the string.
		//
		// For example, given $conds = [ 'a' => 4, 'b' => 7, 'c' => 1 ], this will generate a condition
		// like this:
		//
		//   WHERE  a > 4
		//      OR (a = 4 AND (b > 7
		//                 OR (b = 7 AND (c > 1))))
		//
		// …which is equivalent to the following, which might be easier to understand:
		//
		//   WHERE a > 4
		//      OR a = 4 AND b > 7
		//      OR a = 4 AND b = 7 AND c > 1
		//
		// …and also equivalent to the following, using tuple comparison syntax, which is most intuitive
		// but apparently performs worse:
		//
		//   WHERE (a, b, c) > (4, 7, 1)

		$sql = '';
		foreach ( array_reverse( $conds ) as $field => $value ) {
			if ( is_int( $field ) ) {
				throw new InvalidArgumentException(
					'Non-associative array passed to buildComparison() (typo?)'
				);
			}
			$encValue = $this->quoter->addQuotes( $value );
			if ( $sql === '' ) {
				$sql = "$field $op $encValue";
				// Change '>=' to '>' etc. for remaining fields, as the equality is handled separately
				$op = rtrim( $op, '=' );
			} else {
				$sql = "$field $op $encValue OR ($field = $encValue AND ($sql))";
			}
		}
		return $sql;
	}

	public function makeList( array $a, $mode = self::LIST_COMMA ) {
		$first = true;
		$list = '';
		$keyWarning = null;

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
				if ( $value instanceof IExpression ) {
					$list .= "(" . $value->toSql( $this->quoter ) . ")";
				} elseif ( is_array( $value ) ) {
					throw new InvalidArgumentException( __METHOD__ . ": unexpected array value without key" );
				} elseif ( $value instanceof RawSQLValue ) {
					throw new InvalidArgumentException( __METHOD__ . ": unexpected raw value without key" );
				} else {
					$list .= "($value)";
				}
			} elseif ( $value instanceof IExpression ) {
				if ( $mode == self::LIST_AND || $mode == self::LIST_OR ) {
					throw new InvalidArgumentException( __METHOD__ . ": unexpected key $field for IExpression value" );
				} else {
					throw new InvalidArgumentException( __METHOD__ . ": unexpected IExpression outside WHERE clause" );
				}
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
						// (but call makeList() so that warnings are emitted if needed)
						$list .= $field . " = " . $this->makeList( $value );
					} else {
						$list .= $field . " IN (" . $this->makeList( $value ) . ") ";
					}
					// if null present in array, append IS NULL
					if ( $includeNull ) {
						$list .= " OR $field IS NULL)";
					}
				}
			} elseif ( is_array( $value ) ) {
				throw new InvalidArgumentException( __METHOD__ . ": unexpected nested array" );
			} elseif ( $value === null ) {
				if ( $mode == self::LIST_AND || $mode == self::LIST_OR ) {
					$list .= "$field IS ";
				} elseif ( $mode == self::LIST_SET ) {
					$list .= "$field = ";
				} elseif ( $mode === self::LIST_COMMA && !is_numeric( $field ) ) {
					$keyWarning ??= [
						__METHOD__ . ": array key {key} in list of values ignored",
						[ 'key' => $field, 'exception' => new RuntimeException() ]
					];
				} elseif ( $mode === self::LIST_NAMES && !is_numeric( $field ) ) {
					$keyWarning ??= [
						__METHOD__ . ": array key {key} in list of fields ignored",
						[ 'key' => $field, 'exception' => new RuntimeException() ]
					];
				}
				$list .= 'NULL';
			} else {
				if (
					$mode == self::LIST_AND || $mode == self::LIST_OR || $mode == self::LIST_SET
				) {
					$list .= "$field = ";
				} elseif ( $mode === self::LIST_COMMA && !is_numeric( $field ) ) {
					$keyWarning ??= [
						__METHOD__ . ": array key {key} in list of values ignored",
						[ 'key' => $field, 'exception' => new RuntimeException() ]
					];
				} elseif ( $mode === self::LIST_NAMES && !is_numeric( $field ) ) {
					$keyWarning ??= [
						__METHOD__ . ": array key {key} in list of fields ignored",
						[ 'key' => $field, 'exception' => new RuntimeException() ]
					];
				}
				$list .= $mode == self::LIST_NAMES ? $value : $this->quoter->addQuotes( $value );
			}
		}

		if ( $keyWarning ) {
			// Only log one warning about this per function call, to reduce log spam when a dynamically
			// generated associative array is passed
			$this->logger->warning( ...$keyWarning );
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

		if ( !$conds ) {
			throw new InvalidArgumentException( "Data for $baseKey and $subKey must be non-empty" );
		}

		return $this->makeList( $conds, self::LIST_OR );
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
	 */
	public function buildConcat( $stringList ) {
		return 'CONCAT(' . implode( ',', $stringList ) . ')';
	}

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

	public function buildLike( $param, ...$params ) {
		if ( is_array( $param ) ) {
			$params = $param;
			$param = array_shift( $params );
		}
		$likeValue = new LikeValue( $param, ...$params );

		return ' LIKE ' . $likeValue->toSql( $this->quoter );
	}

	public function anyChar() {
		return new LikeMatch( '_' );
	}

	public function anyString() {
		return new LikeMatch( '%' );
	}

	/**
	 * @inheritDoc
	 */
	public function unionSupportsOrderAndLimit() {
		return true; // True for almost every DB supported
	}

	public function unionQueries( $sqls, $all, $options = [] ) {
		$glue = $all ? ') UNION ALL (' : ') UNION (';

		$sql = '(' . implode( $glue, $sqls ) . ')';
		if ( !$this->unionSupportsOrderAndLimit() ) {
			return $sql;
		}
		$sql .= $this->makeOrderBy( $options );
		$limit = $options['LIMIT'] ?? null;
		$offset = $options['OFFSET'] ?? false;
		if ( $limit !== null ) {
			$sql = $this->limitResult( $sql, $limit, $offset );
		}

		return $sql;
	}

	public function conditional( $cond, $caseTrueExpression, $caseFalseExpression ) {
		if ( is_array( $cond ) ) {
			$cond = $this->makeList( $cond, self::LIST_AND );
		}
		if ( $cond instanceof IExpression ) {
			$cond = $cond->toSql( $this->quoter );
		}

		return "(CASE WHEN $cond THEN $caseTrueExpression ELSE $caseFalseExpression END)";
	}

	public function strreplace( $orig, $old, $new ) {
		return "REPLACE({$orig}, {$old}, {$new})";
	}

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
		if ( !( ( is_int( $length ) && $length >= 0 ) || $length === null ) ) {
			throw new InvalidArgumentException(
				'$length must be null or an integer greater than or equal to 0'
			);
		}
	}

	public function buildStringCast( $field ) {
		// In theory this should work for any standards-compliant
		// SQL implementation, although it may not be the best way to do it.
		return "CAST( $field AS CHARACTER )";
	}

	public function buildIntegerCast( $field ) {
		return 'CAST( ' . $field . ' AS INTEGER )';
	}

	public function implicitOrderby() {
		return true;
	}

	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	/**
	 * @return array[]
	 */
	public function getTableAliases() {
		return $this->tableAliases;
	}

	public function setPrefix( $prefix ) {
		$this->currentDomain = new DatabaseDomain(
			$this->currentDomain->getDatabase(),
			$this->currentDomain->getSchema(),
			$prefix
		);
	}

	public function setCurrentDomain( DatabaseDomain $currentDomain ) {
		$this->currentDomain = $currentDomain;
	}

	/**
	 * @internal For use by tests
	 * @return DatabaseDomain
	 */
	public function getCurrentDomain() {
		return $this->currentDomain;
	}

	public function selectSQLText(
		$tables, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( !is_array( $tables ) ) {
			if ( $tables === '' || $tables === null || $tables === false ) {
				$tables = [];
			} elseif ( is_string( $tables ) ) {
				$tables = [ $tables ];
			} else {
				throw new DBLanguageError( __METHOD__ . ' called with incorrect table parameter' );
			}
		}

		if ( is_array( $vars ) ) {
			$fields = implode( ',', $this->fieldNamesWithAlias( $vars ) );
		} else {
			$fields = $vars;
		}

		$options = (array)$options;

		$useIndexByTable = $options['USE INDEX'] ?? [];
		if ( !is_array( $useIndexByTable ) ) {
			if ( count( $tables ) <= 1 ) {
				$useIndexByTable = [ reset( $tables ) => $useIndexByTable ];
			} else {
				$e = new DBLanguageError( __METHOD__ . " got ambiguous USE INDEX ($fname)" );
				( $this->errorLogger )( $e );
			}
		}

		$ignoreIndexByTable = $options['IGNORE INDEX'] ?? [];
		if ( !is_array( $ignoreIndexByTable ) ) {
			if ( count( $tables ) <= 1 ) {
				$ignoreIndexByTable = [ reset( $tables ) => $ignoreIndexByTable ];
			} else {
				$e = new DBLanguageError( __METHOD__ . " got ambiguous IGNORE INDEX ($fname)" );
				( $this->errorLogger )( $e );
			}
		}

		if (
			$this->selectOptionsIncludeLocking( $options ) &&
			$this->selectFieldsOrOptionsAggregate( $vars, $options )
		) {
			// Some DB types (e.g. postgres) disallow FOR UPDATE with aggregate
			// functions. Discourage use of such queries to encourage compatibility.
			$this->logger->warning(
				__METHOD__ . ": aggregation used with a locking SELECT ($fname)"
			);
		}

		if ( count( $tables ) ) {
			$from = ' FROM ' . $this->tableNamesWithIndexClauseOrJOIN(
				$tables,
				$useIndexByTable,
				$ignoreIndexByTable,
				$join_conds
			);
		} else {
			$from = '';
		}

		[ $startOpts, $preLimitTail, $postLimitTail ] = $this->makeSelectOptions( $options );

		if ( is_array( $conds ) ) {
			$where = $this->makeList( $conds, self::LIST_AND );
		} elseif ( $conds instanceof IExpression ) {
			$where = $conds->toSql( $this->quoter );
		} elseif ( $conds === null || $conds === false ) {
			$where = '';
			$this->logger->warning(
				__METHOD__
				. ' called from '
				. $fname
				. ' with incorrect parameters: $conds must be a string or an array',
				[ 'db_log_category' => 'sql' ]
			);
		} elseif ( is_string( $conds ) ) {
			$where = $conds;
		} else {
			throw new DBLanguageError( __METHOD__ . ' called with incorrect parameters' );
		}

		// Keep historical extra spaces after FROM to avoid testing failures
		if ( $where === '' || $where === '*' ) {
			$sql = "SELECT $startOpts $fields $from   $preLimitTail";
		} else {
			$sql = "SELECT $startOpts $fields $from   WHERE $where $preLimitTail";
		}

		if ( isset( $options['LIMIT'] ) ) {
			$sql = $this->limitResult( $sql, $options['LIMIT'], $options['OFFSET'] ?? false );
		}
		$sql = "$sql $postLimitTail";

		if ( isset( $options['EXPLAIN'] ) ) {
			$sql = 'EXPLAIN ' . $sql;
		}

		if (
			$fname === static::CALLER_UNKNOWN ||
			str_starts_with( $fname, 'Wikimedia\\Rdbms\\' ) ||
			$fname === '{closure}'
		) {
			$exception = new RuntimeException();

			// Try to figure out and report the real caller
			$caller = '';
			foreach ( $exception->getTrace() as $call ) {
				if ( str_ends_with( $call['file'] ?? '', 'Test.php' ) ) {
					// Don't warn when called directly by test code, adding callers there is pointless
					break;
				} elseif ( str_starts_with( $call['class'] ?? '', 'Wikimedia\\Rdbms\\' ) ) {
					// Keep looking for the caller of a rdbms method
				} elseif ( str_ends_with( $call['class'] ?? '', 'SelectQueryBuilder' ) ) {
					// Keep looking for the caller of any custom SelectQueryBuilder
				} else {
					// Warn about the external caller we found
					$caller = implode( '::', array_filter( [ $call['class'] ?? null, $call['function'] ] ) );
					break;
				}
			}

			if ( $fname === '{closure}' ) {
				// Someone did ->caller( __METHOD__ ) in a local function, e.g. in a callback to
				// getWithSetCallback(), MWCallableUpdate or doAtomicSection(). That's not very helpful.
				// Provide a more specific message. The caller has to be provided like this:
				//   $method = __METHOD__;
				//   function ( ... ) use ( $method ) { ... }
				$warning = "SQL query with incorrect caller (__METHOD__ used inside a closure: {caller}): {sql}";
			} else {
				$warning = "SQL query did not specify the caller (guessed caller: {caller}): {sql}";
			}

			$this->logger->warning(
				$warning,
				[ 'sql' => $sql, 'caller' => $caller, 'exception' => $exception ]
			);
		}

		return $sql;
	}

	/**
	 * @param string|array $options
	 * @return bool
	 */
	private function selectOptionsIncludeLocking( $options ) {
		$options = (array)$options;
		foreach ( [ 'FOR UPDATE', 'LOCK IN SHARE MODE' ] as $lock ) {
			if ( in_array( $lock, $options, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array|string $fields
	 * @param array|string $options
	 * @return bool
	 */
	private function selectFieldsOrOptionsAggregate( $fields, $options ) {
		foreach ( (array)$options as $key => $value ) {
			if ( is_string( $key ) ) {
				if ( preg_match( '/^(?:GROUP BY|HAVING)$/i', $key ) ) {
					return true;
				}
			} elseif ( is_string( $value ) ) {
				if ( preg_match( '/^(?:DISTINCT|DISTINCTROW)$/i', $value ) ) {
					return true;
				}
			}
		}

		$regex = '/^(?:COUNT|MIN|MAX|SUM|GROUP_CONCAT|LISTAGG|ARRAY_AGG)\s*\\(/i';
		foreach ( (array)$fields as $field ) {
			if ( is_string( $field ) && preg_match( $regex, $field ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets an array of aliased field names
	 *
	 * @param array $fields [ [alias] => field ]
	 * @return string[] See fieldNameWithAlias()
	 */
	protected function fieldNamesWithAlias( $fields ) {
		$retval = [];
		foreach ( $fields as $alias => $field ) {
			if ( is_numeric( $alias ) ) {
				$alias = $field;
			}
			$retval[] = $this->fieldNameWithAlias( $field, $alias );
		}

		return $retval;
	}

	/**
	 * Get an aliased field name
	 * e.g. fieldName AS newFieldName
	 *
	 * @param string $name Field name
	 * @param string|false $alias Alias (optional)
	 * @return string SQL name for aliased field. Will not alias a field to its own name
	 */
	public function fieldNameWithAlias( $name, $alias = false ) {
		if ( !$alias || (string)$alias === (string)$name ) {
			return $name;
		} else {
			return $name . ' AS ' . $this->addIdentifierQuotes( $alias ); // PostgreSQL needs AS
		}
	}

	/**
	 * Get the aliased table name clause for a FROM clause
	 * which might have a JOIN and/or USE INDEX or IGNORE INDEX clause
	 *
	 * @param array $tables Array of ([alias] => table reference)
	 * @param array $use_index Same as for select()
	 * @param array $ignore_index Same as for select()
	 * @param array $join_conds Same as for select()
	 * @return string
	 */
	protected function tableNamesWithIndexClauseOrJOIN(
		$tables,
		$use_index = [],
		$ignore_index = [],
		$join_conds = []
	) {
		$ret = [];
		$retJOIN = [];
		$use_index = (array)$use_index;
		$ignore_index = (array)$ignore_index;
		$join_conds = (array)$join_conds;

		foreach ( $tables as $alias => $table ) {
			if ( !is_string( $alias ) ) {
				// No alias? Set it equal to the table name
				$alias = $table;
			}

			if ( is_array( $table ) ) {
				// A parenthesized group
				if ( count( $table ) > 1 ) {
					$joinedTable = '(' .
						$this->tableNamesWithIndexClauseOrJOIN(
							$table, $use_index, $ignore_index, $join_conds ) . ')';
				} else {
					// Degenerate case
					$innerTable = reset( $table );
					$innerAlias = key( $table );
					$joinedTable = $this->tableNameWithAlias(
						$innerTable,
						is_string( $innerAlias ) ? $innerAlias : $innerTable
					);
				}
			} else {
				$joinedTable = $this->tableNameWithAlias( $table, $alias );
			}

			// Is there a JOIN clause for this table?
			if ( isset( $join_conds[$alias] ) ) {
				Assert::parameterType( 'array', $join_conds[$alias], "join_conds[$alias]" );
				[ $joinType, $conds ] = $join_conds[$alias];
				$tableClause = $this->normalizeJoinType( $joinType );
				$tableClause .= ' ' . $joinedTable;
				if ( isset( $use_index[$alias] ) ) { // has USE INDEX?
					$use = $this->useIndexClause( implode( ',', (array)$use_index[$alias] ) );
					if ( $use != '' ) {
						$tableClause .= ' ' . $use;
					}
				}
				if ( isset( $ignore_index[$alias] ) ) { // has IGNORE INDEX?
					$ignore = $this->ignoreIndexClause(
						implode( ',', (array)$ignore_index[$alias] ) );
					if ( $ignore != '' ) {
						$tableClause .= ' ' . $ignore;
					}
				}
				$on = $this->makeList( (array)$conds, self::LIST_AND );
				if ( $on != '' ) {
					$tableClause .= ' ON (' . $on . ')';
				}

				$retJOIN[] = $tableClause;
			} elseif ( isset( $use_index[$alias] ) ) {
				// Is there an INDEX clause for this table?
				$tableClause = $joinedTable;
				$tableClause .= ' ' . $this->useIndexClause(
						implode( ',', (array)$use_index[$alias] )
					);

				$ret[] = $tableClause;
			} elseif ( isset( $ignore_index[$alias] ) ) {
				// Is there an INDEX clause for this table?
				$tableClause = $joinedTable;
				$tableClause .= ' ' . $this->ignoreIndexClause(
						implode( ',', (array)$ignore_index[$alias] )
					);

				$ret[] = $tableClause;
			} else {
				$tableClause = $joinedTable;

				$ret[] = $tableClause;
			}
		}

		// We can't separate explicit JOIN clauses with ',', use ' ' for those
		$implicitJoins = implode( ',', $ret );
		$explicitJoins = implode( ' ', $retJOIN );

		// Compile our final table clause
		return implode( ' ', [ $implicitJoins, $explicitJoins ] );
	}

	/**
	 * Validate and normalize a join type
	 *
	 * Subclasses may override this to add supported join types.
	 *
	 * @param string $joinType
	 * @return string
	 */
	protected function normalizeJoinType( string $joinType ) {
		switch ( strtoupper( $joinType ) ) {
			case 'JOIN':
			case 'INNER JOIN':
				return 'JOIN';

			case 'LEFT JOIN':
				return 'LEFT JOIN';

			case 'STRAIGHT_JOIN':
			case 'STRAIGHT JOIN':
				// MySQL only
				return 'JOIN';

			default:
				return $joinType;
		}
	}

	/**
	 * Get an aliased table name
	 *
	 * This returns strings like "tableName AS newTableName" for aliased tables
	 * and "(SELECT * from tableA) newTablename" for subqueries (e.g. derived tables)
	 *
	 * @see Database::tableName()
	 * @param string|Subquery $table The unqualified name of a table, or Subquery
	 * @param string|false $alias Table alias (optional)
	 * @return string SQL name for aliased table. Will not alias a table to its own name
	 */
	protected function tableNameWithAlias( $table, $alias = false ) {
		if ( is_string( $table ) ) {
			$quotedTable = $this->tableName( $table );
		} elseif ( $table instanceof Subquery ) {
			$quotedTable = (string)$table;
		} else {
			throw new InvalidArgumentException( "Table must be a string or Subquery" );
		}

		if ( $alias === false ) {
			if ( $table instanceof Subquery ) {
				throw new InvalidArgumentException( "Subquery table missing alias" );
			}
			$quotedTableWithAnyAlias = $quotedTable;
		} elseif (
			$alias === $table &&
			(
				str_contains( $alias, '.' ) ||
				$this->tableName( $alias, 'raw' ) === $table
			)
		) {
			$quotedTableWithAnyAlias = $quotedTable;
		} else {
			$quotedTableWithAnyAlias = $quotedTable . ' ' . $this->addIdentifierQuotes( $alias );
		}

		return $quotedTableWithAnyAlias;
	}

	public function tableName( string $name, $format = 'quoted' ) {
		$prefix = $this->currentDomain->getTablePrefix();

		// Warn about table names that look qualified
		if (
			(
				str_contains( $name, '.' ) &&
				!preg_match( '/^information_schema\.[a-z_0-9]+$/', $name )
			) ||
			( $prefix !== '' && str_starts_with( $name, $prefix ) )
		) {
			$this->logger->warning(
				__METHOD__ . ' called with qualified table ' . $name,
				[ 'db_log_category' => 'sql' ]
			);
		}

		// Extract necessary database, schema, table identifiers and quote them as needed
		$formattedComponents = [];
		foreach ( $this->qualifiedTableComponents( $name ) as $component ) {
			if ( $format === 'quoted' ) {
				$formattedComponents[] = $this->addIdentifierQuotes( $component );
			} else {
				$formattedComponents[] = $component;
			}
		}

		return implode( '.', $formattedComponents );
	}

	/**
	 * Get the table components needed for a query given the currently selected database/schema
	 *
	 * The resulting array will take one of the follow forms:
	 *  - <table identifier>
	 *  - <database identifier>.<table identifier> (e.g. non-Postgres)
	 *  - <schema identifier>.<table identifier> (e.g. Postgres-only)
	 *  - <database identifier>.<schema identifier>.<table identifier> (e.g. Postgres-only)
	 *
	 * If the provided table name only consists of an unquoted table identifier that has an
	 * entry in ({@link getTableAliases()}), then, the resulting components will be determined
	 * from the alias configuration. If such alias configuration does not specify the table
	 * prefix, then the current DB domain prefix will be prepended to the table identifier.
	 *
	 * In all other cases where the provided table name only consists of an unquoted table
	 * identifier, the current DB domain prefix will be prepended to the table identifier.
	 *
	 * Empty database/schema identifiers are omitted from the resulting array.
	 *
	 * @param string $name Table name as database.schema.table, database.table, or table
	 * @return string[] Non-empty list of unquoted identifiers that form the qualified table name
	 */
	public function qualifiedTableComponents( $name ) {
		$identifiers = $this->extractTableNameComponents( $name );
		if ( count( $identifiers ) > 3 ) {
			throw new DBLanguageError( "Too many components in table name '$name'" );
		}
		// Table alias config and prefixes only apply to unquoted single-identifier names
		if ( count( $identifiers ) == 1 && !$this->isQuotedIdentifier( $identifiers[0] ) ) {
			[ $table ] = $identifiers;
			if ( isset( $this->tableAliases[$table] ) ) {
				// This is an "alias" table that uses a different db/schema/prefix scheme
				$database = $this->tableAliases[$table]['dbname'];
				$schema = is_string( $this->tableAliases[$table]['schema'] )
					? $this->tableAliases[$table]['schema']
					: $this->relationSchemaQualifier();
				$prefix = is_string( $this->tableAliases[$table]['prefix'] )
					? $this->tableAliases[$table]['prefix']
					: $this->currentDomain->getTablePrefix();
			} else {
				// Use the current database domain to resolve the schema and prefix
				$database = '';
				$schema = $this->relationSchemaQualifier();
				$prefix = $this->currentDomain->getTablePrefix();
			}
			$qualifierIdentifiers = [ $database, $schema ];
			$tableIdentifier = $prefix . $table;
		} else {
			$qualifierIdentifiers = array_slice( $identifiers, 0, -1 );
			$tableIdentifier = end( $identifiers );
		}

		$components = [];
		foreach ( $qualifierIdentifiers as $identifier ) {
			if ( $identifier !== null && $identifier !== '' ) {
				$components[] = $this->isQuotedIdentifier( $identifier )
					? substr( $identifier, 1, -1 )
					: $identifier;
			}
		}
		$components[] = $this->isQuotedIdentifier( $tableIdentifier )
			? substr( $tableIdentifier, 1, -1 )
			: $tableIdentifier;

		return $components;
	}

	/**
	 * Extract the dot-separated components of a table name, preserving identifier quotation
	 *
	 * @param string $name Table name, possible qualified with db or db+schema
	 * @return string[] Non-empty list of the identifiers included in the provided table name
	 */
	public function extractTableNameComponents( string $name ) {
		$quoteChar = $this->getIdentifierQuoteChar();
		$components = [];
		foreach ( explode( '.', $name ) as $component ) {
			if ( $this->isQuotedIdentifier( $component ) ) {
				$unquotedComponent = substr( $component, 1, -1 );
			} else {
				$unquotedComponent = $component;
			}
			if ( str_contains( $unquotedComponent, $quoteChar ) ) {
				throw new DBLanguageError(
					'Table name component contains unexpected quote or dot character' );
			}
			$components[] = $component;
		}
		return $components;
	}

	/**
	 * Get the database identifer and prefixed table name identifier for a table
	 *
	 * The table name is assumed to be relative to the current DB domain
	 *
	 * This method is useful for TEMPORARY table tracking. In MySQL, temp tables with identical
	 * names can co-exist on different databases, which can be done via CREATE and USE. Note
	 * that SQLite/PostgreSQL do not allow changing the database within a session. This method
	 * omits the schema identifier for several reasons:
	 *   - MySQL/MariaDB do not support schemas at all.
	 *   - SQLite/PostgreSQL put all TEMPORARY tables in the same schema (TEMP and pgtemp,
	 *     respectively). When these engines resolve a table reference, they first check for
	 *     a matching table in the temp schema, before checking the current DB domain schema.
	 *     Note that this breaks table segregation based on the schema component of the DB
	 *     domain, e.g. a temp table with unqualified name "x" resolves to the same underlying
	 *     table whether the current DB domain is "my_db-schema1-mw_" or "my_db-schema2-mw_".
	 *     By ignoring the schema, we can at least account for this.
	 *   - Exposing the the TEMP/pg_temp schema here would be too leaky of an abstraction,
	 *     running the risk of unexpected results, such as identifiers that don't match. It is
	 *     easier to just avoid creating identically-named TEMPORARY tables on different schemas.
	 *
	 * @internal only to be used inside rdbms library
	 * @param string $table Table name
	 * @return array{0:string|null,1:string} (unquoted database name, unquoted prefixed table name)
	 */
	public function getDatabaseAndTableIdentifier( string $table ) {
		$components = $this->qualifiedTableComponents( $table );
		switch ( count( $components ) ) {
			case 1:
				return [ $this->currentDomain->getDatabase(), $components[0] ];
			case 2:
				return $components;
			default:
				throw new DBLanguageError( 'Too many table components' );
		}
	}

	/**
	 * @return string|null Schema to use to qualify relations in queries
	 */
	protected function relationSchemaQualifier() {
		return $this->currentDomain->getSchema();
	}

	public function tableNamesN( ...$tables ) {
		$retVal = [];

		foreach ( $tables as $name ) {
			$retVal[] = $this->tableName( $name );
		}

		return $retVal;
	}

	/**
	 * Returns if the given identifier looks quoted or not according to
	 * the database convention for quoting identifiers
	 *
	 * @note Do not use this to determine if untrusted input is safe.
	 *   A malicious user can trick this function.
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		$quoteChar = $this->getIdentifierQuoteChar();
		return strlen( $name ) > 1 && $name[0] === $quoteChar && $name[-1] === $quoteChar;
	}

	/**
	 * USE INDEX clause.
	 *
	 * This can be used as optimisation in queries that affect tables with multiple
	 * indexes if the database does not pick the most optimal one by default.
	 * The "right" index might vary between database backends and versions thereof,
	 * as such in practice this is biased toward specifically improving performance
	 * of large wiki farms that use MySQL or MariaDB (like Wikipedia).
	 *
	 * @param string $index
	 * @return string
	 */
	public function useIndexClause( $index ) {
		return '';
	}

	/**
	 * IGNORE INDEX clause.
	 *
	 * The inverse of Database::useIndexClause.
	 *
	 * @param string $index
	 * @return string
	 */
	public function ignoreIndexClause( $index ) {
		return '';
	}

	/**
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query.
	 *
	 * @see Database::select()
	 *
	 * @param array $options Associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return string[] (START OPTIONS, PRE-LIMIT TAIL, POST-LIMIT TAIL)
	 */
	protected function makeSelectOptions( array $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = '';

		$noKeyOptions = [];

		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		if ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['LOCK IN SHARE MODE'] ) ) {
			$postLimitTail .= ' LOCK IN SHARE MODE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		# Various MySQL extensions
		if ( isset( $noKeyOptions['STRAIGHT_JOIN'] ) ) {
			$startOpts .= ' /*! STRAIGHT_JOIN */';
		}

		if ( isset( $noKeyOptions['SQL_BIG_RESULT'] ) ) {
			$startOpts .= ' SQL_BIG_RESULT';
		}

		if ( isset( $noKeyOptions['SQL_BUFFER_RESULT'] ) ) {
			$startOpts .= ' SQL_BUFFER_RESULT';
		}

		if ( isset( $noKeyOptions['SQL_SMALL_RESULT'] ) ) {
			$startOpts .= ' SQL_SMALL_RESULT';
		}

		if ( isset( $noKeyOptions['SQL_CALC_FOUND_ROWS'] ) ) {
			$startOpts .= ' SQL_CALC_FOUND_ROWS';
		}

		return [ $startOpts, $preLimitTail, $postLimitTail ];
	}

	/**
	 * Returns an optional GROUP BY with an optional HAVING
	 *
	 * @param array $options Associative array of options
	 * @return string
	 * @see Database::select()
	 * @since 1.21
	 */
	protected function makeGroupByWithHaving( $options ) {
		$sql = '';
		if ( isset( $options['GROUP BY'] ) ) {
			$gb = is_array( $options['GROUP BY'] )
				? implode( ',', $options['GROUP BY'] )
				: $options['GROUP BY'];
			$sql .= ' GROUP BY ' . $gb;
		}
		if ( isset( $options['HAVING'] ) ) {
			if ( $options['HAVING'] instanceof IExpression ) {
				$having = $options['HAVING']->toSql( $this->quoter );
			} elseif ( is_array( $options['HAVING'] ) ) {
				$having = $this->makeList( $options['HAVING'], self::LIST_AND );
			} else {
				$having = $options['HAVING'];
			}

			$sql .= ' HAVING ' . $having;
		}

		return $sql;
	}

	/**
	 * Returns an optional ORDER BY
	 *
	 * @param array $options Associative array of options
	 * @return string
	 * @see Database::select()
	 * @since 1.21
	 */
	protected function makeOrderBy( $options ) {
		if ( isset( $options['ORDER BY'] ) ) {
			$ob = is_array( $options['ORDER BY'] )
				? implode( ',', $options['ORDER BY'] )
				: $options['ORDER BY'];

			return ' ORDER BY ' . $ob;
		}

		return '';
	}

	public function buildGroupConcatField(
		$delim, $tables, $field, $conds = '', $join_conds = []
	) {
		$fld = "GROUP_CONCAT($field SEPARATOR " . $this->quoter->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $tables, $fld, $conds, static::CALLER_SUBQUERY, [], $join_conds ) . ')';
	}

	public function buildSelectSubquery(
		$tables, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return new Subquery(
			$this->selectSQLText( $tables, $vars, $conds, $fname, $options, $join_conds )
		);
	}

	/**
	 * @param string $table
	 * @param array $rows
	 * @return string[]
	 */
	public function insertSqlText( $table, array $rows ) {
		$encTable = $this->tableName( $table );
		[ $sqlColumns, $sqlTuples ] = $this->makeInsertLists( $rows );

		return [
			"INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples",
			"INSERT INTO $encTable ($sqlColumns) VALUES '?'"
		];
	}

	/**
	 * Make SQL lists of columns, row tuples, and column aliases for INSERT/VALUES expressions
	 *
	 * The tuple column order is that of the columns of the first provided row.
	 * The provided rows must have exactly the same keys and ordering thereof.
	 *
	 * @param array[] $rows Non-empty list of (column => value) maps
	 * @param string $aliasPrefix Optional prefix to prepend to the magic alias names
	 * @param string[] $typeByColumn Optional map of (column => data type)
	 * @return array (comma-separated columns, comma-separated tuples, comma-separated aliases)
	 * @since 1.35
	 */
	public function makeInsertLists( array $rows, $aliasPrefix = '', array $typeByColumn = [] ) {
		$firstRow = $rows[0];
		if ( !is_array( $firstRow ) || !$firstRow ) {
			throw new DBLanguageError( 'Got an empty row list or empty row' );
		}
		// List of columns that define the value tuple ordering
		$tupleColumns = array_keys( $firstRow );

		$valueTuples = [];
		foreach ( $rows as $row ) {
			$rowColumns = array_keys( $row );
			// VALUES(...) requires a uniform correspondence of (column => value)
			if ( $rowColumns !== $tupleColumns ) {
				throw new DBLanguageError(
					'All rows must specify the same columns in multi-row inserts. Found a row with (' .
					implode( ', ', $rowColumns ) . ') ' .
					'instead of expected (' . implode( ', ', $tupleColumns ) . ') as in the first row'
				);
			}
			// Make the value tuple that defines this row
			$valueTuples[] = '(' . $this->makeList( array_values( $row ), self::LIST_COMMA ) . ')';
		}

		$magicAliasFields = [];
		foreach ( $tupleColumns as $column ) {
			$magicAliasFields[] = $aliasPrefix . $column;
		}

		return [
			$this->makeList( $tupleColumns, self::LIST_NAMES ),
			implode( ',', $valueTuples ),
			$this->makeList( $magicAliasFields, self::LIST_NAMES )
		];
	}

	/**
	 * @param string $table
	 * @param array $rows
	 * @return string[]
	 */
	public function insertNonConflictingSqlText( $table, array $rows ) {
		$encTable = $this->tableName( $table );
		[ $sqlColumns, $sqlTuples ] = $this->makeInsertLists( $rows );
		[ $sqlVerb, $sqlOpts ] = $this->makeInsertNonConflictingVerbAndOptions();

		return [
			rtrim( "$sqlVerb $encTable ($sqlColumns) VALUES $sqlTuples $sqlOpts" ),
			rtrim( "$sqlVerb $encTable ($sqlColumns) VALUES '?' $sqlOpts" )
		];
	}

	/**
	 * @return string[] ("INSERT"-style SQL verb, "ON CONFLICT"-style clause or "")
	 * @since 1.35
	 */
	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT IGNORE INTO', '' ];
	}

	/**
	 * @param string $destTable
	 * @param string $srcTable
	 * @param array $varMap
	 * @param array $conds
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @param array $selectJoinConds
	 * @return string
	 */
	public function insertSelectNativeSqlText(
		$destTable,
		$srcTable,
		array $varMap,
		$conds,
		$fname,
		array $insertOptions,
		array $selectOptions,
		$selectJoinConds
	) {
		[ $sqlVerb, $sqlOpts ] = $this->isFlagInOptions( 'IGNORE', $insertOptions )
			? $this->makeInsertNonConflictingVerbAndOptions()
			: [ 'INSERT INTO', '' ];
		$encDstTable = $this->tableName( $destTable );
		$sqlDstColumns = implode( ',', array_keys( $varMap ) );
		$selectSql = $this->selectSQLText(
			$srcTable,
			array_values( $varMap ),
			$conds,
			$fname,
			$selectOptions,
			$selectJoinConds
		);

		return rtrim( "$sqlVerb $encDstTable ($sqlDstColumns) $selectSql $sqlOpts" );
	}

	/**
	 * @param string $option Query option flag (e.g. "IGNORE" or "FOR UPDATE")
	 * @param array $options Combination option/value map and boolean option list
	 * @return bool Whether the option appears as an integer-keyed value in the options
	 * @since 1.35
	 */
	public function isFlagInOptions( $option, array $options ) {
		foreach ( array_keys( $options, $option, true ) as $k ) {
			if ( is_int( $k ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Build an SQL condition to find rows with matching key values to those in $rows.
	 *
	 * @param array[] $rows Non-empty list of rows
	 * @param string[] $uniqueKey List of columns that define a single unique index
	 * @return string
	 */
	public function makeKeyCollisionCondition( array $rows, array $uniqueKey ) {
		if ( !$rows ) {
			throw new DBLanguageError( "Empty row array" );
		} elseif ( !$uniqueKey ) {
			throw new DBLanguageError( "Empty unique key array" );
		}

		if ( count( $uniqueKey ) == 1 ) {
			// Use a simple IN(...) clause
			$column = reset( $uniqueKey );
			$values = array_column( $rows, $column );
			if ( count( $values ) !== count( $rows ) ) {
				throw new DBLanguageError( "Missing values for unique key ($column)" );
			}

			return $this->makeList( [ $column => $values ], self::LIST_AND );
		}

		$nullByUniqueKeyColumn = array_fill_keys( $uniqueKey, null );

		$orConds = [];
		foreach ( $rows as $row ) {
			$rowKeyMap = array_intersect_key( $row, $nullByUniqueKeyColumn );
			if ( count( $rowKeyMap ) != count( $uniqueKey ) ) {
				throw new DBLanguageError(
					"Missing values for unique key (" . implode( ',', $uniqueKey ) . ")"
				);
			}
			$orConds[] = $this->makeList( $rowKeyMap, self::LIST_AND );
		}

		return count( $orConds ) > 1
			? $this->makeList( $orConds, self::LIST_OR )
			: $orConds[0];
	}

	/**
	 * @param string $delTable
	 * @param string $joinTable
	 * @param string $delVar
	 * @param string $joinVar
	 * @param array|string $conds
	 * @return string
	 */
	public function deleteJoinSqlText( $delTable, $joinTable, $delVar, $joinVar, $conds ) {
		if ( !$conds ) {
			throw new DBLanguageError( __METHOD__ . ' called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE FROM $delTable WHERE $delVar IN (SELECT $joinVar FROM $joinTable ";
		if ( $conds != '*' ) {
			$sql .= 'WHERE ' . $this->makeList( $conds, self::LIST_AND );
		}
		$sql .= ')';

		return $sql;
	}

	/**
	 * @param string $table The unqualified name of a table
	 * @param string|array $conds
	 * @return Query
	 */
	public function deleteSqlText( $table, $conds ) {
		$isCondValid = ( is_string( $conds ) || is_array( $conds ) ) && $conds;
		if ( !$isCondValid ) {
			throw new DBLanguageError( __METHOD__ . ' called with empty conditions' );
		}

		$encTable = $this->tableName( $table );
		$sql = "DELETE FROM $encTable";

		$condsSql = '';
		$cleanCondsSql = '';
		if ( $conds !== self::ALL_ROWS && $conds !== [ self::ALL_ROWS ] ) {
			$cleanCondsSql = ' WHERE ' . $this->scrubArray( $conds );
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$condsSql .= ' WHERE ' . $conds;
		}
		return new Query(
			$sql . $condsSql,
			self::QUERY_CHANGE_ROWS,
			'DELETE',
			$table,
			$sql . $cleanCondsSql
		);
	}

	/**
	 * @param mixed $array
	 * @param int $listType
	 */
	private function scrubArray( $array, int $listType = self::LIST_AND ): string {
		if ( is_array( $array ) ) {
			$scrubbedArray = [];
			foreach ( $array as $key => $value ) {
				if ( $value instanceof IExpression ) {
					$scrubbedArray[$key] = $value->toGeneralizedSql();
				} else {
					$scrubbedArray[$key] = '?';
				}
			}
			return $this->makeList( $scrubbedArray, $listType );
		}
		return '?';
	}

	/**
	 * @param string $table
	 * @param array $set
	 * @param string|IExpression|array $conds
	 * @param string|array $options
	 * @return Query
	 */
	public function updateSqlText( $table, $set, $conds, $options ) {
		$isCondValid = ( is_string( $conds ) || is_array( $conds ) ) && $conds;
		if ( !$isCondValid ) {
			throw new DBLanguageError( __METHOD__ . ' called with empty conditions' );
		}
		$encTable = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $encTable";
		$condsSql = " SET " . $this->makeList( $set, self::LIST_SET );
		$cleanCondsSql = " SET " . $this->scrubArray( $set, self::LIST_SET );

		if ( $conds && $conds !== self::ALL_ROWS && $conds !== [ self::ALL_ROWS ] ) {
			$cleanCondsSql .= ' WHERE ' . $this->scrubArray( $conds );
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$condsSql .= ' WHERE ' . $conds;
		}
		return new Query(
			$sql . $condsSql,
			self::QUERY_CHANGE_ROWS,
			'UPDATE',
			$table,
			$sql . $cleanCondsSql
		);
	}

	/**
	 * Make UPDATE options for the Database::update function
	 *
	 * @param array $options The options passed to Database::update
	 * @return string
	 */
	protected function makeUpdateOptions( $options ) {
		$opts = $this->makeUpdateOptionsArray( $options );

		return implode( ' ', $opts );
	}

	/**
	 * Make UPDATE options array for Database::makeUpdateOptions
	 *
	 * @param array $options
	 * @return array
	 */
	protected function makeUpdateOptionsArray( $options ) {
		$options = $this->normalizeOptions( $options );

		$opts = [];

		if ( in_array( 'IGNORE', $options ) ) {
			$opts[] = 'IGNORE';
		}

		return $opts;
	}

	/**
	 * @param string|array $options
	 * @return array Combination option/value map and boolean option list
	 * @since 1.35, moved to SQLPlatform in 1.39
	 */
	final public function normalizeOptions( $options ) {
		if ( is_array( $options ) ) {
			return $options;
		} elseif ( is_string( $options ) ) {
			return ( $options === '' ) ? [] : [ $options ];
		} else {
			throw new DBLanguageError( __METHOD__ . ': expected string or array' );
		}
	}

	/**
	 * @param string $table
	 * @return string
	 */
	public function dropTableSqlText( $table ) {
		// https://mariadb.com/kb/en/drop-table/
		// https://dev.mysql.com/doc/refman/8.0/en/drop-table.html
		// https://www.postgresql.org/docs/9.2/sql-truncate.html
		return "DROP TABLE " . $this->tableName( $table ) . " CASCADE";
	}

	/**
	 * @param string $sql SQL query
	 * @return string|null
	 * @deprecated Since 1.42
	 */
	public function getQueryVerb( $sql ) {
		wfDeprecated( __METHOD__, '1.42' );
		return QueryBuilderFromRawSql::buildQuery( $sql, 0 )->getVerb();
	}

	/**
	 * Determine whether a SQL statement is sensitive to isolation level.
	 *
	 * A SQL statement is considered transactable if its result could vary
	 * depending on the transaction isolation level. Operational commands
	 * such as 'SET' and 'SHOW' are not considered to be transactable.
	 *
	 * Main purpose: Used by query() to decide whether to begin a transaction
	 * before the current query (in DBO_TRX mode, on by default).
	 *
	 * @return bool
	 */
	public function isTransactableQuery( Query $sql ) {
		return !in_array(
			$sql->getVerb(),
			[
				'BEGIN',
				'ROLLBACK',
				'ROLLBACK TO SAVEPOINT',
				'COMMIT',
				'SET',
				'SHOW',
				'CREATE',
				'ALTER',
				'USE',
				'SHOW'
			],
			true
		);
	}

	/**
	 * @param string $column
	 * @return string
	 */
	public function buildExcludedValue( $column ) {
		/* @see Database::upsert() */
		// This can be treated like a single value since __VALS is a single row table
		return "(SELECT __$column FROM __VALS)";
	}

	/**
	 * @param string $identifier
	 * @return string
	 */
	public function savepointSqlText( $identifier ) {
		return 'SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
	}

	/**
	 * @param string $identifier
	 * @return string
	 */
	public function releaseSavepointSqlText( $identifier ) {
		return 'RELEASE SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
	}

	/**
	 * @param string $identifier
	 * @return string
	 */
	public function rollbackToSavepointSqlText( $identifier ) {
		return 'ROLLBACK TO SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
	}

	/**
	 * @return string
	 */
	public function rollbackSqlText() {
		return 'ROLLBACK';
	}

	/**
	 * @param string $table
	 * @param array $rows
	 * @param array $options
	 * @return Query|false
	 */
	public function dispatchingInsertSqlText( $table, $rows, $options ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$rows ) {
			return false;
		}

		$options = $this->normalizeOptions( $options );
		if ( $this->isFlagInOptions( 'IGNORE', $options ) ) {
			[ $sql, $cleanSql ] = $this->insertNonConflictingSqlText( $table, $rows );
		} else {
			[ $sql, $cleanSql ] = $this->insertSqlText( $table, $rows );
		}
		return new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', $table, $cleanSql );
	}

	/**
	 * @param array $rowOrRows A single (field => value) map or a list of such maps
	 * @return array[] List of (field => value) maps
	 * @since 1.35
	 */
	final protected function normalizeRowArray( array $rowOrRows ) {
		if ( !$rowOrRows ) {
			$rows = [];
		} elseif ( isset( $rowOrRows[0] ) ) {
			$rows = $rowOrRows;
		} else {
			$rows = [ $rowOrRows ];
		}

		foreach ( $rows as $row ) {
			if ( !is_array( $row ) ) {
				throw new DBLanguageError( "Got non-array in row array" );
			} elseif ( !$row ) {
				throw new DBLanguageError( "Got empty array in row array" );
			}
		}

		return $rows;
	}

	/**
	 * Validate and normalize parameters to upsert() or replace()
	 *
	 * @param string|string[]|string[][] $uniqueKeys Unique indexes (only one is allowed)
	 * @param array[] &$rows The row array, which will be replaced with a normalized version.
	 * @return string[] List of columns that defines a single unique index
	 * @since 1.35
	 */
	final public function normalizeUpsertParams( $uniqueKeys, &$rows ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$uniqueKeys ) {
			throw new DBLanguageError( 'No unique key specified for upsert/replace' );
		}
		$uniqueKey = $this->normalizeUpsertKeys( $uniqueKeys );
		$this->assertValidUpsertRowArray( $rows, $uniqueKey );

		return $uniqueKey;
	}

	/**
	 * @param array|string $conds
	 * @param string $fname
	 * @return array
	 * @since 1.31
	 */
	final public function normalizeConditions( $conds, $fname ) {
		if ( $conds === null || $conds === false ) {
			$this->logger->warning(
				__METHOD__
				. ' called from '
				. $fname
				. ' with incorrect parameters: $conds must be a string or an array',
				[ 'db_log_category' => 'sql' ]
			);
			return [];
		} elseif ( $conds === '' ) {
			return [];
		}

		return is_array( $conds ) ? $conds : [ $conds ];
	}

	/**
	 * @param string|string[]|string[][] $uniqueKeys Unique indexes (only one is allowed)
	 * @return string[] List of columns that defines a single unique index
	 * @since 1.35
	 */
	private function normalizeUpsertKeys( $uniqueKeys ) {
		if ( is_string( $uniqueKeys ) ) {
			return [ $uniqueKeys ];
		} elseif ( !is_array( $uniqueKeys ) ) {
			throw new DBLanguageError( 'Invalid unique key array' );
		} else {
			if ( count( $uniqueKeys ) !== 1 || !isset( $uniqueKeys[0] ) ) {
				throw new DBLanguageError(
					"The unique key array should contain a single unique index" );
			}

			$uniqueKey = $uniqueKeys[0];
			if ( is_string( $uniqueKey ) ) {
				// Passing a list of strings for single-column unique keys is too
				// easily confused with passing the columns of composite unique key
				$this->logger->warning( __METHOD__ .
					" called with deprecated parameter style: " .
					"the unique key array should be a string or array of string arrays",
					[
						'exception' => new RuntimeException(),
						'db_log_category' => 'sql',
					] );
				return $uniqueKeys;
			} elseif ( is_array( $uniqueKey ) ) {
				return $uniqueKey;
			} else {
				throw new DBLanguageError( 'Invalid unique key array entry' );
			}
		}
	}

	/**
	 * @param array<int,array> $rows Normalized list of rows to insert
	 * @param string[] $uniqueKey Columns of the unique key to UPSERT upon
	 * @since 1.37
	 */
	final protected function assertValidUpsertRowArray( array $rows, array $uniqueKey ) {
		foreach ( $rows as $row ) {
			foreach ( $uniqueKey as $column ) {
				if ( !isset( $row[$column] ) ) {
					throw new DBLanguageError(
						"NULL/absent values for unique key (" . implode( ',', $uniqueKey ) . ")"
					);
				}
			}
		}
	}

	/**
	 * @param array $set Combined column/literal assignment map and SQL assignment list
	 * @param string[] $uniqueKey Columns of the unique key to UPSERT upon
	 * @param array<int,array> $rows List of rows to upsert
	 * @since 1.37
	 */
	final public function assertValidUpsertSetArray(
		array $set,
		array $uniqueKey,
		array $rows
	) {
		if ( !$set ) {
			throw new DBLanguageError( "Update assignment list can't be empty for upsert" );
		}

		// Sloppy callers might construct the SET array using the ROW array, leaving redundant
		// column definitions for unique key columns. Detect this for backwards compatibility.
		$soleRow = ( count( $rows ) == 1 ) ? reset( $rows ) : null;
		// Disallow value changes for any columns in the unique key. This avoids additional
		// insertion order dependencies that are unwieldy and difficult to implement efficiently
		// in PostgreSQL.
		foreach ( $set as $k => $v ) {
			if ( is_string( $k ) ) {
				// Key is a column name and value is a literal (e.g. string, int, null, ...)
				if ( in_array( $k, $uniqueKey, true ) ) {
					if ( $soleRow && array_key_exists( $k, $soleRow ) && $soleRow[$k] === $v ) {
						$this->logger->warning(
							__METHOD__ . " called with redundant assignment to column '$k'",
							[
								'exception' => new RuntimeException(),
								'db_log_category' => 'sql',
							]
						);
					} else {
						throw new DBLanguageError(
							"Cannot reassign column '$k' since it belongs to the provided unique key"
						);
					}
				}
			} elseif ( preg_match( '/^([a-zA-Z0-9_]+)\s*=/', $v, $m ) ) {
				// Value is of the form "<unquoted alphanumeric column> = <SQL expression>"
				if ( in_array( $m[1], $uniqueKey, true ) ) {
					throw new DBLanguageError(
						"Cannot reassign column '{$m[1]}' since it belongs to the provided unique key"
					);
				}
			}
		}
	}

	/**
	 * @param array|string $var Field parameter in the style of select()
	 * @return string|null Column name or null; ignores aliases
	 */
	final public function extractSingleFieldFromList( $var ) {
		if ( is_array( $var ) ) {
			if ( !$var ) {
				$column = null;
			} elseif ( count( $var ) == 1 ) {
				$column = $var[0] ?? reset( $var );
			} else {
				throw new DBLanguageError( __METHOD__ . ': got multiple columns' );
			}
		} else {
			$column = $var;
		}

		return $column;
	}

	public function setSchemaVars( $vars ) {
		$this->schemaVars = is_array( $vars ) ? $vars : null;
	}

	/**
	 * Get schema variables. If none have been set via setSchemaVars(), then
	 * use some defaults from the current object.
	 *
	 * @return array
	 */
	protected function getSchemaVars() {
		return $this->schemaVars ?? $this->getDefaultSchemaVars();
	}

	/**
	 * Get schema variables to use if none have been set via setSchemaVars().
	 *
	 * Override this in derived classes to provide variables for SQL schema
	 * and patch files.
	 *
	 * @return array
	 */
	protected function getDefaultSchemaVars() {
		return [];
	}

	/**
	 * Database-independent variable replacement. Replaces a set of variables
	 * in an SQL statement with their contents as given by $this->getSchemaVars().
	 *
	 * Supports '{$var}' `{$var}` and / *$var* / (without the spaces) style variables.
	 *
	 * - '{$var}' should be used for text and is passed through the database's
	 *   addQuotes method.
	 * - `{$var}` should be used for identifiers (e.g. table and database names).
	 *   It is passed through the database's addIdentifierQuotes method which
	 *   can be overridden if the database uses something other than backticks.
	 * - / *_* / or / *$wgDBprefix* / passes the name that follows through the
	 *   database's tableName method.
	 * - / *i* / passes the name that follows through the database's indexName method.
	 * - In all other cases, / *$var* / is left unencoded. Except for table options,
	 *   its use should be avoided. In 1.24 and older, string encoding was applied.
	 *
	 * @param string $ins SQL statement to replace variables in
	 * @return string The new SQL statement with variables replaced
	 */
	public function replaceVars( $ins ) {
		$vars = $this->getSchemaVars();
		return preg_replace_callback(
			'!
				/\* (\$wgDBprefix|[_i]) \*/ (\w*) | # 1-2. tableName, indexName
				\'\{\$ (\w+) }\'                  | # 3. addQuotes
				`\{\$ (\w+) }`                    | # 4. addIdentifierQuotes
				/\*\$ (\w+) \*/                     # 5. leave unencoded
			!x',
			function ( $m ) use ( $vars ) {
				// Note: Because of <https://bugs.php.net/bug.php?id=51881>,
				// check for both nonexistent keys *and* the empty string.
				if ( isset( $m[1] ) && $m[1] !== '' ) {
					if ( $m[1] === 'i' ) {
						return $m[2];
					} else {
						return $this->tableName( $m[2] );
					}
				} elseif ( isset( $m[3] ) && $m[3] !== '' && array_key_exists( $m[3], $vars ) ) {
					return $this->quoter->addQuotes( $vars[$m[3]] );
				} elseif ( isset( $m[4] ) && $m[4] !== '' && array_key_exists( $m[4], $vars ) ) {
					return $this->addIdentifierQuotes( $vars[$m[4]] );
				} elseif ( isset( $m[5] ) && $m[5] !== '' && array_key_exists( $m[5], $vars ) ) {
					return $vars[$m[5]];
				} else {
					return $m[0];
				}
			},
			$ins
		);
	}

	/**
	 * @param string $lockName
	 * @param float $timeout
	 * @return string
	 */
	public function lockSQLText( $lockName, $timeout ) {
		throw new RuntimeException( 'locking must be implemented in subclasses' );
	}

	/**
	 * @param string $lockName
	 * @return string
	 */
	public function lockIsFreeSQLText( $lockName ) {
		throw new RuntimeException( 'locking must be implemented in subclasses' );
	}

	/**
	 * @param string $lockName
	 * @return string
	 */
	public function unlockSQLText( $lockName ) {
		throw new RuntimeException( 'locking must be implemented in subclasses' );
	}
}
