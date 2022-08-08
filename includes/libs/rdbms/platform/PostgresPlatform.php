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

use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @since 1.39
 * @see ISQLPlatform
 */
class PostgresPlatform extends SQLPlatform {
	/** @var string */
	private $coreSchema;

	public function limitResult( $sql, $limit, $offset = false ) {
		return "$sql LIMIT $limit " . ( is_numeric( $offset ) ? " OFFSET {$offset} " : '' );
	}

	public function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	public function timestamp( $ts = 0 ) {
		$ct = new ConvertibleTimestamp( $ts );

		return $ct->getTimestamp( TS_POSTGRES );
	}

	public function buildStringCast( $field ) {
		return $field . '::text';
	}

	public function implicitOrderby() {
		return false;
	}

	public function getCoreSchema(): string {
		return $this->coreSchema;
	}

	public function setCoreSchema( string $coreSchema ): void {
		$this->coreSchema = $coreSchema;
	}

	public function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
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

				$toCheck = $table;
				reset( $toCheck );
				while ( $toCheck ) {
					$alias = key( $toCheck );
					$name = $toCheck[$alias];
					unset( $toCheck[$alias] );

					$hasAlias = !is_numeric( $alias );
					if ( !$hasAlias && is_string( $name ) ) {
						$alias = $name;
					}

					if ( !isset( $join_conds[$alias] ) ||
						!preg_match( '/^(?:LEFT|RIGHT|FULL)(?: OUTER)? JOIN$/i', $join_conds[$alias][0] )
					) {
						if ( is_array( $name ) ) {
							// It's a parenthesized group, process all the tables inside the group.
							$toCheck = array_merge( $toCheck, $name );
						} else {
							// Quote alias names so $this->tableName() won't mangle them
							$options['FOR UPDATE'][] = $hasAlias ?
								$this->addIdentifierQuotes( $alias ) : $alias;
						}
					}
				}
			}

			if ( isset( $options['ORDER BY'] ) && $options['ORDER BY'] == 'NULL' ) {
				unset( $options['ORDER BY'] );
			}
		}

		return parent::selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
	}

	protected function makeSelectOptions( array $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = $useIndex = $ignoreIndex = '';

		$noKeyOptions = [];
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		if ( isset( $options['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE OF ' .
				implode( ', ', array_map( [ $this, 'tableName' ], $options['FOR UPDATE'] ) );
		} elseif ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ];
	}

	protected function relationSchemaQualifier() {
		if ( $this->coreSchema === $this->currentDomain->getSchema() ) {
			// The schema to be used is now in the search path; no need for explicit qualification
			return '';
		}

		return parent::relationSchemaQualifier();
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "array_to_string(array_agg($field)," . $this->quoter->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT INTO', 'ON CONFLICT DO NOTHING' ];
	}

	protected function makeUpdateOptionsArray( $options ) {
		$options = $this->normalizeOptions( $options );
		// PostgreSQL doesn't support anything like "ignore" for UPDATE.
		$options = array_diff( $options, [ 'IGNORE' ] );

		return parent::makeUpdateOptionsArray( $options );
	}

	public function isTransactableQuery( $sql ) {
		return parent::isTransactableQuery( $sql ) &&
			!preg_match( '/^SELECT\s+pg_(try_|)advisory_\w+\(/', $sql );
	}

	public function lockSQLText( $lockName, $timeout ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->quoter->addQuotes( $this->bigintFromLockName( $lockName ) );
		return "SELECT (CASE WHEN pg_try_advisory_lock($key) " .
			"THEN EXTRACT(epoch from clock_timestamp()) " .
			"ELSE NULL " .
			"END) AS acquired";
	}

	public function lockIsFreeSQLText( $lockName ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->quoter->addQuotes( $this->bigintFromLockName( $lockName ) );
		return "SELECT (CASE(pg_try_advisory_lock($key))
			WHEN 'f' THEN 'f' ELSE pg_advisory_unlock($key) END) AS unlocked";
	}

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
