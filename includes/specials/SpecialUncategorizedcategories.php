<?php
/**
 * Implements Special:Uncategorizedcategories
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
 */

/**
 * A special page that lists uncategorized categories
 *
 * @ingroup SpecialPage
 */
class UncategorizedCategoriesPage extends UncategorizedPagesPage {
	function __construct( $name = 'Uncategorizedcategories' ) {
		parent::__construct( $name );
		$this->requestedNamespace = NS_CATEGORY;
	}

	/**
	 * Formats the result
	 * @param $skin The current skin
	 * @param $result The query result
	 * @return string The category link
	 */
	function formatResult ( $skin, $result ) {
		$title = Title::makeTitle( NS_CATEGORY, $result->title );
		$text = $title->getText();

		return Linker::linkKnown( $title, htmlspecialchars( $text ) );
	}
}
