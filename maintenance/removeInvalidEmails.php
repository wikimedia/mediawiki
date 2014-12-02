<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * A script to remove emails that are invalid from
 * the user_email column of the user table. Emails
 * are validated before users can add them, but
 * this was not always the case so older users may
 * have invalid ones.
 */
class RemoveInvalidEmails extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->setBatchSize( 500 );
	}
	public function execute() {
		$dbr = $this->getDB( DB_SLAVE );
		$dbw = $this->getDB( DB_MASTER );
		do {
			$rows = $dbr->select(
				'user',
				array( 'user_id', 'user_email' ),
				array( 'user_email != ""', 'user_email_authenticated IS NULL' ),
				__METHOD__,
				array( 'LIMIT' => $this->mBatchSize )
			);
			$count = $rows->numRows();
			$badIds = array();
			foreach ( $rows as $row ) {
				if ( !Sanitizer::validateEmail( $row->user_email ) ) {
					$badIds[] = $row->user_id;
				}
			}

			if ( $badIds ) {
				$badCount = count( $badIds );
				$this->output( "Removing $badCount emails from the database.\n" );
				$dbw->update(
					'user',
					array( 'user_email' => '' ),
					array( 'user_id' => $badIds ),
					__METHOD__
				);
				foreach ( $badIds as $badId ) {
					User::newFromId( $badId )->invalidateCache();
				}
				wfWaitForSlaves();
			}
		} while ( $count !== 0 );
		$this->output( "Done.\n" );
	}
}

$maintClass = 'RemoveInvalidEmails';
require_once RUN_MAINTENANCE_IF_MAIN;