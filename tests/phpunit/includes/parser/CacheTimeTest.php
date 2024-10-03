<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\CacheTime;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use Wikimedia\Tests\SerializationTestTrait;

/**
 * @covers \MediaWiki\Parser\CacheTime
 */
class CacheTimeTest extends MediaWikiIntegrationTestCase {
	use SerializationTestTrait;

	protected function setUp(): void {
		parent::setUp();

		MWTimestamp::setFakeTime( ParserCacheSerializationTestCases::FAKE_TIME );
		$this->overrideConfigValue(
			MainConfigNames::ParserCacheExpireTime,
			ParserCacheSerializationTestCases::FAKE_CACHE_EXPIRY
		);
	}

	/**
	 * Overrides SerializationTestTrait::getClassToTest
	 * @return string
	 */
	public static function getClassToTest(): string {
		return CacheTime::class;
	}

	/**
	 * Overrides SerializationTestTrait::getSerializedDataPath
	 * @return string
	 */
	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../data/ParserCache';
	}

	/**
	 * Overrides SerializationTestTrait::getTestInstancesAndAssertions
	 * @return array
	 */
	public static function getTestInstancesAndAssertions(): array {
		return ParserCacheSerializationTestCases::getCacheTimeTestCases();
	}

	/**
	 * Overrides SerializationTestTrait::getSupportedSerializationFormats
	 * @return array
	 */
	public static function getSupportedSerializationFormats(): array {
		return ParserCacheSerializationTestCases::getSupportedSerializationFormats(
			self::getClassToTest()
		);
	}

	public function testCacheExpiryDoesNotIncrease() {
		$cacheTime = new CacheTime();
		$this->assertSame(
			ParserCacheSerializationTestCases::FAKE_CACHE_EXPIRY,
			$cacheTime->getCacheExpiry()
		);

		$cacheTime->updateCacheExpiry( 10 );
		$this->assertSame( 10, $cacheTime->getCacheExpiry() );

		$cacheTime->updateCacheExpiry( 5 );
		$this->assertSame( 5, $cacheTime->getCacheExpiry() );

		$cacheTime->updateCacheExpiry( 100500 );
		$this->assertSame( 5, $cacheTime->getCacheExpiry() );
	}

	public function testCacheExpiryDoesNotIncreaseNotNegative() {
		$cacheTime = new CacheTime();
		$cacheTime->updateCacheExpiry( -10 );
		$this->assertSame( 0, $cacheTime->getCacheExpiry() );
	}

	public function testCacheExpiryNotMoreThenGlobal() {
		$cacheTime = new CacheTime();
		$cacheTime->updateCacheExpiry(
			ParserCacheSerializationTestCases::FAKE_CACHE_EXPIRY + 1
		);
		$this->assertSame(
			ParserCacheSerializationTestCases::FAKE_CACHE_EXPIRY,
			$cacheTime->getCacheExpiry()
		);
	}

	public function testExpired() {
		$cacheTime = new CacheTime();
		$cacheTime->updateCacheExpiry( 0 );
		$this->assertTrue( $cacheTime->expired( MWTimestamp::now() ) );

		$cacheTime = new CacheTime();
		$cacheTime->setCacheTime( MWTimestamp::now() );
		$this->assertTrue(
			$cacheTime->expired(
				MWTimestamp::convert( TS_MW, MWTimestamp::now( TS_UNIX ) + 10 )
			)
		);

		$cacheTime = new CacheTime();
		$cacheTime->updateCacheExpiry( 10 );
		$cacheTime->setCacheTime( MWTimestamp::now() );
		$this->assertTrue(
			$cacheTime->expired(
				MWTimestamp::convert( TS_MW, MWTimestamp::now( TS_UNIX ) + 15 )
			)
		);
	}

	public function testGetSetOptions() {
		$options = ParserOptions::allCacheVaryingOptions();
		$cacheTime = new CacheTime();
		$cacheTime->recordOptions( $options );
		$this->assertArrayEquals( $options, $cacheTime->getUsedOptions() );
	}
}
