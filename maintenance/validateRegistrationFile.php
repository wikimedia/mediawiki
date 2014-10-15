<?php

require_once __DIR__ . '/Maintenance.php';

class ValidateRegistrationFile extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'path', 'Path to extension.json/skin.json file.', true );
	}
	public function execute() {
		if ( !class_exists( 'JsonSchema\Uri\UriRetriever' ) ) {
			$this->error( 'The JsonSchema library cannot be found, please install it through composer.', 1 );
		}

		$retriever = new JsonSchema\Uri\UriRetriever();
		$schema = $retriever->retrieve('file://' . dirname( __DIR__ ) . '/docs/extension.schema.json' );
		$path = $this->getArg( 0 );
		$data = json_decode( file_get_contents( $path ) );
		if ( !is_object( $data ) ) {
			$this->error( "$path is not a valid JSON file.", 1 );
		}

		$validator = new JsonSchema\Validator();
		$validator->check( $data, $schema );
		if ( $validator->isValid() ) {
			$this->output( "$path validates against the schema!\n" );
		} else {
			foreach ( $validator->getErrors() as $error ) {
				$this->output( "[{$error['property']}] {$error['message']}\n" );
			}
			$this->error( "$path does not validate.", 1 );
		}
	}
}

$maintClass = 'ValidateRegistrationFile';
require_once RUN_MAINTENANCE_IF_MAIN;
