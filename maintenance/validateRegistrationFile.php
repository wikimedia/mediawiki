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

		$path = $this->getArg( 0 );
		$data = json_decode( file_get_contents( $path ) );
		if ( !is_object( $data ) ) {
			$this->error( "$path is not a valid JSON file.", 1 );
		}
		if ( !isset( $data['manifest_version'] ) ) {
			$this->error( "$path does not have a manifest_version set." );
		}
		$version = $data['manifest_version'];
		if ( $version !== ExtensionRegistry::MANIFEST_VERSION ) {
			$schemaPath = dirname( __DIR__ ) . "/docs/extension.schema.v$version.json";
		} else {
			$schemaPath = dirname( __DIR__ ) . '/docs/extension.schema.json';
		}

		if ( $version < ExtensionRegistry::MANIFEST_VERSION ) {
			$this->output( "Warning: $path is using a deprecated schema, and should be updated to "
				. ExtensionRegistry::MANIFEST_VERSION );
			$retriever = new JsonSchema\Uri\UriRetriever();
		$schema = $retriever->retrieve('file://' . $schemaPath );

		$validator = new JsonSchema\Validator();
		$validator->check( $data, $schema );
		if ( $validator->isValid() ) {
			$this->output( "$path validates against the version $version schema!\n" );
			}
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
