<?php
/**
 * A title parser service for %MediaWiki.
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
 * A title parser service for %MediaWiki.
 *
 * This is designed to encapsulate knowledge about conventions for the title
 * forms to be used in the database, in urls, in wikitext, etc.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
 */
interface TitleParser {
	/**
	 * Parses the given text and constructs a TitleValue. Normalization
	 * is applied according to the rules appropriate for the form specified by $form.
	 *
	 * @note this only parses local page links, interwiki-prefixes etc. are not considered!
	 *
	 * @param string $text The text to parse
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 *
	 * @throws MalformedTitleException If the text is not a valid representation of a page title.
	 * @return TitleValue
	 */
	public function parseTitle( $text, $defaultNamespace );
}
