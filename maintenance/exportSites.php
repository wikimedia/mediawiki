<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Site\SiteExporter;

/**
 * Maintenance script for exporting site definitions from the sites table to XML.
 *
 * @since 1.25
 *
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 */
class ExportSites extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Exports site definitions from the sites table to XML file' );

		$this->addArg( 'file', 'A file to write the XML to (see docs/sitelist.md). ' .
			'Use "php://stdout" to write to stdout.', true
		);
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

		$siteLookup = $this->getServiceContainer()->getSiteLookup();
		$exporter->exportSites( $siteLookup->getSites() );

		fclose( $handle );

		$this->output( "Exported sites to " . realpath( $file ) . ".\n" );
	}

}

// @codeCoverageIgnoreStart
$maintClass = ExportSites::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
