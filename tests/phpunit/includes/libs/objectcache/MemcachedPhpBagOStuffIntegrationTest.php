<?php

/**
 * @group BagOStuff
 * @covers \MemcachedPhpBagOStuff
 */
class MemcachedPhpBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( 'EnableRemoteBagOStuffTests' ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return $this->getServiceContainer()->getObjectCacheFactory()->getInstance( 'memcached-php' );
	}
}
