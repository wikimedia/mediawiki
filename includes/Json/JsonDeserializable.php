<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

use JsonSerializable;

/**
 * Classes implementing this interface support round-trip JSON serialization/deserialization
 * using the JsonDeserializer utility.
 *
 * The resulting JSON must be annotated with class information for deserialization to work.
 * Use JsonDeserializableTrait in implementing classes which annotates the JSON automatically.
 *
 * @see JsonDeserializer
 * @see JsonDeserializableTrait
 * @since 1.36
 * @package MediaWiki\Json
 * @deprecated since 1.45; use JsonCodecable in new code
 */
interface JsonDeserializable extends JsonSerializable {

	/**
	 * Creates a new instance of the class and initialized it from the $json array.
	 * @param JsonDeserializer $deserializer an instance of JsonDeserializer to use
	 *   for nested properties if they need special care.
	 * @param array $json
	 * @return JsonDeserializable
	 */
	public static function newFromJsonArray( JsonDeserializer $deserializer, array $json );
}

/** @deprecated class alias since 1.43 */
class_alias( JsonDeserializable::class, 'MediaWiki\\Json\\JsonUnserializable' );
