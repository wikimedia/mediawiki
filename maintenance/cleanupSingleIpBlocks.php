<?php

ini_set( 'display_errors', 1 );

require_once __DIR__ . '/Maintenance.php';

/**
 * T51504: Script to sets ipb_range_start and ipb_range_end to empty for single IP blocks
 * @ingroup Maintenance
 */
class CleanupSingleIpBlocks extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Sets ipb_range_start and ipb_range_end to empty for single IP blocks' );
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_MASTER );
		$lbFactory = \MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$total = 0;
		$lastBlockId = 0;

		$this->output( "Cleaning up entries for single IP blocks" );

		do {
			$blockIds = $dbw->selectFieldValues(
				'ipblocks',
				'ipb_id',
				[
					'ipb_range_start = ipb_range_end',
					"ipb_id > $lastBlockId"
				],
				__METHOD__,
				[ 'ORDER BY' => 'ipb_id ASC', 'LIMIT' => 500 ]
			);

			if ( !empty( $blockIds ) ) {
				$dbw->update(
					'ipblocks',
					[
						'ipb_range_start' => '',
						'ipb_range_end' => '',
					],
					[ 'ipb_id' => $blockIds ],
					__METHOD__
				);

				$lastBlockId = array_pop( $blockIds );

				$total += $dbw->affectedRows();

				// Wait for replication after each batch update
				$lbFactory->waitForReplication();

				$this->output( '.' );
			}

		} while ( !empty( $blockIds ) );

		$this->output( "\nDone, $total rows affected.\n" );

		return true;
	}

	protected function getUpdateKey() {
		return 'cleanup single IP blocks';
	}
}

$maintClass = CleanupSingleIpBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
