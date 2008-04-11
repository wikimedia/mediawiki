#!/usr/bin/env php
<?php

define( 'MEDIAWIKI', true );
require 't/Test.php';

require 'includes/Defines.php';

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
			18 => 4 ) )
);

plan( 3 + 3 * count( $vals ) );

require_ok( 'includes/ProfilerStub.php' );
require_ok( 'includes/GlobalFunctions.php' );
require_ok( 'includes/ImageFunctions.php' );

foreach( $vals as $row ) {
	extract( $row );
	foreach( $tests as $max => $expected ) {
		$y = round( $expected * $height / $width );
		$result = wfFitBoxWidth( $width, $height, $max );
		$y2 = round( $result * $height / $width );
		is( $result, $expected,
			"($width, $height, $max) wanted: {$expected}x{$y}, got: {$result}x{$y2}" );
	}
}

