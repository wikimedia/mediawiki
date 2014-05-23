<?php
/**
 * Copyright © Withoutaname
 *
 * Remove all users from a specified user group, usually for deprecating
 * the user group in question.
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
 * @author Withoutaname
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to remove all users from a specified user group.
 *
 * @ingroup Maintenance
 */
class EmptyUserGroup extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Remove all users from a specified user group.";
		$this->addArg( 'group', "The user group to empty.", true );
		$this->addArg( 'log', "Specify if you want to log the actions onwiki.", false );
		$this->addOption( 'user', "The username used to perform the removal if --log was set. " .
				"If no user is specified, \"MediaWiki default\" will be used.", false, true );
		$this->addArg( 'bot', "Assign bot rights to hide --user from RecentChanges.", false );
	}

	public function execute() {

		// Construct the name of the usergroup.
		$usergroup = $this->getArg( 0 );
		if ( !in_array( $usergroup, User::getAllGroups() ) ) {
			throw new MWException( "Invalid user group name specified." );
		}

		// Construct the user that will perform the rights removals,
		// only if --log and --user were specified.
		if ( $this->hasArg( 1 ) ) {
			if ( $this->hasOption( 'user' ) ) {
				$performer = User::newFromName( $this->getOption( 'user' ), true );
				// Check if the user exists, if given as an argument.
				if ( !$performer->getId() ) {
					throw new MWException( "Invalid username given as argument." );
				}
				// Check if the user has the correct permissions.
				$changeableGroups = $performer->changeableGroups();
				if ( !in_array( $usergroup, $changeableGroups['remove'] ) ) {
					throw new MWException( "You do not have permission to remove group \"$usergroup\"." );
				}
			} else {
				$performer = User::newFromName( 'MediaWiki default' );
			}
			if ( $this->hasArg( 2 ) ) {
				$performer->addGroup( 'bot' );
			}
		}

		// Construct a list of group member users to remove from the group.
		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );
		$rows = $dbr->select(
			array( 'user_groups' ),
			array( 'ug_user' ),
			array( 'ug_group' => $usergroup )
		);
		foreach ( $rows as $row ) {

			wfWaitForSlaves();
			$dbw->ping();

			// We do not want to remove the user group from ourselves prematurely.
			if ( $row->ug_user == $performer->getId() && in_array( $usergroup, $performer->getGroups() ) ) {
				continue;
			}

			$this->removeUserFromGroup( $usergroup, $row->ug_user, $this->hasArg( 1 ), $performer );
		}

		$this->removeUserFromGroup( $usergroup, $performer->getID(), $this->hasArg( 1 ), $performer );

		$this->output( "\nDone!\n" );
	}

	/**
	 * Function to iterate through to remove each user from the group.
	 *
	 * @param string $usergroup
	 * @param int $userid
	 * @param int|null $log
	 */
	private function removeUserFromGroup( $usergroup, $userid, $log, $performer ) {
		$username = User::whoIs( $userid );
		$target = User::newFromID( $userid );

		$oldgroups = $target->getGroups();
		$this->output( "Removing $usergroup from [[User:$username]].\n" );
		try {
			$target->removeGroup( $usergroup );
		} catch ( DBError $dberror ) {
			$this->error( "The database encountered an error trying to remove from [[User:$username]]." .
					"If this is persistent, it may be a problem with your installation. skipping...\n" );
			return;
		}
		$newgroups = $target->getGroups();

		// If --log is set, log the action to Special:Log and Special:RecentChanges.
		if ( $log ) {
			$usertitle = Title::makeTitleSafe( NS_USER, $username );
			$logentry = new ManualLogEntry( 'rights', 'rights' );
			$logentry->setParameters( array(
				'4::oldgroups' => $oldgroups,
				'5::newgroups' => $newgroups
				)
			);
			$logentry->setPerformer( $performer );
			$logentry->setTarget( $usertitle );
			$logentry->setComment( "Removing all users from the \"$usergroup\" group." );
			try {
				$entryid = $logentry->insert();
				$logentry->publish( $entryid );
			} catch ( MWException $e ) {
				$this->error( "Error: failed to write new log entry to database.\n" );
				return;
			}
		}
	}
}

$maintClass = "EmptyUserGroup";
require_once RUN_MAINTENANCE_IF_MAIN;
