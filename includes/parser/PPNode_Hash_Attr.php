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

use InvalidArgumentException;
use LogicException;
use Stringable;

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Attr implements Stringable, PPNode {

	/** @var string */
	public $name;
	/** @var string */
	public $value;
	/** @var array */
	private $store;
	/** @var int */
	private $index;

	/**
	 * Construct an object using the data from $store[$index]. The rest of the
	 * store array can be accessed via getNextSibling().
	 *
	 * @param array $store
	 * @param int $index
	 */
	public function __construct( array $store, $index ) {
		$descriptor = $store[$index];
		if ( $descriptor[PPNode_Hash_Tree::NAME][0] !== '@' ) {
			throw new InvalidArgumentException( __METHOD__ . ': invalid name in attribute descriptor' );
		}
		$this->name = substr( $descriptor[PPNode_Hash_Tree::NAME], 1 );
		$this->value = $descriptor[PPNode_Hash_Tree::CHILDREN][0];
		$this->store = $store;
		$this->index = $index;
	}

	public function __toString() {
		return "<@{$this->name}>" . htmlspecialchars( $this->value, ENT_COMPAT ) . "</@{$this->name}>";
	}

	/** @inheritDoc */
	public function getName() {
		return $this->name;
	}

	/** @inheritDoc */
	public function getNextSibling() {
		return PPNode_Hash_Tree::factory( $this->store, $this->index + 1 );
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
	public function getLength() {
		return false;
	}

	/** @inheritDoc */
	public function item( $i ) {
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
class_alias( PPNode_Hash_Attr::class, 'PPNode_Hash_Attr' );
