<?php

use Cache\IntegrationTests\CachePoolTest;

require __DIR__ . '/../../../../../vendor/cache/integration-tests/src/CachePoolTest.php';

/**
 * @covers WANObjectPsrCache
 *
 * @author Addshore
 */
class WANObjectPsrCacheTest extends CachePoolTest {

	private $objectCache;

	public function setUp() {
		$this->objectCache = new WANObjectCache(
			[
				'cache' => new HashBagOStuff(),
				'pool' => __METHOD__,
			]
		);

		parent::setUp();
	}

	public function createCachePool() {
		return new BagOStuffPsrCache( $this->objectCache );
	}

}
