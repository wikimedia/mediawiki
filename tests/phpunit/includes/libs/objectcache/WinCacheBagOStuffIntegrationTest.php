<?php

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\WinCacheBagOStuff
 * @requires extension wincache
 */
class WinCacheBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return $this->getServiceContainer()->getObjectCacheFactory()->getInstance( 'wincache' );
	}
}
