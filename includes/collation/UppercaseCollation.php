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
 * @since 1.16.3
 *
 * @file
 */

class UppercaseCollation extends Collation {

	private $lang;

	public function __construct() {
		// Get a language object so that we can use the generic UTF-8 uppercase
		// function there
		$this->lang = Language::factory( 'en' );
	}

	public function getSortKey( $string ) {
		return $this->lang->uc( $string );
	}

	public function getFirstLetter( $string ) {
		if ( $string[0] == "\0" ) {
			$string = substr( $string, 1 );
		}
		return $this->lang->ucfirst( $this->lang->firstChar( $string ) );
	}

}
