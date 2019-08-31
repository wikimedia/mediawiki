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
 * @deprecated since 1.34, use PPNode_Hash_{Tree,Text,Array,Attr}
 * @ingroup Parser
 * @phan-file-suppress PhanUndeclaredMethod
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_DOM implements PPNode {

	/**
	 * @var DOMElement|DOMNodeList
	 */
	public $node;
	public $xpath;

	public function __construct( $node, $xpath = false ) {
		$this->node = $node;
	}

	/**
	 * @return DOMXPath
	 */
	public function getXPath() {
		if ( $this->xpath === null ) {
			$this->xpath = new DOMXPath( $this->node->ownerDocument );
		}
		return $this->xpath;
	}

	public function __toString() {
		if ( $this->node instanceof DOMNodeList ) {
			$s = '';
			foreach ( $this->node as $node ) {
				$s .= $node->ownerDocument->saveXML( $node );
			}
		} else {
			$s = $this->node->ownerDocument->saveXML( $this->node );
		}
		return $s;
	}

	/**
	 * @return false|PPNode_DOM
	 */
	public function getChildren() {
		return $this->node->childNodes ? new self( $this->node->childNodes ) : false;
	}

	/**
	 * @return false|PPNode_DOM
	 */
	public function getFirstChild() {
		return $this->node->firstChild ? new self( $this->node->firstChild ) : false;
	}

	/**
	 * @return false|PPNode_DOM
	 */
	public function getNextSibling() {
		return $this->node->nextSibling ? new self( $this->node->nextSibling ) : false;
	}

	/**
	 * @param string $type
	 *
	 * @return false|PPNode_DOM
	 */
	public function getChildrenOfType( $type ) {
		return new self( $this->getXPath()->query( $type, $this->node ) );
	}

	/**
	 * @return int
	 */
	public function getLength() {
		if ( $this->node instanceof DOMNodeList ) {
			return $this->node->length;
		} else {
			return false;
		}
	}

	/**
	 * @param int $i
	 * @return bool|PPNode_DOM
	 */
	public function item( $i ) {
		$item = $this->node->item( $i );
		return $item ? new self( $item ) : false;
	}

	/**
	 * @return string
	 */
	public function getName() {
		if ( $this->node instanceof DOMNodeList ) {
			return '#nodelist';
		} else {
			return $this->node->nodeName;
		}
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
		$xpath = $this->getXPath();
		$names = $xpath->query( 'name', $this->node );
		$values = $xpath->query( 'value', $this->node );
		if ( !$names->length || !$values->length ) {
			throw new MWException( 'Invalid brace node passed to ' . __METHOD__ );
		}
		$name = $names->item( 0 );
		$index = $name->getAttribute( 'index' );
		return [
			'name' => new self( $name ),
			'index' => $index,
			'value' => new self( $values->item( 0 ) ) ];
	}

	/**
	 * Split an "<ext>" node into an associative array containing name, attr, inner and close
	 * All values in the resulting array are PPNodes. Inner and close are optional.
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitExt() {
		$xpath = $this->getXPath();
		$names = $xpath->query( 'name', $this->node );
		$attrs = $xpath->query( 'attr', $this->node );
		$inners = $xpath->query( 'inner', $this->node );
		$closes = $xpath->query( 'close', $this->node );
		if ( !$names->length || !$attrs->length ) {
			throw new MWException( 'Invalid ext node passed to ' . __METHOD__ );
		}
		$parts = [
			'name' => new self( $names->item( 0 ) ),
			'attr' => new self( $attrs->item( 0 ) ) ];
		if ( $inners->length ) {
			$parts['inner'] = new self( $inners->item( 0 ) );
		}
		if ( $closes->length ) {
			$parts['close'] = new self( $closes->item( 0 ) );
		}
		return $parts;
	}

	/**
	 * Split a "<h>" node
	 * @throws MWException
	 * @return array
	 */
	public function splitHeading() {
		if ( $this->getName() !== 'h' ) {
			throw new MWException( 'Invalid h node passed to ' . __METHOD__ );
		}
		return [
			'i' => $this->node->getAttribute( 'i' ),
			'level' => $this->node->getAttribute( 'level' ),
			'contents' => $this->getChildren()
		];
	}
}
