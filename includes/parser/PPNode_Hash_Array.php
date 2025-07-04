<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
