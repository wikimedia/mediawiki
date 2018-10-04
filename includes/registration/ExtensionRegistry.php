<?php

use Composer\Semver\Semver;

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
	 * "requires" key that applies to MediaWiki core/$wgVersion
	 */
	const MEDIAWIKI_CORE = 'MediaWiki';

	/**
	 * Version of the highest supported manifest version
	 * Note: Update MANIFEST_VERSION_MW_VERSION when changing this
	 */
	const MANIFEST_VERSION = 2;

	/**
	 * MediaWiki version constraint representing what the current
	 * highest MANIFEST_VERSION is supported in
	 */
	const MANIFEST_VERSION_MW_VERSION = '>= 1.29.0';

	/**
	 * Version of the oldest supported manifest version
	 */
	const OLDEST_MANIFEST_VERSION = 1;

	/**
	 * Bump whenever the registration cache needs resetting
	 */
	const CACHE_VERSION = 7;

	/**
	 * Special key that defines the merge strategy
	 *
	 * @since 1.26
	 */
	const MERGE_STRATEGY = '_merge_strategy';

	/**
	 * Array of loaded things, keyed by name, values are credits information
	 *
	 * @var array
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
	 * @param string $path Absolute path to the JSON file
	 */
	public function queue( $path ) {
		global $wgExtensionInfoMTime;

		$mtime = $wgExtensionInfoMTime;
		if ( $mtime === false ) {
			if ( file_exists( $path ) ) {
				$mtime = filemtime( $path );
			} else {
				throw new Exception( "$path does not exist!" );
			}
			// @codeCoverageIgnoreStart
			if ( $mtime === false ) {
				$err = error_get_last();
				throw new Exception( "Couldn't stat $path: {$err['message']}" );
				// @codeCoverageIgnoreEnd
			}
		}
		$this->queued[$path] = $mtime;
	}

	/**
	 * @throws MWException If the queue is already marked as finished (no further things should
	 *  be loaded then).
	 */
	public function loadFromQueue() {
		global $wgVersion, $wgDevelopmentWarnings;
		if ( !$this->queued ) {
			return;
		}

		if ( $this->finished ) {
			throw new MWException(
				"The following paths tried to load late: "
				. implode( ', ', array_keys( $this->queued ) )
			);
		}

		// A few more things to vary the cache on
		$versions = [
			'registration' => self::CACHE_VERSION,
			'mediawiki' => $wgVersion
		];

		// We use a try/catch because we don't want to fail here
		// if $wgObjectCaches is not configured properly for APC setup
		try {
			// Don't use MediaWikiServices here to prevent instantiating it before extensions have
			// been loaded
			$cacheId = ObjectCache::detectLocalServerCache();
			$cache = ObjectCache::newFromId( $cacheId );
		} catch ( InvalidArgumentException $e ) {
			$cache = new EmptyBagOStuff();
		}
		// See if this queue is in APC
		$key = $cache->makeKey(
			'registration',
			md5( json_encode( $this->queued + $versions ) )
		);
		$data = $cache->get( $key );
		if ( $data ) {
			$this->exportExtractedData( $data );
		} else {
			$data = $this->readFromQueue( $this->queued );
			$this->exportExtractedData( $data );
			// Do this late since we don't want to extract it since we already
			// did that, but it should be cached
			$data['globals']['wgAutoloadClasses'] += $data['autoload'];
			unset( $data['autoload'] );
			if ( !( $data['warnings'] && $wgDevelopmentWarnings ) ) {
				// If there were no warnings that were shown, cache it
				$cache->set( $key, $data, 60 * 60 * 24 );
			}
		}
		$this->queued = [];
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
	 * Process a queue of extensions and return their extracted data
	 *
	 * @param array $queue keys are filenames, values are ignored
	 * @return array extracted info
	 * @throws Exception
	 * @throws ExtensionDependencyError
	 */
	public function readFromQueue( array $queue ) {
		global $wgVersion;
		$autoloadClasses = [];
		$autoloadNamespaces = [];
		$autoloaderPaths = [];
		$processor = new ExtensionProcessor();
		$versionChecker = new VersionChecker(
			$wgVersion,
			PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION,
			get_loaded_extensions()
		);
		$extDependencies = [];
		$incompatible = [];
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
				wfDeprecated(
					"{$info['name']}'s extension.json or skin.json does not have manifest_version",
					'1.29'
				);
				$warnings = true;
				// For backwards-compatability, assume a version of 1
				$info['manifest_version'] = 1;
			}
			$version = $info['manifest_version'];
			if ( $version < self::OLDEST_MANIFEST_VERSION || $version > self::MANIFEST_VERSION ) {
				$incompatible[] = "$path: unsupported manifest_version: {$version}";
			}

			$dir = dirname( $path );
			if ( isset( $info['AutoloadClasses'] ) ) {
				$autoload = $this->processAutoLoader( $dir, $info['AutoloadClasses'] );
				$GLOBALS['wgAutoloadClasses'] += $autoload;
				$autoloadClasses += $autoload;
			}
			if ( isset( $info['AutoloadNamespaces'] ) ) {
				$autoloadNamespaces += $this->processAutoLoader( $dir, $info['AutoloadNamespaces'] );
				AutoLoader::$psr4Namespaces += $autoloadNamespaces;
			}

			// get all requirements/dependencies for this extension
			$requires = $processor->getRequirements( $info );

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
		$incompatible = array_merge(
			$incompatible,
			$versionChecker
				->setLoadedExtensionsAndSkins( $data['credits'] )
				->checkArray( $extDependencies )
		);

		if ( $incompatible ) {
			throw new ExtensionDependencyError( $incompatible );
		}

		// Need to set this so we can += to it later
		$data['globals']['wgAutoloadClasses'] = [];
		$data['autoload'] = $autoloadClasses;
		$data['autoloaderPaths'] = $autoloaderPaths;
		$data['autoloaderNS'] = $autoloadNamespaces;
		return $data;
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
					$GLOBALS[$key] = array_replace_recursive( $GLOBALS[$key], $val );
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
			define( $name, $val );
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
			call_user_func( $cb, $info['credits'][$name] );
		}
	}

	/**
	 * Loads and processes the given JSON file without delay
	 *
	 * If some extensions are already queued, this will load
	 * those as well.
	 *
	 * @param string $path Absolute path to the JSON file
	 */
	public function load( $path ) {
		$this->loadFromQueue(); // First clear the queue
		$this->queue( $path );
		$this->loadFromQueue();
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

		return SemVer::satisfies( $this->loaded[$name]['version'], $constraint );
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function getAttribute( $name ) {
		return $this->attributes[$name] ?? [];
	}

	/**
	 * Get information about all things
	 *
	 * @return array
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
	protected function processAutoLoader( $dir, array $files ) {
		// Make paths absolute, relative to the JSON file
		foreach ( $files as &$file ) {
			$file = "$dir/$file";
		}
		return $files;
	}
}
