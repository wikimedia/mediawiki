<?php

namespace MediaWiki\Settings;

use MediaWiki\Settings\Config\ConfigSink;

/**
 * Utility for loading settings files.
 * @since 1.38
 */
class SettingsBuilder {

	/** @var string */
	private $baseDir;

	/** @var ConfigSink */
	private $configSink;

	/** @var array */
	private $settings;

	/**
	 * @param string $baseDir
	 * @param ConfigSink $configSink
	 */
	public function __construct(
		string $baseDir,
		ConfigSink $configSink
	) {
		$this->baseDir = $baseDir;
		$this->configSink = $configSink;
		$this->reset();
	}

	/**
	 * Load settings from a file.
	 *
	 * @unstable
	 *
	 * @param string $source
	 * @return $this
	 */
	public function loadFile( string $source ): self {
		$newSettings = $this->readSettingsFile( $source );

		return $this->loadArray( $newSettings );
	}

	/**
	 * Load settings from aa array.
	 *
	 * @unstable
	 *
	 * @param array $newSettings
	 * @param string $sourceName
	 *
	 * @return $this
	 */
	public function loadArray( array $newSettings, $sourceName = '<array>' ): self {
		$this->settings['config'] =
			array_merge( $this->settings['config'], $newSettings['config'] ?? [] );

		$schemaOverrides = array_intersect_key(
			$this->settings['config-schema'],
			$newSettings['config-schema'] ?? []
		);
		if ( !empty( $schemaOverrides ) ) {
			throw new SettingsBuilderException( 'Overriding config schema in {source}', [
				'source' => $sourceName,
				'override_keys' => implode( ',', array_keys( $schemaOverrides ) ),
			] );
		}
		$this->settings['config-schema'] =
			array_merge( $this->settings['config-schema'], $newSettings['config-schema'] ?? [] );

		return $this;
	}

	/**
	 * Apply any settings loaded so far to the runtime environment.
	 *
	 * @unstable
	 *
	 * @note This usually makes all configuration available in global variables.
	 * This may however not be the case in the future.
	 */
	public function apply() {
		foreach ( $this->settings['config'] as $key => $value ) {
			$this->configSink->set( $key, $value );
		}

		foreach ( $this->settings['config-schema'] as $key => $schema ) {
			if ( array_key_exists( 'default', $schema ) ) {
				$this->configSink->setIfNotDefined( $key, $schema['default'] );
			}
		}

		$this->reset();
	}

	private function reset() {
		$this->settings = [
			'config' => [],
			'config-schema' => [],
		];
	}

	/**
	 * @param string $path
	 *
	 * @return array
	 */
	private function readSettingsFile( string $path ): array {
		$fullPath = $this->baseDir . '/' . $path;

		if ( !file_exists( $fullPath ) ) {
			throw new SettingsBuilderException( "settings file '{path}' does not exist", [
				'path' => $fullPath
			] );
		}

		$json = file_get_contents( $fullPath );
		$newSettings = json_decode( $json, true );

		if ( !is_array( $newSettings ) ) {
			throw new SettingsBuilderException( "failed to decode JSON from '$fullPath'" );
		}

		return $newSettings;
	}
}
