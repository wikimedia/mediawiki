<?php

/**
 * @group BagOStuff
 * @group Database
 * @covers SqlBagOStuff
 */
class SqlBagOStuffMultiPrimaryIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return ObjectCache::newFromParams( [
			'class' => SqlBagOStuff::class,
			'loggroup' => 'SQLBagOStuff',
			'multiPrimaryMode' => true,
			'purgePeriod' => 0,
			'reportDupes' => false
		] );
	}
}
