<?php

require_once __DIR__ . '/Maintenance.php';

class ValidateRegistrationFile extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg(
			'path',
			'Path or glob pattern to extension.json/skin.json file.',
			true
		);
	}

	public function execute() {
		$validator = new ExtensionJsonValidator( function ( $msg ) {
			$this->fatalError( $msg );
		} );
		$validator->checkDependencies();
		$paths = glob( $this->getArg( 0 ) );
		foreach ( $paths as $path ) {
			try {
				$validator->validate( $path );
				$this->output( "$path validates against the schema!\n" );
			} catch ( ExtensionJsonValidationError $e ) {
				$this->fatalError( $e->getMessage() );
			}
		}
	}
}

$maintClass = ValidateRegistrationFile::class;
require_once RUN_MAINTENANCE_IF_MAIN;
