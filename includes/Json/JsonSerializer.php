<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

use JsonException;

/**
 * Serializes things to JSON.
 *
 * @stable to type
 * @since 1.36
 * @package MediaWiki\Json
 * @deprecated since 1.45; use JsonCodecInterface
 */
interface JsonSerializer {

	/**
	 * Encode $value as JSON with an intent to use JsonDeserializer::unserialize
	 * to decode it back.
	 *
	 * @param mixed|JsonDeserializable $value A value to encode. Can be any scalar,
	 * array, stdClass, JsonDeserializable or any combination of them.
	 * @throws JsonException if the value can not be serialized.
	 * @return string
	 */
	public function serialize( $value );

	// TODO: move more methods from FormatJson to here.
}
