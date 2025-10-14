<?php

/**
 * @group BagOStuff
 * @group Database
 * @covers \SqlBagOStuff
 */
class SqlBagOStuffMultiPrimaryIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return $this->getServiceContainer()->getObjectCacheFactory()->newFromParams( [
			'class' => SqlBagOStuff::class,
			'loggroup' => 'SQLBagOStuff',
			'purgePeriod' => 0,
			'reportDupes' => false
		] );
	}
}
