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
		$serverInfoHolder = TestingAccessWrapper::newFromObject( $lb )->serverInfoHolder;
		return ObjectCache::newFromParams( [
			'class' => SqlBagOStuff::class,
			'servers' => [ $serverInfoHolder->getServerInfo( 0 ) ]
		] );
	}
}
