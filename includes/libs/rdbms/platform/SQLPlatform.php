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
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database\DbQuoter;
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
	/** @var string|null */
	protected $schema;
	/** @var string */
	private $prefix;
	/** @var DbQuoter */
	protected $quoter;
	/** @var LoggerInterface */
	protected $logger;

	public function __construct( DbQuoter $quoter, LoggerInterface $logger = null, $schema = null, $prefix = '' ) {
		$this->quoter = $quoter;
		$this->logger = $logger ?? new NullLogger();
		$this->schema = $schema;
		$this->prefix = $prefix;
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
		$this->prefix = $prefix;
	}

	public function setSchema( $schema ) {
		$this->schema = $schema;
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
	 * @param string|bool $alias Alias (optional)
	 * @return string SQL name for aliased field. Will not alias a field to its own name
	 */
	protected function fieldNameWithAlias( $name, $alias = false ) {
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
	 * @param string|bool $alias Table alias (optional)
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
					'exception' => new \RuntimeException(),
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
					: $this->prefix;
			} else {
				$database = '';
				$schema = $this->relationSchemaQualifier(); # Default schema
				$prefix = $this->prefix; # Default prefix
			}
		}

		return [ $database, $schema, $prefix, $table ];
	}

	/**
	 * @stable to override
	 * @return string Schema to use to qualify relations in queries
	 */
	protected function relationSchemaQualifier() {
		return $this->schema;
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
}
