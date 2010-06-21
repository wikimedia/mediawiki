<?php
/**
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
 */

/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
 */
class UnusedCategoriesPage extends QueryPage {

	function isExpensive() { return true; }

	function getName() {
		return 'Unusedcategories';
	}

	function getPageHeader() {
		return wfMsgExt( 'unusedcategoriestext', array( 'parse' ) );
	}

	function getSQL() {
		$NScat = NS_CATEGORY;
		$dbr = wfGetDB( DB_SLAVE );
		list( $categorylinks, $page ) = $dbr->tableNamesN( 'categorylinks', 'page' );
		return "SELECT 'Unusedcategories' as type,
				{$NScat} as namespace, page_title as title, page_title as value
				FROM $page
				LEFT JOIN $categorylinks ON page_title=cl_to
				WHERE cl_from IS NULL
				AND page_namespace = {$NScat}
				AND page_is_redirect = 0";
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( NS_CATEGORY, $result->title );
		return $skin->link( $title, $title->getText() );
	}
}

/** constructor */
function wfSpecialUnusedCategories() {
	list( $limit, $offset ) = wfCheckLimits();
	$uc = new UnusedCategoriesPage();
	return $uc->doQuery( $offset, $limit );
}
