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
class PPNode_Hash_Array implements PPNode {

	public $value;

	public function __construct( $value ) {
		$this->value = $value;
	}

	public function __toString() {
		return var_export( $this, true );
	}

	public function getLength() {
		return count( $this->value );
	}

	public function item( $i ) {
		return $this->value[$i];
	}

	public function getName() {
		return '#nodelist';
	}

	public function getNextSibling() {
		return false;
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
