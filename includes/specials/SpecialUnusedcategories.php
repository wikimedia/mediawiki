<?php
/**
 * Implements Special:Unusedcategories
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
 * @ingroup SpecialPage
 */
class UnusedCategoriesPage extends QueryPage {

	function isExpensive() { return true; }

	function __construct( $name = 'Unusedcategories' ) {
		parent::__construct( $name );
	}

	function getPageHeader() {
		return $this->msg( 'unusedcategoriestext' )->parseAsBlock();
	}

	function getQueryInfo() {
		return array (
			'tables' => array ( 'page', 'categorylinks' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'page_title AS value' ),
			'conds' => array ( 'cl_from IS NULL',
					'page_namespace' => NS_CATEGORY,
					'page_is_redirect' => 0 ),
			'join_conds' => array ( 'categorylinks' => array (
					'LEFT JOIN', 'cl_to = page_title' ) )
		);
	}

	/**
	 * A should come before Z (bug 30907)
	 */
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( NS_CATEGORY, $result->title );
		return Linker::link( $title, htmlspecialchars( $title->getText() ) );
	}
}
