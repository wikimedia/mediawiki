<?php

namespace MediaWiki\Registration;

use AutoLoader;
use Composer\Semver\Semver;
use InvalidArgumentException;
use LogicException;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\DomainEventSubscriber;
use MediaWiki\Exception\ShellDisabledError;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Shell\Shell;
use MediaWiki\WikiMap\WikiMap;
use ObjectCacheFactory;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ScopedCallback;

/**
 * @defgroup ExtensionRegistry ExtensionRegistry
 *
 * For higher level documentation, see <https://www.mediawiki.org/wiki/Manual:Extension_registration/Architecture>.
 */

/**
 * Load JSON files, and uses a Processor to extract information.
 *
 * This also adds the extension's classes to the AutoLoader.
 *
 * @ingroup ExtensionRegistry
 * @since 1.25
 */
class ExtensionRegistry implements DomainEventSubscriber {

	/**
	 * "requires" key that applies to MediaWiki core
	 */
	public const MEDIAWIKI_CORE = 'MediaWiki';

	/**
	 * Version of the highest supported manifest version
	 * Note: Update MANIFEST_VERSION_MW_VERSION when changing this
	 */
	public const MANIFEST_VERSION = 2;

	/**
	 * MediaWiki version constraint representing what the current
	 * highest MANIFEST_VERSION is supported in
	 */
	public const MANIFEST_VERSION_MW_VERSION = '>= 1.29.0';

	/**
	 * Version of the oldest supported manifest version
	 */
	public const OLDEST_MANIFEST_VERSION = 1;

	/**
	 * Bump whenever the registration cache needs resetting
	 */
	private const CACHE_VERSION = 8;

	private const CACHE_EXPIRY = 60 * 60 * 24;

	/**
	 * Special key that defines the merge strategy
	 *
	 * @since 1.26
	 */
	public const MERGE_STRATEGY = '_merge_strategy';

	/**
	 * Attributes that should be lazy-loaded
	 */
	private const LAZY_LOADED_ATTRIBUTES = [
		'TrackingCategories',
		'QUnitTestModule',
		'SkinLessImportPaths',
	];

	/**
	 * Array of loaded things, keyed by name, values are credits information.
	 *
	 * The keys that the credit info arrays may have is defined
	 * by ExtensionProcessor::CREDIT_ATTRIBS (plus a 'path' key that
	 * points to the skin or extension JSON file).
	 *
	 * This info may be accessed via ExtensionRegistry::getAllThings.
	 *
	 * @var array[]
	 */
	private $loaded = [];

	/**
	 * List of paths that should be loaded
	 *
	 * @var int[]
	 */
	protected $queued = [];

	/**
	 * Whether we are done loading things
	 *
	 * @var bool
	 */
	private $finished = false;

	/**
	 * Items in the JSON file that aren't being
	 * set as globals
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Attributes for testing
	 *
	 * @var array
	 */
	protected $testAttributes = [];

	/**
	 * Lazy-loaded attributes
	 *
	 * @var array
	 */
	protected $lazyAttributes = [];

	/**
	 * The hash of cache-varying options, lazy-initialised
	 *
	 * @var string|null
	 */
	private $varyHash;

	/**
	 * Whether to check dev-requires
	 *
	 * @var bool
	 */
	protected $checkDev = false;

	/**
	 * Whether test classes and namespaces should be added to the auto loader
	 *
	 * @var bool
	 */
	protected $loadTestClassesAndNamespaces = false;

	/**
	 * @var ExtensionRegistry
	 */
	private static $instance;

	/**
	 * @var ?BagOStuff
	 */
	private $cache = null;

	private ?SettingsBuilder $settingsBuilder = null;

	private static bool $accessDisabledForUnitTests = false;

	/**
	 * @codeCoverageIgnore
	 * @return ExtensionRegistry
	 */
	public static function getInstance() {
		if ( self::$accessDisabledForUnitTests ) {
			throw new RuntimeException( 'Access is disabled in unit tests' );
		}
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @internal
	 */
	public static function disableForTest(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( 'Can only be called in tests' );
		}
		self::$accessDisabledForUnitTests = true;
	}

