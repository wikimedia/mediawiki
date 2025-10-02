<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Remove expired blocks from the block and ipblocks_restrictions tables
 *
 * @since 1.35
 * @ingroup Maintenance
 * @author DannyS712
 */
class PurgeExpiredBlocks extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove expired blocks.' );
	}

	public function execute() {
		$this->output( "Purging expired blocks...\n" );

		$this->getServiceContainer()->getDatabaseBlockStore()->purgeExpiredBlocks();

		$this->output( "Done purging expired blocks.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeExpiredBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
