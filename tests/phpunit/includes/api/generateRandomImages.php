<?php
/**
 * Bootstrapping for test image file generation
 *
 * @file
 */

// Evaluate the include path relative to this file
$IP = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );

// Start up MediaWiki in command-line mode
require_once( "$IP/maintenance/Maintenance.php" );
require("RandomImageGenerator.php");

class GenerateRandomImages extends Maintenance {

	public function execute() {

		$getOptSpec = array(
			'dictionaryFile::',
			'minWidth::',
			'maxWidth::',
			'minHeight::',
			'maxHeight::',
			'shapesToDraw::',
			'shape::',

			'number::',
			'format::'
		);
		$options = getopt( null, $getOptSpec );

		$format = isset( $options['format'] ) ? $options['format'] : 'jpg';
		unset( $options['format'] );

		$number = isset( $options['number'] ) ? intval( $options['number'] ) : 10;
		unset( $options['number'] );

		$randomImageGenerator = new RandomImageGenerator( $options );
		$randomImageGenerator->writeImages( $number, $format );
	}
}

$maintClass = 'GenerateRandomImages';
require( RUN_MAINTENANCE_IF_MAIN );


