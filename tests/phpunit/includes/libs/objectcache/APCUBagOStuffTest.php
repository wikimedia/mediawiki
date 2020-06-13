<?php

/**
 * @group BagOStuff
 * @covers APCUBagOStuff
 */
class APCUBagOStuffTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		if ( function_exists( 'apcu_fetch' ) ) {
			// Make sure the APCu methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return new APCUBagOStuff( [] );
			}

			$this->markTestSkipped( 'apc.enable_cli=1 is required to run ' . __CLASS__ );
		}

		$this->markTestSkipped( 'apcu is required to run ' . __CLASS__ );
	}
}
