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

	/**
	 * Processor, should be set by subclasses in the constructor
	 *
	 * @var Processor
	 */
	protected $processor;

	public function __construct() {
		try {
			$this->cache = wfGetCache( CACHE_ACCEL );
		} catch ( MWException $e ) {
			$this->cache = new EmptyBagOStuff();
		}
	}

	/**
	 * Loads and processes the given JSON file
	 *
	 * @param string $path Absolute path to the JSON file
	 * @throws Exception
	 */
	public function load( $path ) {
		$mtime = filemtime( $path );
		$info = $this->loadInfoFromFile( $path, $mtime );
		if ( !$info ) {
			throw new Exception( "Could not read $path" );
		}
		$this->processAutoLoader( dirname( $path ), $info );
		if ( isset( $info['processor'] ) ) {
			$processor = new $info['processor']();
			if ( !$processor instanceof Processor ) {
				throw new Exception( "{$info['processor']} is not a Processor" );
			}
		} else {
			$processor = $this->processor;
		}
		$credits = $processor->processInfo( $path, $info );
		$this->markLoaded( $info['name'], $credits );
		$processor->callback( $info );
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
	 */
	protected function processAutoLoader( $dir, array $info ) {
		if ( isset( $info['AutoloadClasses'] ) ) {
			// Make paths absolute, relative to the JSON file
			$GLOBALS['wgAutoloadClasses'] += array_map( function( $file ) use ( $dir ) {
				return "$dir/$file";
			}, $info['AutoloadClasses'] );
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
