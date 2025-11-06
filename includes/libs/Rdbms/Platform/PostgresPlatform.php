<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms\Platform;

use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\Query;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @since 1.39
 * @see ISQLPlatform
 */
class PostgresPlatform extends SQLPlatform {
	/** @var string */
	private $coreSchema;

	/** @inheritDoc */
	public function limitResult( $sql, $limit, $offset = false ) {
		return "$sql LIMIT $limit " . ( is_numeric( $offset ) ? " OFFSET {$offset} " : '' );
	}

	/** @inheritDoc */
	public function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	/** @inheritDoc */
	public function buildGroupConcat( $field, $delim ): string {
		return "array_to_string(array_agg($field)," . $this->quoter->addQuotes( $delim ) . ')';
	}

	/** @inheritDoc */
	public function timestamp( $ts = 0 ) {
		$ct = new ConvertibleTimestamp( $ts );

		return $ct->getTimestamp( TS_POSTGRES );
	}

	/** @inheritDoc */
	public function buildStringCast( $field ) {
		return $field . '::text';
	}

	/** @inheritDoc */
	public function implicitOrderby() {
		return false;
	}

	public function getCoreSchema(): string {
		return $this->coreSchema;
	}

	public function setCoreSchema( string $coreSchema ): void {
		$this->coreSchema = $coreSchema;
	}

	/** @inheritDoc */
	public function selectSQLText(
		$tables, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( is_string( $options ) ) {
			$options = [ $options ];
		}

		// Change the FOR UPDATE option as necessary based on the join conditions. Then pass
		// to the parent function to get the actual SQL text.
		// In Postgres when using FOR UPDATE, only the main table and tables that are inner joined
		// can be locked. That means tables in an outer join cannot be FOR UPDATE locked. Trying to
		// do so causes a DB error. This wrapper checks which tables can be locked and adjusts it
		// accordingly.
		// MySQL uses "ORDER BY NULL" as an optimization hint, but that is illegal in PostgreSQL.
		if ( is_array( $options ) ) {
			$forUpdateKey = array_search( 'FOR UPDATE', $options, true );
			if ( $forUpdateKey !== false && $join_conds ) {
				unset( $options[$forUpdateKey] );
				$options['FOR UPDATE'] = [];

				$toCheck = $tables;
				reset( $toCheck );
				while ( $toCheck ) {
					$alias = key( $toCheck );
					$table = $toCheck[$alias];
					unset( $toCheck[$alias] );

					if ( !is_string( $alias ) ) {
						// No alias? Set it equal to the table name
						$alias = $table;
					}

					if ( !isset( $join_conds[$alias] ) ||
						!preg_match( '/^(?:LEFT|RIGHT|FULL)(?: OUTER)? JOIN$/i', $join_conds[$alias][0] )
					) {
						if ( is_array( $table ) ) {
							// It's a parenthesized group, process all the tables inside the group.
							$toCheck = array_merge( $toCheck, $table );
						} else {
							// If an alias is declared, then any FOR UPDATE FOR must use it
							$options['FOR UPDATE'][] = $alias;
						}
					}
				}
			}

			if (
				isset( $options['ORDER BY'] ) &&
				( $options['ORDER BY'] == 'NULL' || $options['ORDER BY'] == [ 'NULL' ] )
			) {
				unset( $options['ORDER BY'] );
			}
		}

		return parent::selectSQLText( $tables, $vars, $conds, $fname, $options, $join_conds );
	}

	/** @inheritDoc */
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

		if ( isset( $options['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE OF ' . implode(
				', ',
				array_map( $this->addIdentifierQuotes( ... ), $options['FOR UPDATE'] )
			);
		} elseif ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		return [ $startOpts, $preLimitTail, $postLimitTail ];
	}

	/** @inheritDoc */
	public function getDatabaseAndTableIdentifier( string $table ) {
		$components = $this->qualifiedTableComponents( $table );
		switch ( count( $components ) ) {
			case 1:
				return [ $this->currentDomain->getDatabase(), $components[0] ];
			case 2:
				return [ $this->currentDomain->getDatabase(), $components[1] ];
			case 3:
				return [ $components[0], $components[2] ];
			default:
				throw new DBLanguageError( 'Too many table components' );
		}
	}

	/** @inheritDoc */
	protected function relationSchemaQualifier() {
		if ( $this->coreSchema === $this->currentDomain->getSchema() ) {
			// The schema to be used is now in the search path; no need for explicit qualification
			return '';
		}

		return parent::relationSchemaQualifier();
	}

	/** @inheritDoc */
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
			$typedRowValues = [];
			foreach ( $row as $column => $value ) {
				$type = $typeByColumn[$column] ?? null;
				if ( $value === null ) {
					$typedRowValues[] = 'NULL';
				} elseif ( $type !== null ) {
					$typedRowValues[] = $this->quoter->addQuotes( $value ) . '::' . $type;
				} else {
					$typedRowValues[] = $this->quoter->addQuotes( $value );
				}
			}
			$valueTuples[] = '(' . implode( ',', $typedRowValues ) . ')';
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

	/** @inheritDoc */
	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT INTO', 'ON CONFLICT DO NOTHING' ];
	}

	/** @inheritDoc */
	protected function makeUpdateOptionsArray( $options ) {
		$options = $this->normalizeOptions( $options );
		// PostgreSQL doesn't support anything like "ignore" for UPDATE.
		$options = array_diff( $options, [ 'IGNORE' ] );

		return parent::makeUpdateOptionsArray( $options );
	}

	/** @inheritDoc */
	public function isTransactableQuery( Query $sql ) {
		return parent::isTransactableQuery( $sql ) &&
			!preg_match( '/^SELECT\s+pg_(try_|)advisory_\w+\(/', $sql->getSQL() );
	}

	/** @inheritDoc */
	public function lockSQLText( $lockName, $timeout ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->quoter->addQuotes( $this->bigintFromLockName( $lockName ) );
		return "SELECT (CASE WHEN pg_try_advisory_lock($key) " .
			"THEN EXTRACT(epoch from clock_timestamp()) " .
			"ELSE NULL " .
			"END) AS acquired";
	}

	/** @inheritDoc */
	public function lockIsFreeSQLText( $lockName ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->quoter->addQuotes( $this->bigintFromLockName( $lockName ) );
		return "SELECT (CASE(pg_try_advisory_lock($key))
			WHEN FALSE THEN FALSE ELSE pg_advisory_unlock($key) END) AS unlocked";
	}

	/** @inheritDoc */
	public function unlockSQLText( $lockName ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->quoter->addQuotes( $this->bigintFromLockName( $lockName ) );
		return "SELECT pg_advisory_unlock($key) AS released";
	}

	/**
	 * @param string $lockName
	 * @return string Integer
	 */
	private function bigintFromLockName( $lockName ) {
		return \Wikimedia\base_convert( substr( sha1( $lockName ), 0, 15 ), 16, 10 );
	}
}
