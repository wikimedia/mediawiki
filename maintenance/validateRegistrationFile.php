<?php

require_once __DIR__ . '/Maintenance.php';

class ValidateRegistrationFile extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'path', 'Path to extension.json/skin.json file.', true );
	}
	public function execute() {
		if ( !class_exists( 'Jsv4' ) ) {
			$this->error( 'The Jsv4 class cannot be found, please install it through composer.', 1 );
		}
		$schema = FormatJson::decode(
			file_get_contents( dirname( __DIR__ ) . '/docs/extension-schema.json' )
		);
		$path = $this->getOption( 'path' );
		$data = FormatJson::decode( file_get_contents( $path ) );
		if ( !is_object( $data ) ) {
			$this->error( "$path is not a valid JSON file.", 1 );
		}
		if ( Jsv4::isValid( $data, $schema ) ) {
			$this->output( "$path is valid!\n" );
		} else {
			$this->error( "$path does not validate.", 1 );
		}
	}
}

$maintClass = 'ValidateRegistrationFile';
require_once RUN_MAINTENANCE_IF_MAIN;
