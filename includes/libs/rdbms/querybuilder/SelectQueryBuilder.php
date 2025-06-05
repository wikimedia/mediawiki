<?php

namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * Build SELECT queries with a fluent interface.
 *
 * Each query builder object must be used for a single database query only,
 * and not be reused afterwards. To run multiple similar queries, you can
 * create a query builder to set up most of your query, which you can use
 * as a "template" to clone. You can then modify the cloned object for
 * each individual query.
 *
 * Note that the methods in this class are not stable to override.
 * This class may be extended to create query builders for specific database
 * tables, such {@link \MediaWiki\Page\PageSelectQueryBuilder}, whilst still
 * providing the same fluent interface for adding arbitrary additional
 * conditions and such.
 *
 * @since 1.35
 * @stable to extend
 * @ingroup Database
 */
class SelectQueryBuilder extends JoinGroupBase {

	/** sort the results in ascending order */
	public const SORT_ASC = 'ASC';

	/** sort the results in descending order */
	public const SORT_DESC = 'DESC';

	/**
	 * @var array The fields to be passed to IReadableDatabase::select()
	 */
	private $fields = [];

	/**
	 * @var array Like $fields, but always an assoc array, for checking which field/alias names are already used
	 */
	private $aliasesUsed = [];

	/**
	 * @var array The conditions to be passed to IReadableDatabase::select()
	 */
	private $conds = [];

	/**
	 * @var string The caller (function name) to be passed to IReadableDatabase::select()
	 */
	private $caller = __CLASS__;

	/**
	 * @var array The options to be passed to IReadableDatabase::select()
	 */
	protected $options = [];

	/**
	 * @var int An integer used to assign automatic aliases to tables and groups
	 */
	private $nextAutoAlias = 1;

	/**
	 * @var bool True if $this->caller has been set
	 */
	private $isCallerOverridden = false;

	protected IReadableDatabase $db;

	/**
	 * Only for use in subclasses. To create a SelectQueryBuilder instance,
	 * use `$db->newSelectQueryBuilder()` instead.
	 */
	public function __construct( IReadableDatabase $db ) {
		$this->db = $db;
	}

	/**
	 * Change the IReadableDatabase object the query builder is bound to. The specified
	 * IReadableDatabase will subsequently be used to execute the query.
	 *
	 * @param IReadableDatabase $db
	 * @return $this
	 */
	public function connection( IReadableDatabase $db ) {
		if ( $this->db->getType() !== $db->getType() ) {
			throw new \InvalidArgumentException( __METHOD__ .
				' cannot switch to a database of a different type.' );
		}
		$this->db = $db;
		return $this;
	}

	/**
	 * Set the query parameters to the given values, appending to the values
	 * which were already set. This can be used to interface with legacy code.
	 * If a key is omitted, the previous value will be retained.
	 *
	 * The parameters must be formatted as required by IReadableDatabase::select. For
	 * example, JoinGroup cannot be used.
	 *
	 * @param array $info Associative array of query info, with keys:
	 *   - tables: The raw array of tables to be passed to IReadableDatabase::select()
	 *   - fields: The fields
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - join_conds: The join conditions
	 *   - joins: Alias for join_conds. If both joins and join_conds are
	 *     specified, the values will be merged.
	 *   - caller: The caller signature
	 *
	 * @return $this
	 */
	public function queryInfo( $info ) {
		if ( isset( $info['tables'] ) ) {
			$this->rawTables( $info['tables'] );
		}
		if ( isset( $info['fields'] ) ) {
			$this->fields( $info['fields'] );
		}
		if ( isset( $info['conds'] ) ) {
			$this->where( $info['conds'] );
		}
		if ( isset( $info['options'] ) ) {
			$this->options( (array)$info['options'] );
		}
		if ( isset( $info['join_conds'] ) ) {
			$this->joinConds( (array)$info['join_conds'] );
		}
		if ( isset( $info['joins'] ) ) {
			$this->joinConds( (array)$info['joins'] );
		}
		if ( isset( $info['caller'] ) ) {
			$this->caller( $info['caller'] );
		}
		return $this;
	}

