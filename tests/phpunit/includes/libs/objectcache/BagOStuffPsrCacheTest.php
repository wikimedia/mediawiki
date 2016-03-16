<?php

use Cache\IntegrationTests\CachePoolTest;

/**
 * @covers BagOStuffPsrCache
 *
 * @author Addshore
 */
class BagOStuffPsrCacheTest extends CachePoolTest {

	private $bagOStuff;

	public function setUp() {
		// Expiration has not been implemented
		$this->skippedTests['testExpiration'] = 'Expiration is not implemented';
		$this->skippedTests['testDeferredExpired'] = 'Expiration is not implemented';
		$this->skippedTests['testSaveExpired'] = 'Expiration is not implemented';

		// One HashBagOStuff per used per test (this is a cache after all)...
		$this->bagOStuff = new HashBagOStuff();

		parent::setUp();
	}

	public function createCachePool() {
		return new BagOStuffPsrCache( $this->bagOStuff );
	}

	/**
	 * Data provider for invalid keys.
	 *
	 * @return array
	 */
	public static function invalidKeys()
	{
		return [
			[true],
			[false],
			[null],
			[2],
			[2.5],
// These are valid?
//			['{str'],
//			['rand{'],
//			['rand{str'],
//			['rand}str'],
//			['rand(str'],
//			['rand)str'],
//			['rand/str'],
//			['rand\\str'],
//			['rand@str'],
//			['rand:str'],
			[new \stdClass()],
			[['array']],
		];
	}

}
