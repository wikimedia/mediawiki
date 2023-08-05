<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;

/**
 * @covers ObjectCache
 * @group BagOStuff
 * @group Database
 */
class ObjectCacheTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		// Parent calls ObjectCache::clear() among other things
		parent::setUp();

		$this->setCacheConfig();
		$this->setMainCache( CACHE_NONE );
		$this->overrideConfigValues( [
			MainConfigNames::MessageCacheType => CACHE_NONE,
			MainConfigNames::ParserCacheType => CACHE_NONE,
		] );
	}

	protected function tearDown(): void {
		ObjectCache::$localServerCacheClass = null;
	}

	private function setCacheConfig( $arr = [] ) {
		$defaults = [
			CACHE_NONE => [ 'class' => EmptyBagOStuff::class ],
			CACHE_DB => [ 'class' => SqlBagOStuff::class ],
			CACHE_ANYTHING => [ 'factory' => 'ObjectCache::newAnything' ],
			CACHE_ACCEL => [ 'factory' => 'ObjectCache::getLocalServerInstance' ],
			'hash' => [ 'class' => HashBagOStuff::class ],
		];
		$this->overrideConfigValue( MainConfigNames::ObjectCaches, $arr + $defaults );
		// Mock ACCEL with 'hash' as being installed.
		// This makes tests deterministic regardless of APC.
		ObjectCache::$localServerCacheClass = 'HashBagOStuff';
	}

	public function testNewAnythingNothing() {
		$this->assertInstanceOf(
			SqlBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'No available types. Fallback to DB'
		);
	}

	public function testNewAnythingHash() {
		$this->setMainCache( CACHE_HASH );

		$this->assertInstanceOf(
			HashBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Use an available type (hash)'
		);
	}

	public function testNewAnythingAccel() {
		$this->setMainCache( CACHE_ACCEL );

		$this->assertInstanceOf(
			HashBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Use an available type (CACHE_ACCEL)'
		);
	}

	public function testNewAnythingNoAccel() {
		// Mock APC not being installed (T160519, T147161)
		ObjectCache::$localServerCacheClass = EmptyBagOStuff::class;
		$this->setMainCache( CACHE_ACCEL );

		$this->assertInstanceOf(
			SqlBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Fallback to DB if available types fall back to Empty'
		);
	}

	public function testNewAnythingNoAccelNoDb() {
		$this->setCacheConfig( [
			// Mock APC not being installed (T160519, T147161)
			CACHE_ACCEL => [ 'class' => EmptyBagOStuff::class ]
		] );
		$this->setMainCache( CACHE_ACCEL );

		$this->getServiceContainer()->disableStorage();

		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Fallback to none if available types and DB are unavailable'
		);
	}

	public function testNewAnythingNothingNoDb() {
		$this->getServiceContainer()->disableStorage();

		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'No available types or DB. Fallback to none.'
		);
	}

	public static function provideIsDatabaseId() {
		return [
			[ CACHE_DB, CACHE_NONE, true ],
			[ 'db-replicated', CACHE_NONE, true ],
			[ CACHE_ANYTHING, CACHE_DB, true ],
			[ CACHE_ANYTHING, 'hash', false ],
			[ CACHE_ANYTHING, CACHE_ANYTHING, true ]
		];
	}

	/**
	 * @dataProvider provideIsDatabaseId
	 * @param string|int $id
	 * @param string|int $mainCacheType
	 * @param bool $expected
	 */
	public function testIsDatabaseId( $id, $mainCacheType, $expected ) {
		$this->setCacheConfig( [
			'db-replicated' => MainConfigSchema::ObjectCaches['default']['db-replicated']
		] );
		$this->overrideConfigValues( [
			MainConfigNames::MainCacheType => $mainCacheType
		] );
		$this->assertSame( $expected, ObjectCache::isDatabaseId( $id ) );
	}
}
