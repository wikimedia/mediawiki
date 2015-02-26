<?php

/**
 * Factory to create BagOStuff instances
 *
 * @since 1.25
 */
class ObjectCacheFactory {

	/**
	 * @var ObjectCacheFactory
	 */
	private static $instance;
	private $definitions = array();
	private $instances = array();

	/**
	 * @return ObjectCacheFactory
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Clear instances and registered definitions
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}

	public function isRegistered( $name ) {
		return isset( $this->definitions[$name] );
	}

	/**
	 * @param string|array $name
	 * @param array $definition Must have "class" or "factory" key
	 * @throws Exception
	 */
	public function register( $name, array $definition = null ) {
		if ( is_array( $name ) ) {
			// Allow registering multiple definitions at once
			foreach ( $name as $realName => $definition ) {
				$this->register( $realName, $definition );
			}
			return;
		}
		if ( isset( $this->definitions[$name] ) ) {
			throw new Exception( "A objectcache is already registered under '$name'" );
		}

		if ( !isset( $definition['class'] ) && !isset( $definition['factory'] ) ) {
			throw new InvalidArgumentException( "The definition must have a 'class' or 'factory' key." );
		}

		$this->definitions[$name] = $definition;
	}

	/**
	 * @param array $definition
	 * @throws Exception
	 * @return BagOStuff
	 */
	public function newFromDefinition( array $definition ) {
		if ( isset( $definition['factory'] ) ) {
			$instance = call_user_func( $definition['factory'], $definition );
		} elseif ( isset( $definition['class'] ) ) {
			$instance = new $definition['class']( $definition );
		} else {
			throw new InvalidArgumentException( '$definition must have a "class" or "factory" key.' );
		}

		if ( !$instance instanceof BagOStuff ) {
			$badClass = get_class( $instance );
			throw new Exception( "$badClass is not a BagOStuff instance." );
		}

		return $instance;
	}

	/**
	 * @param string $name
	 * @return BagOStuff
	 * @throws Exception
	 */
	public function getInstance( $name ) {
		if ( isset( $this->instances[$name] ) ) {
			return $this->instances[$name];
		}

		if ( !isset( $this->definitions[$name] ) ) {
			throw new Exception( "No definition is registered for '$name'" );
		}

		$instance = $this->newFromDefinition( $this->definitions[$name] );
		$this->instances[$name] = $instance;
		return $instance;
	}
}
