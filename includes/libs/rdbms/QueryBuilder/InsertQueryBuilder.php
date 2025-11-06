<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Build INSERT queries with a fluent interface.
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
class InsertQueryBuilder {
	/**
	 * @var string The table name to be passed to IDatabase::insert()
	 */
	private $table = '';

	/**
	 * @var list<array> The rows to be passed to IDatabase::insert()
	 */
	private $rows = [];

	/**
	 * @var string The caller (function name) to be passed to IDatabase::insert()
	 */
	private $caller = __CLASS__;

	/**
	 * @var bool whether this is an upsert or not
	 */
	private $upsert = false;

	/**
	 * @var array The set values to be passed to IDatabase::upsert()
	 */
	private $set = [];

	/**
	 * @var string[] The unique keys to be passed to IDatabase::upsert()
	 */
	private $uniqueIndexFields = [];

	/**
	 * @var array The options to be passed to IDatabase::insert()
	 */
	protected $options = [];

	protected IDatabase $db;

	/**
	 * Only for use in subclasses. To create a InsertQueryBuilder instance,
	 * use `$db->newInsertQueryBuilder()` instead.
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
	 * The parameters must be formatted as required by Database::insert.
	 *
	 * @param array $info Associative array of query info, with keys:
	 *   - table: The table name to be passed to Database::insert()
	 *   - rows: The rows to be inserted
	 *   - options: The query options
	 *   - upsert: Whether it's insert or upsert
	 *   - uniqueIndexFields: Fields of the unique index
	 *   - set: The set array
	 *   - caller: The caller signature
	 *
	 * @return $this
	 */
	public function queryInfo( $info ) {
		if ( isset( $info['table'] ) ) {
			$this->table( $info['table'] );
		}
		if ( isset( $info['rows'] ) ) {
			$this->rows( $info['rows'] );
		}
		if ( isset( $info['options'] ) ) {
			$this->options( (array)$info['options'] );
		}
		if ( isset( $info['upsert'] ) ) {
			$this->onDuplicateKeyUpdate();
		}
		if ( isset( $info['uniqueIndexFields'] ) ) {
			$this->uniqueIndexFields( (array)$info['uniqueIndexFields'] );
		}
		if ( isset( $info['set'] ) ) {
			$this->set( (array)$info['set'] );
		}
		if ( isset( $info['caller'] ) ) {
			$this->caller( $info['caller'] );
		}
		return $this;
	}

	/**
	 * Manually set the table name to be passed to IDatabase::insert()
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
	public function insertInto( string $table ) {
		return $this->table( $table );
	}

	/**
	 * Set table for the query. Alias for table().
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @return $this
	 */
	public function insert( string $table ) {
		return $this->table( $table );
	}

	/**
	 * Manually set an option in the $options array to be passed to
	 * IDatabase::insert()
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
	 * IDatabase::insert().
	 *
	 * @param array $options
	 * @return $this
	 */
	public function options( array $options ) {
		$this->options = array_merge( $this->options, $options );
		return $this;
	}

	/**
	 * Add rows to be inserted.
	 *
	 * @param list<array> $rows
	 * $rows should be an integer-keyed list of such string-keyed maps, defining a list of new rows.
	 * The keys in each map must be identical to each other and in the same order.
	 * The rows must not collide with each other.
	 *
	 * @return $this
	 */
	public function rows( array $rows ) {
		$this->rows = array_merge( $this->rows, $rows );
		return $this;
	}

	/**
	 * Add one row to be inserted.
	 *
	 * @param array $row
	 * $row must be a string-keyed map of (column name => value) defining a new row. Values are
	 * treated as literals and quoted appropriately; null is interpreted as NULL.
	 *
	 * @return $this
	 */
	public function row( array $row ) {
		$this->rows[] = $row;
		return $this;
	}

	/**
	 * Enable the IGNORE option.
	 *
	 * Skip insertion of rows that would cause unique key conflicts.
	 * IDatabase::affectedRows() can be used to determine how many rows were inserted.
	 *
	 * @return $this
	 */
	public function ignore() {
		$this->options[] = 'IGNORE';
		return $this;
	}

	/**
	 * Do an update instead of insert
	 *
	 * @return $this
	 */
	public function onDuplicateKeyUpdate() {
		$this->upsert = true;
		return $this;
	}

	/**
	 * Set the unique index fields
	 *
	 * @param string|string[] $uniqueIndexFields
	 * @return $this
	 */
	public function uniqueIndexFields( $uniqueIndexFields ) {
		if ( is_string( $uniqueIndexFields ) ) {
			$uniqueIndexFields = [ $uniqueIndexFields ];
		}
		$this->uniqueIndexFields = $uniqueIndexFields;
		return $this;
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
	 * Run the constructed INSERT query.
	 */
	public function execute(): void {
		if ( !$this->rows ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' can\'t have empty $rows value' );
		}
		if ( $this->table === '' ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects table not to be empty' );
		}
		if ( $this->upsert && ( !$this->set || !$this->uniqueIndexFields ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' called with upsert but no set value or unique key has been provided' );
		}
		if ( !$this->upsert && ( $this->set || $this->uniqueIndexFields ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' is not called with upsert but set value or unique key has been provided' );
		}
		if ( $this->upsert ) {
			$this->db->upsert( $this->table, $this->rows, [ $this->uniqueIndexFields ], $this->set, $this->caller );
			return;
		}
		$this->db->insert( $this->table, $this->rows, $this->caller, $this->options );
	}

	/**
	 * Get an associative array describing the query in terms of its raw parameters to
	 * Database::insert(). This can be used to interface with legacy code.
	 *
	 * @return array The query info array, with keys:
	 *   - table: The table name
	 *   - rows: The rows array
	 *   - options: The query options
	 *   - upsert: Whether it's insert or upsert
	 *   - uniqueIndexFields: Fields of the unique index
	 *   - set: The set array
	 *   - caller: The caller signature
	 */
	public function getQueryInfo() {
		$info = [
			'table' => $this->table,
			'rows' => $this->rows,
			'upsert' => $this->upsert,
			'set' => $this->set,
			'uniqueIndexFields' => $this->uniqueIndexFields,
			'options' => $this->options,
		];
		if ( $this->caller !== __CLASS__ ) {
			$info['caller'] = $this->caller;
		}
		return $info;
	}
}
