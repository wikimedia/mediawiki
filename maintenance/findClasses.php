<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script for finding the files that contain classes
 *
 * @ingroup Maintenance
 * @since 1.37
 */
class FindClasses extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Finds the files containing classes via the autoloader.' );
	}

	public function execute() {
		$stdin = $this->getStdin();

		while ( !feof( $stdin ) ) {
			$line = fgets( $stdin );
			if ( $line === false ) {
				break;
			}
			$class = trim( $line );
			$filename = AutoLoader::find( $class );
			if ( $filename ) {
				$this->output( "$filename\n" );
			} elseif ( $class ) {
				$this->output( "#$class\n" );
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = FindClasses::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
