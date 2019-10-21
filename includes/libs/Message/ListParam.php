<?php

namespace Wikimedia\Message;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are safely newable.
 */
class ListParam extends MessageParam {
	private $listType;

	/**
	 * @param string $listType One of the ListType constants.
	 * @param (MessageParam|MessageValue|string|int|float)[] $elements Values in the list.
	 *  Values that are not instances of MessageParam are wrapped using ParamType::TEXT.
	 */
	public function __construct( $listType, array $elements ) {
		$this->type = ParamType::LIST;
		$this->listType = $listType;
		$this->value = [];
		foreach ( $elements as $element ) {
			if ( $element instanceof MessageParam ) {
				$this->value[] = $element;
			} elseif ( is_scalar( $element ) || $element instanceof MessageValue ) {
				$this->value[] = new ScalarParam( ParamType::TEXT, $element );
			} else {
				throw new \InvalidArgumentException(
					'ListParam elements must be MessageParam or scalar' );
			}
		}
	}

	/**
	 * Get the type of the list
	 *
	 * @return string One of the ListType constants
	 */
	public function getListType() {
		return $this->listType;
	}

	public function dump() {
		$contents = '';
		foreach ( $this->value as $element ) {
			$contents .= $element->dump();
		}
		return "<{$this->type} listType=\"{$this->listType}\">$contents</{$this->type}>";
	}
}
