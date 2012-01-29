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
 * @since 1.19
 */
class FileBackendGroup {
	/**
	 * @var FileBackendGroup
	 */
	protected static $instance = null;

	/** @var Array (name => ('class' => string, 'config' => array, 'instance' => object)) */
	protected $backends = array();

	protected function __construct() {}
	protected function __clone() {}

	/**
	 * @return FileBackendGroup
	 */
	public static function singleton() {
		if ( self::$instance == null ) {
			self::$instance = new self();
			self::$instance->initFromGlobals();
		}
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance
	 * 
	 * @return void
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Register file backends from the global variables
	 * 
	 * @return void
	 */
	protected function initFromGlobals() {
		global $wgLocalFileRepo, $wgForeignFileRepos, $wgFileBackends;

		// Register explicitly defined backends
		$this->register( $wgFileBackends );

		$autoBackends = array();
		// Automatically create b/c backends for file repos...
		$repos = array_merge( $wgForeignFileRepos, array( $wgLocalFileRepo ) );
		foreach ( $repos as $info ) {
			$backendName = $info['backend'];
			if ( is_object( $backendName ) || isset( $this->backends[$backendName] ) ) {
				continue; // already defined (or set to the object for some reason)
			}
			$repoName = $info['name'];
			// Local vars that used to be FSRepo members...
			$directory = $info['directory'];
			$deletedDir = isset( $info['deletedDir'] )
				? $info['deletedDir']
				: false; // deletion disabled
			$thumbDir = isset( $info['thumbDir'] )
				? $info['thumbDir']
				: "{$directory}/thumb";
			$fileMode = isset( $info['fileMode'] )
				? $info['fileMode']
				: 0644;
			// Get the FS backend configuration
			$autoBackends[] = array(
				'name'           => $backendName,
				'class'          => 'FSFileBackend',
				'lockManager'    => 'fsLockManager',
				'containerPaths' => array(
					"{$repoName}-public"  => "{$directory}",
					"{$repoName}-thumb"   => $thumbDir,
					"{$repoName}-deleted" => $deletedDir,
					"{$repoName}-temp"    => "{$directory}/temp"
				),
				'fileMode'       => $fileMode,
			);
		}

		// Register implicitly defined backends
		$this->register( $autoBackends );
	}

	/**
	 * Register an array of file backend configurations
	 *
	 * @param $configs Array
	 * @return void
	 * @throws MWException
	 */
	protected function register( array $configs ) {
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
	 * @return FileBackend
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
	 * @return FileBackend|null Backend or null on failure
	 */
	public function backendFromPath( $storagePath ) {
		list( $backend, $c, $p ) = FileBackend::splitStoragePath( $storagePath );
		if ( $backend !== null && isset( $this->backends[$backend] ) ) {
			return $this->get( $backend );
		}
		return null;
	}
}
