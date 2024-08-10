<?php

namespace MediaWiki\Settings;

use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\IterableConfig;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchema;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\GlobalConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\Source\ArraySource;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\SettingsFileUtils;
use MediaWiki\Settings\Source\SettingsIncludeLocator;
use MediaWiki\Settings\Source\SettingsSource;
use RuntimeException;
use StatusValue;
use Wikimedia\ObjectCache\BagOStuff;
use function array_key_exists;

/**
 * Builder class for constructing a Config object from a set of sources
 * during bootstrap. The SettingsBuilder is used in Setup.php to load
 * and combine settings files and eventually produce the Config object that
 * will be used to configure MediaWiki.
 *
 * The SettingsBuilder object keeps track of "stages" of initialization that
 * correspond to sections of Setup.php:
 *
 * The initial stage is "loading". In this stage, SettingsSources are added
 * to the SettingsBuilder using the load* methods. This sets up the config
 * schema and applies custom configuration values.
 *
 * Once all settings sources have been loaded, the SettingsBuilder is moved to the
 * "registration" stage by calling enterRegistrationStage().
 * In this stage, config values may still be altered, but no settings sources may
 * be loaded. During the "registration" stage, dynamic defaults are applied,
 * extension registration callbacks are executed, and maintenance scripts have an
 * opportunity to manipulate settings.
 *
 * Finally, the SettingsBuilder is moved to the "operation" stage by calling
 * enterOperationStage(). This renders the SettingsBuilder read only: config values
 * may no longer be changed. At this point, it becomes safe to use the Config object
 * returned by getConfig() to initialize the service container.
 *
 * @since 1.38
 */
class SettingsBuilder {

	/**
	 * @var int The initial stage in which settings can be loaded,
	 * but config values cannot be accessed.
	 */
	private const STAGE_LOADING = 1;

	/**
	 * @var int The intermediate stage in which settings can no longer be loaded,
	 * but config values can be accessed and manipulated programmatically.
	 */
	private const STAGE_REGISTRATION = 10;

	/**
	 * @var int The final stage in which config values can be accessed, but can
	 * no longer be changed.
	 */
	private const STAGE_READ_ONLY = 100;

	/** @var string */
	private $baseDir;

	/** @var ExtensionRegistry */
	private $extensionRegistry;

	/** @var BagOStuff */
	private $cache;

	/** @var ConfigBuilder */
	private $configSink;

	/** @var array<string,string> */
	private $obsoleteConfig;

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
	 * The stage of the settings builder. This is used to determine
	 * which settings are allowed to be changed.
	 *
	 * @var int see self::STAGE_*
	 */
	private $stage = self::STAGE_LOADING;

	/**
	 * Whether we have to apply reverse-merging when applying defaults.
	 * This will initially be false, and become true once any config settings have been
	 * assigned a value.
	 *
	 * This is used as an optimization, to avoid costly merge logic when loading initial
	 * defaults before any config variables have been set.
	 *
	 * @var bool
	 */
	private $defaultsNeedMerging = false;

	/** @var string[] */
	private $warnings = [];

	private static bool $accessDisabledForUnitTests = false;

	/**
	 * Accessor for the global SettingsBuilder instance.
	 *
	 * @note It is always preferable to have a SettingsBuilder injected!
	 *       But as long as we can't to this everywhere, this is the preferred way of
	 *       getting the global instance of SettingsBuilder.
	 *
	 * @return SettingsBuilder
	 */
	public static function getInstance(): self {
		static $instance = null;

		if ( self::$accessDisabledForUnitTests ) {
			throw new RuntimeException( 'Access is disabled in unit tests' );
		}

		if ( !$instance ) {
			// NOTE: SettingsBuilder is used during bootstrap, before MediaWikiServices
			//       is available. It has to be, because it is used to construct the
			//       configuration that is used when constructing services. Because of
			//       this, we have to instantiate SettingsBuilder directly, we can't
			//       use service wiring.
			$instance = new SettingsBuilder(
				MW_INSTALL_PATH,
				ExtensionRegistry::getInstance(),
				new GlobalConfigBuilder( 'wg' ),
				new PhpIniSink()
			);
		}

		return $instance;
	}

