<?php
/**
 * Dump an entire language, using the keys from English
 * so we get all the values, not just the customized ones
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup MaintenanceLanguage
 * @todo Make this more useful, right now just dumps content language
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that dumps an entire language, using the keys from English.
 *
 * @ingroup MaintenanceLanguage
 */
class DumpMessages extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump an entire language, using the keys from English' );
	}

	public function execute() {
		$messages = [];
		$localisationCache = $this->getServiceContainer()->getLocalisationCache();
		$localisationMessagesEn = $localisationCache->getItem( 'en', 'messages' );
		foreach ( $localisationMessagesEn as $key => $_ ) {
			$messages[$key] = wfMessage( $key )->text();
		}
		$this->output( "MediaWiki " . MW_VERSION . " language file\n" );
		$this->output( serialize( $messages ) );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DumpMessages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
