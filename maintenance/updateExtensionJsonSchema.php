<?php

use Composer\Semver\VersionParser;

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
			$this->$func( $json );
		}

		$this->updateRequiredMwVersion( $json );

		file_put_contents( $filename, FormatJson::encode( $json, "\t", FormatJson::ALL_OK ) . "\n" );
		$this->output( "Updated to {$json['manifest_version']}...\n" );
	}

	/**
	 * @param array &$json
	 */
	protected function updateRequiredMwVersion( &$json ) {
		if ( !isset( $json['requires'] ) ) {
			$json['requires'] = [];
		}

		$needNewVersion = true;

		// When version is set, parse it and compare against requirement for new manifest
		if ( isset( $json['requires'][ExtensionRegistry::MEDIAWIKI_CORE] ) ) {
			$versionParser = new VersionParser();
			$currentRequired = $versionParser->parseConstraints(
				// @phan-suppress-next-line PhanTypeInvalidDimOffset isset check exists
				$json['requires'][ExtensionRegistry::MEDIAWIKI_CORE]
			);
			$newRequired = $versionParser->parseConstraints(
				// The match works only when using an equal comparision
				str_replace( '>=', '==', ExtensionRegistry::MANIFEST_VERSION_MW_VERSION )
			);
			if ( !$currentRequired->matches( $newRequired ) ) {
				$needNewVersion = false;
			}
		}

		if ( $needNewVersion ) {
			// Set or update a requirement on the MediaWiki version
			// that the current MANIFEST_VERSION was introduced in.
			$json['requires'][ExtensionRegistry::MEDIAWIKI_CORE] =
				ExtensionRegistry::MANIFEST_VERSION_MW_VERSION;
		}
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
						unset( $json['config'][$name]['value'][ExtensionRegistry::MERGE_STRATEGY] );
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

		// Re-maps top level keys under attributes
		$attributes = [
			'CodeMirrorPluginModules' => [ 'CodeMirror', 'PluginModules' ],
			'CodeMirrorTagModes' => [ 'CodeMirror', 'TagModes' ],
			'EventLoggingSchemas' => [ 'EventLogging', 'Schemas' ],
			'SyntaxHighlightModels' => [ 'SyntaxHighlight', 'Models' ],
			'VisualEditorAvailableContentModels' => [ 'VisualEditor', 'AvailableContentModels' ],
			'VisualEditorAvailableNamespaces' => [ 'VisualEditor', 'AvailableNamespaces' ],
			'VisualEditorPreloadModules' => [ 'VisualEditor', 'PreloadModules' ],
			'VisualEditorPluginModules' => [ 'VisualEditor', 'PluginModules' ],
		];

		foreach ( $attributes as $name => $value ) {
			if ( !isset( $json[$name] ) ) {
				continue;
			}

			$json['attributes'][$value[0]][$value[1]] = $json[$name];
			unset( $json[$name] );
		}
	}
}

$maintClass = UpdateExtensionJsonSchema::class;
require_once RUN_MAINTENANCE_IF_MAIN;
