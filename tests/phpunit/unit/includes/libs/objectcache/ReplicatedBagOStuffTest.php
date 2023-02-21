<?php

use Wikimedia\LightweightObjectStore\StorageAwareness;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ReplicatedBagOStuff
 */
class ReplicatedBagOStuffTest extends \MediaWikiUnitTestCase {
	/** @var HashBagOStuff */
	private $writeCache;
	/** @var HashBagOStuff */
	private $readCache;
	/** @var ReplicatedBagOStuff */
	private $cache;

	protected function setUp(): void {
		parent::setUp();

		$this->writeCache = new HashBagOStuff();
		$this->readCache = new HashBagOStuff();
		$this->cache = new ReplicatedBagOStuff( [
			'keyspace' => 'repl_local',
			'writeFactory' => $this->writeCache,
			'readFactory' => $this->readCache,
		] );
	}

	public function testSet() {
		$key = $this->cache->makeKey( 'a', 'key' );
		$value = 'a value';

		$this->cache->set( $key, $value );

		$this->assertSame( $value, $this->writeCache->get( $key ), 'Written' );
		$this->assertFalse( $this->readCache->get( $key ), 'Async replication' );
	}

	public function testGet() {
		$key = $this->cache->makeKey( 'a', 'key' );

		$write = 'new value';
		$this->writeCache->set( $key, $write );
		$read = 'old value';
		$this->readCache->set( $key, $read );

		$this->assertSame( $read, $this->cache->get( $key ), 'Async replication' );
	}

	public function testGetAbsent() {
		$key = $this->cache->makeKey( 'a', 'key' );
		$value = 'a value';
		$this->writeCache->set( $key, $value );

		$this->assertFalse( $this->cache->get( $key ), 'Async replication' );
	}

	public function testGetSetMulti() {
		$keyA = $this->cache->makeKey( 'key', 'a' );
		$keyB = $this->cache->makeKey( 'key', 'b' );
		$valueAOld = 'one old value';
		$valueBOld = 'another old value';
		$valueANew = 'one new value';
		$valueBNew = 'another new value';

		$this->writeCache->setMulti( [ $keyA => $valueANew, $keyB => $valueBNew ] );
		$this->readCache->setMulti( [ $keyA => $valueAOld, $keyB => $valueBOld ] );

		$this->assertEquals(
			[ $keyA => $valueAOld, $keyB => $valueBOld ],
			$this->cache->getMulti( [ $keyA, $keyB ] ),
			'Async replication'
		);
	}

	public function testGetSetRaw() {
		$key = 'a:key';
		$value = 'a value';
		$this->cache->set( $key, $value );

		// Write to master.
		$this->assertEquals( $value, $this->writeCache->get( $key ) );
		// Don't write to replica. Replication is deferred to backend.
		$this->assertFalse( $this->readCache->get( $key ) );
	}

	public function testErrorHandling() {
		$wCache = $this->createPartialMock( HashBagOStuff::class, [ 'set' ] );
		$wCacheWrapper = TestingAccessWrapper::newFromObject( $wCache );
		$wCacheNextError = StorageAwareness::ERR_NONE;
		$wCache->method( 'set' )
			->willReturnCallback( static function () use ( $wCacheWrapper, &$wCacheNextError ) {
				if ( $wCacheNextError !== StorageAwareness::ERR_NONE ) {
					$wCacheWrapper->setLastError( $wCacheNextError );

					return false;
				}

				return true;
			} );
		$rCache = $this->createPartialMock( HashBagOStuff::class, [ 'get' ] );
		$rCacheWrapper = TestingAccessWrapper::newFromObject( $rCache );
		$rCacheNextError = StorageAwareness::ERR_NONE;
		$rCache->method( 'get' )
			->willReturnCallback( static function () use ( $rCacheWrapper, &$rCacheNextError ) {
				if ( $rCacheNextError !== StorageAwareness::ERR_NONE ) {
					$rCacheWrapper->setLastError( $rCacheNextError );
				}

				return false;
			} );
		$cache = new ReplicatedBagOStuff( [
			'keyspace' => 'repl_local',
			'writeFactory' => $wCache,
			'readFactory' => $rCache,
		] );
		$cacheWrapper = TestingAccessWrapper::newFromObject( $cache );
		$key1 = 'a:key';

		$wp1 = $cache->watchErrors();
		$cache->get( $key1 );
		$this->assertSame( StorageAwareness::ERR_NONE, $rCache->getLastError() );
		$this->assertSame( StorageAwareness::ERR_NONE, $cache->getLastError() );
		$this->assertSame( StorageAwareness::ERR_NONE, $cache->getLastError( $wp1 ) );

		$cache->set( $key1, 'value', 3600 );
		$this->assertSame( StorageAwareness::ERR_NONE, $wCache->getLastError() );
		$this->assertSame( StorageAwareness::ERR_NONE, $cache->getLastError() );
		$this->assertSame( StorageAwareness::ERR_NONE, $cache->getLastError( $wp1 ) );

		// Use a different key to avoid the "sessionConsistencyWindow" configuration
		$key2 = 'b:key';
		$wCacheNextError = StorageAwareness::ERR_NO_RESPONSE;
		$rCacheNextError = StorageAwareness::ERR_UNREACHABLE;

		$cache->get( $key2 );
		$this->assertSame( $rCacheNextError, $rCache->getLastError() );
		$this->assertSame( $rCacheNextError, $cache->getLastError() );

		$wp2 = $cache->watchErrors();
		$cache->set( $key2, 'value', 3600 );
		$wp3 = $cache->watchErrors();
		$this->assertSame( $wCacheNextError, $wCache->getLastError() );
		$this->assertSame( $wCacheNextError, $cache->getLastError() );
		$this->assertSame( $wCacheNextError, $cache->getLastError( $wp1 ) );
		$this->assertSame( $wCacheNextError, $cache->getLastError( $wp2 ) );
		$this->assertSame( StorageAwareness::ERR_NONE, $cache->getLastError( $wp3 ) );

		$cacheWrapper->setLastError( StorageAwareness::ERR_UNEXPECTED );
		$wp4 = $cache->watchErrors();
		$this->assertSame( StorageAwareness::ERR_UNEXPECTED, $cache->getLastError() );
		$this->assertSame( StorageAwareness::ERR_UNEXPECTED, $cache->getLastError( $wp1 ) );
		$this->assertSame( StorageAwareness::ERR_UNEXPECTED, $cache->getLastError( $wp2 ) );
		$this->assertSame( StorageAwareness::ERR_UNEXPECTED, $cache->getLastError( $wp3 ) );
		$this->assertSame( StorageAwareness::ERR_NONE, $cache->getLastError( $wp4 ) );
		$this->assertSame( $wCacheNextError, $wCache->getLastError() );
		$this->assertSame( $rCacheNextError, $rCache->getLastError() );
	}
}
