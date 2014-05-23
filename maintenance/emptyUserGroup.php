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

	/**
	 * @var ManualLogEntry object to be created if --log was specified.
	 */
	private $entry;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Remove all users from a specified user group.";
		$this->addArg( 'group', "The user group to empty.", true );
		$this->addOption( 'log', "Specify if you want to log the actions onwiki.", false, false );
		$this->addOption( 'user', "The username used to perform the removal if --log was set. " .
				"If no user is specified, \"MediaWiki default\" will be used.", false, true );
		$this->addOption( 'bot', "Assign bot rights to hide --user from RecentChanges.", false, false );
	}

	public function execute() {
		$log = $this->hasOption( 'log' );

		// Construct the name of the usergroup.
		$usergroup = $this->getArg( 0 );
		if ( !in_array( $usergroup, User::getAllGroups() ) ) {
			throw new MWException( "Invalid user group name specified." );
		}

		if ( $log ) {
			// Construct the user that will perform the rights removals,
			// only if both --log and --user were specified.
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
			if ( $this->hasOption( 'bot' ) ) {
				$performer->addGroup( 'bot' );
			}

			// Prototype to construct the log entry if --log was enabled.
			$this->entry = new ManualLogEntry( 'rights', 'rights' );
			$summary = wfMessage( 'usergroup-remove-summary', $usergroup )->inContentLanguage()->text();
			$this->entry->setPerformer( $performer );
			$this->entry->setComment( $summary );
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

			$this->removeUserFromGroup( $usergroup, $row->ug_user, $log, $performer );
		}

		$this->removeUserFromGroup( $usergroup, $performer->getID(), $log, $performer );

		$this->output( "\nDone!\n" );
	}

	/**
	 * Function to iterate through to remove each user from the group.
	 *
	 * @param string $usergroup the target usergroup to remove
	 * @param int $userid the ID number of the user to remove from, as stored in the database
	 * @param bool|null $log whether to log the removal onwiki or not
	 * @param string $performer the username used to perform the removal
	 */
	private function removeUserFromGroup( $usergroup, $userid, $log, $performer ) {
		$username = User::whoIs( $userid );
		$target = User::newFromID( $userid );
		$usertitle = Title::makeTitleSafe( NS_USER, $username );
		$uttext = $usertitle->getPrefixedText();

		$oldgroups = $target->getGroups();
		$this->output( "Removing $usergroup from [[$uttext]].\n" );
		try {
			$target->removeGroup( $usergroup );
		} catch ( DBError $dberror ) {
			$this->error( "The database encountered an error trying to remove from [[$uttext]]. " .
					"If this is persistent, it may be a problem with your installation. skipping...\n" );
			return;
		}
		$newgroups = $target->getGroups();

		// If --log is set, log the action to Special:Log and Special:RecentChanges.
		if ( $log && $this->entry ) {
			$logentry = clone $this->entry;
			$logentry->setParameters( array(
				'4::oldgroups' => $oldgroups,
				'5::newgroups' => $newgroups
				)
			);
			$logentry->setTarget( $usertitle );
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
