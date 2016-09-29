<?php

require_once __DIR__ . '/Maintenance.php';

use Composer\Spdx\SpdxLicenses;
use JsonSchema\Validator;

class ValidateRegistrationFile extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'path', 'Path to extension.json/skin.json file.', true );
	}
	public function execute() {
		if ( !class_exists( Validator::class ) ) {
			$this->error( 'The JsonSchema library cannot be found, please install it through composer.', 1 );
		} elseif ( !class_exists( SpdxLicenses::class ) ) {
			$this->error(
				'The spdx-licenses library cannot be found, please install it through composer.', 1
			);
		}

		$path = $this->getArg( 0 );
		$data = json_decode( file_get_contents( $path ) );
		if ( !is_object( $data ) ) {
			$this->error( "$path is not a valid JSON file.", 1 );
		}
		if ( !isset( $data->manifest_version ) ) {
			$this->output( "Warning: No manifest_version set, assuming 1.\n" );
			// For backwards-compatability assume 1
			$data->manifest_version = 1;
		}
		$version = $data->manifest_version;
		if ( $version !== ExtensionRegistry::MANIFEST_VERSION ) {
			$schemaPath = dirname( __DIR__ ) . "/docs/extension.schema.v$version.json";
		} else {
			$schemaPath = dirname( __DIR__ ) . '/docs/extension.schema.json';
		}

		if ( $version < ExtensionRegistry::OLDEST_MANIFEST_VERSION
			|| $version > ExtensionRegistry::MANIFEST_VERSION
		) {
			$this->error( "Error: $path is using a non-supported schema version, it should use "
				. ExtensionRegistry::MANIFEST_VERSION, 1 );
		} elseif ( $version < ExtensionRegistry::MANIFEST_VERSION ) {
			$this->output( "Warning: $path is using a deprecated schema, and should be updated to "
				. ExtensionRegistry::MANIFEST_VERSION . "\n" );
		}

		$licenseError = false;
		// Check if it's a string, if not, schema validation will display an error
		if ( isset( $data->{'license-name'} ) && is_string( $data->{'license-name'} ) ) {
			$licenses = new SpdxLicenses();
			$valid = $licenses->validate( $data->{'license-name'} );
			if ( !$valid ) {
				$licenseError = '[license-name] Invalid SPDX license identifier, '
					. 'see <https://spdx.org/licenses/>';
			}
		}

		$validator = new Validator;
		$validator->check( $data, (object) [ '$ref' => 'file://' . $schemaPath ] );
		if ( $validator->isValid() && !$licenseError ) {
			$this->output( "$path validates against the version $version schema!\n" );
		} else {
			foreach ( $validator->getErrors() as $error ) {
				$this->output( "[{$error['property']}] {$error['message']}\n" );
			}
			if ( $licenseError ) {
				$this->output( "$licenseError\n" );
			}
			$this->error( "$path does not validate.", 1 );
		}
	}
}

$maintClass = 'ValidateRegistrationFile';
require_once RUN_MAINTENANCE_IF_MAIN;
