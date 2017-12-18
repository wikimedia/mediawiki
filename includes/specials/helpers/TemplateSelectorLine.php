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
 * @ingroup SpecialPage
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * Represents a line for use on Special:Upload license selector (represents a single type of
 * license).
 */
class TemplateSelectorLine {
	/** @var string */
	public $template;

	/** @var string */
	public $text;

	/**
	 * @param string $str
	 */
	public function __construct( $str ) {
		// the split method is quite crude, so we'll first attempt to parse the text
		// as a message, so some acceptable wikitext (e.g. [[link|]]) won't confuse
		// our splitting
		$str = $this->parse( $str );
		list( $this->template, $this->text ) = $this->split( $str );
	}

	/**
	 * @param string $str
	 * @return string
	 */
	protected function parse( $str ) {
		$msg = new RawMessage( $str );
		return $msg->parse();
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
