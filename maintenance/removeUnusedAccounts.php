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

		$this->output( "Remove unused accounts\n\n" );

		# Do an initial scan for inactive accounts and report the result
		$this->output( "Checking for unused user accounts...\n" );
		$del = [];
		$dbr = $this->getDB( DB_REPLICA );
		$res = $dbr->select( 'user', [ 'user_id', 'user_name', 'user_touched' ], '', __METHOD__ );
		if ( $this->hasOption( 'ignore-groups' ) ) {
			$excludedGroups = explode( ',', $this->getOption( 'ignore-groups' ) );
		} else {
			$excludedGroups = [];
		}
		$touched = $this->getOption( 'ignore-touched', "1" );
		if ( !ctype_digit( $touched ) ) {
			$this->error( "Please put a valid positive integer on the --ignore-touched parameter.", true );
		}
		$touchedSeconds = 86400 * $touched;
		foreach ( $res as $row ) {
			# Check the account, but ignore it if it's within a $excludedGroups
			# group or if it's touched within the $touchedSeconds seconds.
			$instance = User::newFromId( $row->user_id );
			if ( count( array_intersect( $instance->getEffectiveGroups(), $excludedGroups ) ) == 0
				&& $this->isInactiveAccount( $row->user_id, true )
				&& wfTimestamp( TS_UNIX, $row->user_touched ) < wfTimestamp( TS_UNIX, time() - $touchedSeconds )
			) {
				# Inactive; print out the name and flag it
				$del[] = $row->user_id;
				$this->output( $row->user_name . "\n" );
			}
		}
		$count = count( $del );
		$this->output( "...found {$count}.\n" );

		# If required, go back and delete each marked account
		if ( $count > 0 && $this->hasOption( 'delete' ) ) {
			$this->output( "\nDeleting unused accounts..." );
			$dbw = $this->getDB( DB_MASTER );
			$dbw->delete( 'user', [ 'user_id' => $del ], __METHOD__ );
			$dbw->delete( 'user_groups', [ 'ug_user' => $del ], __METHOD__ );
			$dbw->delete( 'user_former_groups', [ 'ufg_user' => $del ], __METHOD__ );
			$dbw->delete( 'user_properties', [ 'up_user' => $del ], __METHOD__ );
			$dbw->delete( 'logging', [ 'log_user' => $del ], __METHOD__ );
			$dbw->delete( 'recentchanges', [ 'rc_user' => $del ], __METHOD__ );
			$this->output( "done.\n" );
			# Update the site_stats.ss_users field
			$users = $dbw->selectField( 'user', 'COUNT(*)', [], __METHOD__ );
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
	 * @param int $id User's ID
	 * @param bool $master Perform checking on the master
	 * @return bool
	 */
	private function isInactiveAccount( $id, $master = false ) {
		$dbo = $this->getDB( $master ? DB_MASTER : DB_REPLICA );
		$checks = [
			'revision' => 'rev',
			'archive' => 'ar',
			'image' => 'img',
			'oldimage' => 'oi',
			'filearchive' => 'fa'
		];
		$count = 0;

		$this->beginTransaction( $dbo, __METHOD__ );
		foreach ( $checks as $table => $fprefix ) {
			$conds = [ $fprefix . '_user' => $id ];
			$count += (int)$dbo->selectField( $table, 'COUNT(*)', $conds, __METHOD__ );
		}

		$conds = [ 'log_user' => $id, 'log_type != ' . $dbo->addQuotes( 'newusers' ) ];
		$count += (int)$dbo->selectField( 'logging', 'COUNT(*)', $conds, __METHOD__ );

		$this->commitTransaction( $dbo, __METHOD__ );

		return $count == 0;
	}
}

$maintClass = "RemoveUnusedAccounts";
require_once RUN_MAINTENANCE_IF_MAIN;
