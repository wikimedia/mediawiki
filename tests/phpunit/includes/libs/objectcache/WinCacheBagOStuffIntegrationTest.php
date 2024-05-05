<?php

/**
 * @group BagOStuff
 * @covers \WinCacheBagOStuff
 * @requires extension wincache
 */
class WinCacheBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return $this->getServiceContainer()->getObjectCacheFactory()->getInstance( 'wincache' );
	}
}
