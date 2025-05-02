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

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Maintenance
 */
class UserOptionsMaintenance extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Pass through all users and change or delete one of their options.
The new option is NOT validated.' );

		$this->addOption( 'list', 'List available user options and their default value' );
		$this->addOption( 'usage', 'Report all options statistics or just one if you specify it' );
		$this->addOption(
			'old',
			'The value to look for. If it is a default value for the option, pass --old-is-default as well.',
			false, true, false, true
		);
		$this->addOption( 'old-is-default', 'If passed, --old is interpreted as a default value.' );
		$this->addOption( 'new', 'New value to update users with', false, true );
		$this->addOption( 'delete', 'Delete the option instead of updating' );
		$this->addOption( 'delete-defaults', 'Delete user_properties row matching the default' );
		$this->addOption( 'fromuserid', 'Start from this user ID when changing/deleting options',
			false, true );
		$this->addOption( 'touserid', 'Do not go beyond this user ID when changing/deleting options',
			false, true );
		$this->addOption( 'nowarn', 'Hides the 5 seconds warning' );
		$this->addOption( 'dry', 'Do not save user settings back to database' );
		$this->addArg( 'option name', 'Name of the option to change or provide statistics about', false );
		$this->setBatchSize( 100 );
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
		} elseif ( $this->hasOption( 'delete' ) ) {
			$this->deleteOptions();
		} elseif ( $this->hasOption( 'delete-defaults' ) ) {
			$this->deleteDefaults();
		} else {
			$this->maybeHelp( true );
		}
	}

	/**
	 * List default options and their value
	 */
	private function listAvailableOptions() {
		$userOptionsLookup = $this->getServiceContainer()->getUserOptionsLookup();
		$def = $userOptionsLookup->getDefaultOptions( null );
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
		$userOptionsLookup = $this->getServiceContainer()->getUserOptionsLookup();
		$defaultOptions = $userOptionsLookup->getDefaultOptions();

		if ( $option && !array_key_exists( $option, $defaultOptions ) ) {
			$this->fatalError( "Invalid user option. Use --list to see valid choices\n" );
		}

		// We list user by user_id from one of the replica DBs
		$dbr = $this->getServiceContainer()->getConnectionProvider()->getReplicaDatabase();

		$result = $dbr->newSelectQueryBuilder()
			->select( [ 'user_id' ] )
			->from( 'user' )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $result as $id ) {
			$user = User::newFromId( $id->user_id );

			// Get the options and update stats
			if ( $option ) {
				$userValue = $userOptionsLookup->getOption( $user, $option );
				if ( $userValue != $defaultOptions[$option] ) {
					$ret[$option][$userValue] = ( $ret[$option][$userValue] ?? 0 ) + 1;
				}
			} else {
				foreach ( $defaultOptions as $name => $defaultValue ) {
					$userValue = $userOptionsLookup->getOption( $user, $name );
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
		$settingWord = $dryRun ? 'Would set' : 'Setting';
		$option = $this->getArg( 0 );
		$fromIsDefault = $this->hasOption( 'old-is-default' );
		$from = $this->getOption( 'old' );
		$to = $this->getOption( 'new' );

		// The fromuserid parameter is inclusive, but iterating is easier with an exclusive
		// range so convert it.
		$fromUserId = (int)$this->getOption( 'fromuserid', 1 ) - 1;
		$toUserId = (int)$this->getOption( 'touserid', 0 ) ?: null;
		$fromAsText = implode( '|', $from );

		if ( !$dryRun ) {
			$forUsers = ( $fromUserId || $toUserId ) ? "some users (ID $fromUserId-$toUserId)" : 'ALL USERS';
			$this->warn(
				<<<WARN
The script is about to change the options for $forUsers in the database.
Users with option <$option> = '$fromAsText' will be made to use '$to'.

Abort with control-c in the next five seconds....
WARN
			);
		}

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		$tempUserConfig = $this->getServiceContainer()->getTempUserConfig();
		$dbr = $this->getReplicaDB();
		$queryBuilderTemplate = $dbr->newSelectQueryBuilder()
			->table( 'user' )
			->leftJoin( 'user_properties', null, [
				'user_id = up_user',
				'up_property' => $option,
			] )
			->fields( [ 'user_id', 'user_name', 'up_value' ] )
			// up_value is unindexed so this can be slow, but should be acceptable in a script
			->where( [ 'up_value' => $fromIsDefault ? null : $from ] )
			// need to order by ID so we can use ID ranges for query continuation
			// also needed for the fromuserid / touserid parameters to work
			->orderBy( 'user_id', SelectQueryBuilder::SORT_ASC )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ );
		if ( $toUserId ) {
			$queryBuilderTemplate->andWhere( $dbr->expr( 'user_id', '<=', $toUserId ) );
		}

		if ( $tempUserConfig->isKnown() ) {
			$queryBuilderTemplate->andWhere(
				$tempUserConfig->getMatchCondition( $dbr, 'user_name', IExpression::NOT_LIKE )
			);
		}

		do {
			$queryBuilder = clone $queryBuilderTemplate;
			$queryBuilder->andWhere( $dbr->expr( 'user_id', '>', $fromUserId ) );
			$result = $queryBuilder->fetchResultSet();
			foreach ( $result as $row ) {
				$fromUserId = (int)$row->user_id;
				$user = UserIdentityValue::newRegistered( $row->user_id, $row->user_name );

				if ( $fromIsDefault ) {
					// $user has the default value for $option; skip if it doesn't match
					// NOTE: This is intentionally a loose comparison. $from is always a string
					// (coming from the command line), but the default value might be of a
					// different type.
					$oldOptionMatchingDefault = null;
					$oldOptionIsDefault = true;
					foreach ( $from as $oldOption ) {
						$oldOptionIsDefault = $oldOption == $userOptionsManager->getDefaultOption( $option, $user );
						if ( $oldOptionIsDefault ) {
							$oldOptionMatchingDefault = $oldOption;
							break;
						}
					}
					if ( !$oldOptionIsDefault ) {
						$this->output(
							"Skipping $option for $row->user_name as the default value for that user is not " .
							"specified in --from\n"
						);
						continue;
					}

					$fromForThisUser = $oldOptionMatchingDefault ?? $fromAsText;
				} else {
					$fromForThisUser = $row->up_value;
				}

				$this->output( "$settingWord $option for $row->user_name from '$fromForThisUser' to '$to'\n" );
				if ( !$dryRun ) {
					$userOptionsManager->setOption( $user, $option, $to );
					$userOptionsManager->saveOptions( $user );
				}
			}
			$this->waitForReplication();
		} while ( $result->numRows() );
	}

	/**
	 * Delete occurrences of the option (with the given value, if provided)
	 */
	private function deleteOptions() {
		$dryRun = $this->hasOption( 'dry' );
		$option = $this->getArg( 0 );
		$fromUserId = (int)$this->getOption( 'fromuserid', 0 );
		$toUserId = (int)$this->getOption( 'touserid', 0 ) ?: null;
		$old = $this->getOption( 'old' );

		if ( !$dryRun ) {
			$forUsers = ( $fromUserId || $toUserId ) ? "some users (ID $fromUserId-$toUserId)" : 'ALL USERS';
			$this->warn( <<<WARN
The script is about to delete '$option' option for $forUsers from user_properties table.
This action is IRREVERSIBLE.

Abort with control-c in the next five seconds....
WARN
			);
		}

		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();

		$rowsNum = 0;
		$rowsInThisBatch = -1;
		$minUserId = $fromUserId;
		while ( $rowsInThisBatch != 0 ) {
			$queryBuilder = $dbr->newSelectQueryBuilder()
				->select( 'up_user' )
				->from( 'user_properties' )
				->where( [ 'up_property' => $option, $dbr->expr( 'up_user', '>', $minUserId ) ] );
			if ( $this->hasOption( 'touserid' ) ) {
				$queryBuilder->andWhere( $dbr->expr( 'up_user', '<', $toUserId ) );
			}
			if ( $this->hasOption( 'old' ) ) {
				$queryBuilder->andWhere( [ 'up_value' => $old ] );
			}
			// need to order by ID so we can use ID ranges for query continuation
			$queryBuilder
				->orderBy( 'up_user', SelectQueryBuilder::SORT_ASC )
				->limit( $this->getBatchSize() );

			$userIds = $queryBuilder->caller( __METHOD__ )->fetchFieldValues();
			if ( $userIds === [] ) {
				// no rows left
				break;
			}

			if ( !$dryRun ) {
				$delete = $dbw->newDeleteQueryBuilder()
					->deleteFrom( 'user_properties' )
					->where( [ 'up_property' => $option, 'up_user' => $userIds ] );
				if ( $this->hasOption( 'old' ) ) {
					$delete->andWhere( [ 'up_value' => $old ] );
				}
				$delete->caller( __METHOD__ )->execute();
				$rowsInThisBatch = $dbw->affectedRows();
			} else {
				$rowsInThisBatch = count( $userIds );
			}

			$this->waitForReplication();
			$rowsNum += $rowsInThisBatch;
			$minUserId = max( $userIds );
		}

		if ( !$dryRun ) {
			$this->output( "Done! Deleted $rowsNum rows.\n" );
		} else {
			$this->output( "Would delete $rowsNum rows.\n" );
		}
	}

	private function deleteDefaults() {
		$dryRun = $this->hasOption( 'dry' );
		$option = $this->getArg( 0 );
		$fromUserId = (int)$this->getOption( 'fromuserid', 0 );
		$toUserId = (int)$this->getOption( 'touserid', 0 ) ?: null;

		if ( $option === null ) {
			$this->fatalError( "Option name is required" );
		}

		if ( $dryRun ) {
			$this->fatalError( "--delete-defaults does not support a dry run." );
		}

		$this->warn( <<<WARN
This script is about to delete all rows in user_properties that match the current
defaults for the user (including conditional defaults).
This action is IRREVERSIBLE.

Abort with control-c in the next five seconds....
WARN
		);

		$dbr = $this->getDB( DB_REPLICA );

		$queryBuilderTemplate = $dbr->newSelectQueryBuilder()
			->select( [ 'user_id', 'user_name', 'up_value' ] )
			->from( 'user_properties' )
			->join( 'user', null, [ 'up_user = user_id' ] )
			->where( [ 'up_property' => $option ] )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ );

		if ( $toUserId !== null ) {
			$queryBuilderTemplate->andWhere( $dbr->expr( 'up_user', '<=', $toUserId ) );
		}

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		do {
			$queryBuilder = clone $queryBuilderTemplate;
			$queryBuilder->andWhere( $dbr->expr( 'up_user', '>', $fromUserId ) );
			$result = $queryBuilder->fetchResultSet();
			foreach ( $result as $row ) {
				$fromUserId = (int)$row->user_id;

				// NOTE: If up_value equals to the default, this will drop the row. Otherwise, it
				// is going to be a no-op.
				$user = UserIdentityValue::newRegistered( $row->user_id, $row->user_name );
				$userOptionsManager->setOption( $user, $option, $row->up_value );
				$userOptionsManager->saveOptions( $user );
			}
			$this->waitForReplication();
		} while ( $result->numRows() );

		$this->output( "Done!\n" );
	}

	/**
	 * The warning message and countdown
	 */
	private function warn( string $message ) {
		if ( $this->hasOption( 'nowarn' ) ) {
			return;
		}

		$this->output( $message );
		$this->countDown( 5 );
	}
}

// @codeCoverageIgnoreStart
$maintClass = UserOptionsMaintenance::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
