<?php

/**
 * @group BagOStuff
 * @covers BagOStuff
 */
class BagOStuffTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( $this->getCliArg( 'use-bagostuff' ) !== null ) {
			global $wgObjectCaches;

			$id = $this->getCliArg( 'use-bagostuff' );
			$cache = ObjectCache::newFromParams( $wgObjectCaches[$id] );
		} else {
			// no type defined - use simple hash
			$cache = new HashBagOStuff;
		}

		return $cache;
	}
}
