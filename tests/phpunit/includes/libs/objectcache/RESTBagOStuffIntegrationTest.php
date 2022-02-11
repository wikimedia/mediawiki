<?php

/**
 * @group BagOStuff
 * @covers RESTBagOStuff
 */
class RESTBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( 'EnableRemoteBagOStuffTests' ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return $this->getCacheByClass( RESTBagOStuff::class );
	}
}
