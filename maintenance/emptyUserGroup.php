<?php

/**
 * Removes all users from a given user group.
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

use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class EmptyUserGroup extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove all users from a given user group' );
		$this->addArg( 'group', 'Group to be removed', true );
		$this->addOption(
			'create-log',
			'If specified, then log entries are created for each user in the group when emptying the user group.',
		);
		$this->addOption(
			'log-reason',
			'If create-log is specified, then this is used as the reason for the log entries created for ' .
			'emptying the user group. If not provided, then the log will have no reason.',
			false,
			true
		);
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$group = $this->getArg( 0 );
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();

		$totalCount = 0;
		$this->output( "Removing users from $group...\n" );
		while ( true ) {
			$users = User::findUsersByGroup( $group, $this->getBatchSize() );
			if ( iterator_count( $users ) === 0 ) {
				break;
			}

			foreach ( $users as $user ) {
				$oldGroups = $userGroupManager->getUserGroups( $user );
				$groupRemoved = $userGroupManager->removeUserFromGroup( $user, $group );

				if ( $groupRemoved ) {
					$totalCount += 1;
					if ( $this->hasOption( 'create-log' ) ) {
						$this->createLogEntry( $user, $group, $oldGroups );
					}
				}
			}

			$this->waitForReplication();
		}
		if ( $totalCount ) {
			$this->output( "  ...done! Removed $totalCount users in total.\n" );
		} else {
			$this->output( "  ...nothing to do, group was empty.\n" );
		}
	}

	/**
	 * Creates a log entry for a user having their groups changed.
	 *
	 * This does not send the log entry to recentchanges to avoid spamming the list of recent changes.
	 *
	 * @param UserIdentity $target
	 * @param string $removedGroup
	 * @param array $oldGroups
	 * @return void
	 */
	private function createLogEntry( UserIdentity $target, string $removedGroup, array $oldGroups ) {
		$newGroups = $oldGroups;
		$newGroups = array_diff( $newGroups, [ $removedGroup ] );

		$logEntry = new ManualLogEntry( 'rights', 'rights' );
		$logEntry->setPerformer( User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] ) );
		$logEntry->setTarget( Title::makeTitle( NS_USER, $target->getName() ) );
		$logEntry->setComment( $this->getOption( 'log-reason', '' ) );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
		] );
		$logEntry->insert();
	}
}

// @codeCoverageIgnoreStart
$maintClass = EmptyUserGroup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
