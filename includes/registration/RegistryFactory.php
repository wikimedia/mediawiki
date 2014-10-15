<?php

/**
 * RegistryFactory class
 *
 * Handles construction of BaseRegistry objects.
 *
 * @since 1.25
 */
class RegistryFactory {

	/**
	 * @var array
	 */
	private $instances = array();

	/**
	 * @param string $type either "skins" or "extensions"
	 * @return BaseRegistry
	 * @throws InvalidArgumentException
	 */
	public function getInstance( $type ) {
		if ( !isset( $this->instances[$type] ) ) {
			if ( $type === 'skins' ) {
				$this->instances[$type] = new SkinRegistry;
			} elseif ( $type === 'extensions' ) {
				$this->instances[$type] = new ExtensionRegistry;
			} else {
				throw new InvalidArgumentException( "There is no registry for $type" );
			}
		}

		return $this->instances[$type];
	}

	/**
	 * @return RegistryFactory
	 */
	public static function getDefaultInstance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self();
		}

		return $instance;
	}
}