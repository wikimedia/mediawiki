<?php

/**
 * @group BagOStuff
 * @covers SqlBagOStuff
 */
class SqlBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return ObjectCache::getInstance( CACHE_DB );
	}
}
