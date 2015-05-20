<?php

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
	 * @var BagOStuff
	 */
	protected $cache;

	/**
	 * Array of loaded things, keyed by name, values are credits information
	 *
	 * @var array
	 */
	private $loaded = array();

	/**
	 * List of paths that should be loaded
	 *
	 * @var array
	 */
	protected $queued = array();

	/**
	 * Items in the JSON file that aren't being
	 * set as globals
	 *
	 * @var array
	 */
	protected $attributes = array();

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
		// We use a try/catch instead of the $fallback parameter because
		// we don't want to fail here if $wgObjectCaches is not configured
		// properly for APC setup
		try {
			$this->cache = ObjectCache::newAccelerator( array() );
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
		if ( !$this->queued ) {
			return;
		}

		// See if this queue is in APC
		$key = wfMemcKey( 'registration', md5( json_encode( $this->queued ) ) );
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
		$this->queued = array();
	}

	/**
	 * Process a queue of extensions and return their extracted data
	 *
	 * @param array $queue keys are filenames, values are ignored
	 * @return array extracted info
	 * @throws Exception
	 */
	public function readFromQueue( array $queue ) {
		$data = array( 'globals' => array( 'wgAutoloadClasses' => array() ) );
		$autoloadClasses = array();
		$processor = new ExtensionProcessor();
		foreach ( $queue as $path => $mtime ) {
			$json = file_get_contents( $path );
			$info = json_decode( $json, /* $assoc = */ true );
			if ( !is_array( $info ) ) {
				throw new Exception( "$path is not a valid JSON file." );
			}
			$autoload = $this->processAutoLoader( dirname( $path ), $info );
			// Set up the autoloader now so custom processors will work
			$GLOBALS['wgAutoloadClasses'] += $autoload;
			$autoloadClasses += $autoload;
			$processor->extractInfo( $path, $info );
		}
		$data = $processor->getExtractedInfo();
		// Need to set this so we can += to it later
		$data['globals']['wgAutoloadClasses'] = array();
		foreach ( $data['credits'] as $credit ) {
			$data['globals']['wgExtensionCredits'][$credit['type']][] = $credit;
		}
		$data['autoload'] = $autoloadClasses;
		return $data;
	}

	protected function exportExtractedData( array $info ) {
		foreach ( $info['globals'] as $key => $val ) {
			if ( !isset( $GLOBALS[$key] ) || !$GLOBALS[$key] ) {
				$GLOBALS[$key] = $val;
			} elseif ( $key === 'wgHooks' || $key === 'wgExtensionCredits' ) {
				// Special case $wgHooks and $wgExtensionCredits, which require a recursive merge.
				// Ideally it would have been taken care of in the first if block though.
				$GLOBALS[$key] = array_merge_recursive( $GLOBALS[$key], $val );
			} elseif ( $key === 'wgGroupPermissions' ) {
				// First merge individual groups
				foreach ( $GLOBALS[$key] as $name => &$groupVal ) {
					if ( isset( $val[$name] ) ) {
						$groupVal += $val[$name];
					}
				}
				// Now merge groups that didn't exist yet
				$GLOBALS[$key] += $val;
			} elseif ( is_array( $GLOBALS[$key] ) && is_array( $val ) ) {
				$GLOBALS[$key] = array_merge( $val, $GLOBALS[$key] );
			} // else case is a config setting where it has already been overriden, so don't set it
		}
		foreach ( $info['defines'] as $name => $val ) {
			define( $name, $val );
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
			return array();
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
			return array();
		}
	}
}
