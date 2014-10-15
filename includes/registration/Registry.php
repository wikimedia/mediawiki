<?php

class Registry {

	/**
	 * @var BagOStuff
	 */
	protected $cache;

	/**
	 * @var array
	 */
	private $processors = array();

	public function __construct() {
		try {
			$this->cache = wfGetCache( CACHE_ACCEL );
		} catch ( MWException $e ) {
			$this->cache = new EmptyBagOStuff();
		}
	}

	/**
	 * @return Registry
	 */
	public static function getDefaultInstance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
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
		$processor = $this->getProcessor( $info );
		$processor->processInfo( $path, $info );
		$processor->callback( $info );
	}

	/**
	 * Register classes with the autoloader
	 *
	 * @param string $dir
	 * @param array $info
	 */
	protected function processAutoLoader( $dir, array $info ) {
		if ( isset( $info['AutoloadClasses'] ) ) {
			$GLOBALS['wgAutoloadClasses'] += array_map( function( $file ) use ( $dir ) {
				return "$dir/$file";
			}, $info['AutoloadClasses'] );
		}
	}

	/**
	 * @param array $info
	 * @throws Exception
	 * @return Processor
	 */
	protected function getProcessor( array $info ) {
		if ( isset( $info['processor'] ) ) {
			$class = $info['processor'];
		} elseif ( $info['type'] === 'skin' ) {
			$class = 'SkinProcessor';
		} else {
			$class = 'ExtensionProcessor';
		}
		if ( !isset( $this->processors[$class] ) ) {
			$processor = new $class();
			if ( !$processor instanceof Processor ) {
				throw new Exception( "{$info['processor']} does not extend Processor" );
			}
			$this->processors[$class] = $processor;
		}

		return $this->processors[$class];
	}


	/**
	 * @param string $filename
	 * @param int $mtime
	 * @return array
	 */
	protected function loadInfoFromFile( $filename, $mtime ) {
		$key = wfMemcKey( 'registry', md5( $filename ) );
		$cached = $this->cache->get( $key );
		if ( isset( $cached['mtime'] ) && $cached['mtime'] === $mtime ) {
			return $cached['info'];
		}

		$contents = file_get_contents( $filename );
		$json = json_decode( $contents );
		if ( is_object( $json ) ) {
			$json = wfObjectToArray( $json );
			$this->cache->set( $key, array( 'mtime' => $mtime, 'info' => $json ) );
		} else {
			// Don't throw an error here, but don't cache it either.
			$json = array();
		}

		return $json;
	}
}
