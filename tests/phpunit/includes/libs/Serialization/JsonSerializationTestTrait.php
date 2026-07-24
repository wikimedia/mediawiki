<?php

namespace Wikimedia\Tests;

use MediaWiki\Json\JsonCodec;

/**
 * A specialized SerializationTestTrait for classes that have only JSON as a
 * serialization format.
 *
 * @since 1.47
 */
trait JsonSerializationTestTrait {
	use SerializationTestTrait;

	protected static function getJsonCodec(): JsonCodec {
		return new JsonCodec();
	}

	public static function getSupportedSerializationFormats(): array {
		$jsonCodec = static::getJsonCodec();
		return [ [
			'ext' => 'json',
			'serializer' => static function ( $obj ) use ( $jsonCodec ) {
				return $jsonCodec->serialize( $obj );
			},
			'deserializer' => static function ( $data ) use ( $jsonCodec ) {
				return $jsonCodec->deserialize( $data );
			},
		] ];
	}

}
