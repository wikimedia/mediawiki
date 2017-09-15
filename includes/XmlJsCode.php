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
 */

/**
 * A wrapper class which causes Xml::encodeJsVar() and Xml::encodeJsCall() to
 * interpret a given string as being a JavaScript expression, instead of string
 * data.
 *
 * @par Example:
 * @code
 *     Xml::encodeJsVar( new XmlJsCode( 'a + b' ) );
 * @endcode
 *
 * This returns "a + b".
 *
 * @note As of 1.21, XmlJsCode objects cannot be nested inside objects or arrays. The sole
 *       exception is the $args argument to Xml::encodeJsCall() because Xml::encodeJsVar() is
 *       called for each individual element in that array.
 *
 * @since 1.17
 */
class XmlJsCode {
	public $value;

	function __construct( $value ) {
		$this->value = $value;
	}
}
