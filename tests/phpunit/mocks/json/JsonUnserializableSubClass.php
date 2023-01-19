<?php

namespace MediaWiki\Tests\Json;

use MediaWiki\Json\JsonUnserializableTrait;
use MediaWiki\Json\JsonUnserializer;

/**
 * Testing class for JsonUnserializer unit tests.
 * @package MediaWiki\Tests\Json
 */
class JsonUnserializableSubClass extends JsonUnserializableSuperClass {
	use JsonUnserializableTrait;

	private $subClassField;

	public function __construct( $superClassFieldValue, $subClassFieldValue ) {
		parent::__construct( $superClassFieldValue );
		$this->subClassField = $subClassFieldValue;
	}

	public function getSubClassField() {
		return $this->subClassField;
	}

	public static function newFromJsonArray( JsonUnserializer $unserializer, array $json ) {
		return new self( $json['super_class_field'], $json['sub_class_field'] );
	}

	protected function toJsonArray(): array {
		return parent::toJsonArray() + [
			'sub_class_field' => $this->getSubClassField()
		];
	}
}
