<?php

namespace MediaWiki\Settings;

use BagOStuff;
use Config;
use ExtensionRegistry;
use HashConfig;
use MediaWiki\Config\IterableConfig;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\Source\ArraySource;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\SettingsFileUtils;
use MediaWiki\Settings\Source\SettingsIncludeLocator;
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

	/** @var Config|null */
	private $config;

	/** @var SettingsSource[] */
	private $currentBatch;

	/** @var ConfigSchemaAggregator */
	private $configSchema;

	/** @var PhpIniSink */
	private $phpIniSink;

	/**
	 * Configuration that applies to SettingsBuilder itself.
	 * Initialized by the constructor, may be overwritten by regular
	 * config values. Merge strategies are currently not implemented
	 * but can be added if needed.
	 *
	 * @var array
	 */
	private $settingsConfig;

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
		$this->settingsConfig = [
			'ExtensionDirectory' => "$baseDir/extensions",
			'StyleDirectory' => "$baseDir/skins",
		];
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

		$this->currentBatch[] = $this->wrapSource( $source );

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
		return $this->load( $this->makeSource( $path ) );
	}

	/**
	 * Checks whether the given file exists relative to the settings builder's
	 * base directory.
	 *
	 * @unstable
	 *
	 * @param string $path
	 * @return bool
	 */
	public function fileExists( string $path ): bool {
		$path = SettingsFileUtils::resolveRelativeLocation( $path, $this->baseDir );
		return file_exists( $path );
	}

	/**
	 * @param SettingsSource $source
	 *
	 * @return SettingsSource
	 */
	private function wrapSource( SettingsSource $source ): SettingsSource {
		if ( $this->cache !== null && $source instanceof CacheableSource ) {
			$source = new CachedSource( $this->cache, $source );
		}
		return $source;
	}

	/**
	 * @param string $location
	 * @return SettingsSource
	 */
	private function makeSource( $location ): SettingsSource {
		// NOTE: Currently, files are the only kind of location, but we could add others.
		//       The set of supported source locations will be hard-coded here.
		//       Custom SettingsSource would have to be instantiated directly and passed to load().
		$path = SettingsFileUtils::resolveRelativeLocation( $location, $this->baseDir );

		return $this->wrapSource( new FileSource( $path ) );
	}

	/**
	 * Assert that the config loaded so far conforms the schema loaded so far.
	 *
	 * @note this is slow, so you probably don't want to do this on every request.
	 *
	 * @return StatusValue
	 */
	public function validate(): StatusValue {
		$config = $this->getConfig();
		return $this->configSchema->validateConfig( $config );
	}

	/**
	 * Return a Config object with all the default settings loaded so far.
	 *
	 * @note This will implicitly call apply()
	 *
	 * @return IterableConfig
	 */
	public function getDefaultConfig(): IterableConfig {
		$this->apply();
		return new HashConfig( $this->configSchema->getDefaults() );
	}

	/**
	 * Returns the names of all defined configuration variables
	 *
	 * @return string[]
	 */
	public function getDefinedConfigKeys(): array {
		$this->apply();
		return array_keys( $this->configSchema->getSchemas() );
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
		if ( !$this->currentBatch ) {
			return $this;
		}

		$this->assertNotFinished();
		$this->config = null;

		$allSettings = $this->loadRecursive( $this->currentBatch );

		foreach ( $allSettings as $settings ) {
			$this->configSchema->addSchemas(
				$settings['config-schema'] ?? [],
				$settings['source-name']
			);
			$this->applySettings( $settings );
		}
		$this->reset();
		return $this;
	}

	/**
	 * Loads all sources in the current batch, recursively resolving includes.
	 *
	 * @param SettingsSource[] $batch The batch of sources to load
	 * @param string[] $stack The current stack of includes, for cycle detection
	 *
	 * @return array[] an array of settings arrays
	 */
	private function loadRecursive( array $batch, array $stack = [] ): array {
		$allSettings = [];

		// Depth-first traversal of settings sources.
		foreach ( $batch as $source ) {
			$sourceName = (string)$source;

			if ( in_array( $sourceName, $stack ) ) {
				throw new SettingsBuilderException(
					'Recursive include chain detected: ' . implode( ', ', $stack )
				);
			}

			$settings = $source->load();
			$settings['source-name'] = $sourceName;

			$allSettings[] = $settings;

			$nextBatch = [];
			foreach ( $settings['includes'] ?? [] as $location ) {
				// Try to resolve the include relative to the source,
				// if the source supports that.
				if ( $source instanceof SettingsIncludeLocator ) {
					$location = $source->locateInclude( $location );
				}

				$nextBatch[] = $this->makeSource( $location );
			}

			$nextStack = array_merge( $stack, [ $settings['source-name'] ] );
			$nextSettings = $this->loadRecursive( $nextBatch, $nextStack );
			$allSettings = array_merge( $allSettings, $nextSettings );
		}

		return $allSettings;
	}

	/**
	 * Updates config settings relevant to the behavior if SettingsBuilder itself.
	 *
	 * @param array $config
	 *
	 * @return string
	 */
	private function updateSettingsConfig( $config ): string {
		foreach ( $this->settingsConfig as $key => $dummy ) {
			if ( array_key_exists( $key, $config ) ) {
				$this->settingsConfig[ $key ] = $config[ $key ];
			}
		}
		return $key;
	}

	/**
	 * Apply the settings array.
	 *
	 * @param array $settings
	 */
	private function applySettings( array $settings ) {
		// First extract config variables that change the behavior of SettingsBuilder.
		// No merge strategies are applied, defaults are set in the constructor.
		if ( isset( $settings['config'] ) ) {
			$this->updateSettingsConfig( $settings['config'] );
		}
		if ( isset( $settings['config-overrides'] ) ) {
			$this->updateSettingsConfig( $settings['config-overrides'] );
		}

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

		foreach ( $settings['config-overrides'] ?? [] as $key => $value ) {
			// no merge strategy, just override
			$this->configSink->set( $key, $value );
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

		if ( isset( $settings['extensions'] ) ) {
			$extDir = $this->settingsConfig['ExtensionDirectory'];
			foreach ( $settings['extensions'] ?? [] as $ext ) {
				$path = "$extDir/$ext/extension.json"; // see wfLoadExtension
				$this->extensionRegistry->queue( $path );
			}
		}

		if ( isset( $settings['skins'] ) ) {
			$skinDir = $this->settingsConfig['StyleDirectory'];
			foreach ( $settings['skins'] ?? [] as $skin ) {
				$path = "$skinDir/$skin/skin.json"; // see wfLoadSkin
				$this->extensionRegistry->queue( $path );
			}
		}
	}

	/**
	 * Puts a value into a config variable.
	 * Depending on the variable's specification, the new value may
	 * be merged with the previous value, or may replace it.
	 * This is a shorthand for putConfigValues( [ $key => $value ] ).
	 * @unstable
	 * @see overrideConfigValue
	 *
	 * @param string $key the name of the config setting
	 * @param mixed $value The value to set
	 *
	 * @return $this
	 */
	public function putConfigValue( string $key, $value ): self {
		return $this->putConfigValues( [ $key => $value ] );
	}

	/**
	 * Sets the value of multiple config variables.
	 * Depending on the variables' specification, the new values may
	 * be merged with the previous values, or they may replace them.
	 * This is a shorthand for loadArray( [ 'config' => $values ] ).
	 * @unstable
	 * @see overrideConfigValues
	 *
	 * @param array $values An associative array mapping names to values.
	 *
	 * @return $this
	 */
	public function putConfigValues( array $values ): self {
		return $this->loadArray( [ 'config' => $values ] );
	}

	/**
	 * Override the value of a config variable.
	 * This ignores any merge strategies and discards any previous value.
	 * This is a shorthand for overrideConfigValues( [ $key => $value ] ).
	 * @unstable
	 * @see putConfigValue
	 *
	 * @param string $key the name of the config setting
	 * @param mixed $value The value to set
	 *
	 * @return $this
	 */
	public function overrideConfigValue( string $key, $value ): self {
		return $this->overrideConfigValues( [ $key => $value ] );
	}

	/**
	 * Override the value of multiple config variables.
	 * This ignores any merge strategies and discards any previous value.
	 * This is a shorthand for loadArray( [ 'config-overrides' => $values ] ).
	 * @unstable
	 * @see putConfigValues
	 *
	 * @param array $values An associative array mapping names to values.
	 *
	 * @return $this
	 */
	public function overrideConfigValues( array $values ): self {
		return $this->loadArray( [ 'config-overrides' => $values ] );
	}

	/**
	 * Returns the config loaded so far. Implicitly triggers apply() when needed.
	 *
	 * @note This will implicitly call apply()
	 *
	 * @unstable
	 * @return Config
	 */
	public function getConfig(): Config {
		if ( $this->config && !$this->currentBatch ) {
			return $this->config;
		}

		$this->apply();
		$this->config = $this->configSink->build();

		return $this->config;
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
		$this->apply();
		$this->finished = true;
	}
}
