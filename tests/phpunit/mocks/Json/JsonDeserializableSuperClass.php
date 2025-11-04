<?php

namespace MediaWiki\Tests\Mocks\Json;

use MediaWiki\Json\JsonDeserializable;
use MediaWiki\Json\JsonDeserializableTrait;
use MediaWiki\Json\JsonDeserializer;

/**
 * Testing class for JsonDeserializer unit tests.
 */
class JsonDeserializableSuperClass implements JsonDeserializable {
	use JsonDeserializableTrait;

	/** @var mixed */
	private $superClassField;

	/**
	 * @param mixed $superClassFieldValue
	 */
	public function __construct( $superClassFieldValue ) {
		$this->superClassField = $superClassFieldValue;
	}

	/**
	 * @return mixed
	 */
	public function getSuperClassField() {
		return $this->superClassField;
	}

	public static function newFromJsonArray( JsonDeserializer $deserializer, array $json ): self {
		return new self( $json['super_class_field'] );
	}

	protected function toJsonArray(): array {
		return [
			'super_class_field' => $this->getSuperClassField()
		];
	}
}
