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

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\User\User;
use Wikimedia\IPUtils;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		# Set up the users involved
		$from = $this->initialiseUser( $this->getArg( 0 ) );
		$to = $this->initialiseUser( $this->getArg( 1 ) );

		// Reject attempts to re-assign to an IP address. This is done because the script does not
		// populate ip_changes and it breaks if temporary accounts are enabled (T373914).
		if ( IPUtils::isIPAddress( $to->getName() ) ) {
			$this->fatalError( 'Script does not support re-assigning to another IP.' );
		} elseif ( $from->equals( $to ) ) {
			$this->fatalError( 'The from and to user cannot be the same.' );
		}

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
			$this->fatalError( "User '{$to->getName()}' not found." );
		}
	}

	/**
	 * Reassign edits from one user to another
	 *
	 * @param User &$from User to take edits from
	 * @param User &$to User to assign edits to
	 * @param bool $updateRC Update the recent changes table
	 * @param bool $report Don't change things; just echo numbers
	 * @return int The number of entries changed, or that would be changed
	 */
	private function doReassignEdits( &$from, &$to, $updateRC = false, $report = false ) {
		$dbw = $this->getPrimaryDB();
		$this->beginTransaction( $dbw, __METHOD__ );
		$actorNormalization = $this->getServiceContainer()->getActorNormalization();
		$fromActorId = $actorNormalization->findActorId( $from, $dbw );

		# Count things
		$this->output( "Checking current edits..." );

		$revisionRows = $dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'revision' )
			->where( [ 'rev_actor' => $fromActorId ] )
			->caller( __METHOD__ )
			->fetchRowCount();

		$this->output( "found {$revisionRows}.\n" );

		$this->output( "Checking deleted edits..." );
		$archiveRows = $dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'archive' )
			->where( [ 'ar_actor' => $fromActorId ] )
			->caller( __METHOD__ )->fetchRowCount();
		$this->output( "found {$archiveRows}.\n" );

		# Don't count recent changes if we're not supposed to
		if ( $updateRC ) {
			$this->output( "Checking recent changes..." );
			$recentChangesRows = $dbw->newSelectQueryBuilder()
				->select( '*' )
				->from( 'recentchanges' )
				->where( [ 'rc_actor' => $fromActorId ] )
				->caller( __METHOD__ )->fetchRowCount();
			$this->output( "found {$recentChangesRows}.\n" );
		} else {
			$recentChangesRows = 0;
		}

		$total = $revisionRows + $archiveRows + $recentChangesRows;
		$this->output( "\nTotal entries to change: {$total}\n" );

		$toActorId = $actorNormalization->acquireActorId( $to, $dbw );
		if ( !$report && $total ) {
			$this->output( "\n" );
			if ( $revisionRows ) {
				# Reassign edits
				$this->output( "Reassigning current edits..." );

				$dbw->newUpdateQueryBuilder()
					->update( 'revision' )
					->set( [ 'rev_actor' => $toActorId ] )
					->where( [ 'rev_actor' => $fromActorId ] )
					->caller( __METHOD__ )->execute();

				$this->output( "done.\n" );
			}

			if ( $archiveRows ) {
				$this->output( "Reassigning deleted edits..." );

				$dbw->newUpdateQueryBuilder()
					->update( 'archive' )
					->set( [ 'ar_actor' => $toActorId ] )
					->where( [ 'ar_actor' => $fromActorId ] )
					->caller( __METHOD__ )->execute();

				$this->output( "done.\n" );
			}
			# Update recent changes if required
			if ( $recentChangesRows ) {
				$this->output( "Updating recent changes..." );

				$dbw->newUpdateQueryBuilder()
					->update( 'recentchanges' )
					->set( [ 'rc_actor' => $toActorId ] )
					->where( [ 'rc_actor' => $fromActorId ] )
					->caller( __METHOD__ )->execute();

				$this->output( "done.\n" );
			}

			# If $from is an IP, delete any relevant rows from the
			# ip_changes. No update needed, as $to cannot be an IP.
			if ( !$from->isRegistered() ) {
				$this->output( "Deleting ip_changes..." );

				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'ip_changes' )
					->where( [ 'ipc_hex' => IPUtils::toHex( $from->getName() ) ] )
					->caller( __METHOD__ )->execute();

				$this->output( "done.\n" );
			}
		}

		$this->commitTransaction( $dbw, __METHOD__ );

		return $total;
	}

	/**
	 * Initialise the user object
	 *
	 * @param string $username Username or IP address
	 * @return User
	 */
	private function initialiseUser( $username ) {
		$services = $this->getServiceContainer();
		if ( $services->getUserNameUtils()->isIP( $username ) ) {
			$user = User::newFromName( $username, false );
			$user->getActorId();
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

// @codeCoverageIgnoreStart
$maintClass = ReassignEdits::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
