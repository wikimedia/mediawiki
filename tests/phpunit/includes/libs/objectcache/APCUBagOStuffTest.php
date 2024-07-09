<?php

use Wikimedia\ObjectCache\APCUBagOStuff;

/**
 * @group BagOStuff
 * @covers \Wikimedia\ObjectCache\APCUBagOStuff
 * @requires extension apcu
 */
class APCUBagOStuffTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		// Make sure the APCu methods actually store anything
		if ( PHP_SAPI === 'cli' && !ini_get( 'apc.enable_cli' ) ) {
			$this->markTestSkipped( 'apc.enable_cli=1 is required to run this test.' );
		}
		return new APCUBagOStuff( [] );
	}
}
