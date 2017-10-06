<?php
/**
 * Bootstrapping for test image file generation
 *
 * @file
 */

// Start up MediaWiki in command-line mode
require_once __DIR__ . "/../../../../maintenance/Maintenance.php";
require __DIR__ . "/RandomImageGenerator.php";

class GenerateRandomImages extends Maintenance {

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$getOptSpec = [
			'dictionaryFile::',
			'minWidth::',
			'maxWidth::',
			'minHeight::',
			'maxHeight::',
			'shapesToDraw::',
			'shape::',

			'number::',
			'format::'
		];
		$options = getopt( null, $getOptSpec );

		$format = $options['format'] ?? 'jpg';
		unset( $options['format'] );

		$number = isset( $options['number'] ) ? intval( $options['number'] ) : 10;
		unset( $options['number'] );

		$randomImageGenerator = new RandomImageGenerator( $options );
		$randomImageGenerator->writeImages( $number, $format );
	}
}

$maintClass = 'GenerateRandomImages';
require RUN_MAINTENANCE_IF_MAIN;