	/**
	 * Given a table or table array as might be passed to IReadableDatabase::select(),
	 * append it to the existing tables, interpreting nested arrays as join
	 * groups.
	 *
	 * This can be used to interface with existing code that expresses join
	 * groups as nested arrays. In new code, join groups should generally
	 * be created with newJoinGroup(), which provides a fluent interface.
	 *
	 * @param string|array $tables Table references; see {@link IReadableDatabase::select}
	 *  for details
	 * @return $this
	 */
	public function rawTables( $tables ) {
		if ( is_array( $tables ) ) {
			$this->tables = array_merge( $this->tables, $tables );
		} elseif ( is_string( $tables ) ) {
			$this->tables[] = $tables;
		} else {
			throw new \InvalidArgumentException( __METHOD__ .
				': $tables must be a string or array' );
		}
		return $this;
	}

	/**
	 * Merge another query builder with this one. Append the other builder's
	 * tables, joins, fields, conditions and options to this one.
	 *
	 * @since 1.41
	 * @param SelectQueryBuilder $builder
	 * @return $this
	 */
	public function merge( SelectQueryBuilder $builder ) {
		$this->rawTables( $builder->tables );
		$this->fields( $builder->fields );
		$this->where( $builder->conds );
		$this->options( $builder->options );
		$this->joinConds( $builder->joinConds );
		if ( $builder->isCallerOverridden ) {
			$this->caller( $builder->caller );
		}
		return $this;
	}

	/**
	 * Get an empty SelectQueryBuilder which can be used to build a subquery
	 * of this query.
	 * @return SelectQueryBuilder
	 */
	public function newSubquery() {
		return new self( $this->db );
	}

	/**
	 * Add a single table to the SELECT query. Alias for table().
	 *
	 * @param string|JoinGroup|Subquery|SelectQueryBuilder $table Table reference; see {@link table}
	 *  for details
	 * @param-taint $table exec_sql
	 * @param string|null $alias The table alias, or null for no alias
	 * @param-taint $alias exec_sql
	 * @return $this
	 */
	public function from( $table, $alias = null ) {
		return $this->table( $table, $alias );
	}

	/**
	 * Add multiple tables. It's recommended to use join() and leftJoin() instead in new code.
	 *
	 * @param string[] $tables Table references (string keys are aliases). See {@link table}
	 *  for details.
	 * @param-taint $tables exec_sql
	 * @return $this
	 */
	public function tables( $tables ) {
		foreach ( $tables as $alias => $table ) {
			if ( is_string( $alias ) ) {
				$this->table( $table, $alias );
			} else {
				$this->table( $table );
			}
		}
		return $this;
	}

	/**
	 * Add a field or an array of fields to the query. Each field is an SQL
	 * fragment. If the array key is non-numeric, the key is taken to be an
	 * alias for the field.
	 *
	 * @see IReadableDatabase::select()
	 *
	 * @param string|string[] $fields
	 * @param-taint $fields exec_sql
	 * @return $this
	 */
	public function fields( $fields ) {
		if ( !is_array( $fields ) ) {
			return $this->field( $fields );
		}

		foreach ( $fields as $alias => $field ) {
			$this->field( $field, is_numeric( $alias ) ? null : $alias );
		}

		return $this;
	}

	/**
	 * Add a field or an array of fields to the query. Alias for fields().
	 *
	 * @param string|string[] $fields
	 * @param-taint $fields exec_sql
	 * @return $this
	 */
	public function select( $fields ) {
		return $this->fields( $fields );
	}

