<?php

use MediaWiki\MediaWikiServices;

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
	 */
	const MANIFEST_VERSION = 1;

	/**
	 * Version of the oldest supported manifest version
	 */
	const OLDEST_MANIFEST_VERSION = 1;

	/**
	 * Bump whenever the registration cache needs resetting
	 */
	const CACHE_VERSION = 3;

	/**
	 * Special key that defines the merge strategy
	 *
	 * @since 1.26
	 */
	const MERGE_STRATEGY = '_merge_strategy';

	/**
	 * @var BagOStuff
	 */
	protected $cache;

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
	 * @return ExtensionRegistry
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		// We use a try/catch because we don't want to fail here
		// if $wgObjectCaches is not configured properly for APC setup
		try {
			$this->cache = MediaWikiServices::getInstance()->getLocalServerObjectCache();
		} catch ( MWException $e ) {
			$this->cache = new EmptyBagOStuff();
		}
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
			if ( !$mtime ) {
				$err = error_get_last();
				throw new Exception( "Couldn't stat $path: {$err['message']}" );
			}
		}
		$this->queued[$path] = $mtime;
	}

	public function loadFromQueue() {
		global $wgVersion;
		if ( !$this->queued ) {
			return;
		}

		// A few more things to vary the cache on
		$versions = [
			'registration' => self::CACHE_VERSION,
			'mediawiki' => $wgVersion
		];

		// See if this queue is in APC
		$key = wfMemcKey(
			'registration',
			md5( json_encode( $this->queued + $versions ) )
		);
		$data = $this->cache->get( $key );
		if ( $data ) {
			$this->exportExtractedData( $data );
		} else {
			$data = $this->readFromQueue( $this->queued );
			$this->exportExtractedData( $data );
			// Do this late since we don't want to extract it since we already
			// did that, but it should be cached
			$data['globals']['wgAutoloadClasses'] += $data['autoload'];
			unset( $data['autoload'] );
			$this->cache->set( $key, $data, 60 * 60 * 24 );
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
	 * Process a queue of extensions and return their extracted data
	 *
	 * @param array $queue keys are filenames, values are ignored
	 * @return array extracted info
	 * @throws Exception
	 */
	public function readFromQueue( array $queue ) {
		global $wgVersion;
		$autoloadClasses = [];
		$autoloaderPaths = [];
		$processor = new ExtensionProcessor();
		$incompatible = [];
		$coreVersionParser = new CoreVersionChecker( $wgVersion );
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
				// For backwards-compatability, assume a version of 1
				$info['manifest_version'] = 1;
			}
			$version = $info['manifest_version'];
			if ( $version < self::OLDEST_MANIFEST_VERSION || $version > self::MANIFEST_VERSION ) {
				throw new Exception( "$path: unsupported manifest_version: {$version}" );
			}
			$autoload = $this->processAutoLoader( dirname( $path ), $info );
			// Set up the autoloader now so custom processors will work
			$GLOBALS['wgAutoloadClasses'] += $autoload;
			$autoloadClasses += $autoload;
			// Check any constraints against MediaWiki core
			$requires = $processor->getRequirements( $info );
			if ( isset( $requires[self::MEDIAWIKI_CORE] )
				&& !$coreVersionParser->check( $requires[self::MEDIAWIKI_CORE] )
			) {
				// Doesn't match, mark it as incompatible.
				$incompatible[] = "{$info['name']} is not compatible with the current "
					. "MediaWiki core (version {$wgVersion}), it requires: " . $requires[self::MEDIAWIKI_CORE]
					. '.';
				continue;
			}
			// Get extra paths for later inclusion
			$autoloaderPaths = array_merge( $autoloaderPaths,
				$processor->getExtraAutoloaderPaths( dirname( $path ), $info ) );
			// Compatible, read and extract info
			$processor->extractInfo( $path, $info, $version );
		}
		if ( $incompatible ) {
			if ( count( $incompatible ) === 1 ) {
				throw new Exception( $incompatible[0] );
			} else {
				throw new Exception( implode( "\n", $incompatible ) );
			}
		}
		$data = $processor->getExtractedInfo();
		// Need to set this so we can += to it later
		$data['globals']['wgAutoloadClasses'] = [];
		$data['autoload'] = $autoloadClasses;
		$data['autoloaderPaths'] = $autoloaderPaths;
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
			if ( !isset( $GLOBALS[$key] ) || ( is_array( $GLOBALS[$key] ) && !$GLOBALS[$key] ) ) {
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

		foreach ( $info['defines'] as $name => $val ) {
			define( $name, $val );
		}
		foreach ( $info['autoloaderPaths'] as $path ) {
			require_once $path;
		}
		foreach ( $info['callbacks'] as $cb ) {
			call_user_func( $cb );
		}

		$this->loaded += $info['credits'];
		if ( $info['attributes'] ) {
			if ( !$this->attributes ) {
				$this->attributes = $info['attributes'];
			} else {
				$this->attributes = array_merge_recursive( $this->attributes, $info['attributes'] );
			}
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
	 * @return bool
	 */
	public function isLoaded( $name ) {
		return isset( $this->loaded[$name] );
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function getAttribute( $name ) {
		if ( isset( $this->attributes[$name] ) ) {
			return $this->attributes[$name];
		} else {
			return [];
		}
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
	 * Mark a thing as loaded
	 *
	 * @param string $name
	 * @param array $credits
	 */
	protected function markLoaded( $name, array $credits ) {
		$this->loaded[$name] = $credits;
	}

	/**
	 * Register classes with the autoloader
	 *
	 * @param string $dir
	 * @param array $info
	 * @return array
	 */
	protected function processAutoLoader( $dir, array $info ) {
		if ( isset( $info['AutoloadClasses'] ) ) {
			// Make paths absolute, relative to the JSON file
			return array_map( function( $file ) use ( $dir ) {
				return "$dir/$file";
			}, $info['AutoloadClasses'] );
		} else {
			return [];
		}
	}
}
