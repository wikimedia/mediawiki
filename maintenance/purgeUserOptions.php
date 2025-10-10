<?php
/**
 * Script to remove user options of users who
 * haven't logged in for certain time.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Maintenance
 */
class PurgeUserOptions extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Pass through all users and purge options for
		users that have not logged in for certain time and have below certain edits' );

		$this->addOption( 'login-age', 'How many years since last login. Default: 5', false, true );
		$this->addOption( 'max-editcount', 'How many edit or more to skip deletion. Default: 100', false, true );
		$this->addOption( 'dry', 'Do not carry out the deletion' );
		$this->addArg( 'option name', 'Only delete this option', false );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$option = $this->getArg( 0 );
		$dbr = $this->getServiceContainer()->getConnectionProvider()->getReplicaDatabase();
		$dbw = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase();
		$maxUserId = $dbr->newSelectQueryBuilder()
			->select( 'MAX(user_id)' )
			->from( 'user' )
			->caller( __METHOD__ )
			->fetchField();
		$cleaned = 0;
		$duration = 'P' . (int)$this->getOption( 'login-age', 5 ) . 'Y';
		$cutoff = $dbr->timestamp( ( new DateTime() )->sub( new DateInterval( $duration ) )->getTimestamp() );
		for ( $min = 0; $min <= $maxUserId; $min += $this->getBatchSize() ) {
			$userIdsToClean = $dbr->newSelectQueryBuilder()
				->select( 'user_id' )
				->from( 'user' )
				->where( $dbr->expr( 'user_id', '>=', $min ) )
				->where( $dbr->expr( 'user_id', '<', $min + $this->getBatchSize() ) )
				->where( $dbr->expr( 'user_touched', '<', $cutoff ) )
				->where( $dbr->expr( 'user_editcount', '<', (int)$this->getOption( 'max-editcount', 100 ) ) )
				->caller( __METHOD__ )->fetchFieldValues();
			if ( $userIdsToClean === [] ) {
				continue;
			}

			if ( $this->getOption( 'dry' ) ) {
				$this->output( 'The script will clean up user_properties for the following users ' .
					json_encode( $userIdsToClean ) . "\n" );
				continue;
			}

			$deleteQueryBuilder = $dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user_properties' )
				->where( [ 'up_user' => $userIdsToClean ] );
			if ( $option ) {
				$deleteQueryBuilder->where( [ 'up_property' => $option ] );
			}
			$deleteQueryBuilder->caller( __METHOD__ )->execute();
			$this->waitForReplication();

			$cleaned += count( $userIdsToClean );
			$this->output( 'Cleaned up user_properties for users up to ' . ( $min + $this->getBatchSize() ) .
				' and ' . $cleaned . " users have been cleared.\n" );

		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeUserOptions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
