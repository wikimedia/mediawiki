<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * Purge the MessageBlobStore cache.
 *
 * This script exists for use with the `--skip-message-purge` option of
 * rebuildLocalisationCache.php.
 *
 * @since 1.36
 */
class PurgeMessageBlobStore extends Maintenance {
	public function execute() {
		$blobStore = $this->getServiceContainer()->getResourceLoader()->getMessageBlobStore();
		$blobStore->clear();
	}
}

$maintClass = PurgeMessageBlobStore::class;
require_once RUN_MAINTENANCE_IF_MAIN;
