<?php

use Composer\Semver\Semver;
use MediaWiki\Shell\Shell;
use MediaWiki\ShellDisabledError;
use Wikimedia\AtEase\AtEase;
use Wikimedia\ScopedCallback;

/**
 * ExtensionRegistry class
 *
 * The Registry loads JSON files, and uses a Processor
 * to extract information from them. It also registers
 * classes with the autoloader.
 *
 * @since 1.25
 */
class ExtensionRegistry {

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
	private const CACHE_VERSION = 7;

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
		'QUnitTestModules',
	];

	/**
	 * Array of loaded things, keyed by name, values are credits information.
	 *
	 * The keys that the credit info arrays may have is defined
	 * by ExtensionProcessor::CREDIT_ATTRIBS (plus a 'path' key that
	 * points to the skin or extension JSON file).
	 *
	 * This info may be accessed via via ExtensionRegistry::getAllThings.
	 *
	 * @var array[]
	 */
	private $loaded = [];

	/**
	 * List of paths that should be loaded
	 *
	 * @var array
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
	 * @codeCoverageIgnore
	 * @return ExtensionRegistry
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @since 1.34
	 * @param bool $check
	 */
	public function setCheckDevRequires( $check ) {
		$this->checkDev = $check;
	}

	/**
	 * Controls if classes and namespaces defined under the keys TestAutoloadClasses and
	 * TestAutoloadNamespaces should be added to the autoloader.
	 *
	 * @since 1.35
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
			AtEase::suppressWarnings();
			$mtime = filemtime( $path );
			AtEase::restoreWarnings();
			// @codeCoverageIgnoreStart
			if ( $mtime === false ) {
				$err = error_get_last();
				throw new Exception( "Unable to open file $path: {$err['message']}" );
				// @codeCoverageIgnoreEnd
			}
		}
		$this->queued[$path] = $mtime;
	}

	private function getCache() : BagOStuff {
		// Can't call MediaWikiServices here, as we must not cause services
		// to be instantiated before extensions have loaded.
		return ObjectCache::makeLocalServerCache();
	}

	private function makeCacheKey( BagOStuff $cache, $component, ...$extra ) {
		// Everything we vary the cache on
		$vary = [
			'registration' => self::CACHE_VERSION,
			'mediawiki' => MW_VERSION,
			'abilities' => $this->getAbilities(),
			'checkDev' => $this->checkDev,
			'queue' => $this->queued,
		];

		// Allow reusing cached ExtensionRegistry metadata between wikis (T274648)
		return $cache->makeGlobalKey(
			"registration-$component",
			// We vary the cache on the current queue (what will be or already was loaded)
			// plus various versions of stuff for VersionChecker
			md5( json_encode( $vary ) ),
			...$extra
		);
	}

	/**
	 * @throws MWException If the queue is already marked as finished (no further things should
	 *  be loaded then).
	 */
	public function loadFromQueue() {
		if ( !$this->queued ) {
			return;
		}

		if ( $this->finished ) {
			throw new MWException(
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
	 * @return array
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
	 * @return bool[]
	 */
	private function getAbilities() {
		return [
			'shell' => !Shell::isDisabled(),
		];
	}

	/**
	 * Queries information about the software environment and constructs an appropiate version checker
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
	 * @param array $queue keys are filenames, values are ignored
	 * @return array extracted info
	 * @throws Exception
	 * @throws ExtensionDependencyError
	 */
	public function readFromQueue( array $queue ) {
		$autoloadClasses = [];
		$autoloadNamespaces = [];
		$autoloaderPaths = [];
		$processor = new ExtensionProcessor();
		$versionChecker = $this->buildVersionChecker();
		$extDependencies = [];
		$warnings = false;
		foreach ( $queue as $path => $mtime ) {
			$json = file_get_contents( $path );
			if ( $json === false ) {
				throw new Exception( "Unable to read $path, does it exist?" );
			}
			$info = json_decode( $json, /* $assoc = */ true );
			if ( !is_array( $info ) ) {
				throw new Exception( "$path is not a valid JSON file." );
			}

			if ( !isset( $info['manifest_version'] ) ) {
				wfDeprecatedMsg(
					"{$info['name']}'s extension.json or skin.json does not have manifest_version, " .
					'this is deprecated since MediaWiki 1.29',
					'1.29', false, false
				);
				$warnings = true;
				// For backwards-compatibility, assume a version of 1
				$info['manifest_version'] = 1;
			}
			$version = $info['manifest_version'];
			if ( $version < self::OLDEST_MANIFEST_VERSION || $version > self::MANIFEST_VERSION ) {
				throw new Exception( "$path: unsupported manifest_version: {$version}" );
			}

			$dir = dirname( $path );
			self::exportAutoloadClassesAndNamespaces(
				$dir,
				$info,
				$autoloadClasses,
				$autoloadNamespaces
			);

			if ( $this->loadTestClassesAndNamespaces ) {
				self::exportTestAutoloadClassesAndNamespaces(
					$dir,
					$info,
					$autoloadClasses,
					$autoloadNamespaces
				);
			}

			// get all requirements/dependencies for this extension
			$requires = $processor->getRequirements( $info, $this->checkDev );

			// validate the information needed and add the requirements
			if ( is_array( $requires ) && $requires && isset( $info['name'] ) ) {
				$extDependencies[$info['name']] = $requires;
			}

			// Get extra paths for later inclusion
			$autoloaderPaths = array_merge( $autoloaderPaths,
				$processor->getExtraAutoloaderPaths( $dir, $info ) );
			// Compatible, read and extract info
			$processor->extractInfo( $path, $info, $version );
		}
		$data = $processor->getExtractedInfo();
		$data['warnings'] = $warnings;

		// check for incompatible extensions
		$incompatible = $versionChecker
			->setLoadedExtensionsAndSkins( $data['credits'] )
			->checkArray( $extDependencies );

		if ( $incompatible ) {
			throw new ExtensionDependencyError( $incompatible );
		}

		// FIXME: It was a design mistake to handle autoloading separately (T240535)
		$data['globals']['wgAutoloadClasses'] = $autoloadClasses;
		$data['autoloaderPaths'] = $autoloaderPaths;
		$data['autoloaderNS'] = $autoloadNamespaces;
		return $data;
	}

	/**
	 * Export autoload classes and namespaces for a given directory and parsed JSON info file.
	 *
	 * @param string $dir
	 * @param array $info
	 * @param array &$autoloadClasses
	 * @param array &$autoloadNamespaces
	 */
	public static function exportAutoloadClassesAndNamespaces(
		$dir, $info, &$autoloadClasses = [], &$autoloadNamespaces = []
	) {
		if ( isset( $info['AutoloadClasses'] ) ) {
			$autoload = self::processAutoLoader( $dir, $info['AutoloadClasses'] );
			// @phan-suppress-next-line PhanUndeclaredVariableAssignOp
			$GLOBALS['wgAutoloadClasses'] += $autoload;
			$autoloadClasses += $autoload;
		}
		if ( isset( $info['AutoloadNamespaces'] ) ) {
			$autoloadNamespaces += self::processAutoLoader( $dir, $info['AutoloadNamespaces'] );
			AutoLoader::$psr4Namespaces += $autoloadNamespaces;
		}
	}

	/**
	 * Export test autoload classes and namespaces for a given directory and parsed JSON info file.
	 *
	 * @since 1.35
	 * @param string $dir
	 * @param array $info
	 * @param array &$autoloadClasses
	 * @param array &$autoloadNamespaces
	 */
	public static function exportTestAutoloadClassesAndNamespaces(
		$dir, $info, &$autoloadClasses = [], &$autoloadNamespaces = []
	) {
		if ( isset( $info['TestAutoloadClasses'] ) ) {
			$autoload = self::processAutoLoader( $dir, $info['TestAutoloadClasses'] );
			$GLOBALS['wgAutoloadClasses'] += $autoload;
			$autoloadClasses += $autoload;
		}
		if ( isset( $info['TestAutoloadNamespaces'] ) ) {
			$autoloadNamespaces += self::processAutoLoader( $dir, $info['TestAutoloadNamespaces'] );
			AutoLoader::$psr4Namespaces += $autoloadNamespaces;
		}
	}

	protected function exportExtractedData( array $info ) {
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
				if ( !array_key_exists( $key, $GLOBALS ) ) {
					$GLOBALS[$key] = $val;
				}
				continue;
			}

			// Optimistic: If the global is not set, or is an empty array, replace it entirely.
			// Will be O(1) performance.
			if ( !array_key_exists( $key, $GLOBALS ) || ( is_array( $GLOBALS[$key] ) && !$GLOBALS[$key] ) ) {
				$GLOBALS[$key] = $val;
				continue;
			}

			if ( !is_array( $GLOBALS[$key] ) || !is_array( $val ) ) {
				// config setting that has already been overridden, don't set it
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

		if ( isset( $info['autoloaderNS'] ) ) {
			AutoLoader::$psr4Namespaces += $info['autoloaderNS'];
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

		foreach ( $info['autoloaderPaths'] as $path ) {
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}

		$this->loaded += $info['credits'];
		if ( $info['attributes'] ) {
			if ( !$this->attributes ) {
				$this->attributes = $info['attributes'];
			} else {
				$this->attributes = array_merge_recursive( $this->attributes, $info['attributes'] );
			}
		}

		foreach ( $info['callbacks'] as $name => $cb ) {
			if ( !is_callable( $cb ) ) {
				if ( is_array( $cb ) ) {
					$cb = '[ ' . implode( ', ', $cb ) . ' ]';
				}
				throw new UnexpectedValueException( "callback '$cb' is not callable" );
			}
			$cb( $info['credits'][$name] );
		}
	}

	/**
	 * Whether a thing has been loaded
	 * @param string $name
	 * @param string $constraint The required version constraint for this dependency
	 * @throws LogicException if a specific contraint is asked for,
	 *                        but the extension isn't versioned
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
	 * Get an attribute value that isn't cached by reading each
	 * extension.json file again
	 * @param string $name
	 * @return array
	 */
	protected function getLazyLoadedAttribute( $name ) {
		if ( isset( $this->testAttributes[$name] ) ) {
			return $this->testAttributes[$name];
		}

		// See if it's in the cache
		$cache = $this->getCache();
		$key = $this->makeCacheKey( $cache, 'lazy-attrib', $name );
		$data = $cache->get( $key );
		if ( $data !== false ) {
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

		return $data;
	}

	/**
	 * Force override the value of an attribute during tests
	 *
	 * @param string $name Name of attribute to override
	 * @param array $value Value to set
	 * @return ScopedCallback to reset
	 * @since 1.33
	 */
	public function setAttributeForTest( $name, array $value ) {
		// @codeCoverageIgnoreStart
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( __METHOD__ . ' can only be used in tests' );
		}
		// @codeCoverageIgnoreEnd
		if ( isset( $this->testAttributes[$name] ) ) {
			throw new Exception( "The attribute '$name' has already been overridden" );
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
	 * @param array $files
	 * @return array
	 */
	protected static function processAutoLoader( $dir, array $files ) {
		// Make paths absolute, relative to the JSON file
		foreach ( $files as &$file ) {
			$file = "$dir/$file";
		}
		return $files;
	}
}
