<?php

namespace MediaWiki\Settings\Config;

use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * Settings sink for values to pass to ini_set.
 *
 * @since 1.39
 */
class PhpIniSink {
	use NormalizedExceptionTrait;

	/**
	 * Sets a php runtime configuration value using ini_set().
	 * A PHP notice is triggered if setting the value fails.
	 *
	 * @param string $option
	 * @param string $value
	 * @return void
	 */
	public function set( string $option, string $value ): void {
		$result = ini_set( $option, $value );

		if ( $result === false ) {
			$msg = $this->getMessageFromNormalizedMessage(
				'Could not set option: {option} with value: {value} to PHP_INI config.',
				[
					'value' => $value,
					'option' => $option,
				]
			);
			trigger_error( $msg );
		}
	}

}
