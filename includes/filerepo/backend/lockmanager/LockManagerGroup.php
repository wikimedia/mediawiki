<?php
/**
 * Class to handle file lock manager registration
 * 
 * @ingroup LockManager
 * @author Aaron Schulz
 * @since 1.19
 */
class LockManagerGroup {

	/**
	 * @var LockManagerGroup
	 */
	protected static $instance = null;

	/** @var Array of (name => ('class' =>, 'config' =>, 'instance' =>)) */
	protected $managers = array();

	protected function __construct() {}
	protected function __clone() {}

	/**
	 * @return LockManagerGroup
	 */
	public static function singleton() {
		if ( self::$instance == null ) {
			self::$instance = new self();
			self::$instance->initFromGlobals();
		}
		return self::$instance;
	}

	/**
	 * Register lock managers from the global variables
	 * 
	 * @return void
	 */
	protected function initFromGlobals() {
		global $wgLockManagers;

		$this->register( $wgLockManagers );
	}

	/**
	 * Register an array of file lock manager configurations
	 *
	 * @param $configs Array
	 * @return void
	 * @throws MWException
	 */
	protected function register( array $configs ) {
		foreach ( $configs as $config ) {
			if ( !isset( $config['name'] ) ) {
				throw new MWException( "Cannot register a lock manager with no name." );
			}
			$name = $config['name'];
			if ( !isset( $config['class'] ) ) {
				throw new MWException( "Cannot register lock manager `{$name}` with no class." );
			}
			$class = $config['class'];
			unset( $config['class'] ); // lock manager won't need this
			$this->managers[$name] = array(
				'class'    => $class,
				'config'   => $config,
				'instance' => null
			);
		}
	}

	/**
	 * Get the lock manager object with a given name
	 *
	 * @param $name string
	 * @return LockManager
	 * @throws MWException
	 */
	public function get( $name ) {
		if ( !isset( $this->managers[$name] ) ) {
			throw new MWException( "No lock manager defined with the name `$name`." );
		}
		// Lazy-load the actual lock manager instance
		if ( !isset( $this->managers[$name]['instance'] ) ) {
			$class = $this->managers[$name]['class'];
			$config = $this->managers[$name]['config'];
			$this->managers[$name]['instance'] = new $class( $config );
		}
		return $this->managers[$name]['instance'];
	}
}
