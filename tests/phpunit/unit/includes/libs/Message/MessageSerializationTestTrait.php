<?php

namespace Wikimedia\Tests\Message;

use Wikimedia\Tests\JsonSerializationTestTrait;

trait MessageSerializationTestTrait {
	use JsonSerializationTestTrait;

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../../../data/MessageValue';
	}

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
}
