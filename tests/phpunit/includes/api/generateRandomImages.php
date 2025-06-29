<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Maintenance\Maintenance;

/**
 * Bootstrapping for test image file generation
 *
 * @file
 */

// Start up MediaWiki in command-line mode
require_once __DIR__ . "/../../../../maintenance/Maintenance.php";
require_once __DIR__ . "/RandomImageGenerator.php";

class GenerateRandomImages extends Maintenance {

	/** @inheritDoc */
	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$getOptSpec = [
			'minWidth::',
			'maxWidth::',
			'minHeight::',
			'maxHeight::',

			'number::',
			'format::'
		];
		$options = getopt( '', $getOptSpec );

		$format = $options['format'] ?? 'svg';
		unset( $options['format'] );

		$number = (int)( $options['number'] ?? 1 );
		unset( $options['number'] );

		$randomImageGenerator = new RandomImageGenerator( $options );
		$randomImageGenerator->writeImages( $number, $format );
	}
}

$maintClass = GenerateRandomImages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
