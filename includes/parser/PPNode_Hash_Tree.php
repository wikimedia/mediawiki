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

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Tree implements PPNode {

	public $name;

	/**
	 * The store array for children of this node. It is "raw" in the sense that
	 * nodes are two-element arrays ("descriptors") rather than PPNode_Hash_*
	 * objects.
	 */
	private $rawChildren;

	/**
	 * The store array for the siblings of this node, including this node itself.
	 */
	private $store;

	/**
	 * The index into $this->store which contains the descriptor of this node.
	 */
	private $index;

	/**
	 * The offset of the name within descriptors, used in some places for
	 * readability.
	 */
	public const NAME = 0;

	/**
	 * The offset of the child list within descriptors, used in some places for
	 * readability.
	 */
	public const CHILDREN = 1;

	/**
	 * Construct an object using the data from $store[$index]. The rest of the
	 * store array can be accessed via getNextSibling().
	 *
	 * @param array $store
	 * @param int $index
	 */
	public function __construct( array $store, $index ) {
		$this->store = $store;
		$this->index = $index;
		list( $this->name, $this->rawChildren ) = $this->store[$index];
	}

	/**
	 * Construct an appropriate PPNode_Hash_* object with a class that depends
	 * on what is at the relevant store index.
	 *
	 * @param array $store
	 * @param int $index
	 * @return PPNode_Hash_Tree|PPNode_Hash_Attr|PPNode_Hash_Text|false
	 * @throws MWException
	 */
	public static function factory( array $store, $index ) {
		if ( !isset( $store[$index] ) ) {
			return false;
		}

		$descriptor = $store[$index];
		if ( is_string( $descriptor ) ) {
			$class = PPNode_Hash_Text::class;
		} elseif ( is_array( $descriptor ) ) {
			if ( $descriptor[self::NAME][0] === '@' ) {
				$class = PPNode_Hash_Attr::class;
			} else {
				$class = self::class;
			}
		} else {
			throw new MWException( __METHOD__ . ': invalid node descriptor' );
		}
		return new $class( $store, $index );
	}

	/**
	 * Convert a node to XML, for debugging
	 * @return string
	 */
	public function __toString() {
		$inner = '';
		$attribs = '';
		for ( $node = $this->getFirstChild(); $node; $node = $node->getNextSibling() ) {
			if ( $node instanceof PPNode_Hash_Attr ) {
				$attribs .= ' ' . $node->name . '="' . htmlspecialchars( $node->value ) . '"';
			} else {
				$inner .= $node->__toString();
			}
		}
		if ( $inner === '' ) {
			return "<{$this->name}$attribs/>";
		} else {
			return "<{$this->name}$attribs>$inner</{$this->name}>";
		}
	}

	/**
	 * @return PPNode_Hash_Array
	 */
	public function getChildren() {
		$children = [];
		foreach ( $this->rawChildren as $i => $child ) {
			$children[] = self::factory( $this->rawChildren, $i );
		}
		return new PPNode_Hash_Array( $children );
	}

	/**
	 * Get the first child, or false if there is none. Note that this will
	 * return a temporary proxy object: different instances will be returned
	 * if this is called more than once on the same node.
	 *
	 * @return PPNode_Hash_Tree|PPNode_Hash_Attr|PPNode_Hash_Text|false
	 */
	public function getFirstChild() {
		if ( !isset( $this->rawChildren[0] ) ) {
			return false;
		} else {
			return self::factory( $this->rawChildren, 0 );
		}
	}

	/**
	 * Get the next sibling, or false if there is none. Note that this will
	 * return a temporary proxy object: different instances will be returned
	 * if this is called more than once on the same node.
	 *
	 * @return PPNode_Hash_Tree|PPNode_Hash_Attr|PPNode_Hash_Text|false
	 */
	public function getNextSibling() {
		return self::factory( $this->store, $this->index + 1 );
	}

	/**
	 * Get an array of the children with a given node name
	 *
	 * @param string $name
	 * @return PPNode_Hash_Array
	 */
	public function getChildrenOfType( $name ) {
		$children = [];
		foreach ( $this->rawChildren as $i => $child ) {
			if ( is_array( $child ) && $child[self::NAME] === $name ) {
				$children[] = self::factory( $this->rawChildren, $i );
			}
		}
		return new PPNode_Hash_Array( $children );
	}

	/**
	 * Get the raw child array. For internal use.
	 * @return array
	 */
	public function getRawChildren() {
		return $this->rawChildren;
	}

	/**
	 * @return bool
	 */
	public function getLength() {
		return false;
	}

	/**
	 * @param int $i
	 * @return bool
	 */
	public function item( $i ) {
		return false;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Split a "<part>" node into an associative array containing:
	 *  - name          PPNode name
	 *  - index         String index
	 *  - value         PPNode value
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitArg() {
		return self::splitRawArg( $this->rawChildren );
	}

	/**
	 * Like splitArg() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawArg( array $children ) {
		$bits = [];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			if ( $child[self::NAME] === 'name' ) {
				$bits['name'] = new self( $children, $i );
				if ( isset( $child[self::CHILDREN][0][self::NAME] )
					&& $child[self::CHILDREN][0][self::NAME] === '@index'
				) {
					$bits['index'] = $child[self::CHILDREN][0][self::CHILDREN][0];
				}
			} elseif ( $child[self::NAME] === 'value' ) {
				$bits['value'] = new self( $children, $i );
			}
		}

		if ( !isset( $bits['name'] ) ) {
			throw new MWException( 'Invalid brace node passed to ' . __METHOD__ );
		}
		if ( !isset( $bits['index'] ) ) {
			$bits['index'] = '';
		}
		return $bits;
	}

	/**
	 * Split an "<ext>" node into an associative array containing name, attr, inner and close
	 * All values in the resulting array are PPNodes. Inner and close are optional.
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitExt() {
		return self::splitRawExt( $this->rawChildren );
	}

	/**
	 * Like splitExt() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawExt( array $children ) {
		$bits = [];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			switch ( $child[self::NAME] ) {
				case 'name':
					$bits['name'] = new self( $children, $i );
					break;
				case 'attr':
					$bits['attr'] = new self( $children, $i );
					break;
				case 'inner':
					$bits['inner'] = new self( $children, $i );
					break;
				case 'close':
					$bits['close'] = new self( $children, $i );
					break;
			}
		}
		if ( !isset( $bits['name'] ) ) {
			throw new MWException( 'Invalid ext node passed to ' . __METHOD__ );
		}
		return $bits;
	}

	/**
	 * Split an "<h>" node
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitHeading() {
		if ( $this->name !== 'h' ) {
			throw new MWException( 'Invalid h node passed to ' . __METHOD__ );
		}
		return self::splitRawHeading( $this->rawChildren );
	}

	/**
	 * Like splitHeading() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawHeading( array $children ) {
		$bits = [];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			if ( $child[self::NAME] === '@i' ) {
				$bits['i'] = $child[self::CHILDREN][0];
			} elseif ( $child[self::NAME] === '@level' ) {
				$bits['level'] = $child[self::CHILDREN][0];
			}
		}
		if ( !isset( $bits['i'] ) ) {
			throw new MWException( 'Invalid h node passed to ' . __METHOD__ );
		}
		return $bits;
	}

	/**
	 * Split a "<template>" or "<tplarg>" node
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitTemplate() {
		return self::splitRawTemplate( $this->rawChildren );
	}

	/**
	 * Like splitTemplate() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawTemplate( array $children ) {
		$parts = [];
		$bits = [ 'lineStart' => '' ];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			switch ( $child[self::NAME] ) {
				case 'title':
					$bits['title'] = new self( $children, $i );
					break;
				case 'part':
					$parts[] = new self( $children, $i );
					break;
				case '@lineStart':
					$bits['lineStart'] = '1';
					break;
			}
		}
		if ( !isset( $bits['title'] ) ) {
			throw new MWException( 'Invalid node passed to ' . __METHOD__ );
		}
		$bits['parts'] = new PPNode_Hash_Array( $parts );
		return $bits;
	}
}
