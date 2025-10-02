<?php
/**
 * Fix the user_registration field.
 * In particular, for values which are NULL, set them to the date of the first edit
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that fixes the user_registration field.
 *
 * @ingroup Maintenance
 */
class FixUserRegistration extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Fix the user_registration field' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dbw = $this->getPrimaryDB();

		$lastId = 0;
		do {
			// Get user IDs which need fixing
			$res = $dbw->newSelectQueryBuilder()
				->select( 'user_id' )
				->from( 'user' )
				->where( [ $dbw->expr( 'user_id', '>', $lastId ), 'user_registration' => null ] )
				->orderBy( 'user_id' )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )->fetchResultSet();

			foreach ( $res as $row ) {
				$id = $row->user_id;
				$lastId = $id;

				// Get first edit time
				$actorStore = $this->getServiceContainer()->getActorStore();
				$userIdentity = $actorStore->getUserIdentityByUserId( $id );
				if ( !$userIdentity ) {
					continue;
				}

				$timestamp = $dbw->newSelectQueryBuilder()
					->select( 'MIN(rev_timestamp)' )
					->from( 'revision' )
					->where( [ 'rev_actor' => $userIdentity->getId() ] )
					->caller( __METHOD__ )->fetchField();

				// Update
				if ( $timestamp !== null ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'user' )
						->set( [ 'user_registration' => $timestamp ] )
						->where( [ 'user_id' => $id ] )
						->caller( __METHOD__ )->execute();

					$user = User::newFromId( $id );
					$user->invalidateCache();
					$this->output( "Set registration for #$id to $timestamp\n" );
				} else {
					$this->output( "Could not find registration for #$id NULL\n" );
				}
			}
			$this->output( "Waiting for replica DBs..." );
			$this->waitForReplication();
			$this->output( " done.\n" );
		} while ( $res->numRows() >= $this->getBatchSize() );
	}
}

// @codeCoverageIgnoreStart
$maintClass = FixUserRegistration::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
