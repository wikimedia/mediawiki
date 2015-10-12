<?php

/**
 * @covers ObjectCacheManager
 * @group objectcache
 */
class ObjectCacheManagerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return MediaWiki\Logger\Spi
	 */
	private function getLoggerFactory() {
		$logger = $this->getMock( 'Psr\Log\LoggerInterface' );

		$loggerFactory = $this->getMock( 'MediaWiki\Logger\Spi' );
		$loggerFactory->expects( $this->any() )
			->method( 'getLogger' )
			->will( $this->returnValue( $logger ) );

		return $loggerFactory;
	}

	/**
	 * @return ObjectCacheManager
	 */
	private function newObjectCacheManager( array $objectCaches, array $wanCaches ) {
		$manager = new ObjectCacheManager(
			'TEST',
			$objectCaches,
			$wanCaches,
			$this->getLoggerFactory()
		);

		$memcachedParams = [
			'servers' => [ '127.0.0.1:11211' ],
			'debug' => false,
			'persistent' => false,
			'timeout' => 60,
		];

		$manager->setDefaultMemcachedParams( $memcachedParams );

		$manager->setMainStash( 'db-replicated' );
		$manager->setMainWANCache( false );
		$manager->setLocalClusterCache( CACHE_NONE );

		$manager->setAnythingCandidates( [
			CACHE_NONE,
			CACHE_ANYTHING,
		] );

		return $manager;
	}

	public function testConstruct() {
		$manager = new ObjectCacheManager(
			'Foo',
			[],
			[],
			$this->getLoggerFactory()
		);

		$this->assertSame( 'Foo', $manager->getDefaultKeyspace() );
	}

	// The default cache setup should mirror the one in DefaultSettings.php
	// Would be nice to have a safe way to access default settings.
	private static $defaultCaches = [
		CACHE_NONE => [ 'class' => 'EmptyBagOStuff' ],
		CACHE_DB => [ 'class' => 'SqlBagOStuff', 'loggroup' => 'SQLBagOStuff' ],

		// Default handling for the CACHE_ANYTHING and CACHE_ACCEL aliases are build in now.
		//CACHE_ANYTHING => [ ... ],
		//CACHE_ACCEL => [ ... ],

		CACHE_MEMCACHED => [ 'class' => 'MemcachedPhpBagOStuff', 'loggroup' => 'memcached' ],

		'db-replicated' => [
			'class'       => 'ReplicatedBagOStuff',
			'readFactory' => [
				'class' => 'SqlBagOStuff',
				'args'  => [ [ 'slaveOnly' => true ] ]
			],
			'writeFactory' => [
				'class' => 'SqlBagOStuff',
				'args'  => [ [ 'slaveOnly' => false ] ]
			],
			'loggroup'  => 'SQLBagOStuff'
		],

		'apc' => [ 'class' => 'APCBagOStuff' ],
		'xcache' => [ 'class' => 'XCacheBagOStuff' ],
		'wincache' => [ 'class' => 'WinCacheBagOStuff' ],
		'memcached-php' => [ 'class' => 'MemcachedPhpBagOStuff', 'loggroup' => 'memcached' ],
		'memcached-pecl' => [ 'class' => 'MemcachedPeclBagOStuff', 'loggroup' => 'memcached' ],
		'hash' => [ 'class' => 'HashBagOStuff' ],
	];

	public function provideGetInstance() {
		$extraCaches = [
			'TEST' => [ 'factory' => function() {
				return new HashBagOStuff();
			} ],
		];

		$aliasOverrides = [
			CACHE_ACCEL => [ 'class' => 'HashBagOStuff' ],
			CACHE_ANYTHING => [ 'class' => 'HashBagOStuff' ],
		];

		return [
			'custom factory' => [ 'TEST', $extraCaches, 'HashBagOStuff' ],

			'CACHE_NONE' => [ CACHE_NONE, $extraCaches, 'EmptyBagOStuff' ],
			'CACHE_DB' => [ CACHE_DB, $extraCaches, 'SqlBagOStuff' ],

			'CACHE_ANYTHING build-in' => [ CACHE_ANYTHING, $extraCaches, 'BagOStuff' ],
			'CACHE_ACCEL build-in' => [ CACHE_ACCEL, $extraCaches, 'BagOStuff' ],
			'CACHE_MEMCACHED' => [ CACHE_MEMCACHED, $extraCaches, 'MemcachedPhpBagOStuff' ],

			'db-replicated' => [ 'db-replicated', $extraCaches, 'ReplicatedBagOStuff' ],

			'apc' => [ 'apc', $extraCaches, 'APCBagOStuff' ],
			'xcache' => [ 'xcache', $extraCaches, 'XCacheBagOStuff' ],
			'wincache' => [ 'wincache', $extraCaches, 'WinCacheBagOStuff' ],
			'memcached-php' => [ 'memcached-php', $extraCaches, 'MemcachedPhpBagOStuff' ],
			'memcached-pecl' => [ 'memcached-pecl', $extraCaches, 'MemcachedPeclBagOStuff' ],
			'hash' => [ 'hash', $extraCaches, 'HashBagOStuff' ],

			'CACHE_ACCEL override' => [ CACHE_ACCEL, $aliasOverrides, 'HashBagOStuff' ],
			'CACHE_ANYTHING override' => [ CACHE_ANYTHING, $aliasOverrides, 'HashBagOStuff' ],
		];
	}

	/**
	 * @dataProvider provideGetInstance
	 */
	public function testGetInstance( $name, $extraCaches, $expectedClass ) {
		$cacheDefinitions = self::$defaultCaches;

		// Extras override defaults.
		// We can't use array_merge, because it messes with numeric keys, especially negative ones.
		foreach ( $extraCaches as $key => $def ) {
			$cacheDefinitions[$key] = $def;
		}

		$manager = $this->newObjectCacheManager( $cacheDefinitions, [] );
		$cache = $manager->getInstance( $name );
		$this->assertInstanceOf( $expectedClass, $cache );

		$this->assertSame( $cache, $manager->getInstance( $name ), 'instances should be cached' );
	}

	public function testGetInstance_unknown() {
		$cacheDefinitions = [
			'hash' => [ 'class' => 'HashBagOStuff' ],
		];

		$manager = $this->newObjectCacheManager( $cacheDefinitions, [] );
		$this->setExpectedException( 'MWException' );
		$manager->getInstance( 'BARF' );
	}

	public function provideGetWANInstance() {
		// The default cache setup should mirror the one in DefaultSettings.php
		$caches = [
			CACHE_NONE => [
				'class'         => 'WANObjectCache',
				'cacheId'       => CACHE_NONE,
				'pool'          => 'mediawiki-main-none',
				'relayerConfig' => [ 'class' => 'EventRelayerNull' ]
			],

			'memcached-php' => array(
				'class'         => 'WANObjectCache',
				'cacheId'       => 'memcached-php',
				'pool'          => 'mediawiki-main-memcached',
				'relayerConfig' => array( 'class' => 'EventRelayerNull' )
			)
		];

		return [
			'memcached-php' => [ 'memcached-php', $caches, 'WANObjectCache' ],
			CACHE_NONE => [ CACHE_NONE, $caches, 'WANObjectCache' ],
		];
	}

	/**
	 * @dataProvider provideGetWANInstance
	 */
	public function testGetWANInstance( $name, $cacheDefinitions, $expectedClass ) {
		$manager = $this->newObjectCacheManager( self::$defaultCaches, $cacheDefinitions );
		$cache = $manager->getWANInstance( $name );
		$this->assertInstanceOf( $expectedClass, $cache );

		$this->assertSame( $cache, $manager->getWANInstance( $name ), 'instances should be cached' );
	}

	public function testGetWANInstance_unknown() {
		$cacheDefinitions = [
			CACHE_NONE => [
				'class'         => 'WANObjectCache',
				'cacheId'       => CACHE_NONE,
				'pool'          => 'mediawiki-main-none',
				'relayerConfig' => [ 'class' => 'EventRelayerNull' ]
			],
		];

		$manager = $this->newObjectCacheManager( $cacheDefinitions, [] );
		$this->setExpectedException( 'MWException' );
		$manager->getWANInstance( 'BARF' );
	}

	public function testClear() {
		$cacheDefinitions = [
			'test' => [ 'class' => 'HashBagOStuff' ],
		];

		$manager = $this->newObjectCacheManager( $cacheDefinitions, [] );
		$cache = $manager->getInstance( 'test' );
		$manager->clear();

		$this->assertNotSame( $cache, $manager->getInstance( 'test' ) );
	}

	public function testSetAnythingCandidates() {

	}

	public function testSetLocalClusterCache() {

	}

	public function testSetMainWANCache() {

	}

	public function testSetMainStash() {

	}

	public function testNewAnything() {

	}

	public function testGetLocalServerInstance() {

	}

	public function testGetLocalClusterInstance() {

	}

	public function testGetMainWANInstance() {

	}

	public function testGetMainStashInstance() {

	}

}
