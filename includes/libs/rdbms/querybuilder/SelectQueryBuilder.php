<?php

namespace Wikimedia\Rdbms;

class SelectQueryBuilder extends JoinGroupBase {
	/**
	 * @var array The fields to be passed to IDatabase::select()
	 */
	private $fields = [];

	/**
	 * @var array The conditions to be passed to IDatabase::select()
	 */
	private $conds = [];

	/**
	 * @var string The caller (function name) to be passed to IDatabase::select()
	 */
	private $caller = __CLASS__;

	/**
	 * @var array The options to be passed to IDatabase::select()
	 */
	private $options = [];

	/**
	 * @var int An integer used to assign automatic aliases to tables and groups
	 */
	private $nextAutoAlias = 1;

	/** @var IDatabase */
	private $db;

	/**
	 * @internal
	 *
	 * @param IDatabase $db
	 */
	public function __construct( IDatabase $db ) {
		$this->db = $db;
	}

	/**
	 * Change the IDatabase object the query builder is bound to. The specified
	 * IDatabase will subsequently be used to execute the query.
	 *
	 * @param IDatabase $db
	 * @return $this
	 */
	public function connection( IDatabase $db ) {
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
	 * The parameters must be formatted as required by Database::select. For
	 * example, JoinGroup cannot be used.
	 *
	 * @param array $info Associative array of query info, with keys:
	 *   - tables: The raw array of tables to be passed to Database::select()
	 *   - fields: The fields
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - join_conds: The join conditions
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
		return $this;
	}

	/**
	 * Given a table or table array as might be passed to Database::select(),
	 * append it to the existing tables, interpreting nested arrays as join
	 * groups.
	 *
	 * This can be used to interface with existing code that expresses join
	 * groups as nested arrays. In new code, join groups should generally
	 * be created with newJoinGroup(), which provides a fluent interface.
	 *
	 * @param string|array $tables
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
	 * @param string $table The table name
	 * @param string|null $alias The table alias, or null for no alias
	 * @return $this
	 */
	public function from( $table, $alias = null ) {
		return $this->table( $table, $alias );
	}

	/**
	 * Add multiple tables. It's recommended to use join() and leftJoin() instead in new code.
	 *
	 * @param string[] $tables
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
	 * @see IDatabase::select()
	 *
	 * @param string|string[] $fields
	 * @return $this
	 */
	public function fields( $fields ) {
		if ( is_array( $fields ) ) {
			$this->fields = array_merge( $this->fields, $fields );
		} else {
			$this->fields[] = $fields;
		}
		return $this;
	}

	/**
	 * Add a field or an array of fields to the query. Alias for fields().
	 *
	 * @param string|string[] $fields
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
	 * @param string|null $alias
	 * @return $this
	 */
	public function field( $field, $alias = null ) {
		if ( $alias === null ) {
			$this->fields[] = $field;
		} else {
			$this->fields[$alias] = $field;
		}
		return $this;
	}

	/**
	 * Add conditions to the query. The supplied conditions will be appended
	 * to the existing conditions, separated by AND.
	 *
	 * @param string|array $conds
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
	 *    - IDatabase::buildLike()
	 *    - IDatabase::conditional()
	 *
	 * Untrusted user input is safe in the values of string keys, however untrusted
	 * input must not be used in the array key names or in the values of numeric keys.
	 * Escaping of untrusted input used in values of numeric keys should be done via
	 * IDatabase::addQuotes()
	 *
	 * @return $this
	 */
	public function where( $conds ) {
		if ( is_array( $conds ) ) {
			$this->conds = array_merge( $this->conds, $conds );
		} else {
			$this->conds[] = $conds;
		}
		return $this;
	}

	/**
	 * Add conditions to the query. Alias for where().
	 *
	 * @param string|array $conds
	 * @return $this
	 */
	public function andWhere( $conds ) {
		return $this->where( $conds );
	}

	/**
	 * Add conditions to the query. Alias for where().
	 *
	 * @param string|array $conds
	 * @return $this
	 */
	public function conds( $conds ) {
		return $this->where( $conds );
	}

	/**
	 * Manually append to the $join_conds array which will be passed to
	 * IDatabase::select(). This is not recommended for new code. Instead,
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
	 * Add a GROUP BY clause. May be either an SQL fragment string naming a
	 * field or expression to group by, or an array of such SQL fragments.
	 *
	 * If there is an existing GROUP BY clause, the new one will be appended.
	 *
	 * @param string|string[] $group
	 * @return $this
	 */
	public function groupBy( $group ) {
		$this->mergeOption( 'GROUP BY', $group );
		return $this;
	}

	/**
	 * Add a HAVING clause. May be either an string containing a HAVING clause
	 * or an array of conditions building the HAVING clause. If an array is
	 * given, the conditions constructed from each element are combined with
	 * AND.
	 *
	 * If there is an existing HAVING clause, the new one will be appended.
	 *
	 * @param string|string[] $having
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
	 * @param string|null $direction ASC or DESC. If this is null then $fields
	 *   is assumed to optionally contain ASC or DESC after each field name.
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
	 * the values, as in the USE INDEX option to IDatabase::select(). The
	 * array will be merged with the existing value.
	 *
	 * @param string|string[] $index
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
	 * the values, as in the IGNORE INDEX option to IDatabase::select(). The
	 * array will be merged with the existing value.
	 *
	 * @param string|string[] $index
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
	 * Enable the STRAIGHT_JOIN option.
	 *
	 * @return $this
	 */
	public function straightJoin() {
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
	 * IDatabase::select()
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
	 * IDatabase::select().
	 *
	 * @param array $options
	 * @return $this
	 */
	public function options( array $options ) {
		$this->options = array_merge( $this->options, $options );
		return $this;
	}

	/**
	 * Set the method name to be included in an SQL comment.
	 *
	 * @param string $fname
	 * @return $this
	 */
	public function caller( $fname ) {
		$this->caller = $fname;
		return $this;
	}

	/**
	 * Run the constructed SELECT query and return all results.
	 *
	 * @return IResultWrapper
	 */
	public function fetchResultSet() {
		return $this->db->select( $this->tables, $this->fields, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Run the constructed SELECT query, and return a single field extracted
	 * from the first result row. This may only be called when only one field
	 * has been added to the builder.
	 *
	 * @return mixed
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
	 */
	public function fetchFieldValues() {
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
	 * @return bool|\stdClass
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
	 * @return int
	 */
	public function fetchRowCount() {
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
	 *
	 * @return int
	 */
	public function estimateRowCount() {
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
	 * Run the SELECT query with the FOR UPDATE option. The field list is ignored.
	 *
	 * @return int
	 */
	public function lockForUpdate() {
		return $this->db->lockForUpdate( $this->tables, $this->conds, $this->caller,
			$this->options, $this->joinConds );
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
		return $this->db->selectSQLText( $this->tables, $this->fields, $this->conds, $this->caller,
			$this->options, $this->joinConds );
	}

	/**
	 * Get an associative array describing the query in terms of its raw parameters to
	 * Database::select(). This can be used to interface with legacy code.
	 *
	 * @return array The query info array, with keys:
	 *   - tables: The table array
	 *   - fields: The fields
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - join_conds: The join conditions
	 */
	public function getQueryInfo() {
		return [
			'tables' => $this->tables,
			'fields' => $this->fields,
			'conds' => $this->conds,
			'options' => $this->options,
			'join_conds' => $this->joinConds
		];
	}
}
