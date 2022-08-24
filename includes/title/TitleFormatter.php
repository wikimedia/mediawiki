<?php
/**
 * A title formatter service for MediaWiki.
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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;

/**
 * A title formatter service for MediaWiki.
 *
 * This is designed to encapsulate knowledge about conventions for the title
 * forms to be used in the database, in urls, in wikitext, etc.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
 */
interface TitleFormatter {
	/**
	 * Returns the title formatted for display.
	 * Per default, this includes the namespace but not the fragment.
	 *
	 * @note Normalization is applied if $title is not in TitleValue::TITLE_FORM.
	 *
	 * @param int|bool $namespace The namespace ID (or false, if the namespace should be ignored)
	 * @param string $text The page title
	 * @param string $fragment The fragment name (may be empty).
	 * @param string $interwiki The interwiki prefix (may be empty).
	 *
	 * @return string
	 */
	public function formatTitle( $namespace, $text, $fragment = '', $interwiki = '' );

	/**
	 * Returns the title text formatted for display, without namespace or fragment.
	 *
	 * @param LinkTarget|PageReference $title The title to format
	 *
	 * @return string
	 */
	public function getText( $title );

	/**
	 * Returns the title formatted for display, including the namespace name.
	 *
	 * @param LinkTarget|PageReference $title The title to format
	 *
	 * @return string
	 */
	public function getPrefixedText( $title );

	/**
	 * Return the title in prefixed database key form, with interwiki
	 * and namespace.
	 *
	 * @since 1.27
	 *
	 * @param LinkTarget|PageReference $target
	 *
	 * @return string
	 */
	public function getPrefixedDBkey( $target );

	/**
	 * Returns the title formatted for display, with namespace and fragment.
	 *
	 * @param LinkTarget|PageReference $title The title to format
	 *
	 * @return string
	 */
	public function getFullText( $title );

	/**
	 * Returns the name of the namespace for the given title.
	 *
	 * @note This must take into account gender sensitive namespace names.
	 * @todo Move this to a separate interface
	 *
	 * @param int $namespace
	 * @param string $text
	 *
	 * @throws InvalidArgumentException
	 * @return string Namespace name with underscores (not spaces), e.g. 'User_talk'
	 */
	public function getNamespaceName( $namespace, $text );
}
