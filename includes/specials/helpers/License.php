<?php
/**
 * License selector for use on Special:Upload.
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
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license GPL-2.0-or-later
 */

/**
 * A License class for use on Special:Upload (represents a single type of license).
 */
class License {
	/** @var string */
	public $template;

	/** @var string */
	public $text;

	/**
	 * @param string $str
	 */
	public function __construct( $str ) {
		$str = $this->parse( $str );
		list( $this->template, $this->text ) = $this->split( $str );
	}

	/**
	 * @param string $str
	 * @return string
	 */
	protected function parse( $str ) {
		return $str;
	}

	/**
	 * @param string $str
	 * @return string[] Array with [template, text]
	 */
	protected function split( $str ) {
		list( $text, $template ) = explode( '|', strrev( $str ), 2 );
		return [ strrev( $template ), strrev( $text ) ];
	}
}