	/**
	 * Add a single field to the query, optionally with an alias. The field is
	 * an SQL fragment. It is unsafe to pass user input to this function.
	 *
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string|null $alias
	 * @param-taint $alias exec_sql
	 * @return $this
	 */
	public function field( $field, $alias = null ) {
		$usedAlias = $alias ?? $field;
		if ( isset( $this->aliasesUsed[$usedAlias] ) ) {
			if ( $this->aliasesUsed[$usedAlias] !== $field ) {
				throw new \LogicException( __METHOD__ .
					": field alias \"$usedAlias\" is already used for a different field" );
			}
			// Identical field/alias combination was already added, don't add it again
			return $this;
		}
		$this->aliasesUsed[$usedAlias] = $field;

		if ( $alias === null ) {
			$this->fields[] = $field;
		} else {
			$this->fields[$alias] = $field;
		}
		return $this;
	}

	/**
	 * Remove all fields from the query.
	 *
	 * @return $this
	 */
	public function clearFields() {
		$this->fields = [];
		$this->aliasesUsed = [];
		return $this;
	}

	/**
	 * Add conditions to the query. The supplied conditions will be appended
	 * to the existing conditions, separated by AND.
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 * @param-taint $conds exec_sql_numkey
	 *
	 * May be either a string containing a single condition, or an array of
	 * conditions. If an array is given, the conditions constructed from each
	 * element are combined with AND.
	 *
	 * Array elements may take one of two forms:
	 *
	 *   - Elements with a numeric key are interpreted as raw SQL fragments.
	 *   - Elements with a string key are interpreted as equality conditions,
	 *     where the key is the field name.
	 *     - If the value of such an array element is a scalar (such as a
	 *       string), it will be treated as data and thus quoted appropriately.
	 *       If it is null, an IS NULL clause will be added.
	 *     - If the value is an array, an IN (...) clause will be constructed
	 *       from its non-null elements, and an IS NULL clause will be added
	 *       if null is present, such that the field may match any of the
	 *       elements in the array. The non-null elements will be quoted.
	 *
	 * Note that expressions are often DBMS-dependent in their syntax.
	 * DBMS-independent wrappers are provided for constructing several types of
	 * expression commonly used in condition queries. See:
	 *    - IReadableDatabase::buildLike()
	 *    - IReadableDatabase::conditional()
	 *
	 * Untrusted user input is safe in the values of string keys, however untrusted
	 * input must not be used in the array key names or in the values of numeric keys.
	 * Escaping of untrusted input used in values of numeric keys should be done via
	 * IReadableDatabase::addQuotes()
	 *
	 * @return $this
	 */
	public function where( $conds ) {
		if ( is_array( $conds ) ) {
			foreach ( $conds as $key => $cond ) {
				if ( is_int( $key ) ) {
					$this->conds[] = $cond;
				} elseif ( isset( $this->conds[$key] ) ) {
					// @phan-suppress-previous-line PhanTypeMismatchDimFetch
					// T288882
					$this->conds[] = $this->db->makeList(
						[ $key => $cond ], IReadableDatabase::LIST_AND );
				} else {
					$this->conds[$key] = $cond;
				}
			}
		} else {
			$this->conds[] = $conds;
		}
		return $this;
	}

	/**
	 * Add conditions to the query. Alias for where().
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 * @param-taint $conds exec_sql_numkey
	 * @return $this
	 */
	public function andWhere( $conds ) {
		return $this->where( $conds );
	}

	/**
	 * Add conditions to the query. Alias for where().
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 * @param-taint $conds exec_sql_numkey
	 * @return $this
	 */
	public function conds( $conds ) {
		return $this->where( $conds );
	}

	/**
	 * Manually append to the $join_conds array which will be passed to
	 * IReadableDatabase::select(). This is not recommended for new code. Instead,
	 * join() and leftJoin() should be used.
	 *
	 * @param array $joinConds
	 * @return $this
	 */
	public function joinConds( array $joinConds ) {
		$this->joinConds = array_merge( $this->joinConds, $joinConds );
		return $this;
	}

	/**
	 * Get a table alias which is unique to this SelectQueryBuilder
	 *
	 * @return string
	 */
	protected function getAutoAlias() {
		return 'sqb' . ( $this->nextAutoAlias++ );
	}

