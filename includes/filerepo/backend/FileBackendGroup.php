<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Class to handle file backend registration
 *
 * @ingroup FileBackend
 */
class FileBackendGroup {
	protected static $instance = null;

	/** @var Array of (name => ('class' =>, 'config' =>, 'instance' =>)) */
	protected $backends = array();

	protected function __construct() {}
	protected function __clone() {}

	public static function singleton() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance, so that a new one will be created next
	 * time singleton() is called.
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Register an array of file backend configurations
	 *
	 * @param $configs Array
	 * @return void
	 * @throws MWException
	 */
	public function register( array $configs ) {
		foreach ( $configs as $config ) {
			if ( !isset( $config['name'] ) ) {
				throw new MWException( "Cannot register a backend with no name." );
			}
			$name = $config['name'];
			if ( !isset( $config['class'] ) ) {
				throw new MWException( "Cannot register backend `{$name}` with no class." );
			}
			$class = $config['class'];

			unset( $config['class'] ); // backend won't need this
			$this->backends[$name] = array(
				'class'    => $class,
				'config'   => $config,
				'instance' => null
			);
		}
	}

	/**
	 * Get the backend object with a given name
	 *
	 * @param $name string
	 * @return FileBackendBase
	 * @throws MWException
	 */
	public function get( $name ) {
		if ( !isset( $this->backends[$name] ) ) {
			throw new MWException( "No backend defined with the name `$name`." );
		}
		// Lazy-load the actual backend instance
		if ( !isset( $this->backends[$name]['instance'] ) ) {
			$class = $this->backends[$name]['class'];
			$config = $this->backends[$name]['config'];
			$this->backends[$name]['instance'] = new $class( $config );
		}
		return $this->backends[$name]['instance'];
	}

	/**
	 * Get an appropriate backend object from a storage path
	 *
	 * @param $storagePath string
	 * @return FileBackendBase|null Backend or null on failure
	 */
	public function backendFromPath( $storagePath ) {
		list( $backend, $c, $p ) = FileBackend::splitStoragePath( $storagePath );
		if ( $backend !== null && isset( $this->backends[$backend] ) ) {
			return $this->get( $backend );
		}
		return null;
	}
}
