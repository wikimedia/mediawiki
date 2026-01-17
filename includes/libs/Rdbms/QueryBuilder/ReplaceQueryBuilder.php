<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Build REPLACE queries with a fluent interface.
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
class ReplaceQueryBuilder implements IWriteQueryBuilder {
	/**
	 * @var string The table name to be passed to IDatabase::replace()
	 */
	private $table = '';

	/**
	 * @var list<array> The rows to be passed to IDatabase::replace()
	 */
	private $rows = [];

	/**
	 * @var string The caller (function name) to be passed to IDatabase::replace()
	 */
	private $caller = __CLASS__;

	/**
	 * @var string[] The unique keys to be passed to IDatabase::replace()
	 */
	private $uniqueIndexFields = [];

	protected IDatabase $db;

	/**
	 * Only for use in subclasses. To create a ReplaceQueryBuilder instance,
	 * use `$db->newReplaceQueryBuilder()` instead.
	 */
	public function __construct( IDatabase $db ) {
		$this->db = $db;
	}

	/**
	 * @inheritDoc
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
	 * @inheritDoc
	 */
	public function queryInfo( $info ) {
		if ( isset( $info['table'] ) ) {
			$this->table( $info['table'] );
		}
		if ( isset( $info['rows'] ) ) {
			$this->rows( $info['rows'] );
		}
		if ( isset( $info['uniqueIndexFields'] ) ) {
			$this->uniqueIndexFields( (array)$info['uniqueIndexFields'] );
		}
		if ( isset( $info['caller'] ) ) {
			$this->caller( $info['caller'] );
		}
		return $this;
	}

	/**
	 * Manually set the table name to be passed to IDatabase::replace()
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
	public function replaceInto( string $table ) {
		return $this->table( $table );
	}

	/**
	 * Add rows to be inserted.
	 *
	 * @param list<array> $rows
	 *   $rows should be an integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *   The keys in each map must be identical to each other and in the same order.
	 *   The rows must not collide with each other.
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
	 *   $row must be a string-keyed map of (column name => value) defining a new row. Values are
	 *   treated as literals and quoted appropriately; null is interpreted as NULL.
	 *
	 * @return $this
	 */
	public function row( array $row ) {
		$this->rows[] = $row;
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
	 * @inheritDoc
	 */
	public function caller( $fname ) {
		$this->caller = $fname;
		return $this;
	}

	/**
	 * @inheritDoc
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
		if ( !$this->uniqueIndexFields ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' expects unique key to be provided' );
		}
		$this->db->replace( $this->table, [ $this->uniqueIndexFields ], $this->rows, $this->caller );
	}

	/**
	 * @inheritDoc
	 */
	public function getQueryInfo() {
		$info = [
			'table' => $this->table,
			'rows' => $this->rows,
			'uniqueIndexFields' => $this->uniqueIndexFields,
		];
		if ( $this->caller !== __CLASS__ ) {
			$info['caller'] = $this->caller;
		}
		return $info;
	}
}
