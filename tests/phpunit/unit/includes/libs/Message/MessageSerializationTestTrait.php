<?php

namespace Wikimedia\Tests\Message;

use MediaWiki\Json\JsonCodec;
use Wikimedia\Tests\SerializationTestTrait;

trait MessageSerializationTestTrait {
	use SerializationTestTrait;

	/**
	 * Overrides SerializationTestTrait::getSerializedDataPath
	 * @return string
	 */
	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../../../data/MessageValue';
	}

	/**
	 * Overrides SerializationTestTrait::getTestInstancesAndAssertions
	 * @return array
	 */
	public static function getTestInstancesAndAssertions(): array {
		$className = self::getClassToTest();
		return array_map( static function ( $test ) use ( $className ) {
			[ $args, $expected ] = $test;
			$obj = new $className( ...$args );
			return [
				'instance' => $obj,
				'assertions' => static function ( $testCase, $obj ) use ( $expected ) {
					$testCase->assertSame( $expected, $obj->dump() );
				},
			];
		}, self::provideConstruct() );
	}

	/**
	 * Overrides SerializationTestTrait::getSupportedSerializationFormats
	 * @return array
	 */
	public static function getSupportedSerializationFormats(): array {
		$jsonCodec = new JsonCodec();
		return [ [
			'ext' => 'json',
			'serializer' => static function ( $obj ) use ( $jsonCodec ) {
				return $jsonCodec->serialize( $obj );
			},
			'deserializer' => static function ( $data ) use ( $jsonCodec ) {
				return $jsonCodec->deserialize( $data );
			},
		] ];
	}
}
