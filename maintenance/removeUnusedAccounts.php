<?php
/**
 * Remove unused user accounts from the database
 * An unused account is one which has made no edits
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
 * @author Rob Church <robchur@gmail.com>
 */

use MediaWiki\User\ActorMigration;
use MediaWiki\User\UserIdentity;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that removes unused user accounts from the database.
 *
 * @ingroup Maintenance
 */
class RemoveUnusedAccounts extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'delete', 'Actually delete the account' );
		$this->addOption( 'ignore-groups', 'List of comma-separated groups to exclude', false, true );
		$this->addOption( 'ignore-touched', 'Skip accounts touched in last N days', false, true );
	}

	public function execute() {
		$services = $this->getServiceContainer();
		$userFactory = $services->getUserFactory();
		$userGroupManager = $services->getUserGroupManager();
		$this->output( "Remove unused accounts\n\n" );

		# Do an initial scan for inactive accounts and report the result
		$this->output( "Checking for unused user accounts...\n" );
		$delUser = [];
		$delActor = [];
		$dbr = $this->getDB( DB_REPLICA );
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'user_id', 'user_name', 'user_touched', 'actor_id' ] )
			->from( 'user' )
			->leftJoin( 'actor', null, 'user_id = actor_user' )
			->caller( __METHOD__ )->fetchResultSet();
		if ( $this->hasOption( 'ignore-groups' ) ) {
			$excludedGroups = explode( ',', $this->getOption( 'ignore-groups' ) );
		} else {
			$excludedGroups = [];
		}
		$touched = $this->getOption( 'ignore-touched', "1" );
		if ( !ctype_digit( $touched ) ) {
			$this->fatalError( "Please put a valid positive integer on the --ignore-touched parameter." );
		}
		$touchedSeconds = 86400 * $touched;
		foreach ( $res as $row ) {
			# Check the account, but ignore it if it's within a $excludedGroups
			# group or if it's touched within the $touchedSeconds seconds.
			$instance = $userFactory->newFromId( $row->user_id );
			if ( count(
				array_intersect( $userGroupManager->getUserEffectiveGroups( $instance ), $excludedGroups ) ) == 0
				&& $this->isInactiveAccount( $instance, $row->actor_id ?? null, true )
				&& wfTimestamp( TS_UNIX, $row->user_touched ) < wfTimestamp( TS_UNIX, time() - $touchedSeconds
				)
			) {
				# Inactive; print out the name and flag it
				$delUser[] = $row->user_id;
				if ( isset( $row->actor_id ) && $row->actor_id ) {
					$delActor[] = $row->actor_id;
				}
				$this->output( $row->user_name . "\n" );
			}
		}
		$count = count( $delUser );
		$this->output( "...found {$count}.\n" );

		# If required, go back and delete each marked account
		if ( $count > 0 && $this->hasOption( 'delete' ) ) {
			$this->output( "\nDeleting unused accounts..." );
			$dbw = $this->getDB( DB_PRIMARY );
			$dbw->delete( 'user', [ 'user_id' => $delUser ], __METHOD__ );
			# Keep actor rows referenced from ipblocks
			$keep = $dbw->newSelectQueryBuilder()
				->select( 'ipb_by_actor' )
				->from( 'ipblocks' )
				->where( [ 'ipb_by_actor' => $delActor ] )
				->caller( __METHOD__ )->fetchFieldValues();
			$del = array_diff( $delActor, $keep );
			if ( $del ) {
				$dbw->delete( 'actor', [ 'actor_id' => $del ], __METHOD__ );
			}
			if ( $keep ) {
				$dbw->update( 'actor', [ 'actor_user' => null ], [ 'actor_id' => $keep ], __METHOD__ );
			}
			$dbw->delete( 'user_groups', [ 'ug_user' => $delUser ], __METHOD__ );
			$dbw->delete( 'user_former_groups', [ 'ufg_user' => $delUser ], __METHOD__ );
			$dbw->delete( 'user_properties', [ 'up_user' => $delUser ], __METHOD__ );
			$dbw->delete( 'logging', [ 'log_actor' => $delActor ], __METHOD__ );
			$dbw->delete( 'recentchanges', [ 'rc_actor' => $delActor ], __METHOD__ );
			$this->output( "done.\n" );
			# Update the site_stats.ss_users field
			$users = $dbw->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'user' )
				->caller( __METHOD__ )->fetchField();
			$dbw->update(
				'site_stats',
				[ 'ss_users' => $users ],
				[ 'ss_row_id' => 1 ],
				__METHOD__
			);
		} elseif ( $count > 0 ) {
			$this->output( "\nRun the script again with --delete to remove them from the database.\n" );
		}
		$this->output( "\n" );
	}

	/**
	 * Could the specified user account be deemed inactive?
	 * (No edits, no deleted edits, no log entries, no current/old uploads)
	 *
	 * @param UserIdentity $user
	 * @param int|null $actor User's actor ID
	 * @param bool $primary Perform checking on the primary DB
	 * @return bool
	 */
	private function isInactiveAccount( $user, $actor, $primary = false ) {
		if ( $actor === null ) {
			// There's no longer a way for a user to be active in any of
			// these tables without having an actor ID. The only way to link
			// to a user row is via an actor row.
			return true;
		}

		$dbo = $this->getDB( $primary ? DB_PRIMARY : DB_REPLICA );
		$checks = [
			'archive' => 'ar',
			'image' => 'img',
			'oldimage' => 'oi',
			'filearchive' => 'fa'
			// re-add when actor migration is complete
			// 'revision' => 'rev'
		];
		$count = 0;

		$this->beginTransaction( $dbo, __METHOD__ );
		foreach ( $checks as $table => $prefix ) {
			$count += (int)$dbo->selectField(
				$table,
				'COUNT(*)',
				[ "{$prefix}_actor" => $actor ],
				__METHOD__
			);
		}

		// Delete this special case when the actor migration is complete
		$actorQuery = ActorMigration::newMigration()->getWhere( $dbo, 'rev_user', $user );
		$count += (int)$dbo->selectField(
			[ 'revision' ] + $actorQuery['tables'],
			'COUNT(*)',
			$actorQuery['conds'],
			__METHOD__,
			[],
			$actorQuery['joins']
		);

		$count += (int)$dbo->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->where( [ 'log_actor' => $actor, 'log_type != ' . $dbo->addQuotes( 'newusers' ) ] )
			->caller( __METHOD__ )->fetchField();

		$this->commitTransaction( $dbo, __METHOD__ );

		return $count == 0;
	}
}

$maintClass = RemoveUnusedAccounts::class;
require_once RUN_MAINTENANCE_IF_MAIN;
