<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * A query builder for DELETE queries with a fluent interface.
 *
 * Any particular query builder object should only be used for a single database query,
 * and not be reused afterwards. However, to run multiple similar queries,
 * you can create a “template” query builder to set up most of the query,
 * and then clone the object (and potentially modify the clone) for each individual query.
 *
 * @stable to extend
 * @since 1.41
 * @ingroup Database
 */
class DeleteQueryBuilder {
	/**
	 * The table name to be passed to IDatabase::delete()
	 */
	private string $table = '';

	/**
	 * The conditions to be passed to IDatabase::delete()
	 */
	private array $conds = [];

	/**
	 * The caller (function name) to be passed to IDatabase::delete()
	 */
	private string $caller = __CLASS__;

	protected IDatabase $db;

	/**
	 * Only for use in subclasses and Database::newDeleteQueryBuilder.
	 * To create a DeleteQueryBuilder instance, use `$db->newDeleteQueryBuilder()` instead.
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
	public function connection( IDatabase $db ): DeleteQueryBuilder {
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
	 * The parameters must be formatted as required by Database::delete.
	 *
	 * @param array $info Associative array of query info, with keys:
	 *   - table: The table name to be passed to IDatabase::delete()
	 *   - conds: The conditions
	 *   - caller: The caller signature
	 *
	 * @return $this
	 */
	public function queryInfo( array $info ): DeleteQueryBuilder {
		if ( isset( $info['table'] ) ) {
			$this->table( $info['table'] );
		}
		if ( isset( $info['conds'] ) ) {
			$this->where( $info['conds'] );
		}
		if ( isset( $info['caller'] ) ) {
			$this->caller( $info['caller'] );
		}
		return $this;
	}

	/**
	 * Manually set the table name to be passed to IDatabase::delete()
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @return $this
	 */
	public function table( string $table ): DeleteQueryBuilder {
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
	public function deleteFrom( string $table ): DeleteQueryBuilder {
		return $this->table( $table );
	}

	/**
	 * Set table for the query. Alias for table().
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @return $this
	 */
	public function delete( string $table ): DeleteQueryBuilder {
		return $this->table( $table );
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
	public function where( $conds ): DeleteQueryBuilder {
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
	public function andWhere( $conds ): DeleteQueryBuilder {
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
	public function conds( $conds ): DeleteQueryBuilder {
		return $this->where( $conds );
	}

	/**
	 * Set the method name to be included in an SQL comment.
	 *
	 * @param string $fname
	 * @param-taint $fname exec_sql
	 * @return $this
	 */
	public function caller( string $fname ): DeleteQueryBuilder {
		$this->caller = $fname;
		return $this;
	}

	/**
	 * Run the constructed DELETE query.
	 */
	public function execute(): void {
		if ( !$this->conds ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects at least one condition to be set' );
		}
		if ( $this->table === '' ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects table not to be empty' );
		}
		$this->db->delete( $this->table, $this->conds, $this->caller );
	}

	/**
	 * Get an associative array describing the query in terms of its raw parameters to
	 * IDatabase::delete(). This can be used to interface with legacy code.
	 *
	 * @return array The query info array, with keys:
	 *   - table: The table name
	 *   - conds: The conditions
	 *   - caller: The caller signature
	 */
	public function getQueryInfo(): array {
		$info = [
			'table' => $this->table,
			'conds' => $this->conds,
		];
		if ( $this->caller !== __CLASS__ ) {
			$info['caller'] = $this->caller;
		}
		return $info;
	}
}
