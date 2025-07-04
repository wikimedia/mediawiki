<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\IterableConfig;
use function array_key_exists;

class ArrayConfigBuilder extends ConfigBuilderBase {

	/** @var array */
	protected $config = [];

	protected function has( string $key ): bool {
		return array_key_exists( $key, $this->config );
	}

	/** @inheritDoc */
	public function get( string $key ) {
		return $this->config[$key] ?? null;
	}

	/** @inheritDoc */
	protected function update( string $key, $value ) {
		$this->config[$key] = $value;
	}

	public function setMulti( array $values, array $mergeStrategies = [] ): ConfigBuilder {
		if ( !$mergeStrategies ) {
			$this->config = array_merge( $this->config, $values );
			return $this;
		}

		foreach ( $values as $key => $newValue ) {
			// Optimization: Inlined logic from set() for performance
			if ( array_key_exists( $key, $this->config ) ) {
				$mergeStrategy = $mergeStrategies[$key] ?? null;
				if ( $mergeStrategy && is_array( $newValue ) ) {
					$oldValue = $this->config[$key];
					if ( $oldValue && is_array( $oldValue ) ) {
						$newValue = $mergeStrategy->merge( $oldValue, $newValue );
					}
				}
			}
			$this->config[$key] = $newValue;
		}

		return $this;
	}

	/**
	 * Build the configuration.
	 */
	public function build(): IterableConfig {
		return new HashConfig( $this->config );
	}

	/** @inheritDoc */
	public function setMultiDefault( $defaults, $mergeStrategies ): ConfigBuilder {
		foreach ( $defaults as $key => $defaultValue ) {
			// Optimization: Inlined logic from setDefault() for performance
			if ( array_key_exists( $key, $this->config ) ) {
				$mergeStrategy = $mergeStrategies[$key] ?? null;
				if ( $mergeStrategy && $defaultValue && is_array( $defaultValue ) ) {
					$customValue = $this->config[$key];
					if ( is_array( $customValue ) ) {
						$newValue = $mergeStrategy->merge( $defaultValue, $customValue );
						$this->config[$key] = $newValue;
					}
				}
			} else {
				$this->config[$key] = $defaultValue;
			}
		}
		return $this;
	}

}
