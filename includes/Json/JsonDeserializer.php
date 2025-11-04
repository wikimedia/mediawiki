<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

use JsonException;

/**
 * Deserializes things from JSON.
 *
 * @since 1.36
 * @package MediaWiki\Json
 * @deprecated since 1.45; use JsonCodecInterface
 */
interface JsonDeserializer {

	/**
	 * Restore an instance of simple type or JsonDeserializable subclass
	 * from the JSON serialization. It supports passing array/object to
	 * allow manual decoding of the JSON string if needed.
	 *
	 * @note JSON objects are unconditionally deserialized as PHP associative
	 * arrays, and not as instances of \stdClass.
	 *
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param array|string|object $json
	 * @param string|null $expectedClass What class to expect in deserialization.
	 *   If null, no expectation. Must be a descendant of JsonDeserializable.
	 * @throws JsonException if the passed $json can't be deserialized.
	 * @return mixed
	 */
	public function deserialize( $json, ?string $expectedClass = null );

	/**
	 * Backwards-compatibility alias for deserialize()
	 *
	 * @deprecated since 1.43
	 */
	public function unserialize( $json, ?string $expectedClass = null );

	/**
	 * Helper to deserialize an array of JsonDeserializable instances or simple types.
	 * @param array $array
	 * @return array
	 */
	public function deserializeArray( array $array ): array;

	/**
	 * Backwards-compatibility alias for deserializeArray()
	 *
	 * @deprecated since 1.43
	 */
	public function unserializeArray( array $array ): array;
}

/** @deprecated class alias since 1.43 */
class_alias( JsonDeserializer::class, 'MediaWiki\\Json\\JsonUnserializer' );