	/**
	 * @internal
	 */
	public static function enableForTest(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( 'Can only be called in tests' );
		}
		self::$accessDisabledForUnitTests = false;
	}

	/**
	 * Set the cache to use for extension info.
	 * Intended for use during testing.
	 *
	 * @internal
	 *
	 * @param BagOStuff $cache
	 */
	public function setCache( BagOStuff $cache ): void {
		$this->cache = $cache;
	}

	/**
	 * @since 1.34
	 *
	 * @param bool $check
	 */
	public function setCheckDevRequires( $check ) {
		$this->checkDev = $check;
		$this->invalidateProcessCache();
	}

	/**
	 * Controls if classes and namespaces defined under the keys TestAutoloadClasses and
	 * TestAutoloadNamespaces should be added to the autoloader.
	 *
	 * @since 1.35
	 *
	 * @param bool $load
	 */
	public function setLoadTestClassesAndNamespaces( $load ) {
		$this->loadTestClassesAndNamespaces = $load;
	}

	/**
	 * @param string $path Absolute path to the JSON file
	 */
	public function queue( $path ) {
		global $wgExtensionInfoMTime;

		$mtime = $wgExtensionInfoMTime;
		if ( $mtime === false ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$mtime = @filemtime( $path );
			// @codeCoverageIgnoreStart
			if ( $mtime === false ) {
				$err = error_get_last();
				throw new MissingExtensionException( $path, $err['message'] );
				// @codeCoverageIgnoreEnd
			}
		}
		$this->queued[$path] = $mtime;
		$this->invalidateProcessCache();
	}

	private function getCache(): BagOStuff {
		if ( !$this->cache ) {
			// NOTE: Copy of ObjectCacheFactory::getDefaultKeyspace
			//
			// Can't call MediaWikiServices here, as we must not cause services
			// to be instantiated before extensions have loaded.
			global $wgCachePrefix;
			$keyspace = ( is_string( $wgCachePrefix ) && $wgCachePrefix !== '' )
				? $wgCachePrefix
				: WikiMap::getCurrentWikiDbDomain()->getId();

			return ObjectCacheFactory::makeLocalServerCache( $keyspace );
		}

		return $this->cache;
	}

	private function makeCacheKey( BagOStuff $cache, string $component, string ...$extra ): string {
		// Allow reusing cached ExtensionRegistry metadata between wikis (T274648)
		return $cache->makeGlobalKey(
			"registration-$component",
			$this->getVaryHash(),
			...$extra
		);
	}

	/**
	 * Get the cache varying hash
	 *
	 * @return string
	 */
	private function getVaryHash() {
		if ( $this->varyHash === null ) {
			// We vary the cache on the current queue (what will be or already was loaded)
			// plus various versions of stuff for VersionChecker
			$vary = [
				'registration' => self::CACHE_VERSION,
				'mediawiki' => MW_VERSION,
				'abilities' => $this->getAbilities(),
				'checkDev' => $this->checkDev,
				'queue' => $this->queued,
			];
			$this->varyHash = md5( json_encode( $vary ) );
		}

		return $this->varyHash;
	}

	/**
	 * Invalidate the cache of the vary hash and the lazy options.
	 */
	private function invalidateProcessCache() {
		$this->varyHash = null;
		$this->lazyAttributes = [];
	}

	public function loadFromQueue() {
		if ( !$this->queued ) {
			return;
		}

		if ( $this->finished ) {
			throw new LogicException(
				"The following paths tried to load late: "
				. implode( ', ', array_keys( $this->queued ) )
			);
		}

		$cache = $this->getCache();
		// See if this queue is in APC
		$key = $this->makeCacheKey( $cache, 'main' );
		$data = $cache->get( $key );
		if ( !$data ) {
			$data = $this->readFromQueue( $this->queued );
			$this->saveToCache( $cache, $data );
		}
		$this->exportExtractedData( $data );
	}

	/**
	 * Save data in the cache
	 *
	 * @param BagOStuff $cache
	 * @param array $data
	 */
	protected function saveToCache( BagOStuff $cache, array $data ) {
		global $wgDevelopmentWarnings;
		if ( $data['warnings'] && $wgDevelopmentWarnings ) {
			// If warnings were shown, don't cache it
			return;
		}
		$lazy = [];
		// Cache lazy-loaded attributes separately
		foreach ( self::LAZY_LOADED_ATTRIBUTES as $attrib ) {
			if ( isset( $data['attributes'][$attrib] ) ) {
				$lazy[$attrib] = $data['attributes'][$attrib];
				unset( $data['attributes'][$attrib] );
			}
		}
		$mainKey = $this->makeCacheKey( $cache, 'main' );
		$cache->set( $mainKey, $data, self::CACHE_EXPIRY );
		foreach ( $lazy as $attrib => $value ) {
			$cache->set(
				$this->makeCacheKey( $cache, 'lazy-attrib', $attrib ),
				$value,
				self::CACHE_EXPIRY
			);
		}
	}

	/**
	 * Get the current load queue. Not intended to be used
	 * outside of the installer.
	 *
	 * @return int[] Map of extension.json files' modification timestamps keyed by absolute path
	 */
	public function getQueue() {
		return $this->queued;
	}

	/**
	 * Clear the current load queue. Not intended to be used
	 * outside of the installer.
	 */
	public function clearQueue() {
		$this->queued = [];
		$this->invalidateProcessCache();
	}

	/**
	 * After this is called, no more extensions can be loaded
	 *
	 * @since 1.29
	 */
	public function finish() {
		$this->finished = true;
	}

	/**
	 * Get the list of abilities and their values
	 *
	 * @return bool[]
	 */
	private function getAbilities() {
		return [
			'shell' => !Shell::isDisabled(),
		];
	}

	/**
	 * Queries information about the software environment and constructs an appropriate version checker
	 *
	 * @return VersionChecker
	 */
	private function buildVersionChecker() {
		// array to optionally specify more verbose error messages for
		// missing abilities
		$abilityErrors = [
			'shell' => ( new ShellDisabledError() )->getMessage(),
		];

		return new VersionChecker(
			MW_VERSION,
			PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION,
			get_loaded_extensions(),
			$this->getAbilities(),
			$abilityErrors
		);
	}

	/**
	 * Process a queue of extensions and return their extracted data
	 *
	 * @internal since 1.39. Extensions should use ExtensionProcessor instead.
	 *
	 * @param int[] $queue keys are filenames, values are ignored
	 *
	 * @return array extracted info
	 * @throws InvalidArgumentException
	 * @throws ExtensionDependencyError
	 */
	public function readFromQueue( array $queue ) {
		$processor = new ExtensionProcessor();
		$versionChecker = $this->buildVersionChecker();
		$extDependencies = [];
		$warnings = false;
		foreach ( $queue as $path => $mtime ) {
			$json = file_get_contents( $path );
			if ( $json === false ) {
				throw new InvalidArgumentException( "Unable to read $path, does it exist?" );
			}
			$info = json_decode( $json, /* $assoc = */ true );
			if ( !is_array( $info ) ) {
				throw new InvalidArgumentException( "$path is not a valid JSON file." );
			}

			$version = $info['manifest_version'];
			if ( $version < self::OLDEST_MANIFEST_VERSION || $version > self::MANIFEST_VERSION ) {
				throw new InvalidArgumentException( "$path: unsupported manifest_version: {$version}" );
			}

			// get all requirements/dependencies for this extension
			$requires = $processor->getRequirements( $info, $this->checkDev );

			// validate the information needed and add the requirements
			if ( is_array( $requires ) && $requires && isset( $info['name'] ) ) {
				$extDependencies[$info['name']] = $requires;
			}

			// Compatible, read and extract info
			$processor->extractInfo( $path, $info, $version );
		}
		$data = $processor->getExtractedInfo( $this->loadTestClassesAndNamespaces );
		$data['warnings'] = $warnings;

		// check for incompatible extensions
		$incompatible = $versionChecker
			->setLoadedExtensionsAndSkins( $data['credits'] )
			->checkArray( $extDependencies );

		if ( $incompatible ) {
			throw new ExtensionDependencyError( $incompatible );
		}

		return $data;
	}

	protected function exportExtractedData( array $info ) {
		if ( $info['globals'] ) {
			// Create a copy of the keys to allow fast access via isset also for null values
			// Since php8.1 always a read-only copy is created when the whole object is passed on function calls
			// (like for array_key_exists). See T366547 - https://wiki.php.net/rfc/restrict_globals_usage
			$knownGlobals = array_fill_keys( array_keys( $GLOBALS ), true );

			foreach ( $info['globals'] as $key => $val ) {
				// If a merge strategy is set, read it and remove it from the value
				// so it doesn't accidentally end up getting set.
				if ( is_array( $val ) && isset( $val[self::MERGE_STRATEGY] ) ) {
					$mergeStrategy = $val[self::MERGE_STRATEGY];
					unset( $val[self::MERGE_STRATEGY] );
				} else {
					$mergeStrategy = 'array_merge';
				}

				if ( $mergeStrategy === 'provide_default' ) {
					if ( !isset( $knownGlobals[$key] ) ) {
						$GLOBALS[$key] = $val;
						$knownGlobals[$key] = true;
					}
					continue;
				}

				// Performance optimization: When the global doesn't exist (not even with null), just set it
				if ( !isset( $knownGlobals[$key] ) ) {
					$GLOBALS[$key] = $val;
					$knownGlobals[$key] = true;
					continue;
				} elseif ( !is_array( $val ) || !is_array( $GLOBALS[$key] ) ) {
					// When at least one of the global value and the default is not an array, the merge
					// strategy is ignored and the global value will simply override the default.
					continue;
				} elseif ( !$GLOBALS[$key] ) {
					// Performance optimization: When the target is an empty array, just set it
					$GLOBALS[$key] = $val;
					continue;
				}

				switch ( $mergeStrategy ) {
					case 'array_merge_recursive':
						$GLOBALS[$key] = array_merge_recursive( $GLOBALS[$key], $val );
						break;
					case 'array_replace_recursive':
						$GLOBALS[$key] = array_replace_recursive( $val, $GLOBALS[$key] );
						break;
					case 'array_plus_2d':
						$GLOBALS[$key] = wfArrayPlus2d( $GLOBALS[$key], $val );
						break;
					case 'array_plus':
						$GLOBALS[$key] += $val;
						break;
					case 'array_merge':
						$GLOBALS[$key] = array_merge( $val, $GLOBALS[$key] );
						break;
					default:
						throw new UnexpectedValueException( "Unknown merge strategy '$mergeStrategy'" );
				}
			}
		}

		if ( isset( $info['autoloaderNS'] ) ) {
			AutoLoader::registerNamespaces( $info['autoloaderNS'] );
		}

		if ( isset( $info['autoloaderClasses'] ) ) {
			AutoLoader::registerClasses( $info['autoloaderClasses'] );
		}

		foreach ( $info['defines'] as $name => $val ) {
			if ( !defined( $name ) ) {
				define( $name, $val );
			} elseif ( constant( $name ) !== $val ) {
				throw new UnexpectedValueException(
					"$name cannot be re-defined with $val it has already been set with " . constant( $name )
				);
			}
		}

		if ( isset( $info['autoloaderPaths'] ) ) {
			AutoLoader::loadFiles( $info['autoloaderPaths'] );
		}

		$this->loaded += $info['credits'];
		if ( $info['attributes'] ) {
			if ( !$this->attributes ) {
				$this->attributes = $info['attributes'];
			} else {
				$this->attributes = array_merge_recursive( $this->attributes, $info['attributes'] );
			}
		}

		// XXX: SettingsBuilder should really be a parameter to this method.
		$settings = $this->getSettingsBuilder();

		foreach ( $info['callbacks'] as $name => $cb ) {
			if ( !is_callable( $cb ) ) {
				if ( is_array( $cb ) ) {
					$cb = '[ ' . implode( ', ', $cb ) . ' ]';
				}
				throw new UnexpectedValueException( "callback '$cb' is not callable" );
			}
			$cb( $info['credits'][$name], $settings );
		}
	}

	/**
	 * Whether a thing has been loaded
	 *
	 * @param string $name
	 * @param string $constraint The required version constraint for this dependency
	 * @return bool
	 */
	public function isLoaded( $name, $constraint = '*' ) {
		$isLoaded = isset( $this->loaded[$name] );
		if ( $constraint === '*' || !$isLoaded ) {
			return $isLoaded;
		}
		// if a specific constraint is requested, but no version is set, throw an exception
		if ( !isset( $this->loaded[$name]['version'] ) ) {
			$msg = "{$name} does not expose its version, but an extension or a skin"
					. " requires: {$constraint}.";
			throw new LogicException( $msg );
		}

		return Semver::satisfies( $this->loaded[$name]['version'], $constraint );
	}

	/**
	 * @param string $name
	 *
	 * @return array
	 */
	public function getAttribute( $name ) {
		if ( isset( $this->testAttributes[$name] ) ) {
			return $this->testAttributes[$name];
		}

		if ( in_array( $name, self::LAZY_LOADED_ATTRIBUTES, true ) ) {
			return $this->getLazyLoadedAttribute( $name );
		}

		return $this->attributes[$name] ?? [];
	}

	/**
	 * Register any domain event subscribers defined by extensions.
	 *
	 * @internal
	 */
	public function registerListeners( DomainEventSource $eventSource ): void {
		$subscribers = $this->getAttribute( 'DomainEventIngresses' );

		foreach ( $subscribers as $subscriber ) {
			$eventSource->registerSubscriber( $subscriber );
		}
	}

	/**
	 * Get an attribute value that isn't cached by reading each
	 * extension.json file again
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	protected function getLazyLoadedAttribute( $name ) {
		if ( isset( $this->testAttributes[$name] ) ) {
			return $this->testAttributes[$name];
		}
		if ( isset( $this->lazyAttributes[$name] ) ) {
			return $this->lazyAttributes[$name];
		}

		// See if it's in the cache
		$cache = $this->getCache();
		$key = $this->makeCacheKey( $cache, 'lazy-attrib', $name );
		$data = $cache->get( $key );
		if ( $data !== false ) {
			$this->lazyAttributes[$name] = $data;

			return $data;
		}

		$paths = [];
		foreach ( $this->loaded as $info ) {
			// mtime (array value) doesn't matter here since
			// we're skipping cache, so use a dummy time
			$paths[$info['path']] = 1;
		}

		$result = $this->readFromQueue( $paths );
		$data = $result['attributes'][$name] ?? [];
		$this->saveToCache( $cache, $result );
		$this->lazyAttributes[$name] = $data;

		return $data;
	}

	/**
	 * Force override the value of an attribute during tests
	 *
	 * @param string $name Name of attribute to override
	 * @param array $value Value to set
	 *
	 * @return ScopedCallback to reset
	 * @since 1.33
	 */
	public function setAttributeForTest( $name, array $value ) {
		// @codeCoverageIgnoreStart
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' can only be used in tests' );
		}
		// @codeCoverageIgnoreEnd
		if ( isset( $this->testAttributes[$name] ) ) {
			throw new InvalidArgumentException( "The attribute '$name' has already been overridden" );
		}
		$this->testAttributes[$name] = $value;

		return new ScopedCallback( function () use ( $name ) {
			unset( $this->testAttributes[$name] );
		} );
	}

	/**
	 * Get credits information about all installed extensions and skins.
	 *
	 * @return array[] Keyed by component name.
	 */
	public function getAllThings() {
		return $this->loaded;
	}

	/**
	 * Fully expand autoloader paths
	 *
	 * @param string $dir
	 * @param string[] $files
	 *
	 * @return array
	 */
	protected static function processAutoLoader( $dir, array $files ) {
		// Make paths absolute, relative to the JSON file
		foreach ( $files as &$file ) {
			$file = "$dir/$file";
		}

		return $files;
	}

	/**
	 * @internal for use by Setup. Hopefully in the future, we find a better way.
	 *
	 * @param SettingsBuilder $settingsBuilder
	 */
	public function setSettingsBuilder( SettingsBuilder $settingsBuilder ) {
		$this->settingsBuilder = $settingsBuilder;
	}

	private function getSettingsBuilder(): SettingsBuilder {
		if ( $this->settingsBuilder === null ) {
			$this->settingsBuilder = SettingsBuilder::getInstance();
		}

		return $this->settingsBuilder;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ExtensionRegistry::class, 'ExtensionRegistry' );
