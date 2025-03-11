<?php
/**
 * A codec for MediaWiki page titles.
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
 * @author Daniel Kinzler
 */

namespace MediaWiki\Title;

/**
 * A codec for MediaWiki page titles.
 *
 * @note Normalization and validation is applied while parsing, not when formatting.
 * It's possible to construct a TitleValue with an invalid title, and use MediaWikiTitleCodec
 * to generate an (invalid) title string from it. TitleValues should be constructed only
 * via parseTitle() or from a (semi)trusted source, such as the database.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
 * @deprecated since 1.44 use TitleParser, TitleFormatter
 */
class MediaWikiTitleCodec {
	/**
	 * Returns a simple regex that will match on characters and sequences invalid in titles.
	 * Note that this doesn't pick up many things that could be wrong with titles, but that
	 * replacing this regex with something valid will make many titles valid.
	 * Previously Title::getTitleInvalidRegex()
	 *
	 * @deprecated since 1.44 use TitleParser::getTitleInvalidRegex()
	 *
	 * @return string Regex string
	 * @since 1.25
	 */
	public static function getTitleInvalidRegex() {
		return TitleParser::getTitleInvalidRegex();
	}
}

/** @deprecated class alias since 1.41 */
class_alias( MediaWikiTitleCodec::class, 'MediaWikiTitleCodec' );
