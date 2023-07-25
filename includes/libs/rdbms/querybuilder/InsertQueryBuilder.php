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
	 * @var array The rows to be passed to IDatabase::insert()
	 */
	private $rows = [];

	/**
	 * @var string The caller (function name) to be passed to IDatabase::insert()
	 */
	private $caller = __CLASS__;

	/**
	 * @var array The options to be passed to IDatabase::insert()
	 */
	protected $options = [];

	/** @var IDatabase */
	protected $db;

	/**
	 * Only for use in subclasses. To create a InsertQueryBuilder instance,
	 * use `$db->newInsertQueryBuilder()` instead.
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
		return $this;
	}

	/**
	 * Manually set the table name to be passed to IDatabase::insert()
	 *
	 * @param string $table The table name
	 * @return $this
	 */
	public function table( $table ) {
		$this->table = $table;
		return $this;
	}

	/**
	 * Set table for the query. Alias for table().
	 *
	 * @param string $table The table name
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
	 * @param array $rows
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
		return $this->rows( [ $row ] );
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
	 * Run the constructed INSERT query and return the result.
	 */
	public function execute() {
		if ( !$this->rows ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' can\'t have empty $rows value' );
		}
		if ( $this->table === '' ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects table not to be empty' );
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
	 */
	public function getQueryInfo() {
		return [
			'table' => $this->table,
			'rows' => $this->rows,
			'options' => $this->options,
		];
	}
}
