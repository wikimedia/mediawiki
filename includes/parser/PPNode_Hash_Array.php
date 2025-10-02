<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use LogicException;
use Stringable;

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Array implements Stringable, PPNode {

	/** @var array */
	public $value;

	/**
	 * @param array $value
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	public function __toString() {
		return var_export( $this, true );
	}

	/** @inheritDoc */
	public function getLength() {
		return count( $this->value );
	}

	/** @inheritDoc */
	public function item( $i ) {
		return $this->value[$i];
	}

	/** @inheritDoc */
	public function getName() {
		return '#nodelist';
	}

	/** @inheritDoc */
	public function getNextSibling() {
		return false;
	}

	/** @inheritDoc */
	public function getChildren() {
		return false;
	}

	/** @inheritDoc */
	public function getFirstChild() {
		return false;
	}

	/** @inheritDoc */
	public function getChildrenOfType( $name ) {
		return false;
	}

	/** @inheritDoc */
	public function splitArg() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( __METHOD__ . ': not supported' );
	}

	/** @inheritDoc */
	public function splitExt() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( __METHOD__ . ': not supported' );
	}

	/** @inheritDoc */
	public function splitHeading() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( __METHOD__ . ': not supported' );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( PPNode_Hash_Array::class, 'PPNode_Hash_Array' );
