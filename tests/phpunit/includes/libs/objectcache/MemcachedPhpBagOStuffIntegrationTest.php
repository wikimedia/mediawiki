<?php

use MediaWiki\MainConfigNames;

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\MemcachedPhpBagOStuff
 */
class MemcachedPhpBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( MainConfigNames::EnableRemoteBagOStuffTests ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return $this->getServiceContainer()->getObjectCacheFactory()->getInstance( 'memcached-php' );
	}
}
