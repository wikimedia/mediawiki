<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use MediaWiki\Settings\Cache\ArrayCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Cache\ArrayCache
 */
class ArrayCacheTest extends TestCase {
	use CacheInterfaceTestTrait;

	protected function newCache(): ArrayCache {
		return new ArrayCache();
	}
}
