<?php

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that cleans up cases where rev_page and revactor_page
 * became desynced, e.g. from T232464.
 *
 * @ingroup Maintenance
 * @since 1.34
 */
class CleanupRevActorPage extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Resyncs revactor_page with rev_page when they differ, e.g. from T232464.'
		);
		$this->setBatchSize( 1000 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_MASTER );
		$max = $dbw->selectField( 'revision', 'MAX(rev_id)', '', __METHOD__ );
		$batchSize = $this->mBatchSize;

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$this->output( "Resyncing revactor_page with rev_page...\n" );

		$count = 0;
		for ( $start = 1; $start <= $max; $start += $batchSize ) {
			$end = $start + $batchSize - 1;
			$this->output( "... rev_id $start - $end, $count changed\n" );

			// Fetch the rows needing update
			$res = $dbw->select(
				[ 'revision', 'revision_actor_temp' ],
				[ 'rev_id', 'rev_page' ],
				[
					'rev_page != revactor_page',
					"rev_id >= $start",
					"rev_id <= $end",
				],
				__METHOD__,
				[],
				[ 'revision_actor_temp' => [ 'JOIN', 'rev_id = revactor_rev' ] ]
			);

			if ( !$res->numRows() ) {
				continue;
			}

			// Update the existing rows
			foreach ( $res as $row ) {
				$dbw->update(
					'revision_actor_temp',
					[ 'revactor_page' => $row->rev_page ],
					[ 'revactor_rev' => $row->rev_id ],
					__METHOD__
				);
				$count += $dbw->affectedRows();
			}

			$lbFactory->waitForReplication();
		}

		$this->output( "Completed resync, $count row(s) updated\n" );

		return true;
	}
}

$maintClass = CleanupRevActorPage::class;
require_once RUN_MAINTENANCE_IF_MAIN;
