<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group BagOStuff
 * @group Database
 * @covers SqlBagOStuff
 */
class SqlBagOStuffServerArrayTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		// Extract server config from main load balancer
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$servers = TestingAccessWrapper::newFromObject( $lb )->servers;
		return ObjectCache::newFromParams( [
			'class' => SqlBagOStuff::class,
			'servers' => [ $servers[0] ]
		] );
	}
}
