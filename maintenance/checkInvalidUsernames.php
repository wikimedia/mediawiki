<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * Outputs a list of usernames in your database that
 * are no longer valid.
 */
class CheckInvalidUsernames extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$dbr = $this->getDB( DB_SLAVE );
		$lastId = 0;
		do {
			$rows = $dbr->select(
				'user',
				array( 'user_id', 'user_name' ),
				array(
					'user_id > ' . $dbr->addQuotes( $lastId ),
				),
				__METHOD__,
				array( 'LIMIT' => $this->mBatchSize )
			);
			$count = $rows->numRows();
			foreach ( $rows as $row ) {

				if ( User::getCanonicalName( $row->user_name ) === false ) {
					$this->output( "Found bad name: {$row->user_name} for user #{$row->user_id}\n" );
				}
				if ( $row->user_id > $lastId ) {
					$lastId = $row->user_id;
				}
			}

		} while ( $count !== 0 );
		$this->output( "Done.\n" );
	}
}

$maintClass = 'CheckInvalidUsernames';
require_once RUN_MAINTENANCE_IF_MAIN;
