<?php

/**
 * @group BagOStuff
 * @covers \SqlBagOStuff
 */
class SqlBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_DB );
	}
}
