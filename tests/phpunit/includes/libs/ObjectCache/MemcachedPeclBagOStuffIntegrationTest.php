<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\MemcachedPeclBagOStuff
 * @requires extension memcached
 */
class MemcachedPeclBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( MainConfigNames::EnableRemoteBagOStuffTests ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return MediaWikiServices::getInstance()->getObjectCacheFactory()->getInstance( 'memcached-pecl' );
	}
}
