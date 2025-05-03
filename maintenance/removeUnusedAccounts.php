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

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\User\UserIdentity;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		$dbr = $this->getReplicaDB();
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
			$dbw = $this->getPrimaryDB();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user' )
				->where( [ 'user_id' => $delUser ] )
				->caller( __METHOD__ )->execute();
			# Keep actor rows referenced from block
			$keep = $dbw->newSelectQueryBuilder()
				->select( 'bl_by_actor' )
				->from( 'block' )
				->where( [ 'bl_by_actor' => $delActor ] )
				->caller( __METHOD__ )->fetchFieldValues();
			$del = array_diff( $delActor, $keep );
			if ( $del ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'actor' )
					->where( [ 'actor_id' => $del ] )
					->caller( __METHOD__ )->execute();
			}
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user_groups' )
				->where( [ 'ug_user' => $delUser ] )
				->caller( __METHOD__ )->execute();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user_former_groups' )
				->where( [ 'ufg_user' => $delUser ] )
				->caller( __METHOD__ )->execute();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user_properties' )
				->where( [ 'up_user' => $delUser ] )
				->caller( __METHOD__ )->execute();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'logging' )
				->where( [ 'log_actor' => $delActor ] )
				->caller( __METHOD__ )->execute();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'recentchanges' )
				->where( [ 'rc_actor' => $delActor ] )
				->caller( __METHOD__ )->execute();
			$this->output( "done.\n" );
			# Update the site_stats.ss_users field
			$users = $dbw->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'user' )
				->caller( __METHOD__ )->fetchField();
			$dbw->newUpdateQueryBuilder()
				->update( 'site_stats' )
				->set( [ 'ss_users' => $users ] )
				->where( [ 'ss_row_id' => 1 ] )
				->caller( __METHOD__ )
				->execute();
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

		$dbo = $primary ? $this->getPrimaryDB() : $this->getReplicaDB();
		$checks = [
			'archive' => 'ar',
			'image' => 'img',
			'oldimage' => 'oi',
			'filearchive' => 'fa',
			'revision' => 'rev',
		];
		$count = 0;

		$this->beginTransaction( $dbo, __METHOD__ );
		foreach ( $checks as $table => $prefix ) {
			$count += (int)$dbo->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( $table )
				->where( [ "{$prefix}_actor" => $actor ] )
				->caller( __METHOD__ )
				->fetchField();
		}

		$count += (int)$dbo->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->where( [ 'log_actor' => $actor, $dbo->expr( 'log_type', '!=', 'newusers' ) ] )
			->caller( __METHOD__ )->fetchField();

		$this->commitTransaction( $dbo, __METHOD__ );

		return $count == 0;
	}
}

// @codeCoverageIgnoreStart
$maintClass = RemoveUnusedAccounts::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