	/**
	 * Create a parenthesized group of joins which can be added to the object
	 * like a table. The group is initially empty.
	 *
	 * @return JoinGroup
	 */
	public function newJoinGroup() {
		return new JoinGroup( $this->getAutoAlias() );
	}

	/**
	 * Set the offset. Skip this many rows at the start of the result set. Offset
	 * with limit() can theoretically be used for paging through a result set,
	 * but this is discouraged for performance reasons.
	 *
	 * If the query builder already has an offset, the old offset will be discarded.
	 *
	 * @param int $offset
	 * @return $this
	 */
	public function offset( $offset ) {
		$this->options['OFFSET'] = $offset;
		return $this;
	}

	/**
	 * Set the query limit. Return at most this many rows. The rows are sorted
	 * and then the first rows are taken until the limit is reached. Limit
	 * is applied to a result set after offset.
	 *
	 * If the query builder already has a limit, the old limit will be discarded.
	 *
	 * @param int $limit
	 * @return $this
	 */
	public function limit( $limit ) {
		$this->options['LIMIT'] = $limit;
		return $this;
	}

	/**
	 * Enable the LOCK IN SHARE MODE option. Lock the returned rows so that
	 * they can't be changed until the next COMMIT. Cannot be used with
	 * aggregate functions (COUNT, MAX, etc., but also DISTINCT).
	 *
	 * @return $this
	 */
	public function lockInShareMode() {
		$this->options[] = 'LOCK IN SHARE MODE';
		return $this;
	}

	/**
	 * Enable the FOR UPDATE option. Lock the returned rows so that
	 * they can't be changed until the next COMMIT. Cannot be used with
	 * aggregate functions (COUNT, MAX, etc., but also DISTINCT).
	 *
	 * @return $this
	 */
	public function forUpdate() {
		$this->options[] = 'FOR UPDATE';
		return $this;
	}

	/**
	 * Enable the DISTINCT option. Return only unique result rows.
	 *
	 * @return $this
	 */
	public function distinct() {
		$this->options[] = 'DISTINCT';
		return $this;
	}

	/**
	 * Set MAX_EXECUTION_TIME for queries.
	 *
	 * @param int $time maximum allowed time in milliseconds
	 * @return $this
	 */
	public function setMaxExecutionTime( int $time ) {
		$this->options['MAX_EXECUTION_TIME'] = $time;
		return $this;
	}

	/**
	 * Add a GROUP BY clause. May be either an SQL fragment string naming a
	 * field or expression to group by, or an array of such SQL fragments.
	 *
	 * If there is an existing GROUP BY clause, the new one will be appended.
	 *
	 * @param string|string[] $group
	 * @param-taint $group exec_sql
	 * @return $this
	 */
	public function groupBy( $group ) {
		$this->mergeOption( 'GROUP BY', $group );
		return $this;
	}

	/**
	 * Add a HAVING clause. May be either a string containing a HAVING clause,
	 * an IExpression instance, or an array of conditions building the HAVING clause.
	 * If an array is given, the conditions constructed from each element are combined with
	 * AND.
	 *
	 * If there is an existing HAVING clause, the new one will be appended.
	 *
	 * @param string|string[]|IExpression|IExpression[] $having
	 * @param-taint $having exec_sql_numkey
	 * @return $this
	 */
	public function having( $having ) {
		$this->mergeOption( 'HAVING', $having );
		return $this;
	}

	/**
	 * Set the ORDER BY clause. If it has already been set, append the
	 * additional fields to it.
	 *
	 * @param string[]|string $fields The field or list of fields to order by.
	 * @param-taint $fields exec_sql
	 * @param string|null $direction Sorting direction applied to all fields,
	 *   self::SORT_ASC or self::SORT_DESC. If different fields need to be sorted in opposite
	 *   directions, then this parameter must be omitted, and $fields must contain 'ASC' or 'DESC'
	 *   after each field name.
	 * @param-taint $direction exec_sql
	 * @return $this
	 */
	public function orderBy( $fields, $direction = null ) {
		if ( $direction === null ) {
			$this->mergeOption( 'ORDER BY', $fields );
		} elseif ( is_array( $fields ) ) {
			$fieldsWithDirection = [];
			foreach ( $fields as $field ) {
				$fieldsWithDirection[] = "$field $direction";
			}
			$this->mergeOption( 'ORDER BY', $fieldsWithDirection );
		} else {
			$this->mergeOption( 'ORDER BY', "$fields $direction" );
		}
		return $this;
	}

