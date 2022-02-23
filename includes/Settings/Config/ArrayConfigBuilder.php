<?php

namespace MediaWiki\Settings\Config;

use Config;
use HashConfig;
use MediaWiki\Config\IterableConfig;

class ArrayConfigBuilder extends ConfigBuilderBase {

	/** @var array */
	protected $config = [];

	protected function has( string $key ): bool {
		return array_key_exists( $key, $this->config );
	}

	protected function get( string $key ) {
		return $this->config[$key] ?? null;
	}

	protected function update( string $key, $value ) {
		$this->config[$key] = $value;
	}

	/**
	 * Build the configuration.
	 *
	 * @todo Once we can use PHP 7.4, change the return type declaration to IterableConfig.
	 * @return IterableConfig
	 */
	public function build(): Config {
		return new HashConfig( $this->config );
	}
}
