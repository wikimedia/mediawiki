<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Content;

use MediaWiki\Json\JsonCodec;
use MediaWiki\MediaWikiServices;
use Wikimedia\Tests\JsonSerializationTestTrait;

trait ContentSerializationTestTrait {
	use JsonSerializationTestTrait;

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../data/Content';
	}

	protected static function getJsonCodec(): JsonCodec {
		return MediaWikiServices::getInstance()->getJsonCodec();
	}
}