	/**
	 * Add a value to an option which may be not set or a string or array.
	 *
	 * @param string $name
	 * @param string|string[] $newArrayOrValue
	 */
	private function mergeOption( $name, $newArrayOrValue ) {
		$value = isset( $this->options[$name] )
			? (array)$this->options[$name] : [];
		if ( is_array( $newArrayOrValue ) ) {
			$value = array_merge( $value, $newArrayOrValue );
		} else {
			$value[] = $newArrayOrValue;
		}
		$this->options[$name] = $value;
	}

	/**
	 * Set a USE INDEX option.
	 *
	 * If a string is given, the index hint is applied to the most recently
	 * appended table or alias. If an array is given, it is assumed to be an
	 * associative array with the alias names in the keys and the indexes in
	 * the values, as in the USE INDEX option to IReadableDatabase::select(). The
	 * array will be merged with the existing value.
	 *
	 * @param string|string[] $index
	 * @param-taint $index exec_sql
	 * @return $this
	 */
	public function useIndex( $index ) {
		$this->setIndexHint( 'USE INDEX', $index );
		return $this;
	}

	/**
	 * Set the IGNORE INDEX option.
	 *
	 * If a string is given, the index hint is applied to the most recently
	 * appended table or alias. If an array is given, it is assumed to be an
	 * associative array with the alias names in the keys and the indexes in
	 * the values, as in the IGNORE INDEX option to IReadableDatabase::select(). The
	 * array will be merged with the existing value.
	 *
	 * @param string|string[] $index
	 * @param-taint $index exec_sql
	 * @return $this
	 */
	public function ignoreIndex( $index ) {
		$this->setIndexHint( 'IGNORE INDEX', $index );
		return $this;
	}

	/**
	 * Private helper for methods that set index hints.
	 *
	 * @param string $type
	 * @param string|string[] $value
	 */
	private function setIndexHint( $type, $value ) {
		if ( !isset( $this->options[$type] ) ) {
			$this->options[$type] = [];
		} elseif ( !is_array( $this->options[$type] ) ) {
			throw new \UnexpectedValueException(
				__METHOD__ . ": The $type option cannot be appended to " .
				'because it is not an array. This may have been caused by a prior ' .
				'call to option() or options().' );
		}
		if ( is_array( $value ) ) {
			$this->options[$type] = array_merge( $this->options[$type], $value );
		} elseif ( $this->lastAlias === null ) {
			throw new \UnexpectedValueException(
				__METHOD__ . ': Cannot append index value since there is no' .
				'prior table' );
		} else {
			$this->options[$type][$this->lastAlias] = $value;
		}
	}

	/**
	 * Make the query be an EXPLAIN SELECT query instead of a SELECT query.
	 *
	 * @return $this
	 */
	public function explain() {
		$this->options['EXPLAIN'] = true;
		return $this;
	}

	/**
	 * Enable the STRAIGHT_JOIN query option.
	 *
	 * @return $this
	 */
	public function straightJoinOption() {
		$this->options[] = 'STRAIGHT_JOIN';
		return $this;
	}

	/**
	 * Enable the SQL_BIG_RESULT option.
	 *
	 * @return $this
	 */
	public function bigResult() {
		$this->options[] = 'SQL_BIG_RESULT';
		return $this;
	}

	/**
	 * Enable the SQL_BUFFER_RESULT option.
	 *
	 * @return $this
	 */
	public function bufferResult() {
		$this->options[] = 'SQL_BUFFER_RESULT';
		return $this;
	}

	/**
	 * Enable the SQL_SMALL_RESULT option.
	 *
	 * @return $this
	 */
	public function smallResult() {
		$this->options[] = 'SQL_SMALL_RESULT';
		return $this;
	}

