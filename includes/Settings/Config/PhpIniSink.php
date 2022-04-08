<?php

namespace MediaWiki\Settings\Config;

use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * @unstable
 */
class PhpIniSink {
	use NormalizedExceptionTrait;

	/**
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
