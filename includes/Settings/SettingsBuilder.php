<?php

namespace MediaWiki\Settings;

use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\ConfigSink;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\Source\ArraySource;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\SettingsSource;
use Psr\SimpleCache\CacheInterface;

/**
 * Utility for loading settings files.
 * @since 1.38
 */
class SettingsBuilder {
	/** @var string */
	private $baseDir;

	/** @var CacheInterface */
	private $cache;

	/** @var ConfigSink */
	private $configSink;

	/** @var array */
	private $currentBatch;

	/** @var ConfigSchemaAggregator */
	private $configSchema;

	/** @var PhpIniSink */
	private $phpIniSink;

	/**
	 * @param string $baseDir
	 * @param ConfigSink $configSink
	 * @param PhpIniSink $phpIniSink
	 * @param CacheInterface|null $cache PSR-16 compliant cache interface used
	 *  to cache settings loaded from each source. The caller should beware
	 *  that secrets contained in any source passed to {@link load} or {@link
	 *  loadFile} will be cached as well.
	 */
	public function __construct(
		string $baseDir,
		ConfigSink $configSink,
		PhpIniSink $phpIniSink,
		CacheInterface $cache = null
	) {
		$this->baseDir = $baseDir;
		$this->cache = $cache;
		$this->configSink = $configSink;
		$this->configSchema = new ConfigSchemaAggregator();
		$this->phpIniSink = $phpIniSink;
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
		if ( $this->cache !== null && $source instanceof CacheableSource ) {
			$source = new CachedSource( $this->cache, $source );
		}

		$this->currentBatch[] = $source;

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
		foreach ( $this->currentBatch as $source ) {
			$settings = $source->load();
			$this->configSchema->addSchemas( $settings['config-schema'] ?? [], (string)$source );
			$this->applySettings( $settings );
		}
		$this->reset();
	}

	/**
	 * Apply the settings array.
	 *
	 * @param array $settings
	 */
	private function applySettings( array $settings ) {
		foreach ( $settings['config'] ?? [] as $key => $value ) {
			$this->configSink->set(
				$key,
				$value,
				$this->configSchema->getMergeStrategyFor( $key )
			);
		}

		foreach ( $settings['config-schema'] ?? [] as $key => $schema ) {
			if ( $this->configSchema->hasDefaultFor( $key ) ) {
				$this->configSink->setDefault(
					$key,
					$this->configSchema->getDefaultFor( $key ),
					$this->configSchema->getMergeStrategyFor( $key )
				);
			}
		}

		foreach ( $settings['php-ini'] ?? [] as $option => $value ) {
			$this->phpIniSink->set(
				$option,
				$value
			);
		}
	}

	private function reset() {
		$this->currentBatch = [];
	}
}
