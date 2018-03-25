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
 * @license GPL-2.0-or-later
 * @author Kunal Mehta <legoktm@member.fsf.org>
 */

/**
 * Marks HTML that shouldn't be escaped
 *
 * @since 1.28
 */
class HtmlArmor {

	/**
	 * @var string|null
	 */
	private $value;

	/**
	 * @param string|null $value
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Provide a string or HtmlArmor object
	 * and get safe HTML back
	 *
	 * @param string|HtmlArmor $input
	 * @return string|null safe for usage in HTML, or null
	 *         if the HtmlArmor instance was wrapping null.
	 */
	public static function getHtml( $input ) {
		if ( $input instanceof HtmlArmor ) {
			return $input->value;
		} else {
			return htmlspecialchars( $input, ENT_QUOTES );
		}
	}
}