	/**
	 * @internal
	 */
	public static function disableAccessForUnitTests(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( 'Can only be called in tests' );
		}
		self::$accessDisabledForUnitTests = true;
	}

	/**
	 * @internal
	 */
	public static function enableAccessAfterUnitTests(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( 'Can only be called in tests' );
		}
		self::$accessDisabledForUnitTests = false;
	}

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
		$this->obsoleteConfig = [];
		$this->configSchema = new ConfigSchemaAggregator();
		$this->phpIniSink = $phpIniSink;
		$this->settingsConfig = [
			MainConfigNames::ExtensionDirectory => "$baseDir/extensions",
			MainConfigNames::StyleDirectory => "$baseDir/skins",
		];
		$this->reset();
	}

	/**
	 * Load settings from a {@link SettingsSource}.
	 * Only allowed during the "loading" stage.
	 *
	 * @param SettingsSource $source
	 * @return $this
	 */
	public function load( SettingsSource $source ): self {
		$this->assertStillLoading( __METHOD__ );

		// XXX: We may want to cache the entire batch instead, see T304493.
		$this->currentBatch[] = $this->wrapSource( $source );

		return $this;
	}

	/**
	 * Load settings from an array.
	 *
	 * @param array $newSettings
	 *
	 * @return $this
	 */
	public function loadArray( array $newSettings ): self {
		return $this->load( new ArraySource( $newSettings ) );
	}

	/**
	 * Load settings from an array.
	 * For internal use. Allowed during "loading" and "registration" stage.
	 *
	 * @param array $newSettings
	 * @param string $func
	 *
	 * @return $this
	 */
	private function loadArrayInternal( array $newSettings, string $func ): self {
		$this->assertNotReadOnly( $func );

		$source = new ArraySource( $newSettings );
		$this->currentBatch[] = $this->wrapSource( $source );

		return $this;
	}

	/**
	 * Load settings from a file.
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
	 * Detect usage of deprecated settings. A setting is counted as used if
	 * it has a value other than the default. Note that deprecated settings are
	 * expected to be supported. Settings that have become non-functional should
	 * be marked as obsolete instead.
	 *
	 * @note this is slow, so you probably don't want to do this on every request.
	 * @note Code that needs to call detectDeprecatedConfig() should probably also
	 *       call detectObsoleteConfig() and getWarnings().
	 *
	 * @return array<string,string> an associative array mapping config keys
	 *         to the deprecation messages from the schema.
	 */
	public function detectDeprecatedConfig(): array {
		$config = $this->getConfig();
		$keys = $this->getDefinedConfigKeys();
		$deprecated = [];

		foreach ( $keys as $key ) {
			$sch = $this->configSchema->getSchemaFor( $key );
			if ( !isset( $sch['deprecated'] ) ) {
				continue;
			}

			$default = $sch['default'] ?? null;
			$value = $config->get( $key );

			if ( $value !== $default ) {
				$deprecated[$key] = $sch['deprecated'];
			}
		}

		return $deprecated;
	}

	/**
	 * Detect usage of obsolete settings. A setting is counted as used if it is
	 * defined in any way. Note that obsolete settings are non-functional, while
	 * deprecated settings are still supported.
	 *
	 * @note this is slow, so you probably don't want to do this on every request.
	 * @note Code that calls detectObsoleteConfig() may also want to
	 *       call detectDeprecatedConfig() and getWarnings().
	 *
	 * @return array<string,string> an associative array mapping config keys
	 *         to the deprecation messages from the schema.
	 */
	public function detectObsoleteConfig(): array {
		$config = $this->getConfig();
		$obsolete = [];

		foreach ( $this->obsoleteConfig as $key => $msg ) {
			if ( $config->has( $key ) ) {
				$obsolete[$key] = $msg;
			}
		}

		return $obsolete;
	}

	/**
	 * Return a Config object with default for all settings from all schemas loaded so far.
	 * If the schema for a setting doesn't specify a default, null is assumed.
	 *
	 * @note This will implicitly call apply()
	 *
	 * @return IterableConfig
	 */
	public function getDefaultConfig(): IterableConfig {
		$this->apply();
		$defaults = $this->configSchema->getDefaults();
		$nulls = array_fill_keys( $this->configSchema->getDefinedKeys(), null );

		return new HashConfig( array_merge( $nulls, $defaults ) );
	}

	/**
	 * Return the configuration schema.
	 *
	 * @note This will implicitly call apply()
	 *
	 * @return ConfigSchema
	 */
	public function getConfigSchema(): ConfigSchema {
		$this->apply();
		return $this->configSchema;
	}

	/**
	 * Returns the names of all defined configuration variables
	 *
	 * @return string[]
	 */
	public function getDefinedConfigKeys(): array {
		$this->apply();
		return $this->configSchema->getDefinedKeys();
	}

	/**
	 * Apply any settings loaded so far to the runtime environment.
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

		$this->assertNotReadOnly( __METHOD__ );
		$this->config = null;

		// XXX: We may want to cache the entire batch after merging together
		//      settings from all sources, see T304493.
		$allSettings = $this->loadRecursive( $this->currentBatch );

		foreach ( $allSettings as $settings ) {
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
		// No merge strategies are applied, defaults are set in the constructor.
		foreach ( $this->settingsConfig as $key => $dummy ) {
			if ( array_key_exists( $key, $config ) ) {
				$this->settingsConfig[ $key ] = $config[ $key ];
			}
		}
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable,PhanPossiblyUndeclaredVariable Always set
		return $key;
	}

	/**
	 * Notify SettingsBuilder that it can no longer assume that is has full knowledge of
	 * all configuration variables that have been set. This would be the case when other code
	 * (such as LocalSettings.php) is manipulating global variables which represent config
	 * values.
	 *
	 * This is used for optimization: up until this method is called, default values can be set
	 * directly for any config values that have not been set yet. This avoids the need to
	 * run merge logic for all default values during initialization.
	 *
	 * @note It is useful to call apply() just before this method, so any settings already queued
	 * will still benefit from assuming that globals are not dirty.
	 *
	 * @return self
	 */
	public function assumeDirtyConfig(): SettingsBuilder {
		$this->defaultsNeedMerging = true;
		return $this;
	}

	/**
	 * Apply schemas from the settings array.
	 *
	 * This returns the default values to apply, splits into two two categories:
	 * "hard" defaults, which can be applied as config overrides without merging.
	 * And "soft" defaults, which have to be reverse-merged.
	 * Defaults can be considered "hard" if no config value was yet set for them. However,
	 * we can only know that as long as we can be sure that nothing has changed config values
	 * in a way that bypasses SettingsLoader (e.g. by setting global variables in LocalSettings.php).
	 *
	 * @param array $settings A settings structure.
	 */
	private function applySchemas( array $settings ) {
		$defaults = [];

		if ( isset( $settings['config-schema-inverse'] ) ) {
			$defaults = $settings['config-schema-inverse']['default'] ?? [];
			$this->configSchema->addDefaults(
				$defaults,
				$settings['source-name']
			);
			$this->configSchema->addMergeStrategies(
				$settings['config-schema-inverse']['mergeStrategy'] ?? [],
				$settings['source-name']
			);
			$this->configSchema->addTypes(
				$settings['config-schema-inverse']['type'] ?? [],
				$settings['source-name']
			);
			$this->configSchema->addDynamicDefaults(
				$settings['config-schema-inverse']['dynamicDefault'] ?? [],
				$settings['source-name']
			);
		}

		if ( isset( $settings['config-schema'] ) ) {
			foreach ( $settings['config-schema'] as $key => $schema ) {
				$this->configSchema->addSchema( $key, $schema );

				if ( $this->configSchema->hasDefaultFor( $key ) ) {
					$defaults[$key] = $this->configSchema->getDefaultFor( $key );
				}
			}
		}

		if ( $this->defaultsNeedMerging ) {
			$mergeStrategies = $this->configSchema->getMergeStrategies();
			$this->configSink->setMultiDefault( $defaults, $mergeStrategies );
		} else {
			// Optimization: no merge strategy, just override in one go
			$this->configSink->setMulti( $defaults );
		}
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

		$this->applySchemas( $settings );

		if ( isset( $settings['config'] ) ) {
			$mergeStrategies = $this->configSchema->getMergeStrategies();
			$this->configSink->setMulti( $settings['config'], $mergeStrategies );
		}

		if ( isset( $settings['config-overrides'] ) ) {
			// no merge strategies, just override in one go
			$this->configSink->setMulti( $settings['config-overrides'] );
		}

		if ( isset( $settings['obsolete-config'] ) ) {
			$this->obsoleteConfig = array_merge( $this->obsoleteConfig, $settings['obsolete-config'] );
		}

		if ( isset( $settings['config'] ) || isset( $settings['config-overrides'] ) ) {
			// We have set some config variables, we can no longer assume we can blindly set defaults
			// without merging with existing config variables.
			// XXX: We could potentially track which config variables have been set, so we can still
			//      apply defaults for other config vars without merging.
			$this->defaultsNeedMerging = true;
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
			$extDir = $this->settingsConfig[MainConfigNames::ExtensionDirectory];
			foreach ( $settings['extensions'] ?? [] as $ext ) {
				$path = "$extDir/$ext/extension.json"; // see wfLoadExtension
				$this->extensionRegistry->queue( $path );
			}
		}

		if ( isset( $settings['skins'] ) ) {
			$skinDir = $this->settingsConfig[MainConfigNames::StyleDirectory];
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
	 *
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
	 *
	 * @see overrideConfigValues
	 *
	 * @param array $values An associative array mapping names to values.
	 *
	 * @return $this
	 */
	public function putConfigValues( array $values ): self {
		return $this->loadArrayInternal( [ 'config' => $values ], __METHOD__ );
	}

	/**
	 * Override the value of a config variable.
	 * This ignores any merge strategies and discards any previous value.
	 * This is a shorthand for overrideConfigValues( [ $key => $value ] ).
	 *
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
	 *
	 * @see putConfigValues
	 *
	 * @param array $values An associative array mapping names to values.
	 *
	 * @return $this
	 */
	public function overrideConfigValues( array $values ): self {
		return $this->loadArrayInternal( [ 'config-overrides' => $values ], __METHOD__ );
	}

	/**
	 * Register hook handlers.
	 *
	 * @param array<string,mixed> $handlers An associative array using the same structure
	 *        as the Hooks config setting:
	 *        Each value is a list of handler callbacks for the hook.
	 *
	 * @return $this
	 * @see HookContainer::register()
	 */
	public function registerHookHandlers( array $handlers ): self {
		// NOTE: Rely on the merge strategy for the Hooks setting.
		// TODO: Make hook handlers a separate structure in settings files,
		//       like they are in extension.json.
		return $this->loadArrayInternal( [ 'config' => [ 'Hooks' => $handlers ] ], __METHOD__ );
	}

	/**
	 * Register a hook handler.
	 *
	 * @param string $hook
	 * @param mixed $handler
	 *
	 * @return $this
	 * @see HookContainer::register()
	 */
	public function registerHookHandler( string $hook, $handler ): self {
		// NOTE: Rely on the merge strategy for the Hooks setting.
		// TODO: Make hook handlers a separate structure in settings files,
		//       like they are in extension.json.
		return $this->loadArray( [ 'config' => [ 'Hooks' => [ $hook => [ $handler ] ] ] ] );
	}

	/**
	 * Returns the config loaded so far. Implicitly triggers apply() when needed.
	 *
	 * @note This will implicitly call apply()
	 *
	 * @return Config
	 */
	public function getConfig(): Config {
		// XXX: Would be nice if we could forbid using this method
		//   before enterRegistrationStage() is called. But we need
		//   access to some configuration earlier, e.g. WikiFarmSettingsDirectory.

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

	private function assertNotReadOnly( string $func ): void {
		if ( $this->stage === self::STAGE_READ_ONLY ) {
			throw new SettingsBuilderException(
				"$func not supported in operation stage."
			);
		}
	}

	private function assertStillLoading( string $func ): void {
		if ( $this->stage !== self::STAGE_LOADING ) {
			throw new SettingsBuilderException(
				"$func only supported while still in the loading stage."
			);
		}
	}

	/**
	 * Sets the SettingsBuilder read-only.
	 *
	 * Call this before using the configuration returned by getConfig() to construct services objects
	 * or initialize the service container.
	 *
	 * @internal For use in Setup.php.
	 */
	public function enterReadOnlyStage(): void {
		$this->apply();
		$this->stage = self::STAGE_READ_ONLY;
	}

	/**
	 * Prevents additional settings from being loaded, but still allows manipulation of config values.
	 *
	 * Call this before applying dynamic defaults and executing extension registration callbacks.
	 *
	 * @internal For use in Setup.php.
	 */
	public function enterRegistrationStage(): void {
		$this->apply();
		$this->stage = self::STAGE_REGISTRATION;
	}

	/**
	 * @internal For use in Setup.php, pending a better solution.
	 * @return ConfigBuilder
	 */
	public function getConfigBuilder(): ConfigBuilder {
		$this->apply();
		return $this->configSink;
	}

	/**
	 * Log a settings related warning, such as a deprecated config variable.
	 *
	 * This can be used during bootstrapping, when the regular logger is not yet available.
	 * The warnings will be passed to a regular logger after bootstrapping is complete.
	 * In addition, the updater will fail if it finds any warnings.
	 * This allows us to warn about deprecated settings, and make sure they are
	 * replaced before the update proceeds.
	 *
	 * @param string $msg
	 */
	public function warning( string $msg ) {
		$this->assertNotReadOnly( __METHOD__ );
		$this->warnings[] = trim( $msg );
	}

	/**
	 * Returns any warnings logged by calling warning().
	 *
	 * @internal
	 * @return string[]
	 */
	public function getWarnings(): array {
		return $this->warnings;
	}

}
