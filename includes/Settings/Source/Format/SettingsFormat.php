<?php

namespace MediaWiki\Settings\Source\Format;

use Stringable;
use UnexpectedValueException;

/**
 * A SettingsFormat is meant to detect supported file types and/or decode
 * source contents into settings arrays.
 *
 * @since 1.38
 * @stable to implement
 */
interface SettingsFormat extends Stringable {
	/**
	 * Decodes the given settings data and returns an associative array.
	 *
	 * @param string $data Settings data.
	 *
	 * @return array
	 * @throws UnexpectedValueException
	 */
	public function decode( string $data ): array;

	/**
	 * Whether or not the format claims to support a file with the given
	 * extension.
	 *
	 * @param string $ext File extension.
	 *
	 * @return bool
	 */
	public static function supportsFileExtension( string $ext ): bool;
}
