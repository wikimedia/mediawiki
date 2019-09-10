<?php
/**
 * Script to change users preferences on the fly.
 *
 * Made on an original idea by Fooey (freenode)
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
 * @author Antoine Musso <hashar at free dot fr>
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * @ingroup Maintenance
 */
class UserOptionsMaintenance extends Maintenance {

	function __construct() {
		parent::__construct();

		$this->addDescription( 'Pass through all users and change one of their options.
The new option is NOT validated.' );

		$this->addOption( 'list', 'List available user options and their default value' );
		$this->addOption( 'usage', 'Report all options statistics or just one if you specify it' );
		$this->addOption( 'old', 'The value to look for', false, true );
		$this->addOption( 'new', 'Rew value to update users with', false, true );
		$this->addOption( 'nowarn', 'Hides the 5 seconds warning' );
		$this->addOption( 'dry', 'Do not save user settings back to database' );
		$this->addArg( 'option name', 'Name of the option to change or provide statistics about', false );
	}

	/**
	 * Do the actual work
	 */
	public function execute() {
		if ( $this->hasOption( 'list' ) ) {
			$this->listAvailableOptions();
		} elseif ( $this->hasOption( 'usage' ) ) {
			$this->showUsageStats();
		} elseif ( $this->hasOption( 'old' )
			&& $this->hasOption( 'new' )
			&& $this->hasArg( 0 )
		) {
			$this->updateOptions();
		} else {
			$this->maybeHelp( /* force = */ true );
		}
	}

	/**
	 * List default options and their value
	 */
	private function listAvailableOptions() {
		$def = User::getDefaultOptions();
		ksort( $def );
		$maxOpt = 0;
		foreach ( $def as $opt => $value ) {
			$maxOpt = max( $maxOpt, strlen( $opt ) );
		}
		foreach ( $def as $opt => $value ) {
			$this->output( sprintf( "%-{$maxOpt}s: %s\n", $opt, $value ) );
		}
	}

	/**
	 * List options usage
	 */
	private function showUsageStats() {
		$option = $this->getArg( 0 );

		$ret = [];
		$defaultOptions = User::getDefaultOptions();

		// We list user by user_id from one of the replica DBs
		$dbr = wfGetDB( DB_REPLICA );
		$result = $dbr->select( 'user',
			[ 'user_id' ],
			[],
			__METHOD__
		);

		foreach ( $result as $id ) {
			$user = User::newFromId( $id->user_id );

			// Get the options and update stats
			if ( $option ) {
				if ( !array_key_exists( $option, $defaultOptions ) ) {
					$this->fatalError( "Invalid user option. Use --list to see valid choices\n" );
				}

				$userValue = $user->getOption( $option );
				if ( $userValue <> $defaultOptions[$option] ) {
					$ret[$option][$userValue] = ( $ret[$option][$userValue] ?? 0 ) + 1;
				}
			} else {
				foreach ( $defaultOptions as $name => $defaultValue ) {
					$userValue = $user->getOption( $name );
					if ( $userValue != $defaultValue ) {
						$ret[$name][$userValue] = ( $ret[$name][$userValue] ?? 0 ) + 1;
					}
				}
			}
		}

		foreach ( $ret as $optionName => $usageStats ) {
			$this->output( "Usage for <$optionName> (default: '{$defaultOptions[$optionName]}'):\n" );
			foreach ( $usageStats as $value => $count ) {
				$this->output( " $count user(s): '$value'\n" );
			}
			print "\n";
		}
	}

	/**
	 * Change our users options
	 */
	private function updateOptions() {
		$dryRun = $this->hasOption( 'dry' );
		$option = $this->getArg( 0 );
		$from = $this->getOption( 'old' );
		$to = $this->getOption( 'new' );

		if ( !$dryRun ) {
			$this->warn( $option, $from, $to );
		}

		// We list user by user_id from one of the replica DBs
		// @todo: getting all users in one query does not scale
		$dbr = wfGetDB( DB_REPLICA );
		$result = $dbr->select( 'user',
			[ 'user_id' ],
			[],
			__METHOD__
		);

		foreach ( $result as $id ) {
			$user = User::newFromId( $id->user_id );

			$curValue = $user->getOption( $option );
			$username = $user->getName();

			if ( $curValue == $from ) {
				$this->output( "Setting {$option} for $username from '{$from}' to '{$to}'): " );

				// Change value
				$user->setOption( $option, $to );

				// Will not save the settings if run with --dry
				if ( !$dryRun ) {
					$user->saveSettings();
				}
				$this->output( " OK\n" );
			} else {
				$this->output( "Not changing '$username' using <{$option}> = '$curValue'\n" );
			}
		}
	}

	/**
	 * The warning message and countdown
	 *
	 * @param string $option
	 * @param string $from
	 * @param string $to
	 */
	private function warn( $option, $from, $to ) {
		if ( $this->hasOption( 'nowarn' ) ) {
			return;
		}

		$this->output( <<<WARN
The script is about to change the options for ALL USERS in the database.
Users with option <$option> = '$from' will be made to use '$to'.

Abort with control-c in the next five seconds....
WARN
		);
		$this->countDown( 5 );
	}
}

$maintClass = UserOptionsMaintenance::class;
require RUN_MAINTENANCE_IF_MAIN;
