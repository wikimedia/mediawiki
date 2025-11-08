<?php

use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\RESTBagOStuff;

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\RESTBagOStuff
 */
class RESTBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( !$this->getConfVar( MainConfigNames::EnableRemoteBagOStuffTests ) ) {
			$this->markTestSkipped( '$wgEnableRemoteBagOStuffTests is false' );
		}
		return $this->getCacheByClass( RESTBagOStuff::class );
	}
}
