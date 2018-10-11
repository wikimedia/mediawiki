<?php
/**
 * Reassign edits from a user or IP address to another user
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
 * @license GPL-2.0-or-later
 */

use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that reassigns edits from a user or IP address
 * to another user.
 *
 * @ingroup Maintenance
 */
class ReassignEdits extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Reassign edits from one user to another' );
		$this->addOption( "force", "Reassign even if the target user doesn't exist" );
		$this->addOption( "norc", "Don't update the recent changes table" );
		$this->addOption( "report", "Print out details of what would be changed, but don't update it" );
		$this->addArg( 'from', 'Old user to take edits from' );
		$this->addArg( 'to', 'New user to give edits to' );
	}

	public function execute() {
		if ( $this->hasArg( 0 ) && $this->hasArg( 1 ) ) {
			# Set up the users involved
			$from = $this->initialiseUser( $this->getArg( 0 ) );
			$to = $this->initialiseUser( $this->getArg( 1 ) );

			# If the target doesn't exist, and --force is not set, stop here
			if ( $to->getId() || $this->hasOption( 'force' ) ) {
				# Reassign the edits
				$report = $this->hasOption( 'report' );
				$this->doReassignEdits( $from, $to, !$this->hasOption( 'norc' ), $report );
				# If reporting, and there were items, advise the user to run without --report
				if ( $report ) {
					$this->output( "Run the script again without --report to update.\n" );
				}
			} else {
				$ton = $to->getName();
				$this->error( "User '{$ton}' not found." );
			}
		}
	}

	/**
	 * Reassign edits from one user to another
	 *
	 * @param User $from User to take edits from
	 * @param User $to User to assign edits to
	 * @param bool $rc Update the recent changes table
	 * @param bool $report Don't change things; just echo numbers
	 * @return int Number of entries changed, or that would be changed
	 */
	private function doReassignEdits( &$from, &$to, $rc = false, $report = false ) {
		global $wgActorTableSchemaMigrationStage;

		$dbw = $this->getDB( DB_MASTER );
		$this->beginTransaction( $dbw, __METHOD__ );

		# Count things
		$this->output( "Checking current edits..." );
		$revQueryInfo = ActorMigration::newMigration()->getWhere( $dbw, 'rev_user', $from );
		$res = $dbw->select(
			[ 'revision' ] + $revQueryInfo['tables'],
			'COUNT(*) AS count',
			$revQueryInfo['conds'],
			__METHOD__,
			[],
			$revQueryInfo['joins']
		);
		$row = $dbw->fetchObject( $res );
		$cur = $row->count;
		$this->output( "found {$cur}.\n" );

		$this->output( "Checking deleted edits..." );
		$arQueryInfo = ActorMigration::newMigration()->getWhere( $dbw, 'ar_user', $from, false );
		$res = $dbw->select(
			[ 'archive' ] + $arQueryInfo['tables'],
			'COUNT(*) AS count',
			$arQueryInfo['conds'],
			__METHOD__,
			[],
			$arQueryInfo['joins']
		);
		$row = $dbw->fetchObject( $res );
		$del = $row->count;
		$this->output( "found {$del}.\n" );

		# Don't count recent changes if we're not supposed to
		if ( $rc ) {
			$this->output( "Checking recent changes..." );
			$rcQueryInfo = ActorMigration::newMigration()->getWhere( $dbw, 'rc_user', $from, false );
			$res = $dbw->select(
				[ 'recentchanges' ] + $rcQueryInfo['tables'],
				'COUNT(*) AS count',
				$rcQueryInfo['conds'],
				__METHOD__,
				[],
				$rcQueryInfo['joins']
			);
			$row = $dbw->fetchObject( $res );
			$rec = $row->count;
			$this->output( "found {$rec}.\n" );
		} else {
			$rec = 0;
		}

		$total = $cur + $del + $rec;
		$this->output( "\nTotal entries to change: {$total}\n" );

		if ( !$report ) {
			if ( $total ) {
				# Reassign edits
				$this->output( "\nReassigning current edits..." );
				if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) {
					$dbw->update(
						'revision',
						[
							'rev_user' => $to->getId(),
							'rev_user_text' => $to->getName(),
						],
						$from->isLoggedIn()
							? [ 'rev_user' => $from->getId() ] : [ 'rev_user_text' => $from->getName() ],
						__METHOD__
					);
				}
				if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
					$dbw->update(
						'revision_actor_temp',
						[ 'revactor_actor' => $to->getActorId( $dbw ) ],
						[ 'revactor_actor' => $from->getActorId() ],
						__METHOD__
					);
				}
				$this->output( "done.\nReassigning deleted edits..." );
				$dbw->update( 'archive',
					$this->userSpecification( $dbw, $to, 'ar_user', 'ar_user_text', 'ar_actor' ),
					[ $arQueryInfo['conds'] ], __METHOD__ );
				$this->output( "done.\n" );
				# Update recent changes if required
				if ( $rc ) {
					$this->output( "Updating recent changes..." );
					$dbw->update( 'recentchanges',
						$this->userSpecification( $dbw, $to, 'rc_user', 'rc_user_text', 'rc_actor' ),
						[ $rcQueryInfo['conds'] ], __METHOD__ );
					$this->output( "done.\n" );
				}
			}
		}

		$this->commitTransaction( $dbw, __METHOD__ );

		return (int)$total;
	}

	/**
	 * Return user specifications for an UPDATE
	 * i.e. user => id, user_text => text
	 *
	 * @param IDatabase $dbw Database handle
	 * @param User $user User for the spec
	 * @param string $idfield Field name containing the identifier
	 * @param string $utfield Field name containing the user text
	 * @param string $acfield Field name containing the actor ID
	 * @return array
	 */
	private function userSpecification( IDatabase $dbw, &$user, $idfield, $utfield, $acfield ) {
		global $wgActorTableSchemaMigrationStage;

		$ret = [];
		if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret += [
				$idfield => $user->getId(),
				$utfield => $user->getName(),
			];
		}
		if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
			$ret += [ $acfield => $user->getActorId( $dbw ) ];
		}
		return $ret;
	}

	/**
	 * Initialise the user object
	 *
	 * @param string $username Username or IP address
	 * @return User
	 */
	private function initialiseUser( $username ) {
		if ( User::isIP( $username ) ) {
			$user = new User();
			$user->setId( 0 );
			$user->setName( $username );
		} else {
			$user = User::newFromName( $username );
			if ( !$user ) {
				$this->fatalError( "Invalid username" );
			}
		}
		$user->load();

		return $user;
	}
}

$maintClass = ReassignEdits::class;
require_once RUN_MAINTENANCE_IF_MAIN;
