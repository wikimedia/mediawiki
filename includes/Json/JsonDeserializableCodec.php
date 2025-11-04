<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * A JsonClassCodec for objects implementing the JsonDeserializable interface.
 *
 * @see JsonDeserializer
 * @see JsonDeserializableTrait
 * @see JsonClassCodec
 * @since 1.43
 * @internal
 */
class JsonDeserializableCodec implements JsonClassCodec {
	private JsonDeserializer $codec;

	public function __construct( JsonDeserializer $codec ) {
		$this->codec = $codec;
	}

	/** @inheritDoc */
	public function toJsonArray( $obj ): array {
		$result = $obj->jsonSerialize();
		// Undo the work of JsonDeserializableTrait to avoid
		// redundant storage of TYPE_ANNOTATION
		unset( $result[JsonConstants::TYPE_ANNOTATION] );
		return $result;
	}

	/** @inheritDoc */
	public function newFromJsonArray( string $className, array $json ) {
		return $className::newFromJsonArray( $this->codec, $json );
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $keyName ) {
		return null;
	}
}
