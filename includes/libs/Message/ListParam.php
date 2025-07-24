<?php

namespace Wikimedia\Message;

use InvalidArgumentException;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 *
 * @newable
 */
class ListParam extends MessageParam {
	use JsonCodecableTrait;

	private string $listType;

	/**
	 * @stable to call.
	 *
	 * @param string $listType One of the ListType constants.
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] $elements Values in the list.
	 *  Values that are not instances of MessageParam are wrapped using ParamType::TEXT.
	 */
	public function __construct( string $listType, array $elements ) {
		if ( !in_array( $listType, ListType::cases() ) ) {
			throw new InvalidArgumentException( '$listType must be one of the ListType constants' );
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
	 * @return string One of the ListType constants
	 */
	public function getListType(): string {
		return $this->listType;
	}

	public function dump(): string {
		$contents = '';
		foreach ( $this->value as $element ) {
			$contents .= $element->dump();
		}
		return "<$this->type listType=\"$this->listType\">$contents</$this->type>";
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
			$this->type => $this->value,
			'type' => $this->listType,
		];
	}

	public static function newFromJsonArray( array $json ): ListParam {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		if ( count( $json ) !== 2 || !isset( $json[ParamType::LIST] ) || !isset( $json['type'] ) ) {
			throw new InvalidArgumentException( 'Invalid format' );
		}
		return new self( $json['type'], $json[ParamType::LIST] );
	}
}
