<?php

class ObjectCacheTest extends MediaWikiTestCase {

	protected function setUp() {
		// Parent calls ObjectCache::clear() among other things
		parent::setUp();

		$this->setCacheConfig();
		$this->setMwGlobals( [
			'wgMainCacheType' => CACHE_NONE,
			'wgMessageCacheType' => CACHE_NONE,
			'wgParserCacheType' => CACHE_NONE,
		] );
	}

	private function setCacheConfig( $arr = [] ) {
		$defaults = [
			CACHE_NONE => [ 'class' => 'EmptyBagOStuff' ],
			CACHE_DB => [ 'class' => 'SqlBagOStuff' ],
			CACHE_ANYTHING => [ 'factory' => 'ObjectCache::newAnything' ],
			// Mock ACCEL with 'hash' as being installed.
			// This makes tests deterministic regardless of APC.
			CACHE_ACCEL => [ 'class' => 'HashBagOStuff' ],
			'hash' => [ 'class' => 'HashBagOStuff' ],
		];
		$this->setMwGlobals( 'wgObjectCaches', $arr + $defaults );
	}

	/** @covers ObjectCache::newAnything */
	public function testNewAnythingNothing() {
		$this->assertInstanceOf(
			SqlBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'No available types. Fallback to DB'
		);
	}

	/** @covers ObjectCache::newAnything */
	public function testNewAnythingHash() {
		$this->setMwGlobals( [
			'wgMainCacheType' => 'hash'
		] );

		$this->assertInstanceOf(
			HashBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Use an available type (hash)'
		);
	}

	/** @covers ObjectCache::newAnything */
	public function testNewAnythingAccel() {
		$this->setMwGlobals( [
			'wgMainCacheType' => CACHE_ACCEL
		] );

		$this->assertInstanceOf(
			HashBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Use an available type (CACHE_ACCEL)'
		);
	}

	/** @covers ObjectCache::newAnything */
	public function testNewAnythingNoAccel() {
		$this->setMwGlobals( [
			'wgMainCacheType' => CACHE_ACCEL
		] );

		$this->setCacheConfig( [
			// Mock APC not being installed (T160519, T147161)
			CACHE_ACCEL => [ 'class' => 'EmptyBagOStuff' ]
		] );

		$this->assertInstanceOf(
			SqlBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Fallback to DB if available types fall back to Empty'
		);
	}

	/** @covers ObjectCache::newAnything */
	public function testNewAnythingNoAccelNoDb() {
		$this->overrideMwServices(); // Ensures restore on tear down
		MediaWiki\MediaWikiServices::disableStorageBackend();

		$this->setMwGlobals( [
			'wgMainCacheType' => CACHE_ACCEL
		] );

		$this->setCacheConfig( [
			// Mock APC not being installed (T160519, T147161)
			CACHE_ACCEL => [ 'class' => 'EmptyBagOStuff' ]
		] );

		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'Fallback to none if available types and DB are unavailable'
		);
	}

	/** @covers ObjectCache::newAnything */
	public function testNewAnythingNothingNoDb() {
		$this->overrideMwServices();
		MediaWiki\MediaWikiServices::disableStorageBackend();

		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			ObjectCache::newAnything( [] ),
			'No available types or DB. Fallback to none.'
		);
	}
}
