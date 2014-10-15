<?php

/**
 * BaseRegistry class
 *
 * The Registry loads JSON files, and uses a Processor
 * to extract information from them. It also registers
 * classes with the autoloader.
 *
 * @since 1.25
 */
abstract class BaseRegistry {

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

	protected $queued = array();

	/**
	 * Class name of the default processor, to be set by subclasses
	 *
	 * @var string
	 */
	protected $defaultProcessor;

	/**
	 * Processors, 'default' should be set by subclasses in the constructor
	 *
	 * @var Processor[]
	 */
	protected $processors = array();

	public function __construct() {
		$this->cache = ObjectCache::newAccelerator( array(), CACHE_NONE );
		//$this->cache = new EmptyBagOStuff();
	}

	/**
	 * @param string $path Absolute path to the JSON file
	 */
	public function queue( $path ) {
		$mtime = 1;
		//$mtime = filemtime( $path );
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
			$data = array( 'globals' => array( 'wgAutoloadClasses' => array() ) );
			foreach ( $this->queued as $path => $mtime ) {
				$json = file_get_contents( $path );
				$info = json_decode( $json, /* $assoc = */ true );
				$autoload = $this->processAutoLoader( dirname( $path ), $info );
				// Set up the autoloader now so custom processors will work
				$GLOBALS['wgAutoloadClasses'] += $autoload;
				$data['globals']['wgAutoloadClasses'] += $autoload;
				if ( isset( $info['processor'] ) ) {
					$processor = $this->getProcessor( $info['processor'] );
				} else {
					$processor = $this->getProcessor( 'default' );
				}
				$processor->extractInfo( $path, $info );
			}
			foreach ( $this->processors as $processor ) {
				$data = array_merge_recursive( $data, $processor->getExtractedInfo() );
			}
			foreach ( $data['credits'] as $credit ) {
				$data['globals']['wgExtensionCredits'][$credit['type']][] = $credit;
			}
			$this->processors = array(); // Reset

			$this->cache->set( $key, $data );
			$this->exportExtractedData( $data );


		}

	}

	protected function getProcessor( $type ) {
		if ( !isset( $this->processors[$type] ) ) {
			$processor = $type === 'default' ? new $this->defaultProcessor() : new $type();
			if ( !$processor instanceof Processor ) {
				throw new Exception( "$type is not a Processor" );
			}
			$this->processors[$type] = $processor;
		}

		return $this->processors[$type];
	}

	protected function exportExtractedData( array $info ) {
		foreach ( $info['globals'] as $key => $val ) {
			if ( !isset( $GLOBALS[$key] ) || !$GLOBALS[$key] ) {
				$GLOBALS[$key] = $val;
			} else {
				$GLOBALS[$key] = array_merge_recursive( $GLOBALS[$key], $val );
			}
		}
		foreach ( $info['defines'] as $name => $val ) {
			define( $name, $val );
		}
		foreach ( $info['callbacks'] as $cb ) {
			call_user_func( $cb );
		}

		$this->loaded += $info['credits'];
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

	/**
	 * @param string $filename absolute path to the JSON file
	 * @param int $mtime modified time of the file
	 * @return array
	 */
	protected function loadInfoFromFile( $filename, $mtime ) {
		$key = wfMemcKey( 'registry', md5( $filename ) );
		$cached = $this->cache->get( $key );
		if ( isset( $cached['mtime'] ) && $cached['mtime'] === $mtime ) {
			return $cached['info'];
		}

		$contents = file_get_contents( $filename );
		$json = json_decode( $contents, /* $assoc = */ true );
		if ( is_array( $json ) ) {
			$this->cache->set( $key, array( 'mtime' => $mtime, 'info' => $json ) );
		} else {
			// Don't throw an error here, but don't cache it either.
			// @todo log somewhere?
			$json = array();
		}

		return $json;
	}
}
