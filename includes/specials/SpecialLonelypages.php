<?php
/**
 * Implements Special:Lonelypaages
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
 * A special page looking for articles with no article linking to them,
 * thus being lonely.
 *
 * @ingroup SpecialPage
 */
class LonelyPagesPage extends PageQueryPage {
	function __construct( $name = 'Lonelypages' ) {
		parent::__construct( $name );
	}

	function getPageHeader() {
		return $this->msg( 'lonelypagestext' )->parseAsBlock();
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		$tables = array( 'page', 'pagelinks', 'templatelinks' );
		$conds = array(
			'pl_namespace IS NULL',
			'page_namespace' => MWNamespace::getContentNamespaces(),
			'page_is_redirect' => 0,
			'tl_namespace IS NULL'
		);
		$joinConds = array(
			'pagelinks' => array(
				'LEFT JOIN', array(
					'pl_namespace = page_namespace',
					'pl_title = page_title'
				)
			),
			'templatelinks' => array(
				'LEFT JOIN', array(
					'tl_namespace = page_namespace',
					'tl_title = page_title'
				)
			)
		);

		// Allow extensions to modify the query
		wfRunHooks( 'LonelyPagesQuery', array( &$tables, &$conds, &$joinConds ) );

		return array(
			'tables' => $tables,
			'fields' => array(
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_title'
			),
			'conds' => $conds,
			'join_conds' => $joinConds
		);
	}

	function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort in MySQL 5
		if ( count( MWNamespace::getContentNamespaces() ) > 1 ) {
			return array( 'page_namespace', 'page_title' );
		} else {
			return array( 'page_title' );
		}
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
