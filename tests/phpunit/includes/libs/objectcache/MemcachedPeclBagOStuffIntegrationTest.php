<?php

use MediaWiki\MediaWikiServices;

/**
 * @group BagOStuff
 * @covers \MemcachedPeclBagOStuff
 * @requires extension memcached
 */
class MemcachedPeclBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( 'EnableRemoteBagOStuffTests' ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return MediaWikiServices::getInstance()->getObjectCacheFactory()->getInstance( 'memcached-pecl' );
	}
}
