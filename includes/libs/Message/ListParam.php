<?php

namespace Wikimedia\Message;

use InvalidArgumentException;
use Wikimedia\JsonCodec\Hint;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 *
 * @newable
 */
class ListParam extends MessageParam {

	// We can't use PHP type hint here without breaking deserialization of
	// old ListParams saved with PHP serialize().
	/** @var ListType */
	private $listType;

	/**
	 * @stable to call.
	 *
	 * @param string|ListType $listType One of the ListType constants.
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] $elements Values in the list.
	 *  Values that are not instances of MessageParam are wrapped using ParamType::TEXT.
	 */
	public function __construct( string|ListType $listType, array $elements ) {
		if ( is_string( $listType ) ) {
			wfDeprecated( __METHOD__ . ' with string listType', '1.45' );
			$listType = ListType::from( $listType );
		}
		$this->type = ParamType::LIST;
		$this->listType = $listType;
		$this->value = [];
		foreach ( $elements as $element ) {
			if ( $element instanceof MessageParam ) {
				$this->value[] = $element;
			} else {
				$this->value[] = new ScalarParam( ParamType::TEXT, $element );
			}
		}
	}

	/**
	 * Get the type of the list
	 *
	 * @return ListType One of the ListType constants
	 */
	public function getListType(): ListType {
		return $this->listType;
	}

	public function dump(): string {
		$contents = '';
		foreach ( $this->value as $element ) {
			$contents .= $element->dump();
		}
		return "<{$this->type->value} listType=\"{$this->listType->value}\">$contents</{$this->type->value}>";
	}

	public function isSameAs( MessageParam $mp ): bool {
		return $mp instanceof ListParam &&
			$this->listType === $mp->listType &&
			count( $this->value ) === count( $mp->value ) &&
			array_all(
				$this->value,
				static fn ( $v, $k ) => $v->isSameAs( $mp->value[$k] )
			);
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return [
			$this->type->value => array_map(
				/**
				 * Serialize trivial parameters as scalar values to minimize the footprint. Full
				 * round-trip compatibility is guaranteed via the constructor.
				 */
				static fn ( $p ) => $p->getType() === ParamType::TEXT ? $p->getValue() : $p,
				$this->value
			),
			'type' => $this->listType->value,
		];
	}

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ) {
		// Reduce serialization overhead by eliminating the type information
		// when the list consists of MessageParam instances
		if ( $keyName === ParamType::LIST->value ) {
			return Hint::build(
				MessageParam::class, Hint::INHERITED,
				Hint::LIST, Hint::USE_SQUARE
			);
		}
		return null;
	}

	public static function newFromJsonArray( array $json ): ListParam {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		if ( count( $json ) !== 2 || !isset( $json[ParamType::LIST->value] ) || !isset( $json['type'] ) ) {
			throw new InvalidArgumentException( 'Invalid format' );
		}
		return new self( ListType::from( $json['type'] ), $json[ParamType::LIST->value] );
	}

	public function __wakeup(): void {
		parent::__wakeup();
		// Backward-compatibility for PHP serialization:
		// Fixup $type after deserialization
		if ( is_string( $this->listType ) ) {
			$this->listType = ListType::from( $this->listType );
		}
	}
}
