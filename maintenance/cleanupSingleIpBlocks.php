<?php

ini_set( 'display_errors', 1 );

require __DIR__ . '/Maintenance.php';

/**
 * T51504: Script to sets ipb_range_start and ipb_range_end to empty for single IP blocks
 * @ingroup Maintenance
 */
class CleanupSingleIpBlocks extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = 'Sets ipb_range_start and ipb_range_end to empty for single IP blocks';
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );
		$lbFactory = \MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$total = 0;

		do {
			$dbw->update(
				'ipblocks',
				[
					'ipb_range_start' => '',
					'ipb_range_end' => '',
				],
				[ 'ipb_range_start = ipb_range_end' ],
				__METHOD__,
				[ 'LIMIT' => 500 ]
			);

			$affectedRows = $dbw->affectedRows();

			$total += $affectedRows;

			// Wait for replication after each batch update
			$lbFactory->waitForReplication();
		} while ( $affectedRows > 0 );

		$this->output( "Done, $total rows affected.\n" );
	}
}

$maintClass = CleanupSingleIpBlocks::class;
require RUN_MAINTENANCE_IF_MAIN;
