<?php

namespace MediaWiki\Settings;

use BagOStuff;
use ExtensionRegistry;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\Source\ArraySource;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\SettingsSource;
use StatusValue;

/**
 * Utility for loading settings files.
 * @since 1.38
 */
class SettingsBuilder {
	/** @var string */
	private $baseDir;

	/** @var ExtensionRegistry */
	private $extensionRegistry;

	/** @var BagOStuff */
	private $cache;

	/** @var ConfigBuilder */
	private $configSink;

	/** @var array */
	private $currentBatch;

	/** @var ConfigSchemaAggregator */
	private $configSchema;

	/** @var PhpIniSink */
	private $phpIniSink;

	/**
	 * When we're done applying all settings.
	 *
	 * @var bool
	 */
	private $finished = false;

	/**
	 * @param string $baseDir
	 * @param ExtensionRegistry $extensionRegistry
	 * @param ConfigBuilder $configSink
	 * @param PhpIniSink $phpIniSink
	 * @param BagOStuff|null $cache BagOStuff used to cache settings loaded
	 *  from each source. The caller should beware that secrets contained in
	 *  any source passed to {@link load} or {@link loadFile} will be cached as
	 *  well.
	 */
	public function __construct(
		string $baseDir,
		ExtensionRegistry $extensionRegistry,
		ConfigBuilder $configSink,
		PhpIniSink $phpIniSink,
		BagOStuff $cache = null
	) {
		$this->baseDir = $baseDir;
		$this->extensionRegistry = $extensionRegistry;
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
		$this->assertNotFinished();

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
	 * Assert that the config loaded so far conforms the schema loaded so far.
	 *
	 * @note this is slow, so you probably don't want to do this on every request.
	 *
	 * @return StatusValue
	 */
	public function validate(): StatusValue {
		$config = $this->configSink->build();
		$validator = new Validator();
		$result = StatusValue::newGood();

		foreach ( $this->configSchema->getSchemas() as $key => $schema ) {
			// All config keys present in the schema must be set.
			if ( !$config->has( $key ) ) {
				$result->fatal( 'config-missing-key', $key );
				continue;
			}

			$value = $config->get( $key );
			$validator->validate(
				$value,
				$schema,
				Constraint::CHECK_MODE_TYPE_CAST
			);
			if ( !$validator->isValid() ) {
				foreach ( $validator->getErrors() as $error ) {
					$result->fatal( 'config-invalid-key', $key, $error['message'] );
				}
			}
			$validator->reset();
		}
		return $result;
	}

	/**
	 * Apply any settings loaded so far to the runtime environment.
	 *
	 * @unstable
	 *
	 * @note This usually makes all configuration available in global variables.
	 * This may however not be the case in the future.
	 *
	 * @return $this
	 * @throws SettingsBuilderException
	 */
	public function apply(): self {
		$this->assertNotFinished();

		foreach ( $this->currentBatch as $source ) {
			$settings = $source->load();
			$this->configSchema->addSchemas( $settings['config-schema'] ?? [], (string)$source );
			$this->applySettings( $settings );
		}
		$this->reset();
		return $this;
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

		// TODO: Closely integrate with ExtensionRegistry. Loading extension.json is basically
		//       the same as loading settings files. See T297166.
		//       That would also mean that extensions would actually be loaded here,
		//       not just queued. We can't do this right now, because we need to preserve
		//       interoperability with wfLoadExtension() being called from LocalSettings.php.

		$config = $this->configSink->build();

		if ( isset( $settings['extensions'] ) ) {
			$extDir = $config->get( 'ExtensionDirectory' );
			foreach ( $settings['extensions'] ?? [] as $ext ) {
				$path = "$extDir/$ext/extension.json"; // see wfLoadExtension
				$this->extensionRegistry->queue( $path );
			}
		}

		if ( isset( $settings['skins'] ) ) {
			$skinDir = $config->get( 'StyleDirectory' );
			foreach ( $settings['skins'] ?? [] as $skin ) {
				$path = "$skinDir/$skin/skin.json"; // see wfLoadSkin
				$this->extensionRegistry->queue( $path );
			}
		}
	}

	private function reset() {
		$this->currentBatch = [];
	}

	private function assertNotFinished(): void {
		if ( $this->finished ) {
			throw new SettingsBuilderException(
				"Tried applying settings too late after applying settings have finalized."
			);
		}
	}

	/**
	 * Settings can't be loaded & applied after calling this
	 * method.
	 *
	 * @internal Most likely called only in Setup.php.
	 *
	 * @return void
	 */
	public function finalize(): void {
		$this->finished = true;
	}
}
