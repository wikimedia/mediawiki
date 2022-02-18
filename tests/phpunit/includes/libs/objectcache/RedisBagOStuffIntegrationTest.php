<?php

/**
 * @group BagOStuff
 * @covers RedisBagOStuff
 * @requires extension redis
 */
class RedisBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( 'EnableRemoteBagOStuffTests' ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return $this->getCacheByClass( RedisBagOStuff::class );
	}
}
