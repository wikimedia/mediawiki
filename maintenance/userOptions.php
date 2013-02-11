<?php
/**
 * Implements the userOptions.php maintenance script for the MediaWiki software.
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
 */

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Script to list user options, report usage statistics, and change all users with a user
 * option and value to a new value.
 *
 * @since 1.21
 */
class UserOptionsScript extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addDescription( 'List the available user options, report usage statistics, or change the value of all user options.' );
		$this->addOption( 'list', 'List the available user options and their default value' );
		$this->addOption( 'usage', 'Report all options statistics (or just one if specified)' );
		$this->addOption( 'old', 'The value to look for', false, true );
		$this->addOption( 'new', 'The new value to update', false, true );
		$this->addOption( 'nowarn', 'Skip the five second warning' );
		$this->addOption( 'dry', 'Perform a dry run and do no actually save user settings to the database' );
	}

	function execute() {
		if ( $this->hasOption( 'list' ) ) {
			$this->showUserOptions();
		} elseif ( $this->hasOption( 'usage' ) ) {
			$this->showOptionUsage();
		} else {
			$this->changeOptions();
		}
	}

	private function showUserOptions() {
		$def = User::getDefaultOptions();
		ksort( $def );
		$maxOpt = max( array_map( 'strlen', array_keys( $def ) ) );
		foreach ( $def as $opt => $value ) {
			$this->output( sprintf( "%-{$maxOpt}s: %s\n", $opt, $value ) );
		}
	}

	private function showOptionUsage() {
		$def = User::getDefaultOptions();
		if ( $this->hasArg( 0 ) ) {
			$option = $this->getArg( 0 );
			if ( !isset( $def[$option] ) ) {
				$this->error( "Invalid user option $option.", true );
			}
			$options = array( $option => $def[$option] );
		} else {
			$options = $def;
		}

		$db = $this->getDB( DB_SLAVE );
		$res = $db->select(
			'user_properties',
			array(
				'up_property',
				'up_value',
				'count' => 'COUNT(' . $db->addIdentifierQuotes( 'up_user' ) . ')'
			),
			array(
				'up_property' => array_keys( $def )
			),
			__METHOD__,
			array(
				'GROUP BY' => array( 'up_property', 'up_value' ),
				'SORT BY' => array( 'up_property', 'up_value' )
			)
		);

		$currOpt = '';
		foreach ( $res as $row ) {
			if ( $row->up_property !== $currOpt ) {
				$currOpt = $row->up_property;
				$this->output( "\nUsage for <{$currOpt}> (default: '{$def[$currOpt]}'):\n" );
			}
			$this->output( " {$row->count} user(s): '{$row->up_value}'\n" );
		}
	}

	private function changeOptions() {
		global $wgAllowPrefChange;

		if ( !$this->hasOption( 'old' ) || !$this->hasOption( 'new' ) || !$this->hasArg( 0 ) ) {
			$this->maybeHelp( true );
			return;
		}

		$option = $this->getArg( 0 );
		$old = $this->getOption( 'old' );
		$new = $this->getOption( 'new' );
		$dry = $this->hasOption( 'dry' );

		if ( $dry ) {
			$db = wfGetDB( DB_SLAVE );
			$options = array();
		} else {
			$db = wfGetDB( DB_MASTER );
			$options = array( 'FOR UPDATE' );
			$db->begin();
		}

		$res = $db->select(
			array( 'user_properties', 'user' ),
			array( 'user_id', 'user_name' ),
			array(
				'up_property' => $option,
				'up_value' => $old
			),
			__METHOD__,
			$options,
			array( 'user' => array( 'LEFT JOIN', 'up_user=user_id' ) )
		);

		foreach( $res as $row ) {
			$this->output( "Setting $option for {$row->user_name} from '$old' to '$new'.\n" );

			if (
				!$dry &&
				isset( $wgAllowPrefChange[$option] ) &&
				( $wgAllowPrefChange[$option] == 'semiglobal' || $wgAllowPrefChange[$option] == 'global' )
			) {
				$user = User::newFromId( $row->user_id );
				$extuser = ExternalUser::newFromUser( $user );
				if ( $extuser ) {
					$extuser->setPref( $option, $new );
				}
			}
		}

		if ( $dry ) {
			return;
		}

		if ( !$this->hasOption( 'nowarn' ) ) {
			$this->output( "The script is about to change options for ALL USERS in the database\n" );
			$this->output( "Users with option <$option> = '$old' will be changed to '$new'.\n" );
			$this->output( "\n" );
			$this->output( "Abort with Ctrl-C in the next five seconds...\n" );
			wfCountDown( 5 );
		}

		$def = User::getDefaultOptions();
		if ( $new === $def[$option] ) {
			// New value is the default. Delete the rows.
			$db->delete(
				'user_properties',
				array(
					'up_property' => $option,
					'up_value' => $old
				),
				__METHOD__
			);
		} else {
			// Non-default value, so update.
			$db->update(
				'user_properties',
				array( 'up_value' => $new ),
				array(
					'up_property' => $option,
					'up_value' => $old
				),
				__METHOD__
			);
		}

		$db->commit();
	}
}

$maintClass = 'UserOptionsScript';
require_once( RUN_MAINTENANCE_IF_MAIN );
