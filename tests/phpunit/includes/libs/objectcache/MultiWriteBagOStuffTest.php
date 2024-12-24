<?php

use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\MultiWriteBagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\ObjectCache\MultiWriteBagOStuff
 * @group BagOStuff
 * @group Database
 */
class MultiWriteBagOStuffTest extends MediaWikiIntegrationTestCase {
	/** @var HashBagOStuff */
	private $cache1;
	/** @var HashBagOStuff */
	private $cache2;
	/** @var MultiWriteBagOStuff */
	private $cache;

	protected function setUp(): void {
		parent::setUp();

		$this->cache1 = new HashBagOStuff();
		$this->cache2 = new HashBagOStuff();
		$this->cache = new MultiWriteBagOStuff( [
			'caches' => [ $this->cache1, $this->cache2 ],
			'replication' => 'async',
			'asyncHandler' => 'DeferredUpdates::addCallableUpdate'
		] );
	}

	public function testSet() {
		$key = 'key';
		$value = 'value';
		$this->cache->set( $key, $value );

		// Set in tier 1
		$this->assertSame( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Set in tier 2
		$this->assertSame( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	public function testAdd() {
		$key = 'key';
		$value = 'value';
		$ok = $this->cache->add( $key, $value );

		$this->assertTrue( $ok );
		// Set in tier 1
		$this->assertSame( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Set in tier 2
		$this->assertSame( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	public function testSyncMergeAsync() {
		$key = 'keyA';
		$value = 'value';
		$func = static function () use ( $value ) {
			return $value;
		};

		// XXX: DeferredUpdates bound to transactions in CLI mode
		$dbw = $this->getDb();
		$dbw->begin();
		$this->cache->merge( $key, $func );

		// Set in tier 1
		$this->assertEquals( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Not yet set in tier 2
		$this->assertFalse( $this->cache2->get( $key ), 'Not written to tier 2' );

		$dbw->commit();
		$this->runDeferredUpdates();

		// Set in tier 2
		$this->assertEquals( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	public function testSyncMergeSync() {
		// Like setUp() but without 'async'
		$cache1 = new HashBagOStuff();
		$cache2 = new HashBagOStuff();
		$cache = new MultiWriteBagOStuff( [
			'caches' => [ $cache1, $cache2 ]
		] );
		$value = 'value';
		$func = static function () use ( $value ) {
			return $value;
		};

		$key = 'keyB';

		$dbw = $this->getDb();
		$dbw->begin();
		$cache->merge( $key, $func );

		// Set in tier 1
		$this->assertEquals( $value, $cache1->get( $key ), 'Written to tier 1' );
		// Immediately set in tier 2
		$this->assertEquals( $value, $cache2->get( $key ), 'Written to tier 2' );

		$dbw->commit();
		$this->runDeferredUpdates();
	}

	public function testSetDelayed() {
		$key = 'key';
		$value = (object)[ 'v' => 'saved value' ];
		$expectValue = clone $value;

		// XXX: DeferredUpdates bound to transactions in CLI mode
		$dbw = $this->getDb();
		$dbw->begin();
		$this->cache->set( $key, $value );

		// Test that later changes to $value don't affect the saved value (e.g. T168040)
		$value->v = 'other value';

		// Set in tier 1
		$this->assertEquals( $expectValue, $this->cache1->get( $key ), 'Written to tier 1' );
		// Not yet set in tier 2
		$this->assertFalse( $this->cache2->get( $key ), 'Not written to tier 2' );

		$dbw->commit();
		$this->runDeferredUpdates();

		// Set in tier 2
		$this->assertEquals( $expectValue, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	public function testMakeKey() {
		$cache1 = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'makeKey' ] )->getMock();
		$cache1->expects( $this->never() )->method( 'makeKey' );

		$cache2 = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'makeKey' ] )->getMock();
		$cache2->expects( $this->never() )->method( 'makeKey' );

		$cache = new MultiWriteBagOStuff( [
			'keyspace' => 'generic',
			'caches' => [ $cache1, $cache2 ]
		] );

		$this->assertSame( 'generic:a:b', $cache->makeKey( 'a', 'b' ) );
	}

	public function testConvertGenericKey() {
		$cache1 = new class extends HashBagOStuff {
			protected function makeKeyInternal( $keyspace, $components ) {
				return $keyspace . ':short-one-way';
			}

			protected function requireConvertGenericKey(): bool {
				return true;
			}
		};
		$cache2 = new class extends HashBagOStuff {
			protected function makeKeyInternal( $keyspace, $components ) {
				return $keyspace . ':short-another-way';
			}

			protected function requireConvertGenericKey(): bool {
				return true;
			}
		};

		$cache = new MultiWriteBagOStuff( [
			'caches' => [ $cache1, $cache2 ]
		] );
		$key = $cache->makeKey( 'a', 'b' );
		$cache->set( $key, 'my_value' );

		$this->assertSame(
			'local:a:b',
			$key
		);
		$this->assertSame(
			[ 'local:short-one-way' ],
			array_keys( TestingAccessWrapper::newFromObject( $cache1 )->bag ),
			'key gets re-encoded for first backend'
		);
		$this->assertSame(
			[ 'local:short-another-way' ],
			array_keys( TestingAccessWrapper::newFromObject( $cache2 )->bag ),
			'key gets re-encoded for second backend'
		);
	}

	public function testMakeGlobalKey() {
		$cache1 = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'makeGlobalKey' ] )->getMock();
		$cache1->expects( $this->never() )->method( 'makeGlobalKey' );

		$cache2 = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'makeGlobalKey' ] )->getMock();
		$cache2->expects( $this->never() )->method( 'makeGlobalKey' );

		$cache = new MultiWriteBagOStuff( [ 'caches' => [ $cache1, $cache2 ] ] );

		$this->assertSame( 'global:a:b', $cache->makeGlobalKey( 'a', 'b' ) );
	}

	public function testDuplicateStoreAdd() {
		$bag = new HashBagOStuff();
		$cache = new MultiWriteBagOStuff( [
			'caches' => [ $bag, $bag ],
		] );

		$this->assertTrue( $cache->add( 'key', 1, 30 ) );
	}

	public function testIncrWithInit() {
		$key = $this->cache->makeKey( 'key' );
		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertSame( 3, $val, "Correct init value" );

		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertSame( 4, $val, "Correct init value" );
		$this->cache->delete( $key );

		$val = $this->cache->incrWithInit( $key, 0, 5 );
		$this->assertSame( 5, $val, "Correct init value" );
	}

	public function testErrorHandling() {
		$t1Cache = $this->createPartialMock( HashBagOStuff::class, [ 'set' ] );
		$t1CacheWrapper = TestingAccessWrapper::newFromObject( $t1Cache );
		$t1CacheNextError = BagOStuff::ERR_NONE;
		$t1Cache->method( 'set' )
			->willReturnCallback( static function () use ( $t1CacheWrapper, &$t1CacheNextError ) {
				if ( $t1CacheNextError !== BagOStuff::ERR_NONE ) {
					$t1CacheWrapper->setLastError( $t1CacheNextError );

					return false;
				}

				return true;
			} );
		$t2Cache = $this->createPartialMock( HashBagOStuff::class, [ 'set' ] );
		$t2CacheWrapper = TestingAccessWrapper::newFromObject( $t2Cache );
		$t2CacheNextError = BagOStuff::ERR_NONE;
		$t2Cache->method( 'set' )
			->willReturnCallback( static function () use ( $t2CacheWrapper, &$t2CacheNextError ) {
				if ( $t2CacheNextError !== BagOStuff::ERR_NONE ) {
					$t2CacheWrapper->setLastError( $t2CacheNextError );

					return false;
				}

				return true;
			} );
		$cache = new MultiWriteBagOStuff( [
			'keyspace' => 'repl_local',
			'caches' => [ $t1Cache, $t2Cache ]
		] );
		$cacheWrapper = TestingAccessWrapper::newFromObject( $cache );
		$key = 'a:key';

		$wp1 = $cache->watchErrors();
		$cache->set( $key, 'value', 3600 );
		$this->assertSame( BagOStuff::ERR_NONE, $t1Cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_NONE, $t2Cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError( $wp1 ) );

		$t1CacheNextError = BagOStuff::ERR_NO_RESPONSE;
		$t2CacheNextError = BagOStuff::ERR_UNREACHABLE;

		$cache->set( $key, 'value', 3600 );
		$this->assertSame( $t1CacheNextError, $t1Cache->getLastError() );
		$this->assertSame( $t2CacheNextError, $t2Cache->getLastError() );
		$this->assertSame( $t2CacheNextError, $cache->getLastError() );
		$this->assertSame( $t2CacheNextError, $cache->getLastError( $wp1 ) );

		$t1CacheNextError = BagOStuff::ERR_NO_RESPONSE;
		$t2CacheNextError = BagOStuff::ERR_UNEXPECTED;

		$wp2 = $cache->watchErrors();
		$cache->set( $key, 'value', 3600 );
		$wp3 = $cache->watchErrors();
		$this->assertSame( $t1CacheNextError, $t1Cache->getLastError() );
		$this->assertSame( $t2CacheNextError, $t2Cache->getLastError() );
		$this->assertSame( $t2CacheNextError, $cache->getLastError( $wp2 ) );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError( $wp3 ) );

		$cacheWrapper->setLastError( BagOStuff::ERR_UNEXPECTED );
		$wp4 = $cache->watchErrors();
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError( $wp1 ) );
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError( $wp2 ) );
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError( $wp3 ) );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError( $wp4 ) );
		$this->assertSame( $t1CacheNextError, $t1Cache->getLastError() );
		$this->assertSame( $t2CacheNextError, $t2Cache->getLastError() );
	}
}
