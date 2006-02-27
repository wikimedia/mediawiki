<?php

require_once( 'PHPUnit.php' );
require_once( '../includes/Defines.php' );
require_once( '../includes/Profiling.php' );
require_once( '../includes/GlobalFunctions.php' );
require_once( '../includes/Image.php' );

class ImageTest extends PHPUnit_TestCase {
	function ImageTest( $name ) {
		$this->PHPUnit_TestCase( $name );
	}

	function setUp() {
	}

	function tearDown() {
	}
	
	function testFitBoxWidth() {
		$vals = array(
			array(
				'width' => 50,
				'height' => 50,
				'tests' => array(
					50 => 50,
					17 => 17,
					18 => 18 ) ),
			array(
				'width' => 366,
				'height' => 300,
				'tests' => array(
					50 => 61,
					17 => 21,
					18 => 22 ) ),
			array(
				'width' => 300,
				'height' => 366,
				'tests' => array(
					50 => 41,
					17 => 14,
					18 => 15 ) ),
			array(
				'width' => 100,
				'height' => 400,
				'tests' => array(
					50 => 12,
					17 => 4,
					18 => 4 ) ) );
		foreach( $vals as $row ) {
			extract( $row );
			foreach( $tests as $max => $expected ) {
				$y = round( $expected * $height / $width );
				$result = wfFitBoxWidth( $width, $height, $max );
				$y2 = round( $result * $height / $width );
				$this->assertEquals( $expected,
					$result,
					"($width, $height, $max) wanted: {$expected}x$y, got: {$result}x$y2" );
			}
		}
	}

	/* TODO: many more! */
}

?>