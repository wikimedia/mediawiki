<?php

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

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
		$this->addDescription( 'Imports site definitions from XML into the sites table.' );

		$this->addArg( 'file', 'An XML file containing site definitions (see docs/sitelist.md). ' .
			'Use "php://stdin" to read from stdin.', true
		);

		parent::__construct();
	}

	/**
	 * Do the import.
	 */
	public function execute() {
		$file = $this->getArg( 0 );

		$siteStore = \MediaWiki\MediaWikiServices::getInstance()->getSiteStore();
		$importer = new SiteImporter( $siteStore );
		$importer->setExceptionCallback( [ $this, 'reportException' ] );

		$importer->importFromFile( $file );

		$this->output( "Done.\n" );
	}

	/**
	 * Outputs a message via the output() method.
	 *
	 * @param Exception $ex
	 */
	public function reportException( Exception $ex ) {
		$msg = $ex->getMessage();
		$this->output( "$msg\n" );
	}
}

$maintClass = ImportSites::class;
require_once RUN_MAINTENANCE_IF_MAIN;
