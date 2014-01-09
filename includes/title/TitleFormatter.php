<?php
/**
 * A title formatter service for %MediaWiki.
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
 * A title formatter service for MediaWiki.
 *
 * This is designed to encapsulate knowledge about conventions for the title
 * forms to be used in the database, in urls, in wikitext, etc.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
interface TitleFormatter {

	/**
	 * Show the "base" title only, that is, the title text without namespace or section.
	 */
	const INCLUDE_BASE = 0;

	/**
	 * Include the title's namespace in the result.
	 * This can be used as a bit mask.
	 */
	const INCLUDE_NAMESPACE = 1;

	/**
	 * Include the title's section in the result.
	 * This can be used as a bit mask.
	 */
	const INCLUDE_SECTION = 2;

	/**
	 * Include all information (like namespace and section) in the result.
	 * This can be used as a bit mask.
	 */
	const INCLUDE_ALL = 0xFF;

	/**
	 * Returns a TitleValue normalized for use in the database.
	 *
	 * @param TitleValue $title the title to normalize.
	 *
	 * @throws InvalidArgumentException if $title could not be normalized
	 * @return TitleValue
	 */
	public function normalizeForDisplay( TitleValue $title );

	/**
	 * Returns the title formatted for display.
	 * Per default, this includes the namespace but not the section.
	 *
	 * @note Normalization is applied if $title is not in TitleValue::TITLE_FORM.
	 *
	 * @param TitleValue $title the title to format
	 * @param int $parts which parts to show, use the INCLUDE_XXX constants.
	 *
	 * @throws InvalidArgumentException if $title is not valid
	 * @return string
	 */
	public function formatForDisplay( TitleValue $title, $parts = self::INCLUDE_NAMESPACE );

	/**
	 * Returns a TitleValue normalized for use in the database.
	 *
	 * @param TitleValue $title the title to normalize.
	 *
	 * @throws InvalidArgumentException if $title could not be normalized
	 * @return TitleValue
	 */
	public function normalizeForDatabase( TitleValue $title );

	/**
	 * Returns the title formatted for use in the database.
	 * Per default, this does not include namespace or section.
	 *
	 * @note Normalization is applied if $title is not in TitleValue::DBKEY_FORM.
	 *
	 * @param TitleValue $title the title to format.
	 * @param int $parts which parts to show, use the INCLUDE_XXX constants.
	 *
	 * @throws InvalidArgumentException if $title is not valid
	 * @return string
	 */
	public function formatForDatabase( TitleValue $title, $parts = self::INCLUDE_BASE );

	/**
	 * Returns the name of the namespace of the given title.
	 * @note This takes into account gender sensitive namespace names.
	 *
	 * @param TitleValue $title
	 *
	 * @return String
	 */
	public function getNamespaceName( TitleValue $title );
}
