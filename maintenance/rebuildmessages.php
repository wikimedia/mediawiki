<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that purges cache used by MessageCache.
 *
 * @ingroup Maintenance
 */
class RebuildMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Purge the MessageCache for all interface languages.' );
	}

	public function execute() {
		$this->output( "Purging message cache for all languages on this wiki..." );
		$messageCache = $this->getServiceContainer()->getMessageCache();
		$messageCache->clear();
		$this->output( "Done\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = RebuildMessages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
