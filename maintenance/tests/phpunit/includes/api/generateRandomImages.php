<?php

require("RandomImageGenerator.php");

$getOptSpec = array( 
	'dictionaryFile::',
	'minWidth::',
	'maxWidth::',
	'minHeight::',
	'maxHeight::',
	'circlesToDraw::',

	'number::',
	'format::'
);
$options = getopt( null, $getOptSpec );

$format = isset( $options['format'] ) ? $options['format'] : 'jpg';
unset( $options['format'] );

$number = isset( $options['number'] ) ? int( $options['number'] ) : 10;
unset( $options['number'] );

$randomImageGenerator = new RandomImageGenerator( $options );
$randomImageGenerator->writeImages( $number, $format );
