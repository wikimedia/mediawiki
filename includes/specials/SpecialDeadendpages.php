<?php
/**
 * Implements Special:Deadenpages
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
 * A special page that list pages that contain no link to other pages
 *
 * @ingroup SpecialPage
 */
class DeadendPagesPage extends PageQueryPage {

	function __construct( $name = 'Deadendpages' ) {
		parent::__construct( $name );
	}

	function getPageHeader() {
		return wfMsgExt( 'deadendpagestext', array( 'parse' ) );
	}

	/**
	 * LEFT JOIN is expensive
	 *
	 * @return true
	 */
	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false; 
	}

	/**
	 * @return false
	 */
	function sortDescending() {
		return false;
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'page', 'pagelinks' ),
			'fields' => array( 'page_namespace AS namespace',
					'page_title AS title',
					'page_title AS value'
			),
			'conds' => array( 'pl_from IS NULL',
					'page_namespace' => MWNamespace::getContentNamespaces(),
					'page_is_redirect' => 0
			),
			'join_conds' => array( 'pagelinks' => array( 'LEFT JOIN', array(
					'page_id=pl_from'
			) ) )
		);
	}
	
	function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort
		if( count( MWNamespace::getContentNamespaces() ) > 1 ) {
			return array( 'page_namespace', 'page_title' );
		} else {
			return array( 'page_title' );
		}
	}
}
