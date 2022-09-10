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

	public function testModtoken() {
		$now = self::TEST_TIME;
		$this->cache->setMockTime( $now );
		$this->cache->set( 'test', 'a' );
		$this->assertSame( 'a', $this->cache->get( 'test' ) );

		$now--;
		// Modtoken comparison makes this a no-op
		$this->cache->set( 'test', 'b' );
		$this->assertSame( 'a', $this->cache->get( 'test' ) );

		$now += 2;
		$this->cache->set( 'test', 'c' );
		$this->assertSame( 'c', $this->cache->get( 'test' ) );
	}
}
