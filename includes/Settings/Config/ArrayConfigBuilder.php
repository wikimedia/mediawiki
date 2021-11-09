<?php

namespace MediaWiki\Settings\Config;

use Config;
use HashConfig;

class ArrayConfigBuilder implements ConfigSink {

	/** @var array */
	private $config;

	/**
	 * @param array $initialConfig
	 */
	public function __construct( array $initialConfig = [] ) {
		$this->config = $initialConfig;
	}

	public function set( string $key, $value ): ConfigSink {
		$this->config[$key] = $value;
		return $this;
	}

	public function setIfNotDefined( string $key, $value ): ConfigSink {
		if ( !array_key_exists( $key, $this->config ) ) {
			$this->set( $key, $value );
		}
		return $this;
	}

	/**
	 * Build the configuration.
	 *
	 * @return Config
	 */
	public function build(): Config {
		return new HashConfig( $this->config );
	}

	/**
	 * Build the configuration as a array.
	 *
	 * @return array
	 */
	public function buildArray(): array {
		return $this->config;
	}
}
