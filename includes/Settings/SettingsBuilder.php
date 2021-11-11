<?php

namespace MediaWiki\Settings;

use MediaWiki\Settings\Config\ConfigSink;
use MediaWiki\Settings\Source\ArraySource;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\SettingsSource;

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
	 * Load settings from a {@link SettingsSource}.
	 *
	 * @unstable
	 *
	 * @param SettingsSource $source
	 * @return $this
	 */
	public function load( SettingsSource $source ): self {
		$newSettings = $source->load();

		$this->settings['config'] =
			array_merge( $this->settings['config'], $newSettings['config'] ?? [] );

		$schemaOverrides = array_intersect_key(
			$this->settings['config-schema'],
			$newSettings['config-schema'] ?? []
		);
		if ( !empty( $schemaOverrides ) ) {
			throw new SettingsBuilderException( 'Overriding config schema in {source}', [
				'source' => $source,
				'override_keys' => implode( ',', array_keys( $schemaOverrides ) ),
			] );
		}
		$this->settings['config-schema'] =
			array_merge( $this->settings['config-schema'], $newSettings['config-schema'] ?? [] );

		return $this;
	}

	/**
	 * Load settings from an array.
	 *
	 * @unstable
	 *
	 * @param array $newSettings
	 *
	 * @return $this
	 */
	public function loadArray( array $newSettings ): self {
		return $this->load( new ArraySource( $newSettings ) );
	}

	/**
	 * Load settings from a file.
	 *
	 * @unstable
	 *
	 * @param string $path
	 * @return $this
	 */
	public function loadFile( string $path ): self {
		// Qualify the path if it isn't already absolute
		if ( !preg_match( '!^[a-zA-Z]:\\\\!', $path ) && $path[0] != DIRECTORY_SEPARATOR ) {
			$path = $this->baseDir . DIRECTORY_SEPARATOR . $path;
		}

		return $this->load( new FileSource( $path ) );
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
				$this->configSink->setDefault( $key, $schema['default'] );
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
}
