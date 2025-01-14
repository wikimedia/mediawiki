<?php

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\ResourceLoader\MessageBlobStore;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Purge the MessageBlobStore cache.
 *
 * This script exists for use with the `--skip-message-purge` option of
 * rebuildLocalisationCache.php.
 *
 * @ingroup ResourceLoader
 * @since 1.36
 */
class PurgeMessageBlobStore extends Maintenance {
	public function execute() {
		// T379722: This script uses the MessageBlobStore static method for the
		// cache clearance to avoid depending on the Database via ResourceLoader
		// service wiring when obtaining MessageBlobStore object.
		$cache = $this->getServiceContainer()->getMainWANObjectCache();
		MessageBlobStore::clearGlobalCacheEntry( $cache );
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeMessageBlobStore::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
