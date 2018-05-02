<?php
/**
 * Init the user_editcount database field based on the number of rows in the
 * revision table.
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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

class InitEditCount extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'quick', 'Force the update to be done in a single query' );
		$this->addOption( 'background', 'Force replication-friendly mode; may be inefficient but
		avoids locking tables or lagging replica DBs with large updates;
		calculates counts on a replica DB if possible.

Background mode will be automatically used if multiple servers are listed
in the load balancer, usually indicating a replication environment.' );
		$this->addDescription( 'Batch-recalculate user_editcount fields from the revision table' );
	}

	public function execute() {
		global $wgActorTableSchemaMigrationStage;

		$dbw = $this->getDB( DB_MASTER );

		// Autodetect mode...
		if ( $this->hasOption( 'background' ) ) {
			$backgroundMode = true;
		} elseif ( $this->hasOption( 'quick' ) ) {
			$backgroundMode = false;
		} else {
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$backgroundMode = $lb->getServerCount() > 1;
		}

		$actorQuery = ActorMigration::newMigration()->getJoin( 'rev_user' );

		$needSpecialQuery = ( $wgActorTableSchemaMigrationStage !== MIGRATION_OLD &&
			$wgActorTableSchemaMigrationStage !== MIGRATION_NEW );
		if ( $needSpecialQuery ) {
			foreach ( $actorQuery['joins'] as &$j ) {
				$j[0] = 'JOIN'; // replace LEFT JOIN
			}
			unset( $j );
		}

		if ( $backgroundMode ) {
			$this->output( "Using replication-friendly background mode...\n" );

			$dbr = $this->getDB( DB_REPLICA );
			$chunkSize = 100;
			$lastUser = $dbr->selectField( 'user', 'MAX(user_id)', '', __METHOD__ );

			$start = microtime( true );
			$migrated = 0;
			for ( $min = 0; $min <= $lastUser; $min += $chunkSize ) {
				$max = $min + $chunkSize;

				if ( $needSpecialQuery ) {
					// Use separate subqueries to collect counts with the old
					// and new schemas, to avoid having to do whole-table scans.
					$result = $dbr->select(
						[
							'user',
							'rev1' => '('
								. $dbr->selectSQLText(
									[ 'revision', 'revision_actor_temp' ],
									[ 'rev_user', 'ct' => 'COUNT(*)' ],
									[
										"rev_user > $min AND rev_user <= $max",
										'revactor_rev' => null,
									],
									__METHOD__,
									[ 'GROUP BY' => 'rev_user' ],
									[ 'revision_actor_temp' => [ 'LEFT JOIN', 'revactor_rev = rev_id' ] ]
								) . ')',
							'rev2' => '('
								. $dbr->selectSQLText(
									[ 'revision' ] + $actorQuery['tables'],
									[ 'actor_user', 'ct' => 'COUNT(*)' ],
									"actor_user > $min AND actor_user <= $max",
									__METHOD__,
									[ 'GROUP BY' => 'actor_user' ],
									$actorQuery['joins']
								) . ')',
						],
						[ 'user_id', 'user_editcount' => 'COALESCE(rev1.ct,0) + COALESCE(rev2.ct,0)' ],
						"user_id > $min AND user_id <= $max",
						__METHOD__,
						[],
						[
							'rev1' => [ 'LEFT JOIN', 'user_id = rev_user' ],
							'rev2' => [ 'LEFT JOIN', 'user_id = actor_user' ],
						]
					);
				} else {
					$revUser = $actorQuery['fields']['rev_user'];
					$result = $dbr->select(
						[ 'user', 'rev' => [ 'revision' ] + $actorQuery['tables'] ],
						[ 'user_id', 'user_editcount' => "COUNT($revUser)" ],
						"user_id > $min AND user_id <= $max",
						__METHOD__,
						[ 'GROUP BY' => 'user_id' ],
						[ 'rev' => [ 'LEFT JOIN', "user_id = $revUser" ] ] + $actorQuery['joins']
					);
				}

				foreach ( $result as $row ) {
					$dbw->update( 'user',
						[ 'user_editcount' => $row->user_editcount ],
						[ 'user_id' => $row->user_id ],
						__METHOD__ );
					++$migrated;
				}

				$delta = microtime( true ) - $start;
				$rate = ( $delta == 0.0 ) ? 0.0 : $migrated / $delta;
				$this->output( sprintf( "%s %d (%0.1f%%) done in %0.1f secs (%0.3f accounts/sec).\n",
					wfWikiID(),
					$migrated,
					min( $max, $lastUser ) / $lastUser * 100.0,
					$delta,
					$rate ) );

				wfWaitForSlaves();
			}
		} else {
			$this->output( "Using single-query mode...\n" );

			$user = $dbw->tableName( 'user' );
			if ( $needSpecialQuery ) {
				$subquery1 = $dbw->selectSQLText(
					[ 'revision', 'revision_actor_temp' ],
					[ 'COUNT(*)' ],
					[
						'user_id = rev_user',
						'revactor_rev' => null,
					],
					__METHOD__,
					[],
					[ 'revision_actor_temp' => [ 'LEFT JOIN', 'revactor_rev = rev_id' ] ]
				);
				$subquery2 = $dbw->selectSQLText(
					[ 'revision' ] + $actorQuery['tables'],
					[ 'COUNT(*)' ],
					'user_id = actor_user',
					__METHOD__,
					[],
					$actorQuery['joins']
				);
				$dbw->query(
					"UPDATE $user SET user_editcount=($subquery1) + ($subquery2)",
					__METHOD__
				);
			} else {
				$subquery = $dbw->selectSQLText(
					[ 'revision' ] + $actorQuery['tables'],
					[ 'COUNT(*)' ],
					[ 'user_id = ' . $actorQuery['fields']['rev_user'] ],
					__METHOD__,
					[],
					$actorQuery['joins']
				);
				$dbw->query( "UPDATE $user SET user_editcount=($subquery)", __METHOD__ );
			}
		}

		$this->output( "Done!\n" );
	}
}

$maintClass = InitEditCount::class;
require_once RUN_MAINTENANCE_IF_MAIN;
