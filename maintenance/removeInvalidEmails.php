<?php

require_once __DIR__ . '/Maintenance.php';

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

	private $commit = false;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'commit', 'Whether to actually update the database', false, false );
		$this->setBatchSize( 500 );
	}
	public function execute() {
		$this->commit = $this->hasOption( 'commit' );
		$dbr = $this->getDB( DB_SLAVE );
		$dbw = $this->getDB( DB_MASTER );
		$lastId = 0;
		do {
			$rows = $dbr->select(
				'user',
				[ 'user_id', 'user_email' ],
				[
					'user_id > ' . $dbr->addQuotes( $lastId ),
					'user_email != ""',
					'user_email_authenticated IS NULL'
				],
				__METHOD__,
				[ 'LIMIT' => $this->mBatchSize ]
			);
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
					$dbw->update(
						'user',
						[ 'user_email' => '' ],
						[ 'user_id' => $badIds ],
						__METHOD__
					);
					foreach ( $badIds as $badId ) {
						User::newFromId( $badId )->invalidateCache();
					}
					wfWaitForSlaves();
				} else {
					$this->output( "Would have removed $badCount emails from the database.\n" );

				}
			}
		} while ( $count !== 0 );
		$this->output( "Done.\n" );
	}
}

$maintClass = 'RemoveInvalidEmails';
require_once RUN_MAINTENANCE_IF_MAIN;
