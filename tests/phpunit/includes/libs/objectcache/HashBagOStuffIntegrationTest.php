<?php

/**
 * @group BagOStuff
 * @covers HashBagOStuff
 */
class HashBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return new HashBagOStuff();
	}
}
