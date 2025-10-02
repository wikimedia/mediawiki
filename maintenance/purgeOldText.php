<?php
/**
 * Purge old text records from the database
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that purges old text records from the database.
 *
 * @ingroup Maintenance
 */
class PurgeOldText extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Purge old text records from the database' );
		$this->addOption( 'purge', 'Performs the deletion' );
	}

	public function execute() {
		$this->purgeRedundantText( $this->hasOption( 'purge' ) );
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeOldText::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
