<?php

namespace MediaWiki\Settings\Config;

use Config;
use HashConfig;
use MediaWiki\Config\IterableConfig;

class ArrayConfigBuilder implements ConfigBuilder {

	use ConfigBuilderTrait;

	/** @var array */
	protected $config = [];

	public function set( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		$this->config[ $key ] =
			$this->getNewValue( $key, $this->config[ $key ] ?? null, $value, $mergeStrategy );
		return $this;
	}

	public function setDefault( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		if ( $mergeStrategy ) {
			$this->set( $key, $value, $mergeStrategy->reverse() );
		} elseif ( !array_key_exists( $key, $this->config ) ) {
			$this->config[$key] = $value;
		}
		return $this;
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
