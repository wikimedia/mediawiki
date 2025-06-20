<?php

namespace MediaWiki\Tests\Json;

use MediaWiki\Json\JsonDeserializableTrait;
use MediaWiki\Json\JsonDeserializer;

/**
 * Testing class for JsonDeserializer unit tests.
 */
class JsonDeserializableSubClass extends JsonDeserializableSuperClass {
	use JsonDeserializableTrait;

	/** @var mixed */
	private $subClassField;

	/**
	 * @param mixed $superClassFieldValue
	 * @param mixed $subClassFieldValue
	 */
	public function __construct( $superClassFieldValue, $subClassFieldValue ) {
		parent::__construct( $superClassFieldValue );
		$this->subClassField = $subClassFieldValue;
	}

	/**
	 * @return mixed
	 */
	public function getSubClassField() {
		return $this->subClassField;
	}

	public static function newFromJsonArray( JsonDeserializer $deserializer, array $json ): self {
		return new self( $json['super_class_field'], $json['sub_class_field'] );
	}

	protected function toJsonArray(): array {
		return parent::toJsonArray() + [
			'sub_class_field' => $this->getSubClassField()
		];
	}
}

// This class_alias exists for testing alias support in JsonCodec and
// should not be removed.
class_alias( JsonDeserializableSubClass::class, 'MediaWiki\\Tests\\Json\\JsonDeserializableSubClassAlias' );
