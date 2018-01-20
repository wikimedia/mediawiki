<?php
/**
 * Hash attr of preprocessor using PHP arrays
 *
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

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Attr implements PPNode {

	public $name, $value;
	private $store, $index;

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
			throw new MWException( __METHOD__.': invalid name in attribute descriptor' );
		}
		$this->name = substr( $descriptor[PPNode_Hash_Tree::NAME], 1 );
		$this->value = $descriptor[PPNode_Hash_Tree::CHILDREN][0];
		$this->store = $store;
		$this->index = $index;
	}

	public function __toString() {
		return "<@{$this->name}>" . htmlspecialchars( $this->value ) . "</@{$this->name}>";
	}

	public function getName() {
		return $this->name;
	}

	public function getNextSibling() {
		return PPNode_Hash_Tree::factory( $this->store, $this->index + 1 );
	}

	public function getChildren() {
		return false;
	}

	public function getFirstChild() {
		return false;
	}

	public function getChildrenOfType( $name ) {
		return false;
	}

	public function getLength() {
		return false;
	}

	public function item( $i ) {
		return false;
	}

	public function splitArg() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitExt() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitHeading() {
		throw new MWException( __METHOD__ . ': not supported' );
	}
}
