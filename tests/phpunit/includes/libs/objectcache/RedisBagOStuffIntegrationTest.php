<?php

use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\RedisBagOStuff;

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\RedisBagOStuff
 * @requires extension redis
 */
class RedisBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( MainConfigNames::EnableRemoteBagOStuffTests ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return $this->getCacheByClass( RedisBagOStuff::class );
	}
}
