<?php
/**
 * A TitleFormatter for generating titles for use in URLs.
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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * A TitleFormatter for generating titles for use in URLs.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class UrlTitleFormatter implements TitleFormatter {

	/**
	 * @var TitleFormatter
	 */
	protected $wikitextFormatter;

	/**
	 * @param Language $language the language object to use for localizing namespace names.
	 * @param bool $showNamespace whether the namespace should be present in the formatted title
	 */
	public function __construct( Language $language, $showNamespace = true ) {
		// static composition seems fine for now
		$this->wikitextFormatter = new WikitextTitleFormatter( $language, $showNamespace );
	}

	/**
	 * Returns the title as a string for use in URLs (without URL-encoding).
	 * Whether the namespace is included depends on the $showNamespace parameter
	 * passed to the constructor.
	 *
	 * @see TitleFormatter::format()
	 *
	 * @param TitleValue $title
	 * @return string
	 */
	public function format( TitleValue $title ) {
		$titleText = $this->wikitextFormatter->format( $title );

		return str_replace( ' ', '_', $titleText );
	}
}