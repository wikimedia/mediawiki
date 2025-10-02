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
 * Clear the cache of interwiki prefixes.
 *
 * @ingroup Maintenance
 */
class ClearInterwikiCache extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Clear all interwiki links for all languages from the cache' );
	}

	public function execute() {
		$lookup = $this->getServiceContainer()->getInterwikiLookup();

		$dbr = $this->getReplicaDB();
		$prefixes = $dbr->newSelectQueryBuilder()
			->select( 'iw_prefix' )
			->from( 'interwiki' )
			->caller( __METHOD__ )
			->fetchFieldValues();

		foreach ( $prefixes as $prefix ) {
			$this->output( "...$prefix\n" );
			$lookup->invalidateCache( $prefix );
		}
		$this->output( "done\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ClearInterwikiCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
