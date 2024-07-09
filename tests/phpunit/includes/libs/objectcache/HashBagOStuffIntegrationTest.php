<?php

use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\HashBagOStuff
 */
class HashBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return new HashBagOStuff();
	}
}
