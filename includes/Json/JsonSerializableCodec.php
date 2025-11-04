<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

use JsonException;
use JsonSerializable;
use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * A JsonClassCodec for objects implementing the JsonSerializable interface.
 *
 * NOTE that this is for compatibility only and does NOT deserialize!
 *
 * @see JsonSerializable
 * @see JsonClassCodec
 * @since 1.43
 * @internal
 */
class JsonSerializableCodec implements JsonClassCodec {

	/** @inheritDoc */
	public function toJsonArray( $obj ): array {
		return $obj->jsonSerialize();
	}

	/** @return never */
	public function newFromJsonArray( string $className, array $json ): never {
		throw new JsonException( "Cannot deserialize: {$className}" );
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $keyName ) {
		return null;
	}

	public static function getInstance(): JsonSerializableCodec {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new JsonSerializableCodec();
		}
		return $instance;
	}
}
