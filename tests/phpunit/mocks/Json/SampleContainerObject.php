<?php
namespace MediaWiki\Tests\Mocks\Json;

use stdClass;
use Wikimedia\Assert\Assert;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Sample container object which uses JsonCodecableTrait to directly implement
 * serialization/deserialization and uses class hints.
 */
class SampleContainerObject implements JsonCodecable {
	use JsonCodecableTrait;

	/** @var mixed */
	public $contents;

	/**
	 * Create a new SampleContainerObject which stores $contents
	 * @param mixed $contents
	 */
	public function __construct( $contents ) {
		$this->contents = $contents;
	}

	// Implement JsonCodecable using the JsonCodecableTrait

	/** @inheritDoc */
	public function toJsonArray(): array {
		return [
			'contents' => $this->contents,
			// Note that json array keys need not correspond to property names
			'test' => (object)[],
			// Test "array of" hints as well as regular hints
			'array' => [ $this->contents ],
		];
	}

	/** @inheritDoc */
	public static function newFromJsonArray( array $json ): SampleContainerObject {
		Assert::invariant(
			get_class( $json['test'] ) === stdClass::class &&
			count( get_object_vars( $json['test'] ) ) === 0,
			"Ensure that the 'test' key is restored correctly"
		);
		Assert::invariant(
			is_array( $json['array'] ) && count( $json['array'] ) === 1,
			"Ensure that the 'array' key is restored correctly"
		);
		return new SampleContainerObject( $json['contents'] );
	}

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ): ?string {
		if ( $keyName === 'contents' ) {
			// Hint that the contained value is a SampleObject. It might be!
			return SampleObject::class;
		} elseif ( $keyName === 'array' ) {
			// Hint that the contained value is a *array of* SampleObject.
			// It might be!
			return SampleObject::class . '[]';
		} elseif ( $keyName === 'test' ) {
			// This hint will always be correct; note that this is a key
			// name not a property of SampleContainerObject
			return stdClass::class;
		}
		return null;
	}
}
