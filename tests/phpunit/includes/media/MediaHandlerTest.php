<?php

/**
 * @group Media
 */
class MediaHandlerTest extends MediaWikiTestCase {

	/**
	 * @covers MediaHandler::fitBoxWidth
	 *
	 * @dataProvider provideTestFitBoxWidth
	 */
	public function testFitBoxWidth( $width, $height, $max, $expected ) {
		$y = round( $expected * $height / $width );
		$result = MediaHandler::fitBoxWidth( $width, $height, $max );
		$y2 = round( $result * $height / $width );
		$this->assertEquals( $expected,
			$result,
			"($width, $height, $max) wanted: {$expected}x$y, got: {z$result}x$y2" );
	}

	public static function provideTestFitBoxWidth() {
		return array_merge(
			static::generateTestFitBoxWidthData( 50, 50, array(
					50 => 50,
					17 => 17,
					18 => 18 )
			),
			static::generateTestFitBoxWidthData( 366, 300, array(
					50 => 61,
					17 => 21,
					18 => 22 )
			),
			static::generateTestFitBoxWidthData( 300, 366, array(
					50 => 41,
					17 => 14,
					18 => 15 )
			),
			static::generateTestFitBoxWidthData( 100, 400, array(
					50 => 12,
					17 => 4,
					18 => 4 )
			)
		);
	}

	private static function generateTestFitBoxWidthData( $width, $height, $tests ) {
		$result = array();
		foreach ( $tests as $max => $expected ) {
			$result[] = array( $width, $height, $max, $expected );
		}
		return $result;
	}
}
