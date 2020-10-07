<?php

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

/**
 * Maintenance script for exporting site definitions from XML into the sites table.
 *
 * @since 1.25
 *
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 */
class ExportSites extends Maintenance {

	public function __construct() {
		$this->addDescription( 'Exports site definitions the sites table to XML file' );

		$this->addArg( 'file', 'A file to write the XML to (see docs/sitelist.md). ' .
			'Use "php://stdout" to write to stdout.', true
		);

		parent::__construct();
	}

	/**
	 * Do the actual work. All child classes will need to implement this
	 */
	public function execute() {
		$file = $this->getArg( 0 );

		if ( $file === 'php://output' || $file === 'php://stdout' ) {
			$this->mQuiet = true;
		}

		$handle = fopen( $file, 'w' );

		if ( !$handle ) {
			$this->fatalError( "Failed to open $file for writing.\n" );
		}

		$exporter = new SiteExporter( $handle );

		$siteLookup = \MediaWiki\MediaWikiServices::getInstance()->getSiteLookup();
		$exporter->exportSites( $siteLookup->getSites() );

		fclose( $handle );

		$this->output( "Exported sites to " . realpath( $file ) . ".\n" );
	}

}

$maintClass = ExportSites::class;
require_once RUN_MAINTENANCE_IF_MAIN;
