<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Language;

use LocalisationCache;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \LocalisationCache
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

}
