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

	protected function updateSkippedMessage() {
		return 'actors already migrated.';
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
		$max = $dbw->selectField( 'user', 'MAX(user_id)', false, __METHOD__ );
		$count = 0;
		while ( $end < $max ) {
			$start = $end + 1;
			$end = min( $start + $this->mBatchSize, $max );
			$this->output( "... $start - $end\n" );
			$res = $dbw->insertSelect(
				'actor',
				'user',
				[ 'actor_user' => 'user_id', 'actor_name' => 'user_name' ],
				[ "user_id >= $start", "user_id <= $end" ],
				__METHOD__,
				[ 'IGNORE' ]
			);
			$count += $dbw->affectedRows();
		}
		$this->output( "Completed actor creation, added $count new actor(s)\n" );

		$errors = 0;
		$errors += $this->migrateToTemp(
			'revision', 'rev_id', 'rev_user', 'rev_user_text', 'revactor_rev', 'revactor_actor'
		);
		$errors += $this->migrate( 'archive', 'ar_id', 'ar_user', 'ar_user_text', 'ar_actor' );
		$errors += $this->migrate( 'ipblocks', 'ipb_id', 'ipb_by', 'ipb_by_text', 'ipb_by_actor' );
		$errors += $this->migrateToTemp(
			'image', 'img_name', 'img_user', 'img_user_text', 'imgactor_name', 'imgactor_actor'
		);
		$errors += $this->migrate(
			'oldimage', [ 'oi_name', 'oi_timestamp' ], 'oi_user', 'oi_user_text', 'oi_actor'
		);
		$errors += $this->migrate( 'filearchive', 'fa_id', 'fa_user', 'fa_user_text', 'fa_actor' );
		$errors += $this->migrate( 'recentchanges', 'rc_id', 'rc_user', 'rc_user_text', 'rc_actor' );
		$errors += $this->migrate( 'logging', 'log_id', 'log_user', 'log_user_text', 'log_actor' );
		return $errors === 0;
	}

	/**
	 * Calculate a "next" condition and a "prompt" string
	 * @param IDatabase $dbw
	 * @param string[] $primaryKey Primary key of the table.
	 * @param object $row Database row
	 * @return array [ string $next, string $prompt ]
	 */
	private function makePrompt( $dbw, $primaryKey, $row ) {
		$next = '';
		$prompt = [];
		for ( $i = count( $primaryKey ) - 1; $i >= 0; $i-- ) {
			$field = $primaryKey[$i];
			$prompt[] = $field . '=' . $row->$field;
			$value = $dbw->addQuotes( $row->$field );
			if ( $next === '' ) {
				$next = "$field > $value";
			} else {
				$next = "$field > $value OR $field = $value AND ($next)";
			}
		}
		$prompt = join( ' ', array_reverse( $prompt ) );
		return [ $next, $prompt ];
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
		$primaryKey = (array)$primaryKey;
		$pkFilter = array_flip( $primaryKey );
		$this->output(
			"Beginning migration of $table.$userField and $table.$nameField to $table.$actorField\n"
		);

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
			$needActors = [];
			$rows = iterator_to_array( $res );
			foreach ( $rows as $row ) {
				if ( $row->actor_id === null ) {
					$needActors[$row->$nameField] = 0;
				}
			}
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
					$row->actor_id = $needActors[$row->$nameField];
				}
			}

			// Update the existing rows
			foreach ( $rows as $row ) {
				if ( !$row->actor_id ) {
					list( , $prompt ) = $this->makePrompt( $dbw, $primaryKey, $row );
					$this->error(
						"Could not make actor for row with $prompt "
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

			list( $next, $prompt ) = $this->makePrompt( $dbw, $primaryKey, $row );
			$this->output( "... $prompt\n" );
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
	 * @param string $userField User ID field name
	 * @param string $nameField User name field name
	 * @param string $newPrimaryKey Primary key of the new table.
	 * @param string $actorField Actor field name
	 */
	protected function migrateToTemp(
		$table, $primaryKey, $userField, $nameField, $newPrimaryKey, $actorField
	) {
		$newTable = $table . '_actor_temp';
		$this->output(
			"Beginning migration of $table.$userField and $table.$nameField to $newTable.$actorField\n"
		);

		$dbw = $this->getDB( DB_MASTER );
		$next = [];
		$countUpdated = 0;
		$countActors = 0;
		$countErrors = 0;
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				[ $table, $newTable, 'actor' ],
				[ $primaryKey, $userField, $nameField, 'actor_id' ],
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
			$needActors = [];
			$rows = iterator_to_array( $res );
			foreach ( $rows as $row ) {
				if ( $row->actor_id === null ) {
					$needActors[$row->$nameField] = 0;
				}
			}
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

			// Update rows
			$inserts = [];
			$updates = [];
			foreach ( $rows as $row ) {
				if ( !$row->actor_id ) {
					list( , $prompt ) = $this->makePrompt( $dbw, [ $primaryKey ], $row );
					$this->error(
						"Could not make actor for row with $prompt "
						. "$userField={$row->$userField} $nameField={$row->$nameField}\n"
					);
					$countErrors++;
					continue;
				}
				$inserts[] = [
					$newPrimaryKey => $row->$primaryKey,
					$actorField => $row->actor_id,
				];
				$updates[] = $row->$primaryKey;
			}
			$this->beginTransaction( $dbw, __METHOD__ );
			$dbw->insert( $newTable, $inserts, __METHOD__ );
			$dbw->update( $table, [ $nameField => '' ], [ $primaryKey => $updates ], __METHOD__ );
			$countUpdated += $dbw->affectedRows();
			$this->commitTransaction( $dbw, __METHOD__ );

			// Calculate the "next" condition
			list( $n, $prompt ) = $this->makePrompt( $dbw, [ $primaryKey ], $row );
			$next = [ $n ];
			$this->output( "... $prompt\n" );
		}

		$this->output(
			"Completed migration, updated $countUpdated row(s) with $countActors new actor(s), "
			. "$countErrors error(s)\n"
		);
	}
}

$maintClass = "MigrateActors";
require_once RUN_MAINTENANCE_IF_MAIN;
