<?php

use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \ObjectCacheFactory
 * @group BagOStuff
 * @group Database
 */
class ObjectCacheFactoryIntegrationTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		$this->setCacheConfig();
		$this->setMainCache( CACHE_NONE );
		$this->overrideConfigValues( [
			MainConfigNames::MessageCacheType => CACHE_NONE,
			MainConfigNames::ParserCacheType => CACHE_NONE,
		] );

		// Mock ACCEL with 'hash' as being installed.
		// This makes tests deterministic regardless of whether APCu is installed.
		ObjectCacheFactory::$localServerCacheClass = 'HashBagOStuff';
	}

	protected function tearDown(): void {
		ObjectCacheFactory::$localServerCacheClass = null;
	}

	private function setCacheConfig( $arr = [] ) {
		$defaults = [
			CACHE_NONE => [ 'class' => EmptyBagOStuff::class ],
			CACHE_DB => [ 'class' => SqlBagOStuff::class ],
			'hash' => [ 'class' => HashBagOStuff::class ],
			CACHE_ANYTHING => [ 'class' => HashBagOStuff::class ],
		];
		$this->overrideConfigValue( MainConfigNames::ObjectCaches, $arr + $defaults );
	}

	public function testNewAnythingNothing() {
		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertInstanceOf(
			SqlBagOStuff::class,
			$ocf->getInstance( $ocf->getAnythingId() ),
			'No available types. Fallback to DB'
		);
	}

	public function testNewAnythingHash() {
		$this->setMainCache( CACHE_HASH );

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertInstanceOf(
			HashBagOStuff::class,
			$ocf->getInstance( $ocf->getAnythingId() ),
			'Use an available type (hash)'
		);
	}

	public function testNewAnythingAccel() {
		$this->setMainCache( CACHE_ACCEL );

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertInstanceOf(
			HashBagOStuff::class,
			$ocf->getInstance( $ocf->getAnythingId() ),
			'Use an available type (CACHE_ACCEL)'
		);
	}

	public function testNewAnythingNoAccel() {
		// Mock APC not being installed (T160519, T147161)
		ObjectCacheFactory::$localServerCacheClass = EmptyBagOStuff::class;
		$this->setMainCache( CACHE_ACCEL );

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertInstanceOf(
			SqlBagOStuff::class,
			$ocf->getInstance( $ocf->getAnythingId() ),
			'Fallback to DB if available types fall back to Empty'
		);
	}

	public function testNewAnythingNoAccelNoDb() {
		$this->setCacheConfig( [
			// Mock APCu not being installed (T160519, T147161)
			CACHE_ACCEL => [ 'class' => EmptyBagOStuff::class ]
		] );
		$this->setMainCache( CACHE_ACCEL );

		$this->getServiceContainer()->disableStorage();

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			$ocf->getInstance( $ocf->getAnythingId() ),
			'Fallback to none if available types and DB are unavailable'
		);
	}

	public function testNewAnythingNothingNoDb() {
		$this->getServiceContainer()->disableStorage();

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			$ocf->getInstance( $ocf->getAnythingId() ),
			'No available types or DB. Fallback to none.'
		);
	}

	public function testNewFromIdWincacheAccel() {
		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$className = get_class( $ocf );

		$this->expectDeprecationAndContinue( "/^Use of $className::newFromId with cache ID \"wincache\"\s/" );

		$this->assertInstanceOf(
			HashBagOStuff::class,
			$ocf->getInstance( 'wincache' ),
			'Fallback to APCu for deprecated wincache'
		);
	}

	public function testNewFromIdWincacheNoAccel() {
		// Mock APC not being installed (T160519, T147161)
		ObjectCacheFactory::$localServerCacheClass = EmptyBagOStuff::class;

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$className = get_class( $ocf );

		$this->expectDeprecationAndContinue( "/^Use of $className::newFromId with cache ID \"wincache\"\s/" );

		$this->assertInstanceOf(
			EmptyBagOStuff::class,
			$ocf->getInstance( 'wincache' ),
			'No caching if APCu is not available'
		);
	}

	public static function provideLocalServerKeyspace() {
		$dbDomain = static function ( $dbName, $dbPrefix ) {
			global $wgDBmwschema;
			return ( new DatabaseDomain( $dbName, $wgDBmwschema, $dbPrefix ) )->getId();
		};
		return [
			'default' => [ false, 'my_wiki', '', $dbDomain( 'my_wiki', '' ) ],
			'custom' => [ 'custom', 'my_wiki', '', 'custom' ],
			'prefix' => [ false, 'my_wiki', 'nl_', $dbDomain( 'my_wiki', 'nl_' ) ],
			'empty string' => [ '', 'my_wiki', 'nl_', $dbDomain( 'my_wiki', 'nl_' ) ],
		];
	}

	/**
	 * @dataProvider provideLocalServerKeyspace
	 */
	public function testLocalServerKeyspace( $cachePrefix, $dbName, $dbPrefix, $expect ) {
		$this->overrideConfigValues( [
			MainConfigNames::CachePrefix => $cachePrefix,
			MainConfigNames::DBname => $dbName,
			MainConfigNames::DBprefix => $dbPrefix,
		] );
		// Regression against T247562, T361177.
		$cache = $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_ACCEL );
		$cache = TestingAccessWrapper::newFromObject( $cache );
		$this->assertSame( $expect, $cache->keyspace );
	}

	public function testNewMultiWrite() {
		$this->overrideConfigValues( [
			MainConfigNames::CachePrefix => 'moon-river',
		] );
		$this->setCacheConfig( [
			'multi-example' => [
				'class' => 'MultiWriteBagOStuff',
				'caches' => [
					0 => [
						'class' => 'HashBagOStuff',
					],
					1 => [
						'class' => 'HashBagOStuff',
					],
				],
			],
		] );

		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$multi = $ocf->getInstance( 'multi-example' );
		$caches = TestingAccessWrapper::newFromObject( $multi )->caches;

		$this->assertSame( 'moon-river:x', $multi->makeKey( 'x' ), 'MultiWrite key' );

		// Confirm that dependency injection is also applied to the objects constructed
		// for the child caches (T318272).
		$this->assertSame( 'moon-river:x', $caches[0]->makeKey( 'x' ), 'inject cache 0 keyspace' );
		$this->assertSame( 'moon-river:x', $caches[1]->makeKey( 'x' ), 'inject cache 1 keyspace' );
	}

	public static function provideIsDatabaseId() {
		return [
			[ CACHE_DB, CACHE_NONE, true ],
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
		$this->overrideConfigValues( [
			MainConfigNames::MainCacheType => $mainCacheType
		] );
		$ocf = $this->getServiceContainer()->getObjectCacheFactory();
		$this->assertSame( $expected, $ocf->isDatabaseId( $id ) );
	}
}