	/**
	 * Enable the SQL_CALC_FOUND_ROWS option.
	 *
	 * @return $this
	 */
	public function calcFoundRows() {
		$this->options[] = 'SQL_CALC_FOUND_ROWS';
		return $this;
	}

	/**
	 * Manually set an option in the $options array to be passed to
	 * IReadableDatabase::select()
	 *
	 * @param string $name The option name
	 * @param mixed $value The option value, or null for a boolean option
	 * @return $this
	 */
	public function option( $name, $value = null ) {
		if ( $value === null ) {
			$this->options[] = $name;
		} else {
			$this->options[$name] = $value;
		}
		return $this;
	}

	/**
	 * Manually set multiple options in the $options array to be passed to
	 * IReadableDatabase::select().
	 *
	 * @param array $options
	 * @return $this
	 */
	public function options( array $options ) {
		$this->options = array_merge( $this->options, $options );
		return $this;
	}

	/**
	 * @param int $recency Bitfield of IDBAccessObject::READ_* constants
	 * @return $this
	 */
	public function recency( $recency ) {
		if ( ( $recency & IDBAccessObject::READ_EXCLUSIVE ) == IDBAccessObject::READ_EXCLUSIVE ) {
			$this->forUpdate();
		} elseif ( ( $recency & IDBAccessObject::READ_LOCKING ) == IDBAccessObject::READ_LOCKING ) {
			$this->lockInShareMode();
		}
		return $this;
	}

	/**
	 * Set the method name to be included in an SQL comment.
	 *
	 * @param string $fname
	 * @param-taint $fname exec_sql
	 * @return $this
	 */
	public function caller( $fname ) {
		$this->caller = $fname;
		$this->isCallerOverridden = true;
		return $this;
	}

	/**
	 * get the method name of the caller, for use in sub classes
	 *
	 * @since 1.43
	 */
	final protected function getCaller(): string {
		return $this->caller;
	}

