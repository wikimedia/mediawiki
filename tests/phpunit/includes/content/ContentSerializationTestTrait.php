<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Content;

use MediaWiki\MediaWikiServices;
use Wikimedia\Tests\SerializationTestTrait;

trait ContentSerializationTestTrait {
	use SerializationTestTrait;

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../data/Content';
	}

	public static function getSupportedSerializationFormats(): array {
		$jsonCodec = MediaWikiServices::getInstance()->getJsonCodec();
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
