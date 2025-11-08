<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\OtlpSerializer;

/**
 * @covers \Wikimedia\Telemetry\OtlpSerializer
 */
class OtlpSerializerTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideKeyValuePairSerializationData
	 */
	public function testKeyValuePairSerialization( array $input, array $expected ): void {
		$actual = OtlpSerializer::serializeKeyValuePairs( $input );

		$this->assertSame( $expected, $actual );
	}

	public static function provideKeyValuePairSerializationData(): iterable {
		yield 'empty data' => [ [], [] ];
		yield 'mixed data' => [
			[
				'string-value' => 'string',
				'numeric-value' => 123,
				'float-value' => 1.5,
				'object-value' => new \stdClass(),
				'boolean-value' => true,
				'list-value' => [ 'a', 'b' ],
				'null-value' => null
			],
			[
				[ 'key' => 'string-value', 'value' => [ 'stringValue' => 'string' ] ],
				[ 'key' => 'numeric-value', 'value' => [ 'intValue' => 123 ] ],
				[ 'key' => 'float-value', 'value' => [ 'doubleValue' => 1.5 ] ],
				[ 'key' => 'boolean-value', 'value' => [ 'boolValue' => true ] ],
				[ 'key' => 'list-value', 'value' => [ 'arrayValue' => [ 'a', 'b' ] ] ]
			]
		];
	}
}