	/**
	 * Run the constructed SELECT query and return all results.
	 *
	 * @return IResultWrapper
	 * @return-taint tainted
	 */
	public function fetchResultSet(): IResultWrapper {
		return $this->db->select( $this->tables, $this->fields, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Run the constructed SELECT query, and return a single field extracted
	 * from the first result row. This may only be called when only one field
	 * has been added to the builder.
	 *
	 * @return mixed|false The value from the field, or false if nothing was found
	 * @return-taint tainted
	 */
	public function fetchField() {
		if ( count( $this->fields ) !== 1 ) {
			throw new \UnexpectedValueException(
				__METHOD__ . ' expects the query to have only one field' );
		}
		$field = reset( $this->fields );
		return $this->db->selectField( $this->tables, $field, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Run the constructed SELECT query, and extract a single field from each
	 * result row, returning an array containing all the values. This may only
	 * be called when only one field has been added to the builder.
	 *
	 * @return array
	 * @return-taint tainted
	 */
	public function fetchFieldValues(): array {
		if ( count( $this->fields ) !== 1 ) {
			throw new \UnexpectedValueException(
				__METHOD__ . ' expects the query to have only one field' );
		}
		$field = reset( $this->fields );
		return $this->db->selectFieldValues( $this->tables, $field, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Run the constructed SELECT query, and return the first result row. If
	 * there were no results, return false.
	 *
	 * @return \stdClass|false
	 * @return-taint tainted
	 */
	public function fetchRow() {
		return $this->db->selectRow( $this->tables, $this->fields, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Run the SELECT query, and return the number of results. This typically
	 * uses a subquery to discard the actual results on the server side, and
	 * is useful when counting rows with a limit.
	 *
	 * To count rows without a limit, it's more efficient to use a normal
	 * COUNT() expression, for example:
	 *
	 *   $queryBuilder->select( 'COUNT(*)' )->from( 'page' )->fetchField()
	 */
	public function fetchRowCount(): int {
		return $this->db->selectRowCount( $this->tables, $this->getRowCountVar(), $this->conds,
			$this->caller, $this->options, $this->joinConds );
	}

	/**
	 * Estimate the number of rows in dataset
	 *
	 * MySQL allows you to estimate the number of rows that would be returned
	 * by a SELECT query, using EXPLAIN SELECT. The estimate is provided using
	 * index cardinality statistics, and is notoriously inaccurate, especially
	 * when large numbers of rows have recently been added or deleted.
	 */
	public function estimateRowCount(): int {
		return $this->db->estimateRowCount( $this->tables, $this->getRowCountVar(), $this->conds,
			$this->caller, $this->options, $this->joinConds );
	}

	/**
	 * Private helper which extracts a field suitable for row counting from the
	 * fields array
	 *
	 * @return string
	 */
	private function getRowCountVar() {
		if ( count( $this->fields ) === 0 ) {
			return '*';
		} elseif ( count( $this->fields ) === 1 ) {
			return reset( $this->fields );
		} else {
			throw new \UnexpectedValueException(
				__METHOD__ . ' expects the query to have at most one field' );
		}
	}

	/**
	 * Build a GROUP_CONCAT or equivalent statement for a query.
	 *
	 * This is useful for combining a field for several rows into a single string.
	 * NULL values will not appear in the output, duplicated values will appear,
	 * and the resulting delimiter-separated values have no defined sort order.
	 * Code using the results may need to use the PHP unique() or sort() methods.
	 *
	 * @param string $delim
	 * @return string
	 */
	public function buildGroupConcatField( $delim ) {
		if ( count( $this->fields ) !== 1 ) {
			throw new \UnexpectedValueException(
				__METHOD__ . ' expects the query to have only one field' );
		}
		$field = reset( $this->fields );
		return $this->db->buildGroupConcatField( $delim, $this->tables, $field,
			$this->conds, $this->joinConds );
	}

	/**
	 * Get the SQL query string which would be used by fetchResultSet().
	 *
	 * @return string
	 */
	public function getSQL() {
		// Assume that whoever is calling this method is doing it to build a subquery
		$caller = $this->isCallerOverridden ? $this->caller : ISQLPlatform::CALLER_SUBQUERY;
		return $this->db->selectSQLText( $this->tables, $this->fields, $this->conds, $caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Get an associative array describing the query in terms of its raw parameters to
	 * IReadableDatabase::select(). This can be used to interface with legacy code.
	 *
	 * @param string $joinsName The name of the join_conds key
	 * @return array The query info array, with keys:
	 *   - tables: The table array
	 *   - fields: The fields
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - join_conds: The join conditions. This can also be given a different
	 *     name by passing a $joinsName parameter, since some legacy code uses
	 *     the name "joins".
	 *   - caller: The caller signature
	 */
	public function getQueryInfo( $joinsName = 'join_conds' ) {
		$info = [
			'tables' => $this->tables,
			'fields' => $this->fields,
			'conds' => $this->conds,
			'options' => $this->options,
		];
		if ( $this->caller !== __CLASS__ ) {
			$info['caller'] = $this->caller;
		}
		$info[ $joinsName ] = $this->joinConds;
		return $info;
	}

	/**
	 * Execute the query, but throw away the results. This is intended for
	 * locking select queries. By calling this method, the caller is indicating
	 * that the query is only done to acquire locks on the selected rows. The
	 * field list is optional.
	 *
	 * Either forUpdate() or lockInShareMode() must be called before calling
	 * this method.
	 *
	 * @see self::forUpdate()
	 * @see self::lockInShareMode()
	 *
	 * @since 1.40
	 */
	public function acquireRowLocks(): void {
		if ( !array_intersect( $this->options, [ 'FOR UPDATE', 'LOCK IN SHARE MODE' ] ) ) {
			throw new \UnexpectedValueException( __METHOD__ . ' can only be called ' .
				'after forUpdate() or lockInShareMode()' );
		}
		$fields = $this->fields ?: '1';
		$this->db->select( $this->tables, $fields, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}
}
