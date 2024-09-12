<?php

use MediaWiki\Parser\Sanitizer;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * A script to remove emails that are invalid from
 * the user_email column of the user table. Emails
 * are validated before users can add them, but
 * this was not always the case so older users may
 * have invalid ones.
 *
 * By default it does a dry-run, pass --commit
 * to actually update the database.
 */
class RemoveInvalidEmails extends Maintenance {

	/** @var bool */
	private $commit = false;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'commit', 'Whether to actually update the database', false, false );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$this->commit = $this->hasOption( 'commit' );
		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();
		$lastId = 0;
		do {
			$rows = $dbr->newSelectQueryBuilder()
				->select( [ 'user_id', 'user_email' ] )
				->from( 'user' )
				->where( [
					$dbr->expr( 'user_id', '>', $lastId ),
					$dbr->expr( 'user_email', '!=', '' ),
					'user_email_authenticated' => null,
				] )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )->fetchResultSet();
			$count = $rows->numRows();
			$badIds = [];
			foreach ( $rows as $row ) {
				if ( !Sanitizer::validateEmail( trim( $row->user_email ) ) ) {
					$this->output( "Found bad email: {$row->user_email} for user #{$row->user_id}\n" );
					$badIds[] = $row->user_id;
				}
				if ( $row->user_id > $lastId ) {
					$lastId = $row->user_id;
				}
			}

			if ( $badIds ) {
				$badCount = count( $badIds );
				if ( $this->commit ) {
					$this->output( "Removing $badCount emails from the database.\n" );
					$dbw->newUpdateQueryBuilder()
						->update( 'user' )
						->set( [ 'user_email' => '' ] )
						->where( [ 'user_id' => $badIds ] )
						->caller( __METHOD__ )
						->execute();
					foreach ( $badIds as $badId ) {
						User::newFromId( $badId )->invalidateCache();
					}
					$this->waitForReplication();
				} else {
					$this->output( "Would have removed $badCount emails from the database.\n" );

				}
			}
		} while ( $count !== 0 );
		$this->output( "Done.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = RemoveInvalidEmails::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
