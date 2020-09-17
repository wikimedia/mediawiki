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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

class EmptyUserGroup extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove all users from a given user group' );
		$this->addArg( 'group', 'Group to be removed', true );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$group = $this->getArg( 0 );
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$userGroupManager = MediaWikiServices::getInstance()->getUserGroupManager();

		$totalCount = 0;
		$this->output( "Removing users from $group...\n" );
		while ( true ) {
			$users = User::findUsersByGroup( $group, $this->getBatchSize() );
			if ( iterator_count( $users ) === 0 ) {
				break;
			}

			foreach ( $users as $user ) {
				$totalCount += (int)$userGroupManager->removeUserFromGroup( $user, $group );
			}
			$lb->waitForReplication();
		}
		if ( $totalCount ) {
			$this->output( "  ...done! Removed $totalCount users in total.\n" );
		} else {
			$this->output( "  ...nothing to do, group was empty.\n" );
		}
	}
}

$maintClass = EmptyUserGroup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
