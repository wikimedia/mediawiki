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
 * Expansion frame with custom arguments
 * @deprecated since 1.34, use PPCustomFrame_Hash
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPCustomFrame_DOM extends PPFrame_DOM {

	public $args;

	public function __construct( $preprocessor, $args ) {
		parent::__construct( $preprocessor );
		$this->args = $args;
	}

	public function __toString() {
		$s = 'cstmframe{';
		$first = true;
		foreach ( $this->args as $name => $value ) {
			if ( $first ) {
				$first = false;
			} else {
				$s .= ', ';
			}
			$s .= "\"$name\":\"" .
				str_replace( '"', '\\"', $value->__toString() ) . '"';
		}
		$s .= '}';
		return $s;
	}

	/**
	 * @return bool
	 */
	public function isEmpty() {
		return !count( $this->args );
	}

	/**
	 * @param int|string $index
	 * @return string|bool
	 */
	public function getArgument( $index ) {
		return $this->args[$index] ?? false;
	}

	public function getArguments() {
		return $this->args;
	}
}
