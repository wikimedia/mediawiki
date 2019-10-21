<?php

require_once __DIR__ . '/Maintenance.php';

class UpdateExtensionJsonSchema extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Updates extension.json files to the latest manifest_version' );
		$this->addArg( 'path', 'Location to the extension.json or skin.json you wish to convert',
			/* $required = */ true );
	}

	public function execute() {
		$filename = $this->getArg( 0 );
		if ( !is_readable( $filename ) ) {
			$this->fatalError( "Error: Unable to read $filename" );
		}

		$json = FormatJson::decode( file_get_contents( $filename ), true );
		if ( !is_array( $json ) ) {
			$this->fatalError( "Error: Invalid JSON" );
		}

		if ( !isset( $json['manifest_version'] ) ) {
			$json['manifest_version'] = 1;
		}

		if ( $json['manifest_version'] == ExtensionRegistry::MANIFEST_VERSION ) {
			$this->output( "Already at the latest version: {$json['manifest_version']}\n" );
			return;
		}

		while ( $json['manifest_version'] !== ExtensionRegistry::MANIFEST_VERSION ) {
			$json['manifest_version'] += 1;
			$func = "updateTo{$json['manifest_version']}";
			// @phan-suppress-next-line PhanUndeclaredMethod
			$this->$func( $json );
		}

		file_put_contents( $filename, FormatJson::encode( $json, "\t", FormatJson::ALL_OK ) . "\n" );
		$this->output( "Updated to {$json['manifest_version']}...\n" );
	}

	protected function updateTo2( &$json ) {
		if ( isset( $json['config'] ) ) {
			$config = $json['config'];
			$json['config'] = [];
			if ( isset( $config['_prefix'] ) ) {
				$json = wfArrayInsertAfter( $json, [
					'config_prefix' => $config['_prefix']
				], 'config' );
				unset( $config['_prefix'] );
			}

			foreach ( $config as $name => $value ) {
				if ( $name[0] !== '@' ) {
					$json['config'][$name] = [ 'value' => $value ];
					if ( isset( $value[ExtensionRegistry::MERGE_STRATEGY] ) ) {
						$json['config'][$name]['merge_strategy'] = $value[ExtensionRegistry::MERGE_STRATEGY];
						unset( $value[ExtensionRegistry::MERGE_STRATEGY] );
					}
					if ( isset( $config["@$name"] ) ) {
						// Put 'description' first for better human-legibility.
						$json['config'][$name] = array_merge(
							[ 'description' => $config["@$name"] ],
							$json['config'][$name]
						);
					}
				}
			}
		}
	}
}

$maintClass = UpdateExtensionJsonSchema::class;
require_once RUN_MAINTENANCE_IF_MAIN;
