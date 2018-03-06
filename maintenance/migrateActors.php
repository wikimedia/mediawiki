<?php
/**
 * Migrate actors from pre-1.31 columns to the 'actor' table
 *
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
 * @ingroup Maintenance
 */

use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that migrates actors from pre-1.31 columns to the
 * 'actor' table
 *
 * @ingroup Maintenance
 */
class MigrateActors extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrates actors from pre-1.31 columns to the \'actor\' table' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		global $wgActorTableSchemaMigrationStage;

		if ( $wgActorTableSchemaMigrationStage < MIGRATION_WRITE_NEW ) {
			$this->output(
				"...cannot update while \$wgActorTableSchemaMigrationStage < MIGRATION_WRITE_NEW\n"
			);
			return false;
		}

		$this->output( "Creating actor entries for all registered users\n" );
		$end = 0;
		$dbw = $this->getDB( DB_MASTER );
		$max = $dbw->selectField( 'user', 'MAX(user_id)', '', __METHOD__ );
		$count = 0;
		while ( $end < $max ) {
			$start = $end + 1;
			$end = min( $start + $this->mBatchSize, $max );
			$this->output( "... $start - $end\n" );
			$dbw->insertSelect(
				'actor',
				'user',
				[ 'actor_user' => 'user_id', 'actor_name' => 'user_name' ],
				[ "user_id >= $start", "user_id <= $end" ],
				__METHOD__,
				[ 'IGNORE' ],
				[ 'ORDER BY' => [ 'user_id' ] ]
			);
			$count += $dbw->affectedRows();
			wfWaitForSlaves();
		}
		$this->output( "Completed actor creation, added $count new actor(s)\n" );

		$errors = 0;
		$errors += $this->migrateToTemp(
			'revision', 'rev_id', [ 'revactor_timestamp' => 'rev_timestamp', 'revactor_page' => 'rev_page' ],
			'rev_user', 'rev_user_text', 'revactor_rev', 'revactor_actor'
		);
		$errors += $this->migrate( 'archive', 'ar_id', 'ar_user', 'ar_user_text', 'ar_actor' );
		$errors += $this->migrate( 'ipblocks', 'ipb_id', 'ipb_by', 'ipb_by_text', 'ipb_by_actor' );
		$errors += $this->migrate( 'image', 'img_name', 'img_user', 'img_user_text', 'img_actor' );
		$errors += $this->migrate(
			'oldimage', [ 'oi_name', 'oi_timestamp' ], 'oi_user', 'oi_user_text', 'oi_actor'
		);
		$errors += $this->migrate( 'filearchive', 'fa_id', 'fa_user', 'fa_user_text', 'fa_actor' );
		$errors += $this->migrate( 'recentchanges', 'rc_id', 'rc_user', 'rc_user_text', 'rc_actor' );
		$errors += $this->migrate( 'logging', 'log_id', 'log_user', 'log_user_text', 'log_actor' );

		$errors += $this->migrateLogSearch();

		return $errors === 0;
	}

	/**
	 * Calculate a "next" condition and a display string
	 * @param IDatabase $dbw
	 * @param string[] $primaryKey Primary key of the table.
	 * @param object $row Database row
	 * @return array [ string $next, string $display ]
	 */
	private function makeNextCond( $dbw, $primaryKey, $row ) {
		$next = '';
		$display = [];
		for ( $i = count( $primaryKey ) - 1; $i >= 0; $i-- ) {
			$field = $primaryKey[$i];
			$display[] = $field . '=' . $row->$field;
			$value = $dbw->addQuotes( $row->$field );
			if ( $next === '' ) {
				$next = "$field > $value";
			} else {
				$next = "$field > $value OR $field = $value AND ($next)";
			}
		}
		$display = implode( ' ', array_reverse( $display ) );
		return [ $next, $display ];
	}

	/**
	 * Add actors for anons in a set of rows
	 * @param IDatabase $dbw
	 * @param string $nameField
	 * @param object[] &$rows
	 * @param array &$complainedAboutUsers
	 * @param int &$countErrors
	 * @return int Count of actors inserted
	 */
	private function addActorsForRows(
		IDatabase $dbw, $nameField, array &$rows, array &$complainedAboutUsers, &$countErrors
	) {
		$needActors = [];
		$countActors = 0;

		$keep = [];
		foreach ( $rows as $index => $row ) {
			$keep[$index] = true;
			if ( $row->actor_id === null ) {
				// All registered users should have an actor_id already. So
				// if we have a usable name here, it means they didn't run
				// maintenance/cleanupUsersWithNoId.php
				$name = $row->$nameField;
				if ( User::isUsableName( $name ) ) {
					if ( !isset( $complainedAboutUsers[$name] ) ) {
						$complainedAboutUsers[$name] = true;
						$this->error(
							"User name \"$name\" is usable, cannot create an anonymous actor for it."
							. " Run maintenance/cleanupUsersWithNoId.php to fix this situation.\n"
						);
					}
					unset( $keep[$index] );
					$countErrors++;
				} else {
					$needActors[$name] = 0;
				}
			}
		}
		$rows = array_intersect_key( $rows, $keep );

		if ( $needActors ) {
			$dbw->insert(
				'actor',
				array_map( function ( $v ) {
					return [
						'actor_name' => $v,
					];
				}, array_keys( $needActors ) ),
				__METHOD__
			);
			$countActors += $dbw->affectedRows();

			$res = $dbw->select(
				'actor',
				[ 'actor_id', 'actor_name' ],
				[ 'actor_name' => array_keys( $needActors ) ],
				__METHOD__
			);
			foreach ( $res as $row ) {
				$needActors[$row->actor_name] = $row->actor_id;
			}
			foreach ( $rows as $row ) {
				if ( $row->actor_id === null ) {
					$row->actor_id = $needActors[$row->$nameField];
				}
			}
		}

		return $countActors;
	}

	/**
	 * Migrate actors in a table.
	 *
	 * Assumes any row with the actor field non-zero have already been migrated.
	 * Blanks the name field when migrating.
	 *
	 * @param string $table Table to migrate
	 * @param string|string[] $primaryKey Primary key of the table.
	 * @param string $userField User ID field name
	 * @param string $nameField User name field name
	 * @param string $actorField Actor field name
	 * @return int Number of errors
	 */
	protected function migrate( $table, $primaryKey, $userField, $nameField, $actorField ) {
		$complainedAboutUsers = [];

		$primaryKey = (array)$primaryKey;
		$pkFilter = array_flip( $primaryKey );
		$this->output(
			"Beginning migration of $table.$userField and $table.$nameField to $table.$actorField\n"
		);
		wfWaitForSlaves();

		$dbw = $this->getDB( DB_MASTER );
		$next = '1=1';
		$countUpdated = 0;
		$countActors = 0;
		$countErrors = 0;
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				[ $table, 'actor' ],
				array_merge( $primaryKey, [ $userField, $nameField, 'actor_id' ] ),
				[
					$actorField => 0,
					$next,
				],
				__METHOD__,
				[
					'ORDER BY' => $primaryKey,
					'LIMIT' => $this->mBatchSize,
				],
				[
					'actor' => [
						'LEFT JOIN',
						"$userField != 0 AND actor_user = $userField OR "
						. "($userField = 0 OR $userField IS NULL) AND actor_name = $nameField"
					]
				]
			);
			if ( !$res->numRows() ) {
				break;
			}

			// Insert new actors for rows that need one
			$rows = iterator_to_array( $res );
			$lastRow = end( $rows );
			$countActors += $this->addActorsForRows(
				$dbw, $nameField, $rows, $complainedAboutUsers, $countErrors
			);

			// Update the existing rows
			foreach ( $rows as $row ) {
				if ( !$row->actor_id ) {
					list( , $display ) = $this->makeNextCond( $dbw, $primaryKey, $row );
					$this->error(
						"Could not make actor for row with $display "
						. "$userField={$row->$userField} $nameField={$row->$nameField}\n"
					);
					$countErrors++;
					continue;
				}
				$dbw->update(
					$table,
					[
						$actorField => $row->actor_id,
						$nameField => '',
					],
					array_intersect_key( (array)$row, $pkFilter ) + [
						$actorField => 0
					],
					__METHOD__
				);
				$countUpdated += $dbw->affectedRows();
			}

			list( $next, $display ) = $this->makeNextCond( $dbw, $primaryKey, $lastRow );
			$this->output( "... $display\n" );
			wfWaitForSlaves();
		}

		$this->output(
			"Completed migration, updated $countUpdated row(s) with $countActors new actor(s), "
			. "$countErrors error(s)\n"
		);
		return $countErrors;
	}

	/**
	 * Migrate actors in a table to a temporary table.
	 *
	 * Assumes the new table is named "{$table}_actor_temp", and it has two
	 * columns, in order, being the primary key of the original table and the
	 * actor ID field.
	 * Blanks the name field when migrating.
	 *
	 * @param string $table Table to migrate
	 * @param string $primaryKey Primary key of the table.
	 * @param array $extra Extra fields to copy
	 * @param string $userField User ID field name
	 * @param string $nameField User name field name
	 * @param string $newPrimaryKey Primary key of the new table.
	 * @param string $actorField Actor field name
	 */
	protected function migrateToTemp(
		$table, $primaryKey, $extra, $userField, $nameField, $newPrimaryKey, $actorField
	) {
		$complainedAboutUsers = [];

		$newTable = $table . '_actor_temp';
		$this->output(
			"Beginning migration of $table.$userField and $table.$nameField to $newTable.$actorField\n"
		);
		wfWaitForSlaves();

		$dbw = $this->getDB( DB_MASTER );
		$next = [];
		$countUpdated = 0;
		$countActors = 0;
		$countErrors = 0;
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				[ $table, $newTable, 'actor' ],
				[ $primaryKey, $userField, $nameField, 'actor_id' ] + $extra,
				[ $newPrimaryKey => null ] + $next,
				__METHOD__,
				[
					'ORDER BY' => $primaryKey,
					'LIMIT' => $this->mBatchSize,
				],
				[
					$newTable => [ 'LEFT JOIN', "{$primaryKey}={$newPrimaryKey}" ],
					'actor' => [
						'LEFT JOIN',
						"$userField != 0 AND actor_user = $userField OR "
						. "($userField = 0 OR $userField IS NULL) AND actor_name = $nameField"
					]
				]
			);
			if ( !$res->numRows() ) {
				break;
			}

			// Insert new actors for rows that need one
			$rows = iterator_to_array( $res );
			$lastRow = end( $rows );
			$countActors += $this->addActorsForRows(
				$dbw, $nameField, $rows, $complainedAboutUsers, $countErrors
			);

			// Update rows
			if ( $rows ) {
				$inserts = [];
				$updates = [];
				foreach ( $rows as $row ) {
					if ( !$row->actor_id ) {
						list( , $display ) = $this->makeNextCond( $dbw, [ $primaryKey ], $row );
						$this->error(
							"Could not make actor for row with $display "
							. "$userField={$row->$userField} $nameField={$row->$nameField}\n"
						);
						$countErrors++;
						continue;
					}
					$ins = [
						$newPrimaryKey => $row->$primaryKey,
						$actorField => $row->actor_id,
					];
					foreach ( $extra as $to => $from ) {
						$ins[$to] = $row->$to; // It's aliased
					}
					$inserts[] = $ins;
					$updates[] = $row->$primaryKey;
				}
				$this->beginTransaction( $dbw, __METHOD__ );
				$dbw->insert( $newTable, $inserts, __METHOD__ );
				$dbw->update( $table, [ $nameField => '' ], [ $primaryKey => $updates ], __METHOD__ );
				$countUpdated += $dbw->affectedRows();
				$this->commitTransaction( $dbw, __METHOD__ );
			}

			// Calculate the "next" condition
			list( $n, $display ) = $this->makeNextCond( $dbw, [ $primaryKey ], $lastRow );
			$next = [ $n ];
			$this->output( "... $display\n" );
		}

		$this->output(
			"Completed migration, updated $countUpdated row(s) with $countActors new actor(s), "
			. "$countErrors error(s)\n"
		);
		return $countErrors;
	}

	/**
	 * Migrate actors in the log_search table.
	 * @return int Number of errors
	 */
	protected function migrateLogSearch() {
		$complainedAboutUsers = [];

		$primaryKey = [ 'ls_field', 'ls_value' ];
		$pkFilter = array_flip( $primaryKey );
		$this->output( "Beginning migration of log_search\n" );
		wfWaitForSlaves();

		$dbw = $this->getDB( DB_MASTER );
		$countUpdated = 0;
		$countActors = 0;
		$countErrors = 0;

		$next = '1=1';
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				[ 'log_search', 'actor' ],
				[ 'ls_field', 'ls_value', 'actor_id' ],
				[
					'ls_field' => 'target_author_id',
					$next,
				],
				__METHOD__,
				[
					'DISTINCT',
					'ORDER BY' => [ 'ls_value' ],
					'LIMIT' => $this->mBatchSize,
				],
				[ 'actor' => [ 'LEFT JOIN', 'ls_value = ' . $dbw->buildStringCast( 'actor_user' ) ] ]
			);
			if ( !$res->numRows() ) {
				break;
			}

			// Update the rows
			$del = [];
			foreach ( $res as $row ) {
				$lastRow = $row;
				if ( !$row->actor_id ) {
					list( , $display ) = $this->makeNextCond( $dbw, $primaryKey, $row );
					$this->error( "No actor for row with $display\n" );
					$countErrors++;
					continue;
				}
				$dbw->update(
					'log_search',
					[
						'ls_field' => 'target_author_actor',
						'ls_value' => $row->actor_id,
					],
					[
						'ls_field' => $row->ls_field,
						'ls_value' => $row->ls_value,
					],
					__METHOD__,
					[ 'IGNORE' ]
				);
				$countUpdated += $dbw->affectedRows();
				$del[] = $row->ls_value;
			}
			if ( $del ) {
				$dbw->delete(
					'log_search', [ 'ls_field' => 'target_author_id', 'ls_value' => $del ], __METHOD__
				);
				$countUpdated += $dbw->affectedRows();
			}

			list( $next, $display ) = $this->makeNextCond( $dbw, $primaryKey, $lastRow );
			$this->output( "... $display\n" );
			wfWaitForSlaves();
		}

		$next = '1=1';
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				[ 'log_search', 'actor' ],
				[ 'ls_field', 'ls_value', 'actor_id' ],
				[
					'ls_field' => 'target_author_ip',
					$next,
				],
				__METHOD__,
				[
					'DISTINCT',
					'ORDER BY' => [ 'ls_value' ],
					'LIMIT' => $this->mBatchSize,
				],
				[ 'actor' => [ 'LEFT JOIN', 'ls_value = actor_name' ] ]
			);
			if ( !$res->numRows() ) {
				break;
			}

			// Insert new actors for rows that need one
			$rows = iterator_to_array( $res );
			$lastRow = end( $rows );
			$countActors += $this->addActorsForRows(
				$dbw, 'ls_value', $rows, $complainedAboutUsers, $countErrors
			);

			// Update the rows
			$del = [];
			foreach ( $rows as $row ) {
				if ( !$row->actor_id ) {
					list( , $display ) = $this->makeNextCond( $dbw, $primaryKey, $row );
					$this->error( "Could not make actor for row with $display\n" );
					$countErrors++;
					continue;
				}
				$dbw->update(
					'log_search',
					[
						'ls_field' => 'target_author_actor',
						'ls_value' => $row->actor_id,
					],
					[
						'ls_field' => $row->ls_field,
						'ls_value' => $row->ls_value,
					],
					__METHOD__,
					[ 'IGNORE' ]
				);
				$countUpdated += $dbw->affectedRows();
				$del[] = $row->ls_value;
			}
			if ( $del ) {
				$dbw->delete(
					'log_search', [ 'ls_field' => 'target_author_ip', 'ls_value' => $del ], __METHOD__
				);
				$countUpdated += $dbw->affectedRows();
			}

			list( $next, $display ) = $this->makeNextCond( $dbw, $primaryKey, $lastRow );
			$this->output( "... $display\n" );
			wfWaitForSlaves();
		}

		$this->output(
			"Completed migration, updated $countUpdated row(s) with $countActors new actor(s), "
			. "$countErrors error(s)\n"
		);
		return $countErrors;
	}
}

$maintClass = "MigrateActors";
require_once RUN_MAINTENANCE_IF_MAIN;
