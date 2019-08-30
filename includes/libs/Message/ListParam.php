<?php

namespace Wikimedia\Message;

/**
 * The class for list parameters
 */
class ListParam extends MessageParam {
	private $listType;

	/**
	 * @param string $listType One of the ListType constants:
	 *   - ListType::COMMA: A comma-separated list
	 *   - ListType::SEMICOLON: A semicolon-separated list
	 *   - ListType::PIPE: A pipe-separated list
	 *   - ListType::TEXT: A natural language list, separated by commas and
	 *     the word "and".
	 * @param (MessageParam|string)[] $elements An array of parameters
	 */
	public function __construct( $listType, array $elements ) {
		$this->type = ParamType::LIST;
		$this->listType = $listType;
		$this->value = [];
		foreach ( $elements as $element ) {
			if ( $element instanceof MessageParam ) {
				$this->value[] = $element;
			} elseif ( is_scalar( $element ) ) {
				$this->value[] = new TextParam( ParamType::TEXT, $element );
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
