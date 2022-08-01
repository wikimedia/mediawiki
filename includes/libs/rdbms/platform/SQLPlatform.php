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
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\LikeMatch;
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
	/** @var string[] Current map of (index alias => index) */
	protected $indexAliases = [];
	/** @var DatabaseDomain|null */
	protected $currentDomain;
	/** @var array|null Current variables use for schema element placeholders */
	protected $schemaVars;
	/** @var DbQuoter */
	protected $quoter;
	/** @var LoggerInterface */
	protected $logger;

	public function __construct(
		DbQuoter $quoter,
		LoggerInterface $logger = null,
		DatabaseDomain $currentDomain = null
	) {
		$this->quoter = $quoter;
		$this->logger = $logger ?? new NullLogger();
		$this->currentDomain = $currentDomain;
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

	/**
	 * Allows for index remapping in queries where this is not consistent across DBMS
	 *
	 * TODO: Make it protected once all the code is moved over.
	 *
	 * @param string $index
	 * @return string
	 */
	public function indexName( $index ) {
		return $this->indexAliases[$index] ?? $index;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function setIndexAliases( array $aliases ) {
		$this->indexAliases = $aliases;
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
	 * @inheritDoc
	 * @stable to override
	 */
	public function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( is_array( $vars ) ) {
			$fields = implode( ',', $this->fieldNamesWithAlias( $vars ) );
		} else {
			$fields = $vars;
		}

		$options = (array)$options;
		$useIndexes = ( isset( $options['USE INDEX'] ) && is_array( $options['USE INDEX'] ) )
			? $options['USE INDEX']
			: [];
		$ignoreIndexes = (
			isset( $options['IGNORE INDEX'] ) &&
			is_array( $options['IGNORE INDEX'] )
		)
			? $options['IGNORE INDEX']
			: [];

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

		if ( is_array( $table ) ) {
			if ( count( $table ) === 0 ) {
				$from = '';
			} else {
				$from = ' FROM ' .
					$this->tableNamesWithIndexClauseOrJOIN(
						$table, $useIndexes, $ignoreIndexes, $join_conds );
			}
		} elseif ( $table != '' ) {
			$from = ' FROM ' .
				$this->tableNamesWithIndexClauseOrJOIN(
					[ $table ], $useIndexes, $ignoreIndexes, [] );
		} else {
			$from = '';
		}

		list( $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ) =
			$this->makeSelectOptions( $options );

		if ( is_array( $conds ) ) {
			$conds = $this->makeList( $conds, self::LIST_AND );
		}

		if ( $conds === null || $conds === false ) {
			$this->logger->warning(
				__METHOD__
				. ' called from '
				. $fname
				. ' with incorrect parameters: $conds must be a string or an array',
				[ 'db_log_category' => 'sql' ]
			);
			$conds = '';
		}

		if ( $conds === '' || $conds === '*' ) {
			$sql = "SELECT $startOpts $fields $from $useIndex $ignoreIndex $preLimitTail";
		} elseif ( is_string( $conds ) ) {
			$sql = "SELECT $startOpts $fields $from $useIndex $ignoreIndex " .
				"WHERE $conds $preLimitTail";
		} else {
			throw new DBLanguageError( __METHOD__ . ' called with incorrect parameters' );
		}

		if ( isset( $options['LIMIT'] ) ) {
			$sql = $this->limitResult( $sql, $options['LIMIT'],
				$options['OFFSET'] ?? false );
		}
		$sql = "$sql $postLimitTail";

		if ( isset( $options['EXPLAIN'] ) ) {
			$sql = 'EXPLAIN ' . $sql;
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
	 * @stable to override
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
	 * @param array $tables ( [alias] => table )
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
				list( $joinType, $conds ) = $join_conds[$alias];
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
	 * @param string|Subquery $table Table name or object with a 'sql' field
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

		if ( $alias === false || $alias === $table ) {
			if ( $table instanceof Subquery ) {
				throw new InvalidArgumentException( "Subquery table missing alias" );
			}

			return $quotedTable;
		} else {
			return $quotedTable . ' ' . $this->addIdentifierQuotes( $alias );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function tableName( $name, $format = 'quoted' ) {
		if ( $name instanceof Subquery ) {
			throw new DBLanguageError(
				__METHOD__ . ': got Subquery instance when expecting a string'
			);
		}

		# Skip the entire process when we have a string quoted on both ends.
		# Note that we check the end so that we will still quote any use of
		# use of `database`.table. But won't break things if someone wants
		# to query a database table with a dot in the name.
		if ( $this->isQuotedIdentifier( $name ) ) {
			return $name;
		}

		# Lets test for any bits of text that should never show up in a table
		# name. Basically anything like JOIN or ON which are actually part of
		# SQL queries, but may end up inside of the table value to combine
		# sql. Such as how the API is doing.
		# Note that we use a whitespace test rather than a \b test to avoid
		# any remote case where a word like on may be inside of a table name
		# surrounded by symbols which may be considered word breaks.
		if ( preg_match( '/(^|\s)(DISTINCT|JOIN|ON|AS)(\s|$)/i', $name ) !== 0 ) {
			$this->logger->warning(
				__METHOD__ . ": use of subqueries is not supported this way",
				[
					'exception' => new RuntimeException(),
					'db_log_category' => 'sql',
				]
			);

			return $name;
		}

		# Split database and table into proper variables.
		list( $database, $schema, $prefix, $table ) = $this->qualifiedTableComponents( $name );

		# Quote $table and apply the prefix if not quoted.
		# $tableName might be empty if this is called from Database::replaceVars()
		$tableName = "{$prefix}{$table}";
		if ( $format === 'quoted'
			&& !$this->isQuotedIdentifier( $tableName )
			&& $tableName !== ''
		) {
			$tableName = $this->addIdentifierQuotes( $tableName );
		}

		# Quote $schema and $database and merge them with the table name if needed
		$tableName = $this->prependDatabaseOrSchema( $schema, $tableName, $format );
		$tableName = $this->prependDatabaseOrSchema( $database, $tableName, $format );

		return $tableName;
	}

	/**
	 * Get the table components needed for a query given the currently selected database
	 *
	 * @param string $name Table name in the form of db.schema.table, db.table, or table
	 * @return array (DB name or "" for default, schema name, table prefix, table name)
	 */
	public function qualifiedTableComponents( $name ) {
		# We reverse the explode so that database.table and table both output the correct table.
		$dbDetails = explode( '.', $name, 3 );
		if ( $this->currentDomain ) {
			$currentDomainPrefix = $this->currentDomain->getTablePrefix();
		} else {
			$currentDomainPrefix = null;
		}
		if ( count( $dbDetails ) == 3 ) {
			list( $database, $schema, $table ) = $dbDetails;
			# We don't want any prefix added in this case
			$prefix = '';
		} elseif ( count( $dbDetails ) == 2 ) {
			list( $database, $table ) = $dbDetails;
			# We don't want any prefix added in this case
			$prefix = '';
			# In dbs that support it, $database may actually be the schema
			# but that doesn't affect any of the functionality here
			$schema = '';
		} else {
			list( $table ) = $dbDetails;
			if ( isset( $this->tableAliases[$table] ) ) {
				$database = $this->tableAliases[$table]['dbname'];
				$schema = is_string( $this->tableAliases[$table]['schema'] )
					? $this->tableAliases[$table]['schema']
					: $this->relationSchemaQualifier();
				$prefix = is_string( $this->tableAliases[$table]['prefix'] )
					? $this->tableAliases[$table]['prefix']
					: $currentDomainPrefix;
			} else {
				$database = '';
				$schema = $this->relationSchemaQualifier(); # Default schema
				$prefix = $currentDomainPrefix; # Default prefix
			}
		}

		return [ $database, $schema, $prefix, $table ];
	}

	/**
	 * @stable to override
	 * @return string|null Schema to use to qualify relations in queries
	 */
	protected function relationSchemaQualifier() {
		if ( $this->currentDomain ) {
			return $this->currentDomain->getSchema();
		}
		return null;
	}

	/**
	 * @param string|null $namespace Database or schema
	 * @param string $relation Name of table, view, sequence, etc...
	 * @param string $format One of (raw, quoted)
	 * @return string Relation name with quoted and merged $namespace as needed
	 */
	private function prependDatabaseOrSchema( $namespace, $relation, $format ) {
		if ( $namespace !== null && $namespace !== '' ) {
			if ( $format === 'quoted' && !$this->isQuotedIdentifier( $namespace ) ) {
				$namespace = $this->addIdentifierQuotes( $namespace );
			}
			$relation = $namespace . '.' . $relation;
		}

		return $relation;
	}

	public function tableNames( ...$tables ) {
		$retVal = [];

		foreach ( $tables as $name ) {
			$retVal[$name] = $this->tableName( $name );
		}

		return $retVal;
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
	 * @stable to override
	 * @note Do not use this to determine if untrusted input is safe.
	 *   A malicious user can trick this function.
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return $name[0] == '"' && substr( $name, -1, 1 ) == '"';
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
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
	 * @param array $options Associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
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

		if ( isset( $options['USE INDEX'] ) && is_string( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}
		if ( isset( $options['IGNORE INDEX'] ) && is_string( $options['IGNORE INDEX'] ) ) {
			$ignoreIndex = $this->ignoreIndexClause( $options['IGNORE INDEX'] );
		} else {
			$ignoreIndex = '';
		}

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ];
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
			$having = is_array( $options['HAVING'] )
				? $this->makeList( $options['HAVING'], self::LIST_AND )
				: $options['HAVING'];
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

	public function unionConditionPermutations(
		$table,
		$vars,
		array $permute_conds,
		$extra_conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	) {
		// First, build the Cartesian product of $permute_conds
		$conds = [ [] ];
		foreach ( $permute_conds as $field => $values ) {
			if ( !$values ) {
				// Skip empty $values
				continue;
			}
			$values = array_unique( $values );
			$newConds = [];
			foreach ( $conds as $cond ) {
				foreach ( $values as $value ) {
					$cond[$field] = $value;
					$newConds[] = $cond; // Arrays are by-value, not by-reference, so this works
				}
			}
			$conds = $newConds;
		}

		$extra_conds = $extra_conds === '' ? [] : (array)$extra_conds;

		// If there's just one condition and no subordering, hand off to
		// selectSQLText directly.
		if ( count( $conds ) === 1 &&
			( !isset( $options['INNER ORDER BY'] ) || !$this->unionSupportsOrderAndLimit() )
		) {
			return $this->selectSQLText(
				$table, $vars, $conds[0] + $extra_conds, $fname, $options, $join_conds
			);
		}

		// Otherwise, we need to pull out the order and limit to apply after
		// the union. Then build the SQL queries for each set of conditions in
		// $conds. Then union them together (using UNION ALL, because the
		// product *should* already be distinct).
		$orderBy = $this->makeOrderBy( $options );
		$limit = $options['LIMIT'] ?? null;
		$offset = $options['OFFSET'] ?? false;
		$all = empty( $options['NOTALL'] ) && !in_array( 'NOTALL', $options );
		if ( !$this->unionSupportsOrderAndLimit() ) {
			unset( $options['ORDER BY'], $options['LIMIT'], $options['OFFSET'] );
		} else {
			if ( array_key_exists( 'INNER ORDER BY', $options ) ) {
				$options['ORDER BY'] = $options['INNER ORDER BY'];
			}
			if ( $limit !== null && is_numeric( $offset ) && $offset != 0 ) {
				// We need to increase the limit by the offset rather than
				// using the offset directly, otherwise it'll skip incorrectly
				// in the subqueries.
				$options['LIMIT'] = $limit + $offset;
				unset( $options['OFFSET'] );
			}
		}

		$sqls = [];
		foreach ( $conds as $cond ) {
			$sqls[] = $this->selectSQLText(
				$table, $vars, $cond + $extra_conds, $fname, $options, $join_conds
			);
		}
		$sql = $this->unionQueries( $sqls, $all ) . $orderBy;
		if ( $limit !== null ) {
			$sql = $this->limitResult( $sql, $limit, $offset );
		}

		return $sql;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "GROUP_CONCAT($field SEPARATOR " . $this->quoter->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, __METHOD__, [], $join_conds ) . ')';
	}

	public function buildSelectSubquery(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return new Subquery(
			$this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds )
		);
	}

	public function insertSqlText( $table, array $rows ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( $rows );

		return "INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples";
	}

	/**
	 * Make SQL lists of columns, row tuples, and column aliases for INSERT/VALUES expressions
	 *
	 * The tuple column order is that of the columns of the first provided row.
	 * The provided rows must have exactly the same keys and ordering thereof.
	 *
	 * @param array[] $rows Non-empty list of (column => value) maps
	 * @param string $aliasPrefix Optional prefix to prepend to the magic alias names
	 * @return array (comma-separated columns, comma-separated tuples, comma-separated aliases)
	 * @since 1.35
	 */
	public function makeInsertLists( array $rows, $aliasPrefix = '' ) {
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
					'Got row columns (' . implode( ', ', $rowColumns ) . ') ' .
					'instead of expected (' . implode( ', ', $tupleColumns ) . ')'
				);
			}
			// Make the value tuple that defines this row
			$valueTuples[] = '(' . $this->makeList( $row, self::LIST_COMMA ) . ')';
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

	public function insertNonConflictingSqlText( $table, array $rows ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( $rows );
		list( $sqlVerb, $sqlOpts ) = $this->makeInsertNonConflictingVerbAndOptions();

		return rtrim( "$sqlVerb $encTable ($sqlColumns) VALUES $sqlTuples $sqlOpts" );
	}

	/**
	 * @stable to override
	 * @return string[] ("INSERT"-style SQL verb, "ON CONFLICT"-style clause or "")
	 * @since 1.35
	 */
	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT IGNORE INTO', '' ];
	}

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
		list( $sqlVerb, $sqlOpts ) = $this->isFlagInOptions( 'IGNORE', $insertOptions )
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

	public function deleteSqlText( $table, $conds ) {
		$this->assertConditionIsNotEmpty( $conds, __METHOD__, false );

		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";

		if ( $conds !== self::ALL_ROWS ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql .= ' WHERE ' . $conds;
		}

		return $sql;
	}

	public function updateSqlText( $table, $set, $conds, $options ) {
		$this->assertConditionIsNotEmpty( $conds, __METHOD__, true );
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $set, self::LIST_SET );

		if ( $conds && $conds !== self::ALL_ROWS ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql .= ' WHERE ' . $conds;
		}

		return $sql;
	}

	/**
	 * Check type and bounds conditions parameters for update
	 *
	 * In order to prevent possible performance or replication issues,
	 * empty condition for 'update' and 'delete' queries isn't allowed
	 *
	 * @param array|string $conds conditions to be validated on emptiness
	 * @param string $fname caller's function name to be passed to exception
	 * @param bool $deprecate define the assertion type. If true then
	 *   wfDeprecated will be called, otherwise DBUnexpectedError will be
	 *   raised.
	 * @since 1.35, moved to SQLPlatform in 1.39
	 */
	protected function assertConditionIsNotEmpty( $conds, string $fname, bool $deprecate ) {
		$isCondValid = ( is_string( $conds ) || is_array( $conds ) ) && $conds;
		if ( !$isCondValid ) {
			if ( $deprecate ) {
				wfDeprecated( $fname . ' called with empty $conds', '1.35', false, 4 );
			} else {
				throw new DBLanguageError( $fname . ' called with empty conditions' );
			}
		}
	}

	/**
	 * Make UPDATE options for the Database::update function
	 *
	 * @stable to override
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
	 * @stable to override
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

	public function dropTableSqlText( $table ) {
		// https://mariadb.com/kb/en/drop-table/
		// https://dev.mysql.com/doc/refman/8.0/en/drop-table.html
		// https://www.postgresql.org/docs/9.2/sql-truncate.html
		return "DROP TABLE " . $this->tableName( $table ) . " CASCADE";
	}

	/**
	 * @param string $sql SQL query
	 * @return string|null
	 */
	public function getQueryVerb( $sql ) {
		// Distinguish ROLLBACK from ROLLBACK TO SAVEPOINT
		return preg_match(
			'/^\s*(rollback\s+to\s+savepoint|[a-z]+)/i',
			$sql,
			$m
		) ? strtoupper( $m[1] ) : null;
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
	 * @stable to override
	 * @param string $sql
	 * @return bool
	 */
	public function isTransactableQuery( $sql ) {
		return !in_array(
			$this->getQueryVerb( $sql ),
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
	 * Determine whether a query writes to the DB. When in doubt, this returns true.
	 *
	 * Main use cases:
	 *
	 * - Subsequent web requests should not need to wait for replication from
	 *   the primary position seen by this web request, unless this request made
	 *   changes to the primary DB. This is handled by ChronologyProtector by checking
	 *   doneWrites() at the end of the request. doneWrites() returns true if any
	 *   query set lastWriteTime; which query() does based on isWriteQuery().
	 *
	 * - Reject write queries to replica DBs, in query().
	 *
	 * @param string $sql SQL query
	 * @param int $flags Query flags to query()
	 * @return bool
	 */
	public function isWriteQuery( $sql, $flags ) {
		// Check if a SQL wrapper method already flagged the query as a write
		if (
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_ROWS ) ||
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_SCHEMA )
		) {
			return true;
		}
		// Check if a SQL wrapper method already flagged the query as a non-write
		if (
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_NONE ) ||
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_TRX ) ||
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_LOCKS )
		) {
			return false;
		}
		// Treat SELECT queries without FOR UPDATE queries as non-writes. This matches
		// how MySQL enforces read_only (FOR SHARE and LOCK IN SHADE MODE are allowed).
		// Handle (SELECT ...) UNION (SELECT ...) queries in a similar fashion.
		if ( preg_match( '/^\s*\(?SELECT\b/i', $sql ) ) {
			return (bool)preg_match( '/\bFOR\s+UPDATE\)?\s*$/i', $sql );
		}
		// BEGIN and COMMIT queries are considered non-write queries here.
		// Database backends and drivers (MySQL, MariaDB, php-mysqli) generally
		// treat these as write queries, in that their results have "affected rows"
		// as meta data as from writes, instead of "num rows" as from reads.
		// But, we treat them as non-write queries because when reading data (from
		// either replica or primary DB) we use transactions to enable repeatable-read
		// snapshots, which ensures we get consistent results from the same snapshot
		// for all queries within a request. Use cases:
		// - Treating these as writes would trigger ChronologyProtector (see method doc).
		// - We use this method to reject writes to replicas, but we need to allow
		//   use of transactions on replicas for read snapshots. This is fine given
		//   that transactions by themselves don't make changes, only actual writes
		//   within the transaction matter, which we still detect.
		return !preg_match(
			'/^\s*(BEGIN|ROLLBACK|COMMIT|SAVEPOINT|RELEASE|SET|SHOW|EXPLAIN|USE)\b/i',
			$sql
		);
	}

	/**
	 * @param int $flags A bitfield of flags
	 * @param int $bit Bit flag constant
	 * @return bool Whether the bit field has the specified bit flag set
	 */
	final protected function fieldHasBit( int $flags, int $bit ) {
		return ( ( $flags & $bit ) === $bit );
	}

	public function buildExcludedValue( $column ) {
		/* @see Database::doUpsert() */
		// This can be treated like a single value since __VALS is a single row table
		return "(SELECT __$column FROM __VALS)";
	}

	public function savepointSqlText( $identifier ) {
		return 'SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
	}

	public function releaseSavepointSqlText( $identifier ) {
		return 'RELEASE SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
	}

	public function rollbackToSavepointSqlText( $identifier ) {
		return 'ROLLBACK TO SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
	}

	public function rollbackSqlText() {
		return 'ROLLBACK';
	}

	public function dispatchingInsertSqlText( $table, $rows, $options ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$rows ) {
			return false;
		}

		$options = $this->normalizeOptions( $options );
		if ( $this->isFlagInOptions( 'IGNORE', $options ) ) {
			return $this->insertNonConflictingSqlText( $table, $rows );
		} else {
			return $this->insertSqlText( $table, $rows );
		}
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
	 * @return string[]|null List of columns that defines a single unique index, or null for
	 *   a legacy fallback to plain insert.
	 * @since 1.35
	 */
	final public function normalizeUpsertParams( $uniqueKeys, &$rows ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$rows ) {
			return null;
		}
		if ( !$uniqueKeys ) {
			// For backwards compatibility, allow insertion of rows with no applicable key
			$this->logger->warning(
				"upsert/replace called with no unique key",
				[
					'exception' => new RuntimeException(),
					'db_log_category' => 'sql',
				]
			);
			return null;
		}
		$identityKey = $this->normalizeUpsertKeys( $uniqueKeys );
		if ( $identityKey ) {
			$allDefaultKeyValues = $this->assertValidUpsertRowArray( $rows, $identityKey );
			if ( $allDefaultKeyValues ) {
				// For backwards compatibility, allow insertion of rows with all-NULL
				// values for the unique columns (e.g. for an AUTOINCREMENT column)
				$this->logger->warning(
					"upsert/replace called with all-null values for unique key",
					[
						'exception' => new RuntimeException(),
						'db_log_category' => 'sql',
					]
				);
				return null;
			}
		}
		return $identityKey;
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
	 * @return string[]|null List of columns that defines a single unique index,
	 *   or null for a legacy fallback to plain insert.
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
	 * @param string[] $identityKey Columns of the (unique) identity key to UPSERT upon
	 * @return bool Whether all the rows have NULL/absent values for all identity key columns
	 * @since 1.37
	 */
	final protected function assertValidUpsertRowArray( array $rows, array $identityKey ) {
		$numNulls = 0;
		foreach ( $rows as $row ) {
			foreach ( $identityKey as $column ) {
				$numNulls += ( isset( $row[$column] ) ? 0 : 1 );
			}
		}

		if (
			$numNulls &&
			$numNulls !== ( count( $rows ) * count( $identityKey ) )
		) {
			throw new DBLanguageError(
				"NULL/absent values for unique key (" . implode( ',', $identityKey ) . ")"
			);
		}

		return (bool)$numNulls;
	}

	/**
	 * @param array $set Combined column/literal assignment map and SQL assignment list
	 * @param string[] $identityKey Columns of the (unique) identity key to UPSERT upon
	 * @param array<int,array> $rows List of rows to upsert
	 * @since 1.37
	 */
	final public function assertValidUpsertSetArray(
		array $set,
		array $identityKey,
		array $rows
	) {
		// Sloppy callers might construct the SET array using the ROW array, leaving redundant
		// column definitions for identity key columns. Detect this for backwards compatibility.
		$soleRow = ( count( $rows ) == 1 ) ? reset( $rows ) : null;
		// Disallow value changes for any columns in the identity key. This avoids additional
		// insertion order dependencies that are unwieldy and difficult to implement efficiently
		// in PostgreSQL.
		foreach ( $set as $k => $v ) {
			if ( is_string( $k ) ) {
				// Key is a column name and value is a literal (e.g. string, int, null, ...)
				if ( in_array( $k, $identityKey, true ) ) {
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
							"Cannot reassign column '$k' since it belongs to identity key"
						);
					}
				}
			} elseif ( preg_match( '/^([a-zA-Z0-9_]+)\s*=/', $v, $m ) ) {
				// Value is of the form "<unquoted alphanumeric column> = <SQL expression>"
				if ( in_array( $m[1], $identityKey, true ) ) {
					throw new DBLanguageError(
						"Cannot reassign column '{$m[1]}' since it belongs to identity key"
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
	 * Override this in derived classes to provide variables for tables.sql
	 * and SQL patch files.
	 *
	 * @stable to override
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
	 * @stable to override
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
						return $this->indexName( $m[2] );
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

	public function lockSQLText( $lockName, $timeout ) {
		throw new RuntimeException( 'locking must be implemented in subclasses' );
	}

	public function lockIsFreeSQLText( $lockName ) {
		throw new RuntimeException( 'locking must be implemented in subclasses' );
	}

	public function unlockSQLText( $lockName ) {
		throw new RuntimeException( 'locking must be implemented in subclasses' );
	}
}
