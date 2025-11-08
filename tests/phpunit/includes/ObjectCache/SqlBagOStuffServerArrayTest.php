<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group BagOStuff
 * @group Database
 * @covers \SqlBagOStuff
 */
class SqlBagOStuffServerArrayTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		// Extract server config from main load balancer
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$serverInfo = TestingAccessWrapper::newFromObject( $lb )->serverInfo;
		return $this->getServiceContainer()->getObjectCacheFactory()->newFromParams( [
			'class' => SqlBagOStuff::class,
			'servers' => [ $serverInfo->getServerInfo( 0 ) ]
		] );
	}
}
