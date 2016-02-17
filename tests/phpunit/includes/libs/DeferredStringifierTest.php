<?php

class DeferredStringifierTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers DeferredStringifier
	 * @dataProvider provideToString
	 */
	public function testToString( $params, $expected ) {
		$class = new ReflectionClass( 'DeferredStringifier' );
		$ds = $class->newInstanceArgs( $params );
		$this->assertEquals( $expected, (string)$ds );
	}

	public static function provideToString() {
		return [
			// No args
			[
				[
					function() {
						return 'foo';
					}
				],
				'foo'
			],
			// Has args
			[
				[
					function( $i ) {
						return $i;
					},
					'bar'
				],
				'bar'
			],
		];
	}

	/**
	 * Verify that the callback is not called if
	 * it is never converted to a string
	 */
	public function testCallbackNotCalled() {
		$ds = new DeferredStringifier( function() {
			throw new Exception( 'This should not be reached!' );
		} );
		// No exception was thrown
		$this->assertTrue( true );
	}
}
