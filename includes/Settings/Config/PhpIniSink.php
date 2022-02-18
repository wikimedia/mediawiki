<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Settings\SettingsBuilderException;

/**
 * @unstable
 */
class PhpIniSink {
	/**
	 * @param string $option
	 * @param string $value
	 * @return void
	 */
	public function set( string $option, string $value ): void {
		$result = ini_set( $option, $value );

		if ( $result === false ) {
			throw new SettingsBuilderException(
				'Could not set option: {option} with value: {value} to PHP_INI config.',
				[
					'value' => $value,
					'option' => $option,
				]
			);
		}
	}

}
