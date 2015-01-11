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
		return array(
			// No args
			array( array( function() {
				return 'foo';
			} ), 'foo' ),
			// Has args
			array( array( function( $i ) {
				return $i;
			}, 'bar' ), 'bar' ),
		);
	}
}
