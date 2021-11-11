<?php

namespace MediaWiki\Settings\Config;

use Config;
use HashConfig;
use MediaWiki\Settings\SettingsBuilderException;

class ArrayConfigBuilder implements ConfigSink {

	/** @var array */
	protected $config = [];

	public function set( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigSink {
		if ( $mergeStrategy ) {
			if ( !is_array( $value ) ) {
				throw new SettingsBuilderException(
					'Cannot merge non-array value of type {value_type} under {key}',
					[
						'value_type' => get_debug_type( $value ),
						'key' => $key,
					]
				);
			} elseif ( !array_key_exists( $key, $this->config ) ) {
				// Optimization: If the $key is not set in the config at all, no need to merge.
				$this->config[$key] = $value;
			} elseif ( is_array( $this->config[$key] ) && !$this->config[$key] ) {
				// Optimization: If the existing value is an empty array, no need to merge.
				$this->config[$key] = $value;
			} else {
				$this->config[$key] = $mergeStrategy->merge( $this->config[$key] ?? [], $value );
			}
			return $this;
		}
		$this->config[$key] = $value;
		return $this;
	}

	public function setDefault( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigSink {
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
	 * @return Config
	 */
	public function build(): Config {
		return new HashConfig( $this->config );
	}
}
