<?php

namespace MediaWiki\Cdn;

use CdnCacheUpdate;
use DeferredUpdates;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class ActiveCdnController extends CacheHeaderCdnController {

	/**
	 * @param string[] $resourceUris
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	public function purgeURLs( array $resourceUris ) {
		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( $resourceUris ),
			DeferredUpdates::PRESEND
		);
	}

}
