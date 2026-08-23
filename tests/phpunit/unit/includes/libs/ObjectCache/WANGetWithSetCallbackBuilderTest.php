<?php

namespace Wikimedia\Tests\ObjectCache;

use LogicException;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\UpdateContext;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @covers \Wikimedia\ObjectCache\WANGetWithSetCallbackBuilder
 * @covers \Wikimedia\ObjectCache\UpdateContext
 */
class WANGetWithSetCallbackBuilderTest extends MediaWikiUnitTestCase {

	/**
	 * @return array{WANObjectCache,HashBagOStuff}
	 */
	private function newWanCache(): array {
		$bag = new HashBagOStuff();
		$cache = new WANObjectCache( [ 'cache' => $bag ] );

		return [ $cache, $bag ];
	}

	public function testKeyIsMadeByTheBuilder() {
		[ $cache ] = $this->newWanCache();

		$calls = 0;
		$callback = static function () use ( &$calls ) {
			++$calls;

			return 'value';
		};

		$value = $cache->buildGetWithSetCallback()
			->key( 'test-group', 'id', 3 )
			->lifetime( 60 )
			->callback( $callback )
			->fetch();

		$this->assertSame( 'value', $value );
		$this->assertSame( 1, $calls );
		// The value is readable under the key that makeKey() would have produced
		$this->assertSame(
			'value',
			$cache->get( $cache->makeKey( 'test-group', 'id', 3 ) )
		);
	}

	public function testGlobalKey() {
		[ $cache ] = $this->newWanCache();

		$value = $cache->buildGetWithSetCallback()
			->globalKey( 'test-group', 'id' )
			->lifetime( 60 )
			->callback( static fn () => 'value' )
			->fetch();

		$this->assertSame( 'value', $value );
		$this->assertSame(
			'value',
			$cache->get( $cache->makeGlobalKey( 'test-group', 'id' ) )
		);
		$this->assertFalse( $cache->get( $cache->makeKey( 'test-group', 'id' ) ) );
	}

	public function testKeyIsRequired() {
		[ $cache ] = $this->newWanCache();

		$this->expectException( LogicException::class );
		$cache->buildGetWithSetCallback()
			->lifetime( 60 )
			->callback( static fn () => 'value' )
			->fetch();
	}

	public function testLifetimeIsRequired() {
		[ $cache ] = $this->newWanCache();

		$this->expectException( LogicException::class );
		$cache->buildGetWithSetCallback()
			->key( 'test-group' )
			->callback( static fn () => 'value' )
			->fetch();
	}

	public function testKeepIndefinitely() {
		[ $cache ] = $this->newWanCache();
		$mockTime = microtime( true );
		$cache->setMockTime( $mockTime );

		$get = static function ( $value ) use ( $cache ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->keepIndefinitely()
				->callback( static fn () => $value )
				->fetch();
		};

		$this->assertSame( 'value', $get( 'value' ) );

		$mockTime += 10 * 86400;
		$this->assertSame( 'value', $get( 'other' ), 'Value is still cached after 10 days' );
	}

	public static function provideLifetimeAliases() {
		return [
			[ 'keepForAMinute', 60 ],
			[ 'keepForAnHour', 3600 ],
			[ 'keepForADay', 86400 ],
			[ 'keepForAWeek', 604800 ],
			[ 'keepForAMonth', 2592000 ],
			[ 'keepForAYear', 31536000 ],
		];
	}

	/**
	 * @dataProvider provideLifetimeAliases
	 */
	public function testLifetimeAliases( string $method, int $expectedLifetime ) {
		[ $cache ] = $this->newWanCache();

		$value = $cache->buildGetWithSetCallback()
			->key( 'test-group' )
			->$method()
			->callback( static fn () => 'value' )
			->fetch();

		$this->assertSame( 'value', $value );
		$this->assertSame(
			$expectedLifetime,
			$cache->getWithInfo( $cache->makeKey( 'test-group' ) )->getLifetime()
		);
	}

