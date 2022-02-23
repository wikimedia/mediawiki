<?php

namespace MediaWiki\Settings\Config;

abstract class ConfigBuilderBase implements ConfigBuilder {

	abstract protected function has( string $key ): bool;

	abstract protected function get( string $key );

	abstract protected function update( string $key, $value );

	/**
	 * @inheritDoc
	 */
	public function set(
		string $key,
		$newValue,
		MergeStrategy $mergeStrategy = null
	): ConfigBuilder {
		if ( $mergeStrategy && is_array( $newValue ) ) {
			$oldValue = $this->get( $key );
			if ( $oldValue && is_array( $oldValue ) ) {
				$newValue = $mergeStrategy->merge( $oldValue, $newValue );
			}
		}
		$this->update( $key, $newValue );
		return $this;
	}

	public function setDefault(
		string $key,
		$defaultValue,
		MergeStrategy $mergeStrategy = null
	): ConfigBuilder {
		if ( $this->has( $key ) ) {
			if ( $mergeStrategy && $defaultValue && is_array( $defaultValue ) ) {
				$customValue = $this->get( $key );
				if ( is_array( $customValue ) ) {
					$newValue = $mergeStrategy->merge( $defaultValue, $customValue );
					$this->update( $key, $newValue );
				}
			}
		} else {
			$this->update( $key, $defaultValue );
		}

		return $this;
	}

}
