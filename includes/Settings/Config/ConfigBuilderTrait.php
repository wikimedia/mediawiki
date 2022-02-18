<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Settings\SettingsBuilderException;

/**
 * Trait for sharing code between implementations of ConfigBuilder
 */
trait ConfigBuilderTrait {

	/**
	 * Determine the new value given an old value and a merge strategy.
	 *
	 * @param string $key
	 * @param mixed $oldValue
	 * @param mixed $newValue
	 * @param ?MergeStrategy $mergeStrategy
	 *
	 * @return mixed
	 */
	private function getNewValue( string $key, $oldValue, $newValue, ?MergeStrategy $mergeStrategy = null ) {
		if ( $mergeStrategy ) {
			if ( !is_array( $newValue ) ) {
				throw new SettingsBuilderException(
					'Cannot merge non-array value of type {value_type} under {key}',
					[
						'value_type' => get_debug_type( $newValue ),
						'key' => $key,
					]
				);
			} elseif ( $oldValue === null ) {
				// Optimization: If there is no old value, no need to merge.
				return $newValue;
			} elseif ( !is_array( $oldValue ) ) {
				throw new SettingsBuilderException(
					'Cannot merge into non-array value of type {value_type} under {key}',
					[
						'value_type' => get_debug_type( $oldValue ),
						'key' => $key,
					]
				);
			// @phan-suppress-next-line PhanImpossibleCondition False positive
			} elseif ( !$oldValue ) {
				// Optimization: If the old value is an empty array, no need to merge.
				return $newValue;
			} else {
				return $mergeStrategy->merge( $oldValue, $newValue );
			}
		}
		return $newValue;
	}

}