	public function testCachedValueIsReused() {
		[ $cache ] = $this->newWanCache();

		$calls = 0;
		$callback = static function () use ( &$calls ) {
			++$calls;

			return "value-$calls";
		};

		for ( $i = 0; $i < 3; $i++ ) {
			$value = $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 60 )
				->callback( $callback )
				->fetch();
			$this->assertSame( 'value-1', $value );
		}
		$this->assertSame( 1, $calls );
	}

	public function testInvalidatedByKey() {
		[ $cache ] = $this->newWanCache();
		$mockTime = microtime( true );
		$cache->setMockTime( $mockTime );

		$calls = 0;
		$get = static function () use ( $cache, &$calls ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 60 )
				->invalidatedByKey( 'test-check' )
				->callback( static function () use ( &$calls ) {
					++$calls;

					return "value-$calls";
				} )
				->fetch();
		};

		$this->assertSame( 'value-1', $get() );
		$this->assertSame( 'value-1', $get() );

		$mockTime += 1;
		$cache->touchCheckKey( $cache->makeKey( 'test-check' ) );
		$mockTime += 1;

		$this->assertSame( 'value-2', $get(), 'Purged via the check key' );
		$this->assertSame( 2, $calls );
	}

	public function testInvalidatedByGlobalKey() {
		[ $cache ] = $this->newWanCache();
		$mockTime = microtime( true );
		$cache->setMockTime( $mockTime );

		$calls = 0;
		$get = static function () use ( $cache, &$calls ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 60 )
				->invalidatedByGlobalKey( 'test-check' )
				->callback( static function () use ( &$calls ) {
					++$calls;

					return "value-$calls";
				} )
				->fetch();
		};

		$this->assertSame( 'value-1', $get() );

		$mockTime += 1;
		$cache->touchCheckKey( $cache->makeKey( 'test-check' ) );
		$mockTime += 1;
		$this->assertSame( 'value-1', $get(), 'Local check key is a different key' );

		$cache->touchCheckKey( $cache->makeGlobalKey( 'test-check' ) );
		$mockTime += 1;
		$this->assertSame( 'value-2', $get() );
	}

	public function testContextReceivesOldValueAndAsOf() {
		[ $cache ] = $this->newWanCache();
		$mockTime = microtime( true );
		$cache->setMockTime( $mockTime );

		$seen = [];
		$get = static function () use ( $cache, &$seen ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 10 )
				->keepStaleFor( 60 )
				->callback(
					static function ( $oldValue, UpdateContext $context ) use ( &$seen ) {
						$seen[] = [
							'oldValue' => $oldValue,
							'hasOldValue' => $context->hasOldValue(),
							'contextOldValue' => $context->getOldValue(),
							'oldAsOf' => $context->getOldAsOf(),
						];

						return 'value';
					}
				)
				->fetch();
		};

		$firstGenerationTime = $mockTime;
		$get();
		$mockTime += 30;
		$get();

		$this->assertSame( false, $seen[0]['oldValue'], 'No prior value' );
		$this->assertFalse( $seen[0]['hasOldValue'] );
		$this->assertFalse( $seen[0]['contextOldValue'] );
		$this->assertNull( $seen[0]['oldAsOf'] );

		$this->assertSame( 'value', $seen[1]['oldValue'], 'Stale prior value' );
		$this->assertTrue( $seen[1]['hasOldValue'] );
		$this->assertSame( 'value', $seen[1]['contextOldValue'] );
		$this->assertEqualsWithDelta( $firstGenerationTime, $seen[1]['oldAsOf'], 0.01 );
	}

	public function testContextSetLifetime() {
		[ $cache ] = $this->newWanCache();
		$mockTime = microtime( true );
		$cache->setMockTime( $mockTime );

		$calls = 0;
		$lifetimeSeen = [];
		$get = static function () use ( $cache, &$calls, &$lifetimeSeen ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 3600 )
				->callback(
					static function ( $oldValue, UpdateContext $context )
						use ( &$calls, &$lifetimeSeen )
					{
						++$calls;
						$lifetimeSeen[] = $context->getLifetime();
						$context->setLifetime( 10 );

						return "value-$calls";
					}
				)
				->fetch();
		};

		$this->assertSame( 'value-1', $get() );

		$mockTime += 30;
		$this->assertSame( 'value-2', $get(), 'Shortened lifetime was used' );
		$this->assertSame( [ 3600, 3600 ], $lifetimeSeen, 'Builder lifetime is the default' );
	}

	public function testContextDoNotCache() {
		[ $cache ] = $this->newWanCache();

		$calls = 0;
		$get = static function () use ( $cache, &$calls ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 3600 )
				->callback(
					static function ( $oldValue, UpdateContext $context ) use ( &$calls ) {
						++$calls;
						$context->doNotCache();

						return "value-$calls";
					}
				)
				->fetch();
		};

		$this->assertSame( 'value-1', $get() );
		$this->assertSame( 'value-2', $get(), 'Nothing was stored' );
		$this->assertFalse( $cache->get( $cache->makeKey( 'test-group' ) ) );
	}

	public function testCallbackParams() {
		[ $cache ] = $this->newWanCache();

		$value = $cache->buildGetWithSetCallback()
			->key( 'test-group' )
			->lifetime( 60 )
			->callbackParams( [ 'id' => 42 ] )
			->callback(
				static function ( $oldValue, UpdateContext $context ) {
					return [
						'params' => $context->getParams(),
						'id' => $context->getParam( 'id' ),
						'missing' => $context->getParam( 'nope', 'fallback' ),
					];
				}
			)
			->fetch();

		$this->assertSame(
			[ 'params' => [ 'id' => 42 ], 'id' => 42, 'missing' => 'fallback' ],
			$value
		);
	}

	public function testProcessCache() {
		[ $cache, $bag ] = $this->newWanCache();

		$calls = 0;
		$get = static function () use ( $cache, &$calls ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 60 )
				->shortProcessCache()
				->callback( static function () use ( &$calls ) {
					++$calls;

					return "value-$calls";
				} )
				->fetch();
		};

		$this->assertSame( 'value-1', $get() );

		// Wipe the backend; only the process cache can answer now
		$bag->clear();

		$this->assertSame( 'value-1', $get() );
		$this->assertSame( 1, $calls );
	}

	public function testValueVersion() {
		[ $cache ] = $this->newWanCache();

		$get = static function ( int $version, string $value ) use ( $cache ) {
			return $cache->buildGetWithSetCallback()
				->key( 'test-group' )
				->lifetime( 60 )
				->valueVersion( $version )
				->callback( static fn () => $value )
				->fetch();
		};

		$this->assertSame( 'v1', $get( 1, 'v1' ) );
		$this->assertSame( 'v1', $get( 1, 'other' ) );
		$this->assertSame( 'v2', $get( 2, 'v2' ), 'Different version uses a variant key' );
		$this->assertSame( 'v1', $get( 1, 'other' ), 'Original version is untouched' );
	}
}
