<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Build UPDATE queries with a fluent interface.
 *
 * Each query builder object must be used for a single database query only,
 * and not be reused afterwards. To run multiple similar queries, you can
 * create a query builder to set up most of your query, which you can use
 * as a "template" to clone. You can then modify the cloned object for
 * each individual query.
 *
 * @since 1.41
 * @stable to extend
 * @ingroup Database
 */
class UpdateQueryBuilder {
	/**
	 * @var string The table name to be passed to IDatabase::update()
	 */
	private $table = '';

	/**
	 * @var array The set values to be passed to IDatabase::update()
	 */
	private $set = [];

	/**
	 * @var array The conditions to be passed to IDatabase::update()
	 */
	private $conds = [];

	/**
	 * @var string The caller (function name) to be passed to IDatabase::update()
	 */
	private $caller = __CLASS__;

	/**
	 * @var array The options to be passed to IDatabase::update()
	 */
	protected $options = [];

	protected IDatabase $db;

	/**
	 * Only for use in subclasses. To create a UpdateQueryBuilder instance,
	 * use `$db->newUpdateQueryBuilder()` instead.
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
			throw new InvalidArgumentException(
				__METHOD__ . ' cannot switch to a database of a different type.'
			);
		}
		$this->db = $db;
		return $this;
	}

	/**
	 * Set the query parameters to the given values, appending to the values
	 * which were already set. This can be used to interface with legacy code.
	 * If a key is omitted, the previous value will be retained.
	 *
	 * The parameters must be formatted as required by Database::update.
	 *
	 * @param array $info Associative array of query info, with keys:
	 *   - table: The table name to be passed to Database::update()
	 *   - set: The set conditions
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - caller: The caller signature.
	 *
	 * @return $this
	 */
	public function queryInfo( $info ) {
		if ( isset( $info['table'] ) ) {
			$this->table( $info['table'] );
		}
		if ( isset( $info['set'] ) ) {
			$this->set( $info['set'] );
		}
		if ( isset( $info['conds'] ) ) {
			$this->where( $info['conds'] );
		}
		if ( isset( $info['options'] ) ) {
			$this->options( (array)$info['options'] );
		}
		if ( isset( $info['caller'] ) ) {
			$this->caller( $info['caller'] );
		}
		return $this;
	}

	/**
	 * Manually set the table name to be passed to IDatabase::update()
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @return $this
	 */
	public function table( $table ) {
		$this->table = $table;
		return $this;
	}

	/**
	 * Set table for the query. Alias for table().
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @return $this
	 */
	public function update( string $table ) {
		return $this->table( $table );
	}

	/**
	 * Manually set an option in the $options array to be passed to
	 * IDatabase::update()
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
	 * IDatabase::update().
	 *
	 * @param array $options
	 * @return $this
	 */
	public function options( array $options ) {
		$this->options = array_merge( $this->options, $options );
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
			foreach ( $conds as $key => $cond ) {
				if ( is_int( $key ) ) {
					$this->conds[] = $cond;
				} elseif ( isset( $this->conds[$key] ) ) {
					// @phan-suppress-previous-line PhanTypeMismatchDimFetch
					// T288882
					$this->conds[] = $this->db->makeList(
						[ $key => $cond ], IDatabase::LIST_AND );
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
	 * Add SET part to the query. It takes an array containing arrays of column names map to
	 * the set values.
	 *
	 * @param string|array<string,?scalar|RawSQLValue>|array<int,string> $set
	 * @param-taint $set exec_sql_numkey
	 *
	 * Combination map/list where each string-keyed entry maps a column
	 * to a literal assigned value and each integer-keyed value is a SQL expression in the
	 * format of a column assignment within UPDATE...SET. The (column => value) entries are
	 * convenient due to automatic value quoting and conversion of null to NULL. The SQL
	 * assignment format is useful for updates like "column = column + X". All assignments
	 * have no defined execution order, so they should not depend on each other. Do not
	 * modify AUTOINCREMENT or UUID columns in assignments.
	 *
	 * Untrusted user input is safe in the values of string keys, however untrusted
	 * input must not be used in the array key names or in the values of numeric keys.
	 * Escaping of untrusted input used in values of numeric keys should be done via
	 * IDatabase::addQuotes()
	 *
	 * @return $this
	 */
	public function set( $set ) {
		if ( is_array( $set ) ) {
			foreach ( $set as $key => $value ) {
				if ( is_int( $key ) ) {
					$this->set[] = $value;
				} else {
					$this->set[$key] = $value;
				}
			}
		} else {
			$this->set[] = $set;
		}
		return $this;
	}

	/**
	 * Add set values to the query. Alias for set().
	 *
	 * @param string|array<string,?scalar|RawSQLValue>|array<int,string> $set
	 * @param-taint $set exec_sql_numkey
	 * @return $this
	 */
	public function andSet( $set ) {
		return $this->set( $set );
	}

	/**
	 * Enable the IGNORE option.
	 *
	 * Skip update of rows that would cause unique key conflicts.
	 * IDatabase::affectedRows() can be used to determine how many rows were updated.
	 *
	 * @return $this
	 */
	public function ignore() {
		$this->options[] = 'IGNORE';
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
		return $this;
	}

	/**
	 * Run the constructed UPDATE query.
	 */
	public function execute(): void {
		if ( !$this->conds ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects at least one condition to be set' );
		}
		if ( !$this->set ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' can\t have empty $set value' );
		}
		if ( $this->table === '' ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects table not to be empty' );
		}
		// @phan-suppress-next-line SecurityCheck-SQLInjection T401748
		$this->db->update( $this->table, $this->set, $this->conds, $this->caller, $this->options );
	}

	/**
	 * Get an associative array describing the query in terms of its raw parameters to
	 * Database::update(). This can be used to interface with legacy code.
	 *
	 * @return array The query info array, with keys:
	 *   - table: The table name
	 *   - set: The set array
	 *   - conds: The conditions
	 *   - options: The query options
	 *   - caller: The caller signature
	 */
	public function getQueryInfo() {
		$info = [
			'table' => $this->table,
			'set' => $this->set,
			'conds' => $this->conds,
			'options' => $this->options,
		];
		if ( $this->caller !== __CLASS__ ) {
			$info['caller'] = $this->caller;
		}
		return $info;
	}
}
