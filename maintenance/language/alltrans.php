<?php
/**
 * Get all the translations messages, as defined in the English language file.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup MaintenanceLanguage
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that gets all messages as defined by the
 * English language file.
 *
 * @ingroup MaintenanceLanguage
 */
class AllTrans extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Get all messages as defined by the English language file' );
	}

	public function execute() {
		$localisationCache = $this->getServiceContainer()->getLocalisationCache();
		$englishMessages = $localisationCache->getItem( 'en', 'messages' );
		foreach ( $englishMessages as $key => $_ ) {
			$this->output( "$key\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = AllTrans::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
