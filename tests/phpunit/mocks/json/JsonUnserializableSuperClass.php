<?php

namespace MediaWiki\Tests\Json;

use MediaWiki\Json\JsonUnserializable;
use MediaWiki\Json\JsonUnserializableTrait;
use MediaWiki\Json\JsonUnserializer;

/**
 * Testing class for JsonUnserializer unit tests.
 * @package MediaWiki\Tests\Json
 */
class JsonUnserializableSuperClass implements JsonUnserializable {
	use JsonUnserializableTrait;

	private $superClassField;

	public function __construct( string $superClassFieldValue ) {
		$this->superClassField = $superClassFieldValue;
	}

	public function getSuperClassField(): string {
		return $this->superClassField;
	}

	public static function newFromJsonArray( JsonUnserializer $unserializer, array $json ) {
		return new self( $json['super_class_field'] );
	}

	protected function toJsonArray(): array {
		return [
			'super_class_field' => $this->getSuperClassField()
		];
	}
}
