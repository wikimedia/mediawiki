<?php

namespace MediaWiki\Settings\Source\Format;

use UnexpectedValueException;

/**
 * Decodes settings data from JSON.
 */
class JsonFormat implements SettingsFormat {

	/**
	 * Decodes JSON.
	 *
	 * @param string $data JSON string to decode.
	 *
	 * @return array
	 * @throws UnexpectedValueException
	 */
	public function decode( string $data ): array {
		$settings = json_decode( $data, true );

		if ( $settings === null ) {
			throw new UnexpectedValueException(
				'Failed to decode JSON: ' . json_last_error_msg()
			);
		}

		if ( !is_array( $settings ) ) {
			throw new UnexpectedValueException(
				'Decoded settings must be an array'
			);
		}

		return $settings;
	}

	/**
	 * Returns true for the file extension 'json'. Case insensitive.
	 *
	 * @param string $ext File extension.
	 *
	 * @return bool
	 */
	public static function supportsFileExtension( string $ext ): bool {
		return strtolower( $ext ) == 'json';
	}

	/**
	 * Returns the name/type of this format (JSON).
	 *
	 * @return string
	 */
	public function __toString(): string {
		return 'JSON';
	}
}
