<?php

namespace MediaWiki\Tests\Parser;

use CacheTime;
use JsonSerializable;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use ParserOutput;

/**
 * A collection of serialization test cases for parser cache.
 *
 * Contains a set of acceptance tests for CacheTime and ParserOutput objects.
 * The acceptance tests will be run on instances created by the current code,
 * as well as instances deserialized from stored serializations for various MW
 * versions.
 *
 * Since backwards compatibility for objects stored in ParserCache is necessary,
 * failure of a deserialization test most likely indicates an error in the code.
 * However, since the serialization format may change, thus if proper compatibility
 * logic was added but a serialization test is still failing, you might want to
 * update stored serialized data using validateParserCacheSerializationTestData.php
 * script. The same script should be run when more acceptance tests are added
 * to generate and save serialized object, which would be used for acceptance
 * deserialization tests.
 *
 * @see SerializationTestTrait
 * @see SerializationTestUtils
 * @see ValidateParserCacheSerializationTestData
 * @package MediaWiki\Tests\Parser
 */
abstract class ParserCacheSerializationTestCases {

	public const FAKE_TIME = 123456789;

	public const FAKE_CACHE_EXPIRY = 42;

	/**
	 * Get acceptance test cases for CacheTime class.
	 * @see SerializationTestTrait::getTestInstancesAndAssertions()
	 * @return array[]
	 */
	public static function getCacheTimeTestCases(): array {
		$cacheTimeWithUsedOptions = new CacheTime();
		$cacheTimeWithUsedOptions->mUsedOptions = [ 'optA', 'optX' ];

		$cacheTimestamp = MWTimestamp::convert( TS_MW, 987654321 );
		$cacheTimeWithTime = new CacheTime();
		$cacheTimeWithTime->setCacheTime( $cacheTimestamp );

		$cacheExpiry = 10;
		$cacheTimeWithExpiry = new CacheTime();
		$cacheTimeWithExpiry->updateCacheExpiry( $cacheExpiry );

		$cacheRevisionId = 1234;
		$cacheTimeWithRevId = new CacheTime();
		$cacheTimeWithRevId->setCacheRevisionId( $cacheRevisionId );

		return [
			'empty' => [
				'instance' => new CacheTime(),
				'assertions' => function ( MediaWikiIntegrationTestCase $testCase, CacheTime $object ) {
					$testCase->assertSame( self::FAKE_CACHE_EXPIRY, $object->getCacheExpiry() );
					$testCase->assertNull( $object->getCacheRevisionId() );
					$testCase->assertSame(
						MWTimestamp::convert( TS_MW, self::FAKE_TIME ),
						$object->getCacheTime()
					);
					// When the cacheRevisionId is not set, this method always returns true.
					$testCase->assertFalse( $object->isDifferentRevision( 29 ) );
					$testCase->assertFalse( $object->isDifferentRevision( 31 ) );
					$testCase->assertTrue( $object->isCacheable() );
					$testCase->assertSame(
						$object->getCacheTime(),
						MWTimestamp::convert( TS_MW, self::FAKE_TIME )
					);
				}
			],
			'usedOptions' => [
				'instance' => $cacheTimeWithUsedOptions,
				'assertions' => function ( MediaWikiIntegrationTestCase $testCase, CacheTime $object ) {
					$testCase->assertArrayEquals( [ 'optA', 'optX' ], $object->mUsedOptions );
				}
			],
			'cacheTime' => [
				'instance' => $cacheTimeWithTime,
				'assertions' => function (
					MediaWikiIntegrationTestCase $testCase, CacheTime $object
				) use ( $cacheTimestamp ) {
					$testCase->assertSame( $cacheTimestamp, $object->getCacheTime() );
				}
			],
			'cacheExpiry' => [
				'instance' => $cacheTimeWithExpiry,
				'assertions' => function (
					MediaWikiIntegrationTestCase $testCase, CacheTime $object
				) use ( $cacheExpiry ) {
					$testCase->assertSame( $cacheExpiry, $object->getCacheExpiry() );
				}
			],
			'cacheRevisionId' => [
				'instance' => $cacheTimeWithRevId,
				'assertions' => function (
					MediaWikiIntegrationTestCase $testCase, CacheTime $object
				) use  ( $cacheRevisionId ) {
					$testCase->assertSame( $cacheRevisionId, $object->getCacheRevisionId() );
				}
			]
		];
	}

	/**
	 * Get acceptance test cases for ParserOutput class.
	 * @see SerializationTestTrait::getTestInstancesAndAssertions()
	 * @return array[]
	 */
	public static function getParserOutputTestCases() {
		$parserOutputWithUsedOptions = new ParserOutput( 'Dummy' );
		$parserOutputWithUsedOptions->recordOption( 'optA' );
		$parserOutputWithUsedOptions->recordOption( 'optX' );
		return [
			'empty' => [
				'instance' => new ParserOutput(),
				'assertions' => function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( self::FAKE_CACHE_EXPIRY, $object->getCacheExpiry() );
					$testCase->assertNull( $object->getCacheRevisionId() );
					$testCase->assertSame(
						MWTimestamp::convert( TS_MW, self::FAKE_TIME ),
						$object->getCacheTime()
					);
					$testCase->assertFalse( $object->isDifferentRevision( 29 ) );
					$testCase->assertSame( '', $object->getText() );
					$testCase->assertArrayEquals( [], $object->getUsedOptions() );
					// TODO: more assertions on the empty object
				}
			],
			'text' => [
				'instance' => new ParserOutput( 'Lorem Ipsum' ),
				'assertions' => function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertSame( 'Lorem Ipsum', $object->getText() );
				}
			],
			'usedOptions' => [
				'instance' => $parserOutputWithUsedOptions,
				'assertions' => function ( MediaWikiIntegrationTestCase $testCase, ParserOutput $object ) {
					$testCase->assertArrayEquals( [ 'optA', 'optX' ], $object->getUsedOptions() );
				}
			]
		];
	}

	/**
	 * @param string $class the class name
	 * @return string[][] a list of supported serialization formats info
	 * in the following format:
	 *  'ext' => string file extension for stored serializations
	 *  'serializer' => callable to serialize objects
	 *  'deserializer' => callable to deserialize objects
	 */
	public static function getSupportedSerializationFormats( string $class ): array {
		$serializationFormats = [ [
			'ext' => 'serialized',
			'serializer' => 'serialize',
			'deserializer' => 'unserialize'
		] ];
		if ( is_subclass_of( $class, JsonSerializable::class ) ) {
			$serializationFormats[] = [
				'ext' => 'json',
				'serializer' => function ( JsonSerializable $obj ) {
					return json_encode( $obj->jsonSerialize() );
				},
				'deserializer' => $class . '::newFromJson'
			];
		}
		return $serializationFormats;
	}
}
