<?php

use Cache\IntegrationTests\CachePoolTest;

require __DIR__ . '/../../../../../vendor/cache/integration-tests/src/CachePoolTest.php';

/**
 * @covers BagOStuffPsrCache
 *
 * @author Addshore
 */
class BagOStuffPsrCacheTest extends CachePoolTest {

	private $bagOStuff;

	public function setUp() {
		// One HashBagOStuff per used per test (this is a cache after all)...
		$this->bagOStuff = new HashBagOStuff();

		parent::setUp();
	}

	public function createCachePool() {
		return new BagOStuffPsrCache( $this->bagOStuff );
	}

}
