<?php

class DatabaseConfig implements Config {

	/**
	 * @var bool|array
	 */
	private $settings = false;
	private $name;

	/**
	 * @var DatabaseConfigLoader
	 */
	private $dcl;

	public static function newInstance( ConfigFactory $factory, $name ) {
		return new self( $name, DatabaseConfigLoader::getDefaultInstance() );
	}

	public function __construct( $name, DatabaseConfigLoader $dcl ) {
		$this->name = $name;
		$this->dcl = $dcl;
	}

	protected function load() {
		if ( $this->settings !== false ) {
			return;
		}

		$this->settings = $this->dcl->get( $this->name );
	}

	public function has( $name ) {
		$this->load();
		return array_key_exists( $name, $this->settings );
	}

	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
		}

		return $this->settings[$name];
	}

	/**
	 * If you have to deal with legacy code that is still
	 * reading from $GLOBALS, you can extract all the settings
	 * to the global space, but you may lose the benefit of
	 * lazy-loading.
	 *
	 * @param string $prefix
	 */
	public function extractToGlobals( $prefix = 'wg' ) {
		$this->load();
		foreach ( $this->settings as $name => $value ) {
			$GLOBALS["$prefix$name"] = $value;
		}
	}
}
