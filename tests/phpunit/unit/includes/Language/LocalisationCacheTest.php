<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Language;

use ConstantDependency;
use DependencyWrapper;
use GlobalDependency;
use MediaWiki\Language\LocalisationCache;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Language\LocalisationCache
 * @group Language
 */
class LocalisationCacheTest extends MediaWikiUnitTestCase {

	public function testAllKeysSplitIntoCoreOnlyAndNonCoreOnly(): void {
		$coreOnlyKeys = TestingAccessWrapper::constant( LocalisationCache::class, 'CORE_ONLY_KEYS' );
		$allExceptCoreOnlyKeys = TestingAccessWrapper::constant( LocalisationCache::class, 'ALL_EXCEPT_CORE_ONLY_KEYS' );
		$this->assertSame( [],
			array_diff( $coreOnlyKeys, LocalisationCache::ALL_KEYS ),
			'core-only keys must be subset of all keys' );
		$this->assertSame( [],
			array_diff( $allExceptCoreOnlyKeys, LocalisationCache::ALL_KEYS ),
			'keys that are not core-only must be subset of all keys' );
		$this->assertSame( [],
			array_intersect( $coreOnlyKeys, $allExceptCoreOnlyKeys ),
			'core-only and other keys must not overlap' );
		$this->assertSame( [],
			array_diff( LocalisationCache::ALL_KEYS, array_merge(
				$coreOnlyKeys,
				$allExceptCoreOnlyKeys
			) ),
			'core-only keys + all keys except them must contain all keys' );
	}

	public function testCoreOnlyKeysNotMergeable(): void {
		$coreOnlyKeys = TestingAccessWrapper::constant( LocalisationCache::class, 'CORE_ONLY_KEYS' );
		$lc = TestingAccessWrapper::newFromClass( LocalisationCache::class );
		foreach ( $coreOnlyKeys as $key ) {
			$this->assertFalse( $lc->isMergeableKey( $key ),
				'it does not make sense for core-only keys to be mergeable' );
		}
	}

	public function testConstantDependencyCallbackNotCalledWhenNotExpired(): void {
		define( 'TEST_CONSTANT_UNIT_1', 'initial' );
		$dep = new ConstantDependency( 'TEST_CONSTANT_UNIT_1' );

		$called = false;
		$reason = null;
		$callback = static function ( $r ) use ( &$called, &$reason ) {
			$called = true;
			$reason = $r;
		};

		$expired = $dep->isExpired( $callback );

		$this->assertFalse( $expired );
		$this->assertFalse( $called, 'Callback should not be called when not expired' );
	}

	public function testGlobalDependencyCallbackNotCalledWhenNotExpired(): void {
		$GLOBALS['TEST_GLOBAL_UNIT_1'] = 'test_value';
		$dep = new GlobalDependency( 'TEST_GLOBAL_UNIT_1' );

		$called = false;
		$callback = static function () use ( &$called ) {
			$called = true;
		};

		$expired = $dep->isExpired( $callback );

		$this->assertFalse( $expired );
		$this->assertFalse( $called, 'Callback should not be called when global not expired' );
	}

	public function testGlobalDependencyUnsetCallbackInvoked(): void {
		$GLOBALS['TEST_GLOBAL_UNIT_2'] = 'test_value';
		$dep = new GlobalDependency( 'TEST_GLOBAL_UNIT_2' );

		unset( $GLOBALS['TEST_GLOBAL_UNIT_2'] );

		$called = false;
		$reason = null;
		$callback = static function ( $r ) use ( &$called, &$reason ) {
			$called = true;
			$reason = $r;
		};

		$expired = $dep->isExpired( $callback );

		$this->assertTrue( $expired );
		$this->assertTrue( $called );
		$this->assertStringContainsString( 'No global named', $reason );
	}

	public function testGlobalDependencyChangedCallbackInvoked(): void {
		$GLOBALS['TEST_GLOBAL_UNIT_3'] = 'initial_value';
		$dep = new GlobalDependency( 'TEST_GLOBAL_UNIT_3' );

		$GLOBALS['TEST_GLOBAL_UNIT_3'] = 'changed_value';

		$called = false;
		$reason = null;
		$callback = static function ( $r ) use ( &$called, &$reason ) {
			$called = true;
			$reason = $r;
		};

		$expired = $dep->isExpired( $callback );

		$this->assertTrue( $expired );
		$this->assertTrue( $called );
		$this->assertStringContainsString( 'Value of global', $reason );
		$this->assertStringContainsString( 'TEST_GLOBAL_UNIT_3', $reason );
	}

	public function testDependencyWrapperNotExpiredWithCallback(): void {
		$GLOBALS['TEST_GLOBAL_UNIT_4'] = 'value';
		$dep = new GlobalDependency( 'TEST_GLOBAL_UNIT_4' );
		$wrapper = new DependencyWrapper( 'test_data', [ $dep ] );

		$called = false;
		$callback = static function () use ( &$called ) {
			$called = true;
		};

		$expired = $wrapper->isExpired( $callback );

		$this->assertFalse( $expired );
		$this->assertFalse( $called, 'Callback should not be called when not expired' );
	}

	public function testDependencyWrapperExpiredPropagatesCallback(): void {
		$GLOBALS['TEST_GLOBAL_UNIT_5'] = 'initial';
		$dep = new GlobalDependency( 'TEST_GLOBAL_UNIT_5' );
		$wrapper = new DependencyWrapper( 'test_data', [ $dep ] );

		$GLOBALS['TEST_GLOBAL_UNIT_5'] = 'changed';

		$called = false;
		$reason = null;
		$callback = static function ( $r ) use ( &$called, &$reason ) {
			$called = true;
			$reason = $r;
		};

		$expired = $wrapper->isExpired( $callback );

		$this->assertTrue( $expired );
		$this->assertTrue( $called );
		$this->assertStringContainsString( 'Value of global', $reason );
	}

	public function testDependencyWrapperMultipleDepsStopsAtFirst(): void {
		$GLOBALS['TEST_GLOBAL_UNIT_6'] = 'value1';
		$GLOBALS['TEST_GLOBAL_UNIT_7'] = 'value2';
		$dep1 = new GlobalDependency( 'TEST_GLOBAL_UNIT_6' );
		$dep2 = new GlobalDependency( 'TEST_GLOBAL_UNIT_7' );
		$wrapper = new DependencyWrapper( 'test_data', [ $dep1, $dep2 ] );

		// Expire the first dependency
		$GLOBALS['TEST_GLOBAL_UNIT_6'] = 'changed';

		$reason = null;
		$callback = static function ( $r ) use ( &$reason ) {
			$reason = $r;
		};

		$expired = $wrapper->isExpired( $callback );

		$this->assertTrue( $expired );
		$this->assertStringContainsString( 'TEST_GLOBAL_UNIT_6', $reason );
		$this->assertStringNotContainsString( 'TEST_GLOBAL_UNIT_7', $reason );
	}

}
