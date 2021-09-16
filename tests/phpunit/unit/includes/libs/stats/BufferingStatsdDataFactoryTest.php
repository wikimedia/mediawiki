<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers BufferingStatsdDataFactory
 */
class BufferingStatsdDataFactoryTest extends PHPUnit\Framework\TestCase {

	public static function provideNormalizeMetricKey() {
		// Reasonable and relied upon
		yield 'Keep empty string' => [ '', '' ];
		yield 'Keep normal' => [ 'foo.BarbaraBar.baz_quux', 'foo.BarbaraBar.baz_quux' ];
		yield 'Skip empty segments' => [ '.missing.start.end.', 'missing.start.end' ];
		yield 'Strip separator prefix' => [ '\\Vendor\\Class::method.x', 'Vendor_Class.method.x' ];

		// Unreasonable and may change, here to self-document current behaviour
		yield [ '__double__under__', 'double_under' ];
		yield [ '.:!:.paamayim.:!:.nekudotayim.:!:.', 'paamayim._.nekudotayim' ];
		yield [ 'first----.!!.!!.-second', 'first_._._._second' ];
	}

	/**
	 * @dataProvider provideNormalizeMetricKey
	 */
	public function testNormalizeMetricKey( string $input, string $expected ) {
		$stats = TestingAccessWrapper::newFromClass( BufferingStatsdDataFactory::class );
		$this->assertSame( $expected, $stats->normalizeMetricKey( $input ) );
	}
}
