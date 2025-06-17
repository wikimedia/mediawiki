<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Site\SiteImporter;

/**
 * Maintenance script for importing site definitions from XML into the sites table.
 *
 * @since 1.25
 *
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 */
class ImportSites extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Imports site definitions from XML into the sites table.' );

		$this->addArg( 'file', 'An XML file containing site definitions (see docs/sitelist.md). ' .
			'Use "php://stdin" to read from stdin.', true
		);
	}

	/**
	 * Do the import.
	 */
	public function execute() {
		$file = $this->getArg( 0 );

		$siteStore = $this->getServiceContainer()->getSiteStore();
		$importer = new SiteImporter( $siteStore );
		$importer->setExceptionCallback( $this->reportException( ... ) );

		$importer->importFromFile( $file );

		$this->output( "Done.\n" );
	}

	/**
	 * Outputs a message via the output() method.
	 */
	private function reportException( Exception $ex ) {
		$msg = $ex->getMessage();
		$this->output( "$msg\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ImportSites::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
