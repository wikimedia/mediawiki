<?php
/**
 * Remove all users from a specified user group, usually for deprecating
 * the user group in question.
 *
 * Copyright © 2014 Withoutaname
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
		$this->addOption( 'log', "Specify if you want to log the actions onwiki.", false, false );
		$this->addOption( 'user', "The username used to perform the removal if --log was set. " .
				"If no user is specified, \"MediaWiki default\" will be used.", false, true );
		$this->addOption( 'bot', "Assign bot rights to hide --user from RecentChanges.", false, false );
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$groupname = $this->getArg( 0 );
		if ( !in_array( $groupname, User::getAllGroups(), true ) ) {
			throw new MWException( "Specified user group name appears to be nonexistent or invalid." );
		}
		$log = $this->hasOption( 'log' );
		if ( $log ) {
			if ( $this->hasOption( 'user' ) ) {
				$performer = User::newFromName( $this->getOption( 'user' ), 'valid' );
				if ( !$performer->getId() ) {
					throw new MWException( "Invalid user name given as argument." );
				}
				$changeableGroups = $performer->changeableGroups();
				if ( !in_array( $groupname, $changeableGroups['remove'] ) ) {
					throw new MWException( "You do not have permission to remove group \"$groupname\"." );
				}
			} else {
				$performer = User::newFromName( 'MediaWiki default' );
			}
			if ( $this->hasOption( 'bot' ) ) {
				$performer->addGroup( 'bot' );
			}
			$logentrybase = new ManualLogEntry( 'rights', 'rights' );
			$summary = wfMessage( 'usergroup-remove-summary', $groupname )->inContentLanguage()->text();
			$logentrybase->setPerformer( $performer );
			$logentrybase->setComment( $summary );
		}
		$dbw = wfGetDB( DB_MASTER );
		$end = $dbw->selectField(
			'user_groups',
			'MAX(ug_user)',
			array( 'ug_group' => $groupname ),
			__FUNCTION__
		);
		if ( $end === null ) {
			$this->error( "No users were detected; this user group is already empty.", true );
		}
		$numRows = $dbw->numRows( $dbw->select(
			array( 'user_groups' ),
			array( 'ug_user' ),
			array( 'ug_group' => $groupname ),
			__METHOD__
		) );
		$leftover = $numRows;
		$start = $end - $this->mBatchSize;
		while ( $numRows > 0 ) {
			$res = $dbw->select(
				array( 'user_groups' ),
				array( 'ug_user' ),
				array( 'ug_group' => $groupname, "ug_user BETWEEN $start AND $end" ),
				__METHOD__
			);
			$dbw->begin( __METHOD__ );
			$dbw->delete(
				array( 'user_groups' ),
				array( 'ug_group' => $groupname, "ug_user BETWEEN $start AND $end" ),
				__METHOD__
			);
			$dbw->commit( __METHOD__ );
			$leftover -= $dbw->affectedRows();
			$numRows -= $this->mBatchSize;

			foreach ( $res as $row ) {
				$user = User::newFromId( $row->ug_user );
				$user->invalidateCache();
				if ( $log ) {
					$logentry = clone $logentrybase;
					$logentry->setParameters( array(
						'4::oldgroups' => ( $user->getGroups() + array( $groupname ) ),
						'5::newgroups' => $user->getGroups(),
					) );
					$logentry->setTarget( Title::makeTitleSafe( NS_USER, User::whoIs( $row->ug_user ) ) );
					try {
						$logid = $logentry->insert();
						$logentry->publish( $logid );
					} catch ( MWException $e ) {
						$this->error( "Error: failed to write new log entry to database.\n" );
					}
				}
			}
			$start -= $this->mBatchSize;
			$end -= $this->mBatchSize;
			wfWaitForSlaves();
		}
		$performer->removeGroup( 'bot' );
		if ( $leftover ) {
			$leftover = " However, $leftover users were unable to be removed.";
		}
		$this->output( "\nDone! Removed $count users from user group \"$groupname\"." . $leftover . "\n" );
	}
}

$maintClass = "EmptyUserGroup";
require_once RUN_MAINTENANCE_IF_MAIN;
